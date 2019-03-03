<?php
require_once("Main.php"); // loading Main controller
class Storypollpost extends Main
{
	public $userId;
	public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('loggedIn') != 1) {
        	redirect('main/loginPage', 'location');
        }
          
        if($this->session->userdata('userType') != 'Admin' && !in_array(503,$this->moduleAccess)) {
        	redirect('main/loginPage', 'location'); 
        }
        $this->userId=$this->session->userdata('userId');
    }
    public function index()
    {
      $this->addStoryPost();
    }
    public function addStoryPost()
    {
    	$data['body'] = 'shadowpostig/story_poll_post/add_auto_post';
    	$data['pageTitle'] = 'Story Poll Post';
    	$data["time_zone"]= $this->_timeZones();
    	$data["userInfo"]=$this->common->readData("instagram_account_info",array("where"=>array("user_id"=>$this->userId)));
        $this->_mainView($data);
    }
    public function addStoryPollPostAction()
    {
        if(!$_POST) {
            exit();
        }
              
        $this->load->library("shadowpost_uo_library");
        // $this->load->library("shadowpost_library");
        $post=$_POST;
        foreach ($post as $key => $value) {
           $$key=$value;
           if(!is_array($value))
           $insert_data[$key]=$value;
        }
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
        // die();
        $insert_data["post_type"] = $insert_data["submit_post_hidden"];
        unset($insert_data["submit_post_hidden"]);
        unset($insert_data["schedule_type"]);
        $insert_data["user_id"] = $this->userId;        
        if(!isset($post_to_account) || !is_array($post_to_account)) {
            $post_to_account=array();
        }
        if($schedule_type=="now") {
            $insert_data["posting_status"] ='2';
        } else {
            $insert_data["posting_status"] ='0';
        }
        $insert_data_batch=array();
        $user_id_array=array($this->userId);
        $count=0;
        if(count($post_to_account)>0) {
            $account_info = $this->common->readData("instagram_account_info",array("where_in"=>array("id"=>$post_to_account,"user_id"=>$this->userId)));
            foreach ($account_info as $key => $value) {
                $igpassword =  isset($value["igpassword"]) ? $value["igpassword"] : ""; 
                $igusername =  isset($value["igusername"]) ? $value["igusername"] : "";
                $igproxy =  isset($value["igproxy"]) ? $value["igproxy"] : "";
                $igaccountid =  isset($value["id"]) ? $value["id"] : "";
                $insert_data_batch[$count]["page_or_group_or_user_name"] = isset($value["igusername"]) ? $value["igusername"] : "";
                $insert_data_batch[$count]=$insert_data;             
                $insert_data_batch[$count]["page_group_user_id"]=$igaccountid;
                $insert_data_batch[$count]["page_or_group_or_user"]="user";                
                $insert_data_batch[$count]["post_id"] = "";
                $insert_data_batch[$count]["post_url"] = "";
                $insert_data_batch[$count]["option_one"] = $option_one;
                $insert_data_batch[$count]["option_two"] = $option_two;
                if($schedule_type=="now") {
                    if($submit_post_hidden=="image_submit") {
                        try {
                            $response = $this->shadowpost_uo_library->storyPollPost($igusername,$igpassword,$igproxy,$image_url,$option_one, $option_two);
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
                        $error_db = "";
                    }
                    $error_db = "";
                    $object_id = "";
                    $share_access_token = $igpassword;
                    $insert_data_batch[$count]["post_id"]= $object_id;
                    $post_url_value = "";
                    if(isset($postInfo['media']['code']) && !empty($postInfo['media']['code'])){
                        $post_url_value = "https://www.instagram.com/p/".$postInfo['media']['code'];
                    }
                    $insert_data_batch[$count]["post_url"]= $post_url_value;
                    $insert_data_batch[$count]["error_mesage"]= $error_db;
                    $insert_data_batch[$count]["last_updated_at"]= date("Y-m-d H:i:s");
                    $this->common->createData("instagram_story_poll_post",$insert_data_batch[$count]);
                }
                $count++;
            }
        }
        if($schedule_type=="now") {
                $return_val=array("status"=>"1","message"=>"<i class='fa fa-check-circle'></i>  Instagram post has been performed successfully.");
        } else {
            if($this->db->insert_batch("instagram_story_poll_post",$insert_data_batch)) {
                $return_val=array("status"=>"1","message"=>"<i class='fa fa-check-circle'></i>  Instagram post campaign has been created successfully.");
            } else {
                $return_val=array("status"=>"0","message"=>"<i class='fa fa-remove'></i>  Something went wrong. Instagram post campaign has been failed.");
            }
        }
        echo json_encode($return_val);
    }
    public function uploadImageOnly()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            exit();
        }
        $ret = [];
        $output_dir = FCPATH.'upload/storypollpost';
        if (isset($_FILES['myfile'])) {
            $error = $_FILES['myfile']['error'];
            $post_fileName = $_FILES['myfile']['name'];
            $post_fileName_array = explode('.', $post_fileName);
            $ext = array_pop($post_fileName_array);
            $filename = implode('.', $post_fileName_array);
            $filename = 'image_'.$this->userId.'_'.time().substr(uniqid(mt_rand(), true), 0, 6).'.'.$ext;
            $allow="png|jpg|jpeg";
            $allow=explode('|', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
                die();
            }
            move_uploaded_file($_FILES['myfile']['tmp_name'], $output_dir.'/'.$filename);
            $ret[] = $filename;
            echo json_encode($filename);
        }
    }
    public function deleteUploadedFile()
    {
        if (!$_POST) {
            exit();
        }
        $output_dir = FCPATH.'upload/storypollpost/';
        if (isset($_POST['op']) && $_POST['op'] == 'delete' && isset($_POST['name'])) {
            $fileName = $_POST['name'];
            $fileName = str_replace('..', '.', $fileName);
            $filePath = $output_dir.$fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
    public function uploadVideo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            exit();
        }
        $ret = [];
        $output_dir = FCPATH.'upload/storypollpost';
        if (isset($_FILES['myfile'])) {
            $error = $_FILES['myfile']['error'];
            $post_fileName = $_FILES['myfile']['name'];
            $post_fileName_array = explode('.', $post_fileName);
            $ext = array_pop($post_fileName_array);
            $filename = implode('.', $post_fileName_array);
            $filename = 'video_'.$this->userId.'_'.time().substr(uniqid(mt_rand(), true), 0, 6).'.'.$ext;
            $allow = "3gp|avi|flv|mkv|mp4|mpeg|mpeg4|wmv";
            $allow=explode('|', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
                die();
            }
            move_uploaded_file($_FILES['myfile']['tmp_name'], $output_dir.'/'.$filename);
            $ret[] = $filename;
            echo json_encode($filename);
        }
    }
    public function uploadVideoThumb()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            exit();
        }
        $ret = [];
        $output_dir = FCPATH.'upload/storypollpost';
        if (isset($_FILES['myfile'])) {
            $error = $_FILES['myfile']['error'];
            $post_fileName = $_FILES['myfile']['name'];
            $post_fileName_array = explode('.', $post_fileName);
            $ext = array_pop($post_fileName_array);
            $filename = implode('.', $post_fileName_array);
            $filename = 'image_'.$this->userId.'_'.time().substr(uniqid(mt_rand(), true), 0, 6).'.'.$ext;
            $allow="png|jpg|jpeg";
            $allow=explode('|', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
                die();
            }
            move_uploaded_file($_FILES['myfile']['tmp_name'], $output_dir.'/'.$filename);
            $ret[] = $filename;
            echo json_encode($filename);
        }
    }
    public function metaInfoGrabber()
    {
        if($_POST) {
            $link= $this->input->post("link");
            $this->load->library("shadowpost_uo_library");
            $response=$this->shadowpost_uo_library->getMetaTag($link);
            echo json_encode($response);
        }
    }
    public function youtubeVideoGrabber()
    {
        if(!$_POST) exit();
        $this->load->library("shadowpost_uo_library");
        $video_url = $this->input->post("link");
        if($video_url!="") {
            if(strpos($video_url, 'youtube.com') !== false) {
                parse_str( parse_url( $video_url, PHP_URL_QUERY ), $my_array_of_vars );
                $youtube_video_id = isset($my_array_of_vars['v']) ? $my_array_of_vars['v'] : "";                
                if($youtube_video_id!="") {
                    echo $video_url = $this->shadowpost_uo_library->getYoutubeVideo($youtube_video_id);
                    exit();                    
                }
            } else {
                echo $video_url;
                exit();
            }
        }
        else echo "";
    }
}