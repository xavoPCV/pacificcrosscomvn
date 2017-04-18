<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/abstract.php');

class JFormFieldCategories extends EasyBlogFormField
{
	protected $type = 'Categories';

	/**
	 * Displays the category selection form
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */	
	protected function getInput()
	{
		$title = JText::_('COM_EASYBLOG_SELECT_A_CATEGORY');

		if ($this->value) {
			$category = EB::table('Category');
			$category->load((int) $this->value);
			
			$title = $category->title;
		}

		$theme = EB::template();
		$theme->set('id', $this->id);
		$theme->set('name', $this->name);
		$theme->set('value', $this->value);
		$theme->set('title', $title);

		$output = $theme->output('admin/elements/categories');

		return $output;
	}
}