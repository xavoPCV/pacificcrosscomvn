jQuery.noConflict();

(function($) {
	$(document).ready(function() {
		// IE10 viewport hack for Surface/desktop Windows 8 bug
	    if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
	      var msViewportStyle = document.createElement('style');
	      msViewportStyle.appendChild(
	        document.createTextNode(
	          '@-ms-viewport{width:auto!important}'
	        )
	      );
	      document.getElementsByTagName('head')[0].
	        appendChild(msViewportStyle);
	    };

		// main nav
		$('#nav li:has(ul)').doubleTapToGo();
		
		// footable script
		$('.footable').footable({
			breakpoints: {
			  phone: 704,
			  tablet: 1024
			}
		});

		// search toggle
		$('#search-button').click(function(){
		    $('#search-bar').slideToggle();
		    if ($('#search-field').is(':focus'))
		       {
		         $('#search-field').blur();
		       }else {
		       	$('#search-field').focus();
		       }
		       return false;
		});
		// end of search toggle

		// home page 10 reasons box toggle
		$( ".ten-reasons-btn" ).click(function(event) {
		  // $( ".box1" ).slideToggle( "slow" );
		  $(this).next().slideToggle( "slow" );
		  $(this).parent().toggleClass("box-active")
		  event.preventDefault();
		});

		// sticky nav
		var sticky = new Waypoint.Sticky({
		  element: $('.main-nav-wrapper')[0]
		});

		$('.free-quote-button').waypoint(function() {
	        $(this.element).addClass('visible');
	    });

	    // responsive slide bar
		$.slidebars();
					
	    // Stop submenu toggle from closing Slidebars.
	    $('#sb-slidebar-nav .sb-toggle-submenu').off('click')
	        .on('click', function() {
	            $submenu = $(this).parent().children('.sb-submenu');
	            $(this).add($submenu).toggleClass('sb-submenu-active'); // Toggle active class.
	            
	            if ($submenu.hasClass('sb-submenu-active')) {
	            	$('#sb-slidebar-nav .sb-toggle-submenu i').toggleClass('ion-ios-arrow-up ion-ios-arrow-down');
	                $submenu.slideDown(200);
	            } else {
	            	$('#sb-slidebar-nav .sb-toggle-submenu i').toggleClass('ion-ios-arrow-up ion-ios-arrow-down');
	                $submenu.slideUp(200);
	            };
	            return false;
	        });
	    // add caret to parent link
	    $('#sb-slidebar-nav .sb-toggle-submenu').append(' <i class="ion ion-ios-arrow-down"></i>');
	    // end of responsive slide bar

	    // FAQ script
		// $(".faq-item-content").hide();   
		// $( ".faq-item-title" ).click(function() {
		//     $(this).next().slideToggle("slow");
		//     $(this).toggleClass('faq-item-active');
		// });

		$( ".faq-item-title" ).click(function(event) {
		  // $( ".box1" ).slideToggle( "slow" );
		  $(this).next().slideToggle( "slow" );
		  $(this).parent().toggleClass("faq-item-active")
		  event.preventDefault();
		});
		// end of FAQ script


		// filter funtion for gollsary
	    $("#filter").keyup(function(){
	        // Retrieve the input field text and reset the count to zero
	        var filter = $(this).val(), count = 0;
	        // Loop through the comment list
	        $(".glossary-list li").each(function(){
	            // If the list item does not contain the text phrase fade it out
	            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
	                $(this).fadeOut();
	            // Show the list item if the phrase matches and increase the count by 1
	            } else {
	                $(this).show();
	                count++;
	            }
	        });
	        // Update the count
	        var numberItems = count;
	        $("#filter-count").text(count+" item(s) found");
	    });
	    // disable enter key
	    $("#glossary-search :input").on("keypress", function(e) {
		    return e.keyCode != 13;
		});
	    // end of filter funtion for gollsary

		// floating labels
		if( $('.floating-labels').length > 0 ) floatLabels();
		function floatLabels() {
			var inputFields = $('.floating-labels .cd-label').next();
			inputFields.each(function(){
				var singleInput = $(this);
				//check if user is filling one of the form fields 
				checkVal(singleInput);
				singleInput.on('change keyup', function(){
					checkVal(singleInput);	
				});
			});
		};

		function checkVal(inputField) {
			( inputField.val() == '' ) ? inputField.prev('.cd-label').removeClass('float') : inputField.prev('.cd-label').addClass('float');
		};

		$('#currency-usd').change(function(){
		    $('#currency-warning').toggle(this.checked); 
		});

		// home page gallery
		// $('.gallery-nav').flickity({
		//   asNavFor: '.gallery-main',
		//   contain: true,
		//   pageDots: false
		// });

	});// end of $(document).ready(function(){...
}) (jQuery);