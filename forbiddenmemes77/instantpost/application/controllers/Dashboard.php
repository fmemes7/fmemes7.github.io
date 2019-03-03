<?php
require_once("Main.php"); // including main controller
/**
* class dashboard
* @category controller
*/
class Dashboard extends Main
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
        set_time_limit(0);
        // die();
    }
    /**
    * load index method for dashboard view
    * @access public
    * @return void
    */
    public function index()
    {
        $this->instantDashboard();
    }
    /**
    * load instantDashboard method for dashboard view
    * @access public
    * @return void
    */
    public function instantDashboard()
    {
        $account_info = $this->common->readData('instagram_account_info', ['where'=>['user_id'=>$this->userId, 'deleted'=>'0']], ['id']);
        $data['account_number'] = count($account_info);
        $auto_post = $this->common->readData('instagram_auto_post', ['where'=>['user_id'=>$this->userId]], ['id']);
        $data['auto_post'] = count($auto_post);
        $story_post = $this->common->readData('instagram_story_post', ['where'=>['user_id'=>$this->userId]], ['id']);
        $data['story_post'] = count($story_post);
        $story_poll_post = $this->common->readData('instagram_story_poll_post', ['where'=>['user_id'=>$this->userId]], ['id']);
        $data['story_poll_post'] = count($story_poll_post);
        $colorsArray = array("rgba(241, 148, 138,0.6)", "rgba(155, 89, 182,0.6)", "rgba(0, 255, 153,0.6)", "rgba(255, 102, 204,0.6)", "rgba(0, 153, 204,0.6)", "rgba(0, 0, 102  ,0.6)", "rgba(112, 123, 124  ,0.6)", "rgba(131, 145, 146,0.6)", "rgba(202, 207, 210  ,0.6)", "rgba(120, 66, 18,0.6)", "rgba(245, 203, 167  ,0.6)", "rgba(212, 172, 13  ,0.6)", "rgba(252, 243, 207  ,0.6)", "rgba(20, 90, 50,0.6)", "rgba(82, 190, 128,0.6)", "rgba(17, 120, 100 ,0.6)", "rgba(118, 215, 196,0.6)", "rgba(21, 67, 96,0.6)", "rgba(169, 204, 227,0.6)", "rgba(74, 35, 90,0.6)");
        $data['body'] = 'dashboard';
        $data['pageTitle'] = 'Dashboard';
        $this->_mainView($data);
    }
}