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

class com_fleximportInstallerScript {


    public function install(JAdapterInstance $adapter)
    {

        $this->displayWelcome();
    }

    public function update(JAdapterInstance $adapter)
    {
        $this->install($adapter);
    }

    protected function displayWelcome()
    {
        $lang_tag = JFactory::getLanguage()->getTag();
        $welcome_path = JPATH_ADMINISTRATOR . '/components/com_fleximport/installation/';
        if (file_exists($welcome_path . $lang_tag . '.welcome.html')) {
            include $welcome_path . $lang_tag . '.welcome.html';
        } else {
            include $welcome_path . 'fr-FR.welcome.html';
        }
    }
}