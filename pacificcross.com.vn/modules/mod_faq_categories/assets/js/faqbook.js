(function($) {
	$(function(){  
		
		var token = window.fbpvars.token + "=1";
		var site_path = window.fbpvars.site_path;
		var page_view = window.fbpvars.page_view;
		var page_title = window.fbpvars.page_title;		
		var sectionId = window.fbpvars.sectionId;
		var encoded_section_link = window.fbpvars.section_link;
		var section_link = encoded_section_link.replace(/&amp;/g, '&');		
		var topicId = window.fbpvars.topicId;
		//var Itemid = window.fbpvars.Itemid;
		var leftnav = window.fbpvars.leftnav;
		var ajax_request;
	
		var title = window.fbpvars.title;
		var url = window.location.href;
		var captcha_key = window.fbpvars.captcha_key;
		// Text
		var thank_you_up = window.fbpvars.thank_you_up;
		var thank_you_down = window.fbpvars.thank_you_down;
		var already_voted = window.fbpvars.already_voted;
		var why_not = window.fbpvars.why_not;
		var incorrect_info = window.fbpvars.incorrect_info;
		var dont_like = window.fbpvars.dont_like;
		var confusing = window.fbpvars.confusing;
		var not_answer = window.fbpvars.not_answer;
		var too_much = window.fbpvars.too_much;
		var other = window.fbpvars.other;
		var error_voting = window.fbpvars.error_voting;
		
		jQuery(window).load(function () {
		  
			var page_view = window.fbpvars.page_view;
			var topicId = window.fbpvars.topicId;
			
			// Fix leftnav height on window load
			if (page_view == 'topic' || page_view == 'question') 
			{
				var parent_ul = jQuery('#liid'+topicId).parent();
				var vheight = jQuery(parent_ul).height();
				jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});
			} 
			else if (page_view == 'section') 
			{
				var vheight = jQuery('.NavLeftUL_parent').height();
				jQuery('.fbpLeftNavigation_root').css({"height":vheight+"px"});
			}
			
			// Hide Leftnav in mobile
			if (page_view == 'topic' || page_view == 'question') 
			{
				// Add 'show menu' button
				jQuery('.show_menu').addClass('fbp-shown');	
				
				jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');
			}
						
		});
	
		function setWidth() 
		{
			var fbp_width = jQuery("#fbpExtended").width();
			if (jQuery(document).width() < 768) 
			{
				jQuery('#NavLeftUL').css({"width":fbp_width+"px"});
				jQuery('ul.NavLeftUL_sublist').css({"left":"100%"});
			} 
			else 
			{
				//#HT px to %
				fbp_width = 420;
				jQuery('#NavLeftUL').css({"width":"420px"});
				jQuery('ul.NavLeftUL_sublist').css({"left":fbp_width+"px"});
			}
		};
		
		setWidth();
		
		jQuery(window).resize(setWidth);
				
		function loadHome()
		{ 
			jQuery('.show_menu').removeClass('fbp-shown');	
		  
			jQuery.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&view=section&id="+ sectionId +"&format=raw&" + token,
				beforeSend: function() {
					window.history.pushState({}, document.title, section_link); // change url dynamically
				},
				success: function(msg) {
					jQuery(".fbpTopNavigation_wrap").removeClass('NavTopULloading');  
					jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
					jQuery('.NavLeftUL_item').removeClass('li_selected'); 
					jQuery(document).attr('title', page_title); // change browser title dynamically
				}
			});
		}
		
		// Topic & Question view
		if (page_view == 'topic' || page_view == 'question') 
		{
			// Active leftnav li
			jQuery('#liid'+topicId).addClass('li_selected');   
			
			// Active leftnav ul
			jQuery('#liid'+topicId).parents('ul.NavLeftUL_sublist').addClass('NavLeftUL_expanded');   
			var parent_ul_class = jQuery('#liid'+topicId).parent('ul').attr('class');
			if (leftnav > 0) 
			{
				if (parent_ul_class != 'NavLeftUL_parent') 
				{
					var ul_level = parent_ul_class.split(" ")[1];
					var ul_level_num = ul_level.substring(ul_level.lastIndexOf('level') + 5);
					var move_ul = parseInt(ul_level_num)*100;
					jQuery('.fbpLeftNavigation_wrap').css({"left":"-"+move_ul+"%"});   	
				
					// Active topnav li
					var parents_num = parseInt(ul_level_num);
					var first_parent_text = jQuery('#liid'+topicId).parent().parent().find('> .NavLeftUL_anchor span.catTitle').text();
					var first_parent_id = jQuery('#liid'+topicId).parent('ul').parent('li').attr('id');
					
					jQuery('.fbpTopNavigation_root li.NavTopUL_firstChild').removeClass('NavTopUL_lastChild');
				  
					// Add topnav li's   
					var $id = jQuery('#'+first_parent_id);
					var $li = jQuery('#'+first_parent_id);
					
					function findParents()
					{
						$id = $id.parent().parent();
						$li = $li.parent('ul').parent('li');
						var prev_parent_text = $id.find('> .NavLeftUL_anchor span.catTitle').text();     
						var prev_parent_id = $li.attr('id');
				
						jQuery('<li id="top_'+prev_parent_id+'" class="NavTopUL_item"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+prev_parent_text+'</a></li>').insertAfter('li.NavTopUL_firstChild');
					}
				 
					for (var i = 1; i < parents_num; i++) 
					{
						findParents();  
					}		
				  
					// Add lastChild li
					jQuery('.fbpTopNavigation_root').append(jQuery('<li id="top_'+first_parent_id+'" class="NavTopUL_item NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+first_parent_text+'</a></li>'));	
				}
			}
		}
		
		function loadEndpoint(id, this_liid, href_link, cat_title)
		{
			// Check if there is a pending ajax request
			if(typeof ajax_request !== "undefined")
				ajax_request.abort();
			
			jQuery('.NavLeftUL_endpoint').removeClass('li_loading'); 
							
			ajax_request = jQuery.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&view=topic&format=raw&id=" + id + "&" + token,
				beforeSend: function() {
					jQuery('#'+this_liid).addClass('li_loading'); 
					window.history.pushState({}, document.title, href_link);
				},
				success: function(msg) {
					// Show Leftnav again in case it is hidden
					jQuery('.fbpLeftNavigation_core').addClass('fbp-hidden');	
					
					// Add 'show menu' button
					jQuery('.show_menu').addClass('fbp-shown');	
			
					jQuery('#'+this_liid).removeClass('li_loading'); 
					jQuery('.NavLeftUL_item').removeClass('li_selected'); 
					jQuery('#'+this_liid).addClass('li_selected'); 
					jQuery(".fbpContent_root").hide().html(msg).fadeIn('fast');
					jQuery(document).attr('title', cat_title); // change browser title dynamically
													
					// Toggle FAQ in Category
					jQuery('.topic_faqToggleLink').on('click', function(event, faq_id)
					{	
						event.preventDefault;
						var this_faqid = jQuery(this).attr('id');
						var faq_id = this_faqid.split("_").pop(0);
				
						jQuery('#a_w_'+faq_id+' .topic_faqAnswerWrapper_inner').toggle();
						if (jQuery('#faq_'+faq_id).hasClass('faq_open')) {
							jQuery('#faq_'+faq_id).removeClass('faq_open');
						} else {
							jQuery('#faq_'+faq_id).addClass('faq_open');
							// Hits script
							addHit(faq_id, event);
						}
					});
				}
			});
		}
		
		// Add class 'expanded' to 1st child ul / Move wrap to the left
		jQuery('#NavLeftUL li.NavLeftUL_item').each(function(this_liid) 
		{ 
			var this_liid = this['id'];
			
			jQuery('#'+this_liid).find('a:first').on('click', function(event) {
				event.preventDefault;
		
				var this_liclass = jQuery(this).parent('li').attr('class');
				
				if (this_liclass == 'NavLeftUL_item NavLeftUL_endpoint' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint li_selected') 
				{
				} 
				else 
				{
					// Front - Get ul height
					var parent_li = jQuery(this).parent(); 
					var child_ul = jQuery(parent_li).find('ul:first'); 
					var eheight = jQuery(child_ul).height();
					jQuery('.fbpLeftNavigation_root').css({"height":eheight+"px"});
				}
				
				if (this_liclass === 'NavLeftUL_item' || this_liclass === 'NavLeftUL_item li_selected')
				{		
					if (jQuery('.fbpLeftNavigation_wrap:animated').length == 0) // Keep track of animation to prevent double clicks
					{ 
						jQuery('#'+this_liid).find('ul:first').addClass('NavLeftUL_expanded');	
						var lefty = jQuery('.fbpLeftNavigation_wrap');
						lefty.animate(
							{left:"-=100%"},
							{queue: true, complete: function(){ 
													   
								jQuery('.fbpTopNavigation_root li').removeClass('NavTopUL_lastChild');
								var this_title = jQuery('#'+this_liid).find('a:first').text();						
								jQuery('.fbpTopNavigation_root').append(jQuery('<li id="top_'+this_liid+'" class="NavTopUL_item NavTopUL_lastChild"><i class="fa fa-caret-right"></i>&nbsp;&nbsp;<a class="NavTopUL_link" href="#" onclick="return false;">'+this_title+'</a></li>'));		
							} 
						});
					}
				}
				/*if (this_liclass === 'NavLeftUL_item NavLeftUL_endpoint' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint firstItem' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint lastItem'
				|| this_liclass === 'NavLeftUL_item NavLeftUL_endpoint li_selected' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint li_selected firstItem' || this_liclass == 'NavLeftUL_item NavLeftUL_endpoint li_selected lastItem'
				)
				{*/
					var endpoint_liid = jQuery(this).parent('li').attr('id');
					var endpoint_id = endpoint_liid.split("id").pop(1);
					
					var href_link = jQuery(this).attr('href');
					var cat_title = jQuery(this).text();
						
						
					//alert(1);
					//loadEndpoint(endpoint_id, this_liid, href_link, cat_title);
				/*}*/
			});
		});
		
		// Front - Get ul height
		jQuery('.NavLeftUL_backItem').on('click', function(event) {	
			event.preventDefault;
			var back_child_ul = jQuery(this).parent().parent().parent();
			var wheight = jQuery(back_child_ul).height();
			jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});
			
			//terminate answer_placeholder
			$('.answer_placeholder').html('');
			
		});
		
		// Home button - Fix leftnav height
		jQuery('.NavTopUL_firstChild').on('click', function(event) {	
			event.preventDefault;
			var wheight = jQuery('.NavLeftUL_parent').height();
			jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});
		});
	  
		// Remove class 'expanded' from 1st parent ul / Move wrap to the right
		jQuery('#NavLeftUL li.NavLeftUL_backItem').each(function(this_backliid) { 
			var this_backliid = this['id'];
			
			jQuery('#'+this_backliid).find('a:first').click(function(event) {		
				event.preventDefault;
				var righty = jQuery('.fbpLeftNavigation_wrap');		
				if (jQuery('.fbpLeftNavigation_wrap:animated').length == 0) // Keep track of animation to prevent double clicks
				{
					righty.animate(
						{left:"+=100%"}, 
						{queue: false, complete: function(){ 
					  
							jQuery('#'+this_backliid).parent('ul').removeClass('NavLeftUL_expanded'); 		
							jQuery('.fbpTopNavigation_root li:last').remove();
							jQuery('.fbpTopNavigation_root li:last').addClass('NavTopUL_lastChild');	
						} 
					});
				}
			});
		});
		
		// Top Navigation
		jQuery('.fbpTopNavigation_root').on('click', 'li', function(event, this_liclass) { 
			event.preventDefault;
			var this_liclass = jQuery(this).attr('class');
			var this_liid = jQuery(this).attr('id');
			
			// Show Leftnav again in case it is hidden
			jQuery('.fbpLeftNavigation_core').removeClass('fbp-hidden');	
				
			// Fix leftnav height
			if (this_liclass == 'NavTopUL_item')
			{	
				var this_id = this_liid.split("liid").pop(0);
				var left_child_ul = jQuery('#liid'+this_id).find('ul:first');
				var lheight = jQuery(left_child_ul).height();
				jQuery('.fbpLeftNavigation_root').css({"height":lheight+"px"});
			}		
			if (this_liclass == 'NavTopUL_item NavTopUL_firstChild')
			{
				var wheight = jQuery('.NavLeftUL_parent').height();
				jQuery('.fbpLeftNavigation_root').css({"height":wheight+"px"});	
			}
			
			if (this_liclass == 'NavTopUL_item NavTopUL_firstChild' || this_liclass == 'NavTopUL_item')
			{		
				var li_count = jQuery('.fbpTopNavigation_root li').length;			
				var li_index = jQuery(this).index();	
				var slide_count = parseInt(li_count) - parseInt(li_index) - 1;			
			  
				var righty = jQuery('.fbpLeftNavigation_wrap');		
				var move_right = slide_count * 100;
				if (jQuery('.fbpLeftNavigation_wrap:animated').length == 0) // Keep track of animation to prevent double clicks
				{ 
					if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
					{
					  jQuery(".fbpTopNavigation_wrap").addClass('NavTopULloading');  
					}
					righty.animate(
						{left:"+="+move_right+"%"}, 
						{queue: false, complete: function(){ 
						
							if (this_liclass.indexOf("NavTopUL_firstChild") !== -1)
							{
								loadHome();
							}
				
							var this_id = this_liid.split("_").pop(0);
							if (this_id === 'home') 
							{
								jQuery('#NavLeftUL ul').removeClass('NavLeftUL_expanded');
							} 
							else 
							{
								jQuery('#'+this_id+' ul ul').removeClass('NavLeftUL_expanded');
							}	
							for (var i = 0; i < slide_count; i++) {
								jQuery('.fbpTopNavigation_root li:last').remove();
								jQuery('.fbpTopNavigation_root li:last').addClass('NavTopUL_lastChild');	
							}
		
						} 
					});
				}
			}
			if (this_liclass == 'NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild')
			{	
				jQuery(".fbpTopNavigation_wrap").addClass('NavTopULloading');  
				loadHome();
			}
						
		});
		
		// Toggle FAQ in Category
		jQuery('.topic_faqToggleLink').on('click', function(event, faq_id) {	
			event.preventDefault;
			var this_faqid = jQuery(this).attr('id');
			var faq_id = this_faqid.split("_").pop(0);
			
			jQuery('#a_w_'+faq_id+' .topic_faqAnswerWrapper_inner').toggle();
			if (jQuery('#faq_'+faq_id).hasClass('faq_open')) {
				jQuery('#faq_'+faq_id).removeClass('faq_open');
			} else {
				jQuery('#faq_'+faq_id).addClass('faq_open');
				// Hits script
				addHit(faq_id, event);
			}
		});
												
		function addHit(faq_id, event) 
		{
			jQuery.ajax({
				type: "POST",
				url: site_path+"index.php?option=com_faqbookpro&task=question.addHit&id=" + faq_id + "&" + token,
				beforeSend: function() {},
				success: function(msg) {}
			});  
		}
				
		// Hide Show menu button / Show leftnav
		var show_leftnav = jQuery('.show_menu').on('click', function(event) {			
		event.preventDefault;
			jQuery('.show_menu').removeClass('fbp-shown');
			jQuery('.fbpLeftNavigation_core').removeClass('fbp-hidden');
		});
	
	})
})(jQuery);