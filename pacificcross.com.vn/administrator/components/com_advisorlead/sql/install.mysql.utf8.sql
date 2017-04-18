-- CATEGORIES TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT IGNORE INTO `#__advisorlead_categories` (`id`, `name`, `slug`) VALUES
(1, 'Sales', 'sales'),
(2, 'Opt-In', 'opt-in'),
(3, 'Webinar', 'webinar'),
(4, 'Thank You', 'thank-you'),
(6, 'CTA', 'cta'),
(8, 'Other', 'other');
-- END CATEGORIES TABLE

-- CTA TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_cta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `color_data` text NOT NULL,
  `style_data` text NOT NULL,
  `field_data` text,
  `display_data` text,
  `dynamic_data` text,
  `user_head_code` text,
  `user_end_code` text,
  `views` int(11) NOT NULL DEFAULT '0',
  `uniques` int(11) NOT NULL DEFAULT '0',
  `optins` int(11) NOT NULL DEFAULT '0',
  `rates` float NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `updated` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `template_id` (`template_id`),
  KEY `uniques` (`uniques`,`optins`,`rates`,`updated`,`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;
-- END CTA TABLE

-- OPTIONS TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT IGNORE INTO `#__advisorlead_options` (`id`, `name`, `value`) VALUES
(1, 'google_fonts', '[{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Open+Sans","name":"Open Sans"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Josefin+Slab","name":"Josefin Slab"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Arvo","name":"Arvo"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Lato","name":"Lato"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Vollkorn","name":"Vollkorn"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Abril+Fatface","name":"Abril Fatface"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Ubuntu","name":"Ubuntu"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=PT+Sans","name":"PT Sans"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Old+Standard+TT","name":"Old Standard TT"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Droid+Sans","name":"Droid Sans"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Proxima+Nova","name":"Proxima Nova"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Roboto","name":"Roboto"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Inconsolata","name":"Inconsolata"},{"url":"https:\\/\\/fonts.googleapis.com\\/css?family=Montserrat","name":"Montserrat"}]');
-- END OPTIONS TABLE

-- PAGES TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `data` longtext,
  `color_data` text,
  `js_data` text,
  `font_data` text,
  `exit_popup` tinyint(1) DEFAULT '0',
  `exit_popup_redirect` tinyint(1) DEFAULT '0',
  `exit_popup_message` text,
  `exit_popup_redirect_url` varchar(255) DEFAULT '',
  `parent_id` int(11) DEFAULT '0',
  `is_control` tinyint(1) DEFAULT '0',
  `is_test` tinyint(1) DEFAULT '0',
  `is_variation` tinyint(1) DEFAULT '0',
  `page_description` varchar(255) DEFAULT '',
  `page_keywords` varchar(255) DEFAULT '',
  `page_title` varchar(255) DEFAULT '',
  `page_redirect_url` varchar(255) DEFAULT '',
  `is_page_redirect` tinyint(1) NOT NULL DEFAULT '0',
  `is_page_mobile_redirect` tinyint(1) NOT NULL DEFAULT '0',
  `user_head_code` text,
  `user_end_code` text,
  `slot` smallint(6) DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `uniques` int(11) NOT NULL DEFAULT '0',
  `optins` int(11) NOT NULL DEFAULT '0',
  `rates` float NOT NULL DEFAULT '0',
  `fb_page` bigint(20) DEFAULT '0',
  `fb_app` bigint(20) DEFAULT '0',
  `custom_domain` varchar(50) DEFAULT '',
  `updated` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `template_id` (`template_id`),
  KEY `slot` (`slot`),
  KEY `name` (`name`),
  KEY `uniques` (`uniques`,`optins`,`rates`,`updated`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- END PAGES TABLE

-- TEMPLATES TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(225) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `js_variables` text NOT NULL,
  `optin_variables` text NOT NULL,
  `default_variables` text,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=171 ;

INSERT IGNORE INTO `#__advisorlead_templates` (`id`, `name`, `slug`, `js_variables`, `optin_variables`, `default_variables`) VALUES
(80, 'Centered Video Sales Page<br> (Background Image)', 'centered-video-sales-page', '', '', ''),
(81, 'Centered Video Opt-in Popup<br> (Background Image)', 'video-optin-popup', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(83, 'Right Sales Video <br> (Background Image)', 'video-right-sales-page', '', '', ''),
(84, 'Video Opt-in Right<br> (Background Image)', 'video-right-optin-popup', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(85, 'Centered Video Opt-in Popup <br>(Background Color)', 'video-right-optin-popup-bg', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(86, 'Centered Video Sales Page<br> (Background Color)', 'video-right-sales-page-bg', '', '', ''),
(87, 'Webinar 1.0', 'webinar-1', '{"twitterid":{"variable":"twitterid","dafault":"your_twitter_id","name":"Twitter ID for Sharing"},"twitterurl":{"variable":"twitterurl","dafault":"","name":"Twitter Share URL for Tweet Button (Leave blank to share current page)"},"twittertext":{"variable":"twittertext","dafault":"","name":"Twitter Share Message"},"facebookshareurl":{"variable":"facebookshareurl","dafault":"","name":"Facebook Share URL (Leave blank to share current page)"},"googleurl":{"variable":"googleurl","dafault":"","name":"Google Plus URL for Share Button (Leave blank to share current page)"}}', '', ''),
(88, 'Webinar 2.0', 'webinar-2', '{"twitterid":{"variable":"twitterid","dafault":"your_twitter_id","name":"Twitter ID for Sharing"},"twitterurl":{"variable":"twitterurl","dafault":"","name":"Twitter Share URL for Tweet Button (Leave blank to share current page)"},"twittertext":{"variable":"twittertext","dafault":"","name":"Twitter Share Message"},"facebookshareurl":{"variable":"facebookshareurl","dafault":"","name":"Facebook Share URL (Leave blank to share current page)"},"googleurl":{"variable":"googleurl","dafault":"","name":"Google Plus URL for Share Button (Leave blank to share current page)"}}', '', ''),
(89, 'Webinar 3.0', 'webinar-3', '{"twitterid":{"variable":"twitterid","dafault":"your_twitter_id","name":"Twitter ID for Sharing"},"twitterurl":{"variable":"twitterurl","dafault":"","name":"Twitter Share URL for Tweet Button (Leave blank to share current page)"},"twittertext":{"variable":"twittertext","dafault":"","name":"Twitter Share Message"},"facebookshareurl":{"variable":"facebookshareurl","dafault":"","name":"Facebook Share URL (Leave blank to share current page)"},"googleurl":{"variable":"googleurl","dafault":"","name":"Google Plus URL for Share Button (Leave blank to share current page)"}}', '', ''),
(90, 'Webinar 1.0 with Video', 'webinar-video-1', '', '', ''),
(91, 'Webinar 3.0 with Video', 'webinar-video-3', '', '', ''),
(92, 'Centered Form <br> (Background Image)', 'untapped-traffic', '', '', ''),
(93, 'Simple Landing Page with Opt-In Form (Background Image)', 'simple-landing-page', '', '', ''),
(94, 'Simple Landing Page with Opt-In Form (Background Color)', 'simple-landing-page-colorbg', '', '', ''),
(95, 'Simple Video Sales Page <br> (Background Color)', 'simple-landing-page-bgcolor', '', '', ''),
(96, 'Simple Video Sales Page <br> (Background Color & Header Image)', 'simple-landing-page-bgcolor-2', '', '', ''),
(97, 'Simple Video Landing Page with Opt-In Popup <br>(Background Image & Header Image) ', 'simple-landing-page-bg-img', '', '', ''),
(98, 'Simple Video Sales Page <br>(Background Image & Header Image)', 'simple-landing-page-bg-img-2', '', '', ''),
(100, 'Thank You Video', 'blog-2', '', '', ''),
(99, 'Simple Blog with Video 2<br>(Button Link)', 'Blog-with-Video-2', '', '', ''),
(101, 'Simple Landing Page - Fake Video <br> (Get users to opt-in before they can watch your video)', 'simple-landing-page-fake-video', '{"message":{"variable":"message","dafault":"Get this FREE Instant video! Just enter your email ASAP!","name":"Video Alert Popup Message"}}', '', ''),
(102, 'Video Squeeze Page Opt-in Right', 'video-with-right-opt-in', '', '', ''),
(103, 'Thank You with Purchase Option', 'book-thank-you', '{"twitterid":{"variable":"twitterid","dafault":"your_twitter_id","name":"Twitter ID for Sharing"},"twitterurl":{"variable":"twitterurl","dafault":"","name":"Twitter Share URL for Tweet Button (Leave blank to share current page)"},"twittertext":{"variable":"twittertext","dafault":"","name":"Twitter Share Message"},"facebookshareurl":{"variable":"facebookshareurl","dafault":"","name":"Facebook Share URL (Leave blank to share current page)"},"facebooklikebox":{"variable":"facebooklikebox","dafault":"https:\\/\\/www.facebook.com\\/VinceReedLive","name":"Facebook Like Box"},"googleurl":{"variable":"googleurl","dafault":"","name":"Google Plus URL for Share Button (Leave blank to share current page)"}}', '', ''),
(104, 'Simple Blog', 'simple-blog', '', '', ''),
(105, 'Simple Blog with Video<br> (Opt-in Form)', 'simple-blog-with-video', '', '', ''),
(106, 'Go To Meeting', 'go-to-meeting', '', '', ''),
(107, 'Simple Thank You Page', 'elegant-thank-you-page', '', '', ''),
(108, 'Blog With Video Posts <br> (Opt-in Form)', 'blog-with-video-posts', '', '', ''),
(109, 'Blog With Video Posts 2 <br> (Button Link)', 'blog-with-video-posts-2', '', '', ''),
(113, 'Blog With Video Posts 3 <br> (Opt-in Pop Up)', 'blog-with-video-posts-3', '', '', ''),
(39, '3 Steps Opt-in <br>(Background Color)', '3-steps-optin-bgcolor', '', '', ''),
(111, '3 Steps Opt-in<br> (Background Image)', '3-steps-optin-bgimg', '', '', ''),
(112, '3 Steps Opt-in with Video <br> (Background Color)', '3-steps-optin-video-bgcolor', '', '', ''),
(114, '3 Steps Opt-in with Video <br> (Background Image)', '3-steps-optin-video-bgimg', '', '', ''),
(125, '4 Part Video Sales Page Series <br> (Page 2)', 'video-sales-page-new-2', '{"video-source-1":{"variable":"video-multi-1","dafault":"","name":"Video Embed URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-2":{"variable":"video-multi-2","dafault":"","name":"Video Embed URL 2","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(126, '4 Part Video Sales Page Series <br> (Page 3)', 'video-sales-page-new-3', '{"video-source-1":{"variable":"video-multi-1","dafault":"","name":"Video Embed URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-2":{"variable":"video-multi-2","dafault":"","name":"Video Embed URL 2","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-3":{"variable":"video-multi-3","dafault":"","name":"Video Embed URL 3","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(121, 'Perfect Video Opt-in Page<br> (Background Image)', 'optin-bgimg-06062014', '', '', ''),
(122, 'Perfect Video Opt-in Page<br> (Background Color)', 'optin-bgcolor-06062014', '', '', ''),
(123, 'Sales Page With Countdown Timer', 'sales-bgcolor-06062014', '{"toDateTime":{"variable":"toDateTime","dafault":"","name":"The Timer Will Only Display In Preview Or Publish Mode<br/><br/>Countdown Timer","load_js": "datetimepicker"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(128, 'New Blog With Video & Opt-in', 'vince-blog-2', '{"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(119, 'Sales Page with Facebook Comments<br> (Background Color)', 'sales-page-bgcolor', '{"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(120, 'Sales Page with Facebook Comments <br> (Background Image)', 'sales-page-bgimg', '{"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(124, '4 Part Video Sales Page Series <br> (Page 1)', 'video-sales-page-new-1', '{"video-source-1":{"variable":"video-multi-1","dafault":"","name":"Video Embed URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(127, '4 Part Video Sales Page Series <br> (Page 4)', 'video-sales-page-new-4', '{"video-source-1":{"variable":"video-multi-1","dafault":"","name":"Video Embed URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-2":{"variable":"video-multi-2","dafault":"","name":"Video Embed URL 2","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-3":{"variable":"video-multi-3","dafault":"","name":"Video Embed URL 3","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-4":{"variable":"video-multi-4","dafault":"","name":"Video Embed URL 4","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', ''),
(129, '"The Deal" Sales Page', 'sales-bgcolor-06192014', '', '', ''),
(133, 'Webinar With Opt-in Popup', 'optin-bgcolor-07312014', '', '', ''),
(130, 'Mobile 1: Video & Opt-in Form <br> (For Mobile Phones Only)', 'mobile-1', '', '', ''),
(131, 'Mobile 2: Headline, Subheadline, Opt-in Form  <br> (For Mobile Phones Only)', 'mobile-2', '', '', ''),
(132, 'Mobile 3: Sales Page - Video & Buy Button  <br> (For Mobile Phones Only)', 'mobile-3', '', '', ''),
(134, '"The Must-Have Product" Sales Template', 'sales-bgcolor-08042014', '', '', ''),
(136, '"Free Gift" Opt-in Template', 'optin-bgcolor-08042014', '', '', ''),
(137, 'Blog With Video Posts 4 <br> (Link Opt-in Popups)', 'blog-with-video-posts-3-popups', '', '', ''),
(138, 'Simple Video Registration / Opt-in <br>(Popup Opt-in Form)', 'optin-bgcolor-08012014', '{"video-source":{"variable":"video-source","dafault":"","name":"Background Video URL","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid url to your video file. <br>- The video file must be in \\".mp4, .ogv, .webm\\" format.<\\/span>"},"image-source":{"variable":"image-source","dafault":"","name":"Background Image URL","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">This Image will be shown until the video load.<\\/span>"}}', '', ''),
(139, '"Locked" Video Template', 'optin-bgcolor-09102014', '{"toDateTime":{"variable":"toDateTime","dafault":"","name":"The Timer Will Only Display In Preview Or Publish Mode<br/><br/>Countdown Timer","load_js": "datetimepicker"}}', '', ''),
(140, '"Unlocked" Video Template', 'sales-bgcolor-09102014', '{"vs-1":{"variable":"video-1","dafault":"","name":"Video URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-1":{"variable":"vsthumb-1","dafault":"","name":"Video Thumbnail 1"},"vs-2":{"variable":"video-2","dafault":"","name":"Video URL 2","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-2":{"variable":"vsthumb-2","dafault":"","name":"Video Thumbnail 2"},"vs-3":{"variable":"video-3","dafault":"","name":"Video URL 3","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-3":{"variable":"vsthumb-3","dafault":"","name":"Video Thumbnail 3"},"vs-4":{"variable":"video-4","dafault":"","name":"Video URL 4","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-4":{"variable":"vsthumb-4","dafault":"","name":"Video Thumbnail 4"},"vs-5":{"variable":"video-5","dafault":"","name":"Video URL 5","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-5":{"variable":"vsthumb-5","dafault":"","name":"Video Thumbnail 5"},"vs-6":{"variable":"video-6","dafault":"","name":"Video URL 6","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">A valid youtube,vimeo,dailymotion,... URL. <br>Example: http:\\/\\/www.youtube.com\\/embed\\/XXXXXXX.<\\/span>"},"vsthumb-6":{"variable":"vsthumb-6","dafault":"","name":"Video Thumbnail 6"}}', '', ''),
(141, 'Perfect Video Opt-in Page<br> (Popup Opt-in Form)', 'perfect-optin-popup', '', '', ''),
(142, 'Video Checklist Opt-in Page <br/> (popup opt-in form)', 'video-checklist-optin', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(143, 'Ultimate Lead Generation Page', '3-lead-generation-secrets-09262014', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}.{"name":"recruiting", "source":"", "required":false, "value":"", "caption":"Recruiting", "role":"", "values":[], "type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(144, '3 Video Sales Page', '3video-sales', '{"facebookshareurl":{"variable":"facebookshareurl","dafault":"","name":"Facebook Share URL (Leave blank to share current page)"},"facebooklikebox":{"variable":"facebooklikebox","dafault":"https:\\/\\/www.facebook.com\\/VinceReedLive","name":"Facebook Like Box"},"facebookcomments":{"variable":"facebookcomments","dafault":"https:\\/\\/www.facebook.com\\/VinceReedLive","name":"Facebook Comments"},"facebookcommentsposts":{"variable":"facebookcomments","dafault":"8","name":"Facebook Comments Posts"},"facebooklikeurl":{"variable":"facebookcomments","dafault":"https:\\/\\/www.facebook.com\\/VinceReedLive","name":"Facebook Like URL"}}', '', ''),
(145, '4 Video Sales Page<br/> (background image)', '4-video-sales-page', '', '', ''),
(146, '4 Video Sales Page<br/> (background color)', '4-video-sales-page-bgcolor', '', '', ''),
(147, 'Simple Opt-in Page <br>(Popup Opt-In Form)', 'optin-bgimg-12052014', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(148, 'Simple Opt-in Page With Video <br> (Popup Opt-In Form)', 'optin-bgimage-12-15', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(149, 'Social Thank You Page', 'thank-you-12192014', '', '', ''),
(150, 'Social Page <br>(Popup Opt-In Form)', 'thankyou-12192014-popup', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(151, '"The Deal" Opt-in Page', 'optin-bgcolor-12192014', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(152, 'Video Sales Template<br/> (with time release button)', 'sales-timedrelease-1-9-15', '', '', ''),
(153, 'Webinar Registration', 'optin-bgcolor-01152015', '{"toDateTime":{"variable":"toDateTime","dafault":"","name":"The Timer Will Only Display In Preview Or Publish Mode<br/><br/>Countdown Timer","load_js": "datetimepicker"}}', '', ''),
(154, 'Optin With Fake Video Image', 'optin-picture-1-22-15', '', '', ''),
(155, 'Capture Clicks Template 1', 'capture-clicks-1', '', '', ''),
(156, 'Capture Clicks Template 9', 'capture-clicks-demo', '', '', ''),
(157, 'Capture Clicks Template 2', 'capture-clicks-2', '', '', ''),
(158, 'Capture Clicks Template 3', 'capture-clicks-3', '', '', ''),
(159, 'Capture Clicks Template 4', 'capture-clicks-4', '', '', ''),
(160, 'Capture Clicks Template 5', 'capture-clicks-5', '', '', ''),
(161, 'Capture Clicks Template 6', 'capture-clicks-6', '', '', ''),
(162, 'Capture Clicks Template 7', 'capture-clicks-7', '', '', ''),
(163, 'Capture Clicks Template 8', 'capture-clicks-8', '', '', ''),
(164, '4 Part Video Sales Page 2.0', 'sales-bgcolor-01212015', '{"video-source-1":{"variable":"video-multi-1","dafault":"","name":"Video Embed URL 1","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-2":{"variable":"video-multi-2","dafault":"","name":"Video Embed URL 2","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-3":{"variable":"video-multi-3","dafault":"","name":"Video Embed URL 3","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"video-source-4":{"variable":"video-multi-4","dafault":"","name":"Video Embed URL 4","desc":"<span style=\\"display:block;font-size:10px;line-height:12px\\">Ex: https:\\/\\/www.youtube.com\\/embed\\/XXXXX<\\/span>","validate":"checkUrl"},"facebookcomments":{"variable":"facebookcomments","dafault":"http:\\/\\/www.mitspages.com","name":"Facebook Comments URL"},"facebookcommentsposts":{"variable":"facebookcommentsposts","dafault":"5","name":"Number of Facebook Comments to Display"},"facebooklikeurl":{"variable":"facebooklikeurl","dafault":"","name":"Facebook Like Button URL (Leave blank to share current page)"}}', '', '');
INSERT IGNORE INTO `#__advisorlead_templates` (`id`, `name`, `slug`, `js_variables`, `optin_variables`, `default_variables`) VALUES
(165, 'Simple Opt-in Page Center <br>(Popup Opt-In Form)', 'optin-bgimg-02042015', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', ''),
(166, 'Sales Page With Video', 'sales-page-3-24', '', '', ''),
(167, 'Vince Yahoo/Bing Template', 'vince1', '', '{"aweber":{"warn":false,"fields":[{"name":"name","source":"itp-full_name","required":false,"value":"","caption":"Name","role":"full_name","values":[],"type":"text"},{"name":"email","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"mailchimp":{"warn":false,"fields":[{"name":"FNAME","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"LNAME","source":"itp-last_name","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"EMAIL","source":"itp-email","required":true,"value":"","caption":"Email","role":"email","values":[],"type":"email"}],"add_all":true,"html":true,"show_link":false},"constantcontact":{"warn":false,"fields":[{"name":"first-name","source":"itp-first_name","required":false,"value":"","caption":"First Name","role":"first_name","values":[],"type":"text"},{"name":"prefix-name","source":"","required":false,"value":"","caption":"Title","role":"","values":[],"type":"text"},{"name":"middle-name","source":"","required":false,"value":"","caption":"Middle Name","role":"","values":[],"type":"text"},{"name":"last-name","source":"itp-lastname","required":false,"value":"","caption":"Last Name","role":"last_name","values":[],"type":"text"},{"name":"email","source":"itp_email","required":false,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"},{"name":"cell_phone","source":"itp-cell_phone","required":false,"value":"","caption":"Cell Phone","role":"cell_phone","values":[],"type":"tel"},{"name":"work_phone","source":"itp-work_phone","required":false,"value":"","caption":"Work Phone","role":"work_phone","values":[],"type":"tel"},{"name":"home_phone","source":"","required":false,"value":"","caption":"Home Phone","role":"","values":[],"type":"tel"},{"name":"Fax","source":"","required":false,"value":"","caption":"Fax","role":"","values":[],"type":"tel"},{"name":"company_name","source":"","required":false,"value":"","caption":"Company Name","role":"","values":[],"type":"text"},{"name":"job_title","source":"","required":false,"value":"","caption":"Job Title","role":"","values":[],"type":"text"},{"name":"postal_code","source":"","required":false,"value":"","caption":"Postal Code","role":"","values":[],"type":"number"},{"name":"country_code","source":"","required":false,"value":"","caption":"Country Code","role":"","values":[],"type":"text"},{"name":"state_code","source":"","required":false,"value":"","caption":"State Code","role":"","values":[],"type":"text"},{"name":"city","source":"","required":false,"value":"","caption":"City","role":"","values":[],"type":"text"},{"name":"address_1","source":"","required":false,"value":"","caption":"Address Line 1","role":"","values":[],"type":"text"},{"name":"address_2","source":"","required":false,"value":"","caption":"Address Line 2","role":"","values":[],"type":"text"},{"name":"address_3","source":"","required":false,"value":"","caption":"Address Line 3","role":"","values":[],"type":"text"}],"add_all":true,"html":true,"show_link":false,"default":{"name":"LP_NONE","source":"itp-email","required":true,"value":"","caption":"E-mail","role":"email","values":[],"type":"email"}}}', '{"video_embed_code":"<iframe src=\\"\\/\\/fast.wistia.net\\/embed\\/iframe\\/mu5pq5edw4\\" allowtransparency=\\"true\\" frameborder=\\"0\\" scrolling=\\"no\\" class=\\"wistia_embed\\" name=\\"wistia_embed\\" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width=\\"581\\" height=\\"327\\"><\\/iframe><script src=\\"\\/\\/fast.wistia.net\\/assets\\/external\\/E-v1.js\\" async><\\/script>","thankyou_url":"http:\\/\\/www.myinternettrafficsystem.com\\/3-masterclass-videos\\/"}'),
(168, '"Thursday Night Webinar" Template', 'optin-06192015', '', '', ''),
(169, 'Simple Webinar Registration', 'optin-06182015', '', '', ''),
(170, 'Simple Optin Page', 'optin-07032015', '', '', NULL);
-- END TEMPLATES TABLE

-- TEMPLATE CATEGORY TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_template_category` (
  `template_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__advisorlead_template_category` (`template_id`, `category_id`, `sort`) VALUES
(80, 1, 1),
(81, 2, 24),
(82, 2, 0),
(83, 1, 2),
(84, 2, 28),
(85, 2, 23),
(86, 1, 2),
(87, 3, 14),
(88, 3, 15),
(89, 3, 16),
(90, 3, 17),
(91, 3, 18),
(92, 2, 3),
(93, 2, 4),
(94, 2, 27),
(95, 1, 5),
(96, 1, 4),
(97, 2, 26),
(98, 1, 3),
(100, 4, 21),
(99, 8, 84),
(101, 8, 25),
(102, 2, 25),
(103, 4, 22),
(104, 8, 82),
(105, 8, 82),
(106, 3, 19),
(107, 4, 23),
(139, 2, 31),
(133, 3, 0),
(136, 2, 30),
(125, 1, 86),
(126, 1, 89),
(127, 1, 90),
(111, 2, 6),
(108, 8, 85),
(109, 8, 86),
(112, 2, 7),
(114, 2, 8),
(39, 2, 5),
(115, 1, 0),
(116, 1, 0),
(117, 1, 0),
(118, 1, 0),
(119, 1, 0),
(120, 1, 0),
(121, 2, 1),
(122, 2, 2),
(123, 1, 0),
(124, 1, 87),
(124, 8, 100),
(125, 8, 101),
(126, 8, 102),
(127, 8, 103),
(128, 8, 80),
(129, 1, 0),
(130, 8, 91),
(131, 8, 92),
(132, 8, 93),
(113, 8, 87),
(134, 1, 91),
(137, 8, 90),
(138, 2, 0),
(140, 1, 105),
(141, 2, 2),
(142, 2, 32),
(143, 2, 33),
(144, 1, 106),
(145, 1, 107),
(146, 1, 108),
(147, 2, 34),
(148, 2, 35),
(149, 4, 24),
(150, 2, 36),
(152, 1, 104),
(152, 2, 34),
(153, 3, 0),
(155, 6, 0),
(156, 6, 9),
(157, 6, 0),
(158, 6, 0),
(159, 6, 0),
(160, 6, 0),
(161, 6, 0),
(162, 6, 0),
(163, 6, 0),
(154, 2, 37),
(164, 1, 109),
(165, 2, 34),
(166, 1, 0),
(167, 1, 1),
(168, 3, 1),
(169, 3, 2),
(170, 2, 2);
-- END TEMPLATE CATEGORY TABLE

-- TRACKING TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `type` varchar(25) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`object_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
-- END TRACKING TABLE

-- USER IMAGES TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_user_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `size` bigint(20) NOT NULL,
  `url` varchar(255) NOT NULL,
  `preload` tinyint(1) NOT NULL DEFAULT '0',
  `templates` text,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `preload` (`preload`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
-- END USER IMAGES TABLE

-- USER META TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_user_meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `integrations` longtext,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
-- END USER META TABLE

-- VIDEO TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `link` text NOT NULL,
  `sort` int(11) DEFAULT '0',
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

INSERT IGNORE INTO `#__advisorlead_video` (`id`, `title`, `link`, `sort`, `category`) VALUES
(1, 'Create a Page & Get Online in Just Minutes', 'https://www.youtube.com/watch?v=RERTl71Wt7U', 0, 1),
(2, 'Setting up the Timed Release Button feature on Advisor Pages', 'https://www.youtube.com/watch?v=NnblT-q-ToQ', 1, 1),
(3, 'Connect Your GoToWebinar Account With Your Advisor Pages', 'https://www.youtube.com/watch?v=VMy4QwXZeGw', 3, 2),
(4, 'Publish Your Advisor Pages To Facebook - Up To 3 Custom Tabs Available', 'https://www.youtube.com/watch?v=oJAc5Sz6AlE', 4, 3),
(5, 'How To Publish Your Advisor Pages To Your WordPress Website', 'https://www.youtube.com/watch?v=Q0UhdX168aI', 2, 1),
(6, 'How To Connect Your Aweber Account', 'https://www.youtube.com/watch?v=Tm-2sNSzoxU&feature=youtu.be', 5, 2),
(7, 'Creating a Advisor Pages using the ''Webinar 1.0 with Video'' template', 'https://www.youtube.com/watch?v=ba2uvkapVV8', 6, 1),
(8, 'How To Connect Your Email Services With Your Advisor Pages', 'https://www.youtube.com/watch?v=KaDWe7FwHWA', 7, 2),
(9, 'How to setup your custom url with Advisor Pages', 'https://www.youtube.com/watch?v=S0B3aE0hitM', 8, 1),
(10, 'How To Setup A Page Redirect For Your Advisor Pages', 'https://www.youtube.com/watch?v=HsrQPp3oMOA', 9, 1),
(11, 'New blog with video and optin', 'https://www.youtube.com/watch?v=ZUNh7kRU_D4', 10, 3),
(12, 'Working with ''The Deal'' Sales Page Template', 'https://www.youtube.com/watch?v=C_irS-ZgvHc', 11, 3),
(13, 'How To Create An AB Split Test', 'https://www.youtube.com/watch?v=9gNk1rjhYrA', 12, 1),
(14, 'First Setting Up Your Account', 'http://youtu.be/0dI5zbEEU0Y', 13, 1),
(15, 'How To Connect Your MailChimp Account', 'https://www.youtube.com/watch?v=eqgws2Pw9Tw', 14, 2),
(16, 'How To Connect Your Aweber Account', 'https://www.youtube.com/watch?v=Tm-2sNSzoxU&feature=youtu.be', 15, 2),
(17, 'How To Use The CTAs Optin Forms', 'https://www.youtube.com/watch?v=X2Q_0iu3Kpw', 15, 4);
-- END VIDEO TABLE

-- VIDEO CATEGORY TABLE
CREATE TABLE IF NOT EXISTS `#__advisorlead_video_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT IGNORE INTO `#__advisorlead_video_category` (`id`, `name`, `sort`) VALUES
(1, 'Getting Started', 0),
(2, 'Autoresponder Setup', 0),
(3, 'Template Features', 0),
(4, 'CTA', 0);
-- END VIDEO CATEGORY TABLE