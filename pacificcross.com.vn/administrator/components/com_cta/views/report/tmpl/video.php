<?php
// no direct access
defined('_JEXEC') or die;


$root_url = JURI::root(true);


$doc = JFactory::getDocument();
$doc->addScript($root_url.'/components/com_cta/assets/function.js');
$doc->addScript($root_url.'/components/com_cta/player/cbplayer/cbplayer.js');


$vidfile = JRequest::getVar('vidfile');
$vidimg = JRequest::getVar('vidimg');

if ($vidfile):
?>
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
		var cb_player_file = '<?php echo $root_url;?>/components/com_cta/player/cbplayer/player.swf';
		var cb_player_url = '<?php echo $root_url;?>/components/com_cta/player/cbplayer';
		//var player_logo = '<?php echo $root_url;?>/components/com_cta/logo/<?php echo $this->setting->watermark_logo;?>';
		var player_logo = '';
		var hq_video_file = '<?php echo $this->params->get('VIDEO_BASE_URL').'/'.$vidfile;?>';
		var preview_img = '<?php if ($vidimg) echo $this->params->get('IMG_BASE_URL').'/'.$vidimg;?>';
		jwplayer("mediaplayer").setup({
			flashplayer: cb_player_file,
			skin : cb_player_url+'/skins/glow/glow.xml',
			file: hq_video_file,
			image: preview_img,
			width: '100%',
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
<?php endif;?>