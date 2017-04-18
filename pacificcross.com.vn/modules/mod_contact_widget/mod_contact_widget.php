<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_countup
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$email	= $params->get('email');
$grouptype	= $params->get('type_121');
$captcha	= $params->get('captcha');
$fileupload	= $params->get('fileupload');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_contact_widget', $params->get('layout', 'default'));
