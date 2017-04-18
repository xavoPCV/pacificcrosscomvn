<?php
/**

 * $Id: default.php 11917 2009-05-29 19:37:05Z ian $

 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.modal');

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
?>
<script type="text/javascript">
function openPopupWin(url) {
	var myWindow = window.open(url,"","width=820,height=400");
	myWindow.focus();
}
</script>
<div class="com_cta">
	<h2>Reports List:</h2>
	<ul class="vid_lst">
		<?php foreach ($this->videos as $video_row):
				$video_link = JRoute::_('index.php?option=com_cta&view=video&layout=video&vidfile='.$video_row['VideoFile'].'&vidimg='.$video_row['ImgCTA']);
				if ($video_row['ImgCTA'])
					$img_thumb = $this->params->get('IMG_BASE_URL').'/'.$video_row['ImgCTA'];
				else
					$img_thumb = $this->baseurl.'/components/com_cta/assets/no_image.png';
		?>
			<li>
				<div class="ctavidbox">
					<div class="vidthumb"><a href="javascript:void(0);<?php #echo $video_link;?>" onclick="openPopupWin('<?php echo $video_link;?>');" class="modal2" rel="{handler: 'iframe', size: {x:820, y:400}}"><img src="<?php echo $img_thumb;?>" border="0" align="middle" class="videothumb"></a></div>
					<div class="vidlink"><a href="javascript:void(0);<?php #echo $video_link;?>" onclick="openPopupWin('<?php echo $video_link;?>');" class="modal2" rel="{handler: 'iframe', size: {x:820, y:400}}"><?php echo $video_row['Title'];?></a></div>
				</div><!--ctavidbox-->
			</li>
		<?php endforeach;?>
	</ul><!--vid_lst-->
	<div class="clr"></div>
</div><!--com_cta-->