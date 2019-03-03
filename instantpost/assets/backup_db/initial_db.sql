DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
);

DROP TABLE IF EXISTS `cron_job`;
CREATE TABLE IF NOT EXISTS `cron_job` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `facebook_app`;
CREATE TABLE IF NOT EXISTS `facebook_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_id` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `numeric_id` varchar(250) NOT NULL,
  `user_access_token` varchar(500) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `use_by` enum('only_me','everyone') NOT NULL DEFAULT 'only_me',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `forget_password`;
CREATE TABLE IF NOT EXISTS `forget_password` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `confirmation_code` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `instagram_account_info`;
CREATE TABLE IF NOT EXISTS `instagram_account_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `igusername` varchar(200) DEFAULT NULL,
  `igpassword` varchar(200) DEFAULT NULL,
  `igproxy` varchar(200) NOT NULL,
  `igpk` varchar(255) NOT NULL,
  `ig_full_name` varchar(255) NOT NULL,
  `profile_picture` text NOT NULL,
  `media_count` varchar(200) NOT NULL,
  `follower_count` varchar(200) NOT NULL,
  `following_count` varchar(200) NOT NULL,
  `is_business` enum('1','0') NOT NULL DEFAULT '0',
  `add_date` date NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `instagram_auto_post`;
CREATE TABLE IF NOT EXISTS `instagram_auto_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shadow_post_user_info_id` int(11) NOT NULL,
  `post_type` enum('text_submit','link_submit','image_submit','video_submit') NOT NULL DEFAULT 'text_submit',
  `page_group_user_id` varchar(200) NOT NULL,
  `page_or_group_or_user` enum('page','group','user') NOT NULL,
  `page_or_group_or_user_name` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `link` text NOT NULL,
  `image_url` text NOT NULL,
  `video_url` text NOT NULL,
  `video_thumb_url` text NOT NULL,
  `auto_share_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_share_this_post_by_pages` text NOT NULL,
  `auto_share_to_profile` enum('0','1') NOT NULL DEFAULT '0',
  `auto_like_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply_text` text NOT NULL,
  `auto_reply_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'taken by cronjob or not',
  `auto_reply_count` int(11) NOT NULL,
  `auto_reply_done_ids` text NOT NULL,
  `auto_comment` enum('0','1') NOT NULL DEFAULT '0',
  `auto_comment_text` varchar(200) NOT NULL,
  `posting_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,processing,completed',
  `post_id` varchar(200) NOT NULL COMMENT 'fb post id',
  `post_url` text NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `post_auto_comment_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto comment is done by cron job',
  `post_auto_like_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto like is done by cron job',
  `post_auto_share_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto share is done by cron job',
  `error_mesage` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`shadow_post_user_info_id`),
  KEY `posting_status` (`posting_status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `instagram_story_poll_post`;
CREATE TABLE IF NOT EXISTS `instagram_story_poll_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shadow_post_user_info_id` int(11) NOT NULL,
  `post_type` enum('image_submit') NOT NULL DEFAULT 'image_submit',
  `page_group_user_id` varchar(200) NOT NULL,
  `page_or_group_or_user` enum('page','group','user') NOT NULL,
  `page_or_group_or_user_name` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `link` text NOT NULL,
  `option_one` text NOT NULL,
  `option_two` text NOT NULL,
  `image_url` text NOT NULL,
  `video_url` text NOT NULL,
  `video_thumb_url` text NOT NULL,
  `auto_share_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_share_this_post_by_pages` text NOT NULL,
  `auto_share_to_profile` enum('0','1') NOT NULL DEFAULT '0',
  `auto_like_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply_text` text NOT NULL,
  `auto_reply_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'taken by cronjob or not',
  `auto_reply_count` int(11) NOT NULL,
  `auto_reply_done_ids` text NOT NULL,
  `auto_comment` enum('0','1') NOT NULL DEFAULT '0',
  `auto_comment_text` varchar(200) NOT NULL,
  `posting_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,processing,completed',
  `post_id` varchar(200) NOT NULL COMMENT 'fb post id',
  `post_url` text NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `post_auto_comment_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto comment is done by cron job',
  `post_auto_like_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto like is done by cron job',
  `post_auto_share_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto share is done by cron job',
  `error_mesage` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`shadow_post_user_info_id`),
  KEY `posting_status` (`posting_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `instagram_story_post`;
CREATE TABLE IF NOT EXISTS `instagram_story_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shadow_post_user_info_id` int(11) NOT NULL,
  `post_type` enum('image_submit') NOT NULL DEFAULT 'image_submit',
  `page_group_user_id` varchar(200) NOT NULL,
  `page_or_group_or_user` enum('page','group','user') NOT NULL,
  `page_or_group_or_user_name` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `link` text NOT NULL,
  `image_url` text NOT NULL,
  `video_url` text NOT NULL,
  `video_thumb_url` text NOT NULL,
  `auto_share_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_share_this_post_by_pages` text NOT NULL,
  `auto_share_to_profile` enum('0','1') NOT NULL DEFAULT '0',
  `auto_like_post` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply` enum('0','1') NOT NULL DEFAULT '0',
  `auto_reply_text` text NOT NULL,
  `auto_reply_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'taken by cronjob or not',
  `auto_reply_count` int(11) NOT NULL,
  `auto_reply_done_ids` text NOT NULL,
  `auto_comment` enum('0','1') NOT NULL DEFAULT '0',
  `auto_comment_text` varchar(200) NOT NULL,
  `posting_status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT 'pending,processing,completed',
  `post_id` varchar(200) NOT NULL COMMENT 'fb post id',
  `post_url` text NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `schedule_time` datetime NOT NULL,
  `time_zone` varchar(100) NOT NULL,
  `post_auto_comment_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto comment is done by cron job',
  `post_auto_like_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto like is done by cron job',
  `post_auto_share_cron_jon_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'post''s auto share is done by cron job',
  `error_mesage` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`shadow_post_user_info_id`),
  KEY `posting_status` (`posting_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(250) DEFAULT NULL,
  `add_ons_id` int(11) NOT NULL,
  `extra_text` varchar(50) NOT NULL DEFAULT 'month',
  `limit_enabled` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=504 DEFAULT CHARSET=utf8;

INSERT INTO `modules` (`id`, `module_name`, `add_ons_id`, `extra_text`, `limit_enabled`, `deleted`) VALUES
(500, 'Instagram - Account Import', 0, '', '1', '0'),
(501, 'Instagram - Auto Post', 0, '', '0', '0'),
(502, 'Instagram - Story Post', 0, '', '0', '0'),
(503, 'Instagram - Story Poll Post', 0, '', '0', '0');

DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package_name` varchar(250) NOT NULL,
  `module_ids` varchar(250) NOT NULL,
  `monthly_limit` text,
  `bulk_limit` text,
  `price` varchar(20) NOT NULL DEFAULT '0',
  `validity` int(11) NOT NULL,
  `is_default` enum('0','1') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `package` (`id`, `package_name`, `module_ids`, `monthly_limit`, `bulk_limit`, `price`, `validity`, `is_default`, `deleted`) VALUES
(1, 'Trial', '500,501,502,503,504,505', '{\"500\":0,\"501\":0,\"502\":0,\"503\":0,\"504\":0,\"505\":0}', '{\"500\":0,\"501\":0,\"502\":0,\"503\":0,\"504\":0,\"505\":0}', '0', 7, '1', '0');

DROP TABLE IF EXISTS `payment_configuration`;
CREATE TABLE IF NOT EXISTS `payment_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paypal_email` varchar(250) NOT NULL,
  `stripe_secret_key` varchar(150) NOT NULL,
  `stripe_publishable_key` varchar(150) NOT NULL,
  `currency` enum('USD','AUD','BRL','CAD','CZK','DKK','EUR','HKD','HUF','ILS','JPY','MYR','MXN','TWD','NZD','NOK','PHP','PLN','GBP','RUB','SGD','SEK','CHF','VND') NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `payment_configuration` (`id`, `paypal_email`, `stripe_secret_key`, `stripe_publishable_key`, `currency`, `deleted`) VALUES
(1, 'Paypalemail@example.com', '', '', 'USD', '0');

DROP TABLE IF EXISTS `smtp_configuration`;
CREATE TABLE IF NOT EXISTS `smtp_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` varchar(100) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_password` varchar(100) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `social_login`;
CREATE TABLE IF NOT EXISTS `social_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) DEFAULT NULL,
  `api_id` varchar(250) DEFAULT NULL,
  `api_secret` varchar(250) DEFAULT NULL,
  `user_access_token` text NOT NULL,
  `google_client_id` text,
  `google_client_secret` varchar(250) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `add_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL,
  `delete_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transaction_history`;
CREATE TABLE IF NOT EXISTS `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `verify_status` varchar(200) NOT NULL,
  `first_name` varchar(250) NOT NULL,
  `last_name` varchar(250) NOT NULL,
  `paypal_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `payment_date` varchar(250) NOT NULL,
  `payment_type` varchar(100) NOT NULL,
  `transaction_id` varchar(150) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cycle_start_date` date NOT NULL,
  `cycle_expired_date` date NOT NULL,
  `package_id` int(11) NOT NULL,
  `stripe_card_source` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(99) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `email` varchar(99) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(99) NOT NULL,
  `user_logo` text NOT NULL,
  `address` text NOT NULL,
  `user_type` enum('Member','Admin') NOT NULL,
  `status` enum('1','0') NOT NULL,
  `activation_code` varchar(20) NOT NULL,
  `my_note` text NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_at` datetime NOT NULL,
  `expired_date` datetime NOT NULL,
  `package_id` int(11) NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  `last_login_ip` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `name`, `gender`, `email`, `phone`, `password`, `user_logo`, `address`, `user_type`, `status`, `activation_code`, `my_note`, `add_date`, `last_login_at`, `expired_date`, `package_id`, `deleted`, `last_login_ip`) VALUES
(1, 'Admin', 'male', 'admin@gmail.com', '01670984145', 'e10adc3949ba59abbe56e057f20f883e', '1.png', 'Natore, Rajshahi, Bangladesh', 'Admin', '1', '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries', '2015-12-31 06:00:00', '2018-10-05 13:57:02', '0000-00-00 00:00:00', 0, '0', '::1');
COMMIT;
