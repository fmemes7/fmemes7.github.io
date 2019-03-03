<?php
	$c = $this->uri->segment(1);
	$m = $this->uri->segment(2);
?>
<div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="ambitious-user-profile">                    
                </div>
                <ul class="nav" id="side-menu">
                    <li <?php if($c == "dashboard") echo "class='active'"?>> <a href="<?php echo site_url()."dashboard";?>" class="waves-effect"><i class="fa fa-dashboard ambitious-font" ></i>  <?php echo $this->lang->line("Dashboard");?></a> </li>
                    
                    <?php if($this->session->userdata('userType') == 'Admin'): ?>
                    <li <?php if($c == "admin" || $c == "isconfiguration") echo "class='active'"?> <?php if($m == "admin_payment_history") echo "class='active'"?>> <a href="javascript:void(0)" class="waves-effect <?php if($c == "admin" || $c == "isconfiguration") echo 'active';?> <?php if($m == "admin_payment_history") echo "class='active'"?>"><i class="fa fa-cogs ambitious-font"></i> <span class="hide-menu"><?php echo $this->lang->line("Setting");?><span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li <?php if(strpos($m,"general") !== false || strpos($c, "isconfiguration") !== false) echo "class='active'"?>><a href="javascript:void(0)" class="waves-effect <?php if(strpos($m,"general") !== false || strpos($c, "isconfiguration") !== false) echo 'active';?>"><i class="fa fa-cog ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("General Setting");?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li> <a href="<?php echo site_url()."admin/generalApplicationConfiguration";?>"><i class="fa fa-globe ambitious-font-3rd"></i> <span class="hide-menu"><?php echo $this->lang->line("Application Setting");?></span></a></li>
                                    <li> <a href="<?php echo site_url()."admin/generalSmtpConfiguration";?>"><i class="fa fa-envelope-open-o ambitious-font-3rd"></i> <span class="hide-menu"><?php echo $this->lang->line("SMTP Setting");?></span></a></li>
                                    <li> <a href="<?php echo site_url()."admin/generalSocialLoginConfiguration";?>"><i class="fa fa-sign-in ambitious-font-3rd"></i> <span class="hide-menu"><?php echo $this->lang->line("Social Login Setting");?></span></a></li>                                    
                                </ul>
                            </li>
                            <li <?php if(strpos($m,"payment") !== false ) echo "class='active'"?>><a href="javascript:void(0)" class="waves-effect <?php if(strpos($m,"payment") !== false ) echo 'active';?>"><i class="fa fa-usd ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("Payment");?></span><span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li> <a href="<?php echo site_url()."admin/paymentConfiguration";?>"><i class="fa fa-cog ambitious-font-3rd"></i> <span class="hide-menu"><?php echo $this->lang->line("Payment Setting");?></span></a></li>
                                    <li> <a href="<?php echo site_url()."payment/admin_payment_history";?>"><i class="fa fa-history ambitious-font-3rd"></i> <span class="hide-menu"><?php echo $this->lang->line("Payment History");?></span></a></li>
                                </ul>
                            </li>
                            <li <?php if(strpos($m,"userManagement") !== false ) echo "class='active'"?>><a href="<?php echo site_url()."admin/userManagement";?>"><i class="fa fa-user-o ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("User Management");?></span></a></li>
                            <li <?php if(strpos($m,"sendEmail") !== false ) echo "class='active'"?>><a href="<?php echo site_url()."admin/sendEmail";?>"><i class="fa fa-envelope-o ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("Send Email");?></span></a></li>
                            <li <?php if(strpos($m,"packageSettings") !== false ) echo "class='active'"?>><a href="<?php echo site_url()."admin/packageSettings";?>"><i class="fa fa-dashcube ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("Package Setting");?></span></a></li>
        
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata('userType') == 'Member'): ?>
                    <li <?php if(strpos($m,"member_payment_history") !== false ) echo "class='active'"?>> <a href="<?php echo site_url()."payment/member_payment_history";?>" class="waves-effect"><i class="fa fa-history ambitious-font" ></i><?php echo $this->lang->line("Payment History");?></a></li>
                    <?php endif; ?>
                    <?php if($this->session->userdata("userType")=="Admin" || in_array(500,$this->moduleAccess)) : ?> 
                    <li <?php if($c == "uoconnectaccount") echo "class='active'"?>> <a href="<?php echo site_url()."uoconnectaccount";?>" class="waves-effect"><i class="fa fa-plus ambitious-font" ></i>  <?php echo $this->lang->line("Connect Account");?></a></li>
                    <?php endif; ?>
                    <?php if($this->session->userdata("userType")=="Admin" || in_array(501,$this->moduleAccess)) : ?> 
                    <li <?php if($c == "igautopost") echo "class='active'"?>> <a href="<?php echo site_url()."igautopost";?>" class="waves-effect"><i class="fa fa-instagram ambitious-font" ></i>  <?php echo $this->lang->line("Auto Post");?></a> </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata("userType")=="Admin" || in_array(502,$this->moduleAccess)) : ?> 
                    <li <?php if($c == "storypost") echo "class='active'"?>> <a href="<?php echo site_url()."storypost";?>" class="waves-effect"><i class="fa fa-spinner ambitious-font" ></i>
                    <?php echo $this->lang->line("Story Post");?></a> </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata("userType")=="Admin" || in_array(503,$this->moduleAccess)) : ?> 
                    <li <?php if($c == "storypollpost") echo "class='active'"?>> <a href="<?php echo site_url()."storypollpost";?>" class="waves-effect"><i class="fa fa-hand-pointer-o ambitious-font" ></i>
                    <?php echo $this->lang->line("Story Poll Post");?></a> </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata("userType")=="Admin" || in_array(501,$this->moduleAccess)) : ?> 
                    <li <?php if($c == "report") echo "class='active'"?>> <a href="javascript:void(0)" class="waves-effect <?php if($c == "report") echo 'active';?>"><i class="fa fa-list ambitious-font"></i> <span class="hide-menu"><?php echo $this->lang->line("Report");?><span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <?php if($this->session->userdata("userType")=="Admin" || in_array(501,$this->moduleAccess)) : ?>
                            <li <?php if(strpos($m,"autoPost") !== false ) echo "class='active'"?>><a href="<?php echo site_url()."report/autoPost";?>"><i class="fa fa-bullseye ambitious-font-2nd"></i> <span class="hide-menu"><?php echo $this->lang->line("Auto Post Report");?></span></a></li>
                            <?php endif; ?>        
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if($this->session->userdata('userType') == 'Admin'): ?>
                    <li <?php if($c == "cronjob") echo "class='active'"?>> <a href="<?php echo site_url()."cronjob";?>" class="waves-effect"><i class="fa fa-clock-o ambitious-font" ></i>  <?php echo $this->lang->line("Cron Job");?></a> </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>