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

/**
 * Gnosis helper.
 */
class GnosisHelper
{
    /**
     * Configure the Linkbar.
     */
    public static function addSubmenu($vName = '')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_GNOSIS_TITLE_WORDS'),
            'index.php?option=com_gnosis&view=words',
            $vName == 'words'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_GNOSIS_TITLE_CATEGORIES'),
            'index.php?option=com_gnosis&view=categories',
            $vName == 'categories'
        );

    }

    /**
     * Gets a list of the actions that can be performed.
     *
     * @return    JObject
     * @since    1.6
     */
    public static function getActions()
    {
        $user = JFactory::getUser();
        $result = new JObject;

        $assetName = 'com_gnosis';

        $actions = array(
            'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }
}
