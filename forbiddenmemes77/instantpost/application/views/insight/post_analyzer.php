<style>
	.border-right{
		border-right: 1px solid #f4f4f4;
	}
</style>
<br>
<?php
// echo "<pre>";
// print_r($accouuntInfo);
// echo "</pre>";
?>
<div class='white-box'>
	<div class="row">
		<div class="col-md-3">
			<div>
				<img src='<?php echo $instagramAccount['profile_picture']?>' class='img-circle' alt='Profile' width='152px' height='152px'>
			</div>
		</div>
		<div class="col-md-4">
			<div>
				<h3 style="padding-left: 20px;"><a href='https://www.instagram.com/<?php echo $instagramAccount['igusername'];?>' target='_blank'><?php echo "@".$instagramAccount['igusername'];?></a></h3>
				<br>
				<div class="row">
					<div class="col-md-4 border-right">
					  	<div class="description-block">
					    	<h5 class="description-header"><?php echo $instagramAccount['media_count']?></h5>
						       <span class="description-text">MEDIA</span>
						</div>
					</div>
					<div class="col-md-4 border-right">
					  	<div class="description-block">
					    	<h5 class="description-header"><?php echo $instagramAccount['follower_count']?></h5>
						    <span class="description-text">FOLLOWERS</span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="description-block">
						    <h5 class="description-header"><?php echo $instagramAccount['following_count']?></h5>
						    <span class="description-text">FOLLOWS</span>
				      	</div>
				    </div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<br>
		<br>
		<div class="col-md-1">
		</div>
		<div class="col-md-6">
			<h2>Total Engagement Report</h2>
			<?php
				$item=1;
            	$totalLike = 0;
            	$totalComment = 0;
            	$totalFollowers = $instagramAccount['follower_count'];
            	foreach ($accouuntInfo["items"] as $accouunt) {
            		$totalLike = $totalLike + $accouunt['like_count'];
            		$totalComment = $totalComment + $accouunt['comment_count'];
            		$item++;
            	}
            	$accountEngagement = ((($totalLike+$totalComment)/$totalFollowers) * 100) / $item;
            	$accountEngagement = sprintf('%.3f', $accountEngagement)."%";
            	$avarageLike = $totalLike/$item;
            	$avarageLike = sprintf('%.3f', $avarageLike);
            	$avarageComment = $totalComment/$item;
            	$avarageComment = sprintf('%.3f', $avarageComment);
			?>
			<div class="row">
				<div class="col-md-4">
					<h3>Engagement <a href="#" data-toggle="tooltip" title="total engagement base on follower like comment"><i class="fa fa-info"></i></a></h3>				
				</div>
				<div class="col-md-8">
					<h3><?php echo $accountEngagement; ?></h3>
				</div>
				<div class="col-md-4">
					<h3>Like <a href="#" data-toggle="tooltip" title="avarage like base on latest <?php echo $item-1;?> post"><i class="fa fa-thumbs-up"></i></a></h3>				
				</div>
				<div class="col-md-8">
					<h3><?php echo $avarageLike; ?></h3>
				</div>
				<div class="col-md-4">
					<h3>Comment <a href="#" data-toggle="tooltip" title="avarage comment base on latest <?php echo $item-1;?> post"><i class="fa fa-comment"></i></a></h3>				
				</div>
				<div class="col-md-8">
					<h3><?php echo $avarageComment; ?></h3>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<h2>FOLLOWERS vs FOLLOWS</h2>
			<div id="canvas-holder">
				<canvas id="pie_area"></canvas>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="table-responsive">
			<table class="table color-table inverse-table">
				<thead>
					<tr>
	                    <th>#</th>
	                    <th>Media type</th>
	                    <th>Visit</th>
	                    <th>Content</th>
	                    <th>Like count</th>
	                    <th>Comments count</th>
	                    <th>Total Engagement</th>	                    
	                    <th>Caption</th>
	                </tr>
				</thead>
				<tbody>
					<?php $i=0;
					foreach($accouuntInfo["items"] as $json) : $i++;
					$media_type = $json["media_type"];
					$media_info = "";
					if($media_type == "1"){
						$post_type = "Image";
						$media_info = "<img src='".$json['image_versions2']['candidates']['1']['url']."' class='img-rounded' alt='Cinque Terre' width='70' height='90'>";
					} elseif ($media_type == "2") {
						$post_type = "Video";
					} elseif ($media_type == "8") {
						$post_type = "Carousel";
					} else{
						$post_type = "Unknown";
					}
					$post_url = "<a href='https://www.instagram.com/p/".$json['code']."' title='Visit your post' target='_BLANK'<span class='btn btn-info btn-circle btn-lg btn-outline'><i class='fa fa-hand-o-right'></i></span></a>";
					?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $post_type;?></td>
						<td><?php echo $post_url;?></td>
						<td><?php echo $media_info;?></td>
						<td><?php echo $json["like_count"]?></td>
						<td><?php echo $json["comment_count"]?></td>
						<td><?php echo $json['like_count']+$json['comment_count'];?></td>
						
						<td><?php echo implode(' ', array_slice(explode(' ', $json["caption"]["text"]), 0, 4));?></td>
					</tr>
					<?php if($i==15) break;?>
                	<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<script>
	var dataName = <?php echo $dataName; ?>;
	var dataValue = <?php echo $dataValue; ?>;
	var colorData = <?php echo $colorData; ?>;
	
	var pie_config = {
		type: 'pie',
		data: {
			datasets: [{
				data: dataValue,
				backgroundColor: colorData
			}],
			labels: dataName
		},
		options: {
		    legend: {
		       display: false
		    },
		    responsive: true
		}
	};
	window.onload = function() {
		var try_pie = document.getElementById('pie_area').getContext('2d');
		window.myPie = new Chart(try_pie, pie_config);
	};
</script>