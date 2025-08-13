(function($) {
	"use strict";
	$( 'ul.navbar-nav li.dropdown' ).on(
		'mouseenter',
		function() {
			$( this ).find( '.dropdown-menu' ).stop( true, true ).delay( 100 ).fadeIn( 500 );
		}
	).on(
		'mouseleave',
		function() {
			$( this ).find( '.dropdown-menu' ).stop( true, true ).delay( 100 ).fadeOut( 500 );
		}
	);

	// ---------------------------------------------------------
	// Back to Top
	// ---------------------------------------------------------
	jQuery( window ).on(
		'scroll',
		function () {
			if (jQuery( this ).scrollTop() > 100) {
				jQuery( '#back-top' ).fadeIn(
					'fast',
					function(){
						clearTimeout( jQuery.data( this, 'scrollTimer' ) );
						jQuery.data(
							this,
							'scrollTimer',
							setTimeout(
								function() {
									jQuery( '#back-top' ).delay( 2000 ).fadeOut( 'slow' );// do something after 2s.
								},
								2000
							)
						);
					}
				);
			} else {
				jQuery( '#back-top' ).fadeOut();
			}
		}
	);
	jQuery( '#back-top a' ).on(
		'click',
		function () {
			jQuery( 'body,html' ).stop( false, false ).animate(
				{
					scrollTop: 0
				},
				800
			);
			return false;
		}
	);

	// WooCommerce
	// Magnific Popup Init
	$(
		function() {
			$( '.duck-lightbox, .attachment-shop_single' ).parent().addClass( 'image-popup-fit-width' );

			$( '.image-popup-fit-width' ).magnificPopup(
				{
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
						duration: 300,
					}
				}
			);
		}
	);

	// Dropdown cart in header
	$( '.cart-holder' ).on(
		'click',
		function(){
			if ($( this ).hasClass( 'cart-opened' )) {
				$( this ).removeClass( 'cart-opened' );
				$( '.widget_shopping_cart_content' ).slideUp( 300 );
			} else {
				$( this ).addClass( 'cart-opened' );
				$( '.widget_shopping_cart_content' ).slideDown( 300 );
			}
		}
	);
})( jQuery );
// Prevent Multiple Clicks on Contact Form 7 Submissions
jQuery( document ).on(
	'click',
	'.wpcf7-submit',
	function(e){
		if ( jQuery( '.ajax-loader' ).hasClass( 'is-active' ) ) {
			 e.preventDefault();
			 return false;
		}
	}
);
// Contact Form 7 Error Class
jQuery( document ).ajaxComplete(
	function() {
		jQuery( '.wpcf7-not-valid-tip' ).on(
			'mouseover',
			function(){
				jQuery( this ).fadeOut();
				jQuery( '.wpcf7-not-valid' ).removeClass( '.wpcf7-not-valid' );
			}
		);
		jQuery( '.wpcf7-not-valid' ).on(
			'focus',
			function(){
				jQuery( this ).removeClass( 'wpcf7-not-valid' );
				jQuery( this ).next( '.wpcf7-not-valid-tip' ).fadeOut();
			}
		);
		jQuery( '.wpcf7-form input[type="reset"]' ).on(
			'click',
			function(){
				jQuery( '.wpcf7-not-valid-tip, .wpcf7-response-output' ).fadeOut();
			}
		);
	}
);
