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
<h3><?= JText::sprintf('COM_FLEXIMPORT_FILTER_RESULT',$this->pagination->total); ?></h3>
<?php if ($this->items) :?>
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th width="5" class="center">
			<?= JHtml::_('grid.checkall'); ?>
		</th>
		<th class="left">
			<?= JText::_('FLEXI_TITLE'); ?>
		</th>
		<th width="1%" nowrap="nowrap" class="center">
			<?= JText::_('FLEXI_FLAG'); ?>
		</th>
		<th width="1%" nowrap="nowrap" class="center">
			<?= JText::_('FLEXI_TYPE_NAME'); ?>
		</th>
		<th width="10%" class="left">
			<?= JText::_( 'FLEXI_CATEGORIES' ); ?>
		</th>
		<th align="center" width="85" class="center">
			<?= JText::_('FLEXI_CREATED'); ?>
		</th>
		<th align="center" width="85" class="center">
			<?= JText::_('FLEXI_REVISED'); ?>
		</th>
		<th width="2%" nowrap="nowrap" class="center">
			<?= JText::_('FLEXI_ID'); ?>
		</th>
	</tr>
</thead>
<tfoot>
	<tr>
		<td colspan="8">
			<?= $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
<tbody>
<?php foreach ($this->items as $i => $item) : ?>
	<?php
	$checked = JHtml::_('grid.id', $i, $item->id);
	$autologin		= '&fcu='.$this->user->username . '&fcp='.$this->user->password;
	$previewlink 	= JUri::root().FlexicontentHelperRoute::getItemRoute($item->id.':'.$item->alias) . $autologin.'&tmpl=component';
	?>
	<tr class="row<?= $i % 2; ?>">
		<td width="7"><?= $checked; ?></td>
		<td align="left">
			<a href="<?= $previewlink; ?>" class="modal" rel="{handler: 'iframe', size: {x: 780, y: 500}}">
				<?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
			</a>
		</td>
		<td align="center">
		<?php if ($item->language=='*'):
			echo JText::_('JALL');
		else:
			if (strpos($item->language,'_')!==false){
				$lang = explode('_',$item->language);
			}else{
				$lang = explode('-',$item->language);
			}
			?>
			<img src="<?= JUri::root(true); ?>/media/mod_languages/images/<?= $lang[0]; ?>.gif" alt="<?= $item->language; ?>" />
		<?php endif; ?>
		</td>
		<td align="center" nowrap="nowrap">
			<?= $item->type_name; ?>
		</td>
		<td nowrap="nowrap">
		<?php
		foreach ($item->categories as $key => $category) :
			$typeofcats = ((int)$category->id == (int)$item->maincat) ? 'maincat' : 'secondarycat';
			if ($typeofcats=='maincat') {
				$title = htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8');
				echo $title;
				break;
			}
			endforeach;
		?>
		</td>
		<td nowrap="nowrap">
			<?= JHtml::_('date',  $item->created,JText::_('COM_FLEXIMPORT_DATE_FORMAT') ); ?>
		</td>
		<td nowrap="nowrap">
			<?= ($item->modified != '0000-00-00 00:00:00') ? JHtml::_('date', $item->modified, JText::_('COM_FLEXIMPORT_DATE_FORMAT')) : JText::_('FLEXI_NEVER'); ?>
		</td>
		<td align="center">
			<?= $item->id; ?>
		</td>
	</tr>
<?php endforeach ;?>
</tbody>
</table>
<?php endif; ?>