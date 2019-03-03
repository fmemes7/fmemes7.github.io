<?php $this->load->view('layouts/message_layout'); ?>
<section class="content-header">
  <h2><?php echo $this->lang->line("Send Email to Users");?></h2>
</section>
<!-- Main content -->
<section class="content">  
  	<div class="row">
    	<div class="col-xs-12">
        	<div class="grid_container" style="width:100%; height:700px;">
            	<table 
            	id="tt"  
            	class="easyui-datagrid" 
            	url="<?php echo base_url()."admin/sendEmailDataLoader"; ?>"
            	pagination="true" 
            	rownumbers="true" 
            	toolbar="#tb" 
            	pageSize="10" 
            	pageList="[5,10,20,50,100]"  
            	fit= "true" 
            	fitColumns= "true" 
            	nowrap= "true" 
            	view= "detailview"
            	idField="id"
            	>            
               	<thead>
                 	<tr>
                    	<th field="id" checkbox="true"></th>                        
                    	<th field="name" sortable="true"><?php echo $this->lang->line("Name");?></th>   
                    	<th field="email"  sortable="true"><?php echo $this->lang->line("Email");?></th>
                    	<th field="phone" sortable="true" ><?php echo $this->lang->line("Mobile");?></th>
                    	<th field="address" sortable="true" ><?php echo $this->lang->line("Address");?></th>
                    	<th field="status" formatter="statusCheck" ><?php echo $this->lang->line("Status");?></th>                  
                 	</tr>
               	</thead>
            	</table>                        
         	</div>  
       		<div id="tb" style="padding:3px 3px 3px 0px">
      			<a class="btn btn-warning"  title="Send Email" onclick="sms_send_email_ui()">
        			<i class="fa fa-envelope"></i> <?php echo $this->lang->line("Send Email");?>
    			</a>
 				<form class="form-inline" style="margin-top:20px">
        	        <div class="form-group">
        	            <input id="first_name" name="first_name" class="form-control" size="20" placeholder="<?php echo $this->lang->line("Name");?>">
        	        </div>
        	        <button class='btn btn-info' style="padding: 8px" onclick="doSearch(event)"><?php echo $this->lang->line("Search");?></button>                      
        	    </form>
        	</div>        
    	</div>
  	</div>   
</section>
<script>   
 function sms_send_email_ui()
    {
      $("#modal_send_sms_email").modal();
    }    
    $("document").ready(function() {
        $( ".datepicker" ).datepicker();
        $("#send_sms_email").click(function(){      
                  
          var subject=$("#sms_subject").val();
          var content=$("#sms_content").val();         
          var rows = $('#tt').datagrid('getSelections');
          var info=JSON.stringify(rows);  
          
          if(rows=="") 
          {
            $("#show_message").addClass("alert alert-warning");
            $("#show_message").html("<b>You did not select any user</b>");
            return;
          }
          
          if(subject=="" || content=="")
          {
            $("#show_message").addClass("alert alert-warning");
            $("#show_message").html("Something is missing");
            return;
          }
          $(this).attr('disabled','yes');
          $("#show_message").addClass("alert alert-info");
          $("#show_message").show().html('<i class="fa fa-spinner fa-spin"></i>Sending email, please wait');
          $.ajax({
          type:'POST' ,
          url: "<?php echo site_url(); ?>admin/sendEmailMember",
          data:{content:content,info:info,subject:subject},
          success:function(response){
            $("#send_sms_email").removeAttr('disabled');                     
            $("#show_message").addClass("alert alert-info");
            $("#show_message").html(response);
          }
        });   
      }); 
    });  
    var base_url="<?php echo site_url(); ?>";
    function statusCheck(value, row, index)
    {
      var status = row.status; 
      var str = '';     
      if(status == "1")
        str = str+"<label class='label label-success'>Active</label>";      
      if(status == '0')
        str = str+"<label class='label label-danger'>Inactive</label>";
      return str;
    }
    
   
    function doSearch(event)
    {
        event.preventDefault(); 
        $('#tt').datagrid('load',{
          fName:       $('#first_name').val(),
          isSearched:      1
        });
    }  
</script>
<!--Modal for Send SMS  Email-->
  
<div id="modal_send_sms_email" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 id="SMS" class="modal-title"> <i class="fa fa-envelope"></i> <b>Send Email</b></h4>
      </div>
      <div id="modalBody" class="modal-body">        
        <div id="show_message" class="text-center"></div>
        <div class="form-group">
          <label for="sms_content">Message Subject *</label><br/>
          <input type="text" id="sms_subject" required class="form-control"/>
        </div>
        <div class="form-group">
          <label for="sms_content">Message *</label><br/>
          <textarea name="sms_content" required style="width:100%;height:200px;" id="sms_content"></textarea>
        </div>
     
      </div>
      <div class="modal-footer clearfix">
           <button id="send_sms_email" class="btn btn-warning pull-left" > <i class="fa fa-envelope"></i>  Send</button>
           <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>