<?php 
if (! function_exists('duck_diver_extra_widgets_init')){
	function duck_diver_extra_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Footer Widgets Left', 'dd_theme' ),
			'id'            => 'left-footer-widgets',
			'description'   => 'In the footer on the left side',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
			// Footer Widget Area 1
		// Location: at the top of the footer, above the copyright
		register_sidebar( array(
			'name'          => __("Footer 1"),
			'id'            => 'footer-sidebar-1',
			'description'   => __("Footer Column 1"),
			'before_widget' => '<div id="%1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		) );
		// Footer Widget Area 2
		// Location: at the top of the footer, above the copyright
		register_sidebar( array(
			'name'          => __("Footer 2"),
			'id'            => 'footer-sidebar-2',
			'description'   => __("Footer Column 2"),
			'before_widget' => '<div id="%1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		) );
		// Footer Widget Area 3
		// Location: at the top of the footer, above the copyright
		register_sidebar( array(
			'name'          => __("Footer 3"),
			'id'            => 'footer-sidebar-3',
			'description'   => __("Footer Column 3"),
			'before_widget' => '<div id="%1$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		) );

        if (in_array('woocommerce/woocommerce.php', get_option( 'active_plugins') ) ) {
        // Cart Holder
            register_sidebar(array(
                'name'          => __( "Cart Holder", "themeWoo" ),
                'id'            => 'cart-holder',
                'description'   => __( "Widget for cart in Header", "themeWoo" ),
                'before_widget' => '<div id="%1$s" class="cart-holder">',
                'after_widget'  => '</div><span class="cart-items"></span>',
                'before_title'  => '<h3>',
                'after_title'   => '</h3>'
            ));
            register_sidebar(array(
                'name'          => __("Shop Sidebar", 'dd_theme'),
                'id'            => 'shop-sidebar',
                'description'   => __("The sidebar for the shop if used", 'dd_theme'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widgettitle">',
                'after_title'   => '</h2>',
            ));
        }
	}
    add_action( 'widgets_init', 'duck_diver_extra_widgets_init' );
}


class duck_dashboard_widget {

	public function __construct() {
		
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widget' ) );

	}

	public function add_dashboard_widget() {

		wp_add_dashboard_widget(
			'dd_dash_widget',
			__( 'Duck Diver Marketing', 'duckdiver' ),
			array( $this, 'render_dd_dashboard' ),
			array( $this, 'save_dd_dashboard' )
		);

	}

	public function render_dd_dashboard() {
		$logo = get_template_directory_URI() . '/admin/img/duck-diver-logo.png';
		echo '<div class="main"><ul class="dd_home_widget"><li>';
		echo '<h2>Duck Diver Marketing</h2>';
		echo '<p>Need Help?<br />';
		echo 'Call or E-Mail<br />';
		echo '(970)406-1122<br />';
		echo '<a href="mailto:support@duckdiverllc.com" target="_blank" class="button button-primary">E-Mail Support</a></p></li>';
		echo '<li><img src="'.$logo.'" style="max-width: 100%;" /></li></ul></div>';
	}
	
	public function save_dd_dashboard() {

		echo'<input type="submit">';

	}

}

new duck_dashboard_widget;
