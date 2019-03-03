<?php
require_once("Main.php");
/**
* class Uoconnectaccount
* @category controller
*/
class Uoconnectaccount extends Main
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
        if ($this->session->userdata('userType') != 'Admin' && !in_array(500, $this->moduleAccess))
            redirect('main/loginPage', 'location');
        if ($this->session->userdata('loggedIn') != 1) exit();
        $data['body'] = 'uo_connect_account';
        $data['pageTitle'] = 'instagram connect account';
        $where['where'] = array('user_id' => $this->userId);
        $existingFbAccounts = $this->common->readData('instagram_account_info', $where);
        // echo "<pre>";
        // print_r($existingFbAccounts);
        // echo "</pre>";
        // die();
        $data['showConnectAccountBox'] = 1;
        if (!empty($existingFbAccounts)) {
        	$i = 0;
        	foreach ($existingFbAccounts as $value) {
                $existingFbAccountsInfo[$i]['userinfo_table_id'] = isset($value["id"]) ? $value["id"] : "";
                $existingFbAccountsInfo[$i]['igusername'] = isset($value["igusername"]) ? $value["igusername"] : "";
                $existingFbAccountsInfo[$i]['ig_full_name'] = isset($value["ig_full_name"]) ? $value["ig_full_name"] : "";
                $existingFbAccountsInfo[$i]['igpk'] = isset($value["igpk"]) ? $value["igpk"] : "";
                $existingFbAccountsInfo[$i]['ig_full_name'] = isset($value["ig_full_name"]) ? $value["ig_full_name"] : "";
                $existingFbAccountsInfo[$i]['profile_picture'] = isset($value["profile_picture"]) ? $value["profile_picture"] : "";
                $existingFbAccountsInfo[$i]['media_count'] = isset($value["media_count"]) ? $value["media_count"] : "";
                $existingFbAccountsInfo[$i]['follower_count'] = isset($value["follower_count"]) ? $value["follower_count"] : "";
                $existingFbAccountsInfo[$i]['following_count'] = isset($value["following_count"]) ? $value["following_count"] : "";
                $existingFbAccountsInfo[$i]['add_date'] = isset($value["add_date"]) ? $value["add_date"] : "";
                $i++;
        	}
        	$data['existingFbAccounts'] = $existingFbAccountsInfo;
        } else {
        	$data['existingFbAccounts'] = '0';
        }
        $this->_mainView($data);
    }
    public function postInsight($id="0")
    {
        if($id == 0) {
            redirect('main/loginPage', 'location');
        }
        $table_name = "instagram_account_info";
        $where['where'] = array('id' => $id);
        $instagramAccount = $this->common->readData($table_name, $where);
        $igusername = isset($instagramAccount[0]["igusername"]) ? $instagramAccount[0]["igusername"] : "";
        $igpassword = isset($instagramAccount[0]["igpassword"]) ? $instagramAccount[0]["igpassword"] : "";
        $igproxy = isset($instagramAccount[0]["igproxy"]) ? $instagramAccount[0]["igproxy"] : "";
        $this->load->library("shadowpost_uo_library");
        $accouuntInfoJson = $this->shadowpost_uo_library->getOwnUserFeed($igusername, $igpassword, $igproxy);
        $accouuntInfo = json_decode($accouuntInfoJson,true);
        $data['instagramAccount'] = $instagramAccount[0];
        $data['accouuntInfo'] = $accouuntInfo;
        $dataName = array(
            '0' => 'FOLLOWERS',
            '1' => 'FOLLOWS'
        );
        $dataValue = array(
            '0' => $instagramAccount[0]['follower_count'],
            '1' => $instagramAccount[0]['following_count']
        );
        $colorData = array (
            '0' => 'rgba(241, 148, 138,0.6)',
            '1' => 'rgba(155, 89, 182,0.6)',
            '2' => 'rgba(0, 255, 153,0.6)',
            '3' => 'rgba(255, 102, 204,0.6)',
            '4' => 'rgba(0, 153, 204,0.6)',
            '5' => 'rgba(0, 0, 102  ,0.6)'
        );
        $data['dataName'] = json_encode($dataName);
        $data['dataValue'] = json_encode($dataValue);
        $data['colorData'] = json_encode($colorData);
        
        $data['body'] = "insight/post_analyzer";
        $data['pageTitle'] = "Analyzer";
        $this->_mainView($data);
    }
    public function accountUpdate()
    {
        if ($this->session->userdata('loggedIn') != 1) {
            redirect('main/loginPage', 'location');
        }
        if (!$_POST) exit();
        $instagramAccountId = $_POST['id'];
        $table_name = "instagram_account_info";
        $where['where'] = array('id' => $instagramAccountId);
        $instagramAccount = $this->common->readData($table_name, $where, array("igusername","igpassword","igproxy"));
        $igusername = isset($instagramAccount[0]["igusername"]) ? $instagramAccount[0]["igusername"] : "";
        $igpassword = isset($instagramAccount[0]["igpassword"]) ? $instagramAccount[0]["igpassword"] : "";
        $igproxy = isset($instagramAccount[0]["igproxy"]) ? $instagramAccount[0]["igproxy"] : "";
        $this->load->library("shadowpost_uo_library");
        $accouuntInfoJson = $this->shadowpost_uo_library->getOwnInfo($igusername, $igpassword, $igproxy);
        $accouuntInfo = json_decode($accouuntInfoJson,true);
        if($accouuntInfo["status"] == "ok") {
            $username =  isset($accouuntInfo["user"]["username"]) ? $accouuntInfo["user"]["username"] : "";
            $full_name =  isset($accouuntInfo["user"]["full_name"]) ? $accouuntInfo["user"]["full_name"] : "";
            $profile_pic_url =  isset($accouuntInfo["user"]["profile_pic_url"]) ? $accouuntInfo["user"]["profile_pic_url"] : "";
            $pk =  isset($accouuntInfo["user"]["pk"]) ? $accouuntInfo["user"]["pk"] : "";
            $media_count =  isset($accouuntInfo["user"]["media_count"]) ? $accouuntInfo["user"]["media_count"] : "";
            $follower_count =  isset($accouuntInfo["user"]["follower_count"]) ? $accouuntInfo["user"]["follower_count"] : "";
            $following_count =  isset($accouuntInfo["user"]["following_count"]) ? $accouuntInfo["user"]["following_count"] : "";
            $is_business =  isset($accouuntInfo["user"]["is_business"]) ? $accouuntInfo["user"]["is_business"] : "";
            $instradata = array(
                'igusername' => $username,
                'ig_full_name' => $full_name,
                'profile_picture' => $profile_pic_url,
                'igpk' => $pk,
                'media_count' => $media_count,
                'follower_count' => $follower_count,            
                'following_count' => $following_count,
                'is_business' => $is_business,
            );
            $where = array('id' => $instagramAccountId);
            $this->common->updateData('instagram_account_info', $where, $instradata);
            $str = "Wow Update :) . Now you have {$follower_count} followers and {$media_count} media";
            $response = array();
            $response["message"] = $str;
            $response["count"] = $follower_count;
            echo json_encode($response);
        }
    }
    public function addInstagramAccount()
    {
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessAorbidden', 'location');
        }
    	if ($_POST) {
            $post = $_POST;
            foreach ($post as $key => $value) {
                $$key = $this->input->post($key);
            }
        }
        $this->load->library("shadowpost_uo_library");
        $accouuntInfoJson = $this->shadowpost_uo_library->getOwnInfo($igusername, $igpassword, $proxy);
        $accouuntInfo = json_decode($accouuntInfoJson,true);
        if($accouuntInfo["status"] == "ok") {
            $username =  isset($accouuntInfo["user"]["username"]) ? $accouuntInfo["user"]["username"] : "";
            $password =  isset($igpassword) ? $igpassword : "";
            $proxy =  isset($proxy) ? $proxy : "";
            $full_name =  isset($accouuntInfo["user"]["full_name"]) ? $accouuntInfo["user"]["full_name"] : "";
            $profile_pic_url =  isset($accouuntInfo["user"]["profile_pic_url"]) ? $accouuntInfo["user"]["profile_pic_url"] : "";
            $pk =  isset($accouuntInfo["user"]["pk"]) ? $accouuntInfo["user"]["pk"] : "";
            $media_count =  isset($accouuntInfo["user"]["media_count"]) ? $accouuntInfo["user"]["media_count"] : "";
            $follower_count =  isset($accouuntInfo["user"]["follower_count"]) ? $accouuntInfo["user"]["follower_count"] : "";
            $following_count =  isset($accouuntInfo["user"]["following_count"]) ? $accouuntInfo["user"]["following_count"] : "";
            $is_business =  isset($accouuntInfo["user"]["is_business"]) ? $accouuntInfo["user"]["is_business"] : "";
            
        
            $data = array(
                'user_id' => $this->userId,
                'igusername' => $igusername,
                'igpassword' => $igpassword,
                'igproxy' => $proxy,
                'ig_full_name' => $full_name,
                'profile_picture' => $profile_pic_url,
                'igpk' => $pk,
                'media_count' => $media_count,
                'follower_count' => $follower_count,
                'following_count' => $following_count,
                'is_business' => $is_business,
                'add_date' => date("Y-m-d h:i:s"),
                'status' => "1",
                'deleted' => '0'
            );
            if ($this->common->createData('instagram_account_info', $data)) {
                $return['status'] = 1;
                $return['message'] = "<div class='alert alert-success'>Your given information has been updated successfully.</div>";
            } else {
                $return['status'] = 0;
                $return['message'] = "<div class='alert alert-danger'>Something went wrong, please try again.</div>";
            }
        } else {
            $return['status'] = 0;
            $return['message'] = "<div class='alert alert-danger'>Something went wrong, please try with right username & password</div>";
        }
        echo json_encode($return);
    }
    public function deleteAccount()
    {
        $table_id = $this->input->post("user_table_id");
        $this->db->trans_start();
        $this->common->deleteData('instagram_account_info',array('id'=>$table_id));
        $autoPost = $this->common->readData('instagram_auto_post',array('where'=>array('page_group_user_id'=>$table_id,'page_or_group_or_user'=>'user')));
        if(!empty($autoPost)) {
            $this->common->deleteData('instagram_auto_post',array('page_group_user_id'=>$table_id));
        }
        $storyPollPost = $this->common->readData('instagram_story_poll_post',array('where'=>array('page_group_user_id'=>$table_id,'page_or_group_or_user'=>'user')));
        if(!empty($storyPollPost)) {
            $this->common->deleteData('instagram_story_poll_post',array('page_group_user_id'=>$table_id));
        }
        $storyPost = $this->common->readData('instagram_story_post',array('where'=>array('page_group_user_id'=>$table_id,'page_or_group_or_user'=>'user')));
        if(!empty($storyPost)) {
            $this->common->deleteData('instagram_story_post',array('page_group_user_id'=>$table_id));
        }
        
        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            echo "<div class='alert alert-danger text-center'>'".$this->lang->line("something went wrong, please try again.")."'</div>";
        }
        else
        {
            echo json_encode(array("success"=>1,"error"=>"<div class='alert alert-success text-center'>Your Account has been deleted successfully.</div>"));
        }
    }
}