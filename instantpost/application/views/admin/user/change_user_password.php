<?php $this->load->view('layouts/message_layout'); ?>
<br>
<section class="content-header">
	<section class="content">
		<div class="white-box">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-pencil"></i> <?php echo "change password"; ?> - <?php echo " [ ".$user_name." ]"; ?></h3>
			</div><!-- /.box-header -->
			<!-- form start -->
			<form class="form-material form-horizontal" action="<?php echo site_url().'admin/changeUserPasswordAction';?>" method="POST">
				<div class="box-body">			
					<div class="form-group">
						<label class="col-sm-3 control-label" for="name"><?php echo "Password"; ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="password" value="<?php echo set_value('password');?>"  class="form-control ambitious-form-loading" type="password" placeholder="type your password here...">		          
							<span class="red"><?php echo form_error('password'); ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="name"><?php echo "Confirm Password"; ?> *
						</label>
						<div class="col-sm-9 col-md-6 col-lg-6">
							<input name="confirm_password" value="<?php echo set_value('confirm_password');?>"  class="form-control ambitious-form-loading" type="password" placeholder="type your confirm password here...">		          
							<span class="red"><?php echo form_error('confirm_password'); ?></span>
						</div>
					</div>

				</div> <!-- /.box-body --> 
				<div class="box-footer">
					<div class="form-group">
						<div class="col-sm-12 text-center">
							<input name="submit" type="submit" class="btn btn-warning btn-lg" value="Save"/>  
							<input type="button" class="btn btn-default btn-lg" value="Cancel" onclick='goBack("admin/userManagement")'/>  
						</div>
					</div>
				</div><!-- /.box-footer -->         
			</div><!-- /.box-info -->       
		</form>     
	</div>
</section>
</section>

