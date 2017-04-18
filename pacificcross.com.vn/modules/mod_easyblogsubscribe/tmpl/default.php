<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');
?>

<?php 
  JPluginHelper::importPlugin('captcha');
   $dispatcher = JDispatcher::getInstance();
   $dispatcher->trigger('onInit','dynamic_recaptcha_1');
?>


<div id="fd" class="eb eb-mod mod_easyblogsubscribe<?php echo $params->get('moduleclass_sfx'); ?>" style="    border: 1px solid #0750a4;    padding: 10px;    border-radius: 8px;">
<?php if ($params->get('type' , 'link') == 'link') { ?>
	<a href="javascript:void(0);" data-blog-subscribe data-type="site" class="btn btn-primary btn-block"><?php echo JText::_('MOD_SUBSCRIBE_MESSAGE');?></a>
<?php } else { ?>
	<form name="subscribe-blog" id="subscribe-blog-module" method="post" class="eb-mod-form">
		<div class="eb-mod-form-item form-group">
			
			<input type="text" name="esfullname" placeholder="<?php echo JText::_('MOD_EASYBLOGSUBSCRIBE_YOUR_NAME'); ?>"   class="form-control" id="eb-subscribe-fullname" data-eb-subscribe-name />
		</div>
		<div class="eb-mod-form-item form-group">
			
			<input type="text" name="email" placeholder="<?php echo JText::_('MOD_EASYBLOGSUBSCRIBE_YOUR_EMAIL'); ?>"  class="form-control" id="eb-subscribe-email" data-eb-subscribe-mail />
		</div>
		<style>
		
			#dynamic_recaptcha_1 {
			       transform: scale(0.81);
    margin-left: -24px;
    margin-top: -16px;
   
			
			}
		
		</style>	
		
		 <p><div id="dynamic_recaptcha_1"></div></p><br />
		<div class="eb-mod-form-action">
			<a href="javascript:void(0);" style="    width: 100%;     margin-top: -24px;  "  class="btn btn-primary" data-subscribe-link><?php echo JText::_('MOD_EASYBLOGSUBSCRIBE_SUBSCRIBE_BUTTON');?></a>
		</div>
	</form>

	<script type="text/javascript">
	EasyBlog.ready(function($){

		$('[data-subscribe-link]').on("click", function() {
			
			
			 var v = grecaptcha.getResponse();
			if(v.length == 0)
			{          
				jQuery(".alertperson").remove();
				<?php if (strtolower(JFactory::getLanguage()->getTag()) == 'vi-vn' ){ ?>
				jQuery("<span class='alertperson' style='color:red;' > Vui lòng chọn ô bên trên</span>").insertAfter("#dynamic_recaptcha_1");        
				<?php } else { ?>
				jQuery("<span class='alertperson' style='color:red;' > Please tick captcha box to proceed </span>").insertAfter("#dynamic_recaptcha_1");     
				<?php } ?>
			 
			   return false;
			}
			jQuery(".alertperson").remove();
			grecaptcha.reset(); 
			
			var type = "site";
			var name = $('[data-eb-subscribe-name]').val();
			var mail = $('[data-eb-subscribe-mail]').val();

			EasyBlog.dialog({
				content: EasyBlog.ajax('site/views/subscription/subscribe', {
							"type":"site", 
							"name": name, 
							"email": mail
						})
			});
			
			jQuery('#eb-subscribe-email').val('');jQuery('#eb-subscribe-fullname').val('');
			<?php if (strtolower(JFactory::getLanguage()->getTag()) == 'vi-vn' ){ ?>
			
			setTimeout(function(){  jQuery('<div class="col-cell cell-tight eb-dialog-close-button"><button>Đóng</button></div>').insertAfter('.hint-failed'); } , 2000);
			<?php } else { ?>
			setTimeout(function(){  jQuery('<div class="col-cell cell-tight eb-dialog-close-button"><button>Close</button></div>').insertAfter('.hint-failed'); } , 2000);
			<?php } ?>
			

		});

	});
	</script>
<?php } ?>
</div>
