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
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * flexIMPORT Component Field Model
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */

class FleximportModelField extends JModelAdmin {

	public function getTable($type = 'Fields', $prefix = 'FlexImportTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_fleximport.field', 'field', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;

	}
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_fleximport.edit.field.data', array());
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'type_id = ' . (int)$table->type_id;
		return $condition;
	}
	public function validate($form, $data, $group = null)
	{
        try{
            $form->validate($data, $group);
        }catch (Exception $e){
            JFactory::getApplication()->enqueueMessage($e->getMessage(),'error');
            return false;
        }
		return $data;
	}
}