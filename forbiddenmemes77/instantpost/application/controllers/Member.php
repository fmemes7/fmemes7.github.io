<?php
require_once("Main.php");
/**
* class Member
* @category controller
*/
class Member extends Main
{
    /**
    * load constructor method
    * @access public
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('loggedIn')!= 1) {
            redirect('main/loginPage', 'location');
        }
   }
    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */
    public function index()
    {
        $this->accountSetting();
    }
    public function profile()
    {
        $data['body'] = "member/my_profile";
        $data['pageTitle'] = 'My Profile';
        $table = "users";
        $where = array("where"=>array("id"=>$this->session->userdata("userId")));
        $data["profileInfo"]=$this->common->readData($table,$where);
        $this->_mainView($data);
    }
 
    public function accountSetting()
    {      
        $data['body'] = "member/account_setting";
        $data['pageTitle'] = 'User';
        $table = "users";
        $where = array("where"=>array("id"=>$this->session->userdata("userId")));
        $data["profileInfo"]=$this->common->readData($table,$where);
        $this->_mainView($data);
    }
    public function accountSettingAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        if ($_POST) {
            $this->form_validation->set_rules('name', '<b>Name</b>', 'trim|required');
            $this->form_validation->set_rules('phone', '<b>Phone</b>', 'trim');
            $this->form_validation->set_rules('my_note', '<b>my note</b>', 'trim');
            $this->form_validation->set_rules('email', '<b>Email</b>', 'trim|required|valid_email|callback_unique_email_check['.$this->session->userdata('userId').']');
            $this->form_validation->set_rules('address', '<b>address</b>', 'trim');
            $this->form_validation->set_rules('gender', '<b>Gender</b>', 'trim');
            
            if ($this->form_validation->run() == false) 
            {
                return $this->accountSetting();
            } 
            else 
            {
                // assign
                $name=addslashes(strip_tags($this->input->post('name', true)));
                $phone=addslashes(strip_tags($this->input->post('phone', true)));
                $email=addslashes(strip_tags($this->input->post('email', true)));
                $my_note=addslashes(strip_tags($this->input->post('my_note', true)));
                $address=addslashes(strip_tags($this->input->post('address', true)));
                $gender=addslashes(strip_tags($this->input->post('gender', true)));
                $base_path=FCPATH . 'assets/img/member';
                if(!file_exists($base_path)) mkdir($base_path,0755);
                $this->load->library('upload');
                $photo="";
                if ($_FILES['logo']['size'] != 0) {
                    $photo = $this->session->userdata("userId").".png";
                    $config = array(
                        "allowed_types" => "png",
                        "upload_path" => $base_path,
                        "overwrite" => true,
                        "file_name" => $photo,
                        'max_size' => '200',
                        'max_width' => '500',
                        'max_height' => '500'
                        );
                    $this->upload->initialize($config);
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('logo')) {
                        $this->session->set_userdata('logoError', $this->upload->display_errors());
                        return $this->accountSetting();
                    }
                }
                $updateData=array
                (
                    "name"=>$name,
                    "phone"=>$phone,
                    "email"=>$email,
                    "my_note"=>$my_note,
                    "gender"=>$gender,
                    "address"=>$address
                );
                if($photo!="") $updateData["user_logo"] = $photo;
 
                $this->common->updateData("users",array("id"=>$this->session->userdata("userId")),$updateData);
                     
                $this->session->set_flashdata('successMessage', 1);
                redirect('member', 'location');
            }
        }
    }
    public function unique_email_check($str, $edited_id)
    {
        $email= strip_tags(trim($this->input->post('email',TRUE)));
        if($email==""){
            $s= $this->lang->line("required");
            $s=str_replace("<b>%s</b>","",$s);
            $s="<b>".$this->lang->line("email")."</b> ".$s;
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
        }
        
        if(!isset($edited_id) || !$edited_id) {
            $where=array("email"=>$email);
        } else {
            $where=array("email"=>$email,"id !="=>$edited_id);
        }
        
        
        $is_unique=$this->common->isUnique("users",$where,$select='');
        
        if (!$is_unique) {
            $s = $this->lang->line("is_unique");
            $s=str_replace("<b>%s</b>","",$s);
            $s="<b>".$this->lang->line("email")."</b> ".$s;
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
        }                
        return TRUE;
    }
}
