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
defined( '_JEXEC' ) or die;
JHtml::_('behavior.tooltip');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="<?= JRoute::_('index.php?option=com_fleximport&view=types'); ?>" method="post" name="adminForm" id="adminForm">
	<?= $this->loadTemplate('filter'); ?>
	<?php if (count($this->items)):?>
		<table class="adminlist">
			<thead><?= $this->loadTemplate('head'); ?></thead>
			<tfoot><?= $this->loadTemplate('footer'); ?></tfoot>
			<tbody><?= $this->loadTemplate('body'); ?></tbody>
		</table>
	<?php else: ?>
		<div class="alert"><?= JText::_('COM_FLEXIMPORT_NO_ELEMENT')?></div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?= $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?= $listDirn; ?>" />
		<?= JHtml::_('form.token'); ?>
	</div>
</form>