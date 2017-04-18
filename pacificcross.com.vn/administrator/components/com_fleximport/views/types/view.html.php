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

class FleximportViewTypes extends JViewLegacy {
    protected $items;
    protected $pagination;
    protected $state;
    protected $filters;
    protected $params;
    protected $user;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->params = JComponentHelper::getParams('com_fleximport');
        $this->user = JFactory::getUser();

        $filters = array();
    	$publishOptions[] =  JHtml::_('select.option','*',JText::_('JALL'));
    	$publishOptions[] =  JHtml::_('select.option','1',JText::_('JPUBLISHED'));
    	$publishOptions[] =  JHtml::_('select.option','0',JText::_('JUNPUBLISHED'));

		$filters['published'] = JHtml::_('select.genericlist',$publishOptions,'filter_published',array('onchange'=>'this.form.submit()'),'value','text',$this->state->get('filter.published'),'filter_published');
		$filters['flexi_type'] = JHtml::_('fifilters.flexitype',$this->state->get('filter.flexi_type_id'));
		$filters['id'] = JHtml::_('fifilters.id',$this->state->get('filter.id'));

        $this->filters = $filters;
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception( implode("\n", $errors), 500);
            return false;
        }

        $this->addToolBar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $user = JFactory::getUser();

        JToolBarHelper::title(JText::_('COM_FLEXIMPORT_TYPES'), 'types');

        if ($user->authorise('core.create', 'com_fleximport')) {
            JToolBarHelper::custom('types.copy', 'copy.png', 'copy_f2.png', 'COM_FLEXIMPORT_COPY',true);
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
            JToolBarHelper::addNew('type.add', 'JTOOLBAR_NEW');
        }
        if ($user->authorise('core.edit', 'com_fleximport')) {
            JToolBarHelper::editList('type.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
        }
        if ($user->authorise('core.edit', 'com_fleximport')) {
            JToolBarHelper::publish('types.publish', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::unpublish('types.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
        }
        if ($user->authorise('core.delete', 'com_fleximport')) {
            JToolBarHelper::deleteList('', 'types.delete', 'JTOOLBAR_DELETE');
            JToolBarHelper::divider();
            JToolBarHelper::spacer();
        }
        if ($user->authorise('core.admin', 'com_fleximport')) {
            JToolBarHelper::preferences('com_fleximport');
        }
    }
}