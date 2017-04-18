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
	<h1 class="page-title"><span class="icon-border"><i class="ion ion-android-download"></i></span> <?php echo $item->title; ?></h1>
<?php endif; ?>

<?php if (isset($item->positions['description'])) : ?>
	<?php foreach ($item->positions['description'] as $field) : ?>
		<?php echo $field->display; ?>
	<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($item->positions['Section1Title'])) : ?>
	<?php foreach ($item->positions['Section1Title'] as $field) : ?>
		<h2><?php echo $field->display; ?></h2>
	<?php endforeach; ?>

	<div class="bkg-white pall-15px form-box-list">
		<?php if (isset($item->positions['Section1SubTitle1'])) : ?>
			<?php foreach ($item->positions['Section1SubTitle1'] as $field) : ?>
				<h3 style="margin-top:0"><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1File1'])) : ?>
			<?php foreach ($item->positions['Section1File1'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1SubTitle2'])) : ?>
			<?php foreach ($item->positions['Section1SubTitle2'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1File2'])) : ?>
			<?php foreach ($item->positions['Section1File2'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1SubTitle3'])) : ?>
			<?php foreach ($item->positions['Section1SubTitle3'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1File3'])) : ?>
			<?php foreach ($item->positions['Section1File3'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1SubTitle4'])) : ?>
			<?php foreach ($item->positions['Section1SubTitle4'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section1File4'])) : ?>
			<?php foreach ($item->positions['Section1File4'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- end of bkg-white pall-15px -->
<?php endif; ?>

<?php if (isset($item->positions['Section2Title'])) : ?>
	<?php foreach ($item->positions['Section2Title'] as $field) : ?>
		<h2><?php echo $field->display; ?></h2>
	<?php endforeach; ?>

	<div class="bkg-white pall-15px form-box-list">
		<?php if (isset($item->positions['Section2SubTitle1'])) : ?>
			<?php foreach ($item->positions['Section2SubTitle1'] as $field) : ?>
				<h3 style="margin-top:0"><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2File1'])) : ?>
			<?php foreach ($item->positions['Section2File1'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2SubTitle2'])) : ?>
			<?php foreach ($item->positions['Section2SubTitle2'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2File2'])) : ?>
			<?php foreach ($item->positions['Section2File2'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2SubTitle3'])) : ?>
			<?php foreach ($item->positions['Section2SubTitle3'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2File3'])) : ?>
			<?php foreach ($item->positions['Section2File3'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2SubTitle4'])) : ?>
			<?php foreach ($item->positions['Section2SubTitle4'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section2File4'])) : ?>
			<?php foreach ($item->positions['Section2File4'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- end of bkg-white pall-15px -->
<?php endif; ?>

<?php if (isset($item->positions['Section3Title'])) : ?>
	<?php foreach ($item->positions['Section3Title'] as $field) : ?>
		<h2><?php echo $field->display; ?></h2>
	<?php endforeach; ?>

	<div class="bkg-white pall-15px form-box-list">
		<?php if (isset($item->positions['Section3SubTitle1'])) : ?>
			<?php foreach ($item->positions['Section3SubTitle1'] as $field) : ?>
				<h3 style="margin-top:0"><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3File1'])) : ?>
			<?php foreach ($item->positions['Section3File1'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3SubTitle2'])) : ?>
			<?php foreach ($item->positions['Section3SubTitle2'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3File2'])) : ?>
			<?php foreach ($item->positions['Section3File2'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3SubTitle3'])) : ?>
			<?php foreach ($item->positions['Section3SubTitle3'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3File3'])) : ?>
			<?php foreach ($item->positions['Section3File3'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3SubTitle4'])) : ?>
			<?php foreach ($item->positions['Section3SubTitle4'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section3File4'])) : ?>
			<?php foreach ($item->positions['Section3File4'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- end of bkg-white pall-15px -->
<?php endif; ?>

<?php if (isset($item->positions['Section4Title'])) : ?>
	<?php foreach ($item->positions['Section4Title'] as $field) : ?>
		<h2><?php echo $field->display; ?></h2>
	<?php endforeach; ?>

	<div class="bkg-white pall-15px form-box-list">
		<?php if (isset($item->positions['Section4SubTitle1'])) : ?>
			<?php foreach ($item->positions['Section4SubTitle1'] as $field) : ?>
				<h3 style="margin-top:0"><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4File1'])) : ?>
			<?php foreach ($item->positions['Section4File1'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4SubTitle2'])) : ?>
			<?php foreach ($item->positions['Section4SubTitle2'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4File2'])) : ?>
			<?php foreach ($item->positions['Section4File2'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4SubTitle3'])) : ?>
			<?php foreach ($item->positions['Section4SubTitle3'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4File3'])) : ?>
			<?php foreach ($item->positions['Section4File3'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4SubTitle4'])) : ?>
			<?php foreach ($item->positions['Section4SubTitle4'] as $field) : ?>
				<h3><?php echo $field->display; ?></h3>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['Section4File4'])) : ?>
			<?php foreach ($item->positions['Section4File4'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div><!-- end of bkg-white pall-15px -->
<?php endif; ?>

<?php } /* EOF if html5  */ ?>
