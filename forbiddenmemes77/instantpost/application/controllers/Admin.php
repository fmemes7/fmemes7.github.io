<?php
require_once("Main.php");
/**
* class Admin
* @category controller
*/
class Admin extends Main
{
    public $userId;
    /**
     * Load constructor method
     * @access public
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('loggedIn') != 1) {
            redirect('main/loginPage', 'location');        
        }
        if ($this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        
        $this->load->helper('form');
        $this->load->library('upload');
        
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->userId=$this->session->userdata('userId');
        set_time_limit(0);
    }
    public function packageSettings()
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') 
        redirect('main/loginPage', 'location');
        $data['body']='admin/payment/package_list';
        $data['paymentConfiguration']=$this->common->readData('payment_configuration');
        $this->_mainView($data); 
    }
    public function packageData()
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') 
        redirect('main/loginPage', 'location');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'package_name';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'ASC';
        $order_by_str=$sort." ".$order;
        $offset = ($page-1)*$rows;
        $info=$this->common->readData('package',$where='',$select='',$join='',$limit=$rows,$start=$offset,$order_by=$order_by_str,$group_by='',$num_rows=1);
        $totalRowsArray=$this->common->countRow($table="package",$where='',$count="package.id");
        $totalResult=$totalRowsArray[0]['total_rows'];            
        echo convertToGridData($info,$totalResult);
    }
    public function addPackage()
    {
        $data['body']='admin/payment/add_package';
        $data['pageTitle']='Package Settings';
        $data['modules']=$this->common->readData('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['payment_configuration']=$this->common->readData('payment_configuration');
        $this->_mainView($data);
    }
    public function addPackageAction()
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') 
        redirect('main/loginPage', 'location');
        if($_SERVER['REQUEST_METHOD'] === 'GET') 
        redirect('main/accessForbidden','location');
        if($_POST) {
            $this->form_validation->set_rules('name', '<b>Package name</b>', 'trim|required');   
            $this->form_validation->set_rules('price', '<b>Price</b>', 'trim|required');
            $this->form_validation->set_rules('validity', '<b>Validity</b>', 'trim|required|integer');   
            $this->form_validation->set_rules('modules[]','<b>Modules</b>','trim|required');       
                
            if ($this->form_validation->run() == FALSE) {
                $this->addPackage(); 
            } else {
                $packageName=$this->input->post('name');
                $price=$this->input->post('price');
                $validity=$this->input->post('validity');                
                $modules=array();
                if(count($this->input->post('modules'))>0)  
                {
                   $modules=$this->input->post('modules');                            
                }
                $bulkLimit=array();
                $monthlyLimit=array();
                foreach ($modules as $value) 
                {
                    $monthly_field="monthly_".$value;                   
                    $val=$this->input->post($monthly_field);
                    if($val=="") $val=0;
                    $monthlyLimit[$value]=$val;              
                    $bulk_field="bulk_".$value;                    
                    $val=$this->input->post($bulk_field);
                    if($val=="") $val=0;
                    $bulkLimit[$value]=$val;                    
                }
                $modulesStr=implode(',',$modules);      
                $data=array
                (
                    'package_name'=>$packageName,
                    'price'=>$price,
                    'validity'=>$validity,
                    'module_ids'=>$modulesStr,
                    'monthly_limit'=>json_encode($monthlyLimit),
                    'bulk_limit'=>json_encode($bulkLimit)
                );                
                if($this->common->createData('package',$data))                                      
                $this->session->set_flashdata('successMessage',1);   
                else    
                $this->session->set_flashdata('errorMessage',1);
                redirect('admin/packageSettings','location');
            }
        } 
    }
    public function detailsPackage($id=0)
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        if($id==0) {
            redirect('main/accessForbidden','location');
        }
        $data['body']='admin/payment/details_package';
        $data['modules']=$this->common->readData('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['value']=$this->common->readData('package',$where=array("where"=>array("id"=>$id)));
        $data['paymentConfiguration']=$this->common->readData('payment_configuration');
        $this->_mainView($data); 
    }
    public function updatePackage($id=0)
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        if($id==0) {
            redirect('main/accessForbidden','location');
        }
        $data['body']='admin/payment/update_package';
        $data['pageTitle']='Package Settings';
        $data['modules']=$this->common->readData('modules',$where='',$select='',$join='',$limit='',$start='',$order_by='module_name asc',$group_by='',$num_rows=0);
        $data['value']=$this->common->readData('package',$where=array("where"=>array("id"=>$id)));
        $data['paymentConfiguration']=$this->common->readData('payment_configuration');
        $this->_mainView($data);
    }
    public function updatePackageAction()
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden','location');
        }
        if($_POST)
        {
            $id=$this->input->post("id");
            $this->form_validation->set_rules('name', '<b>Package Name</b>', 'trim|required');  
            $this->form_validation->set_rules('modules','<b>Modules</b>','trim');   
            $this->form_validation->set_rules('price', '<b>Price</b>', 'trim|required'); 
            
            if($this->input->post("is_default")=="1" && $this->input->post("price")=="Trial")    
            $this->form_validation->set_rules('validity', '<b>Validity</b>', 'trim|required|integer');   
            
            if ($this->form_validation->run() == FALSE) {
                $this->updatePackage($id); 
            } else {
                $packageName=$this->input->post('name');
                $validity=$this->input->post('validity');
                $price=$this->input->post('price');                
                $modules=array();
                if(count($this->input->post('modules'))>0) {
                   $modules=$this->input->post('modules');                            
                }
                $bulk_limit=array();
                $monthly_limit=array();
                foreach ($modules as $value) {
                    $monthly_field="monthly_".$value;                   
                    $val=$this->input->post($monthly_field);
                    if($val=="") $val=0;
                    $monthly_limit[$value]=$val;
                    $bulk_field="bulk_".$value;                    
                    $val=$this->input->post($bulk_field);
                    if($val=="") $val=0;
                    $bulk_limit[$value]=$val;                    
                }
                $modules_str=implode(',',$modules);
                if($this->input->post("is_default")=="1" && $this->input->post("price")=="0") 
                $validity="0"; 
                $data=array
                (
                    'package_name'=>$packageName,
                    'validity'=>$validity,
                    'module_ids'=>$modules_str,
                    'price'=>$price,
                    'monthly_limit'=>json_encode($monthly_limit),
                    'bulk_limit'=>json_encode($bulk_limit)
                );
                
                if($this->common->updateData('package',array("id"=>$id),$data))                                      
                $this->session->set_flashdata('successMessage',1);   
                else    
                $this->session->set_flashdata('errorMessage',1);     
                
                redirect('admin/packageSettings','location');                 
                
            }
        }  
    }
    public function deletePackage($id=0)
    {
        if($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') 
        redirect('main/loginPage', 'location');
        if($id==0) 
        redirect('main/accessForbidden','location');
        if($this->common->updateData('package',array("id"=>$id,"is_default"=>"0"),array("deleted"=>"1"))) {
            $this->session->set_flashdata('deleteSuccessMessage',1); 
        } else {
            $this->session->set_flashdata('deleteErrorMessage',1);
        }
        redirect('admin/packageSettings','location');
    }
    public function sendEmail()
    {
        $data['body'] = 'admin/send_email_to_users';
        $data['page_title'] = 'Send email to users';
        $this->_mainView($data);
    }
    public function sendEmailDataLoader()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 15;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
        $order_by_str = $sort." ".$order;
        $fName = trim($this->input->post('fName', true));
        $isSearched= $this->input->post('isSearched', true);
        if ($isSearched) {
            $this->session->set_userdata('sendEmailFname', $fName);
        }
        $searchFname = $this->session->userdata('sendEmailFname');
        $whereSimple = array();
        if ($searchFname) {
            $whereSimple['name like'] = $searchFname."%";
        }
        $whereSimple['deleted'] = '0';
        $where = array('where' => $whereSimple);
        $offset = ($page-1)*$rows;
        $result = array();
        $table = "users";
        $info = $this->common->readData($table, $where, $select = '', $join='', $limit = $rows, $start = $offset, $order_by = $order_by_str);
        $totalRowsArray = $this->common->countRow($table, $where, $count = "id");
        $totalResult = $totalRowsArray[0]['total_rows'];
        echo convertToGridData($info, $totalResult);
    }
    public function sendEmailMember()
    {        
        if($_POST)
        {
            $subject= $this->input->post('subject');
            $content= $this->input->post('content');
            $info=$this->input->post('info');
            $info=json_decode($info,TRUE);
            $count=0;            
            foreach($info as $member) {               
                $email=$member['email'];
                $member_id=$member['id'];                
                $message=$content;
                $from=$this->config->item('companyEmail');
                $to=$email;
                $mask=$this->config->item('companyAddress');                
                if($message=="" || $from=="" || $to=="" || $subject=="") continue;
                if($this->_mailSender($from,$to,$subject,$message,$mask))  $count++;               
            }
            echo "<b> $count / ".count($info)." : Email sent successfully</b>";           
        }   
    }
    public function userManagement()
    {
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('users');
        $crud->order_by('id');
        $crud->where('users.deleted', '0');
        $crud->set_subject($this->lang->line("User"));
        $crud->set_relation('package_id','package','package_name',array('package.deleted' => '0'));
        $crud->fields('name', 'email', 'phone', 'password', 'address', 'user_type', 'status');
        $crud->edit_fields('name', 'email', 'phone', 'address','expired_date','package_id', 'status');
        $crud->add_fields('name', 'email', 'phone', 'password', 'address', 'user_type', 'status');
        $crud->required_fields('name', 'email', 'password', 'user_type','expired_date','package_id', 'status');
        $crud->columns('name', 'email','package_id', 'status', 'user_type', 'add_date','last_login_at','last_login_ip','expired_date');
        $crud->field_type('password', 'password');
        $crud->field_type('expired_date', 'date');
        $crud->display_as('add_date',$this->lang->line('Register date'));
        $crud->display_as('last_login_at',$this->lang->line('Last Logged in'));
        $crud->display_as('last_login_ip',$this->lang->line('Last IP'));
        $crud->display_as('name', $this->lang->line('Name'));
        $crud->display_as('email', $this->lang->line('Email'));
        $crud->display_as('phone', $this->lang->line('Mobile'));
        $crud->display_as('address', $this->lang->line('Address'));
        $crud->display_as('status', $this->lang->line('Status'));
        $crud->display_as('user_type', $this->lang->line('Type'));
        $crud->display_as('password', $this->lang->line('Password'));
        $crud->display_as('package_id', $this->lang->line('Package name'));
        $crud->display_as('expired_date', $this->lang->line('Expiry date'));
        $crud->unset_texteditor('address');
        $crud->set_rules("email","email",'valid_email');
        $images_url = "<i class='fa fa-key'></i>";
        $crud->add_action('Change User Password', $images_url, 'admin/changeUserPassword');
        $crud->callback_column('expired_date', array($this, 'expiredDateDisplayCrud'));
        $crud->callback_field('expired_date', array($this, 'expiredDateFieldCrud'));
        $crud->callback_column('add_date', array($this, 'registeredDateDisplayCrud'));
        $crud->callback_column('last_login_at', array($this, 'lastLoginDateDisplayCrud'));
        $crud->callback_column('status', array($this, 'statusDisplayCrud'));
        $crud->callback_field('status', array($this, 'statusFieldCrud'));
        $crud->callback_after_insert(array($this, 'encriptPassword'));
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();
        $output = $crud->render();
        $data['output']=$output;
        $data['pageTitle'] = 'User management';
        $data['crud']=1;
        $this->_mainView($data);
    }
    function unique_email_check($str, $edited_id)
    {
        $email= strip_tags(trim($this->input->post('email',TRUE)));
        if($email==""){
            $s= "Required";
            $s=str_replace("<b>%s</b>","",$s);
            $s="<b>Email</b> ".$s;
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
        }
        
        if(!isset($edited_id) || !$edited_id)
            $where=array("email"=>$email);
        else        
            $where=array("email"=>$email,"id !="=>$edited_id);
        
        
        $is_unique=$this->common->isUnique("users",$where,$select='');
        
        if (!$is_unique) {
            $s = $this->lang->line("is_unique");
            $s=str_replace("<b>%s</b>","",$s);
            $s="<b>Email</b> ".$s;
            $this->form_validation->set_message('unique_email_check', $s);
            return FALSE;
            }
                
        return TRUE;
    }
    public function changeUserPassword($id)
    {
        $this->session->set_userdata('changeUserPasswordId', $id);
        $table = 'users';
        $where['where'] = array('id' => $id);
        $info = $this->common->readData($table, $where);
        $data['user_name'] = $info[0]['name'];
        $data['body'] = 'admin/user/change_user_password';
        $data['pageTitle'] =  'Change Password';
        $this->_mainView($data);
    }
    public function changeUserPasswordAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessForbidden', 'location');
        }
        $id = $this->session->userdata('changeUserPasswordId');
        if ($_POST) {
            $this->form_validation->set_rules('password', '<b>Password</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>Confirm password</b>', 'trim|required|matches[password]');
        }
        if ($this->form_validation->run() == false) {
            $this->changeUserPassword($id);
        } else {
            $newPassword = $this->input->post('password', true);
            $newConfirmPassword = $this->input->post('confirm_password', true);
            $tableChangePassword = 'users';
            $whereChangePassword = array('id' => $id);
            $data = array('password' => md5($newPassword));
            $this->common->updateData($tableChangePassword, $whereChangePassword, $data);
            $where['where'] = array('id' => $id);
            $mailInfo = $this->common->readData('users', $where);            
            $name = $mailInfo[0]['name'];
            $to = $mailInfo[0]['email'];
            $password = $newPassword;
            $subject = 'Change Password Notification';
            $mask = $this->config->item('itemName');
            $from = $this->config->item('companyEmail');
            $url = site_url();
            $message = "Dear {$name},<br/> Your <a href='".$url."'>{$mask}</a> password has been changed. Your new password is: {$password}.<br/><br/> Thank you.";
            $this->_mailSender($from, $to, $subject, $message, $mask);
            $this->session->set_flashdata('successMessage', 1);
            redirect('admin/userManagement', 'location');
        }
    }
    public function expiredDateDisplayCrud($value, $row)
    {
        if($row->user_type=="Admin") return "N/A";
        if ($value == '0000-00-00 00:00:00') {
            $value = "-";
        }
        else $value=date("Y-m-d",strtotime($value));
        return $value;
    }
    public function expiredDateFieldCrud($value, $row)
    {
        if ($value == '0000-00-00 00:00:00') {
            $value = "";
        }
        else $value=date("Y-m-d",strtotime($value));
        return '<input id="field-expired_date" type="text" maxlength="100" value="'.$value.'" name="expired_date">';
    }
    public function registeredDateDisplayCrud($value, $row)
    {
        if ($value == '0000-00-00 00:00:00') {
            $value = "-";
        }
        else $value=date("Y-m-d H:i",strtotime($value));
        return $value;
    }
    public function lastLoginDateDisplayCrud($value, $row)
    {        
        if ($value == '0000-00-00 00:00:00') {
            $value = "-";
        }
        else $value=date("Y-m-d H:i",strtotime($value));
        return $value;
    }
    public function encriptPassword($post_array, $primary_key)
    {
        $id = $primary_key;
        $where = array('id'=>$id);
        $password = md5($post_array['password']);
        $table = 'users';
        $data = array('password'=>$password);
        $this->common->updateData($table, $where, $data);
        return true;
    }
    public function paymentConfiguration()
    {
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
            redirect('main/loginPage', 'location');
        }
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('payment_configuration');
        $crud->order_by('id');
        $crud->where('deleted', '0');
        $crud->set_subject($this->lang->line("Payment Configuration"));
        $crud->required_fields('currency');
        $crud->columns('paypal_email','stripe_secret_key','stripe_publishable_key','currency');     
        $crud->fields('paypal_email','stripe_secret_key','stripe_publishable_key','currency');
        $crud->display_as('paypal_email',$this->lang->line('Paypal Email'));
        $crud->display_as('currency',$this->lang->line('Currency'));
        $crud->display_as('stripe_secret_key',$this->lang->line('Stripe Secret Key'));
        $crud->display_as('stripe_publishable_key',$this->lang->line('Stripe Publishable Key'));
        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_read();
        $crud->unset_print();
        $crud->unset_export();
        $output = $crud->render();
        $data['output']=$output;
        $data['pageTitle']="Payment Configuration";
        $data['crud']=1;
        $this->_mainView($data);
    }
    public function generalApplicationConfiguration()
    {
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
           redirect('main/loginPage', 'location');
        }
        $data['body'] = "admin/application_configuration";
        $data['timeZone'] = $this->_timeZones();
        $data['pageTitle'] = "Application Configuration";
        $this->_mainView($data);
    }
    public function generalApplicationConfigurationAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('main/accessAorbidden', 'location');
        }
        if ($_POST) {
            $this->form_validation->set_rules('itemName', '<b>Item Name</b>', 'trim');
            $this->form_validation->set_rules('itemShortName', '<b>Item Short Name</b>', 'trim');
            $this->form_validation->set_rules('timeZone', '<b>Time zone</b>', 'trim');
            $this->form_validation->set_rules('companyName','<b>Company Name</b>','trim');
            $this->form_validation->set_rules('companyAddress','<b>Company Address</b>','trim');
            $this->form_validation->set_rules('companyEmail','<b>company Email</b>','trim');
            if ($this->form_validation->run() == false) {
                return $this->generalApplicationConfiguration();
            } else {
                $itemName=addslashes(strip_tags($this->input->post('itemName', true)));
                $itemShortName=addslashes(strip_tags($this->input->post('itemShortName', true)));
                $companyName=addslashes(strip_tags($this->input->post('companyName', true)));
                $companyAddress=addslashes(strip_tags($this->input->post('companyAddress', true)));
                $companyEmail=addslashes(strip_tags($this->input->post('companyEmail', true)));
                $timeZone=addslashes(strip_tags($this->input->post('timeZone', true)));              
                
                // writing application/config/ambitious_config
                $app_my_config_data = "<?php\n";
                $app_my_config_data.= "\$config['itemName'] = '$itemName';\n";
                $app_my_config_data.= "\$config['itemShortName'] = '$itemShortName';\n";
                $app_my_config_data.= "\$config['itemVersion'] = '".$this->config->item('itemVersion')."';\n\n";
                
                $app_my_config_data.= "\$config['companyName'] = '$companyName';\n";
                $app_my_config_data.= "\$config['companyAddress'] = '$companyAddress';\n";
                $app_my_config_data.= "\$config['companyEmail'] = '$companyEmail';\n\n";
                $app_my_config_data.= "\$config['developedBy'] = '".$this->config->item('developedBy')."';\n";
                $app_my_config_data.= "\$config['developedByHref'] = '".$this->config->item('developedByHref')."';\n";
                $app_my_config_data.= "\$config['developedByTitle'] = '".$this->config->item('developedByTitle')."';\n";
                $app_my_config_data.= "\$config['developedByPrefix'] = '".$this->config->item('developedByPrefix')."';\n";
                $app_my_config_data.= "\$config['supportEmail'] = '".$this->config->item('supportEmail')."';\n\n";
                $app_my_config_data.= "\$config['language'] = '".$this->config->item('language')."';\n";
                $app_my_config_data.= "\$config['timeZone'] = '$timeZone';\n"; 
                $app_my_config_data.= "\n\$config['defaultPageUrl'] = '".$this->config->item('defaultPageUrl')."';\n\n";
                $app_my_config_data.= "\$config['instantShutterVerifyToken'] = '".$this->config->item('instantShutterVerifyToken')."';\n";
                $app_my_config_data.= "\$config['sess_use_database'] = FALSE;\n";
                $app_my_config_data.= "\$config['sess_table_name'] = '".$this->config->item('sess_table_name')."';\n";
                file_put_contents(APPPATH.'config/ambitious_config.php', $app_my_config_data, LOCK_EX);
                $this->session->set_flashdata('successMessage', 1);
                redirect('admin/generalApplicationConfiguration', 'location');
            }
        }
    }
    public function uploadLogoOnly()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();
        $ret=array();
        $output_dir = FCPATH."assets/img";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="logo-text.".$ext;
            $allow=".png";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
            }
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);
            $ret[]= $filename;
            echo json_encode($filename);
        }
    }
    public function deleteLogoUploadedFile()
    {
        $output_dir = FCPATH."assets/img/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName);
            $filePath = $output_dir. $fileName;
            echo $filePath;
            if (file_exists($filePath)) 
            {
               unlink($filePath);
            }
        }
    }
    public function uploadFaviconOnly()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();
        $ret=array();
        $output_dir = FCPATH."assets/img";
        if (isset($_FILES["myfile"])) {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="favicon.".$ext;
            $allow=".png";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
            }
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);
            $ret[]= $filename;
            echo json_encode($filename);
        }
    }
    
    public function deleteFaviconUploadedFile()
    {
        $output_dir = FCPATH."assets/img/";
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
            $fileName =$_POST['name'];
            $fileName=str_replace("..",".",$fileName);
            $filePath = $output_dir. $fileName;
            echo $filePath;
            if (file_exists($filePath)) 
            {
               unlink($filePath);
            }
        }
    }
    /**
    ************************************************************************
    ***************************  Method for STMP  **************************
    ************************************************************************
    */
    public function generalSocialLoginConfiguration()
    {
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
           redirect('main/loginPage', 'location');
        }
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('social_login');
        $crud->order_by('status','DESC');
        $crud->set_subject($this->lang->line("Social Login Configuration"));
        $crud->required_fields('status');
        $crud->columns('app_name','api_id', 'api_secret','google_client_id','google_client_secret','status');
        $crud->fields('app_name','api_id', 'api_secret','google_client_id','google_client_secret','status');
        $crud->where('deleted','0');
        $crud->callback_after_insert(array($this, 'makeUpActiveSocialLoginSetting'));
        $crud->callback_after_update(array($this, 'makeUpActiveSocialLoginSetting'));
        $crud->callback_field('status', array($this, 'statusFieldCrud'));
        $crud->callback_column('status', array($this, 'statusDisplayCrud'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->unset_texteditor('google_client_id');
        $crud->display_as('app_name', $this->lang->line('Facebook App Name'));
        $crud->display_as('api_id', $this->lang->line('Facebook App Id'));
        $crud->display_as('api_secret', $this->lang->line('Facebook App Secret'));
        $crud->display_as('google_client_id', $this->lang->line('Google Client Id'));
        $crud->display_as('google_client_secret', $this->lang->line('Google Client Secret'));
        $crud->display_as('status', $this->lang->line('Status'));
        $output = $crud->render();
        $data['output'] = $output;
        $data['crud'] = 1;
        $data['pageTitle'] = "Social Login Configuration";
        $this->_mainView($data);
    }
    /**
    * method to active facebook setting
    * @access public
    * @return boolean
    */
    public function makeUpActiveSocialLoginSetting($post_array, $primary_key)
    {
        if ($post_array['status']=='1') 
        {
            $table="social_login";
            $where=array('id !='=> $primary_key);
            $data=array("status"=>"0");
            $this->common->updateData($table, $where, $data);
            $this->db->last_query();
        }
        return true;
    }
    /**
    ************************************************************************
    ***************************  Method for STMP  **************************
    ************************************************************************
    */
    /**
     * Load generalSmtpConfiguration method
     * @access public
     * @return  void
     */
    public function generalSmtpConfiguration()
    {
        if ($this->session->userdata('loggedIn') == 1 && $this->session->userdata('userType') != 'Admin') {
           redirect('main/loginPage', 'location');
        }
        $this->load->database();
        $this->load->library('grocery_CRUD');
        $crud = new grocery_CRUD();
        $crud->set_theme('flexigrid');
        $crud->set_table('smtp_configuration');
        $crud->order_by('email_address');
        $crud->set_subject($this->lang->line("SMTP settings"));
        $crud->required_fields('email_address', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'status');
        $crud->columns('email_address', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'status');
        $crud->fields('email_address', 'smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'status');
        $crud->set_rules('email_address','email','valid_email');
        $crud->where('deleted','0');
        $crud->callback_after_insert(array($this, 'makeUpActiveSmtpSetting'));
        $crud->callback_after_update(array($this, 'makeUpActiveSmtpSettingEdit'));
        $crud->callback_field('status', array($this, 'statusFieldCrud'));
        $crud->callback_column('status', array($this, 'statusDisplayCrud'));
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_read();
        $crud->display_as('email_address', $this->lang->line('Email'));
        $crud->display_as('smtp_host', $this->lang->line('SMTP Host'));
        $crud->display_as('smtp_port', $this->lang->line('SMTP Port'));
        $crud->display_as('smtp_user', $this->lang->line('SMTP User'));
        $crud->display_as('smtp_password', $this->lang->line('SMTP Password'));
        $crud->display_as('status', $this->lang->line('Status'));
        $output = $crud->render();
        $data['output'] = $output;
        $data['crud'] = 1;
        $data['pageTitle'] = "SMTP Configuration";
        $this->_mainView($data);
    }
    /**
    * method to active smtp smtp setting
    * @access public
    * @return boolean
    */
    public function makeUpActiveSmtpSetting($post_array, $primary_key)
    {
        if ($post_array['status']=='1') {
            $table="smtp_configuration";
            $where=array('id !='=> $primary_key);
            $data=array("status"=>"0");
            $this->common->updateData($table, $where, $data);
            $this->db->last_query();
        }
        return true;
    }
    /**
    * method to active smtp smtp setting edit
    * @access public
    * @return boolean
    */
    public function makeUpActiveSmtpSettingEdit($post_array, $primary_key)
    {
        if ($post_array['status']=='1') {
            $table="email_config";
            $where=array('id !='=> $primary_key);
            $data=array("status"=>"0");
            $this->common->updateData($table, $where, $data);
            $this->db->last_query();
        }
        return true;
    }
    /**
    * method to load status Field Smtp Crud
    * @access public
    * @return from_dropdown dropdown
    * @param $value string
    * @param $row   array
    */
    public function statusFieldCrud($value, $row)
    {
        if ($value == '') {
            $value = 1;
        }
        return form_dropdown('status', array(0 => 'Inactive', 1 => 'Active'), $value, 'class="form-control" id="field-status"');
    }
    /**
    * method to load status Display Smtp Crud
    * @access public
    * @return message string
    * @param $value integer
    * @param $row  array
    */
    public function statusDisplayCrud($value, $row)
    {
        if ($value == 1) {
            return "<span class='label label-success'>Active</sapn>";
        } else {
            return "<span class='label label-warning'>Inactive</sapn>";
        }
    }
}
