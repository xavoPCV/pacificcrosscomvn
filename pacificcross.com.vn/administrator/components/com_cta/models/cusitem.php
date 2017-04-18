<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Banner model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.6
 */
class ctaModelCusitem extends JModelAdmin
{
	
	var $max_size_upload = 20971520; // 20MB in bytes
	var $file_exts = array('gif', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'pdf', 'flv', 'mp4', 'zip', 'mp3', 'ppt', 'pptx', 'psd', 'xls', 'xlsx'); 
	
	/**
	 * Returns a JTable object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate. [optional]
	 * @param   string  $prefix  A prefix for the table class name. [optional]
	 * @param   array   $config  Configuration array for model. [optional]
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Cusitem', $prefix = 'ctaTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_cta.cusitem', 'cusitem', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_cta.edit.cusitem.data', array());

		if (empty($data)) {
			$data = $this->getItem();
			
			if ($data) {
				$data->cur_picture_name = $data->file_name;
			}//if
		}

		return $data;
	}
	
	
	protected function populateState()
	{
		// Load the User state.
		$pk = (int) JRequest::getInt('id');
		$this->setState('cusitem.id', $pk);

	}
	
	public function save($data)
	{
		
		define('PHOTOS_DIR', JPATH_ROOT."/media/com_cta/");
		
		if (!file_exists(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
		
		#print_r($data);
		#exit;
		JSession::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		
		$file = JRequest::getVar('jform', '', 'files');
		$old_picture = $data['cur_picture_name'];
		
		/*echo '<pre>';
		print_r($data);
		print_r($file);
		exit;*/
		
		if ( $file['name']['file_name'] && $file['error']['file_name'] ) {
			$this->setError('Upload Error!');
			return false;
		}//if
		
		if ($file['name']['file_name']) {
			//custom check
			$info = pathinfo($file['name']['file_name']);
			$extension  = strtolower($info['extension']);
			$valid_extension = in_array($extension, $this->file_exts);
			if ( !$valid_extension ) {
				$this->setError('Invalid featured file extension. Allow: '.(implode(', ', $this->file_exts)));
				return false;
			} else if ( $file['size']['file_name'] > $this->max_size_upload ) {
				$this->setError('Over max_size_upload. Allow: '.($this->max_size_upload/1048576).'MB');
				return false;
			}//if
			
			$filename = $info['filename'].'_'.rand(1,1000);
			if ( move_uploaded_file($file['tmp_name']['file_name'], PHOTOS_DIR.$filename.".".$extension)) {
				$file_name = $filename.".".$extension;
				$data['file_name'] = $file_name;
				$data['file_type'] = $extension;
				if ($old_picture) @unlink(PHOTOS_DIR.$old_picture);
			}//if
		}//if
		
		if (!$data['id']) $data['date_created'] = JFactory::getDate()->toSQL();
		
		
		return parent::save($data);
		
	}//func
	
	public function delete() {
		$table = $this->getTable();
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$db = $this->getDBO();
		foreach ($cid as $pk) {
			$table->load($pk);
			if ($table->file_name) {
				@unlink(JPATH_ROOT."/media/com_cta/".$table->file_name);
			}//if
			$table->delete($pk);
		}//for
		return true;
	}//func
	
}//class