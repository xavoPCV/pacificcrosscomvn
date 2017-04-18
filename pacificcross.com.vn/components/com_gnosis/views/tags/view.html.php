<?php
/**
 * @version     1.0.7
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Gnosis.
 */
class GnosisViewTags extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->params = $app->getParams('com_gnosis');


        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            ;
            throw new Exception(implode("\n", $errors));
        }


        $this->_prepareDocument();
        parent::display($tpl);
    }


    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
	
        $app = JFactory::getApplication();

        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('com_gnosis_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }

    protected function getSortFields()
    {
        return array(
            'a.id' => JText::_('JGRID_HEADING_ID'),
            'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'a.state' => JText::_('JSTATUS'),
            'a.word' => JText::_('COM_GNOSIS_WORDS_WORD'),
            'a.pronounciation' => JText::_('COM_GNOSIS_WORDS_PRONOUNCIATION'),
            'a.category' => JText::_('COM_GNOSIS_WORDS_CATEGORY'),
            'a.definition' => JText::_('COM_GNOSIS_WORDS_DEFINITION'),
            'a.created_by' => JText::_('COM_GNOSIS_WORDS_CREATED_BY'),
			'a.creation_date' => JText::_('COM_GNOSIS_WORDS_CREATION_DATE'),
			'a.modified_date' => JText::_('COM_GNOSIS_WORDS_MODIFIED_DATE'),
			'a.tags' => JText::_('COM_GNOSIS_WORDS_TAGS'),
			'a.source' => JText::_('COM_GNOSIS_WORDS_SOURCE'),
        );
    }
	
}




