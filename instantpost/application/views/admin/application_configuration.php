<script src="<?php echo base_url();?>plugins/upload/jquery.uploadfile.min.js"></script>
<?php $this->load->view('layouts/message_layout');?>
<br>
<div class="white-box">
	<h2 class="box-title"><?php echo $this->lang->line('Application Configuration');?></h2>
	<br>
	<div class="row">
        <div class="col-md-12">
            <form class="form-material form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'admin/generalApplicationConfigurationAction';?>" method="POST">
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Item Name');?></label>
                    <div class="col-md-8">
                        <input type="text" name="itemName" class="form-control ambitious-form-loading" value="<?php echo $this->config->item('itemName');?>">
                        <span class="red"><?php echo form_error('itemName'); ?></span>
                    </div>                        
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Item Short Name');?></label>
                    <div class="col-md-8">
                        <input type="text" name="itemShortName" class="form-control ambitious-form-loading" value="<?php echo $this->config->item('itemShortName');?>">
                        <span class="red"><?php echo form_error('itemShortName'); ?></span>
                    </div>
                </div>
                                        
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Time Zone');?></label>
                    <div class="col-sm-8">
                        <?php	$timeZone['']='Time Zone';
							echo form_dropdown('timeZone',$timeZone,$this->config->item('timeZone'),'class="form-control ambitious-form-loading" id="time_zone"');  ?>		          
	             			<span class="red"><?php echo form_error('time_zone'); ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Company Name');?></label>
                    <div class="col-md-8">
                        <input type="text" name="companyName" class="form-control ambitious-form-loading" value="<?php echo $this->config->item('companyName');?>">
                        <span class="red"><?php echo form_error('companyName'); ?></span>
                    </div>                        
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Company Address');?></label>
                    <div class="col-md-8">
                        <input type="text" name="companyAddress" class="form-control ambitious-form-loading" value="<?php echo $this->config->item('companyAddress');?>">
                        <span class="red"><?php echo form_error('companyAddress'); ?></span>
                    </div>                        
                </div>
                <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Company Email');?></label>
                    <div class="col-md-8">
                        <input type="text" name="companyEmail" class="form-control ambitious-form-loading" value="<?php echo $this->config->item('companyEmail');?>">
                        <span class="red"><?php echo form_error('companyEmail'); ?></span>
                    </div>                        
                </div>
		        <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Logo');?></label>
                    <div class="col-md-8">
                    	<div class='text-center'><img class="img-responsive" src="<?php echo base_url();?>assets/img/logo-text.png" alt="Logo"/></div>
                    	<?php echo "Max Dimension: 200 x 100, Max Size: 100KB, Allowed Format: png"; ?>
                        <div id="logoImageUrl"> Upload</div>
                    </div>                        
                </div>
		        <div class="form-group">
                    <label class="col-md-4" style="text-align:center;padding-top:10px;"><?php echo $this->lang->line('Favicon');?></label>
                    <div class="col-md-8">
                    	<div class='text-center'><img class="img-responsive" src="<?php echo base_url();?>assets/img/favicon.png" alt="Favicon"/></div>
                    	<?php echo "Preferable Dimension: 32 x 32, Preferable Size: 50KB, Allowed Format: png"?>
                        <div id="faviconImageUrl"> Upload</div>
                    </div>                        
                </div>
		        <div class="box-footer">
		            <div class="form-group">
		             	<div class="col-sm-12 text-center">
		               		<input name="submit" type="submit" class="btn btn-info btn-lg" value="<?php echo $this->lang->line('Save');?>"
		               		style="padding-left: 40px; padding-right: 40px;"/> 
		             	</div>
		           	</div>
		        </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	var baseUrl="<?php echo base_url();?>";
	$('[data-toggle="popover"]').popover(); 
	$('[data-toggle="popover"]').on('click', function(e) {e.preventDefault(); return true;});
    $("#logoImageUrl").uploadFile({
		url:baseUrl+"admin/uploadLogoOnly",
		fileName:"myfile",
		returnType: "json",
		dragDrop: true,
		showDelete: true,
		multiple:false,
	    maxFileCount:1,
		acceptFiles:".png",
		deleteCallback: function (data, pd) {
	        var deleteUrl="<?php echo site_url('admin/deleteLogoUploadedFile');?>";
            $.post(deleteUrl, {op: "delete",name: data},
            function (resp,textStatus, jqXHR) { 
                $("#image_url_link").val("");
                $("#json_page_thumb").attr('src','');                   	                 
            });	           
	    }
	});
	$("#faviconImageUrl").uploadFile({
		url:baseUrl+"admin/uploadFaviconOnly",
		fileName:"myfile",
		returnType: "json",
		dragDrop: true,
		showDelete: true,
		multiple:false,
	    maxFileCount:1,
		acceptFiles:".png",
		deleteCallback: function (data, pd) {
	        var deleteUrl="<?php echo site_url('admin/deleteFaviconUploadedFile');?>";
            $.post(deleteUrl, {op: "delete",name: data},
            function (resp,textStatus, jqXHR) { 
                $("#image_url_link").val("");
                $("#json_page_thumb").attr('src','');                   	                 
            });	           
	    }
	});
});
</script>