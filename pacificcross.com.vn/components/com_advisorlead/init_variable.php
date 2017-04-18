<?php

$db = JFactory::getDbo();
define('CATEGORIES_TABLE', $db->quoteName('#__advisorlead_categories'));
define("TEMPLATES_TABLE", $db->quoteName('#__advisorlead_templates'));
define('TEMPLATE_CATEGORY_TABLE', $db->quoteName('#__advisorlead_template_category'));
define('PAGES_TABLE', $db->quoteName('#__advisorlead_pages'));
define('PAGES_TABLE_UNQUOTE', '#__advisorlead_pages');
define('CTA_TABLE', $db->quoteName('#__advisorlead_cta'));
define('CTA_TABLE_UNQUOTE', '#__advisorlead_cta');
define('TRACKING_TABLE', $db->quoteName('#__advisorlead_tracking'));
define('TRACKING_TABLE_UNQUOTE', '#__advisorlead_tracking');
define('USER_IMAGES_TABLE', $db->quoteName('#__advisorlead_user_images'));
define('USER_IMAGES_TABLE_UNQUOTE', '#__advisorlead_user_images');
define('USER_META_TABLE', $db->quoteName('#__advisorlead_user_meta'));
define('USER_META_TABLE_UNQUOTE', '#__advisorlead_user_meta');
define('OPTIONS_TABLE', $db->quoteName('#__advisorlead_options'));
define('VIDEO_TABLE', $db->quoteName('#__advisorlead_video'));
define('VIDEO_CATEGORY_TABLE', $db->quoteName('#__advisorlead_video_category'));

define('ADVISORLEAD_URL', JURI::base() . 'advisorlead');
define('ADVISORLEAD_INC_PATH', JPATH_BASE . '/components/com_advisorlead/assets/inc');
define('HTML_TEMPLATES_PATH', JPATH_BASE . '/components/com_advisorlead/html_templates/');
define('ASSETS_PATH', JPATH_BASE . '/components/com_advisorlead/assets');
define('ASSETS_URL', JURI::base() . 'components/com_advisorlead/assets');
define('UPLOAD_PATH', JPATH_SITE . '/images/advisorlead/');
define('UPLOAD_URL', JURI::base() . 'images/advisorlead/');

require_once ADVISORLEAD_INC_PATH . '/services/mailchimp/mailchimp.php';
require_once ADVISORLEAD_INC_PATH . '/services/Ctct/autoload.php';
require_once ADVISORLEAD_INC_PATH . '/services/aweber/aweber_api.php';
require_once ADVISORLEAD_INC_PATH . '/services/getresponse/GetResponseAPI.class.php';
require_once ADVISORLEAD_INC_PATH . '/services/icontact/iContactApi.php';
require_once ADVISORLEAD_INC_PATH . '/services/infusionsoft/isdk.php';
require_once ADVISORLEAD_INC_PATH . '/services/gotowebinar/gotowebinar.php';
require_once ADVISORLEAD_INC_PATH . '/services/stripe/init.php';


$com_params = JComponentHelper::getParams('com_advisorlead');

//var_dump($com_params);

define('APP_ID', $com_params->get('api_key','457986864397895'));
define('APP_SECRET', $com_params->get('api_secret','fd3d5d613dfe2e7699c9af2996f19da0'));

// Require helper file
JLoader::register('AdvisorleadHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'advisorlead.php');
