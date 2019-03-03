<?php $this->load->view('layouts/message_layout'); ?>
<!-- Main content -->
<section class="content-header">
	<h1 class = 'text-info'><?php echo $this->lang->line("Auto Post Report"); ?> </h1>
</section>
<section class="content">  
	<div class="row" >
		<div class="col-xs-12">
			<div class="grid_container" style="width:100%; min-height:760px;">
				<table 
				id="tt"  
				class="easyui-datagrid" 
				url="<?php echo base_url().'report/autoPostList'; ?>"
				pagination="true" 
				rownumbers="true" 
				toolbar="#tb" 
				pageSize="15" 
				pageList="[5,10,15,20,50,100]"  
				fit= "true" 
				fitColumns= "true" 
				nowrap= "true" 
				view= "detailview"
				idField="id"
				>
					<thead>
						<tr>							
							<th field="message_formatted" ><?php echo $this->lang->line("Message"); ?></th>
							<th field="post_type" sortable="true"><?php echo $this->lang->line("Post Type"); ?></th>
							<th field="scheduled_at" align="center" sortable="true"><?php echo $this->lang->line("Scheduled at"); ?></th>
							<th field="status" sortable="true"><?php echo $this->lang->line("Status"); ?></th>
							<th field="action_url" align="center" sortable="true"><?php echo $this->lang->line("Action"); ?></th>
						</tr>
					</thead>
				</table>                        
			</div>
			<div id="tb" style="padding-bottom: 3px;">
 
			<form class="form-inline" style="margin-top:20px">
				<div class="form-group">
					<select class="form-control" id="post_type" name="post_type">
						<option value=""><?php echo $this->lang->line("All Posts"); ?></option>
						<option value="image_submit"><?php echo $this->lang->line("Image Post"); ?></option>
						<option value="video_submit"><?php echo $this->lang->line("Video Post"); ?></option>
					</select>
				</div>   
				<div class="form-group">
					<input id="scheduled_from" name="scheduled_from" class="form-control ambitious_form_datetime" size="20" placeholder="<?php echo $this->lang->line("Scheduled from"); ?>">
				</div>
				<div class="form-group">
					<input id="scheduled_to" name="scheduled_to" class="form-control ambitious_form_datetime" size="20" placeholder="<?php echo $this->lang->line("Scheduled to"); ?>">
				</div>                    
				<button class='btn btn-info btn-outline' style="padding: 8px;"  onclick="doSearch(event)"><?php echo $this->lang->line("Search Report"); ?></button> 
							
			</div>  
			</form> 
			</div>        
		</div>
	</div>   
</section>
<?php 
	
	$itemName = $this->config->item('itemName');
?>
<script>
  $("document").ready(function(){
  	var itemName = "<?php echo $itemName;?>";
  	$('.ambitious_form_datetime').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
      	autoclose: 1,
      	todayHighlight: 1,
      	startView: 2,
      	forceParse: 0,
        showMeridian: 1
      });
  	$(document.body).on('click','.delete',function(){ 
		var id = $(this).attr('id');
		alertify.confirm(itemName, 'Do you really want to delete this post from our database?', 
			function(clickYes){
				alertify.success('Ok');
				if(clickYes) {
					$.ajax({
				       type:'POST' ,
				       url: "<?php echo base_url('report/deletePpost')?>",
				       data: {id:id},
				       success:function(response) { 
				       		if(response=='1') {
				       			$('#tt').datagrid('reload');
				       		} else {
				       			alert("Something went wrong.");
				       		}
				        }
					});
				}
			},
			function(){
				alertify.error('Cancel');
				location.reload();
			}
		);
	});
	function doSearch(event)
	{
		event.preventDefault(); 
		$('#tt').datagrid('load',{
			post_type   :     $('#post_type').val(),           
			page_or_group_or_user_name  :     $('#page_or_group_or_user_name').val(),              
			scheduled_from  		:     $('#scheduled_from').val(),    
			scheduled_to    		:     $('#scheduled_to').val(),         
			is_searched		:      1
		});
	}
  });
</script>