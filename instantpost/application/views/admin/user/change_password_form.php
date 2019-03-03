<br>
<div class="white-box">
	<h2 class="box-title">Change Password</h2>
	<br>
	<div class="row">
        <div class="col-md-12">
        	<?php 
				if($this->session->userdata('error'))
					echo '<div class="alert alert-warning text-center">'.$this->session->userdata('error').'</div>';
				$this->session->unset_userdata('error');
			?>
			<form class="form-material form-horizontal" method="POST" action="<?php echo site_url('password/changePasswordAction'); ?>">
				<div class="form-group">
					<label class="col-md-4" for="oldPassword" style="text-align:center;padding-top:10px;">Old password</label>
					<div class="col-md-8">
						<input required type="password" class="form-control ambitious-form-loading" id="oldPassword" name="oldPassword" placeholder="type your Old password here...">
						<span class="red"><?php echo form_error('oldPassword');?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4" for="newPassword" style="text-align:center;padding-top:10px;">New Password</label>
					<div class="col-md-8">
						<input required type="password" class="form-control ambitious-form-loading" id="newPassword" name="newPassword" placeholder="type your New password here...">
						<span class="red"><?php echo form_error('newPassword');?></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-4" for="confirmNewPassword" style="text-align:center;padding-top:10px;">Confirm Password</label>
					<div class="col-md-8">
						<input required type="password" class="form-control ambitious-form-loading" id="confirmNewPassword" name="confirmNewPassword" placeholder="type your Confirm password here...">
						<span class="red"><?php echo form_error('confirmNewPassword');?></span>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-center">
						<button type="submit" class="btn btn-warning">Change Password</button>
					</div>
				</div>
			</form>
        </div>
    </div>
</div>