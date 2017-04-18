<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.framework', true);

$ctaItemid = 0;


$menu = JSite::getMenu();
$items	= $menu->getItems('component', 'com_cta');
$Itemid = NULL;
for ($i = 0, $n = count($items); $i < $n; $i++) {
	if ($items[$i]->query['view']=='form') {
		$ctaItemid = $items[$i]->id;
		break;
	}//if
}//for
?>
<div class="mod_ctareport<?php echo $params->get('moduleclass_sfx'); ?>" id="modctareport<?php echo $module->id; ?>" data_class="<?php echo $params->get('moduleclass_sfx'); ?>" data_type="0" data_id="<?php echo $module->id ?>">

    <?php if ( (count($videos) > 1 || count($cusitems) > 1) && !$params->get('custom_pic', NULL)  ): ?>
        <script type="text/javascript">
		window.addEvent('domready', function() {
			/* settings */
			var showDuration = 2000;
			var container = $('slideshow-container<?php echo $module->id; ?>');
			var divs = container.getElements('dt');
			var currentIndex = 0;
			var showIndex = 0;
			var interval;
			divs.each(function(div, i) {
				if (i > 0) {
					div.set('opacity', 0);
				}
			});
			var show = function() {
				divs[currentIndex].fade('out');
				divs[currentIndex = currentIndex < divs.length - 1 ? currentIndex + 1 : 0].fade('in');
			};
			//window.addEvent('load',function(){
			interval = show.periodical(showDuration);
			//});
		});
		
		
		
        </script>
    <?php endif; ?>
    
        
		<?php
		if ($params->get('custom_pic', NULL)) {
?>
<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo $baseurl;?>/modules/mod_ctareport/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $baseurl;?>/modules/mod_ctareport/assets/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

<script>
jQuery(document).ready(function ($) {
	
	$('#redirectOutput<?php echo $module->id; ?>').on("click", function (e) {
    
		e.preventDefault();
		
		$.fancybox({ 
			"type": 'iframe',
			'autoSize': false,
			'width':600, 
			'height':200,
			'href':'<?php echo JRoute::_('index.php?option=com_cta&view=form'.($params->get('popup_style', 0)?'&tmpl=component':'').'&Itemid='.$ctaItemid); ?>'+'&'+$("#adminForm118").serialize()
		});
	
	});	
	
});
function redirectOutput() {
	var myForm = document.getElementById('adminForm<?php echo $module->id; ?>');
	
	console.log(myForm);
	
	<?php if ($params->get('popup_style', 0)):?>
	var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=800,height=600');
	myForm.target = 'Popup_Window';
	<?php endif;?>
	
	myForm.submit();
	
}//func
</script>		
		<form action="<?php echo JRoute::_('index.php?option=com_cta&view=form'.($params->get('popup_style', 0)?'&tmpl=component':'').'&Itemid='.$ctaItemid); ?>" name="adminForm222" id="adminForm<?php echo $module->id; ?>" class="cta_report_form form-validate333" method="post" <?php if ($params->get('popup_style', 0)):?>target="_blank"<?php endif;?>>
		
		<div class="ctacuspic"><a href="javascript:void(0);" id="redirectOutput<?php echo $module->id;?>"><img src="<?php echo $params->get('custom_pic');?>" border="0" /></a></div>
		
		<?php foreach ($videos as $video_row): ?>
			<input type="hidden" name="video_id[]" value="<?php echo $video_row['VideoId'] . '|' . htmlspecialchars($video_row['Title']); ?>" />
		<?php endforeach; ?>	

		<?php foreach ($cusitems as $cusitem): ?>
			<input type="hidden" name="cusitem_id[]" value="<?php echo $cusitem->id; ?>" />
		<?php endforeach; ?>
		
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="view" value="form" />
			<input type="hidden" name="option" value="com_cta" />
			<input type="hidden" name="page_referer" value="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $ctaItemid;?>" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
<?php		
		} else {
		?>
		<form action="<?php echo JRoute::_('index.php?option=com_cta&view=form&Itemid='.$ctaItemid); ?>" name="adminForm333" id="adminForm<?php echo $module->id; ?>" class="cta_report_form form-validate333" method="post">
		<div class="ctalegend"><?php echo $module->title; ?></div>
        <ul>
            <li>
                <dl class="<?php echo (count($videos) > 1 || count($cusitems) > 1) ? 'slideshow-container' : 'onevid-container'; ?>" id="slideshow-container<?php echo $module->id; ?>">
                    <?php foreach ($videos as $video_row): ?>
                        <dt><?php echo $video_row['Title']; ?></dt>
                        <input type="hidden" name="video_id[]" value="<?php echo $video_row['VideoId'] . '|' . htmlspecialchars($video_row['Title']); ?>" />
                    <?php endforeach; ?>	

                    <?php foreach ($cusitems as $cusitem): ?>
                        <dt><?php echo $cusitem->title; ?></dt>
                        <input type="hidden" name="cusitem_id[]" value="<?php echo $cusitem->id; ?>" />
                    <?php endforeach; ?>	

                </dl>
            </li>
            <?php if (count($videos) || count($cusitems)): ?>
                <li><button type="submit" class="ctabutton" onclick="Joomla.submitbutton('')">Sign In</button></li>
            <?php endif; ?>
        </ul>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="view" value="form" />
			<input type="hidden" name="option" value="com_cta" />
			<input type="hidden" name="page_referer" value="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" />
			<input type="hidden" name="Itemid" value="<?php echo $ctaItemid;?>" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
		<?php
		}//if
		?>
		
		
       
    <div class="clr"></div>
</div><!--mod_ctareport-->