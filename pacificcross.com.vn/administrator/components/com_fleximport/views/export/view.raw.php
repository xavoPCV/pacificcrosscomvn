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

/**
 * View class for the flexIMPORT logs screen
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewExport extends JViewLegacy {
	protected $form;
	protected $state;
	protected $user;
	protected $items;
	protected $pagination;

    function display($tpl = null)
    {

    	$this->user = JFactory::getUser();
    	$this->state = $this->get('State');
    	$this->form = $this->get('Form');
    	$this->items = $this->get('Items');
    	$this->pagination = $this->get('Pagination');

    	require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.categories.php');
    	require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.helper.php');
    	require_once (JPATH_SITE . '/components/com_flexicontent/helpers/route.php');

    	$this->setLayout('default_list');
        parent::display($tpl);
    }
}
