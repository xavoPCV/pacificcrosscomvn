<?php
defined('_JEXEC') or die;
$userId	= $this->user->get('id');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

foreach($this->items as $i => $item):
	$fields		= 'index.php?option=com_fleximport&view=fields&filter_type='. $item->id;
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
				<?= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'types.', $canCheckin); ?>
			<?php endif; ?>
			<?php if ($this->user->authorise('core.edit','com_fleximport')): ?>
				<a href="index.php?option=com_fleximport&task=type.edit&id=<?= (int)$item->id; ?>">
					<?= $item->name; ?>
				</a>
			<?php else: ?>
				<?= $item->name; ?>
			<?php endif; ?>
		</td>
		<td class="center">
			<?= $item->flexi_type; ?>
		</td>
		<td class="center">
			<?= $item->import_type; ?>
		</td>
		<td class="center">
		<?php
		if (strlen($item->description) > 50) {
			echo substr( htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8'), 0 , 50).'...';
		} else {
			echo htmlspecialchars($item->description, ENT_QUOTES, 'UTF-8');
		}
		?>
		</td>
		<td class="center">
			<?= $item->fassigned; ?>
			<a href="<?= $fields; ?>">
			[<?= JText::_( 'COM_FLEXIMPORT_VIEW_FIELDS' );?>]
			</a>
		</td>
		<td class="center">
			<?= JHtml::_('jgrid.published', $item->published, $i,'types.'); ?>
		</td>
		<td class="center">
			<?= $item->id; ?>
		</td>
	</tr>
<?php endforeach;