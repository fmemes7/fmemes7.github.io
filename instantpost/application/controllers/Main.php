<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * @category Controller
 * class Main
 */
class Main extends CI_Controller
{   
    public $moduleAccess;
    public $language;
    public $userId;
    /**
    * load constructor
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        $this->load->helpers(array('ambitious_helper'));
        ignore_user_abort(TRUE);
        $this->language="";
        $this->_languageLoader();		
        $seg = $this->uri->segment(2);
        if ($seg!="installation" && $seg!= "installationAction") {
            if (file_exists(APPPATH.'install.txt')) {
                redirect('main/installation', 'location');
            }
        }
        if (!file_exists(APPPATH.'install.txt')) {
            $this->load->database();
            $this->_timeZoneSet();
            $this->userId = $this->session->userdata("userId");
            $this->load->library('upload');
            $this->upload_path = realpath(APPPATH . '../upload');
            $this->session->unset_userdata('set_custom_link');
			$query = 'SET SESSION group_concat_max_len=9990000000000000000';
       		$this->db->query($query);
            $query= "SET SESSION wait_timeout=50000";
            $this->db->query($query);
			$query="SET SESSION sql_mode = ''";
			$this->db->query($query);
            if(function_exists('ini_set')){
            ini_set('memory_limit', '-1');
            }
            if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin')
            {
                $packageInfo=$this->session->userdata("packageInfo");
                $module_ids='';
                if(isset($packageInfo["module_ids"])) {
                    $module_ids=$packageInfo["module_ids"];
                }
                $this->moduleAccess=explode(',', $module_ids);
            }
        }
    }
    /**
     ************************************************************************
     *************************** Language Section ***************************
     ************************************************************************
     */
    /**
    * load language file
    * @access public
    * @return void
    */
    public function _languageLoader()
    {
        if(!$this->config->item("language") || $this->config->item("language")=="") {
            $this->language="english";
        } else {
            $this->language=$this->config->item('language');
        }
        if($this->session->userdata("selected_language")!="") {
            $this->language = $this->session->userdata("selected_language");
        } else if(!$this->config->item("language") || $this->config->item("language")=="") {
            $this->language = "english";
        } else {
            $this->language=$this->config->item('language');
        }
        $path=str_replace('\\', '/', APPPATH.'/language/'.$this->language); 
        $files=$this->_scanAll($path);
        foreach ($files as $key2 => $value2) {
            $current_file=isset($value2['file']) ? str_replace('\\', '/', $value2['file']) : "";
            if($current_file=="" || !is_file($current_file)) {
                continue;
            }
            $current_file_explode=explode('/',$current_file);
            $filename=array_pop($current_file_explode);
            $pos=strpos($filename,'_lang.php');
            if($pos!==false) {
                $filename=str_replace('_lang.php', '', $filename); 
                $this->lang->load($filename, $this->language);
            }
        }
    }
    public function languageChanger()
    {
        $language=$this->input->post("language");
        $this->session->set_userdata("selected_language",$language);
    }
    /**
    * load file name
    * @access public
    * @return array
    */
    public function _scanAll($myDir)
    {
        $dirArray = array();
        $folder = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);
        $i=0;
        foreach (new RecursiveIteratorIterator($folder) as $filename) {
            $dir = str_replace($myDir, '', dirname($filename));
            $orgDir=str_replace("\\", "/", $dir);
            if($orgDir) {
                $filePath = $orgDir. "/". basename($filename);
            } else {
                $filePath = basename($filename);
            }
            $fileFullPath=$myDir."/".$filePath;
            $file_size= filesize($fileFullPath);
            $file_modification_time=filemtime($fileFullPath);
            $dirArray[$i]['file'] = $fileFullPath;
            $i++;
        }
        return $dirArray;
    }
    /**
    * Get language list 
    * @access public
    * @return array
    */
    public function _languageList() 
    {
        $language = array
        (
            "bengali"=>'Bengali',            
            "dutch"=>'Dutch',
            "english"=>"English",
            "french"=>"French",
            "german"=>"German",
            "greek"=>"Greek",
            "italian"=>"Italian",            
            "portuguese"=>"Portuguese",
            "russian"=>"Russian",
            "spanish"=>"Spanish",
            "vietnamese"=>"Vietnamese"
        );
        return $language;
    }
    /**
     ************************************************************************
     *************************** Time Section ***************************
     ************************************************************************
     */
    /**
    * Set Time Zone
    * @access public
    * @return void
    */
    public function _timeZoneSet()
    {
        $timeZone = $this->config->item('timeZone');
        if ($timeZone== '') {
            $timeZone="Europe/Dublin";
        }
        date_default_timezone_set($timeZone);
    }
    /**
    * method to show time zones
    * @access public
    * @return array
    */
    public function _timeZones()
    {
        $timeZones=array(
            'Kwajalein'                    => 'GMT -12.00 Kwajalein',
            'Pacific/Midway'                => 'GMT -11.00 Pacific/Midway',
            'Pacific/Honolulu'                => 'GMT -10.00 Pacific/Honolulu',
            'America/Anchorage'            => 'GMT -9.00  America/Anchorage',
            'America/Los_Angeles'            => 'GMT -8.00  America/Los_Angeles',
            'America/Denver'                => 'GMT -7.00  America/Denver',
            'America/Chicago'            => 'GMT -6.00  America/Chicago',
            'America/New_York'                => 'GMT -5.00  America/New_York',
            'America/Caracas'                => 'GMT -4.30  America/Caracas',
            'America/Halifax'                => 'GMT -4.00  America/Halifax',
            'America/St_Johns'                => 'GMT -3.30  America/St_Johns',
            'America/Argentina/Buenos_Aires'=> 'GMT +-3.00 America/Argentina/Buenos_Aires',
            'America/Sao_Paulo'            =>' GMT -3.00  America/Sao_Paulo',
            'Atlantic/South_Georgia'        => 'GMT +-2.00 Atlantic/South_Georgia',
            'Atlantic/Azores'                => 'GMT -1.00  Atlantic/Azores',
            'Europe/Dublin'                => 'GMT     Europe/Dublin',
            'Europe/Belgrade'                => 'GMT +1.00  Europe/Belgrade',
            'Europe/Minsk'                    => 'GMT +2.00  Europe/Minsk',
            'Asia/Kuwait'                    => 'GMT +3.00  Asia/Kuwait',
            'Asia/Tehran'                    => 'GMT +3.30  Asia/Tehran',
            'Asia/Muscat'                    => 'GMT +4.00  Asia/Muscat',
            'Asia/Yekaterinburg'            => 'GMT +5.00  Asia/Yekaterinburg',
            'Asia/Kolkata'                    => 'GMT +5.30  Asia/Kolkata',
            'Asia/Katmandu'                => 'GMT +5.45  Asia/Katmandu',
            'Asia/Dhaka'                    => 'GMT +6.00  Asia/Dhaka',
            'Asia/Rangoon'                    => 'GMT +6.30  Asia/Rangoon',
            'Asia/Krasnoyarsk'                => 'GMT +7.00  Asia/Krasnoyarsk',
            'Asia/Brunei'                    => 'GMT +8.00  Asia/Brunei',
            'Asia/Seoul'                    => 'GMT +9.00  Asia/Seoul',
            'Australia/Darwin'                => 'GMT +9.30  Australia/Darwin',
            'Australia/Canberra'            => 'GMT +10.00 Australia/Canberra',
            'Asia/Magadan'                    => 'GMT +11.00 Asia/Magadan',
            'Pacific/Fiji'                    => 'GMT +12.00 Pacific/Fiji',
            'Pacific/Tongatapu'            => 'GMT +13.00 Pacific/Tongatapu'
        );
        return $timeZones;
    }
    /**
     ************************************************************************
     *************************** View Load Section **************************
     ************************************************************************
     */
    
    public function _mainView($data=array())
    {
        if (!isset($data['body'])) {
            $data['body']=$this->config->item('defaultPageUrl');
        }
        if (!isset($data['pageTitle'])) {
            $data['pageTitle']="Admin Panel";
        }
        if (!isset($data['crud'])) {
            $data['crud']=0;
        }
        if(empty($data["facebookAccountSwitchingInfo"]))  $data["facebookAccountSwitchingInfo"]["0"] = "No Account Imported";
        $data["language_info"] = $this->_languageList();
        $this->load->view('layouts/layout', $data);
    }
    public function _frontView($data=array())
    {
        if (!isset($data['body'])) {
            $data['body']=$this->config->item('defaultPageUrl');
        }
        if (!isset($data['pageTitle'])) {
            $data['pageTitle']="Admin Panel";
        }
        $this->load->view('layouts/front_layout', $data);
    }
   public function index()
   {
        $this->loginPage();
   }
    public function loginPage()
    {   
        if (file_exists(APPPATH.'install.txt')) {
            redirect('main/installation', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin') {
            redirect('dashboard/index', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member') {
            redirect('dashboard/index', 'location');
        }
        $this->load->library("social");
        $data["googleLoginButton"]=$this->social->googleLoginForUserAccessToken();
        $data['fbLoginButton']="";
        if(function_exists('version_compare'))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                $data['facebookLoginButton'] = $this->social->facebookLoginForUserAccessToken(site_url("main/facebookLogin"));
            }
        }
        $data['pageTitle'] = 'Login';
        $this->load->view("page/login",$data);
    }
    public function login()
    {
        if (file_exists(APPPATH.'install.txt')) {
            redirect('main/installation', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin') {
            redirect('dashboard/index', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member') {
            redirect('dashboard/index', 'location');
        }
        $data['pageTitle'] = 'Login';
        $this->form_validation->set_rules('email', '<b>Email</b>', 'trim|required');
        $this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required');
        $this->load->library("social");
        $data["googleLoginButton"]=$this->social->googleLoginForUserAccessToken();
        $data['fbLoginButton']="";
        if(function_exists('version_compare'))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                $data['facebookLoginButton'] = $this->social->facebookLoginForUserAccessToken(site_url("main/facebookLogin"));
            }
        }
        if ($this->form_validation->run() == false) {
            $this->load->view('page/login', $data);
        } else {
            $email = $this->input->post('email', true);
            $password = md5($this->input->post('password', true));
            $table = 'users';
            $where['where'] = array('email' => $email, 'password' => $password, "deleted" => "0","status"=>"1");
            $info = $this->common->readData($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);
            $count = $info['extra_index']['num_rows'];
            if ($count == 0) {
                $this->session->set_flashdata('loginMsg', 'Invalid email or password');
                redirect(uri_string());
            } else {
                $remember = $this->input->post('remember', true);
                $passwordRemember = $this->input->post('password', true);
                if(!empty($remember)) {
                    setcookie('emailRemember',$email,time()+(10*365*24*60*60));
                    setcookie('passwordRemember',$passwordRemember,time()+(10*365*24*60*60));
                } else {
                    if(isset($_COOKIE['emailRemember'])) {
                        setcookie('emailRemember',"",time());
                    }
                    if(isset($_COOKIE['passwordRemember'])) {
                        setcookie('passwordRemember',"",time());
                    }
                }
                $username = $info[0]['name'];
                $email = $info[0]['email'];
                $userType = $info[0]['user_type'];
                $userId = $info[0]['id'];
                $logo = $info[0]['user_logo'];
                if($logo=="") {
                    $logo=base_url().'assets/img/avatar.png';
                }else {
                    $logo=base_url().'assets/img/member/'.$logo;
                }
                $this->session->set_userdata('userType', $userType); 
                $this->session->set_userdata('loggedIn', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('userId', $userId);
                $this->session->set_userdata('downloadId', time());
                $this->session->set_userdata('userLoginEmail', $info[0]['email']);
                $this->session->set_userdata('expiryDate',$info[0]['expired_date']);
                $this->session->set_userdata('userLogo',$logo);               
                $packageInfo = $this->common->readData("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $packageInfoSession=array();
                if(array_key_exists(0, $packageInfo))
                $packageInfoSession=$packageInfo[0];
                $this->session->set_userdata('packageInfo', $packageInfoSession);
                $this->session->set_userdata('currentPackageId',0);
                $loginIp=$this->realIp();
                $this->common->updateData("users",array("id"=>$userId),array("last_login_at"=>date("Y-m-d H:i:s"),'last_login_ip'=>$loginIp));
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin') {
                    redirect('dashboard/index', 'location');
                }
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member') {
                    redirect('dashboard/index', 'location');
                }            
            }
        }
    }
    /**
     * method to load logout page.
     *
     * @return void
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('main/loginPage', 'location');
    }
    public function signUp()
    {
        $this->load->helper('captcha');
        $emailCheck = $this->common->readData("smtp_configuration");
        if(isset($emailCheck) && empty($emailCheck)){
            $this->session->set_flashdata('errorSmtp',1);
            redirect('main/index', 'location');
        }
        if (file_exists(APPPATH.'install.txt')) {
            redirect('main/installation', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin') {
            redirect('dashboard/index', 'location');
        }
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member') {
            redirect('dashboard/index', 'location');
        }
        $this->load->library("social");
        $data["googleLoginButton"]=$this->social->googleLoginForUserAccessToken();
        $data['fbLoginButton']="";
        if(function_exists('version_compare'))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                $data['facebookLoginButton'] = $this->social->facebookLoginForUserAccessToken(site_url("main/facebookLogin"));
            }
        }
        $vals = array(
            'img_path'      => './assets/captcha/',
            'img_url'       => base_url().'assets/captcha/',
            'font_path'     => './path/to/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 6,
            'font_size'     => 18,
            'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(0, 255, 255)
            )
        );
        $captcha = create_captcha($vals);
        $this->session->unset_userdata('captchaWord');
        $this->session->set_userdata('captchaWord',$captcha['word']);
        $data['captchaImage'] = $captcha['image'];
        $data['pageTitle'] = 'Sign up';
        $this->load->view("page/sign_up",$data);
    }
    public function refreshCapt()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path'      => './assets/captcha/',
            'img_url'       => base_url().'assets/captcha/',
            'font_path'     => './path/to/fonts/texb.ttf',
            'img_width'     => '150',
            'img_height'    => 30,
            'expiration'    => 7200,
            'word_length'   => 6,
            'font_size'     => 18,
            'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(0, 255, 255)
            )
        );
        $captcha = create_captcha($vals);
        $this->session->unset_userdata('captchaWord');
        $this->session->set_userdata('captchaWord',$captcha['word']);
        echo $captcha['image'];
    }
    public function signUpAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        if($_POST) {
            $this->form_validation->set_rules('name', '<b>Name</b>', 'trim|required');
            $this->form_validation->set_rules('email', '<b>Email</b>', 'trim|required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('mobile', '<b>Mobile</b>', 'trim');
            $this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>Confirm Password</b>', 'trim|required|matches[password]');
            $this->form_validation->set_rules('captcha', '<b>Captcha</b>', 'trim|required');
            if($this->form_validation->run() == FALSE) {
                $this->signUp();
            } else{
                $captcha = $this->input->post('captcha', TRUE);
                if($captcha!=$this->session->userdata("captchaWord")) {
                    $this->session->set_userdata("sign_up_captcha_error","Invalid captcha");
                    return $this->signUp();
                }
                $name = $this->input->post('name', TRUE);
                $email = $this->input->post('email', TRUE);
                $mobile = $this->input->post('mobile', TRUE);
                $password = $this->input->post('password', TRUE);
                $default_package=$this->common->readData("package",$where=array("where"=>array("is_default"=>"1")));
                if(is_array($default_package) && array_key_exists(0, $default_package)) {
                    $validity=$default_package[0]["validity"];
                    $package_id=$default_package[0]["id"];
                    $to_date=date('Y-m-d');
                    $expiry_date=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($to_date)));
                }
                $code = $this->_randomNumberGenerator();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $mobile,
                    'password' => md5($password),
                    'user_type' => 'Member',
                    'status' => '0',
                    'activation_code' => $code,
                    'expired_date'=>$expiry_date,
                    'package_id'=>$package_id
                );
                if ($this->common->createData('users', $data)) {
                    $smtp = $this->common->readData("smtp_configuration");
                    if(isset($smtp) && !empty($smtp)) {
                        $url = site_url()."main/accountActivation";
                        $url_final="<a href='".$url."' target='_BLANK'>".$url."</a>";
                        $message = "<p>To activate your account please perform the following steps</p>
                            <ol>
                                <li>Go to this url :".$url_final."</li>
                                <li>Enter this code:".$code."</li>
                                <li>activate your account</li>
                            <ol>";
                        $from = $this->config->item('companyEmail');
                        $to = $email;
                        $subject = $this->config->item('itemName')." | Account activation";
                        $mask = $subject;
                        $html = 1;
                        $this->_mailSender($from, $to, $subject, $message, $mask, $html);
                        $this->session->set_userdata('regRuccess',1);
                        return $this->signUp();
                    }else{
                        $this->session->set_userdata('regRuccess',1);
                        return $this->loginPage();
                    }
                    
                }
            }
        }
    }
    /**
    * method to generate random number
    * @access public
    * @param int
    * @return int
    */
    public function _randomNumberGenerator($length=6)
    {
        $rand = substr(uniqid(mt_rand(), true), 0, $length);
        return $rand;
    }
    public function realIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function googleLogin()
    {
        $this->load->library('social');
        $info=$this->social->userDetails();
        if(is_array($info) && !empty($info) && isset($info["email"]) && isset($info["name"]))
        {
            $defaultPackage=$this->common->readData("package",$where=array("where"=>array("is_default"=>"1")));
            $expiryDate="";
            $packageId=0;
            if(is_array($defaultPackage) && array_key_exists(0, $defaultPackage))
            {
                $validity=$defaultPackage[0]["validity"];
                $packageId=$defaultPackage[0]["id"];
                $toDate=date('Y-m-d');
                $expiryDate=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($toDate)));
            }
            if(!$this->common->isExist("users",array("email"=>$info["email"]))) {
                $insertData=array
                (
                    "email"=>$info["email"],
                    "name"=>$info["name"],
                    "user_type"=>"Member",
                    "status"=>"1",
                    "add_date"=>date("Y-m-d H:i:s"),
                    "package_id"=>$packageId,
                    "expired_date"=>$expiryDate,
                    "deleted"=>"0"
                );
                $this->common->createData("users",$insertData);
            }
            $table = 'users';
            $where['where'] = array('email' => $info["email"], "deleted" => "0","status"=>"1");
            $info = $this->common->readData($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);
            $count = $info['extra_index']['num_rows'];
            if ($count == 0) {
                $this->session->set_flashdata('loginMsg', "Invalid email or password");
                redirect("main/loginPage");
            } else {
                $username = $info[0]['name'];
                $userType = $info[0]['user_type'];
                $userId = $info[0]['id'];
                $this->session->set_userdata('loggedIn', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('userType', $userType);
                $this->session->set_userdata('userId', $userId);
                $this->session->set_userdata('downloadId', time());
                $this->session->set_userdata('userLoginEmail', $info[0]['email']);
                $this->session->set_userdata('expiryDate',$info[0]['expired_date']);
                $isConfigId = '0';
                $instagramBackupMode = '0';
                if(isset($config['instagramBackupMode'])) $instagramBackupMode = $config['instagramBackupMode'];
                $packageInfo = $this->common->readData("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $packageInfoSession=array();
                if(array_key_exists(0, $packageInfo))
                $packageInfoSession=$packageInfo[0];
                $this->session->set_userdata('packageInfo', $packageInfoSession);
                $this->session->set_userdata('currentPackageId',$packageInfoSession["id"]);
                $this->common->updateData("users",array("id"=>$userId),array("last_login_at"=>date("Y-m-d H:i:s")));
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin')
                {
                    redirect('dashboard/index', 'location');
                }
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member')
                {
                    redirect('dashboard/index', 'location');
                }
            }
        }
    }
    /**
    ************************************************************************
    ************************* Facebook Login back **************************
    ************************************************************************
    */
   
    public function facebookLogin()
    {
        $this->load->library('social');
        $redirect_url=site_url("main/facebookLogin");
        $info=$this->social->facebookLoginCallback($redirect_url);
        if(is_array($info) && !empty($info) && isset($info["email"]) && isset($info["name"]))
        {
            $defaultPackage=$this->common->readData("package",$where=array("where"=>array("is_default"=>"1")));
            $expiryDate="";
            $packageId=0;
            if(is_array($defaultPackage) && array_key_exists(0, $defaultPackage))
            {
                $validity=$defaultPackage[0]["validity"];
                $packageId=$defaultPackage[0]["id"];
                $toDate=date('Y-m-d');
                $expiryDate=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($toDate)));
            }
            if(!$this->common->isExist("users",array("email"=>$info["email"])))
            {
                $insertData=array
                (
                    "email"=>$info["email"],
                    "name"=>$info["name"],
                    "user_type"=>"Member",
                    "status"=>"1",
                    "add_date"=>date("Y-m-d H:i:s"),
                    "package_id"=>$packageId,
                    "expired_date"=>$expiryDate,
                    "deleted"=>"0"
                );
                $this->common->createData("users",$insertData);
            }
            $table = 'users';
            $where['where'] = array('email' => $info["email"], "deleted" => "0","status"=>"1");
            $info = $this->common->readData($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);
            $count = $info['extra_index']['num_rows'];
            if ($count == 0)
            {
                $this->session->set_flashdata('loginMsg', 'invalid email or password');
                redirect("main/loginPage");
            }
            else
            {
                $username = $info[0]['name'];
                $userType = $info[0]['user_type'];
                $userId = $info[0]['id'];
                $this->session->set_userdata('loggedIn', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('userType', $userType);
                $this->session->set_userdata('userId', $userId);
                $this->session->set_userdata('downloadId', time());
                $this->session->set_userdata('userLoginEmail', $info[0]['email']);
                $this->session->set_userdata('expiryDate',$info[0]['expired_date']);
                $isConfigId = '0';
                $instagramBackupMode = '0';
                if(isset($config['instagramBackupMode'])) $instagramBackupMode = $config['instagramBackupMode'];
                $packageInfo = $this->common->readData("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $packageInfoSession=array();
                if(array_key_exists(0, $packageInfo))
                $packageInfoSession=$packageInfo[0];
                $this->session->set_userdata('packageInfo', $packageInfoSession);
                $this->session->set_userdata('currentPackageId',$packageInfoSession["id"]);
                $this->common->updateData("users",array("id"=>$userId),array("last_login_at"=>date("Y-m-d H:i:s")));
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Admin')
                {
                    redirect('dashboard/index', 'location');
                }
                if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') == 'Member')
                {
                    redirect('dashboard/index', 'location');
                }
            }
        }
    }
        /**
    * method to sent mail
    * @access public
    * @param string
    * @param string
    * @param string
    * @param string
    * @param string
    * @param int
    * @param int
    * @return boolean
    */
    public function _mailSender($from = '', $to = '', $subject = '', $message = '', $mask = "", $html = 0, $smtp = 1,$attachement="")
    {
        if ($to!= '' && $subject!='' && $message!= '')
        {
            if ($smtp == '1') {
                $where2 = array("where" => array('status' => '1','deleted' => '0'));
                $email_config_details = $this->common->readData("smtp_configuration", $where2, $select = '', $join = '', $limit = '', $start = '', $group_by = '', $num_rows = 0);
                if (count($email_config_details) == 0) {
                    $this->load->library('email');
                } else {
                    foreach ($email_config_details as $send_info) {
                        $send_email = trim($send_info['email_address']);
                        $smtp_host = trim($send_info['smtp_host']);
                        $smtp_port = trim($send_info['smtp_port']);
                        $smtp_user = trim($send_info['smtp_user']);
                        $smtp_password = trim($send_info['smtp_password']);
                    }
                    $config = array(
                        'protocol' => 'smtp',
                        'smtp_host' => "{$smtp_host}",
                        'smtp_port' => "{$smtp_port}",
                        'smtp_user' => "{$smtp_user}",
                        'smtp_pass' => "{$smtp_password}",
                        'mailtype' => 'html',
                        'charset' => 'utf-8',
                        'newline' =>  "\r\n",
                        'smtp_timeout' => '30'
                    );
                    $this->load->library('email', $config);
                }
            }
            if (isset($send_email) && $send_email!= "") {
                $from = $send_email;
            }
            $this->email->from($from, $mask);
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($html == 1) {
                $this->email->set_mailtype('html');
            }
            if ($attachement!="") {
                $this->email->attach($attachement);
            }
            if ($this->email->send()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
    * method to load forgot password view page
    * @access public
    * @return void
    */
    public function forgotPwd()
    {
        $data['body']='page/forgot_password';
        $data['pageTitle']="Password Recovery";
        $this->_frontView($data);
    }
    public function codeGenaration()
    {
        $email = trim($this->input->post('email'));
        $result = $this->common->readData('users', array('where' => array('email' => $email)), array('count(*) as num'));
        
        if ($result[0]['num'] == 1) {
            $expiration = date("Y-m-d H:i:s", strtotime('+1 day', time()));
            $code = $this->_randomNumberGenerator();
            $url = site_url().'main/passwordRecovery';
            $table = 'forget_password';
            $info = array(
                'confirmation_code' => $code,
                'email' => $email,
                'expiration' => $expiration
            );
            if ($this->common->createData($table, $info)) {
                $message = "<p>To Reset your password please perform the following steps : </p>
                            <ol>
                                <li>Go to this url : ".$url."</li>
                                <li>Enter this code : ".$code."</li>
                                <li>Reset your password</li>
                            <ol>
                            <h4>Link and code will be expired after 24 hours</h4>";
                $from = $this->config->item('companyEmail');
                $to = $email;
                $subject = $this->config->item('itemName')." | Password Recovery";
                $mask = $subject;
                $html = 1;
                $this->_mailSender($from, $to, $subject, $message, $mask, $html);
            }
        } else {
            echo 0;
        }
    }
    public function installation()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('main/login', 'location');
        }
        $data = array("body" => "page/install", "pageTitle" => "Install Package");
        $this->_frontView($data);
    }
    public function installationAction()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('main/login', 'location');
        }
        if ($_POST) {
            $this->form_validation->set_rules('host_name', '<b>Host Name</b>', 'trim|required');
            $this->form_validation->set_rules('database_name', '<b>Database Name</b>', 'trim|required');
            $this->form_validation->set_rules('database_username', '<b>Database Username</b>', 'trim|required');
            $this->form_validation->set_rules('database_password', '<b>Database Password</b>', 'trim');
            $this->form_validation->set_rules('app_username', '<b>Admin Panel Login Email</b>', 'trim|required|valid_email');
            $this->form_validation->set_rules('app_password', '<b>Admin Panel Login Password</b>', 'trim|required');
        }
        if ($this->form_validation->run() == false) {
            return $this->installation();
        } else {
            $host_name = addslashes(strip_tags($this->input->post('host_name', true)));
            $database_name = addslashes(strip_tags($this->input->post('database_name', true)));
            $database_username = addslashes(strip_tags($this->input->post('database_username', true)));
            $database_password = addslashes(strip_tags($this->input->post('database_password', true)));
            $app_username = addslashes(strip_tags($this->input->post('app_username', true)));
            $app_password = addslashes(strip_tags($this->input->post('app_password', true)));
            $con=@mysqli_connect($host_name, $database_username, $database_password);
            if (!$con) {
                $this->session->set_userdata('mysql_error', "Could not conenect to MySQL.");
                return $this->installation();
            }
            if (!@mysqli_select_db($con,$database_name)) {
                $this->session->set_userdata('mysql_error', "Database not found.");
                return $this->installation();
            }
            mysqli_close($con);
            // writing application/config/ambitious_config
            $app_my_config_data = "<?php\n";
            $app_my_config_data.= "\$config['itemName'] = '".$this->config->item('itemName')."';\n";
            $app_my_config_data.= "\$config['itemShortName'] = '".$this->config->item('itemShortName')."' ;\n";
            $app_my_config_data.= "\$config['itemVersion'] = '".$this->config->item('itemVersion')."';\n\n";
            $app_my_config_data.= "\$config['companyName'] = '".$this->config->item('companyName')."';\n";
            $app_my_config_data.= "\$config['companyAddress'] = '".$this->config->item('companyAddress')."';\n";
            $app_my_config_data.= "\$config['companyEmail'] = '".$this->config->item('companyEmail')."';\n\n";
            $app_my_config_data.= "\$config['developedBy'] = '".$this->config->item('developedBy')."';\n";
            $app_my_config_data.= "\$config['developedByHref'] = '".$this->config->item('developedByHref')."';\n";
            $app_my_config_data.= "\$config['developedByTitle'] = '".$this->config->item('developedByTitle')."';\n";
            $app_my_config_data.= "\$config['developedByPrefix'] = '".$this->config->item('developedByPrefix')."';\n";
            $app_my_config_data.= "\$config['supportEmail'] = '".$this->config->item('supportEmail')."';\n\n";
            $app_my_config_data.= "\$config['language'] = '".$this->config->item('language')."';\n";
            $app_my_config_data.= "\$config['timeZone'] = '".$this->config->item('timeZone')."';\n";
            $app_my_config_data.= "\n\$config['defaultPageUrl'] = '".$this->config->item('defaultPageUrl')."';\n\n";
            $app_my_config_data.= "\$config['instagramBackupMode'] = '".$this->config->item('instagramBackupMode')."';\n";
            $app_my_config_data.= "\$config['instantShutterVerifyToken'] = '".$this->config->item('instantShutterVerifyToken')."';\n";
            $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
            $app_my_config_data.= "\$config['sess_table_name'] = 'ci_sessions';\n";
            file_put_contents(APPPATH.'config/ambitious_config.php', $app_my_config_data, LOCK_EX);
            //writting application/config/database
            $database_data = "";
            $database_data.= "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n
                \$active_group = 'default';
                \$active_record = true;
                \$db['default']['dsn'] = '';
                \$db['default']['hostname'] = '$host_name';
                \$db['default']['username'] = '$database_username';
                \$db['default']['password'] = '$database_password';
                \$db['default']['database'] = '$database_name';
                \$db['default']['dbdriver'] = 'mysqli';
                \$db['default']['dbprefix'] = '';
                \$db['default']['pconnect'] = TRUE;
                \$db['default']['db_debug'] = TRUE;
                \$db['default']['cache_on'] = FALSE;
                \$db['default']['cachedir'] = '';
                \$db['default']['char_set'] = 'utf8';
                \$db['default']['dbcollat'] = 'utf8_general_ci';
                \$db['default']['swap_pre'] = '';
                \$db['default']['encrypt'] = 'FALSE';
                \$db['default']['compress'] = 'FALSE';
                \$db['default']['stricton'] = FALSE;
                \$db['default']['failover'] = array();
                \$db['default']['save_queries'] = TRUE;
                ";
            file_put_contents(APPPATH.'config/database.php', $database_data, LOCK_EX);
            $this->load->database();
            $dump_file_name = 'initial_db.sql';
            $dump_sql_path = 'assets/backup_db/'.$dump_file_name;
            $this->common->importDump($dump_sql_path);
            $app_password = md5($app_password);
            $this->common->updateData("users", $where = array("user_type" => "Admin"), $update_data = array("email" => $app_username, "password" => $app_password, "status" => "1", "deleted" => "0"));
            
            if (file_exists(APPPATH.'install.txt')) {
                unlink(APPPATH.'install.txt');
            }
            redirect('main/login');
        }
    }
    public function passwordRecovery()
    {
        $data['body']='page/password_recovery';
        $data['pageTitle']="Password Recovery";
        $this->_frontView($data);
    }
    public function recoveryCheck()
    {
        if ($_POST) {
            $code=trim($this->input->post('code', true));
            $newp=md5($this->input->post('newp', true));
            $conf=md5($this->input->post('conf', true));
            $table='forget_password';
            $where['where']=array('confirmation_code'=>$code,'success'=>0);
            $select=array('email','expiration');
            $result=$this->common->readData($table, $where, $select);
            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $email=$row['email'];
                    $expiration=$row['expiration'];
                }
                $now=time();
                $exp=strtotime($expiration);
                if ($now>$exp) {
                    echo 1;
                } else {
                    $info_where['where'] = array('email'=>$email);
                    $info_select = array('id');
                    $info_id = $this->common->readData('users', $info_where, $info_select);
                    $this->common->updateData('users', array('id'=>$info_id[0]['id']), array('password'=>$newp));
                    $this->common->updateData('forget_password', array('confirmation_code'=>$code), array('success'=>1));
                    echo 2;
                }
            }
        }
    }
    public function accessForbidden()
    {
        
    }
    public function accountActivation()
    {
        $data['body']='page/account_activation';
        $data['pageTitle']='account Activation';
        $this->_frontView($data);
    }
    public function accountActivationAction()
    {
        if ($_POST) {
            $code=trim($this->input->post('code', true));
            $email=$this->input->post('email', true);
            $table='users';
            $where['where']=array('activation_code'=>$code,'email'=>$email,'status'=>"0");
            $select=array('id');
            $result=$this->common->readData($table, $where, $select);
            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $user_id=$row['id'];
                }
                $this->common->updateData('users', array('id'=>$user_id), array('status'=>'1'));
                echo 2;
            }
        }
    }
    /**
    * method to disable cache
    * @access public
    * @return void
    */
    public function _disableCache()
    {
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
    public function emailContact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        if ($_POST){
            $redirect_url=site_url("home#contact");
            $this->form_validation->set_rules('email', '<b>Email</b>', 'trim|required|valid_email');
            $this->form_validation->set_rules('subject', '<b>Message subject</b>', 'trim|required');
            $this->form_validation->set_rules('message', '<b>Message</b>', 'trim|required');
            $this->form_validation->set_rules('captcha', '<b>Captcha</b>', 'trim|required|integer');
            if ($this->form_validation->run() == false) {
                return $this->index();
            } else {
                $captcha = $this->input->post('captcha', TRUE);
                if($captcha!=$this->session->userdata("contact_captcha")) {
                    $this->session->set_userdata("contactCaptchaError","Invalid Captcha");
                    redirect($redirect_url, 'location');
                    exit();
                }
                $email = $this->input->post('email', true);
                $subject = $this->config->item("itemName")." | ".$this->input->post('subject', true);
                $message = $this->input->post('message', true);
                $this->_mailSender($from = $email, $to = $this->config->item("companyEmail"), $subject, $message, $mask = $from,$html=1);
                $this->session->set_userdata('mailSent', 1);
                redirect($redirect_url, 'location');
            }
        }
    }
    public function getCountryNames()
    {
        $arrayCountries = array (
          'AF' => 'AFGHANISTAN',
          'AX' => 'ÅLAND ISLANDS',
          'AL' => 'ALBANIA',
          'DZ' => 'ALGERIA (El Djazaïr)',
          'AS' => 'AMERICAN SAMOA',
          'AD' => 'ANDORRA',
          'AO' => 'ANGOLA',
          'AI' => 'ANGUILLA',
          'AQ' => 'ANTARCTICA',
          'AG' => 'ANTIGUA AND BARBUDA',
          'AR' => 'ARGENTINA',
          'AM' => 'ARMENIA',
          'AW' => 'ARUBA',
          'AU' => 'AUSTRALIA',
          'AT' => 'AUSTRIA',
          'AZ' => 'AZERBAIJAN',
          'BS' => 'BAHAMAS',
          'BH' => 'BAHRAIN',
          'BD' => 'BANGLADESH',
          'BB' => 'BARBADOS',
          'BY' => 'BELARUS',
          'BE' => 'BELGIUM',
          'BZ' => 'BELIZE',
          'BJ' => 'BENIN',
          'BM' => 'BERMUDA',
          'BT' => 'BHUTAN',
          'BO' => 'BOLIVIA',
          'BA' => 'BOSNIA AND HERZEGOVINA',
          'BW' => 'BOTSWANA',
          'BV' => 'BOUVET ISLAND',
          'BR' => 'BRAZIL',
          'BN' => 'BRUNEI DARUSSALAM',
          'BG' => 'BULGARIA',
          'BF' => 'BURKINA FASO',
          'BI' => 'BURUNDI',
          'KH' => 'CAMBODIA',
          'CM' => 'CAMEROON',
          'CA' => 'CANADA',
          'CV' => 'CAPE VERDE',
          'KY' => 'CAYMAN ISLANDS',
          'CF' => 'CENTRAL AFRICAN REPUBLIC',
          'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
          'CL' => 'CHILE',
          'CN' => 'CHINA',
          'CX' => 'CHRISTMAS ISLAND',
          'CO' => 'COLOMBIA',
          'KM' => 'COMOROS',
          'CG' => 'CONGO, REPUBLIC OF',
          'CK' => 'COOK ISLANDS',
          'CR' => 'COSTA RICA',
          'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
          'HR' => 'CROATIA (Hrvatska)',
          'CU' => 'CUBA',
          'CW' => 'CURAÇAO',
          'CY' => 'CYPRUS',
          'CZ' => 'ZECH REPUBLIC',
          'DK' => 'DENMARK',
          'DJ' => 'DJIBOUTI',
          'DM' => 'DOMINICA',
          'DC' => 'DOMINICAN REPUBLIC',
          'EC' => 'ECUADOR',
          'EG' => 'EGYPT',
          'SV' => 'EL SALVADOR',
          'GQ' => 'EQUATORIAL GUINEA',
          'ER' => 'ERITREA',
          'EE' => 'ESTONIA',
          'ET' => 'ETHIOPIA',
          'FO' => 'FAEROE ISLANDS',
          'FJ' => 'FIJI',
          'FI' => 'FINLAND',
          'FR' => 'FRANCE',
          'GF' => 'FRENCH GUIANA',
          'GA' => 'GABON',
          'GM' => 'GAMBIA, THE',
          'GE' => 'GEORGIA',
          'DE' => 'GERMANY (Deutschland)',
          'GH' => 'GHANA',
          'GI' => 'GIBRALTAR',
          'GR' => 'GREECE',
          'GL' => 'GREENLAND',
          'GD' => 'GRENADA',
          'GP' => 'GUADELOUPE',
          'GU' => 'GUAM',
          'GT' => 'GUATEMALA',
          'GG' => 'GUERNSEY',
          'GN' => 'GUINEA',
          'GW' => 'GUINEA-BISSAU',
          'GY' => 'GUYANA',
          'HT' => 'HAITI',
          'HN' => 'HONDURAS',
          'HK' => 'HONG KONG (Special Administrative Region of China)',
          'HU' => 'HUNGARY',
          'IS' => 'ICELAND',
          'IN' => 'INDIA',
          'ID' => 'INDONESIA',
          'IR' => 'IRAN (Islamic Republic of Iran)',
          'IQ' => 'IRAQ',
          'IE' => 'IRELAND',
          'IM' => 'ISLE OF MAN',
          'IL' => 'ISRAEL',
          'IT' => 'ITALY',
          'JM' => 'JAMAICA',
          'JP' => 'JAPAN',
          'JE' => 'JERSEY',
          'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
          'KZ' => 'KAZAKHSTAN',
          'KE' => 'KENYA',
          'KI' => 'KIRIBATI',
          'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
          'KR' => 'KOREA (Republic of [South] Korea)',
          'KW' => 'KUWAIT',
          'KG' => 'KYRGYZSTAN',
          'LV' => 'LATVIA',
          'LB' => 'LEBANON',
          'LS' => 'LESOTHO',
          'LR' => 'LIBERIA',
          'LY' => 'LIBYA (Libyan Arab Jamahirya)',
          'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
          'LT' => 'LITHUANIA',
          'LU' => 'LUXEMBOURG',
          'MO' => 'MACAO (Special Administrative Region of China)',
          'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
          'MG' => 'MADAGASCAR',
          'MW' => 'MALAWI',
          'MY' => 'MALAYSIA',
          'MV' => 'MALDIVES',
          'ML' => 'MALI',
          'MT' => 'MALTA',
          'MH' => 'MARSHALL ISLANDS',
          'MQ' => 'MARTINIQUE',
          'MR' => 'MAURITANIA',
          'MU' => 'MAURITIUS',
          'YT' => 'MAYOTTE',
          'MX' => 'MEXICO',
          'FM' => 'MICRONESIA (Federated States of Micronesia)',
          'MD' => 'MOLDOVA',
          'MC' => 'MONACO',
          'MN' => 'MONGOLIA',
          'ME' => 'MONTENEGRO',
          'MS' => 'MONTSERRAT',
          'MA' => 'MOROCCO',
          'MZ' => 'MOZAMBIQUE (Moçambique)',
          'MM' => 'MYANMAR (formerly Burma)',
          'NA' => 'NAMIBIA',
          'NR' => 'NAURU',
          'NP' => 'NEPAL',
          'NL' => 'NETHERLANDS',
          'AN' => 'NETHERLANDS ANTILLES (obsolete)',
          'NC' => 'NEW CALEDONIA',
          'NZ' => 'NEW ZEALAND',
          'NI' => 'NICARAGUA',
          'NE' => 'NIGER',
          'NG' => 'NIGERIA',
          'NU' => 'NIUE',
          'NF' => 'NORFOLK ISLAND',
          'MP' => 'NORTHERN MARIANA ISLANDS',
          'ND' => 'NORWAY',
          'OM' => 'OMAN',
          'PK' => 'PAKISTAN',
          'PW' => 'PALAU',
          'PS' => 'PALESTINIAN TERRITORIES',
          'PA' => 'PANAMA',
          'PG' => 'PAPUA NEW GUINEA',
          'PY' => 'PARAGUAY',
          'PE' => 'PERU',
          'PH' => 'PHILIPPINES',
          'PN' => 'PITCAIRN',
          'PL' => 'POLAND',
          'PT' => 'PORTUGAL',
          'PR' => 'PUERTO RICO',
          'QA' => 'QATAR',
          'RE' => 'RÉUNION',
          'RO' => 'ROMANIA',
          'RU' => 'RUSSIAN FEDERATION',
          'RW' => 'RWANDA',
          'BL' => 'SAINT BARTHÉLEMY',
          'SH' => 'SAINT HELENA',
          'KN' => 'SAINT KITTS AND NEVIS',
          'LC' => 'SAINT LUCIA',
          'PM' => 'SAINT PIERRE AND MIQUELON',
          'VC' => 'SAINT VINCENT AND THE GRENADINES',
          'WS' => 'SAMOA (formerly Western Samoa)',
          'SM' => 'SAN MARINO (Republic of)',
          'ST' => 'SAO TOME AND PRINCIPE',
          'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
          'SN' => 'SENEGAL',
          'RS' => 'SERBIA (Republic of Serbia)',
          'SC' => 'SEYCHELLES',
          'SL' => 'SIERRA LEONE',
          'SG' => 'SINGAPORE',
          'SX' => 'SINT MAARTEN',
          'SK' => 'SLOVAKIA (Slovak Republic)',
          'SI' => 'SLOVENIA',
          'SB' => 'SOLOMON ISLANDS',
          'SO' => 'SOMALIA',
          'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
          'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
          'SS' => 'SOUTH SUDAN',
          'ES' => 'SPAIN (España)',
          'LK' => 'SRI LANKA (formerly Ceylon)',
          'SD' => 'SUDAN',
          'SR' => 'SURINAME',
          'SJ' => 'SVALBARD AND JAN MAYE',
          'SZ' => 'SWAZILAND',
          'SE' => 'SWEDEN',
          'CH' => 'SWITZERLAND (Confederation of Helvetia)',
          'SY' => 'SYRIAN ARAB REPUBLIC',
          'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
          'TJ' => 'TAJIKISTAN',
          'TZ' => 'TANZANIA',
          'TH' => 'THAILAND',
          'TL' => 'TIMOR-LESTE (formerly East Timor)',
          'TG' => 'TOGO',
          'TK' => 'TOKELAU',
          'TO' => 'TONGA',
          'TT' => 'TRINIDAD AND TOBAGO',
          'TN' => 'TUNISIA',
          'TR' => 'TURKEY',
          'TM' => 'TURKMENISTAN',
          'TC' => 'TURKS AND CAICOS ISLANDS',
          'TV' => 'TUVALU',
          'UG' => 'UGANDA',
          'UA' => 'UKRAINE',
          'AE' => 'UNITED ARAB EMIRATES',
          'US' => 'UNITED STATES',
          'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
          'UK' => 'UNITED KINGDOM',
          'UY' => 'URUGUAY',
          'UZ' => 'UZBEKISTAN',
          'VU' => 'VANUATU',
          'VA' => 'VATICAN CITY (Holy See)',
          'VN' => 'VIET NAM',
          'VG' => 'VIRGIN ISLANDS, BRITISH',
          'VI' => 'VIRGIN ISLANDS, U.S.',
          'WF' => 'WALLIS AND FUTUNA',
          'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
          'YE' => 'YEMEN (Yemen Arab Republic)',
          'ZW' => 'ZIMBABWE'
        );
        return $arrayCountries;
    }
    public function getLanguageNames()
    {
        $arrayLanguages = array(
        'ar-XA'=>'Arabic',
        'bg'=>'Bulgarian',
        'hr'=>'Croatian',
        'cs'=>'Czech',
        'da'=>'Danish',
        'de'=>'German',
        'el'=>'Greek',
        'en'=>'English',
        'et'=>'Estonian',
        'es'=>'Spanish',
        'fi'=>'Finnish',
        'fr'=>'French',
        'in'=>'Indonesian',
        'ga'=>'Irish',
        'hr'=>'Hindi',
        'hu'=>'Hungarian',
        'he'=>'Hebrew',
        'it'=>'Italian',
        'ja'=>'Japanese',
        'ko'=>'Korean',
        'lv'=>'Latvian',
        'lt'=>'Lithuanian',
        'nl'=>'Dutch',
        'no'=>'Norwegian',
        'pl'=>'Polish',
        'pt'=>'Portuguese',
        'sv'=>'Swedish',
        'ro'=>'Romanian',
        'ru'=>'Russian',
        'sr-CS'=>'Serbian',
        'sk'=>'Slovak',
        'sl'=>'Slovenian',
        'th'=>'Thai',
        'tr'=>'Turkish',
        'uk-UA'=>'Ukrainian',
        'zh-chs'=>'Chinese (Simplified)',
        'zh-cht'=>'Chinese (Traditional)'
        );
        return $arrayLanguages;
    }
    public function getLanguageAndCountryNames()
    {
        $config = array(
            'default' => 'Default',
            'af_ZA' => 'Afrikaans',
            'ar_AR' => 'Arabic',
            'az_AZ' => 'Azerbaijani',
            'be_BY' => 'Belarusian',
            'bg_BG' => 'Bulgarian',
            'bn_IN' => 'Bengali',
            'bs_BA' => 'Bosnian',
            'ca_ES' => 'Catalan',
            'cs_CZ' => 'Czech',
            'cy_GB' => 'Welsh',
            'da_DK' => 'Danish',
            'de_DE' => 'German',
            'el_GR' => 'Greek',
            'en_GB' => 'English (UK)',
            'en_PI' => 'English (Pirate)',
            'en_UD' => 'English (Upside Down)',
            'en_US' => 'English (US)',
            'eo_EO' => 'Esperanto',
            'es_ES' => 'Spanish (Spain)',
            'es_LA' => 'Spanish',
            'et_EE' => 'Estonian',
            'eu_ES' => 'Basque',
            'fa_IR' => 'Persian',
            'fb_LT' => 'Leet Speak',
            'fi_FI' => 'Finnish',
            'fo_FO' => 'Faroese',
            'fr_CA' => 'French (Canada)',
            'fr_FR' => 'French (France)',
            'fy_NL' => 'Frisian',
            'ga_IE' => 'Irish',
            'gl_ES' => 'Galician',
            'he_IL' => 'Hebrew',
            'hi_IN' => 'Hindi',
            'hr_HR' => 'Croatian',
            'hu_HU' => 'Hungarian',
            'hy_AM' => 'Armenian',
            'id_ID' => 'Indonesian',
            'is_IS' => 'Icelandic',
            'it_IT' => 'Italian',
            'ja_JP' => 'Japanese',
            'ka_GE' => 'Georgian',
            'km_KH' => 'Khmer',
            'ko_KR' => 'Korean',
            'ku_TR' => 'Kurdish',
            'la_VA' => 'Latin',
            'lt_LT' => 'Lithuanian',
            'lv_LV' => 'Latvian',
            'mk_MK' => 'Macedonian',
            'ml_IN' => 'Malayalam',
            'ms_MY' => 'Malay',
            'nb_NO' => 'Norwegian (bokmal)',
            'ne_NP' => 'Nepali',
            'nl_NL' => 'Dutch',
            'nn_NO' => 'Norwegian (nynorsk)',
            'pa_IN' => 'Punjabi',
            'pl_PL' => 'Polish',
            'ps_AF' => 'Pashto',
            'pt_BR' => 'Portuguese (Brazil)',
            'pt_PT' => 'Portuguese (Portugal)',
            'ro_RO' => 'Romanian',
            'ru_RU' => 'Russian',
            'sk_SK' => 'Slovak',
            'sl_SI' => 'Slovenian',
            'sq_AL' => 'Albanian',
            'sr_RS' => 'Serbian',
            'sv_SE' => 'Swedish',
            'sw_KE' => 'Swahili',
            'ta_IN' => 'Tamil',
            'te_IN' => 'Telugu',
            'th_TH' => 'Thai',
            'tl_PH' => 'Filipino',
            'tr_TR' => 'Turkish',
            'uk_UA' => 'Ukrainian',
            'vi_VN' => 'Vietnamese',
            'zh_CN' => 'Chinese (China)',
            'zh_HK' => 'Chinese (Hong Kong)',
            'zh_TW' => 'Chinese (Taiwan)',
        );
        asort($config);
        return $config;
    }
    public function getEnumValues( $table, $field )
    {
        $type = $this->db->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->row( 0 )->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }
    public function _paymentPackage()
    {
        $paymentPackage=$this->common->readData("package",$where=array("where"=>array("is_default"=>"0","price > "=>0)),$select='',$join='',$limit='',$start=NULL,$order_by='price');
        $returnVal=array();
        $config_data=$this->common->readData("payment_configuration");
        $currency=$config_data[0]["currency"];
        foreach ($paymentPackage as $row)
        {
            $returnVal[$row['id']]=$row['package_name']." : Only @".$currency." ".$row['price']." for ".$row['validity']." days";
        }
        return $returnVal;
    }
}