<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$doc = JFactory::getDocument();
$doc->addStyleSheet('https://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css');
?>

<script>
//jQuery.noConflict();
window.fbpvars = {
	token: "c461de72efd6adb9069ee95100423535",
	site_path: "http://joomla-extensions.minitek.gr/joomla-extensions-free/",
	page_view: "section",
	page_title: "Documentation",
	sectionId: "1",
	section_link: "/joomla-extensions-free/faq-book/documentation",
	leftnav: "1",
	
	thank_you_up: "Thank you for the feedback.",
	thank_you_down: "Thank you for the feedback.",
	already_voted: "You have already voted for this question.",
	why_not: "Why not?",
	incorrect_info: "It contains incorrect info",
	dont_like: "I don't like the answer",
	confusing: "It's confusing",
	not_answer: "It doesn't answer my question",
	too_much: "It's too much to read",
	other: "Other",
	error_voting: "There was an error while saving. Please try again later."
};
jQuery(document).ready(function($){
	//alert(2)
	
	$('a.faq_question').click(function(e) {
		e.preventDefault();
		var ansdiv = $(this).attr('href');
		$(ansdiv+'_liid').html($(ansdiv).html());
		
		var lheight = parseInt($(ansdiv+'_liid').height()) + 40;//for back
		
		jQuery('.fbpLeftNavigation_root').css({"height":lheight+"px"});
		
	});
});
</script>
<script src="<?php echo $baseurl = JURI::root(false);?>modules/mod_faq_categories/assets/js/faqbook.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $baseurl;?>modules/mod_faq_categories/assets/css-dd076.css" type="text/css" />
<link rel="stylesheet" href="" type="text/css" />
<style>
.answer_placeholder iframe {
	max-width:100% !important;
}
</style>
<!--<ul class="categories-module<?php echo $moduleclass_sfx; ?>" style="margin-bottom:20px"></ul>-->
<div class="fbpLeftNavigation_core">
    <div class="fbpLeftNavigation_root">
        <div id="fbp_l_n" class="fbpLeftNavigation_wrap">
            <ul id="NavLeftUL" class="NavLeftUL_parent">
<?php
require JModuleHelper::getLayoutPath('mod_faq_categories', $params->get('layout', 'default').'_items');
?>
            </ul>
        </div>
    </div>
</div>
<?php
if (count($answer_array)) {
	foreach ($answer_array as $answer_id => $faq_answer) {
	?>
	<div style="display:none;">
		<div id="answer<?php echo $answer_id;?>">
			<?php echo $faq_answer;?>
		</div>
	</div>
	<?php
	}//for
}//if
