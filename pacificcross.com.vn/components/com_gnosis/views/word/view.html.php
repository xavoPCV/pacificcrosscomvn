<?php

/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class GnosisViewWord extends JViewLegacy
{

    protected $state;
    protected $item;
    protected $form;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null)
    {

        $app = JFactory::getApplication();
        $user = JFactory::getUser();

        $this->state = $this->get('State');
        $this->item = $this->get('Data');
        $this->params = $app->getParams('com_gnosis');

        $this->form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }


        if ($this->_layout == 'edit') {

            $authorised = $user->authorise('core.create', 'com_gnosis');

            if ($authorised !== true) {
                throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
            }
        }

        $this->_prepareDocument();

        parent::display($tpl);
    }


    /**
     * Prepares the document
     */
    protected function _prepareDocument()
    {
        JPluginHelper::importPlugin('content');
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        if ($this->params->get('gcontentplugins') == '1') {
            $this->item->definition = JHtml::_('content.prepare', $this->item->definition);
            $this->item->examples = JHtml::_('content.prepare', $this->item->examples);
            $this->item->etymology = JHtml::_('content.prepare', $this->item->etymology);
        }
        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($this->params->get('gwpageheading') == '0') {
            $this->params->def('page_heading', $this->item->word);
        } else if ($this->params->get('gwpageheading') == '1') {
            $this->params->def('page_heading', JText::_('COM_GNOSIS'));
        } else {
            if ($menu) {
                $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
            } else {
                $this->params->def('page_heading', JText::_('COM_GNOSIS'));
            }
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

}
