DROP TABLE IF EXISTS `#__cta_setting`;

CREATE TABLE IF NOT EXISTS `#__cta_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `is_auto` tinyint(1) NOT NULL DEFAULT '0',
  `auto_num` int(11) NOT NULL DEFAULT '0',
  `selected_video` text,
  `slide_video` text,
  `nu_slide` tinyint(1) NOT NULL DEFAULT '1',
  `slide_code` text,
  `slide_code2` text,
  `used_twitter` tinyint(1) NOT NULL DEFAULT '0',
  `link_twitter` varchar(255) DEFAULT NULL,
  `used_linkedin` tinyint(1) NOT NULL DEFAULT '0',
  `link_linkedin` varchar(255) DEFAULT NULL,
  `used_facebook` tinyint(1) NOT NULL DEFAULT '0',
  `link_facebook` varchar(255) DEFAULT NULL,
  `mandatory_social` tinyint(1) NOT NULL DEFAULT '0',
  `social_icon_size` tinyint(1) NOT NULL DEFAULT '1',
  `mandatory_enewsletter` tinyint(1) NOT NULL DEFAULT '0',
  `used_enewsletter` tinyint(1) NOT NULL DEFAULT '0',
  `constantcontact` tinyint(1) NOT NULL DEFAULT '0',
  `constant_contact_email` varchar(100) DEFAULT NULL,
  `constant_contact_key` varchar(100) DEFAULT NULL,
  `constant_contact_secret` varchar(100) DEFAULT NULL,
  `constant_contact_token` varchar(100) DEFAULT NULL,
  `mailchimp` tinyint(1) NOT NULL DEFAULT '0',
  `mailchimp_email` varchar(100) DEFAULT NULL,
  `mailchimp_api_key` varchar(100) DEFAULT NULL,
  `watermark_logo` varchar(150) DEFAULT NULL,
  `opacity` tinyint(4) NOT NULL DEFAULT '100',
  `enewsletter_logo` varchar(50) DEFAULT NULL,
  `social_text` mediumtext,
  `social_text_position` enum('left','right') NOT NULL DEFAULT 'left',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `#__cta_setting` (`id`, `is_auto`, `auto_num`, `selected_video`, `slide_video`, `nu_slide`, `slide_code`, `slide_code2`, `used_twitter`, `link_twitter`, `used_linkedin`, `link_linkedin`, `used_facebook`, `link_facebook`, `mandatory_social`, `social_icon_size`, `mandatory_enewsletter`, `used_enewsletter`, `constantcontact`, `constant_contact_email`, `constant_contact_key`, `constant_contact_secret`, `constant_contact_token`, `mailchimp`, `mailchimp_email`, `mailchimp_api_key`, `watermark_logo`, `opacity`,`enewsletter_logo`,`social_text`) VALUES
(1, 1, 15, NULL, '', 1, '', '', 0, '', 0, '', 0, '', 0, 32, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 50, NULL, 'Thank you for your interest in our firm. Please provide your contact information below to view our special reports:');

DROP TABLE IF EXISTS `#__cta_register`;

CREATE TABLE IF NOT EXISTS `#__cta_register` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `video_selected` text,
  `cusitems_selected` text,
  `social_connect` text,
  `used_enewsletter` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `session_id` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


DROP TABLE IF EXISTS `#__cta_cusitems`;

CREATE TABLE IF NOT EXISTS `#__cta_cusitems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `file_name` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `published` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;