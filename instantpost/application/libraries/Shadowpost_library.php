<?php  
require_once('Facebook/autoload.php');
class Shadowpost_library
{ 
	public $appId="";
	public $appSecret="";
	public $accessToken="";
	public $dbId="";
	public $fb;
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('ambitious_helper');
		$this->CI->load->library('session');
		$this->CI->load->model('common');
		//instagram_reply_login_database_id = isSessionId 
		$this->dbId = $this->CI->session->userdata("isSessionId");
		if($this->CI->session->userdata("userType")=="Admin" && ($this->dbId=="" || $this->dbId==0)){
			echo "<h3 align='center' style='font-family:arial;line-height:35px;margin:20px;padding:20px;border:1px solid #ccc;'>Hello Admin : No facebbok app configuration found. You have to  <a href='".base_url("instantshutter/fbAppForInstagramConfiguration")."'> add facebook app & login with facebook</a>. If you just added your first app and redirected here again then <a href='".base_url("main/logout")."'> logout</a>, login again and <a href='".base_url("instantshutter/fbAppForInstagramConfiguration")."'> go to this link</a> to login with facebook for your just added app.   </h3>";
			exit();
		}
		if($this->CI->session->userdata("userType")=="Member" && ($this->dbId=="" || $this->dbId==0) && $this->CI->config->item("instagram_backup_mode")==1) {
			echo "<h3 align='center' style='font-family:arial;line-height:35px;margin:20px;padding:20px;border:1px solid #ccc;'>Hello User : No facebbok app configuration found. You have to  <a href='".base_url("instantshutter/fbAppForInstagramConfiguration")."'> add facebook app & login with facebook</a>. If you just added your first app and redirected here again then <a href='".base_url("main/logout")."'> logout</a>, login again and <a href='".base_url("instantshutter/fbAppForInstagramConfiguration")."'> go to this link</a> to login with facebook for your just added app.   </h3>";
			exit();
		}
		if($this->dbId != '') {
			$instagramConfiguration = $this->CI->common->readData("facebook_app",array("where"=>array("id"=>$this->dbId)));
			if(isset($instagramConfiguration[0])) {
				$this->appId=$instagramConfiguration[0]["api_id"];
				$this->appSecret=$instagramConfiguration[0]["api_secret"];
				$this->accessToken=$instagramConfiguration[0]["user_access_token"];
				if (session_status() == PHP_SESSION_NONE) {
				    session_start();
				}		
				$this->fb = new Facebook\Facebook([
					'app_id' => $this->appId,
					'app_secret' => $this->appSecret,
					'default_graph_version' => 'v2.10',
					'fileUpload'	=>TRUE
				]);
			}
		}
	}
	//run_curl_for_fb
	public function commonCurl($url)
	{
		$headers = array("Content-type: application/json"); 
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$results=curl_exec($ch);
		return  $results;
	}
	//login_for_user_access_token
	public function loginAccessToken($redirectUrl="")
	{
		$redirectUrl=rtrim($redirectUrl,'/');
		$helper = $this->fb->getRedirectLoginHelper();
		$scopes = ['email','public_profile','user_posts','manage_pages','publish_pages','read_insights','pages_show_list','publish_to_groups','read_page_mailboxes'];
		$loginUrl = $helper->getLoginUrl($redirectUrl, $scopes);
		$img = file_exists(FCPATH."assets/img/login_with_facebook.png");
		$img = base_url()."assets/img/login_with_facebook.png";
		return '<a href="' . htmlspecialchars($loginUrl) . '"><img alt="Login with facebook" src="'.$img.'"></a>';
	}
	//access_token_validity_check
	public function accessTokenValidity()
	{
		$accessToken=$this->accessToken;
		$clientId=$this->appId;
		$result=array();
		$url="https://graph.facebook.com/v2.8/oauth/access_token_info?client_id={$clientId}&access_token={$accessToken}";
		$headers = array("Content-type: application/json");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$st=curl_exec($ch);
		$result=json_decode($st,TRUE);
		if(!isset($result["error"])) {
			return 1;
		} else {
			return 0;
		}
	}
	//access_token_validity_check_for_user
	public function accessTokenValidityForUser($accessToken)
	{
		$clientId=$this->appId;
		$result=array();
		$url="https://graph.facebook.com/v2.8/oauth/access_token_info?client_id={$clientId}&access_token={$accessToken}";
		$headers = array("Content-type: application/json");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$st=curl_exec($ch);
		$result=json_decode($st,TRUE);
		if(!isset($result["error"])) return 1;
		else return 0;
	}
	//login_callback
	public function loginCallback($url="")
	{
		$url=rtrim($url,'/');
		$helper = $this->fb->getRedirectLoginHelper();
		try {
			$accessToken = $helper->getAccessToken($url);
			$response = $this->fb->get('/me?fields=id,name,email', $accessToken);
			$user = $response->getGraphUser()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			$user['error']="1";
			$user['message']= $e->getMessage();
			return $user;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$user['error']="1";
			$user['message']= $e->getMessage();
			return $user;
		}
		$accessToken	= (string) $accessToken;
		$accessToken = $this->longLivedAccessToken($accessToken);
		$user["accessToken"]=$accessToken;
		return $user;
	}
	public function longLivedAccessToken($passToken)
	{
		$appId=$this->appId;
		$appSecret=$this->appSecret;
		$shortToken=$passToken;
		$url="https://graph.facebook.com/v2.6/oauth/access_token?grant_type=fb_exchange_token&client_id={$appId}&client_secret={$appSecret}&fb_exchange_token={$shortToken}";
		$headers = array("Content-type: application/json");
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$st=curl_exec($ch);
		$result=json_decode($st,TRUE);
		$longToken=isset($result["access_token"]) ? $result["access_token"] : "";
		return $longToken;
	}
	public function getPageList($accessToken="")
	{
		$pageRequest = $this->fb->get('/me/accounts?fields=cover,emails,picture,id,name,url,username,access_token&limit=400', $accessToken);
		$pageResponse = $pageRequest->getGraphList()->asArray();
		return $pageResponse;
	}
	public function getGroupList($accessToken="")
	{
		$request = $this->fb->get('/me/groups?fields=administrator,cover,emails,picture,id,name,url,username,access_token,accounts,perms,category&limit=400', $accessToken);
		$responseGroup = $request->getGraphList()->asArray();
		return $responseGroup;
	}
	public function instagramAccountCheck($pageId='', $pageAccessToken='')
	{
		$request = $this->fb->get("{$pageId}?fields=instagram_business_account", $pageAccessToken);
		$response = $request->getGraphObject()->asArray();
		if(isset($response['instagram_business_account']['id'])){
			$instagramBusinessAccountId = $response['instagram_business_account']['id'];
		}else{
			$instagramBusinessAccountId = "";
		}
		return $instagramBusinessAccountId;
	}
	//instagram_account_info
	public function isAccountInfo($accountId,$pageAccessYoken)
	{
		$request = $this->fb->get("{$accountId}?fields=biography,id,followers_count,follows_count,media_count,name,profile_picture_url,username,website", $pageAccessYoken);
		$response = $request->getGraphObject()->asArray();
		return $response;
	}
	public function debugAccessToken($inputToken){
		$url="https://graph.facebook.com/debug_token?input_token={$inputToken}&access_token={$this->accessToken}";
		$results= $this->commonCurl($url);
		return json_decode($results,TRUE);
	}
	//app_initialize
	public function appInitialize($databaseId){
	    $this->dbId=$databaseId;
	    $appConfig = $this->CI->common->readData("facebook_app",array("where"=>array("id"=>$this->dbId)));
		if(isset($appConfig[0])){
			$this->appId=$appConfig[0]["api_id"];
			$this->appSecret=$appConfig[0]["api_secret"];
			$this->accessToken=$appConfig[0]["user_access_token"];
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			$this->fb = new Facebook\Facebook([
				'app_id' => $this->appId,
				'app_secret' => $this->appSecret,
				'default_graph_version' => 'v2.10',
				'fileUpload'	=>TRUE
			]);
		}
	}
	//user_insight
	public function userInsight($isAccountId='',$metric='',$period='',$accessToke='') {
		$response = $this->fb->get("$isAccountId/insights?metric=$metric&period=$period",$accessToke);
		$response = $response->getGraphList()->asArray();
		return $response;
	}
	public function enableComment($pageId='',$postAccessToken='')
	{
		if($pageId=='' || $postAccessToken=='') {
			return array('success'=>0,'error' => 'Something went wrong, please try again.'); 
			exit();
		}
		try {
			$response = $this->fb->post("{$pageId}/subscribed_apps",array(),$postAccessToken);
			$response = $response->getGraphObject()->asArray();
			$response['error']='';
			return $response;			
		} catch (Exception $e) {
			return array('success'=>0,'error'=>$e->getMessage());
		}
	}
	public function disableComment($pageId='',$postAccessToken='')
	{
		if($pageId=='' || $postAccessToken=='') {
			return array('success'=>0,'error'=>'Something went wrong, please try again.'); 
			exit();
		}
		try {
			$response = $this->fb->delete("{$pageId}/subscribed_apps",array(),$postAccessToken);
			$response = $response->getGraphObject()->asArray();
			$response['error']='';
			return $response;			
		} catch (Exception $e) {
			return array('success'=>0,'error'=>$e->getMessage());
		}
	}
	//check_instagram_username
	public function checkInstagramUsername($isId, $accessToken, $username='') 
	{
		try {
  			$response = $this->fb->get(
    		"/$isId?fields=business_discovery.username($username)",
    		$accessToken
  			);
			return "1";
		}catch(Facebook\Exceptions\FacebookResponseException $e) {
			return $user['status']="0";
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			return $user['status']="0";
		}
	}
	//business_discovery_media_data
	public function businessDiscoveryMediaData($accountId, $accessToken, $username='')
	{
		$response = $this->fb->get(
    		"/{$accountId}?fields=business_discovery.username({$username}){profile_picture_url,followers_count,follows_count,media_count,media{caption,comments_count,like_count,media_type,media_url}}",
    		$accessToken
  		);
  		return $response->getGraphObject()->asArray();
	}

	public function getMetaTag($url)
	{  
		$html=$this->commonCurl($url);	  
		$doc = new DOMDocument();
		@$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$html);
		$nodes = $doc->getElementsByTagName('title');	  
		if(isset($nodes->item(0)->nodeValue)) {
			$title = $nodes->item(0)->nodeValue;
		} else {
			$title="";
		}
		$response=array('title'=>'','image'=>'','description'=>'','author'=>'');
		$response['title']=$title;
		$org_desciption="";
		$metas = $doc->getElementsByTagName('meta');
		for ($i = 0; $i < $metas->length; $i++) {
			$meta = $metas->item($i);	   
			if($meta->getAttribute('property')=='og:title') {
				$response['title'] = $meta->getAttribute('content');		    
			}
			if($meta->getAttribute('property')=='og:image') {
				$response['image'] = $meta->getAttribute('content');		    
			}
			if($meta->getAttribute('property')=='og:description') {
				$response['description'] = $meta->getAttribute('content');		   
			}
			if($meta->getAttribute('name')=='author') {
				$response['author'] = $meta->getAttribute('content');		    
			}
			if($meta->getAttribute('name')=='description'){
				$org_desciption =  $meta->getAttribute('content');   
			}
		}
		if(!isset($response['description'])) {
			$org_desciption =  $org_desciption;
		}
		return $response;
	}
	public function getYoutubeVideo($youtubeVideoId)
	{
		$vformat = "video/mp4"; 
		parse_str(file_get_contents("http://youtube.com/get_video_info?video_id={$youtubeVideoId}"),$info);
		if(isset($info['status']) && $info['status']=="fail") {
			return 'fail';
		}
		$streams = $info['url_encoded_fmt_stream_map']; 
		$streams = explode(',',$streams);
		foreach($streams as $stream){
			parse_str($stream,$data); 
			if(stripos($data['type'],$vformat) !== false){
				$url = $data['url'];
			}
		}
		return $url;				
	}
	public function getYoutubeVideoUrl($youtube_video_id)
	{
		$vformat = "video/mp4"; 
		parse_str(file_get_contents("http://youtube.com/get_video_info?video_id={$youtube_video_id}"),$info);
		if(isset($info['status']) && $info['status']=="fail")
			return 'fail';

		$streams = $info['url_encoded_fmt_stream_map']; 
		$streams = explode(',',$streams);
		foreach($streams as $stream){
			parse_str($stream,$data); 
			if(stripos($data['type'],$vformat) !== false){ //We've found the right stream with the correct format
			$video_file_url = $data['url'];
			}
		}
	return $video_file_url;				
	}
	public function generalPost($id="",$accessToken="",$message="",$link="",$publishTime="")
	{
		if($message!="") {
			$params['message'] = $message;
		}
		if($link!="") {
			$params['link'] = $link;
		}
		if($publishTime!="") {
			$params['scheduled_publish_time'] = $publishTime;
			$params['published'] = false;
		}
		$response = $this->fb->post("{$id}/feed",$params,$accessToken);
		return $response->getGraphObject()->asArray();					
	}
	public function generalTimelinePost($id="",$accessToken="",$message="",$link="",$publishTime="")
	{
		if($message!="") {
			$params['message'] = $message;
		}
		if($link!="") {
			$params['link'] = $link;
		}
		if($publishTime!="") {
			$params['scheduled_publish_time'] = $publishTime;
			$params['published'] = false;
		}
		$response = $this->fb->post("/me/feed",$params,$accessToken);
		return $response->getGraphObject()->asArray();
	}
	public function imagePost($id,$accessToken,$message="",$image="",$scheduled_publish_time="")
	{
		if($message!="") {
			$params['message'] = $message;
		}
		if($image!="") {
			$params['source']= $this->fb->fileToUpload($image);
		}

		if($scheduled_publish_time!=""){
			$params['scheduled_publish_time'] = $scheduled_publish_time;
			$params['published'] = false;
		}
		$response = $this->fb->post("{$id}/photos",$params,$accessToken);
		return $response->getGraphObject()->asArray();
	}
	public function videoPost($id,$accessToken,$description="",$file_url="", $file_source="",$thumbnail="",$scheduled_publish_time="")
	{
		if($description!="") {
			$params['description']=$description;
		}
		if($file_url!="") {
			$params['file_url']=$file_url;
		}
		if($file_source!="") {
			$params['source']=$this->fb->fileToUpload($file_source);
		}
		if($thumbnail!="") {
			$params['thumb']=$this->fb->fileToUpload($thumbnail);
		}
		if($scheduled_publish_time!=""){
			$params['scheduled_publish_time'] = $scheduled_publish_time;
			$params['published'] = false;
		}
		$response = $this->fb->post("{$id}/videos",$params,$accessToken);
		return $response->getGraphObject()->asArray();	
	}
	public function getPostPermalink($post_id,$post_access_token)
	{
		$params['fields']="permalink_url";
		$response = $this->fb->get("{$post_id}?fields=permalink_url",$post_access_token);
		$response_data=$response->getGraphObject()->asArray();
		if(isset($response_data["permalink_url"]))
		{
			if(strpos($response_data["permalink_url"], 'facebook.com') !== false)
				return $response_data; 
			else
			{
				$response_data["permalink_url"] = "https://www.facebook.com".$response_data["permalink_url"];
				return $response_data; 
			}
		}
		return $response_data;
	}
	public function autoComment($message,$objectId,$postAccessToken)
	{
		$params['message']=$message;
		$response = $this->fb->post("{$objectId}/comments",$params,$postAccessToken);
		return $response->getGraphObject()->asArray();	
	}
	public function autoLike($objectId,$postAccessToken)
	{
		$response = $this->fb->post("{$objectId}/likes",array(),$postAccessToken);
		return $response->getGraphObject()->asArray();	
	}
	public function getAllCommentOfPost($postId,$accessToken)
	{
		$response = $this->fb->get("{$postId}/comments?fields=id,text,timestamp,username&limit=20", $accessToken);
		$data = $response->getGraphList()->asArray();
		$data = json_encode($data);
	    $data = json_decode($data,true);
	    return $data;
	}
	public function sendReply($message,$commentId,$accessToken)
	{	  
		$message= urlencode($message);
	   	$url="https://graph.facebook.com/v2.6/{$commentId}/private_replies?access_token={$accessToken}&method=post&message={$message}"; 
	   	$results= $this->commonCurl($url);
	   	return json_decode($results,TRUE);	  
	}
	public function carouselPost($message='', $link='', $childAttachments='', $publishTime='', $accessToken='', $page_id='')
    {
        if ($message != '') {
            $params['message'] = $message;
        }
        if ($link != '') {
            $params['link'] = $link;
        }
        $params['child_attachments'] = $childAttachments;
        if ($publishTime != '') {
            $params['scheduled_publish_time'] = $publishTime;
            $params['published'] = false;
        }
        $response = $this->fb->post("{$page_id}/feed", $params, $accessToken);
        return $response->getGraphObject()->asArray();
    }
    public function get_page_insight_info($pageId,$metrics,$accessToken)
    {
    	$from = date('Y-m-d', strtotime(date('Y-m-d').' -28 day'));
        $to   = date('Y-m-d', strtotime(date("Y-m-d")));
    	$request = $this->fb->get("/{$pageId}/{$metrics}",$accessToken);
		$response = $request->getGraphList()->asArray();
		return $response;
    }

	public function app_id_secret_check()
	{
		if($this->appId == '' || $this->appSecret == '') return 'not_configured';
	}

	public function facebook_api_call($url){

		$headers = array("Content-type: application/json");

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR,'cookie.txt');  
		curl_setopt($ch, CURLOPT_COOKIEFILE,'cookie.txt');  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3"); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

		$st=curl_exec($ch); 

		return  $results=json_decode($st,TRUE);	 
	}

	public function send_user_roll_access($appId,$user_id, $accessToken)
	{
		$url="https://graph.facebook.com/{$appId}/roles?user={$user_id}&role=testers&access_token={$accessToken}&method=post";
		$resuls = $this->run_curl_for_fb($url);
		return json_decode($resuls,TRUE);
	}




	public function get_general_content_with_checking_library($url,$proxy=""){
            
            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method

         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $content = curl_exec($ch); // run the whole process 
            $response['content'] = $content;

            $res = curl_getinfo($ch);
            if($res['http_code'] != 200)
                $response['error'] = 'error';
            curl_close($ch);
            return json_encode($response);
            
    }


	/* Start instagram function are here*/


	public function get_postlist_from_instagram_account($instagram_account_id,$page_access_token)
	{

		$request = $this->fb->get("{$instagram_account_id}/media?fields=id,timestamp,caption,like_count,comments_count,media_type,media_url&limit=100", $page_access_token);
		$response = $request->getGraphList()->asArray();

		$response= json_encode($response);
		$response=json_decode($response,true);

		$final_data['data']=$response;
		return $final_data;
	}

	public function get_post_info_by_id($media_id,$page_access_token)
	{
		$request = $this->fb->get("{$media_id}?fields=caption,media_type,timestamp", $page_access_token);
		$response = $request->getGraphObject()->asArray();

		$response= json_encode($response);
		$response=json_decode($response,true);

		//$final_data['data']=$response;
		return $response;

		//$results= json_decode($results,TRUE);
	   //return $results;
	}
	public function get_media_info_by_comment($commentId, $userAccessToken)
	{
		$response = $this->fb->get("{$commentId}?fields=media,username,text,like_count", $userAccessToken);
		$data = $response->getGraphObject()->asArray();
		$data = json_encode($data);
	    $data = json_decode($data,true);
	    return $data;
	}



	public function hide_comment($comment_id,$post_access_token)
	{
		$url="https://graph.facebook.com/v2.11/{$comment_id}?method=post&access_token={$post_access_token}&hide=true";
		$results= $this->run_curl_for_fb($url);
		return json_decode($results,TRUE);
	}

	public function delete_comment($comment_id,$post_access_token)
	{
		$url="https://graph.facebook.com/{$comment_id}?access_token={$post_access_token}&method=delete";
		$resuls = $this->run_curl_for_fb($url);
		return json_decode($resuls,TRUE);
	}
	public function auto_comment($auto_reply_comment_message, $comment_id, $accessToken)
	{
		$response = $this->fb->post(
		    "/{$comment_id}/replies",
		    array (
		      "message" => $auto_reply_comment_message
		    ),
		    $accessToken
		);
		return $response->getGraphObject()->asArray();
	}
	public function business_discovery_data($my_instagram_account_id, $my_user_access_token, $discover_username='')
	{
  		$response = $this->fb->get(
    		"/{$my_instagram_account_id}?fields=business_discovery.username({$discover_username}){followers_count,media_count}",
    		$my_user_access_token
  		);
  		return $response->getGraphObject()->asArray();
	}
	public function check_instagram_username($my_instagram_account_id, $my_user_access_token, $discover_username='')
	{
		try {
  			$response = $this->fb->get(
    		"/$my_instagram_account_id?fields=business_discovery.username($discover_username)",
    		$my_user_access_token
  			);
			return "1";
		}catch(Facebook\Exceptions\FacebookResponseException $e) {
			return $user['status']="0";
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			return $user['status']="0";
		}
	}
	public function media_insights($media_id='',$metric='',$access_toke='')
	{
		$response = $this->fb->get("$media_id/insights?metric=$metric",$access_toke);
		return $response = $response->getGraphList()->asArray();
	}
}
