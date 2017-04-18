<?php
/**

 * $Id: default.php 11917 2009-05-29 19:37:05Z ian $

 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
$doc->addScript($this->baseurl.'/components/com_cta/assets/function.js');
$doc->addScript($this->baseurl.'/components/com_cta/player/cbplayer/cbplayer.js');
#echo $this->vid;
?>
<div class="com_cta">
	<h2 class="video_title"><?php echo $this->video['Title'];?></h2>
	<div id="video_player_cont">
		<div id="mediaplayer">Loading player, please wait....</div>
		<script type="text/javascript">
		<!--
		var hasFlash = detectFlashPlayer();
		if (!hasFlash){
			$("mediaplayer").set('html','Flash player must be installed!');
			$("mediaplayer").setStyle('color', 'red');
			$("mediaplayer").setStyle('text-align','center');
			$("mediaplayer").setStyle('font-size','22px');
		} else {
			var cb_player_file = '<?php echo $this->baseurl;?>/components/com_cta/player/cbplayer/player.swf';
			var cb_player_url = '<?php echo $this->baseurl;?>/components/com_cta/player/cbplayer';
			var player_logo = '<?php echo $this->baseurl;?>/components/com_cta/logo/<?php echo $this->setting->watermark_logo;?>';
			var hq_video_file = '<?php echo $this->params->get('VIDEO_BASE_URL').'/'.$this->video['VideoFile'];?>';
			var preview_img = '<?php echo $this->params->get('IMG_BASE_URL').'/'.$this->video['ImgCTA'];?>';
			jwplayer("mediaplayer").setup({
				flashplayer: cb_player_file,
				skin : cb_player_url+'/skins/glow/glow.xml',
				file: hq_video_file,
				image: preview_img,
				width: '762px',
				height:'360px',
				autostart : '1',
				'logo':{
					file : player_logo,
					link : '',
					margin : '8',
					position : 'top-right',
					timeout : '3',
					over :'1',
					out :'0.5',
					hide: 'false'
				}
			});
		}//if hasFlash
		-->
		</script>
	</div><!--video_player_cont-->
	<ul class="vid_lst">
		<?php foreach ($this->videos as $video_row):?>
			<?php if ( $video_row['VideoId']!=$this->vid ):?>
			<li><a href="<?php echo JRoute::_('index.php?option=com_cta&view=video&vid='.$video_row['VideoId']);?>"><?php echo $video_row['Title'];?></a></li>
			<?php endif;?>
		<?php endforeach;?>
	</ul><!--vid_lst-->
</div><!--com_cta-->