<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class AdvisorleadModelTemplates extends JModelList {

    function get_categories() {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . CATEGORIES_TABLE . " ORDER BY id ASC";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_templates($category) {
        $db = JFactory::getDbo();
        //$query = "SELECT * FROM " . TEMPLATES_TABLE . " t LEFT JOIN " . TEMPLATE_CATEGORY_TABLE . " c ON c.template_id = t.id";
		$query = "SELECT * FROM " . TEMPLATES_TABLE . " t INNER JOIN " . TEMPLATE_CATEGORY_TABLE . " c ON c.template_id = t.id";
        if (!empty($category)) {
            $query .= " WHERE c.category_id = $category";
        }
        $query .= ' GROUP BY t.id ORDER BY c.sort ASC';
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_template($template_id) {
        $db = JFactory::getDbo();
        $query = "SELECT * FROM " . TEMPLATES_TABLE . " WHERE id = $template_id LIMIT 1";
        $db->setQuery($query);
        return $db->loadObject();
    }

    function upload_image($file) {

        global $user;
        $result = array('id' => 0, 'url' => '');
        if (!empty($file['name']) && $file['error'] == 0) {
            $db = JFactory::getDbo();
            $ext = substr($file['name'], strrpos($file['name'], '.') + 1);
            $name = $user->id . '_' . time() . '.' . $ext;
            $file_path = UPLOAD_PATH . $name;
            $file_url = UPLOAD_URL . $name;
            if (JFile::upload($file['tmp_name'], $file_path)) {

                $insert_arr = array(
                    'uid' => $user->id,
                    'name' => substr($file['name'], 0, 255),
                    'type' => $file['type'],
                    'size' => $file['size'],
                    'url' => $file_url,
                    'created' => time()
                );

                $inserts = (object) $insert_arr;
                $inserted = $db->insertObject(USER_IMAGES_TABLE_UNQUOTE, $inserts);
                if ($inserted) {
                    $result['id'] = $db->insertid();
                    $result['url'] = $file_url;
                }
            }
        }
        return $result;
    }

}

?>