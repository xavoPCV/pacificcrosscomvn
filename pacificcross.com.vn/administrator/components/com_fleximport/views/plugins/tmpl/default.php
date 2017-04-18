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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
<form enctype="multipart/form-data" action="<?= JRoute::_('index.php?option=com_fleximport&view=plugins');?>" method="post" name="adminForm_pck">
	<fieldset class="adminform">
		<legend><?= JText::_('COM_FLEXIMPORT_PLUGINS_INSTALL'); ?></legend>
		<table class="adminform">
			<tbody>
				<tr>
					<td width="80">
						<label for="install_package"><?= JText::_('COM_FLEXIMPORT_PLUGINS_SELECT_FILE'); ?>:</label>
					</td>
					<td>
						<input class="input_box" type="file" size="57" id="install_package" name="install_package" />
						<input type="submit" value="<?= JText::_('COM_FLEXIMPORT_PLUGINS_UPLOAD'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	<input type="hidden" name="task" value="plugins.installUpgrade" />
	<?= JHtml::_('form.token'); ?>
</form>

<form action="<?= JRoute::_('index.php?option=com_fleximport&view=plugins');?>" method="post" name="adminForm" id="adminForm">
		<table class="adminform">
			<tr>
				<td width="100%">
				</td>
				<td nowrap="nowrap">
					<?= $this->filters['type_plugin']; ?>
				</td>
			</tr>
		</table>
	<?php if (count($this->items) > 0) : ?>
		<table class="adminlist" cellspacing="1">
		<thead>
			<tr>
				<th width="5%">
		<?= JHtml::_('grid.checkall'); ?>
				</th>
				<th class="title"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS' ); ?></th>
				<th width="10%"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS_TYPEOF' ); ?></th>
				<th width="25%"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS_DESCRIPTION' ); ?></th>
				<th width="5%"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS_VERSION' ); ?></th>
				<th width="10%"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS_LICENCE' ); ?></th>
				<th width="15%"><?= JText::_( 'COM_FLEXIMPORT_PLUGINS_AUTHOR' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($this->items as $i => $item):
	   		?>
			<tr class="row<?= $i % 2; ?>">
				<td class="center">
				<?php
				if ($item->system!= "true") : ?>
					<?= JHtml::_('grid.id', $i, $item->id);?>
				<?php endif; ?>
				</td>
				<td align="left">
					<?= $item->name; ?>
				</td>
				<td align="center"><?= $item->type; ?></td>
				<td align="left"><?= $item->description; ?></td>
				<td align="center"><?= $item->version; ?></td>
				<td align="center"><?= $item->license; ?></td>
				<td align="center"><?= $item->author; ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		</table>
	<?php else: ?>
		<div class="alert"><?= JText::_('COM_FLEXIMPORT_NO_ELEMENT')?></div>
	<?php endif; ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?= JHtml::_( 'form.token' ); ?>
</form>