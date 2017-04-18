<?php
/**

 * $Id: default.php 11917 2009-05-29 19:37:05Z ian $

 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.modal');

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
?>
<div class="com_cta">
	<h2>Health Tips List:</h2>
	<ul class="vid_lst_cusitem">
		<?php foreach ($this->selected_cusitem as $cusitem):
		?>
			<li>
				<div class="">
					<div class=""><a href="<?php echo $this->baseurl.'/media/com_cta/'.$cusitem->file_name;?>" target="_blank" title="view"><?php echo $cusitem->title;?></a></div>
				</div><!--ctavidbox-->
			</li>
		<?php endforeach;?>
	</ul><!--vid_lst-->
	<div class="clr"></div>
</div><!--com_cta-->