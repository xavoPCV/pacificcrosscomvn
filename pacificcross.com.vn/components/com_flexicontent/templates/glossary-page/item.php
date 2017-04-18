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

<?php if ($this->params->get('show_title', 1)) : ?>
	<h1 class="page-title"><span class="icon-border"><i class="ion ion-android-list"></i></span> <?php echo $item->title; ?></h1>
<?php endif; ?>

<?php if (isset($item->positions['description'])) : ?>
	<?php foreach ($item->positions['description'] as $field) : ?>
		<?php echo $field->display; ?>
	<?php endforeach; ?>
<?php endif; ?>
<div id="glossary">
<form name="glossary-search" id="glossary-search" class="pure-form pure-form-stacked">
  <fieldset>
      <label for="text"><?php echo JText::_('SEARCH_BY_TERMS'); ?></label>
      <input type="text" name="text" class="pure-u-1" id="filter" placeholder="<?php echo JText::_('ENTER_KEYWORDS'); ?>" tabindex="1" />
  </fieldset>
</form>
  <p><strong><?php echo JText::_('SEARCH_RESULTS'); ?>:</strong> <span id="filter-count"></span></p>

	<?php if (isset($item->positions['glossary-items'])) : ?>
		<?php foreach ($item->positions['glossary-items'] as $field) : ?>
			<?php echo $field->display; ?>
		<?php endforeach; ?>
	<?php endif; ?>

<script type="text/javascript" async>
	!function(t){t(document).ready(function(){t("#filter").keyup(function(){var e=t(this).val(),n=0;t(".glossary-list li").each(function(){t(this).text().search(new RegExp(e,"i"))<0?t(this).fadeOut():(t(this).show(),n++)});t("#filter-count").text(n+" <?php echo JText::_('GLOSSARY_ITEMS_FOUND'); ?>")}),t("#glossary-search :input").on("keypress",function(t){return 13!=t.keyCode})})}($);
</script>
</div><!-- end of #glossary -->

<?php } /* EOF if html5  */ ?>
