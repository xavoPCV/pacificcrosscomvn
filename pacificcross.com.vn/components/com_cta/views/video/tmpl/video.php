<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework');

if ($this->modal) JHtml::_('behavior.modal');


$this->document->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
$this->document->addScript($this->baseurl.'/components/com_cta/assets/function.js');
$this->document->addScript($this->baseurl.'/components/com_cta/player/cbplayer/cbplayer.js');

if ($this->vidfile || $this->cusvidfile):
?>
<?php if ($this->modal):?><div style="display:none;"><?php endif;?>


<?php if ($this->video->title) echo "<h1>".$this->video->title."</h1>";?>

<div id="video_player_cont">
	<div id="mediaplayer">Loading player, please wait....</div>
	<script type="text/javascript">
	<!--
	function inIframe() {
		try {
			return window.self !== window.top;
		} catch (e) {
			return true;
		}
	}//func
	
	
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
		
		<?php if ($this->cusvidfile) :?>
		var hq_video_file = '<?php echo $this->cusvidfile;?>';
		<?php else :?>
		var hq_video_file = '<?php echo $this->params->get('VIDEO_BASE_URL').'/'.$this->vidfile;?>';
		<?php endif;?>
		var preview_img = '<?php if($this->vidimg) echo $this->params->get('IMG_BASE_URL').'/'.$this->vidimg;?>';
		
		
		var width = '100%';
		var height = '';
		
		if (inIframe()) {
			height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
			if (height) height = height - 0;
		}
		
	
		if (height) {
			
			//alert(height)
			
			jwplayer("mediaplayer").setup({
				flashplayer: cb_player_file,
				skin : cb_player_url+'/skins/glow/glow.xml',
				file: hq_video_file,
				image: preview_img,
				width: width,
				height: height,
				autostart : '1',
				'logo':{
					file : player_logo,
					link : '',
					margin : '8',
					position : 'top-right',
					timeout : '3',
					over :'1',
					out: '<?php echo number_format($this->setting->opacity/100, 1);?>',
					hide: 'false'
				}
			});
		} else {
			jwplayer("mediaplayer").setup({
				flashplayer: cb_player_file,
				skin : cb_player_url+'/skins/glow/glow.xml',
				file: hq_video_file,
				image: preview_img,
				width: width,
				/*
				width: '762px',
				height:'360px',
				*/
				autostart : '1',
				'logo':{
					file : player_logo,
					link : '',
					margin : '8',
					position : 'top-right',
					timeout : '3',
					over :'1',
					out: '<?php echo number_format($this->setting->opacity/100, 1);?>',
					hide: 'false'
				}
			});
		
		}//if
		
		
		
	}//if hasFlash
	-->
	</script>
</div><!--video_player_cont-->
<?php if ($this->modal):?></div><?php endif;?>

<?php if ($this->modal):?>
	<div style="text-align:center;"><a href="#video_player_cont" rel="{handler: 'clone', size: {x: 770, y: 400}}" class="modal">View Video</a></div>
<script type="text/javascript">
<!--
window.addEvent('domready', function(){
	SqueezeBox.open($('video_player_cont'), {handler: 'clone', size: {x: 770, y: 400}});
});
-->
</script>
<?php endif;?>
<?php endif;?>
