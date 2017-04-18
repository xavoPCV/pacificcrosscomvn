<?php

/* ------------------------------------------------------------------------
  # advisorlead.php - Advisor Lead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Advisorlead component helper
 */
abstract class AdvisorleadHelper {

    public static function render($file, $vars = null) {
        if (is_array($vars) && !empty($vars)) {
            extract($vars);
        }
        ob_start();
        include $file;
        return ob_get_clean();
    }

    public static function render_replace($path, $search = array(), $replace = array()) {
        $file = self::render($path);
        $results = str_replace($search, $replace, $file);
        return $results;
    }

    public static function get_option($name) {
        $db = JFactory::getDbo();
        $query = "SELECT value FROM " . OPTIONS_TABLE . "WHERE name LIKE '$name' LIMIT 1";
        $db->setQuery($query);
        return $db->loadResult();
    }

    public static function get_user_meta($uid, $meta_name = '') {
        $db = JFactory::getDbo();
        $select = !empty($meta_name) ? $meta_name : '*';
        $query = "SELECT $select FROM " . USER_META_TABLE . "WHERE uid = $uid LIMIT 1";
        $db->setQuery($query);
        $results = !empty($meta_name) ? $db->loadResult() : $db->loadObject();
        return $results;
    }

    public static function update_user_meta($uid, $meta_name, $meta_value) {
        $db = JFactory::getDbo();

        $meta = self::get_user_meta($uid, $meta_name);
        if (!empty($meta)) {

            $update_arr = array(
                'uid' => $uid,
                $meta_name => is_array($meta_value) || is_object($meta_value) ? serialize($meta_value) : $meta_value
            );
            $updates = (object) $update_arr;
            $result = $db->updateObject(USER_META_TABLE_UNQUOTE, $updates, 'uid');
        } else {
            $insert_arr = array(
                'uid' => $uid,
                $meta_name => is_array($meta_value) || is_object($meta_value) ? serialize($meta_value) : $meta_value
            );

            $inserts = (object) $insert_arr;
            $result = $db->insertObject(USER_META_TABLE_UNQUOTE, $inserts);
        }

        return $result;
    }

    public static function get_integrations($uid) {

        $integrations = unserialize(self::get_user_meta($uid, 'integrations'));

        if (!$integrations || !is_array($integrations) || empty($integrations)) {
            $integrations = array(
                'aweber' => array('name' => 'Aweber', 'active' => 0, 'access_token' => '', 'access_token_secret' => '', 'copy_paste' => 0),
                'constantcontact' => array('name' => 'ConstantContact', 'active' => 0, 'access_token' => '', 'expires_in' => 0, 'token_type' => ''),
                'icontact' => array('name' => 'iContact', 'active' => 0, 'api_username' => '', 'api_password' => ''),
                'infusionsoft' => array('name' => 'Infusionsoft', 'active' => 0, 'name' => '', 'api_key' => '', 'copy_paste' => 0),
                'getresponse' => array('name' => 'GetResponse', 'active' => 0, 'api_key' => '', 'copy_paste' => 0),
                'gotowebinar' => array('name' => 'GoToWebinar', 'active' => 0, 'details' => array()),
                'mailchimp' => array('name' => 'Mailchimp', 'active' => 0, 'double_optin' => 'true', 'api_key' => ''),
            );
        }

        return $integrations;
    }

    public static function get_available_services($uid) {
        $integrations = self::get_integrations($uid);
        $available_services = array(
            'constantcontact' => $integrations['constantcontact']['active'] ? true : false,
            'icontact' => $integrations['icontact']['active'] ? true : false,
            'getresponse' => $integrations['getresponse']['active'] ? true : false,
            'mailchimp' => $integrations['mailchimp']['active'] ? true : false,
            'aweber' => $integrations['aweber']['active'] ? true : false,
            'infusionsoft' => $integrations['infusionsoft']['active'] ? true : false,
            'gotowebinar' => !empty($integrations['gotowebinar']['active']) ? true : false,
            'other' => true
        );
        return $available_services;
    }

    public static function clean_html($string) {
        $output = str_replace(array("\r\n", "\r"), "\n", $string);
        $lines = explode("\n", $output);
        $new_string = array();

        foreach ($lines as $i => $line) {
            if (!empty($line))
                $new_string[] = trim($line);
        }
        return implode(' ', $new_string);
    }

    public static function icontact_init_fields() {

        $text_input = array(
            array(
                'display_name' => 'Email',
                'field_name' => 'email',
                'required' => 1,
                'is_show' => 1
            ),
            array(
                'display_name' => 'First Name',
                'field_name' => 'first_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Last Name',
                'field_name' => 'last_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Prefix',
                'field_name' => 'prefix',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Suffix',
                'field_name' => 'suffix',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Address 1',
                'field_name' => 'address_1',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Address 2',
                'field_name' => 'address_2',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'City',
                'field_name' => 'city',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'State',
                'field_name' => 'state',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Postal Code',
                'field_name' => 'postal_code',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Phone',
                'field_name' => 'phone',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Fax',
                'field_name' => 'fax',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Business Phone',
                'field_name' => 'business',
                'required' => 0,
                'is_show' => 0
            ),
        );

        return $text_input;
    }

    public static function constantcontact_init_fields() {
        $text_input = array(
            array(
                'display_name' => 'Email',
                'field_name' => 'email',
                'required' => 1,
                'is_show' => 1
            ),
            array(
                'display_name' => 'First Name',
                'field_name' => 'first_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Last Name',
                'field_name' => 'last_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Middle name',
                'field_name' => 'middle_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Prefix Name',
                'field_name' => 'prefix_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Job Title',
                'field_name' => 'job_title',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Company Name',
                'field_name' => 'company_name',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Address Line 1',
                'field_name' => 'address_line_1',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Address Line 2',
                'field_name' => 'address_line_2',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Address Line 3',
                'field_name' => 'address_line_3',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'City',
                'field_name' => 'address_city',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'State',
                'field_name' => 'address_state',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Postal Code',
                'field_name' => 'address_postal_code',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Country Code',
                'field_name' => 'address_country_code',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Home Phone',
                'field_name' => 'home_phone',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Business Phone',
                'field_name' => 'business',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Cell Phone',
                'field_name' => 'cell_phone',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Work Phone',
                'field_name' => 'work_phone',
                'required' => 0,
                'is_show' => 0
            ),
            array(
                'display_name' => 'Fax',
                'field_name' => 'fax',
                'required' => 0,
                'is_show' => 0
            ),
        );

        return $text_input;
    }

    public static function get_IP() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

}

?>