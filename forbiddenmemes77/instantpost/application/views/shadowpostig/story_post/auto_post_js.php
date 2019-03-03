<script>
	$("document").ready(function(){
		var baseUrl="<?php echo base_url(); ?>";
        $("#example1").emojioneArea();
		$('.ambitious_form_datetime').datetimepicker({
        	weekStart: 1,
        	todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
        	showMeridian: 1
    	});
	
		$("#link_block,#image_block,#video_block,.preview_img_block,.auto_share_enable_contant,.auto_comment_enable_contant,.auto_reply_enable_contant,.schedule_block_item,.preview_only_img_block,.preview_video_block").hide();
	
		$(document.body).on('change','input[name=auto_share_post]',function(){    
   	    	if($("input[name=auto_share_post]:checked").val()=="1") {
   	    		$(".auto_share_enable_contant").show();
   	    	} else {
   	    		$(".auto_share_enable_contant").hide();
   	    	}
   	 	});
	
   	 	$(document.body).on('change','input[name=auto_comment]',function(){    
   	    	if($("input[name=auto_comment]:checked").val()=="1") {
   	    		$(".auto_comment_enable_contant").show();
   	    	} else {
   	    		$(".auto_comment_enable_contant").hide();
   	    	}
   	 	});
	
   	 	$(document.body).on('change','input[name=auto_reply]',function(){
   	     	if($("input[name=auto_reply]:checked").val()=="1") {
   	     		$(".auto_reply_enable_contant").show();
   	     	} else {
   	     		$(".auto_reply_enable_contant").hide();
   	     	}
   	 	});
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
        	if(post_type == "text_post") {
        		$("#link_block,#image_block,#video_block").hide();
        		$('.post_type').removeClass("active");
        		$('.post_type').removeClass("btn-info");
        		$('.post_type').addClass("btn-default");
        		$('#text_post').addClass("btn-info");
        		$('#submit_post').attr("submit_type","text_submit");
        		$('#submit_post_hidden').val("text_submit");
        		$(".demo_preview").hide();
        		$(".preview_img_block").hide();
        		$(".preview_only_img_block").hide();
        		$(".preview_video_block").hide();
        	} else if (post_type == "link_post") {
        		$("#image_block,#video_block").hide();
        		$("#link_block").show();
        		$('.post_type').removeClass("active");
        		$('.post_type').removeClass("btn-info");
        		$('.post_type').addClass("btn-default");
        		$('#link_post').addClass("btn-info");
        		$('#submit_post').attr("submit_type","link_submit");
        		$('#submit_post_hidden').val("link_submit");
        		
        		$(".demo_preview").hide();
        		$(".preview_img_block").show();
        		$(".preview_only_img_block").hide();
        		$(".preview_video_block").hide();
        		var link_pre=$("#link").val();
		    	if(link_pre!="") {
		    		$(".preview_og_info_link").html(link_pre);
		    		
		    	}
        		
        	} else if (post_type == "image_post") {
        		$("#link_block,#video_block").hide();
        		$("#image_block").show();
        		$('.post_type').removeClass("active");
        		$('.post_type').removeClass("btn-info");
        		$('.post_type').addClass("btn-default");
        		$('#image_post').addClass("btn-info");
        		$('#submit_post').attr("submit_type","image_submit");
        		$('#submit_post_hidden').val("image_submit");
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
        		$('.post_type').removeClass("active");
        		$('.post_type').removeClass("btn-info");
        		$('.post_type').addClass("btn-default");
        		$('#video_post').addClass("btn-info");        		
        		$('#submit_post').attr("submit_type","video_submit");
        		$('#submit_post_hidden').val("video_submit");
        		$(".demo_preview").hide();    		
	    		$(".preview_img_block").hide();    		
	    		$(".preview_only_img_block").hide();    		
	    		$(".preview_video_block").show();
        		var video_url_pre=$("#video_url").val();
		    	if(video_url_pre!="")
		    	{
		    		var write_html='<video width="100%" height="auto" style="border:1px solid #ccc;" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+video_url_pre+'">Your browser does not support the video tag.</video>';
		    		$(".preview_video_block").html(write_html);	
		    	}
        	}
        	$(this).addClass("active");
        });
        $("#image_url_upload").uploadFile({
	        url:baseUrl+"igautopost/uploadImageOnly",
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
	            $("#image_url").val(data_modified);	
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
	               var data_modified = baseUrl+"upload/autopost/"+data;            
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
        	if(message!="") {
        		message=message+"<br/><br/>";
        		$(".preview_message").html(message);
        		$(".demo_preview").hide();
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
            $(".only_preview_img").css("border","1px solid #ccc");	            
            $(".only_preview_img").attr("src",link);    
        	$(".preview_only_img_block").show();	 	
	         
        });
        $(document.body).on('blur','#video_thumb_url',function(){  
        	var link=$("#video_thumb_url").val();   
	        if(link!='') {
	            var write_html='<video width="100%" height="auto" style="border:1px solid #ccc;" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+$("#video_url").val()+'">Your browser does not support the video tag.</video>';
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
	               	 		var write_html='<video width="100%" height="auto" style="border:1px solid #ccc;" controls poster="'+$("#video_thumb_url").val()+'"><source src="'+response+'">Your browser does not support the video tag.</video>';
	            			$(".preview_video_block").html(write_html);	 
	               	 	}
	               }              
	            }
	            });	            
	        }
        });
        $(document.body).on('click','#submit_post',function(){        	
        	var post_type=$(this).attr("submit_type");
        	if(post_type=="text_submit") {
        		if($("#message").val()=="") {
        			alertify.alert('Text Post', 'Please type a text to post.', function(){ 
        				alertify.success('Ok');
        				return;
        			});
        		}
        	} else if(post_type=="link_submit") {
        		if($("#link").val()=="") {
        			alertify.alert('Link Post', 'Please paste a link to post.', function(){ 
        				alertify.success('Ok');
        				return;
        			});
        		}
        	} else if(post_type=="image_submit") {   
        		if($("#image_url").val()=="") {
        			alertify.alert('Image Post', 'Please paste an image url or uplaod an image to post.', function(){ 
        				alertify.success('Ok');
        				return;
        			});
        		}     		
        	} else if(post_type=="video_submit") {
        		if($("#video_url").val()=="") {
        			alertify.alert('Video Post', 'Please paste an video url or uplaod an video to post.', function(){ 
        				alertify.success('Ok');
        				return;
        			});
        		}  
        	}

        	var postToProfile = $("input[name=post_to_profile]:checked").val();
        	var postToPages = $("#post_to_pages").val();
        	var postToGroups = $("#post_to_groups").val();
        	if(postToProfile=="No" && postToPages=="" && postToGroups=="") {
        		alertify.alert('Poster', 'Please select timeline/pages/groups to publish this post.', function(){ 
        			alertify.success('Ok');
        			return;
        		});
        	}
        	var auto_share_post = $("input[name=auto_share_post]:checked").val();
        	var auto_share_this_post_by_pages = $("#auto_share_this_post_by_pages").val();
        	if((auto_share_post=='1' && auto_share_this_post_by_pages=="") && $("input[name=auto_share_to_profile]:checked").val() == "No") {
        		alertify.alert('Auto share', 'Please select timeline or page(s) for auto sharing.', function(){ 
        			alertify.success('Ok');
        			return;
        		});
        	}
        	var auto_private_reply = $("input[name=auto_reply]:checked").val();
        	var auto_private_reply_text = $("#auto_private_reply_text").val();
        	if(auto_private_reply=='1' && auto_private_reply_text=="") {
        		alertify.alert('Auto Reply', 'Please type private reply message.', function(){ 
        			alertify.success('Ok');
        			return;
        		});
        	}
        	var auto_comment = $("input[name=auto_comment]:checked").val();
        	var auto_comment_text = $("#auto_comment_text").val();
        	if(auto_comment=='1' && auto_comment_text=="") {
        		alertify.alert('Auto Comment', 'Please type auto comment message.', function(){ 
        			alertify.success('Ok');
        			return;
        		});
        	}
        	var schedule_type = $("input[name=schedule_type]:checked").val();
        	var schedule_time = $("#schedule_time").val();
        	var time_zone = $("#time_zone").val();
        	if(schedule_type=='later' && (schedule_time=="" || time_zone=="")) {
        		alertify.alert('Auto Time', 'Please select schedule time/time zone.', function(){ 
        			alertify.success('Ok');
        			return;
        		});
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
		       url: baseUrl+"igautopost/addStoryPostAction",
		       data: queryString,
		       dataType : 'JSON',
		       cache: false,
		       contentType: false,
		       processData: false,
		       success:function(response) {  		         
		       		$("#submit_post").removeClass("disabled");
		         	$("#submit_post").html('<i class="fa fa-send"></i> Submit Post');
		         	var reportLink="<br/><a href='"+baseUrl+"igautopost/autoPostReport'>See Report</a>";
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