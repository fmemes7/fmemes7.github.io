<?php
require_once("Main.php");
/**
* class Isconfiguration
* @category controller
*/
class Isconfiguration extends Main
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
        $this->fbAppForInstagramConfiguration();
    }
	public function fbAppForInstagramConfiguration()
	{  
		if ($this->session->userdata('userType') != 'Admin' && !in_array(300, $this->moduleAccess))
            redirect('main/loginPage', 'location');
        if ($this->session->userdata('userType') == "Member" && $this->config->item("instagramBackupMode") == 0) {
            redirect('main/loginPage', 'location');
        }
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('facebook_app');
        $crud->order_by('app_name');
        $crud->set_subject('API Settings for Instagram');
        $crud->required_fields('api_id', 'api_secret', 'status');
        $crud->columns('app_name', 'api_id', 'api_secret', 'status', 'validity');
        $crud->fields('app_name', 'api_id', 'api_secret', 'status');
        $crud->where('user_id', $this->session->userdata('userId'));
        $crud->callback_field('status', array($this, 'statusField'));
        $crud->callback_column('status', array($this, 'statusDisplay'));
        $crud->callback_column('validity', array($this, 'validityDisplay'));
        $crud->callback_after_insert(array($this, 'makeUpSetting'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_delete();        
        $totalRows = $this->common->countRow("facebook_app", array("where" => array('user_id' => $this->session->userdata('userId'))), $count = "id");
        $totalResult = $totalRows[0]['total_rows'];
        if ($this->session->userdata("userType") == "Member" && $totalResult > 0) {
            $crud->unset_add();
        }        
        $crud->display_as('validity', 'Token Validity');
        $crud->display_as('app_name', 'Facebook App Name');
        $crud->display_as('api_id', 'Facebook App ID');
        $crud->display_as('api_secret', 'Facebook App Secret');
        $crud->display_as('status', 'Status');        
        $imagesUrl = "<i class='fa fa-sign-in'></i>";
        $crud->add_action('Login', $imagesUrl, 'isconfiguration/isLogin');
        $output = $crud->render();
        $data['output'] = $output;
        $data['crud'] = 1;
        $data['pageTitle'] = 'Api Settings';
        $this->_mainView($data);
	}
	public function statusField($value, $row)
    {
        if ($value == '') {
            $value = 1;
        }
        return form_dropdown('status', array(0 => 'Inactive', 1 => 'Active'), $value, 'class="form-control" id="field-status"');
    }
    public function statusDisplay($value, $row)
    {
        if ($value == 1) {
            return "<span class='label label-success' title='Access Token : " . $row->user_access_token . "'>Active</sapn>";
        } else {
            return "<span class='label label-warning' title='Access Token : " . $row->user_access_token . "'>Inactive</sapn>";
        }
    }
    public function validityDisplay($value, $row)
    {
        $input_token = $row->user_access_token;
        if ($input_token == "" || $input_token == NULL)
            return "<span class='label label-warning' style='font-weight:normal'>Invalid</sapn>";
        $this->load->library("shadowpost_library");
        $url = "https://graph.facebook.com/debug_token?input_token={$input_token}&access_token={$input_token}";
        $result = $this->shadowpost_library->commonCurl($url);
        $result = json_decode($result, true);
        if (isset($result["data"]["is_valid"]) && $result["data"]["is_valid"]) {
            return "<span class='label label-success' style='font-weight:normal'>Valid</sapn>";
        } else {
            return "<span class='label label-warning' style='font-weight:normal'>Expired</sapn>";
        }
    }
    public function makeUpSetting($postArray, $primaryKey)
    {
        if ($this->session->userdata("userType") == "Admin") {
        	$useBy = "everyone";
        } else {
        	$useBy = "only_me";
        }
        $this->common->updateData("facebook_app", array('id' => $primaryKey), array("user_id" => $this->session->userdata("userId"), 'use_by' => $useBy));
        return true;
    }
    public function isLogin($id)
    {
        $this->session->set_userdata("isSessionId", $id);
        $this->load->library("shadowpost_library");
        $redirect_url = base_url() . "isconfiguration/loginCallback";
        $data['loginButton'] = $this->shadowpost_library->loginAccessToken($redirect_url);
        $data['body'] = 'admin_login';
        $data['pageTitle'] = 'Admin Login';
        $data['expiredOrNot'] = $this->shadowpost_library->accessTokenValidity();
        $this->_mainView($data);
    }
	public function loginCallback()
	{
        if ($this->session->userdata('loggedIn') != 1) {
            exit();
        }
        $this->load->library('shadowpost_library');
        $id = $this->session->userdata("isSessionId");
        $redirectUrl = base_url() . "isconfiguration/loginCallback/";
        $userInfo = $this->shadowpost_library->loginCallback($redirectUrl);
        if (isset($userInfo['error']) && $userInfo['error'] == '1') {
            $data['error'] = 1;
            $data['message'] = "<a href='" . base_url("isconfiguration/index/") . "'> Something went wrong, please try again.</a>";
            $data['body'] = "admin_login";
            $this->_mainView($data);
        } else {
            $accessToken = $userInfo['accessToken'];
            $where = array('id' => $id);
            $updateData = array('user_access_token' => $accessToken);
            if ($this->common->updateData('facebook_app', $where, $updateData)) {
                $data = array(
                    'user_id' => $this->userId,
                    'config_id' => $id,
                    'access_token' => $accessToken,
                    'name' => isset($userInfo['name']) ? $userInfo['name'] : "",
                    'email' => isset($userInfo['email']) ? $userInfo['email'] : "",
                    'fb_id' => isset($userInfo['id']) ? $userInfo['id'] : "",
                    'add_date' => date('Y-m-d')
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
                redirect('isconfiguration/index', 'location');
                exit();
            } else {
                $data['error'] = 1;
                $data['message'] = "<a href='" . base_url("isconfiguration/index/") . "'> Something went wrong, please try again.</a>";
                $data['body'] = "admin_login";
                $this->_mainView($data);
            }
        }
	}
}