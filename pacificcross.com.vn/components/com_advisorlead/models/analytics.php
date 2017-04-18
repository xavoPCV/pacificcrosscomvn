<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class AdvisorleadModelAnalytics extends JModelList {

    function get_analytic_stats($tracking_type, $where, $from = '', $until = '') {
        $db = JFactory::getDbo();

        $table = $tracking_type == 'page' ? PAGES_TABLE : CTA_TABLE;

        if (!empty($from) && !empty($until)) {
            $total_query = "SELECT 
                            SUM((SELECT COUNT(*) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'optin' AND created >= $from AND created < $until)) as optins, 
                            SUM((SELECT COUNT(DISTINCT ip) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'view' AND created >= $from AND created < $until)) as uniques
                            FROM $table p $where";
        } else {
            $total_query = "SELECT 
                            SUM((SELECT COUNT(*) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'optin')) as optins, 
                            SUM((SELECT COUNT(DISTINCT ip) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'view')) as uniques
                            FROM $table p $where";
        }
//        echo $total_query;
        $db->setQuery($total_query);
        $total_results = $db->loadObject();
        $total_results->tracking_type = $tracking_type;
        return $total_results;
    }

    function get_analytic_page_stats($tracking_type, $where, $order, $from = '', $until = '') {
        $db = JFactory::getDbo();

        $table = $tracking_type == 'page' ? PAGES_TABLE : CTA_TABLE;
        if (!empty($from) && !empty($until)) {
            $query = "SELECT p.id, p.name,
                      (SELECT COUNT(*) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'optin' AND created >= {$from} AND created < {$until} LIMIT 1) as optins, 
                      (SELECT COUNT(DISTINCT ip) FROM " . TRACKING_TABLE . " WHERE object_id = p.id AND type LIKE 'view' AND created >= {$from} AND created < {$until} LIMIT 1) as uniques
                      FROM $table p $where $order";
        } else {
            $query = "SELECT id, name, uniques, optins FROM $table $where $order";
        }
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    function get_analytic_total($tracking_type, $uid) {
        $db = JFactory::getDbo();
        $table = $tracking_type == 'page' ? PAGES_TABLE : CTA_TABLE;
        $query = "SELECT COUNT(*) FROM $table WHERE uid = $uid LIMIT 1";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function get_analytic_optins($object_id, $type, $tracking_type = 'page', $from = '', $to = '') {
        $db = JFactory::getDbo();
        $where = '';
        if (!empty($from) && !empty($to)) {
            $where = "AND created >= $from AND created < $to";
        }
        switch ($type) {
            case "optins":
                $query = "SELECT COUNT(id) FROM " . TRACKING_TABLE . " WHERE object_id = $object_id AND type LIKE 'optin' AND tracking_type LIKE '$tracking_type' $where";
                break;
            case "uniques":
                $query = "SELECT COUNT(DISTINCT ip) FROM " . TRACKING_TABLE . " WHERE object_id = $object_id AND type LIKE 'view' AND tracking_type LIKE '$tracking_type' $where";
                break;
            case "views":
                $query = "SELECT COUNT(id) FROM " . TRACKING_TABLE . " WHERE object_id = $object_id AND type LIKE 'view' AND tracking_type LIKE '$tracking_type' $where";
                break;
        }
        $db->setQuery($query);
        $total = $db->loadResult();
        return !empty($total) ? $total : 0;
    }

    function get_view_ip($object_id) {
        $db = JFactory::getDbo();
        $query = "SELECT DISTINCT(ip) FROM " . TRACKING_TABLE . " WHERE type LIKE 'view' AND object_id = $object_id ORDER BY id DESC LIMIT 20";
        $db->setQuery($query);
        $total = $db->loadColumn();
        return $total;
    }

    function get_last_view_time($object_id) {
        $db = JFactory::getDbo();
        $query = "SELECT MAX(created) FROM " . TRACKING_TABLE . " WHERE type LIKE 'view' AND object_id = $object_id LIMIT 1";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function save_tracking_data($object_id, $type, $email, $ip, $tracking_type) {
        $db = JFactory::getDbo();
        $values = array(
            'object_id' => $object_id,
            'type' => $type,
            'tracking_type' => $tracking_type,
            'email' => $email,
            'ip' => $ip,
            'created' => time()
        );
        $inserts = (object) $values;

        $db->insertObject(TRACKING_TABLE_UNQUOTE, $inserts);
    }

    function save_object_tracking_data($object_table, $object_id, $views, $uniques, $optins, $rates) {
        $db = JFactory::getDbo();
        $set = array(
            'id' => $object_id,
            'views' => $views,
            'uniques' => $uniques,
            'optins' => $optins,
            'rates' => $rates,
        );
        $updates = (object) $set;
        $db->updateObject($object_table, $updates, 'id');
    }

}

?>