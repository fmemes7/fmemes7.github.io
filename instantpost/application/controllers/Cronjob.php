<?php
require_once 'Main.php';
/**
 * class Corn Job.
 *
 * @category controller
 */
class Cronjob extends Main
{
	public $userId;
    /**
     * load constructor.
     *
     * @return void
     */
    
    public function __construct()
    {
        parent::__construct();
        $this->userId = $this->session->userdata('userId');
        $this->upload_path = realpath(APPPATH.'../upload');
    }
    /**
     * method to load get api.
     */
    public function index()
    {
        $this->getApi();
    }
    public function getApi()
    {
    	if ($this->session->userdata('loggedIn') != 1) {
    		redirect('main/loginPage', 'location');
    	}
    	if($this->session->userdata('userType') != 'Admin') {
    		redirect('main/loginPage', 'location');
    	}
    	$data['body'] = "api/cron_job";
        $data['pageTitle'] = 'Cron Job';
        $apiData=$this->common->readData("cron_job",array("where"=>array("user_id"=>$this->session->userdata("userId"))));
        $data["apiKey"]="";
        if(count($apiData)>0) $data["apiKey"]=$apiData[0]["api_key"];
        $this->_mainView($data);
    }
    public function getApiAction()
    {
    	if ($this->session->userdata('loggedIn') != 1) {
            redirect('main/login', 'location');
        }
        if ($this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        $apiKey = $this->apiKeyGenerator();
        if ($this->common->isExist('cron_job', ['api_key'=>$apiKey])) {
            $this->getApiAction();
        }
        $userId = $this->session->userdata('userId');
        if ($this->common->isExist('cron_job', ['user_id'=>$userId])) {
            $this->common->updateData('cron_job', ['user_id'=>$userId], ['api_key'=>$apiKey]);
        } else {
            $this->common->createData('cron_job', ['api_key'=>$apiKey, 'user_id'=>$userId]);
        }
        redirect('cronjob/index', 'location');
    }
    /**
     * method to api key check
     * @param  string
     * @return void
     */
    public function apiKeyCheck($api_key = "")
    {
        if ($api_key=="") {
            echo "API Key is required.";
            exit();
        }
        if (!$this->common->isExist("cron_job", array("api_key"=>$api_key))) {
            echo "API Key does not match with any user.";
            exit();
        }
        if (!$this->common->isExist("users", array("status"=>"1","deleted"=>"0","user_type"=>"Admin"))) {
            echo "API Key does not match with any authentic user.";
            exit();
        }
    }
    public function apiKeyGenerator()
    {
        if ($this->session->userdata('loggedIn') != 1) {
            redirect('main/loginPage', 'location');
        }
        if ($this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        $val = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789'), 0, 7);
        return $val;
    }
    public function getFacebookConfig($userId=0)
    {
        if($userId==0) {
        	return 0;
        }
        $getdata= $this->common->readData("instagram_account_info",array("where"=>array("id"=>$userId)),array("config_id"));
        $return_val = isset($getdata[0]["config_id"]) ? $getdata[0]["config_id"] : 0;
        return $return_val; 
       
    }
	public function igAutoPost($apiKey="")
    {
        $this->apiKeyCheck($apiKey);
        $where['where'] = ['posting_status'=>'0'];
        $post_info = $this->common->readData('instagram_auto_post', $where, $select = '', $join = '', $limit = 200, $start = 0, $order_by = 'schedule_time ASC');
        $database = [];
        $campaign_id_array = [];
        foreach ($post_info as $info) {
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            if ($time_zone) {
                date_default_timezone_set($time_zone);
            }
            $now_time = date('Y-m-d H:i:s');
            if (strtotime($now_time) < strtotime($schedule_time)) {
                continue;
            }
            $campaign_id_array[] = $info['id'];
        }
        if (empty($campaign_id_array)) {
            exit();
        }        
        $this->db->where_in('id', $campaign_id_array);
        $this->db->update('instagram_auto_post', ['posting_status'=>'1']);
        $config_id_database = [];
        foreach ($post_info as $info) {
            $campaign_id = $info['id'];
            if (!in_array($campaign_id, $campaign_id_array)) {
                continue;
            }

            $post_type = $info['post_type'];
            $page_group_user_id = $info['page_group_user_id'];
            $page_or_group_or_user = $info['page_or_group_or_user'];
            $user_id = $info['user_id'];
            $message = $info['message'];
            $image_url = $info['image_url'];
            $video_url = $info['video_url'];
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            $account_info = $this->common->readData("instagram_account_info",array("where_in"=>array("id"=>$page_group_user_id,"user_id"=>$user_id)));
            $igusername =  isset($account_info[0]["igusername"]) ? $account_info[0]["igusername"] : "";
            $igpassword =  isset($account_info[0]["igpassword"]) ? $account_info[0]["igpassword"] : "";            
            $igproxy =  isset($account_info[0]["igproxy"]) ? $account_info[0]["igproxy"] : "";
            $this->load->library("shadowpost_uo_library");
            $response = [];
            $error_msg = '';
            $error_db = "";
            if($post_type=="image_submit") {
                try {
                    $response = $this->shadowpost_uo_library->imagePost($igusername,$igpassword,$igproxy,$message,$image_url);
                } catch(Exception $e) {
                    $error_db = $e->getMessage();
                    $error_msg = $e->getMessage();
                    $return_val=array("status"=>"0","message"=>$error_msg);
                    echo json_encode($return_val);
                    exit();
                }
            } else {
                try {
                    $response = $this->shadowpost_uo_library->videoPost($igusername,$igpassword,$igproxy,$message,$video_url);
                } catch(Exception $e) {
                    $error_db = $e->getMessage();
                    $error_msg = "<i class='fa fa-remove'></i> ".$e->getMessage();
                    $return_val=array("status"=>"0","message"=>$error_msg);
                    echo json_encode($return_val);
                    exit();
                }           
            }
            $postInfo = json_decode($response,true);
            if($postInfo['status'] == "ok") {
                $object_id = $postInfo['upload_id'];
                $updateData=array (
                    'igpk'=>$postInfo['media']['user']['pk'],
                    'ig_full_name'=>$postInfo['media']['user']['full_name'],
                    'profile_picture'=>$postInfo['media']['user']['profile_pic_url']
                );                
                $this->common->updateData('instagram_account_info',array("igusername" => $igusername,"igpassword" => $igpassword),$updateData);
            }
            $post_url_value = "";
            if(isset($postInfo['media']['code']) && !empty($postInfo['media']['code'])){
                $post_url_value = "https://www.instagram.com/p/".$postInfo['media']['code'];
            }
            $update_data = ['posting_status'=>'2', 'post_id'=>$object_id, 'post_url'=>$post_url_value, 'error_mesage'=>$error_db, 'last_updated_at'=>date('Y-m-d H:i:s')];
            $this->common->updateData('instagram_auto_post', ['id'=>$campaign_id], $update_data);
            sleep(rand(1, 10));
        }
    }
    public function igStoryPost($apiKey="")
    {
        $this->apiKeyCheck($apiKey);
        $where['where'] = ['posting_status'=>'0'];
        $post_info = $this->common->readData('instagram_story_post', $where, $select = '', $join = '', $limit = 200, $start = 0, $order_by = 'schedule_time ASC');
        
        $database = [];
        $campaign_id_array = [];
        foreach ($post_info as $info) {
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            if ($time_zone) {
                date_default_timezone_set($time_zone);
            }
            $now_time = date('Y-m-d H:i:s');
            if (strtotime($now_time) < strtotime($schedule_time)) {
                continue;
            }
            $campaign_id_array[] = $info['id'];
        }
        if (empty($campaign_id_array)) {
            exit();
        }
        $this->db->where_in('id', $campaign_id_array);
        $this->db->update('instagram_story_post', ['posting_status'=>'1']);
        $config_id_database = [];
        foreach ($post_info as $info) {
            $campaign_id = $info['id'];
            if (!in_array($campaign_id, $campaign_id_array)) {
                continue;
            }

            $post_type = $info['post_type'];
            $page_group_user_id = $info['page_group_user_id'];
            $page_or_group_or_user = $info['page_or_group_or_user'];
            $user_id = $info['user_id'];
            $message = $info['message'];
            $image_url = $info['image_url'];
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            $account_info = $this->common->readData("instagram_account_info",array("where_in"=>array("id"=>$page_group_user_id,"user_id"=>$user_id)));
            $igusername =  isset($account_info[0]["igusername"]) ? $account_info[0]["igusername"] : "";
            $igpassword =  isset($account_info[0]["igpassword"]) ? $account_info[0]["igpassword"] : "";            
            $igproxy =  isset($account_info[0]["igproxy"]) ? $account_info[0]["igproxy"] : "";
            $this->load->library("shadowpost_uo_library");
            $response = [];
            $error_msg = '';
            $error_db = "";
            if($post_type=="image_submit") {
                try {
                    $response = $this->shadowpost_uo_library->storyPost($igusername,$igpassword,$igproxy,$message,$image_url);
                } catch(Exception $e) {
                    $error_db = $e->getMessage();
                    $error_msg = $e->getMessage();
                    $return_val=array("status"=>"0","message"=>$error_msg);
                    echo json_encode($return_val);
                    exit();
                }
            }
            $postInfo = json_decode($response,true);
            $object_id = "";
            if($postInfo['status'] == "ok") {
                $object_id = $postInfo['upload_id'];
                $updateData=array (
                    'igpk'=>$postInfo['media']['user']['pk'],
                    'ig_full_name'=>$postInfo['media']['user']['full_name'],
                    'profile_picture'=>$postInfo['media']['user']['profile_pic_url']
                );                
                $this->common->updateData('instagram_account_info',array("igusername" => $igusername,"igpassword" => $igpassword),$updateData);
            }
            $post_url_value = "";
            if(isset($postInfo['media']['code']) && !empty($postInfo['media']['code'])){
                $post_url_value = "https://www.instagram.com/p/".$postInfo['media']['code'];
            }
            $update_data = ['posting_status'=>'2', 'post_id'=>$object_id, 'post_url'=>$post_url_value, 'error_mesage'=>$error_db, 'last_updated_at'=>date('Y-m-d H:i:s')];
            $this->common->updateData('instagram_auto_post', ['id'=>$campaign_id], $update_data);
            sleep(rand(1, 10));
        }
    }
    public function igStoryPollPost($apiKey="")
    {
        $this->apiKeyCheck($apiKey);
        $where['where'] = ['posting_status'=>'0'];
        $post_info = $this->common->readData('instagram_story_poll_post', $where, $select = '', $join = '', $limit = 200, $start = 0, $order_by = 'schedule_time ASC');
        $database = [];
        $campaign_id_array = [];
        foreach ($post_info as $info) {
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            if ($time_zone) {
                date_default_timezone_set($time_zone);
            }
            $now_time = date('Y-m-d H:i:s');
            if (strtotime($now_time) < strtotime($schedule_time)) {
                continue;
            }
            $campaign_id_array[] = $info['id'];
        }
        if (empty($campaign_id_array)) {
            exit();
        }
        $config_id_database = [];
        foreach ($post_info as $info) {
            $campaign_id = $info['id'];
            if (!in_array($campaign_id, $campaign_id_array)) {
                continue;
            }
            $post_type = $info['post_type'];
            $page_group_user_id = $info['page_group_user_id'];
            $page_or_group_or_user = $info['page_or_group_or_user'];
            $user_id = $info['user_id'];
            $message = $info['message'];
            $image_url = $info['image_url'];
            $time_zone = $info['time_zone'];
            $schedule_time = $info['schedule_time'];
            $option_one = $info['option_one'];
            $option_two = $info['option_two'];
            $account_info = $this->common->readData("instagram_account_info",array("where_in"=>array("id"=>$page_group_user_id,"user_id"=>$user_id)));
            $igusername =  isset($account_info[0]["igusername"]) ? $account_info[0]["igusername"] : "";
            $igpassword =  isset($account_info[0]["igpassword"]) ? $account_info[0]["igpassword"] : "";            
            $igproxy =  isset($account_info[0]["igproxy"]) ? $account_info[0]["igproxy"] : "";
            $this->load->library("shadowpost_uo_library");
            $response = [];
            $error_msg = '';
            $error_db = "";
            if($post_type=="image_submit") {
                try {
                    $response = $this->shadowpost_uo_library->storyPollPost($igusername,$igpassword,$igproxy,$image_url,$option_one, $option_two);
                } catch(Exception $e) {
                    $error_db = $e->getMessage();
                    $error_msg = $e->getMessage();
                    $return_val=array("status"=>"0","message"=>$error_msg);
                    echo json_encode($return_val);
                    exit();
                }
            }
            $postInfo = json_decode($response,true);
            $object_id = "";
            if($postInfo['status'] == "ok") {
                $object_id = $postInfo['upload_id'];
            }
            $post_url_value = "";
            if(isset($postInfo['media']['code']) && !empty($postInfo['media']['code'])){
                $post_url_value = "https://www.instagram.com/p/".$postInfo['media']['code'];
            }
            $update_data = ['posting_status'=>'2', 'post_id'=>$object_id, 'post_url'=>$post_url_value, 'error_mesage'=>$error_db, 'last_updated_at'=>date('Y-m-d H:i:s')];
            $this->common->updateData('instagram_auto_post', ['id'=>$campaign_id], $update_data);
            sleep(rand(1, 10));
        }
    }
}