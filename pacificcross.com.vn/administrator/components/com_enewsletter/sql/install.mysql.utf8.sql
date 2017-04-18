--
-- Table structure for table `#__advisorsettings`
--
DROP TABLE IF EXISTS `#__advisorsettings`;

CREATE TABLE IF NOT EXISTS `#__advisorsettings` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `alternative_domain` varchar(255) DEFAULT NULL,
  `newsletter_api` char(1) DEFAULT NULL,
  `api_login_name` varchar(255) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `firm` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `path_quote` varchar(255) DEFAULT NULL,
  `custom_link_article` varchar(255) DEFAULT NULL,
  `allow_to_use_name` char(1) DEFAULT NULL,
  `customer_website` char(1) DEFAULT NULL,
  `subscribers_limit` varchar(255) DEFAULT NULL,
  `send_newsletter` char(1) DEFAULT NULL,
  `send_update` char(1) DEFAULT NULL,
  `show_address` char(1) DEFAULT NULL,
  `update_subject` varchar(255) DEFAULT NULL,
  `auto_update` char(1) DEFAULT NULL,
  `newsletter_disclosure` text,
  `mass_email_disclosure` text,
  `weekly_update_newsletter` text,
  `weekly_update_intro` text,
  `join_list_instruction` text,
  `join_list_email` text,
  `privacy_policy` text,
  `archive_cc_list` varchar(255) DEFAULT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `newsletter_default_template` INT(10) DEFAULT NULL,
  `weeklyupdate_default_template` INT(10) DEFAULT NULL,
  `massemail_default_template` INT(10) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `social_links` tinytext,
  `bannerimg` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `#__enewsletter`
--
DROP TABLE IF EXISTS `#__enewsletter`;

CREATE TABLE IF NOT EXISTS `#__enewsletter` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `edited_by` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `intro` text,
  `trailer` text,
  `mass_email_content` text,
  `type` varchar(255) DEFAULT NULL,
  `api_type` char(1) DEFAULT NULL,
  `email_sent_status` char(1) DEFAULT '0',
  `email_id` varchar(255) DEFAULT NULL,
  `is_active` char(1) DEFAULT NULL,
  `approval_status` enum('APR','PND','REJ') DEFAULT NULL,
  `approval_email_id` int(11) DEFAULT NULL,
  `content` text,
  `weekly_update_content` text,
  `dte_created` datetime DEFAULT NULL,
  `dte_modified` datetime DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__enewsletter_article`
--
DROP TABLE IF EXISTS `#__enewsletter_article`;

CREATE TABLE IF NOT EXISTS `#__enewsletter_article` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `e_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `show_image` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__enewsletter_groups`
--

DROP TABLE IF EXISTS `#__enewsletter_groups`;

CREATE TABLE IF NOT EXISTS `#__enewsletter_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `e_id` int(11) DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
)  ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__enewsletter_history`
--

DROP TABLE IF EXISTS `#__enewsletter_history`;

CREATE TABLE IF NOT EXISTS `#__enewsletter_history` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `campaign_title` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text,
  `e_id` int(11) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `api_type` char(1) DEFAULT NULL,
  `dte_send` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__weeklyupdate_group`;

CREATE TABLE IF NOT EXISTS `#__weeklyupdate_group` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `api_type` char(1) DEFAULT NULL,
  `group_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__email_templates`
--
DROP TABLE IF EXISTS `#__email_templates`;

CREATE TABLE `#__email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(80) DEFAULT NULL,
  `filename` varchar(80) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

INSERT INTO `#__email_templates` (`id`, `description`, `filename`, `type`, `status`) 
	VALUES(1, 'Newsletter Template', 'enewsletter.html', 'newsletter', 'published'),
		  (2, 'Weekly Update Template', 'weeklyupdate.html', 'weeklyupdate', 'published'),
		  (3, 'Mass Email Template', 'massemail.html', 'massemail', 'published');
		  
INSERT INTO `#__advisorsettings` (`newsletter_default_template`, `weeklyupdate_default_template`, `massemail_default_template`) 
	VALUES( '1', '2', '3');


--
-- Table structure for table `#__advisorsettings`
--
DROP TABLE IF EXISTS `#__advisorsettings_log`;

CREATE TABLE IF NOT EXISTS `#__advisorsettings_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `alternative_domain` varchar(255) DEFAULT NULL,
  `newsletter_api` char(1) DEFAULT NULL,
  `api_login_name` varchar(255) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `firm` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `path_quote` varchar(255) DEFAULT NULL,
  `custom_link_article` varchar(255) DEFAULT NULL,
  `allow_to_use_name` char(1) DEFAULT NULL,
  `customer_website` char(1) DEFAULT NULL,
  `subscribers_limit` varchar(255) DEFAULT NULL,
  `send_newsletter` char(1) DEFAULT NULL,
  `send_update` char(1) DEFAULT NULL,
  `show_address` char(1) DEFAULT NULL,
  `update_subject` varchar(255) DEFAULT NULL,
  `auto_update` char(1) DEFAULT NULL,
  `newsletter_disclosure` text,
  `mass_email_disclosure` text,
  `weekly_update_newsletter` text,
  `weekly_update_intro` text,
  `join_list_instruction` text,
  `join_list_email` text,
  `privacy_policy` text,
  `archive_cc_list` varchar(255) DEFAULT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `newsletter_default_template` INT(10) DEFAULT NULL,
  `weeklyupdate_default_template` INT(10) DEFAULT NULL,
  `massemail_default_template` INT(10) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `social_links` tinytext,
  `bannerimg` varchar(50) DEFAULT NULL,
  `edit_id` int(10) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `client_ip` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
