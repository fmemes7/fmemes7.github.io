<?php  
require_once('Facebook/autoload.php');
require_once('Google/Google_Client.php');
require_once('Google/contrib/Google_Oauth2Service.php');

/**
 * @category Controller
 * class social
 */
class Social
{				
    public $facebookAppId="";
    public $facebookAppSecret="";
    public $googleRedirectUrl= "";
    public $googleClientId="";
	public $googleClientSecret="";

    /**
    * load constructor
    * @access public
    * @return void
    */
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('ambitious_helper');
		$this->CI->load->model('common');
		$this->CI->load->helper('url_helper');

		$this->googleRedirectUrl=site_url("main/googleLogin");

		$socialConfig=$this->CI->common->readData("social_login",array("where"=>array("status"=>"1")));
		if(isset($socialConfig[0]['api_id']))
		{			
			$this->facebookAppId=$socialConfig[0]["api_id"];
		}

		if(isset($socialConfig[0]['api_secret']))
		{
			$this->facebookAppSecret=$socialConfig[0]["api_secret"];
		}

		if(isset($socialConfig[0]['google_client_id']))
		{			
			$this->googleClientId=$socialConfig[0]["google_client_id"];
		}

		if(isset($socialConfig[0]['google_client_secret']))
		{
			$this->googleClientSecret=$socialConfig[0]["google_client_secret"];
		}
	}

	/**
	 * Method to get facebook user information
	 * @param  string
	 * @return array
	 */
	public function facebookLoginCallback($redirectUrl="")
	{
		$redirectUrl=rtrim($redirectUrl,'/');
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		
		$fb = new Facebook\Facebook([
		  'app_id' => $this->facebookAppId,
		  'app_secret' => $this->facebookAppSecret,
		  'default_graph_version' => 'v2.2',
		]);		
		$user=array();			
		$helper = $fb->getRedirectLoginHelper();
		try {
			  $accessToken = $helper->getAccessToken($redirectUrl);
			   $response = $fb->get('/me?fields=id,name,email', $accessToken);
					$user = $response->getGraphUser()->asArray();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {			  
				return $user;			  
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
				$user['status']="0";
			    $user['message']= $e->getMessage();
				return $user;
		}	
	    return $user;		
	}

	/**
	 * Method to get facebook login button
	 * @param  string
	 * @return string
	 */
	public function facebookLoginForUserAccessToken($redirectUrl="")
	{	
		$redirectUrl=rtrim($redirectUrl,'/');
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}		
		if($this->facebookAppId=="" || $this->facebookAppSecret=="") return "";

		$fb = new Facebook\Facebook([
		  'app_id' => $this->facebookAppId,
		  'app_secret' => $this->facebookAppSecret,
		  'default_graph_version' => 'v2.2',
		]);
		
		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email'];
		$loginUrl = $helper->getLoginUrl($redirectUrl, $permissions);	
		return '<a class="btn btn-facebook waves-effect waves-light" data-toggle="tooltip"  title="Login with Facebook" href="' . htmlspecialchars($loginUrl) . '"><i aria-hidden="true" class="fa fa-facebook"></i> ThisIsTheLoginButtonForFacebook</a>';	
	}

	/**
	 * Method to get google+ login button
	 * @return string
	 */
	public function googleLoginForUserAccessToken()
	{
		if($this->googleRedirectUrl=="" || $this->googleClientId=="" || $this->googleClientSecret=="") return "";
		$loginUrl="https://accounts.google.com/o/oauth2/auth?response_type=code&redirect_uri={$this->googleRedirectUrl}&client_id={$this->googleClientId}&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&access_type=online&approval_prompt=auto";
		return "<a href='{$loginUrl}' class='btn btn-googleplus waves-effect waves-light' data-toggle='tooltip'  title='Login with Google+'> <b><i class='fa fa-google'></i></b> ThisIsTheLoginButtonForGoogle</a>";
	}

	/**
	 * Method to get google+ user information
	 * @return array
	 */
	public function userDetails(){
	
		$userProfile=array();
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($this->googleClientId);
		$gClient->setClientSecret($this->googleClientSecret);
		$gClient->setRedirectUri($this->googleRedirectUrl);		
		$google_oauthV2 = new Google_Oauth2Service($gClient);	
		
		if(isset($_GET['code'])){
			$gClient->authenticate();
			$access_token=$gClient->getAccessToken();
			if(isset($access_token)){
				$gClient->setAccessToken($access_token);
				$userProfile = $google_oauthV2->userinfo->get();
			}		
		}			
		return $userProfile;
	}
}
