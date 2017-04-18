<?php
/**
 *
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class FlexImportModelType extends JModelAdmin {
    public function getTable($type = 'Types', $prefix = 'FlexImportTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_fleximport.type', 'type', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }
        return $form;

    }
	public function save($data = null){
		if (parent::save($data)) {
			// on ne va cr�er les champs automatiquement qu'au premier enregistrement
			if ($data['id'] !== 0) return true;
			$itemID = $this->getState($this->getName() . '.id');
			$item = $this->getItem($itemID);
			if ($item) {
				$fields = FleximportHelper::getFlexiFields($item->flexi_type_id);
				foreach ($fields as $field) {
					if (!in_array($field->field_type, $GLOBALS['fi_fields_nocopy'])) {
						$obj = new stdClass();
						$obj->type_id = $item->id;
						if (in_array($field->field_type,$GLOBALS['fi_fields_standard']) || $field->iscore) {
							$obj->field_type = $field->field_type;
						} else {
							$obj->field_type = "text";
						}
						$obj->flexi_field_id = $field->id;
						$obj->name = $field->label . '(' . $item->name . ')';
						$obj->label = $field->name;
						$obj->description = $field->description;
						if (in_array($field->field_type, $GLOBALS['fi_fields_required'])) {
							$obj->isrequired = 1;
						} else {
							$obj->isrequired = 0;
						}
						$obj->iscore = $field->iscore;
						$obj->ordering = $field->ordering;
						$obj->params = '{"usefor":"1"}';
						$obj->published = 1;
						$this->_db->insertObject('#__fleximport_fields', $obj);
					}
				}
				// ajout du champs attribut qui est obligatoire �galement
				$obj = new stdClass();
				$obj->type_id = $item->id;
				$obj->field_type = "attribs";
				$obj->name = 'Attribs(' . $item->name . ')';
				$obj->label ='attribs';
				$obj->description = 'Article\'s attribs';
				$obj->isrequired = 0;
				$obj->iscore = 1;
				$obj->ordering = ($field->ordering + 1);
				// d�termine que le champs est valable pour l'export et l'import
				$obj->params = '{"usefor":"1"}';
				$obj->published = 1;
				$this->_db->insertObject('#__fleximport_fields', $obj);
				// ajout du champs langue qui est obligatoire �galement
				$obj = new stdClass();
				$obj->type_id = $item->id;
				$obj->field_type = "language";
				$obj->name = 'Language(' . $item->name . ')';
				$obj->label ='language';
				$obj->description = 'Article\'s language';
				$obj->isrequired = 0;
				$obj->iscore = 1;
				$obj->ordering = ($field->ordering + 1);
				// d�termine que le champs est valable pour l'export et l'import
				$obj->params = '{"usefor":"1"}';
				$obj->published = 1;
				$this->_db->insertObject('#__fleximport_fields', $obj);
				return true;
			}
		}else{
			return false;
		}
	}
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_fleximport.edit.type.data', array());
        if (empty($data)) {
            $data = $this->getItem();
        }
        return $data;
    }
    public function delete(&$pks)
    {
        if (parent::delete($pks)) {
            // delete fields associated
            try{
                $query = $this->_db->getQuery(true);
                $query->delete('#__fleximport_fields');
                $query->where('type_id IN (' . implode(',', $pks) . ')');
                $this->_db->setQuery($query);
                $this->_db->execute();
            }catch (Exception $e){
                    JFactory::getApplication()->enqueueMessage($e->getMessage(),'error');
                    return false;
            }

            return true;
        } else {
            return false;
        }
    }
	public function validate($form, $data, $group = null)
	{
		$form->validate($data, $group);
        try{
            $form->validate($data, $group);
        }catch (Exception $e){
            JFactory::getApplication()->enqueueMessage($e->getMessage(),'error');
            return false;
        }
		return $data;
	}
}