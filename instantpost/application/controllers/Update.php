<?php
require_once("Main.php"); // loading main controller
/**
* @category controller
* class Admin
*/
class Update extends Main
{
    /**
     * Load constructor method
     * @access public
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('userId');
        set_time_limit(0);
    }
    public function index()
    {
        $this->v_1_1to1_2();
    }
    public function v_1_1to1_2()
    {
        $lines="ALTER TABLE `instagram_account_info` ADD `media_count` VARCHAR(200) NOT NULL AFTER `profile_picture`;
        ALTER TABLE `instagram_account_info` ADD `follower_count` VARCHAR(200) NOT NULL AFTER `media_count`;
        ALTER TABLE `instagram_account_info` ADD `following_count` VARCHAR(200) NOT NULL AFTER `follower_count`;
        ALTER TABLE `instagram_account_info` ADD `is_business` ENUM('1','0') NOT NULL DEFAULT '0' AFTER `following_count`;
        CREATE TABLE IF NOT EXISTS `ci_sessions` (
          `id` varchar(128) NOT NULL,
          `ip_address` varchar(45) NOT NULL,
          `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
          `data` blob NOT NULL,
          KEY `ci_sessions_timestamp` (`timestamp`)
        );
        ";
       
        // Loop through each line
        $lines=explode(";", $lines);
        $count=0;
        foreach ($lines as $line) 
        {
            $count++;      
            $this->db->query($line);
        }
        echo $this->config->item('itemShortName')." has been updated successfully.".$count." queries executed.";
    }
}
