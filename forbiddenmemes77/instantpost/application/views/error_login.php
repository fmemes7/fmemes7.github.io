<div style="margin:20px;padding:20px">
<?php
if(isset($message))
{
	if($error==1) $class="danger";
	else $class="success";
	echo '<div class="text-center alert alert-'.$class.'">';
		echo '<b>'.$message.'</b>';
	echo '</div>';
}	
echo '<center><a href="'.base_url("isconnectaccount/index").'"><i class="fa fa-arrow-circle-left"></i> Go back</a></center>';
?>
</div>