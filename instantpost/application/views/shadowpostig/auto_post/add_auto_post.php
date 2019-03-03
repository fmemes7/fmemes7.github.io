<script src="<?php echo base_url();?>plugins/upload/jquery.uploadfile.min.js"></script>
<br>
<div class="row">
	<div class="col-md-12" style="margin: 0px;padding: 0px;">
		<div class="col-xs-12 col-md-8 padding-5">
      <div class="panel panel-default block4 block4-header">
        <div class="panel-heading" style="border-bottom:none;"><i class="fa fa-paper-plane"></i> <?php echo $this->lang->line("Auto Post");?></div>
        <div class="row">
          <div class="tab-type">
            <a id="image_post" class="col-md-6 post_type tab-active" data-toggle="tab" type="radio" style="color: rgb(78, 82, 89);">
              <i class="fa fa-file-image-o"></i> 
              <span style="font-family: 'M PLUS Rounded 1c', sans-serif;"><?php echo $this->lang->line("Image");?></span>
            </a>
            <a id="video_post" class="col-md-6 post_type" data-toggle="tab" type="radio" style="color: rgb(78, 82, 89);">
              <i class="fa fa-video-camera"></i>
              <span style="font-family: 'M PLUS Rounded 1c', sans-serif;"><?php echo $this->lang->line("Video");?></span>
            </a>
          </div>
        </div>
    		<div class="panel-body" style="border: none;">
    			<form class="form-material form-horizontal" action="#" enctype="multipart/form-data" id="auto_post_form" method="post">
    				<div class="form-group">
              <label class="col-md-12"><?php echo $this->lang->line("Caption");?></label>
              <div class="col-md-12">
                <textarea class="form-control ambitious-form-loading" name="message" id="message" rows="5" placeholder="<?php echo $this->lang->line("Write a caption...");?>"></textarea>
              </div>
            </div>
						<div id="image_block">
							<div class="form-group">
								<label class="col-md-12"><?php echo $this->lang->line("Image URL");?></label>
								<div class="col-md-12">
									<input class="form-control ambitious-form-loading" name="image_url" id="image_url" type="text"> 
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                  <?php echo "Allowed Format: png or jpg or jpeg"; ?>
	                <div id="image_url_upload"><?php echo $this->lang->line('Upload'); ?></div>
	              </div>
							</div>
						</div>
						<div id="video_block">
							<div class="form-group">
								<label class="col-md-12"><?php echo $this->lang->line("Video URL");?></label>
								<div class="col-md-12">
									<input class="form-control ambitious-form-loading" name="video_url" id="video_url" type="text"> 
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">
                  <?php echo "Allowed Format: 3gp or avi or flv or mkv or mp4 or mpeg or mpeg4 or wmv"; ?>
	                <div id="video_url_upload"><?php echo $this->lang->line('Upload'); ?></div>
	              </div>                                
	              <br/>
	            </div>	
						</div>
            <div class="form-group">
              <label class="col-md-12"><?php echo $this->lang->line("Select Account");?></label>
              <div class="col-md-12">
                <select class="selectpicker" multiple class="form-control" id="post_to_account" name="post_to_account[]"> 
                  <?php
                  foreach($userInfo as $key=>$val)
                  { 
                    $id=$val['id'];
                    $page_name=$val['igusername'];
                    echo "<option value='{$id}'>{$page_name}</option>";               
                  }
                  ?>            
                </select>
              </div>
            </div>  
						<div class="form-group">
							<label class="col-md-12"><?php echo $this->lang->line("Schedule");?></label>
    					<div class="maxl">
  							<label class="radio inline"> 
      						<input name="schedule_type" id="schedule_now" type="radio" value="now" checked>
      						<span><?php echo $this->lang->line("Now");?></span> 
   							</label>
  							<label class="radio inline"> 
  								<input name="schedule_type" id="schedule_later" type="radio" value="later">
  								<span><?php echo $this->lang->line("Later");?></span> 
  							</label>
							</div>
						</div>
						<div class="col-md-12 form-group schedule_block_item">
							<label><?php echo $this->lang->line("Schedule time");?></label>
							<input placeholder="Time" name="schedule_time" id='schedule_time' type='text' class="form-control ambitious-form-loading ambitious_form_datetime" size="16" readonly>
						</div>
						<div class="col-md-12 form-group schedule_block_item">
							<label><?php echo $this->lang->line("Time zone");?></label>
							<?php
								$time_zone[''] = 'Please Select';
								echo form_dropdown('time_zone',$time_zone,set_value('time_zone'),' class="form-control ambitious-form-loading" id="time_zone" required'); 
							?>
						</div>
            <br>
						<div class="row">
					    <div class="col-md-12">
                <input type="hidden" name="submit_post_hidden" id="submit_post_hidden" value="image_submit">
					      <button class="btn btn-block btn-info btn-lg" submit_type="image_submit" id="submit_post" name="submit_post" type="button"><i class="fa fa-send"></i> <?php echo $this->lang->line("Submit Post");?> </button>
					    </div>
					  </div>
    			</form>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-4 padding-5">
      <div class="panel panel-default panel-preview block4-header">
        <div class="panel-heading" style="border-bottom:none;"><i class="fa fa-instagram"></i> <?php echo $this->lang->line("Preview");?></div>
        <?php 
    			$fbUserInfoId=isset($userInfo[0]["id"]) ? $userInfo[0]["id"] : 0; 
    			$fbUserInfoName=isset($userInfo[0]["igusername"]) ? $userInfo[0]["igusername"] : "";
          if(isset($userInfo[0]['profile_picture']) && !empty($userInfo[0]['profile_picture'])) {
            $profilePicture = $userInfo[0]['profile_picture'];
          } else {
              $profilePicture=base_url("assets/img/avatar.png");
          }
    		?>
        <div class="panel-body preview block42" style="border: none;">
          <div class="row">
            <div class="col-md-2">
              <img src="<?php echo $profilePicture;?>" class="img-circle preview_cover_img inline pull-left text-center" alt="X" width="50px" height="50px">
            </div>
            <div class="col-md-4" style="padding-left: 0px;">
              <span class="preview_page"><?php echo $fbUserInfoName;?></span><br>
              <span class="preview_page_sm"><?php echo $this->config->item("itemShortName");?></span>
            </div>
            <div class="col-md-6">
              <a class="btn btn-outline btn-info pull-right" href="<?php echo "https://www.instagram.com/".$fbUserInfoName;?>" target="_blank" style="text-transform: uppercase;">Follow</a>
            </div>
          </div>
          <br/>
          <div class="row">
            <div class="col-md-12">
              <img src="<?php echo base_url('assets/img/demo_img.png');?>" class="demo_preview responsive" alt="No Image Preview">
            </div>
          </div>  
				  <div class="preview_img_block">
					  <img src="<?php echo base_url('assets/img/demo_img.png');?>" class="preview_img" alt="No Image Preview" width="404px !important;" height="410px !important;">		
					  <div class="preview_og_info">
						  <div class="preview_og_info_title inline-block"></div>
						  <div class="preview_og_info_desc inline-block">							
						  </div>
						  <div class="preview_og_info_link inline-block">							
						  </div>
					  </div>
				  </div>
				  <div class="preview_only_img_block">
					  <img src="<?php echo base_url('assets/img/demo_img.png');?>" class="only_preview_img" alt="No Image Preview"  width="404px !important;" height="410px !important;">
				  </div>
				  <div class="preview_video_block">
					  <video controls="" width="100%" poster="" style="border-radius:3px"><source  src=""></source></video>
					  <br/>
					  <div class="video_preview_og_info_desc inline-block">							
					  </div>
				  </div>
          <div class="row" style="padding-top: 12px;">
            <div class="col-md-12">
              <i class="fa fa-heart" style="font-size: 25px; color:red; padding-left: 10px;"></i>
              <i class="fa fa fa-comment-o" style="font-size: 25px; padding-left: 10px;"></i>
              <i class="fa fa fa-send-o" style="font-size: 25px; padding-left: 10px;"></i>
              <i class="fa fa fa fa-bookmark-o pull-right" style="font-size: 25px; padding-right: 10px;"></i>
            </div>
          </div>
          <div class="row" style="padding-top: 12px;">
            <div class="col-md-12">
              <i class="fa fa-heart-o" style="padding-left: 10px;"></i> 786 likes
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <span class="preview_message"><i class="fa fa-comments-o" style="font-size: 25px; padding-left: 10px;"></i> Add a comment...</span>
            </div>
          </div>
        </div>
      </div>
	  </div>
  </div>
</div>
<script>
  $("document").ready(function(){
    var baseUrl="<?php echo base_url(); ?>";
    $('.ambitious_form_datetime').datetimepicker({
          weekStart: 1,
          todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      forceParse: 0,
          showMeridian: 1
      });
  
    $("#video_block,.preview_img_block,.schedule_block_item,.preview_only_img_block,.preview_video_block").hide();
  
      $(document.body).on('change','input[name=schedule_type]',function(){    
          if($("input[name=schedule_type]:checked").val()=="later")
          $(".schedule_block_item").show();
          else 
          {
            $("#schedule_time").val("");
            $("#time_zone").val("");
            $(".schedule_block_item").hide();
          }
        });
        $(document.body).on('click','.post_type',function(){
          var post_type=$(this).attr("id");
          // alert(post_type);
          if (post_type == "image_post") {
            $("#link_block,#video_block").hide();
            $("#image_block").show();
            $('.post_type').removeClass("tab-active");
            $('#submit_post').attr("submit_type","image_submit");
            $('#submit_post_hidden').val("image_submit");
            $('#image_post').addClass("tab-active");           
           
            $(".demo_preview").hide();
            $(".preview_img_block").hide();
            $(".preview_video_block").hide();
            $(".preview_only_img_block").show();
            var image_url_pre=$("#image_url").val();          
            if(image_url_pre!="")
            {
              $(".only_preview_img").attr("src",image_url_pre);           
            }
          } else if (post_type == "video_post") {
            $("#link_block,#image_block").hide();
            $("#video_block").show();
            $('.post_type').removeClass("tab-active");
            $('#submit_post').attr("submit_type","video_submit");
            $('#submit_post_hidden').val("video_submit");
            $('#video_post').addClass("tab-active");
            $(".demo_preview").hide();        
            $(".preview_img_block").hide();       
            $(".preview_only_img_block").hide();        
            $(".preview_video_block").show();
            var video_url_pre=$("#video_url").val();
            if(video_url_pre!="")
            {
              var write_html='<video width="100%" height="auto" style="border-radius:3px" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+video_url_pre+'">Your browser does not support the video tag.</video>';
              $(".preview_video_block").html(write_html);
            }
          }
          $(this).addClass("active");
        });
        $("#image_url_upload").uploadFile({
          url:baseUrl+"igautopost/uploadImageOnly",
          fileName:"myfile",
          returnType: "json",
          dragDrop: true,
          showDelete: true,
          multiple:false,
          maxFileCount:1,
          acceptFiles:".png,.jpg,.jpeg",
          deleteCallback: function (data, pd) {
              var delete_url="<?php echo site_url('igautopost/deleteUploadedFile');?>";
                $.post(delete_url, {op: "delete",name: data},
                    function (resp,textStatus, jqXHR) {                         
                });            
          },
          onSuccess:function(files,data,xhr,pd){
              // var data_modified = baseUrl+"upload/autopost/"+data;
              var data_modified = "upload/autopost/"+data;
              $("#image_url").val(data_modified);
              var link=$("#image_url").val();              
              $(".only_preview_img").css("border-radius","3px");              
              $(".only_preview_img").attr("src",link);
              $(".demo_preview").hide(); 
              $(".preview_only_img_block").show();
          }
      });
      $("#video_url_upload").uploadFile({
          url:baseUrl+"igautopost/uploadVideo",
          fileName:"myfile",
          maxFileSize:100*1024*1024,
          showPreview:false,
          returnType: "json",
          dragDrop: true,
          showDelete: true,
          multiple:false,
          maxFileCount:1, 
          acceptFiles:".3g2,.3gp,.3gpp,.asf,.avi,.dat,.divx,.dv,.f4v,.flv,.m2ts,.m4v,.mkv,.mod,.mov,.mp4,.mpe,.mpeg,.mpeg4,.mpg,.mts,.nsv,.ogm,.ogv,.qt,.tod,.ts,.vob,.wmv",
          deleteCallback: function (data, pd) {
              var delete_url="<?php echo site_url('igautopost/deleteUploadedFile'); ?>";
                $.post(delete_url, {op: "delete",name: data},
                    function (resp,textStatus, jqXHR) {                         
                    });
             
           },
           onSuccess:function(files,data,xhr,pd)
             {
                 // var data_modified = baseUrl+"upload/autopost/"+data;
                 var data_modified = "upload/autopost/"+data;
                 var write_html='<video width="100%" height="auto" style="border:1px solid #ccc;" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+data_modified+'">Your browser does not support the video tag.</video>';
                 $(".preview_video_block").html(write_html);           
                 $("#video_url").val(data_modified);
             }
      });
      $("#video_thumb_url_upload").uploadFile({
          url:baseUrl+"igautopost/uploadVideoThumb",
          fileName:"myfile",
          maxFileSize:1*1024*1024,
          showPreview:false,
          returnType: "json",
          dragDrop: true,
          showDelete: true,
          multiple:false,
          maxFileCount:1, 
          acceptFiles:".png,.jpg,.jpeg",
          deleteCallback: function (data, pd) {
              var delete_url="<?php echo site_url('igautopost/deleteUploadedFile'); ?>";
                $.post(delete_url, {op: "delete",name: data},
                    function (resp,textStatus, jqXHR) {                         
                });            
          },
          onSuccess:function(files,data,xhr,pd){
              var data_modified = baseUrl+"upload/autopost/"+data;
              $("#video_thumb_url").val(data_modified);             
          }
      });
      var message_pre=$("#message").val();
      message_pre=message_pre.replace(/[\r\n]/g, "<br />");
      if(message_pre!="")
      {
        message_pre=message_pre+"<br/><br/>";
        $(".preview_message").html(message_pre);
      }
      $(document.body).on('keyup','#message',function(){  
          var message=$(this).val();
          message=message.replace(/[\r\n]/g, "<br />");
          if(message!="")
          {
            message=message+"<br/><br/>";
            $(".preview_message").html(message);
          }
        });

        $(document.body).on('blur','#link',function(){
          var link=$("#link").val();
          $.ajax({
              type:'POST' ,
              url:"<?php echo site_url();?>igautopost/metaInfoGrabber",
              data:{link:link},
              dataType:'JSON',
              success:function(response){
                                  
                  $("#link_preview_image").val(response.image);
                  $(".preview_img").attr("src",response.image); 
                  if(typeof(response.image)==='undefined' || response.image=="")
                  $(".preview_img").hide();
                  else $(".preview_img").show();                  
                  $("#link_caption").val(response.title);
                  $(".preview_og_info_title").html(response.title); 
                  $("#link_description").val(response.description);
                  $(".preview_og_info_desc").html(response.description);
                  if(response.image==undefined || response.image=="")
                  $(".preview_img").hide();
                  else $(".preview_img").show();
                $(".preview_img_block").show();                
              }
          }); 
        });
        $(document.body).on('blur','#image_url',function(){     
          var link=$("#image_url").val();              
          $(".only_preview_img").css("border-radius","3px");              
          $(".only_preview_img").attr("src",link);
          $(".demo_preview").hide(); 
          $(".preview_only_img_block").show();
        });
        $(document.body).on('blur','#video_thumb_url',function(){  
          var link=$("#video_thumb_url").val();   
          if(link!='') {
              var write_html='<video width="100%" height="auto" style="border-radius:3px" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+$("#video_url").val()+'">Your browser does not support the video tag.</video>';
              $(".preview_video_block").html(write_html); 
          }          
            
        });
        $(document.body).on('blur','#video_url',function(){  
          var link=$("#video_url").val();   
          if(link!='') {
              $.ajax({
              type:'POST' ,
              url:"<?php echo site_url();?>igautopost/youtubeVideoGrabber",
              data:{link:link},
              success:function(response){
                 if(response!="") {
                    if(response=='fail') {
                      alert("Video URL is invalid or this video is restricted from playback on certain sites.");
                      $("#video_url").val("");
                    } else {
                      var write_html='<video width="100%" height="auto" style="border:0px solid #ccc;" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+response+'">Your browser does not support the video tag.</video>';
                    $(".preview_video_block").html(write_html);  
                    }
                 }              
              }
              });             
          }
        });
        $(document.body).on('click','#submit_post',function(){          
          var post_type=$(this).attr("submit_type");
          if(post_type=="image_submit") {
            if($("#image_url").val()=="") {
              alertify.alert('Image Post', 'Please paste an image url or uplaod an image to post.');
              return;
            }         
          } else if(post_type=="video_submit") {
            if($("#video_url").val()=="") {
              alertify.alert('Video Post', 'Please paste an video url or uplaod an video to post.');
                return;
            }  
          }
          var schedule_type = $("input[name=schedule_type]:checked").val();
          var schedule_time = $("#schedule_time").val();
          var time_zone = $("#time_zone").val();
          if(schedule_type=='later' && (schedule_time=="" || time_zone=="")) {
            alertify.alert('Auto Time', 'Please select schedule time/time zone.');
            return;
          }
          $("#submit_post").html('Processing...');      
          $("#submit_post").addClass("disabled");
          $("#response_modal_content").removeClass("alert-danger");
          $("#response_modal_content").removeClass("alert-success");
          var loading = '<img src="'+baseUrl+'assets/pre-loader/snakes_chasing.gif" class="center-block">';
          $("#response_modal_content").html(loading);
          $("#response_modal").modal();
          var queryString = new FormData($("#auto_post_form")[0]);
          $.ajax({
           type:'POST' ,
           url: baseUrl+"igautopost/addAutoPostAction",
           data: queryString,
           dataType : 'JSON',
           cache: false,
           contentType: false,
           processData: false,
           success:function(response) {              
              $("#submit_post").removeClass("disabled");
              $("#submit_post").html('<i class="fa fa-send"></i> Submit Post');
              var reportLink="<br/><a href='"+baseUrl+"report/autoPost'>See Report</a>";
              if(response.status=="1") {
                $("#response_modal_content").removeClass("alert-danger");
                $("#response_modal_content").addClass("alert-success");
                $("#response_modal_content").html(response.message+reportLink);
              } else {
                $("#response_modal_content").removeClass("alert-success");
                $("#response_modal_content").addClass("alert-danger");
                $("#response_modal_content").html(response.message+reportLink);
              }
            }
        });
        });
    });
</script>

<?php $this->load->view('shadowpostig/auto_post/auto_post_css');?>
<div class="modal fade" id="response_modal" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Auto Post Campaign Status</h4>
			</div>
			<div class="modal-body">
				<div class="alert text-center" id="response_modal_content">
					
				</div>
			</div>
		</div>
	</div>
</div>
