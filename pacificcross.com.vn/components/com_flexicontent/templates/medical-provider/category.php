<?php
/**
 * @version 1.5 stable $Id: category.php 1505 2012-10-01 17:16:26Z ggppdk $
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

// USE HTML5 or XHTML
$html5 = $this->params->get('htmlmode', 0); // 0 = XHTML , 1 = HTML5
if ($html5) {  /* BOF html5  */
	echo $this->loadTemplate('html5');
} else {

$page_classes  = '';
$page_classes .= $this->pageclass_sfx ? ' page'.$this->pageclass_sfx : '';
$page_classes .= ' fccategory fccat'.$this->category->id;
$menu = JFactory::getApplication()->getMenu()->getActive();
if ($menu) $page_classes .= ' menuitem'.$menu->id; 
?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
		<h1 class="page-title">
			<?php echo $this->params->get( 'page_heading' ) ?>
		</h1>
<?php endif; ?>


<?php if ( $this->category->id > 0) : /* Category specific data may not be not available, e.g. for -author- layout view */ ?>
		<?php
		// Only show this part if some category info is to be printed
		if (
			$this->params->get('show_cat_title', 1) ||
			($this->params->get('show_description_image', 1) && $this->category->image) ||
			($this->params->get('show_description', 1) && $this->category->description)
		) :
			echo $this->loadTemplate('category');
		endif;
		?>
<?php endif; ?>

<!-- needs to be added to the head in production site. -->
<!-- <script src="../js/google.map-settings.js"></script> -->

<!-- <div class="pure-g">
    <div class="pure-u-1">
        <div class="map-wrapper">
            <div id="map-canvas">
            </div>
        </div>
    </div>
</div> -->

<div class="provider-list">
<?php
	echo $this->loadTemplate('items');
	echo empty($this->items) ? '<span class="fc_return_msg">'.JText::sprintf('FLEXI_CLICK_HERE_TO_RETURN', '"JavaScript:window.history.back();"').'</span>' : "";
?>
</div><!-- end of provider-list -->
<?php
	// If customizing via CSS rules or JS scripts is not enough, then please copy the following file here to customize the HTML too
	// include('pagination.php');
	include(JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'tmpl_common'.DS.'pagination.php');
?>

<?php } /* EOF if html5  */ ?>