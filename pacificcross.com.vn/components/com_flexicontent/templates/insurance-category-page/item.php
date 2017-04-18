<?php
/**
 * @version 1.5 stable $Id: item.php 1704 2013-08-04 08:23:12Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
// first define the template name
$tmpl = $this->tmpl; // for backwards compatiblity
$item = $this->item;  // an alias

// USE HTML5 or XHTML
$html5			= $this->params->get('htmlmode', 0); // 0 = XHTML , 1 = HTML5
if ($html5) {  /* BOF html5  */
	echo $this->loadTemplate('html5');
} else {

// Prepend toc (Table of contents) before item's description (toc will usually float right)
// By prepend toc to description we make sure that it get's displayed at an appropriate place
if (isset($item->toc)) {
	$item->fields['text']->display = $item->toc . $item->fields['text']->display;
}

// Set the class for controlling number of columns in custom field blocks
switch ($this->params->get( 'columnmode', 2 )) {
	case 0: $columnmode = 'singlecol'; break;
	case 1: $columnmode = 'doublecol'; break;
	default: $columnmode = ''; break;
}

$page_classes  = '';
$page_classes .= $this->pageclass_sfx ? ' page'.$this->pageclass_sfx : '';
$page_classes .= ' fcitems fcitem'.$item->id;
$page_classes .= ' fctype'.$item->type_id;
$page_classes .= ' fcmaincat'.$item->catid;
?>
<?php if ( $this->params->get( 'show_page_heading', 1 ) && $this->params->get('page_heading') != $item->title ) : ?>
	<h1 class="page-title">
	<?php if (isset($item->positions['page-icon'])) : ?>
		<?php foreach ($item->positions['page-icon'] as $field) : ?>
			<?php echo $field->display; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php echo $this->params->get('page_heading'); ?>
	</h1>
<?php endif; ?>

<?php if ($this->params->get('show_title', 1)) : ?>
	<h1 class="page-title">
		<?php if (isset($item->positions['page-icon'])) : ?>
			<?php foreach ($item->positions['page-icon'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php echo $item->title; ?>
	</h1>
<?php endif; ?>

<?php if (isset($item->positions['description'])) : ?>
	<?php foreach ($item->positions['description'] as $field) : ?>
		<?php echo $field->display; ?>
	<?php endforeach; ?>
<?php endif; ?>

<div class="pure-g margin-top left-right-selector">
	<?php if (isset($item->positions['info-box'])) : ?>
		<?php foreach ($item->positions['info-box'] as $field) : ?>
			<?php echo $field->display; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</div><!-- end of pure-g -->

<?php } /* EOF if html5  */ ?>
