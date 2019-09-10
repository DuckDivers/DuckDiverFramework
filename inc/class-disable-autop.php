<?php
/**
 * Disable AutoP
 *
 * @since    1.2.5
 */
class DD_Disable_AutoP {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' )         );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'DD-Disable',
			__( 'Disable Auto P', 'dd_theme' ),
			array( $this, 'render_metabox' ),
			'page',
			'side',
			'core'
		);

	}

	public function render_metabox( $post ) {

		// Retrieve an existing value from the database.
		$dd_disable_autop = get_post_meta( $post->ID, 'dd_disable-autop', true );

		// Set default values.
		if( empty( $dd_disable_autop ) ) $dd_disable_autop = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<td>';
		echo '			<label><input type="checkbox" id="dd_disable_autop" name="dd_disable-autop" class="dd_disable_autop_field" value="checked" ' . checked( $dd_disable_autop, 'checked', false ) . '> ' . __( '', 'dd_theme' ) . '</label>';
		echo '			<span class="description">' . __( 'Check this box to disable the WordPress Auto &lt;p&gt;', 'dd_theme' ) . '</span>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Sanitize user input.
		$dd_disablenew_dd_autop = isset( $_POST[ 'dd_disable-autop' ] ) ? 'checked'  : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'dd_disable-autop', $dd_disablenew_dd_autop );

	}

}

new DD_Disable_AutoP;


function dd_custom_wpautop($content) {
  if (get_post_meta(get_the_ID(), 'dd_disable-autop', true) == 'checked')
    return $content;
  else
    return wpautop($content);
}
remove_filter('the_content', 'wpautop');
add_filter('the_content', 'dd_custom_wpautop');