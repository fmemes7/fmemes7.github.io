<?php $this->load->view('layouts/message_layout'); ?>
<?php 
$name= isset($profileInfo[0]["name"]) ? $profileInfo[0]["name"] : ""; 
$phone= isset($profileInfo[0]["phone"]) ? $profileInfo[0]["phone"] : ""; 
$gender= isset($profileInfo[0]["gender"]) ? $profileInfo[0]["gender"] : ""; 
$email= isset($profileInfo[0]["email"]) ? $profileInfo[0]["email"] : ""; 
$my_note= isset($profileInfo[0]["my_note"]) ? $profileInfo[0]["my_note"] : ""; 
$address= isset($profileInfo[0]["address"]) ? $profileInfo[0]["address"] : ""; 
$logo= isset($profileInfo[0]["user_logo"]) ? $profileInfo[0]["user_logo"] : "";
if($logo=="") $logo=base_url().'assets/img/avatar.png' ? base_url("assets/img/avatar.png") : "";
else $logo=base_url().'member/'.$logo;
?>
<br>
<div class="white-box">
	<h2 class="box-title">Account Setting</h2>
	<br>
	<div class="row">
        <div class="col-md-12">
        	<form class="form-material form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'member/accountSettingAction';?>" method="POST">
        		<div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;">Name *</label>
                    <div class="col-md-8">
                        <input type="text" name="name" class="form-control ambitious-form-loading" value="<?php echo $name;?>">
                        <span class="red"><?php echo form_error('name'); ?></span>
                    </div>                        
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;">Mobile *</label>
                    <div class="col-md-8">
                        <input type="text" name="phone" class="form-control ambitious-form-loading" value="<?php echo $phone;?>">
                        <span class="red"><?php echo form_error('phone'); ?></span>
                    </div>                        
                </div>
                <div class="form-group">
		            <label class="col-sm-4" style="text-align:center;padding-top:10px;" for="">Email *
		            </label>
	             	<div class="col-sm-8 col-md-6 col-lg-6">
	               		<input name="email" value="<?php echo $email;?>"  class="form-control ambitious-form-loading" type="email">	
	               		<span class="red"><?php echo form_error('email'); ?></span>
	             	</div>
		        </div>
		        <div class="form-group">
                    <label class="col-sm-4" style="text-align:center;padding-top:10px;" >Address</label>
                    <div class="col-sm-8">
                        <textarea name="address" class="form-control ambitious-form-loading" rows="3"><?php echo $address;?></textarea>
                        <span class="red"><?php echo form_error('address'); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4" style="text-align:center;padding-top:10px;" >Gender</label>
                    <div class="col-sm-8">
                        <select class="form-control ambitious-form-loading" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
		            <label class="col-sm-4" style="text-align:center;padding-top:10px;" >Profile Picture</label>
	             	<div class="col-sm-8 col-md-6 col-lg-6" >
		           		<div class='text-center'>
		           			<img id="ambitious-upload-image-pp" class="img-responsive" src="<?php echo $this->session->userdata('userLogo');?>" alt="<?php echo "Profile Picture";?>"/>
		           		</div>
	               		<?php echo "Max Dimension : 500 x 500, Max Size : 100KB, Allowed Format : png";?>
	               		<input name="logo" class="form-control" type="file">		          
	             		<span class="red"> <?php echo $this->session->userdata('logoError'); $this->session->unset_userdata('logoError'); ?></span>
	             	</div>
		        </div>
		        <div class="form-group">
                    <label class="col-sm-4" style="text-align:center;padding-top:10px;" >My Note</label>
                    <div class="col-sm-8">
                        <textarea name="my_note" class="form-control ambitious-form-loading" rows="7"><?php echo $my_note;?></textarea>
                        <span class="red"><?php echo form_error('my_note'); ?></span>
                    </div>
                </div>
		        <div class="box-footer">
		           	<div class="form-group">
		             	<div class="col-sm-12 text-center clearfix">
		               		<input name="submit" type="submit" class="btn btn-warning btn-lg" value="Save"/>  
		              		<input type="button" class="btn btn-default btn-lg" value="Cancel" onclick='goBack("dashboard",1)'/>
		             	</div>
		           	</div>
		        </div><!-- /.box-footer -->  
        	</form>
        </div>
    </div>
</div>
