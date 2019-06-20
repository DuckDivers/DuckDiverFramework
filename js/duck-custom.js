(function($) {
"use strict";
	$('ul.nav li.dropdown').hover(function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
	}, function() {
	  $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
	});
	

// ---------------------------------------------------------
// Back to Top
// ---------------------------------------------------------
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 100) {
			jQuery('#back-top').fadeIn('fast', function(){
				clearTimeout(jQuery.data(this, 'scrollTimer'));
    				jQuery.data(this, 'scrollTimer', setTimeout(function() {
       		 jQuery('#back-top').delay(2000).fadeOut('slow');// do something after 2s
	    		}, 2000));
			});
		} else {
			jQuery('#back-top').fadeOut();
		}
	});
	jQuery('#back-top a').click(function () {
		jQuery('body,html').stop(false, false).animate({
			scrollTop: 0
		}, 800);
		return false;
	});

// WooCommerce 
// Magnific Popup Init
$(document).ready(function() {	 // Document Ready
	$('.duck-lightbox, .attachment-shop_single').parent().addClass('image-popup-fit-width');

		$('.image-popup-fit-width').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			mainClass: 'mfp-fade',
			gallery:{
				enabled: true,
				navigateByImgClick: true,
				preload: [0,0]
			},
			image: {
				verticalFit: false
			},
			zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			}
			});
	});

	//Dropdown cart in header
		$('.cart-holder > h3').click(function(){
			if($(this).hasClass('cart-opened')) {
				$(this).removeClass('cart-opened').next().slideUp(300);
			} else {
				$(this).addClass('cart-opened').next().slideDown(300);
			}
		});
    	//Fix contact form not valid messages errors
        jQuery('.wpcf7-not-valid-tip').on('mouseover', function(){
            jQuery(this).fadeOut();
            jQuery('.wpcf7-not-valid').removeClass('.wpcf7-not-valid');
        });

        jQuery('.wpcf7-form input[type="reset"]').on('click', function(){
            jQuery('.wpcf7-not-valid-tip, .wpcf7-response-output').fadeOut();
        });					
})( jQuery );
// Prevent Multiple Clicks on Contact Form 7 Submissions
jQuery(document).on('click', '.wpcf7-submit', function(e){
     if( jQuery('.ajax-loader').hasClass('is-active') ) {
          e.preventDefault();
          return false;
     }
});
