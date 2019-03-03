<nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="<?php echo base_url();?>">
                        <b>
                            <img src="<?php echo base_url();?>assets/img/favicon.png" alt="home" class="light-logo" />
                        </b>
                        <span class="hidden-xs">
                        <img src="<?php echo base_url();?>assets/img/logo-text.png" alt="home" class="light-logo" />
                     </span> </a>
                </div>
                <ul class="nav navbar-top-links navbar-left">
                    <li><a href="javascript:void(0)" class="open-close waves-effect waves-light"><span style="color: white; mat"><i class="fa fa-bars"></i></span></a></li>
                    <!-- /.Megamenu -->
                </ul>
                <!-- /Logo -->
                <!-- Search input and Toggle icon -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                    <?php
                        echo form_dropdown('language',$language_info,$this->language,'class="form-control  pull-right hidden-xs" id="language_change" style="width:100px;height:40px;margin-top:10px; font-size:10px;"');
                    ?>
                        <span class="red"><?php echo form_error('language'); ?></span>  
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="<?php echo $this->session->userdata('userLogo');?>" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $this->session->userdata('username');?></b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="<?php echo $this->session->userdata('userLogo');?>" alt="user" /></div>
                                    <div class="u-text">
                                        <h4><?php echo $this->session->userdata('username');?></h4>
                                        <p class="text-muted"><?php echo $this->session->userdata('userLoginEmail');?></p></div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo site_url('member/profile') ?>"><i class="fa fa-user-o"></i> My Profile</a></li>
                            <li><a href="<?php echo site_url('member') ?>"><i class="fa fa-cog"></i> Account Setting</a></li>
                            <li><a href="<?php echo site_url('password') ?>"><i class="fa fa-key"></i> Change Password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo site_url('main/logout') ?>"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>