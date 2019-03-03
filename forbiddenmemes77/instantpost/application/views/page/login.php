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
      <form class="form-horizontal form-material" id="loginform" action="<?php echo site_url('main/login');?>" method="post">
        <a href="javascript:void(0)" class="text-center db"><img src="<?php echo base_url();?>assets/img/favicon.png" alt="Home" /><br/><img src="<?php echo base_url();?>assets/img/logo-text.png" alt="Home" /></a>  
        <?php
          if($this->session->flashdata('loginMsg')!='') {
            echo "<br>";
            echo "<div class='alert alert-danger text-center'>"; 
              echo $this->session->flashdata('loginMsg');
            echo "</div>"; 
          }
          if($this->session->flashdata('resetSuccess')!='') {
            echo "<br>";
            echo "<div class='alert alert-success text-center'>"; 
              echo $this->session->flashdata('resetSuccess');
            echo "</div>"; 
          }
          if($this->session->flashdata('errorSmtp') == 1) {
            echo "<br>";
            echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> Your SMTP Not setup. Without smtp user not take activation key. Please set smtp from SMTP settings</h4></div>";
          }
        ?>
        <div class="form-group m-t-40">
          <div class="col-xs-12">
            <input type="text" value="<?php if(isset($_COOKIE['emailRemember'])){ echo $_COOKIE['emailRemember']; }?>" class="form-control ambitious-form-loading" name="email" placeholder="Email" required="" >
            <span style="color:red"><?php echo form_error('email'); ?></span>
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input type="password" value="<?php if(isset($_COOKIE['passwordRemember'])){ echo $_COOKIE['passwordRemember']; }?>" class="form-control ambitious-form-loading" name="password" required="" placeholder="Password">
            <span style="color:red"><?php echo form_error('password'); ?></span>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            <div class="checkbox checkbox-info pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox" name="remember" <?php if(isset($_COOKIE['emailRemember'])) { echo "checked";}?>>
              <label for="checkbox-signup"> Remember me </label>
            </div>
            <a href="<?php echo base_url().'main/forgotPwd';?>" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
            <div class="social">
                <?php echo  str_replace("ThisIsTheLoginButtonForGoogle","Google +", $googleLoginButton); ?>
                <?php echo  str_replace("ThisIsTheLoginButtonForFacebook","Facebook", $facebookLoginButton); ?>
            </div>
          </div>
        </div>
        <div class="form-group m-b-0">
          <div class="col-sm-12 text-center">
            <p>Don't have an account? <a href="<?php echo base_url();?>main/signUp" class="text-info"><b>Sign Up</b></a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
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
