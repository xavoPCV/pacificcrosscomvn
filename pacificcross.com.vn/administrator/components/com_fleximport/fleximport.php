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
// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fleximport')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 404);
}
$params = JComponentHelper::getParams('com_fleximport');
define('FLEXIMPORT_DEBUG',$params->get('debug','0'));
if (FLEXIMPORT_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL| E_STRICT);
}
if ($params->get('notimelimit', 0))
    set_time_limit(0);

jimport( 'joomla.version' );

define('FLEXIMPORT_VERSION', '3.0');
define('FLEXIMPORT_PATH_FIELDS', JPATH_COMPONENT . '/classes/plugins/fields/');
define('FLEXIMPORT_PATH_TYPES', JPATH_COMPONENT . '/classes/plugins/types/');
$GLOBALS['fi_fields_required'] = array(
    'title',
    'categories');
$GLOBALS['fi_fields_nocopy'] = array(
    'version',
    'favourites',
    'voting',
    'modified',
    'created',
    'type',
    'hits',
    'groupmarker',
    'fcpagenav',
    'toolbar',
    'relation_reverse');
$GLOBALS['fi_fields_standard'] = array(
    'checkbox',
    'checkboximage',
    'date',
    'email',
    'extendedweblink',
    'file',
    'image',
    'linkslist',
    'minigallery',
    'radio',
    'radioimage',
    'relation',
    'relation_reverse',
    'select',
    'selectmultiple',
    'text',
    'textarea',
    'textselect',
    'weblink');
$GLOBALS['fi_fields_system'] = array(
    'attribs',
    'categories',
    'createdby',
    'language',
    'maintext',
    'modifiedby',
    'publishdown',
    'publishup',
    'state',
    'tags',
    'title');
if (!defined('__DIR__')) {
	define('__DIR__', dirname(__FILE__));
}
JLoader::register('FLeximportHelper', __DIR__ . '/helpers/fleximport.php');

JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html');

if (JFactory::getApplication()->input->get('format')!= 'raw') {
    $document = JFactory::getDocument();
    $document->addStyleSheet(JUri::root(true) . '/media/com_fleximport/css/fleximport.css');
    JHtml::_('jquery.framework');
    $document->addStyleSheet(JUri::root(true) . '/media/com_fleximport/css/joomla3.css');

}
/* Utile pour certains plugins */
$lang = JFactory::getLanguage();
$lang->load('com_flexicontent', JPATH_ADMINISTRATOR);
$lang->load('com_content', JPATH_ADMINISTRATOR);

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/controller.php');
$controller = JControllerLegacy::getInstance('FlexImport');
$controller->execute(JFactory::getApplication()->input->get('task'));

$controller->redirect();