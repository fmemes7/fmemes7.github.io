<style>    
.card-box {
    padding: 20px;
    border: 1px solid rgba(54, 64, 74, 0.05);
    border-radius: 5px;
    margin-bottom: 20px;
    background-clip: padding-box;
    background-color: #ffffff;
}
.widget-bg-color-icon .bg-icon-info {
    background-color: rgba(52, 211, 235, 0.2);
    border: 1px solid #34d3eb;
}
.widget-bg-color-icon .bg-icon {
    height: 80px;
    width: 80px;
    text-align: center;
    border-radius: 50%;
}
.widget-bg-color-icon .bg-icon i {
    font-size: 32px;
    line-height: 80px;
}
.bg-icon-account{
    background-color: rgb(77, 184, 255, 0.3);
    border: 1px solid rgb(77, 184, 255);
}
.bg-icon-page{
    background-color: rgba(65, 102, 180, 0.5);
    border: 1px solid rgba(65, 102, 180);
}
.bg-icon-group{
    background-color: rgba(247, 148, 29, 0.4);
    border: 1px solid rgba(247, 148, 29);
}
.bg-icon-story{
    background-color: rgba(0, 148, 29, 0.4);
    border: 1px solid rgba(0, 148, 29);
}
.text-story{
    color: rgba(0, 148, 29) !important;
}
.text-group{
    color: rgba(247, 148, 29) !important;
}
.text-page{
    color: rgba(65, 102, 180) !important;
}
.text-account{
	color: rgb(77, 184, 255) !important;
}
</style>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<h4 class="page-title"><?php echo $this->lang->line('Dashboard');?></h4>
            <p class="text-muted page-title-alt"><?php echo $this->lang->line('Welcome to');?> <?php echo $this->config->item('itemName'); ?> <?php echo $this->session->userdata('userType');?> <?php echo $this->lang->line('panel !');?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4">
        </div>
    	<div class="col-md-4 col-lg-4 col-xl-4">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-account pull-left">
                    <i class="fa fa-user text-account"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo number_format($account_number); ?></b></h3>
                    <p class="text-muted mb-0"><?php echo $this->lang->line('Total Accounts');?></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
        </div>        
	</div>
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-page pull-left">
                    <i class="fa fa-paper-plane text-page"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo number_format($auto_post); ?></b></h3>
                    <p class="text-muted mb-0"><?php echo $this->lang->line('Auto Post');?></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-group pull-left">
                    <i class="fa fa fa-spinner text-group"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo number_format($story_post); ?></b></h3>
                    <p class="text-muted mb-0"><?php echo $this->lang->line('Story Post');?></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-story pull-left">
                    <i class="fa fa-circle-o-notch text-story"></i>
                </div>
                <div class="text-right">
                    <h3 class="text-dark"><b class="counter"><?php echo number_format($story_poll_post); ?></b></h3>
                    <p class="text-muted mb-0"><?php echo $this->lang->line('Story Poll Post');?></p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
