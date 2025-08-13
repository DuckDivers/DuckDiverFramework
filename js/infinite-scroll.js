/**
 * Infinite Scroll JS
 *
 * @package Duck Diver Infinite Scroll.
 *
 * @var ajaxposts obj
 */

jQuery(
	function ($) {

		let canBeLoaded  = true,
			bottomOffset = 1500;
		let query_object = JSON.parse( ajaxposts.posts );
		if ( 0 !== query_object['p'] ) {
			console.log( 'this is a single' );
			return false;
		}
		$( window ).on(
			'scroll',
			function () {
				let data = {
					'action': 'loadmore',
					'_ajax_nonce': ajaxposts.nonce,
					'query': ajaxposts.posts,
					'page': ajaxposts.current_page,
				};
				if ( $( document ).scrollTop() > ( $( document ).height() - bottomOffset ) && true === canBeLoaded ) {
					$.ajax(
						{
							url : ajaxposts.ajaxurl,
							data:data,
							type:'POST',
							dataType:'html',
							beforeSend: function ( xhr ) {
								canBeLoaded = false;
							},
							success:function ( response ) {
								if ( response ) {
									$( '#infinite' ).find( '.col-md-4:last-of-type' ).after( response );
									canBeLoaded = true;
									ajaxposts.current_page++;
								}
							}
						}
					);
				}
			}
		);
	}
);
