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
    <?php $this->load->view('thirdparty/include_css_file_back');?>
    <?php $this->load->view('thirdparty/include_js_file_front');?>
</head>
<body class="fix-header">
	<div class="container-fluid">
		<br>
		<div class="row">
			<div class="col-xs-12 background_darkblue" style="height:80px;">
				<a href="<?php echo base_url();?>" class="text-center db"><img src="<?php echo base_url();?>assets/img/favicon.png" alt="Home" /><br/><img src="<?php echo base_url();?>assets/img/logo-text.png" alt="Home" /></a>
	   		</div>
		</div>
	</div>
	<div class="container-fluid">
		<?php $this->load->view($body);?>
	</div>
	
</body>
</html>
