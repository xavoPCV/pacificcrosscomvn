<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

use Ctct\ConstantContact;
use Ctct\Auth\CtctOAuth2;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\OAuth2Exception;

class AdvisorleadModelDashboard extends JModelList {

    function get_videos() {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . VIDEO_TABLE . " ORDER BY sort ASC, id ASC";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_video_categories() {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . VIDEO_CATEGORY_TABLE;
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_user_optins($uid, $start = '', $end = '') {
        $db = JFactory::getDbo();
        $where = '';
        if (!empty($start) && !empty($end)) {
            $where = " AND created >= $start AND created <= $end";
        }

        $page_query = "SELECT SUM((SELECT COUNT(id) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'optin' $where))
                       FROM " . PAGES_TABLE . " p WHERE uid = $uid";
        $db->setQuery($page_query);
        $page_result = $db->loadResult();

        $cta_query = "SELECT SUM((SELECT COUNT(id) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'optin' $where))
                      FROM " . CTA_TABLE . " p WHERE uid = $uid";
        $db->setQuery($cta_query);
        $cta_result = $db->loadResult();
        return $page_result + $cta_result;
    }

    function get_user_images($uid) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . USER_IMAGES_TABLE . " WHERE uid = $uid";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function integration_action($service, $service_title, $action, $param) {
        global $user;
        $app = JFactory::getApplication();
        $integrations = AdvisorleadHelper::get_integrations($user->id);
        $redirect_url = ADVISORLEAD_URL . "/dashboard/integrations/$service/";
        $connected_message = "$service_title account has been connected successfully.";
        $disconnected_message = "$service_title account has been disconnected from AdvisorLead.";
        if ($action == 'disconnect') {
            $_SESSION['message'] = array("status" => "success", "msg" => $disconnected_message);
        } else {
            $_SESSION['message'] = array("status" => "success", "msg" => $connected_message);
        }

        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);

        switch ($service) {
            case 'aweber':

                $integrations['aweber'] = array(
                    'name' => 'Aweber',
                    'active' => 0,
                    'copy_paste' => 0,
                    'access_token' => '',
                    'access_token_secret' => ''
                );

                if ($action == 'connect') {

                    $consumerKey = $email_services->aweber_key;
                    $consumerSecret = $email_services->aweber_secret;
                    $aweber = new AWeberAPI($consumerKey, $consumerSecret);
                    $integrations['aweber']['active'] = 1;
                    $integrations['aweber']['access_token'] = $_SESSION['accessToken'];
                    $integrations['aweber']['access_token_secret'] = $_SESSION['accessTokenSecret'];

                    if (empty($_GET['oauth_token'])) {
                        $callbackUrl = JURI::current();
                        $token = $aweber->getRequestToken($callbackUrl);
                        $_SESSION['requestTokenSecret'] = $token[1];
                        $_SESSION['callbackUrl'] = $callbackUrl;
                        $app = JFactory::getApplication();
                        $app->redirect($aweber->getAuthorizeUrl());
                    }
                    $aweber->user->tokenSecret = $_SESSION['requestTokenSecret'];
                    $aweber->user->requestToken = $_GET['oauth_token'];
                    $aweber->user->verifier = $_GET['oauth_verifier'];
                    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();
                    $_SESSION['accessToken'] = $accessToken;
                    $_SESSION['accessTokenSecret'] = $accessTokenSecret;
                }
                break;

            case 'mailchimp':
                $integrations['mailchimp'] = array(
                    'name' => 'Mailchimp',
                    'active' => 0,
                    'double_optin' => 'false',
                    'api_key' => ''
                );

                if ($action == 'connect' && !empty($param['mailchimp_token'])) {
                    $mailchimp = new MailChimp($param['mailchimp_token']);
                    $method = 'lists/list';
                    $args = array();
                    $result = $mailchimp->call($method, $args);
                    if (empty($result) || $result['status'] == 'error') {
                        $_SESSION['message'] = array("status" => "error", "msg" => "Invalid Api key.");
                    } else {
                        $integrations['mailchimp']['active'] = 1;
                        $integrations['mailchimp']['api_key'] = $param['mailchimp_token'];
                    }
                }

                break;
            case 'icontact':

                break;
            case 'getresponse':

                break;
            case 'constantcontact':
                $integrations['constantcontact'] = array(
                    'name' => 'ConstantContact',
                    'active' => 0,
                    'access_token' => '',
                    'token_type' => '',
                    'expires_in' => ''
                );
                if ($action == 'connect') {
                    $api_key = $email_services->cc_app_key;
                    $consumer_secret = $email_services->cc_app_secret;

                    $oauth = new CtctOAuth2($api_key, $consumer_secret, $redirect_url . 'connect');

                    if (isset($param['error'])) {
                        $_SESSION['message'] = array("status" => "error", "msg" => $param['error']);
                    } else if (isset($param['code'])) {
                        $accessToken = array();
                        $error_message = '';
                        try {
                            $accessToken = $oauth->getAccessToken($param['code']);
                        } catch (OAuth2Exception $ex) {
                            $error_message = $ex->getMessage();
                        }

                        if (isset($accessToken['access_token']) && !empty($accessToken['access_token'])) {
                            $integrations['constantcontact']['active'] = 1;
                            $integrations['constantcontact']['access_token'] = $accessToken['access_token'];
                            $integrations['constantcontact']['expires_in'] = $accessToken['expires_in'];
                            $integrations['constantcontact']['token_type'] = $accessToken['token_type'];
                        } else {
                            $_SESSION['message'] = array("status" => "error", "text" => $error_message);
                        }
                    } else {
                        $app->redirect($oauth->getAuthorizationUrl());
                    }
                }
                break;
            case 'gotowebinar':

                break;
            case 'infusionsoft':

                break;
        }

        AdvisorleadHelper::update_user_meta($user->id, 'integrations', $integrations);
        $app->redirect($redirect_url);
    }

}

?>