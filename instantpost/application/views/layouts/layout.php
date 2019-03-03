<!DOCTYPE html>
<html lang="en">
<?php 
// print_r($this->uri->segment(2));
// die();
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url();?>assets/img/favicon.png">    
    <title><?php echo $pageTitle." | ".$this->config->item('itemName');?></title>
    <?php $this->load->view('thirdparty/include_css_file_back');?>
    <?php $this->load->view('thirdparty/include_js_file_back');?>
</head>
<body class="fix-header <?php if($this->uri->segment(1)=='') echo 'show-sidebar hide-sidebar';?>">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="wrapper">
        <?php $this->load->view('layouts/header_layout');?>
        <?php $this->load->view('layouts/sidebar_layout');?>
        <div id="page-wrapper">
            <div class="container-fluid">
                <?php
                if($this->uri->segment(1)=="isconfiguration" && ($this->uri->segment(3)=="add" || $this->uri->segment(3)=="edit"))
                { ?> 
                <br>
                <div class="white-box">
                    <h4>Facebook URL</h4><hr>
                    <?php echo "App Domain : <span style='color:#41B3F9'>".getDomainOnly(base_url()); ?> </span><br/>
                    <?php echo "Site URL : <span style='color:#41B3F9'>".base_url(); ?> </span><br/><br/>
                    <?php echo "Privacy Policy URL : <span style='color:#41B3F9'>".base_url("main/privacyPolicyUrl"); ?> </span><br/>
                    <?php echo "Terms of Service URL : <span style='color:#41B3F9'>".base_url("main/termsServiceUrl"); ?> </span><br/><br/>
                    <?php echo "Valid OAuth redirect URI : "; ?><br/>
                    <?php echo "<span style='color:#41B3F9'>".base_url("isconfiguration/loginCallback"); ?></span><br/>
                    <?php echo "<span style='color:#41B3F9'>".base_url("isconnectaccount/memberLoginCallback"); ?></span><br/>
                    <?php echo "<span style='color:#41B3F9'>".base_url("isconnectaccount/refreshTokenCallback"); ?></span><br/><br/>
                </div>
                <?php } ?>
                <?php
                if($this->uri->segment(2)=="generalSocialLoginConfiguration" && ($this->uri->segment(3)=="add" || $this->uri->segment(3)=="edit"))
                { ?> 
                <br>
                <div class="white-box">
                    <h4>Google+ URL</h4><hr>
                    <?php echo "Google+ auth redirect URL : <span style='color:#41B3F9'>". base_url("main/googleLogin"); ?></span>
                </div>
                <div class="white-box">
                    <h4>Facebook URL</h4><hr>
                    <?php echo "App Domain : <span style='color:#41B3F9'>".getDomainOnly(base_url()); ?> </span><br/>
                    <?php echo "Site URL : <span style='color:#41B3F9'>".base_url(); ?> </span><br/>
                    <?php echo "Valid OAuth redirect URI : <span style='color:#41B3F9'>".base_url("main/facebookLogin"); ?></span>
                </div>
                <?php } ?>
                <?php if($crud==1) {
                    $this->load->view('layouts/crud_layout',$output); 
                } else {
                    $this->load->view($body);
                }
                ?>
            </div>            
        </div>
    </div>
    <!-- /#wrapper -->
    <?php $this->load->view('layouts/footer_layout');?>
</body>
</html>
