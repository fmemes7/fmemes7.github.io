<!DOCTYPE html>  
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>assets/img/favicon.png">
<title><?php echo $pageTitle." | ".$this->config->item('itemName');?></title>
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url();?>plugins/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- animation CSS -->
<link href="<?php echo base_url();?>plugins/dist/css/style.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?php echo base_url();?>plugins/dist/css/style.css" rel="stylesheet">
<!-- color CSS -->
<link href="<?php echo base_url();?>plugins/dist/css/colors/default.css" id="theme"  rel="stylesheet">
<!-- Ambitious CSS -->
<link href="<?php echo base_url();?>plugins/dist/css/ambitious.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
<div class="login-box login-sidebar">
    <div class="white-box">
    	<form class="form-horizontal form-material" method="post" action="<?php echo site_url('main/signUpAction'); ?>">
    		<a href="javascript:void(0)" class="text-center db"><img src="<?php echo base_url();?>assets/img/favicon.png" alt="Home" /><br/><img src="<?php echo base_url();?>assets/img/logo-text.png" alt="Home" /></a>
    		<?php if($this->session->userdata('regRuccess')==1) {
            	echo "<br>";
            	echo "<div class='alert alert-success text-center'>";
              		echo "Your registration have been performed successfully. Please check your mail";
            	echo "</div>";
          	}
          	?>
    		<div class="form-group">
	          	<div class="col-xs-12">
	            	<input type="text" value="<?php echo set_value('name');?>" class="form-control ambitious-form-loading" name="name" id="name" placeholder="Name *" required="" >
	            	<span style="color:red"><?php echo form_error('name'); ?></span>
	          	</div>
	        </div>
	        <div class="form-group">
	  			<div class="col-xs-12">
	  				<input type="text" value="<?php echo set_value('mobile');?>" class="form-control ambitious-form-loading" name="mobile" id="mobile"  placeholder="Mobile">
	  			</div>
	  			<span style="color:red;"><?php echo form_error('mobile'); ?></span>				
			</div>
			<div class="form-group">
	  			<div class="col-xs-12">
	  				<input type="email" value="<?php echo set_value('email');?>" class="form-control ambitious-form-loading" name="email" id="email"  placeholder="Email *">
	  			</div>
	  			<span style="color:red;"><?php echo form_error('email'); ?></span>
	  		</div>
	  		<div class="form-group">
	  			<div class="col-xs-12">
	  				<input type="password" value="<?php echo set_value('password');?>" class="form-control ambitious-form-loading" name="password" id="password" placeholder="Password *">
	  			</div>
	  			<span style="color:red;"><?php echo form_error('password'); ?></span>
	  		</div>
	  		<div class="form-group">
	  			<div class="col-xs-12">
	  				<input type="password" value="<?php echo set_value('confirm_password');?>" class="form-control ambitious-form-loading"  name="confirm_password" id="confirm_password" placeholder="Confirm password *">	  						
	  			</div>
	  			<span style="color:red;"><?php echo form_error('confirm_password'); ?></span>
	  		</div>
	  		<p id="captImg"><?php echo $captchaImage;?></p>
	  		<p>Can't read the image? click <a href="javascript:void(0);" class="refreshCaptch">here</a> to refresh.</p>
	  		<div class="form-group">
	          	<div class="col-xs-12">
	            	<input type="text" value="<?php echo set_value('captcha');?>" class="form-control ambitious-form-loading" name="captcha" id="captcha" placeholder="Captcha *" required="" >
	            	<span style="color:red"><?php echo form_error('captcha'); ?></span>
	          	</div>
	        </div>
	        <span style="color:red;margin:10px;">
		  		<?php 
		  			if(form_error('captcha')) {
		  				echo form_error('captcha'); 
		  			}
		  			else { 
		  				echo $this->session->userdata("sign_up_captcha_error"); 
		  				$this->session->unset_userdata("sign_up_captcha_error"); 
		  			}
		  		?>
	  		</span>
	        <div class="form-group">
				<button type="submit" class="btn btn-info btn-block text-uppercase waves-effect waves-light" id="sign_up_button"><b>Sign Up</b></button>
	  		</div>
    	</form>
    	<div class="form-group">
          <div class="col-sm-12 text-center">
            <p>Already have an account? <a href="<?php echo base_url();?>" class="text-info"><b>Sign In</b></a></p>
          </div>
        </div>
    </div>
</div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$('.refreshCaptch').on('click', function(){
			$.get('<?php echo base_url().'main/refreshCapt';?>', function(data){
				$('#captImg').html(data);
			});
		});
	});
</script>
<!-- jQuery -->
<script src="<?php echo base_url();?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?php echo base_url();?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?php echo base_url();?>plugins/dist/js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/custom.min.js"></script>
</body>
</html>
