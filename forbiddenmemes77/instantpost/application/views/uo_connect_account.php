<style>
	.box-title{
		color: white;
	}
.ubdate_btn {
  flex: 1 1 auto;
  text-align: center;
  transition: 0.5s;
  background-size: 200% auto;
  color: white;
  font-weight: bold;
 }
.ubdate_btn:hover {
  background-position: right center;
}
.ubdate_btn {
  background-image: linear-gradient(to right, #84fab0 0%, #8fd3f4 51%, #84fab0 100%);
}
.btn-info{
	border: 1px solid #41b3f9;
}
.btn-danger{
	border: 1px solid #f33155;
}
.btn-success{
	 border: 1px solid #7ace4c;
}
.horizontal_break{
	padding: 3px;
	margin: 0px;
}
</style>
<?php
	if($this->session->userdata('successMessage') == 'success')
	{
		echo "<h4 style='margin:0'><div class='alert alert-success text-center'><i class='fa fa-check-circle'></i> Your account has been imported successfully.</div></h4>";
		$this->session->unset_userdata('successMessage');
	}
?>
<div class="clearfix">
	<?php  if($showConnectAccountBox==0) : ?>
		<br/>
		<div style="padding: 15px;">			
			<div class='alert alert-danger text-center'><i class='fa fa-times-circle'></i> Please check your package and delete the account that has warning to delete.</div>
		</div>
	<?php endif; ?>
	<div class="row" style="padding:0 15px;">
		<div class="col-xs-12">				
			<h4>
				<div class="text-center">
					<br><p data-placement="bottom"><button class="btn btn-instagram waves-effect waves-light add_instagram_account" type="button"> <i class="fa fa-instagram"></i>   Login With Instagram</button></p>
				</div>
			</h4>
		</div>
	</div>
	<?php if($existingFbAccounts == '0') : ?>
		<br>
		<div class="col-md-12">
			<div class="white-box">
				<h3 class="text-center" style="margin:0">
					<?php echo $this->lang->line("Please login with your instagram account");?>
				</h3>
			</div>
		</div>
	<?php endif; ?>	
	<?php if($existingFbAccounts != '0') : ?>
		<div>
			<?php  if($showConnectAccountBox == 1) { ?>
				<br>
				<div class="col-md-12">
					<div class="white-box">
						<h3 class="text-center" style="margin:0">
							<?php echo $this->lang->line("Your Existing Accounts");?>
						</h3>
					</div>
				</div>
			<?php } ?>
			<?php $j=0; foreach($existingFbAccounts as $value) : ?>
				<div class="col-md-6">
          			<div class="box box-widget widget-user">            
            			<div class="widget-user-header bg-aqua-active" >
            			  	<h3 class="widget-user-username pull-left" style="color: white"><a href="<?php echo "https://www.instagram.com/".$value['igusername'];?>" target="_blank" style="color: white"><i class="fa fa-instagram"></i> <?php echo $value['ig_full_name']; ?></a></h3>
            			  	<div class="box-tools pull-right">
		    			        <button type="button" class="btn btn-box-tool" data-widget="collapse" style="color: white"><i class="fa fa-minus"></i></button>
		    			        <button type="button" class="delete_account btn btn-box-tool" table_id="<?php echo $value['userinfo_table_id']; ?>" style="color: white"><i class="fa fa-trash"></i></button>
		    			    </div>
            			</div>
            			<div class="widget-user-image">
            				<?php
            				if(isset($value['profile_picture']) && !empty($value['profile_picture'])){
            					$logo = $value['profile_picture'];
            				}else{
            					$logo = base_url("assets/img/avatar.png");
            				}
            				?>
              				<img class="img-circle" src="<?php echo $logo;?>" alt="<?php echo $value['igusername']; ?>">
            			</div>
            			<div class="box-body">
            				<br/>
							<br/>
							<br/>
            				<div class="row">
			            	    <div class="col-md-4 border-right">
			            	      	<div class="description-block">
			            	        	<h5 class="description-header"><?php echo $value['media_count']?></h5>
			            	        	<span class="description-text">MEDIA</span>
			            	      	</div>
			            	    </div>
			            	    <div class="col-md-4 border-right">
			            	      	<div class="description-block">
			            	        	<h5 class="description-header"><?php echo $value['follower_count']?></h5>
			            	        	<span class="description-text">FOLLOWERS</span>
			            	      	</div>
			            	    </div>
			            	    <div class="col-md-4">
			            	      	<div class="description-block">
			            	        	<h5 class="description-header"><?php echo $value['following_count']?></h5>
			            	        	<span class="description-text">FOLLOWS</span>
			            	      	</div>
			            	    </div>
			            	</div>
			            	<div class="col-xs-12" style="overflow-y:auto;">
								<br><br>
								<div class="col-xs-12 col-sm-12 col-md-12" style="text-align: center; color: gray;">
										<?php echo "Added on ".date("jS F, Y",strtotime($value['add_date']));?>
								</div>
								<br><br>
              				</div>
			            	<div class="row">
			            		<div class="col-md-6">
			            			<a href="<?php echo base_url('uoconnectaccount/postInsight/'.$value['userinfo_table_id']); ?>" class="btn btn-block btn-info btn-outline" target="_blank"><i class="fa fa-bar-chart"></i>  Post Insights</a>
			            		</div>
			            		<div class="col-md-6">
			            			<a id ="<?php echo $value['userinfo_table_id'];?>" class="btn btn-block btn-warning btn-outline is_account_update" type="button"><i class="fa fa-retweet"></i>  Update Account</a>
			            		</div>
			            	</div>
			            	<br>             				
            			</div>
          			</div>		          	
		        </div>
			<?php
				$j++;
				if($j%2 == 0)
					echo "</div><div class='row' style='padding:0 15px;'>";
				endforeach;				
			?>
		</div>
	<?php endif; ?>
</div>
<?php 
	
	$itemName = $this->config->item('itemName');
?>
<script>
	var baseUrl = "<?php echo base_url();?>";
	$( document ).ready(function() {	
		$(document.body).on('click','.add_instagram_account',function(){	      
	        $("#add_instagram_account_modal").addClass("modal");
	        $("#add_instagram_account_modal").modal();
	    });
	    $(document.body).on('click', '#full_modal_close', function () {
	        $("#add_instagram_account_modal").removeClass("modal");
	        location.reload();
	    });
	});
	$(document.body).on('click', '#save_add_account', function () {
		var igusername = $("#igusername").val().trim();
		var igpassword = $("#igpassword").val().trim();
		var proxy = $("#proxy").val().trim();
		if (igusername == '') {
          	alertify.alert('Add Account', 'Please type your username');
        	return false;
        }
        if (igpassword == '') {
          	alertify.alert('Add Account', 'Please type your Password');
        	return false;
        }
        var loading = '<img src="' + baseUrl + 'assets/pre-loader/snakes_chasing.gif" class="center-block">';
      	$("#response_status").html(loading);
      	var queryString = new FormData($("#add_ig_account_form")[0]);
      	// alert(igusername);
      	// alert(igpassword);
      	// alert(proxy);
      	$.ajax({
	        type: 'POST',
	        url: baseUrl + "uoconnectaccount/addInstagramAccount",
	        data: queryString,
	        dataType: 'JSON',
	        cache: false,
	        contentType: false,
	        processData: false,
	        success: function (response) {
	          if (response.status == "1") {
	            $("#response_status").html(response.message);
	          }
	          else {
	            $("#response_status").html(response.message);
	          }
	        }
	    });
	});
	$( document.body ).on('click','.is_account_update',function(){
		var id=$(this).attr('id');
		var alert_id = "alert_"+id;
		$(".is_account_update").addClass('disabled');
		var  loading = '<img src="'+baseUrl+'assets/pre-loader/custom.gif" class="center-block">';    
	    $("#"+alert_id).removeClass("alert-success");
	    $("#"+alert_id).show().html(loading);
		$.ajax({
			type:'POST',
			url:"<?php echo site_url();?>uoconnectaccount/accountUpdate",
			data:{id:id},
	    	dataType:'JSON',
	    	success:function(response){
	    	   	$("#"+alert_id).addClass("alert-success");
	    	   	$("#"+alert_id).show().html(response.message);
	    	   	alertify.alert("Update", response.message, function(){ 
	    	   		location.reload(); 
	    	   	});	    	     
	    	}
		});
	});
	$(".delete_account").click(function(){
		var user_table_id = $(this).attr('table_id');
		alertify.confirm('Account Setting', 'Do you want to delete this account from database?', 
			function(clickYes){ 
				alertify.success('Ok');
				if(clickYes) {
					$.ajax
					({
						type:'POST',
						url:baseUrl+'uoconnectaccount/deleteAccount',
			   			data:{user_table_id:user_table_id},
						dataType:'JSON',
						success:function(response) {
		   					if(response.success) {
		   						location.reload();
		   					}
		   				}
					});
				}
			},
			function(){
			    alertify.error('Cancel');
			}
		);
	});
</script>
<div class="modal fade" id="delete_confirmation" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center">Delete Confirmation</h4>
            </div>
            <div class="modal-body" id="delete_confirmation_body">                
            </div>
        </div>
    </div>
</div>
<!-- Set full account reply -->
<div class="modal fade" id="add_instagram_account_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id='full_modal_close' class="close">&times;</button>
                <br>
                <h4 class="modal-title text-center">Please give the following information for Connect your account</h4>
                <br>
            </div>
            <form action="#" id="add_ig_account_form" method="post" class="form-material form-horizontal">
            	<br>
            	<div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;">User Name</label>
                    <div class="col-md-7">
                        <input type="text" name="igusername" id="igusername" class="form-control ambitious-form-loading" placeholder="type your username">
                        <span class="red"><?php echo form_error('igusername'); ?></span>
                    </div>
                    <div class="col-md-1"></div>                     
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;">Password</label>
                    <div class="col-md-7">
                        <input type="text" name="igpassword" id="igpassword" class="form-control ambitious-form-loading" placeholder="type your password">
                        <span class="red"><?php echo form_error('igpassword'); ?></span>
                    </div>
                    <div class="col-md-1"></div>                     
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;">Proxy (Optional)</label>
                    <div class="col-md-7">
                        <input type="text" name="proxy" id="proxy" class="form-control ambitious-form-loading" placeholder="type your Proxy">
                        <span class="red"><?php echo form_error('proxy'); ?></span>
                    </div>
                    <div class="col-md-1"></div>                     
                </div>
                <div class="form-group">
                    <label class="col-md-11" style="text-align:center;padding-top:10px;">
                    	<ul class="field-tips" style="list-style: none;">
                            <li>It's recommended to to use a proxy belongs to the country where you've logged in this acount in Instagram's official app</li>
                        </ul>
                    </label>                    
                </div>
                <div class="form-group">
                	<div class="col-xs-12 text-center" id="response_status"></div>
                </div>               
            </form>
            <div class="modal-footer text-center">
                <button class="btn btn-lg btn-info btn-outline" id="save_add_account">Add Account</button>
            </div>
        </div>
    </div>
</div>