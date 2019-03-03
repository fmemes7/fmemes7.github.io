<?php 
	if($this->session->flashdata('successMessage')==1)
	{	
		echo "<br>";	
		echo "<div class='alert alert-primary text-center' role='alert'><h4 style='margin:0;'><i class='fa fa-check-circle'></i>Your data has been successfully stored into the database</h4></div>";
	}
	if($this->session->flashdata('warningMessage')==1)
	echo "<div class='alert alert-warning text-center'><h4 style='margin:0;'><i class='fa fa-warning'></i> Something went wrong, please try again.</h4></div>";
	if($this->session->flashdata('errorMessage')==1)
	echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-remove'></i> Your data has been failed to stored into the database.</h4></div>";
		
	if($this->session->flashdata('deleteSuccessMessage')==1 || $this->session->flashdata('deleteSuccess')==1)
	echo "<div class='alert alert-success text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> Your data has been successfully deleted from the database.</h4></div>";
	
	if($this->session->flashdata('deleteErrorMessage')==1)
	echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i>Your data has been failed to delete from the database.</h4></div>";
	
	if($this->session->flashdata('notExistMessage')==1)
	echo "<div class='alert alert-warning text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> Sorry! your data does not exist.</h4></div>";
	if($this->session->flashdata('availableError')==1)
	echo "<div class='alert alert-danger text-center'><h4 style='margin:0;'><i class='fa fa-check-circle'></i> This book isn't available right now.</h4></div>";
?>