<?php
/**
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
?>
<h2><?= JText::_('COM_FLEXIMPORT_EXPORT_FILTER_SAVE'); ?></h2>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<small><?= JText::_('COM_FLEXIMPORT_EXPORT_FILTERS_INFOS'); ?></small><br/>
	<span><?= JText::_('COM_FLEXIMPORT_EXPORT_FILTERS_NAME'); ?></span>
	<input type="text" name="filter_name" id="filter_name" size="40" value="<?= $this->value_default;?>"/><br/><br/>
	<input type="submit" value="<?= JText::_('COM_FLEXIMPORT_EXPORT_RECORD_FILTERS');?>"/>
	<input type="hidden" name="option" value="com_fleximport" />
	<input type="hidden" name="view" value="export" />
	<input type="hidden" name="layout" value="savefilter" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="export.savefilter" />
	<?= JHtml::_( 'form.token' ); ?>
</form>