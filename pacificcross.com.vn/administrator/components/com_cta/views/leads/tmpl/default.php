<?php
// no direct access
defined('_JEXEC') or die;

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));


$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_cta&view=leads'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
		</div>
	</fieldset>
	<div class="clr"> </div>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th>
					<div align="left"><?php echo JHtml::_('grid.sort',  'First Name', 'a.first_name', $listDirn, $listOrder); ?></div>
				</th>
				<th>
					<div align="left"><?php echo JHtml::_('grid.sort',  'Last Name', 'a.last_name', $listDirn, $listOrder); ?></div>
				</th>
				<th>
					<div align="left"><?php echo JHtml::_('grid.sort',  'Email', 'a.email', $listDirn, $listOrder); ?></div>
				</th>
				<th>
					<div align="left"><?php echo JText::_('Phone'); ?></div>
				</th>
				<th>
					<?php echo JHtml::_('grid.sort',  'Created', 'a.date_created', $listDirn, $listOrder); ?>
				</th>
				
				<th width="1%" class="nowrap">
					<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="13">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<?php echo $item->first_name;?>
				</td>
				<td>
					<?php echo $item->last_name;?>
				</td>
				<td>
					<?php echo $item->email; ?>
				</td>
				<td>
					<?php echo $this->escape($item->phone); ?>
				</td>
				<td align="center">
					<?php echo JHtml::_('date', $item->date_created, JText::_('DATE_FORMAT_LC3'));?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>