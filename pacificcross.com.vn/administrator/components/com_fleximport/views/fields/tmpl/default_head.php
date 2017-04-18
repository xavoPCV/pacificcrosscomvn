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
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= ($listOrder == 'f.ordering' && $listDirn=='asc' && count($this->items) > 1);
?>
<tr>
	<th width="5%">
		<?= JHtml::_('grid.checkall'); ?>
	</th>
	<th width="5%">
		<?= JText::_('COM_FLEXIMPORT_NUM'); ?>
	</th>
	<th width="15%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_FIELD_NAME', 'f.name', $listDirn, $listOrder); ?>
	</th>
	<th width="15%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_FIELD_LABEL', 'f.label', $listDirn, $listOrder); ?>
	</th>
	<th width="25%">
		<?= JText::_('COM_FLEXIMPORT_DESCRIPTION'); ?>
	</th>
	<th width="1%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_FIELD_ISCORE', 'f.iscore', $listDirn, $listOrder); ?>
	</th>
	<th width="1%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_FIELD_REQUIRED', 'f.isrequired', $listDirn, $listOrder); ?>
	</th>
	<th width="1%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_PUBLISHED', 'f.published', $listDirn, $listOrder); ?>
	</th>
	<th width="10%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_REORDER', 'f.ordering', $listDirn, $listOrder); ?>
		<?php if ($saveOrder) :?>
			<?= JHtml::_('grid.order',  $this->items, 'filesave.png', 'fields.saveorder'); ?>
		<?php endif; ?>
	</th>
	<th width="5%">
		<?= JHtml::_('grid.sort',  'COM_FLEXIMPORT_ID', 'f.id', $listDirn, $listOrder); ?>
	</th>
</tr>