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
JHTML::_('behavior.modal');
?>
<form action="<?= JRoute::_('index.php?option=com_fleximport&view=logs'); ?>" method="post" name="adminForm" id="adminForm">

	<table class="adminform">
		<tbody>
		<tr>
			<td>
				<div class="filter-select fltrt">
					<?php foreach ($this->filters as $filterName => $filter ){
						echo $filter;
					}?>
					<button type="submit" class="btn"><?= JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
	<?php if (count($this->items) > 0) : ?>
	<table class="adminlist">
		<thead>
			<th width="5"><?= JText::_( 'COM_FLEXIMPORT_NUM' ); ?></th>
			<th width="7">
		<?= JHtml::_('grid.checkall') ?>
			</th>
			<th class="title"><?= JText::_( 'COM_FLEXIMPORT_LOG_DATE' ); ?></th>
			<th width="10%"><?= JText::_( 'COM_FLEXIMPORT_TYPE' ); ?></th>
			<th width="10%"><?= JText::_( 'COM_FLEXIMPORT_IMPORT_METHOD' ); ?></th>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5"><?= $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php
	foreach($this->items as $i => $item):
		$link = 'index.php?option=com_fleximport&format=raw&view=logs&cid[]='. $item->filepath;
	?>
	<tr class="row<?= $i % 2; ?>">
		<td class="center">
			<?= $this->pagination->getRowOffset($i); ?>
		</td>
		<td><input type="checkbox" id="cb<?= "$i"; ?>" name="cid[]" value="<?= $item->filepath;?>" onclick="Joomla.isChecked(this.checked);"></td>
		<td align="left">
			<span class="editlinktip hasTip" title="<?= JText::_( 'COM_FLEXIMPORT_LOG_PREVIEW' )."::".JText::_( 'COM_FLEXIMPORT_LOG_PREVIEW_DESC' );?>">
			<a class="modal" href="<?= $link; ?>" rel="{handler: 'iframe', size: {x: 650, y: 550}}">
			<?= $item->date; ?>
			</a></span>
		</td>
		<td align="center"><?= $item->type_name; ?></td>
		<td align="center"><?= $item->method; ?></td>
	</tr>
<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
		<div class="alert"><?= JText::_('COM_FLEXIMPORT_NO_ELEMENT')?></div>
	<?php endif; ?>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?= JHtml::_('form.token'); ?>
	</div>
</form>