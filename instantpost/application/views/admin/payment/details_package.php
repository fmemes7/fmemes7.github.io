<br>
<section class="white-box">
	<section class="content">
		<div class="box box-info custom_box">
			<div class="box-header">
		        <h3 class="box-title"><i class="fa fa-binoculars"></i> Details - Package Settings</h3>
		    </div>
		    <form class="form-horizontal">
		    	<div class="box-body">
		    		<div class="form-group">
		             	<label class="col-sm-2 control-label" for="name">  </label>
		             	<div class="col-sm-10 col-md-6 col-lg-6 text-center" style="padding-top:7px">
		             	 	<h3>Package name : 
		               			<?php echo $value[0]["package_name"];?> @
		               			<?php echo $paymentConfiguration[0]['currency']; ?> <?php echo $value[0]["price"];?> /
		               			<?php echo $value[0]["validity"];?> Days
		               		</h3>
		             	</div>
		           	</div>
		           	<div class="form-group">
             			<label class="col-sm-2 control-label" for=""></label>
             			<div class="col-sm-10 table-responsive">
             				<table class="table table-bordered table-condensed table-hover table-striped" style="width:auto;">
              					<tr>
               						<td colspan="5" align="center">0 means unlimited</td>
              					</tr>
               					<?php
                    				$current_modules=array();
                    				$current_modules=explode(',',$value[0]["module_ids"]); 
                    				$monthly_limit=json_decode($value[0]["monthly_limit"],true);
                    				$bulk_limit=json_decode($value[0]["bulk_limit"],true);
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
                     					if(in_array($module["id"],$current_modules)) {
                        					echo "<tr>";    
                          						echo "<td>";
                            						echo "<b>".$module['module_name']."</b>";                
                        						echo "</td>";
                        					$xmonthly_val=0;
                        					$xbulk_val=0;
                        					if(in_array($module["id"],$current_modules)) {
                          						$xmonthly_val=$monthly_limit[$module["id"]];
                          						$xbulk_val=$bulk_limit[$module["id"]];
                        					}
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
                        					echo "<td><input type='".$type."' disabled='disabled' value='".$xmonthly_val."' style='width:70px;' name='monthly_".$module['id']."'></td>";
                        					echo "</tr>";      
                      					}           
                   					}                
                				?>            
              				</table>     
               				<span class="red" ><?php echo "<br/><br/>".form_error('modules'); ?></span>  
              			</div> 
           			</div>
		    	</div>
		    </form>
		</div>
	</section>
</section>