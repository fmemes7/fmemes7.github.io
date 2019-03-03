<br>
<section class="white-box">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
         		<h3 class="box-title"><i class="fa fa-plus-circle"></i> Add Package Settings</h3>
       		</div>
       		<br>
       		<br>
       		<form class="form-material form-horizontal" action="<?php echo site_url().'admin/addPackageAction';?>" method="POST">
       			<div class="box-body">
       				<div class="form-group">
			            <label class="col-sm-3 control-label" for="name"> Package Name *</label>
			            <div class="col-sm-9 col-md-6 col-lg-6">
			               	<input placeholder="type your package name" name="name" value="<?php echo set_value('name');?>"  class="form-control ambitious-form-loading" type="text">
			               	<span class="red"><?php echo form_error('name'); ?></span>
			            </div>
			        </div>
			        <div class="form-group">
		             	<label class="col-sm-3 control-label" for="price">Price - <?php echo $payment_configuration[0]['currency']; ?> *</label>
		             	<div class="col-sm-9 col-md-6 col-lg-6">
		               		<input placeholder="type your price" name="price" value="<?php echo set_value('price');?>"  class="form-control ambitious-form-loading" type="text">
		               		<span class="red"><?php echo form_error('price'); ?></span>
		             	</div>
		           	</div>
		           	<div class="form-group">
			            <label class="col-sm-3 control-label" for="price">Validity - Days *</label>
			            <div class="col-sm-9 col-md-6 col-lg-6">
			               <input placeholder="type your validity days" name="validity" value="<?php echo set_value('validity');?>"  class="form-control ambitious-form-loading" type="text">
			               <span class="red"><?php echo form_error('validity'); ?></span>
			            </div>
			        </div>
			        <div class="form-group">
             			<label class="col-sm-3 control-label" for="">Modules * </label>
             			<div class="col-sm-9 table-responsive">
             				<table class="table table-bordered table-condensed table-hover table-striped" style="width:auto;">
             				<tr>
             					<td colspan="5"><input  id="all_modules" type="checkbox"/> <font color="">&nbsp;&nbsp;&nbsp;&nbsp;<b>All Modules</b></font> [0 means unlimited]</td>
             				</tr>
	             			<?php                  
	                  			$current_modules=array();
	                  			if(count($this->input->post('modules'))>0)  
	                  			$current_modules=$this->input->post('modules');  
	              	  			echo "<tr>";    
                        			echo "<th class='text-center success'>"; 
                          				echo "Modules";         
                        			echo "</th>";
                        			echo "<th class='text-center success' colspan='2'>"; 
                          				echo "Limit";         
                        			echo "</th>";
                     			echo "</tr>";
                    			
                    			$no_limit_modules=array();
                    			$not_monthly_modules=array();
                    			foreach($modules as $module) {
                    			  	if($module['limit_enabled']=='0')
                    			  	$no_limit_modules[]=$module['id'];
                      				if($module['extra_text']=='')
                      				$not_monthly_modules[]=$module['id'];
                    			}
	                            foreach($modules as $module) { 
	                 	        	echo "<tr>";    
		                    		echo "<td>";
			                    	if(is_array($current_modules) && in_array($module['id'], $current_modules)) { ?>                  
			                        	<input  name="modules[]" class="modules" checked="checked" type="checkbox" value="<?php echo $module['id']; ?>"/>
			                    	<?php 
			                    	} else { ?>
			                        	<input  name="modules[]" class="modules"  type="checkbox" value="<?php echo $module['id']; ?>"/>
			                     	<?php 
			                    	}
			                    	echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".$module['module_name']."</b>";                
			                		echo "</td>";			                 
                        			if(in_array($module["id"],$no_limit_modules)) {
                          				$type="hidden";
                          				$limit="";
                        			} else {
                            			$type="number";
                            			if(in_array($module["id"],$not_monthly_modules))
                            				$limit="";
                            			else
                            				$limit='/ '.$module['extra_text'];
                        			}
		                    		echo "<td><input type='".$type."' value='0' min='0' style='width:70px;' name='monthly_".$module['id']."'></td>";
	                  				echo "</tr>";                 
		               			}                
	              			?>         		
             				</table>     
               				<span class="red" ><?php echo "<br/><br/>".form_error('modules[]'); ?></span>  
              			</div> 
           			</div>
           			<div class="form-group">
             			<div class="col-sm-12 text-center">
               				<input name="submit" type="submit" class="btn btn-warning btn-lg" value="Save"/>         
               				<input type="button" class="btn btn-default btn-lg" value="cancel" onclick='goBack("admin/packageSettings",0)'/>
             			</div>
           			</div>
       			</div>
       		<form>
		</div>
	</section>
</section>