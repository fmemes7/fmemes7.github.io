<div style="margin:20px;padding:20px">
<?php
if(isset($message)) {
	if($error==1) $class="danger";
	else $class="success";
	echo '<div class="text-center alert alert-'.$class.'">';
		echo '<b>'.$message.'</b>';
	echo '</div>';
}
if(isset($expiredOrNot) && $expiredOrNot==1) {
	echo '<div class="alert alert-success">';
		echo '<h5 class="text-center"> <i class="fa fa-info"></i> User access token is valid. you can login and get new user access token if you want.<h5>';
	echo '</div>';
}
if(isset($loginButton)) {
	echo '<div class="well">';
		echo '<h3 class="text-center">'.$loginButton.'<h3>';
	echo '</div>';
}
echo '<center><a href="'.base_url("isconfiguration").'"><i class="fa fa-arrow-circle-left"></i>Go back</a></center>';
?>
</div>