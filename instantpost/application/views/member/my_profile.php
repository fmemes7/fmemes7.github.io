<br>
<br>
<?php
// echo "<pre>";
// print_r($profileInfo);
// echo "</pre>";
// echo "<pre>";
// print_r($this->session->all_userdata());
// echo "</pre>";
?>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="white-box">
            <div class="user-bg"> <img width="100%" alt="user" src="<?php echo base_url();?>assets/img/logo-background.png">
                <div class="overlay-box">
                    <div class="user-content">
                        <a href="javascript:void(0)"><img src="<?php echo $this->session->userdata('userLogo');?>" class="thumb-lg img-circle" alt="img"></a>
                        <h4 class="text-white"><?php echo $profileInfo[0]['name'];?></h4>
                        <h5 class="text-white"><?php echo $profileInfo[0]['email'];?></h5> </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xs-12">
        <div class="white-box">
            <ul class="nav nav-tabs tabs customtab">
                <li class="tab active">
                    <a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Profile</span> </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="profile">
                    <div class="row">
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                            <br>
                            <p class="text-muted"><?php echo $profileInfo[0]['name'];?></p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                            <br>
                            <p class="text-muted"><?php echo $profileInfo[0]['phone'];?></p>
                        </div>
                        <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                            <br>
                            <p class="text-muted"><?php echo $profileInfo[0]['email'];?></p>
                        </div>
                        <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                            <br>
                            <p class="text-muted"><?php echo $profileInfo[0]['address'];?></p>
                        </div>
                    </div>
                    <hr>
                    <p><?php echo $profileInfo[0]['my_note'];?></p>                                                                              
                </div>
            </div>
        </div>
    </div>
</div>