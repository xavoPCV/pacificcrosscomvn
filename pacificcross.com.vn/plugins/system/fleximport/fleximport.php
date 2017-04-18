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

class plgSystemFleximport extends JPlugin {
    function onAfterInitialise()
    {
        $input = JFactory::getApplication()->input;
        $option = $input->get('option');
        $task = $input->get('task' );
        // limite la connexion automatique pour les taches cron
        if ($option != 'com_fleximport' or ($task != 'import.cron' && $task!='export.cron')) return;
        $cronAccess = base64_decode($input->get('crona'));
        $userValue = explode('||', $cronAccess);
        if (count($userValue) != 2) return;
        $username = $userValue[0];
        $password = $userValue[1];
        $params = JComponentHelper::getParams('com_fleximport');

        if (!empty($username) && !empty($password) && $params->get('allow_cron', 0))
            $result = $this->loginUser($username, $password);

        return;
    }

    private function loginUser($username, $password)
    {
        jimport('joomla.user.helper');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id')->from('#__users')->where('username = ' . $db->quote($username))->where('password = ' . $db->quote($password));
        $db->setQuery($query);
        $result = $db->loadObject();
        if ($result) {
            $mainframe = JFactory::getApplication();
            JPluginHelper::importPlugin('user');
            $response = new stdClass();
            $response->username = $username;
            $response->password = $password;

            $options['action'] = 'core.login.admin';
            $result = $mainframe->triggerEvent('onUserLogin', array((array)$response, $options));
        }
        return;
    }
}