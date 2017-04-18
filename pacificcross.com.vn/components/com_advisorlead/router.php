<?php

/* ------------------------------------------------------------------------
  # router.php - Advisor Lead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

function AdvisorleadBuildRoute(&$query) {
    $segments = array();

    if (isset($query['view'])) {
        $segments[] = $query['view'];
        unset($query['view']);
    };

    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    };

    return $segments;
}

function AdvisorleadParseRoute($segments) {
    $vars = array();
    // Count segments
    $count = count($segments);
    //Handle View and Identifier
    $app = JFactory::getApplication();
    $menu = $app->getMenu();

    switch ($segments[0]) {

        case "dashboard":
            $vars['view'] = 'advisorlead';
            if ($count > 1) {
                switch ($segments[1]) {
                    case 'images':
                        $vars['layout'] = 'images';
                        break;
                    case "integrations":
                        $vars['layout'] = 'integrations';
                        $list_integrations = array(
                            'aweber' => 'Aweber',
                            'mailchimp' => 'Mailchimp',
                            'infusionsoft' => 'Infusionsoft',
                            'icontact' => 'iContact',
                            'getresponse' => 'GetResponse',
                            'constantcontact' => 'ConstantContact',
                            'gotowebinar' => 'GoToWebinar'
                        );
                        if ($count > 2 && array_key_exists($segments[2], $list_integrations)) {
                            $vars['integration'] = $segments[2];
                            $vars['integration_title'] = $list_integrations[$segments[2]];
                            if ($count == 4) {
                                $vars['service_action'] = $segments[3];
                            }
                        }
                        break;
                }
            }
            break;
        case "templates":
            $vars['view'] = 'templates';
            break;
        case "ctas":
            $vars['view'] = 'cta';
            if (($count == 2 && is_numeric($segments[1])) || $count == 3 && is_numeric($segments[1]) && is_numeric($segments[2])) {
                $vars['layout'] = 'editor';
                $vars['view'] = 'templates';
                $vars['template_id'] = $segments[1];
                $vars['template_type'] = 'cta';
                $vars['cta_id'] = !empty($segments[2]) ? $segments[2] : 0;
                $vars['plain_template'] = 1;
            } else if ($count == 3 && is_numeric($segments[1])) {
                $vars['plain_template'] = 1;
                switch ($segments[2]) {
                    case "html":
                        $vars['layout'] = 'template_html';
                        $vars['template_id'] = $segments[1];
                        $vars['type'] = 'html';
                        $vars['is_editing'] = !empty($_GET['no_edit']) ? false : true;
                        break;
                    case "publish":
                        $vars['layout'] = 'publish';
                        $vars['cta_id'] = $segments[1];
                        $vars['type'] = 'publish';
                        break;
                    case "delete":
                        $vars['cta_id'] = $segments[1];
                        $vars['type'] = 'delete';
                        break;
                    case "load":
                        $vars['cta_id'] = $segments[1];
                        $vars['type'] = 'load-cta-scripts';
                        break;
                    case "close":
                        $vars['cta_id'] = $segments[1];
                        $vars['type'] = 'close-forever';
                        break;
                    case "optin":
                        $vars['cta_id'] = $segments[1];
                        $vars['type'] = 'optin';
                        break;
                }
            }
            break;
        case "pages":
            $vars['view'] = 'pages';
            if (($count == 2 && is_numeric($segments[1])) || $count == 3 && is_numeric($segments[1]) && is_numeric($segments[2])) {
                $vars['layout'] = 'editor';
                $vars['view'] = 'templates';
                $vars['template_id'] = $segments[1];
                $vars['template_type'] = 'page';
                $vars['page_id'] = !empty($segments[2]) ? $segments[2] : 0;
                $vars['plain_template'] = 1;
            } else if ($count == 3 && is_numeric($segments[1])) {
                $vars['plain_template'] = 1;
                switch ($segments[2]) {
                    case "html":
                        $vars['layout'] = 'template_html';
                        $vars['template_id'] = $segments[1];
                        $vars['type'] = 'html';
                        $vars['is_editing'] = !empty($_GET['no_edit']) ? false : true;
                        break;
                    case "publish":
                        $vars['layout'] = 'publish';
                        $vars['page_id'] = $segments[1];
                        $vars['type'] = 'publish';
                        break;
                    case "delete":
                        $vars['page_id'] = $segments[1];
                        $vars['type'] = 'delete';
                        break;
                    case "optin":
                        $vars['page_id'] = $segments[1];
                        $vars['type'] = 'optin';
                        break;
                    case "payment":
                        $vars['page_id'] = $segments[1];
                        $vars['type'] = 'payment';
                        break;
                }
            }
            break;
        case "analytics":
            $vars['view'] = 'analytics';
            break;
    }

    return $vars;
}

?>