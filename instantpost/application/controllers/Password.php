<?php 
require_once("Main.php");
class Password extends Main
{
	public $userId;
	public function __construct()
    {
        parent::__construct();
        $this->userId=$this->session->userdata('userId');
        if ($this->session->userdata('loggedIn')!= 1) {
            redirect('main/login', 'location');
        }
    }
    public function index()
    {
        $this->changePassword();
    }
    public function changePassword()
    {
    	$data['pageTitle'] = "Change password";
    	$data['body'] = 'admin/user/change_password_form';
    	$this->_mainView($data);
    }
    public function changePasswordAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        $this->form_validation->set_rules('oldPassword', '<b>Old password</b>', 'trim|required');
        $this->form_validation->set_rules('newPassword', '<b>New password</b>', 'trim|required');
        $this->form_validation->set_rules('confirmNewPassword', '<b>Confirm password</b>', 'trim|required|matches[newPassword]');
        if ($this->form_validation->run() == false) {
            $this->changePassword();
        } else {
            $userId = $this->userId;
            $password = strip_tags($this->input->post('oldPassword', true));
            $newPassword = strip_tags($this->input->post('newPassword', true));
            $table = 'users';
            $where['where'] = array(
                'id' => $userId,
                'password' => md5($password)
                );
            $select = array('');
            if ($this->common->readData($table, $where, $select)) {
                $where = array(
                    'id' => $userId,
                    'password' => md5($password)
                    );
                $data = array('password' => md5($newPassword));
                $this->common->updateData($table, $where, $data);
                $this->session->set_userdata('loggedIn', 0);
                $this->session->set_flashdata('resetSuccess', 'Please login with new password');
                redirect('main/login', 'location');
            } else {
                $this->session->set_userdata('error', 'The old password you have given is wrong');
                $this->changePassword();
            }
        }
    }
}