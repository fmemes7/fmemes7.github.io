<?php $this->load->view('layouts/message_layout'); ?>
<section class="row" style="min-height: 100px">
	<section class="col-md-12">
		<?php 
            $text = 'Generate Your '.$this->config->item('itemShortName').' API Key';
            $getKeyText = 'Get Your '.$this->config->item('itemShortName').' API Key';
            if (isset($apiKey) && $apiKey != '') {
                $text = 'Re-generate Your '.$this->config->item('itemShortName').' API Key';
                $getKeyText = 'Your '.$this->config->item('itemShortName').' API Key';
            }
        ?>
        <form class="form-horizontal" enctype="multipart/form-data" action="<?php echo site_url().'cronjob/getApiAction'; ?>" method="GET">
		        <div class="box-body" style="padding-top:0;">
		           	<div class="form-group">
		           		<div class="small-box bg-blue">
							<div class="inner" style="padding: 30px !important">
								<h4><?php echo $getKeyText; ?></h4>
								<p>									
		   							<h2><?php echo $apiKey; ?></h2>
								</p>
								<input name="button" type="submit" class="btn btn-default btn-lg btn" value="<?php echo $text; ?>"/>
							</div>
							<div class="icon">
								<i class="fa fa-key"></i>
							</div>
						</div>
		            </div>	           
	         		               
		           </div> <!-- /.box-body -->      
		</form>
	</section>
</section>
<div class="row">
	<div class="col-md-12">
		<?php 
        if ($apiKey != '') {
            ?>
			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;">
						<i class="fa fa-clock-o"></i> Auto Poster Cron Job [Once per minute or higher]
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo 'curl '.site_url('cronjob/igAutoPost').'/'.$apiKey; ?>
				</div>
			</div>
			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;">
						<i class="fa fa-clock-o"></i> Auto Story Post Cron Job [Once per Hour]
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo 'curl '.site_url('cronjob/igStoryPost').'/'.$apiKey; ?>
				</div>
			</div>
			<div id=''>
				<h4 style="margin:0">
					<div class="alert alert-info" style="margin-bottom:0;">
						<i class="fa fa-clock-o"></i> Auto Story Poll Post Cron Job [Once per Hour]
					</div>
				</h4>
				<div class="well" style="background:#F9F2F4;margin-top:0;border-radius:0;;">
					<?php echo 'curl '.site_url('cronjob/igStoryPollPost').'/'.$apiKey; ?>
				</div>
			</div>
			
		<?php
        }?>
	</div>
</div>