<?php
require_once("Main.php");
/**
* class Isconnectaccount
* @category controller
*/
class Isconnectaccount extends Main
{
	public $userId;
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('loggedIn') != 1)
        redirect('main/loginPage', 'location');
        $this->userId=$this->session->userdata('userId');
    }
    public function index()
    {
        if ($this->session->userdata('userType') != 'Admin' && !in_array(400, $this->moduleAccess))
            redirect('main/loginPage', 'location');
        if ($this->session->userdata("shadow_user_info") == 0 && $this->config->item("instagramBackupMode") == 1)
            redirect('isconfiguration/index', 'refresh');
        if ($this->session->userdata('loggedIn') != 1) exit();
        $this->load->library("shadowpost_library");
        $data['body'] = 'connect_account';
        $data['pageTitle'] = 'Facebook connect account';
        $redirectUrl = base_url() . "isconnectaccount/refreshTokenCallback";
        $loginButton = $this->shadowpost_library->loginAccessToken($redirectUrl);
        $data['loginButton'] = $loginButton;
        $where['where'] = array('user_id' => $this->userId);
        $existingFbAccounts = $this->common->readData('shadow_user_info', $where);
        $data['showConnectAccountBox'] = 1;
        if (!empty($existingFbAccounts)) {
        	$i = 0;
        	foreach ($existingFbAccounts as $value) {
        		$existingFbAccountsInfo[$i]['need_to_delete'] = $value['need_to_delete'];
        		if ($value['need_to_delete'] == '1') {
                    $showConnectAccountBox = 0;
                    $data['showConnectAccountBox'] = $showConnectAccountBox;
                }
                $existingFbAccountsInfo[$i]['fb_id'] = $value['fb_id'];
                $existingFbAccountsInfo[$i]['userinfo_table_id'] = $value['id'];
                $existingFbAccountsInfo[$i]['name'] = $value['name'];
                $existingFbAccountsInfo[$i]['email'] = $value['email'];
                $existingFbAccountsInfo[$i]['user_access_token'] = $value['access_token'];
                $validOrInvalid = $this->shadowpost_library->accessTokenValidityForUser($value['access_token']);
                if ($validOrInvalid) {
                    $existingFbAccountsInfo[$i]['validity'] = 'yes';
                } else {
                    $existingFbAccountsInfo[$i]['validity'] = 'no';
                }
                $where = array();
                $where['where'] = array('shadow_user_info_id' => $value['id']);
                $allPage = $this->common->readData('shadow_page_info', $where);
                $existingFbAccountsInfo[$i]['page_list'] = $allPage;
                if (!empty($allPage)) {
                    $existingFbAccountsInfo[$i]['total_pages'] = count($allPage);
                } else {
                	$existingFbAccountsInfo[$i]['total_pages'] = 0;
                }
                $allGroup = $this->common->readData('shadow_group_info',$where);
                $existingFbAccountsInfo[$i]['group_list'] = $allGroup;
                if(!empty($allGroup)) {
                    $existingFbAccountsInfo[$i]['total_groups'] = count($allGroup);                    
                } else {
                    $existingFbAccountsInfo[$i]['total_groups'] = 0;
                }
                $i++;
        	}
        	$data['existingFbAccounts'] = $existingFbAccountsInfo;
        } else {
        	$data['existingFbAccounts'] = '0';
        }
        $this->_mainView($data);
    }
    public function refreshTokenCallback()
    {
    	$this->load->library("shadowpost_library");
    	$id = $this->session->userdata("isSessionId");
    	$redirectUrl = base_url() . "isconnectaccount/refreshTokenCallback";
        $userInfo = array();
        $userInfo = $this->shadowpost_library->loginCallback($redirectUrl);
        if (isset($userInfo['error']) && $userInfo['error'] == '1') {
            $data['error'] = 1;
            $data['message'] = "<a href='" . base_url("isconnectaccount/index/") . "'> Something went wrong, please try again.</a>";
            $data['body'] = "error_login";
            $this->_mainView($data);
        } else {
        	$accessToken = $userInfo['accessToken'];
        	$permission = $this->shadowpost_library->debugAccessToken($accessToken);
        	$givenPermission = array();
        	if (isset($permission['data']['scopes'])) {
        		$permissionChecking = array();
        		$neededPermission = array('email','public_profile','user_posts','manage_pages','publish_pages','read_insights','pages_show_list','publish_to_groups','read_page_mailboxes');
        		$givenPermission = $permission['data']['scopes'];
        		$permissionChecking = array_intersect($neededPermission, $givenPermission);
        		if (empty($permissionChecking)) {
        			$text = "Sorry, You didn't confirm the request yet. Please login to your fb account and accept the request. for more";
                    $this->session->set_userdata('limitCross', $text);
                    redirect('isconnectaccount/index', 'location');
                    exit();
        		}
        	}
        	if (isset($accessToken)) {
        		$data = array(
                    'user_id' => $this->userId,
                    'config_id' => $id,
                    'access_token' => $accessToken,
                    'name' => isset($userInfo['name']) ? $userInfo['name'] : "",
                    'email' => isset($userInfo['email']) ? $userInfo['email'] : "",
                    'fb_id' => isset($userInfo['id']) ? $userInfo['id'] : "",
                    'add_date' => date('Y-m-d'),
                    'deleted' => '0'
                );
                $where = array();
                $where['where'] = array('user_id' => $this->userId, 'fb_id' => $userInfo['id']);
                $existOrNot = $this->common->readData('shadow_user_info', $where);
                if (empty($existOrNot)) {
                    $this->common->createData('shadow_user_info', $data);
                    $tableId = $this->db->insert_id();
                } else {
                   	$tableId = $existOrNot[0]['id'];
                    $where = array('user_id' => $this->userId, 'fb_id' => $userInfo['id']);
                    $this->common->updateData('shadow_user_info', $where, $data);
                }
                $this->session->set_userdata("shadowPostUserInfo", $tableId);
                $pageList = array();
                $pageList = $this->shadowpost_library->getPageList($accessToken);
                if (!empty($pageList)) {
                    foreach ($pageList as $page) {
                        $userId = $this->userId;
                        $pageId = $page['id'];
                        $pageCover = '';
                        if (isset($page['cover']['source'])) $pageCover = $page['cover']['source'];
                        $pageProfile = '';
                        if (isset($page['picture']['url'])) $pageProfile = $page['picture']['url'];
                        $pageName = '';
                        if (isset($page['name'])) $pageName = $page['name'];
                        $pageUsername = '';
                        if (isset($page['username'])) $pageUsername = $page['username'];
                        $pageAccessToken = '';
                        if (isset($page['access_token'])) $pageAccessToken = $page['access_token'];
                        $pageEmail = '';
                        if (isset($page['emails'][0])) $pageEmail = $page['emails'][0];
                        $data = array(
                            'user_id' => $userId,
                            'shadow_user_info_id' => $tableId,
                            'page_id' => $pageId,
                            'page_cover' => $pageCover,
                            'page_profile' => $pageProfile,
                            'page_name' => $pageName,
                            'username' => $pageUsername,
                            'page_access_token' => $pageAccessToken,
                            'page_email' => $pageEmail,
                            'add_date' => date('Y-m-d')
                        );
                        $where = array();
                        $where['where'] = array('shadow_user_info_id' => $tableId, 'page_id' => $page['id']);
                        $exist_or_not = $this->common->readData('shadow_page_info', $where);
                        if (empty($exist_or_not)) {
                            $this->common->createData('shadow_page_info', $data);
                        } else {
                            $where = array('shadow_user_info_id' => $tableId, 'page_id' => $page['id']);
                            $this->common->updateData('shadow_page_info', $where, $data);
                        }
                    }
                }
                $groupList = $this->shadowpost_library->getGroupList($accessToken);
                if (!empty($groupList)) {
                    foreach ($groupList as $group) {
                        if($group['administrator'] == "1") {
                            $user_id = $this->userId;
                            $group_access_token = $accessToken; // group uses user access token
                            $group_id = $group['id'];
                            $group_cover = '';
                            if (isset($group['cover']['source'])) $group_cover = $group['cover']['source'];
                            $group_profile = '';
                            if (isset($group['picture']['url'])) $group_profile = $group['picture']['url'];
                            $group_name = '';
                            if (isset($group['name'])) $group_name = $group['name'];
    
                            $data = array(
                                'user_id' => $user_id,
                                'shadow_user_info_id' => $tableId,
                                'group_id' => $group_id,
                                'group_cover' => $group_cover,
                                'group_profile' => $group_profile,
                                'group_name' => $group_name,
                                'group_access_token' => $group_access_token,
                                'add_date' => date('Y-m-d')
                            );
                            $where = array();
                            $where['where'] = array('shadow_user_info_id' => $tableId, 'group_id' => $group['id']);
                            $exist_or_not = $this->common->readData('shadow_group_info', $where);
    
                            if (empty($exist_or_not)) {
                                $this->common->createData('shadow_group_info', $data);
                            } else {
                                $where = array('shadow_user_info_id' => $tableId, 'group_id' => $page['id']);
                                $this->common->updateData('shadow_group_info', $where, $data);
                            }
                        }
                    }
                }
                $this->session->set_flashdata('successMessage', 1);
                redirect('isconnectaccount/index', 'location');
                exit();
        	} else {
        		$data['error'] = 1;
                $data['message'] = "<a href='" . base_url("isconnectaccount/index/") . "'> Something went wrong, please try again.</a>";
                $data['body'] = "error_login";
                $this->_mainView($data);
        	}
        }
    }

    public function enableDisable()
	{
		if($this->session->userdata('userType') != 'Admin' && !in_array(303,$this->moduleAccess))
        exit();
    	if(!$_POST) exit();
    	$this->load->library("shadowpost_library");
    	$instagramBusinessAccountId = $this->input->post('businessAccountId');
    	$enableDisable = $this->input->post('enableDisable');
    	$pageData = $this->common->readData("shadow_page_info",array("where"=>array("instagram_business_account_id"=>$instagramBusinessAccountId)));
    	$fbPageId = isset($pageData[0]["page_id"]) ? $pageData[0]["page_id"] : "";
        $pageAccessToken=isset($pageData[0]["page_access_token"]) ? $pageData[0]["page_access_token"] : "";
        $fbUserId = $pageData[0]["shadow_user_info_id"];
        $fbUserInfo = $this->common->readData('shadow_user_info',array('where'=>array('id'=>$fbUserId)));
        $this->shadowpost_library->appInitialize($fbUserInfo[0]['config_id']);
        if ($enableDisable=='enable') {
        	$alreadyEnabled = $this->common->readData('shadow_page_info',array('where'=>array('page_id'=>$fbPageId,'comment_enabled'=>'1')));
        	
        	if(!empty($alreadyEnabled)) {
                echo json_encode(array('success'=>0,'error'=>'This page is already enabled by other Admin.'));
                exit();
            }
            $response=$this->shadowpost_library->enableComment($fbPageId,$pageAccessToken);
            $output = $response;
            if($output['error'] == '')
            {
                $this->common->updateData("shadow_page_info",array("instagram_business_account_id"=>$instagramBusinessAccountId),array("comment_enabled"=>"1"));             
            } 
            echo json_encode($response);
        }
        if ($enableDisable == 'disable') {
        	$response=$this->shadowpost_library->disableComment($fbPageId,$pageAccessToken);
            $output = $response;
            if($output['error'] == '')
            {
                $this->common->updateData("shadow_page_info",array("instagram_business_account_id"=>$instagramBusinessAccountId),array("comment_enabled"=>"0"));
            }
            echo json_encode($response);
        }
	}
	public function isAccountUpdate()
	{
		if ($this->session->userdata('loggedIn') != 1) {
			redirect('main/loginPage', 'location');
		}
        
		if (!$_POST) exit();
        $instagramAccountId = $_POST['id'];
        $table_name = "shadow_page_info";
        $where['where'] = array('instagram_business_account_id' => $instagramAccountId);
        $instagramPageInfo = $this->common->readData($table_name, $where, "page_access_token");
        $pageAccessToken = $instagramPageInfo['0']['page_access_token'];
        $this->load->library("shadowpost_library");
        $isAccountInfo = $this->shadowpost_library->isAccountInfo($instagramAccountId, $pageAccessToken);
        $instradata = array(
            'is_followers_count' => isset($isAccountInfo['followers_count']) ? $isAccountInfo['followers_count'] : "",
            'is_follows_count' => isset($isAccountInfo['follows_count']) ? $isAccountInfo['follows_count'] : "",
            'is_media_count' => isset($isAccountInfo['media_count']) ? $isAccountInfo['media_count'] : "",
            'is_name' => isset($isAccountInfo['name']) ? $isAccountInfo['name'] : "",
            'is_profile_picture_url' => isset($isAccountInfo['profile_picture_url']) ? $isAccountInfo['profile_picture_url'] : "",
            'is_username' => isset($isAccountInfo['username']) ? $isAccountInfo['username'] : "",            
            'is_website' => isset($isAccountInfo['website']) ? $isAccountInfo['website'] : "",
            'is_biography' => isset($isAccountInfo['biography']) ? $isAccountInfo['biography'] : "",
        );
        $where = array('is_username' => $isAccountInfo['username']);
        // $this->common->updateData('instagram_account_info', $where, $instradata);
        // $str = $instagram_account_info['followers_count'];
        $str = "Wow Update :) . Now you have {$isAccountInfo['followers_count']} followers and {$isAccountInfo['media_count']} media";
        $response = array();
        $response["message"] = $str;
        $response["count"] = $isAccountInfo['followers_count'];
        echo json_encode($response);
	}
	public function userInsight($accountInfo = 0)
	{
	}
    public function facebookAccountSwitch()
    {
        if(!$_POST) exit();
        $id=$this->input->post("id");        
        $this->session->set_userdata("shadowPostUserInfo",$id); 
        $userData = $this->common->readData("shadow_user_info",array("where"=>array("id"=>$id,"user_id"=>$this->userId)));
        $configId = isset($userData[0]["config_id"]) ? $userData[0]["config_id"] : 0;
        $this->session->set_userdata("isSessionId",$configId);   
    }
    public function deletePageAction()
    {
        $table_id = $this->input->post('page_table_id');
        $data = ['deleted' => '1'];
        $this->common->updateData('shadow_page_info', ['id'=>$table_id], $data);
        echo json_encode(array("success"=>1,"error"=>"<div class='alert alert-success text-center'>Your page has been deleted successfully.</div>"));
    }
}
