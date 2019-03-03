<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!-- jQuery -->
<script src="<?php echo base_url();?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jEasy Grid -->
<!-- ================ -->
<script src="<?php echo base_url();?>plugins/easyui/jquery.easyui.min.js"></script>
<!-- Load Language -->
<?php $crud_language_name=$this->language;?>
<script src="<?php echo base_url();?>plugins/easyui/locale/<?php echo $crud_language_name;?>.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/dist/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_url();?>plugins/dist/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?php echo base_url();?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!-- chatjs -->
<script src="<?php echo base_url();?>plugins/bower_components/chart.js/bundle.js"></script>
<script src="<?php echo base_url();?>plugins/bower_components/chart.js/utils.js"></script>
<!--slimscroll JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?php echo base_url();?>plugins/dist/js/waves.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?php echo base_url();?>plugins/dist/js/custom.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>plugins/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url();?>plugins/dist/js/emojionearea.min.js"></script>
<!-- upload js -->
<!-- <script src="<?php echo base_url();?>plugins/upload/jquery.uploadfile.min.js"></script> -->
<!-- datepicker -->
<script src="<?php echo base_url();?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.1/build/alertify.min.js"></script>
<?php 
	
	$itemName = $this->config->item('itemName');
?>
<script>
$(document).ready(function() {
	var itemName = "<?php echo $itemName;?>";
	function goBack(link,insert_or_update)
	{
		if (typeof(insert_or_update)==='undefined') insert_or_update = 0;
		var mes='';
		if(insert_or_update==0) {
			mes="the data you had insert may not be saved";
		} else {
	    	mes="the data you had change may not be saved.";
	    }
	  	var ans=confirm(mes); 
	  	link="<?php echo site_url();?>"+link;
	  	if(ans) window.location.assign(link);
	}
  
    $("#facebookAccountSwitchingInfo").change(function(){
    	var id=$(this).val();
    	alertify.confirm(itemName, 'Are You sure :) You want to Switch your account', 
			function(clickYes){
				alertify.success('Ok');
				if(clickYes) {
					$.ajax
					({
						url: '<?php echo site_url("isconnectaccount/facebookAccountSwitch");?>',
				    	type: 'POST',
				    	data: {id:id},
				    	success:function(response){
				    	    location.reload();
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
    $("#language_change").change(function(){
      var language=$(this).val();
      $("#language_label").html("Loading Language...");
      $.ajax({
        url: '<?php echo site_url("main/languageChanger");?>',
        type: 'POST',
        data: {language:language},
        success:function(response){
            $("#language_label").html("Language");
            location.reload(); 
        }
      })      
    });
});
</script>