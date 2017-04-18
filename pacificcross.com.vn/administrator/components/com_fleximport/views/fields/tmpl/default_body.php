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
$userId	= $this->user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= ($listOrder == 'f.ordering' && $listDirn=='asc' && count($this->items) > 1);

foreach($this->items as $i => $item):
	$canCheckin	= $this->user->authorise('core.manage','com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
	?>
	<tr class="row<?= $i % 2; ?>">
		<td class="center">
			<?php
			if ($this->user->authorise('core.edit','com_fleximport') || $this->user->authorise('core.delete','com_fleximport')) {
				echo JHtml::_('grid.id', $i, $item->id);
			}
			?>
		</td>
		<td class="center">
			<?= $this->pagination->getRowOffset($i); ?>
		</td>
		<td>
			<?php if ($item->checked_out) : ?>
				<?= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'fields.', $canCheckin); ?>
			<?php endif; ?>
			<?php if ($this->user->authorise('core.edit','com_fleximport')): ?>
				<a href="index.php?option=com_fleximport&task=field.edit&id=<?= (int)$item->id; ?>">
					<?= $item->name; ?>
				</a>
			<?php else: ?>
				<?= $item->name; ?>
			<?php endif; ?>
		</td>
		<td class="center">
			<?= $item->label.'<br/><small><i>'.$item->field_type.'</i></small>'; ?>
		</td>
		<td >
			<?php
			if (strlen($item->description) > 50) {
				echo substr( htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8'), 0 , 50).'...';
			} else {
				echo htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8');
			}
			?>
		</td>
		<td class="center">
			<?php
			if ($item->iscore) {
				$imgData = 'tick.png';
			}else{
				$imgData = 'publish_x.png';
			}
			echo JHtml::_('image', 'admin/' . $imgData, null, null, true);
			?>
		</td>
		<td class="center">
			<?php
			if ($item->isrequired) {
				$imgData = 'tick.png';
			}else{
				$imgData = 'publish_x.png';
			}
			echo JHtml::_('image', 'admin/' . $imgData, null, null, true);
			?>
		</td>
		<td class="center">
			<?= JHtml::_('jgrid.published', $item->published, $i,'fields.'); ?>
		</td>
		<td class="order">
			<?php if ($saveOrder) :?>
					<span><?= $this->pagination->orderUpIcon($i, ($item->type_id == @$this->items[$i-1]->type_id ), 'fields.orderup'); ?></span>
					<span><?= $this->pagination->orderDownIcon($i,$this->pagination->total,($item->type_id == @$this->items[$i+1]->type_id), 'fields.orderdown'); ?></span>
			<?php endif; ?>
			<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
			<input type="text" name="order[]" size="5" value="<?= $item->ordering;?>" <?= $disabled ?> class="text-area-order" />
		</td>
		<td class="center">
			<?= $item->id; ?>
		</td>
	</tr>
<?php endforeach;