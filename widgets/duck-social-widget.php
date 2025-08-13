<?php

/**
 * Class Duck_Social_Widget
 *
 * Extends the WP_Widget class to create a customizable social networks widget.
 * This widget enables users to display links to various social media platforms
 * along with optional labels and icons.
 */
class Duck_Social_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
		// Base ID of your widget.
			'Duck_Social_Widget',
			// Widget name will appear in UI.
			__( 'Duck Diver - Social Networks', 'duck_widget_domain' ),
			// Widget description.
			array( 'description' => __( 'Duck Diver Links to your Networks', 'duck_widget_domain' ) )
		);
	}

	public function Duck_Social_Widget() {
		$widget_ops = array(
			'classname'   => 'social_networks_widget',
			'description' => __( 'Link to your social networks.', 'text_domain' ),
		);
		parent::__construct( 'social_networks', __( 'Ducks - Social Networks', 'text_domain' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );

		$networks['Twitter']['link']   = isset( $instance['twitter'] ) ? $instance['twitter'] : '';
		$networks['Facebook']['link']  = isset( $instance['facebook'] ) ? $instance['facebook'] : '';
		$networks['Flickr']['link']    = isset( $instance['flickr'] ) ? $instance['flickr'] : '';
		$networks['Rss']['link']       = isset( $instance['rss'] ) ? $instance['rss'] : '';
		$networks['Linkedin']['link']  = isset( $instance['linkedin'] ) ? $instance['linkedin'] : '';
		$networks['Instagram']['link'] = isset( $instance['instagram'] ) ? $instance['instagram'] : '';
		$networks['Youtube']['link']   = isset( $instance['youtube'] ) ? $instance['youtube'] : '';
		$networks['Pinterest']['link'] = isset( $instance['pinterest'] ) ? $instance['pinterest'] : '';
		$networks['Vimeo']['link']     = isset( $instance['vimeo'] ) ? $instance['vimeo'] : '';
		$networks['Tumblr']['link']    = isset( $instance['tumblr'] ) ? $instance['tumblr'] : '';

		$networks['Twitter']['label']   = isset( $instance['twitter_label'] ) ? $instance['twitter_label'] : '';
		$networks['Facebook']['label']  = isset( $instance['facebook_label'] ) ? $instance['facebook_label'] : '';
		$networks['Flickr']['label']    = isset( $instance['flickr_label'] ) ? $instance['flickr_label'] : '';
		$networks['Rss']['label']       = isset( $instance['rss_label'] ) ? $instance['rss_label'] : '';
		$networks['Linkedin']['label']  = $instance['linkedin_label'] ?? '';
		$networks['Instagram']['label'] = isset( $instance['instagram_label'] ) ? $instance['instagram_label'] : '';
		$networks['Youtube']['label']   = isset( $instance['youtube_label'] ) ? $instance['youtube_label'] : '';
		$networks['Pinterest']['label'] = isset( $instance['pinterest_label'] ) ? $instance['pinterest_label'] : '';
		$networks['Vimeo']['label']     = isset( $instance['vimeo_label'] ) ? $instance['vimeo_label'] : '';
		$networks['Tumblr']['label']    = isset( $instance['tumblr_label'] ) ? $instance['tumblr_label'] : '';

		$display = $instance['display'];
		echo wp_kses_post( $before_widget );
		if ( $title ) {
			echo wp_kses_post( $before_title );
				echo esc_attr( $title );
			echo wp_kses_post( $after_title );
		}
		?>

		<!-- BEGIN SOCIAL NETWORKS -->
		<?php
		if ( 'both' === $display || 'labels' === $display ) {
			$add_class = 'social__list';
		} elseif ( 'icons' === $display ) {
			$add_class = 'social__row clearfix';
		}
		?>

		<ul class="social <?php echo esc_attr( $add_class ); ?> list-unstyled">

		<?php foreach ( array( 'Facebook', 'Twitter', 'Flickr', 'Rss', 'Pinterest', 'Instagram', 'Linkedin', 'Vimeo', 'Youtube', 'Tumblr' ) as $network ) : ?>
			<?php if ( ! empty( $networks[ $network ]['link'] ) ) : ?>
			<li class="social_li">
				<a class="social_link social_link__<?php echo esc_attr( strtolower( $network ) ); ?>" rel="tooltip noopener" data-original-title="<?php echo esc_attr( strtolower( $network ) ); ?>" target="_blank" href="<?php echo esc_url( $networks[ $network ]['link'] ); ?>">
					<?php
					if ( ( 'both' === $display ) || ( 'icons' === $display ) ) {
						if ( 'Vimeo' === $network ) {
							?>
								<span class="social_ico"><i class="fa fa-vimeo-square"></i></span>
							<?php } else { ?>
								<span class="social_ico"><i class="fa fa-<?php echo esc_attr( strtolower( $network ) ); ?>"></i></span>
								<?php
							}
					} if ( ( 'labels' === $display ) || ( 'both' === $display ) ) {
						?>
						<span class="social_label">
						<?php
						if ( ( $networks[ $network ]['label'] ) !== '' ) {
							echo esc_attr( $networks[ $network ]['label'] );
						} else {
							echo esc_attr( $network );
						}
						?>
						</span>
					<?php } ?>
				</a>
			</li>
			<?php endif; ?>
		<?php endforeach; ?>

		</ul>
		<!-- END SOCIAL NETWORKS -->
		<?php
		echo wp_kses_post( $after_widget );
	}
	
	/**
	 * Updates the instance with new values.
	 *
	 * @param array $new_instance The new set of values to update the instance with.
	 * @param array $old_instance The previous instance values before the update.
	 *
	 * @return array The updated instance values.
	 */
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		/* Set up some default widget settings. */
		$defaults = array(
			'title'           => '',
			'twitter'         => '',
			'twitter_label'   => '',
			'facebook'        => '',
			'facebook_label'  => '',
			'flickr'          => '',
			'flickr_label'    => '',
			'rss'             => '',
			'rss_label'       => '',
			'linkedin'        => '',
			'linkedin_label'  => '',
			'instagram'       => '',
			'instagram_label' => '',
			'youtube'         => '',
			'youtube_label'   => '',
			'pinterest'       => '',
			'pinterest_label' => '',
			'vimeo'           => '',
			'vimeo_label'     => '',
			'tumblr'          => '',
			'tumblr_label'    => '',
			'display'         => 'icons',
			'text'            => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$twitter   = $instance['twitter'];
		$facebook  = $instance['facebook'];
		$flickr    = $instance['flickr'];
		$rss       = $instance['rss'];
		$linkedin  = $instance['linkedin'];
		$instagram = $instance['instagram'];
		$youtube   = $instance['youtube'];
		$pinterest = $instance['pinterest'];
		$vimeo     = $instance['vimeo'];
		$tumblr    = $instance['tumblr'];

		$twitter_label   = $instance['twitter_label'];
		$facebook_label  = $instance['facebook_label'];
		$flickr_label    = $instance['flickr_label'];
		$rss_label       = $instance['rss_label'];
		$linkedin_label  = $instance['linkedin_label'];
		$instagram_label = $instance['instagram_label'];
		$youtube_label   = $instance['youtube_label'];
		$pinterest_label = $instance['pinterest_label'];
		$vimeo_label     = $instance['vimeo_label'];
		$tumblr_label    = $instance['tumblr_label'];
		$display         = $instance['display'];
		$title           = strip_tags( $instance['title'] );
		?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'text_domain' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Facebook', 'text_domain' ); ?>:</legend>

		<p><label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook URL', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'facebook_label' ); ?>"><?php _e( 'Facebook label', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'facebook_label' ); ?>" name="<?php echo $this->get_field_name( 'facebook_label' ); ?>" type="text" value="<?php echo esc_attr( $facebook_label ); ?>" /></p>
	</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Twitter', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter URL', 'text_domain' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'twitter_label' ); ?>"><?php _e( 'Twitter label', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'twitter_label' ); ?>" name="<?php echo $this->get_field_name( 'twitter_label' ); ?>" type="text" value="<?php echo esc_attr( $twitter_label ); ?>" /></p>
	</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Flickr', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'flickr' ); ?>"><?php _e( 'Flickr URL', 'text_domain' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'flickr' ); ?>" name="<?php echo $this->get_field_name( 'flickr' ); ?>" type="text" value="<?php echo esc_attr( $flickr ); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'flickr_label' ); ?>"><?php _e( 'Flickr label', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'flickr_label' ); ?>" name="<?php echo $this->get_field_name( 'flickr_label' ); ?>" type="text" value="<?php echo esc_attr( $flickr_label ); ?>" /></p>
	</fieldset>


	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Pinterest', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'pinterest' ); ?>"><?php _e( 'Pinterest URL', 'text_domain' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" type="text" value="<?php echo esc_attr( $pinterest ); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'pinterest_label' ); ?>"><?php _e( 'Pinterest label', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest_label' ); ?>" name="<?php echo $this->get_field_name( 'pinterest_label' ); ?>" type="text" value="<?php echo esc_attr( $pinterest_label ); ?>" /></p>
	</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'RSS feed', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'rss' ); ?>"><?php _e( 'RSS feed', 'text_domain' ); ?>:</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo esc_attr( $rss ); ?>" /></p>
	<p><label for="<?php echo $this->get_field_id( 'rss_label' ); ?>"><?php _e( 'RSS label', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss_label' ); ?>" name="<?php echo $this->get_field_name( 'rss_label' ); ?>" type="text" value="<?php echo esc_attr( $rss_label ); ?>" /></p>
	</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
			<legend style="padding:0 5px;"><?php _e( 'Linkedin', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e( 'Linkedin URL', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'linkedin_label' ); ?>"><?php _e( 'Linkedin label', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'linkedin_label' ); ?>" name="<?php echo $this->get_field_name( 'linkedin_label' ); ?>" type="text" value="<?php echo esc_attr( $linkedin_label ); ?>" /></p>
		</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
			<legend style="padding:0 5px;"><?php _e( 'instagram', 'text_domain' ); ?>:</legend>
	<p><label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'instagram URL', 'text_domain' ); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'instagram_label' ); ?>"><?php _e( 'instagram label', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'instagram_label' ); ?>" name="<?php echo $this->get_field_name( 'instagram_label' ); ?>" type="text" value="<?php echo esc_attr( $instagram_label ); ?>" /></p>
		</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Youtube', 'text_domain' ); ?>:</legend>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube URL', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube_label' ); ?>"><?php _e( 'Youtube label', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube_label' ); ?>" name="<?php echo $this->get_field_name( 'youtube_label' ); ?>" type="text" value="<?php echo esc_attr( $youtube_label ); ?>" />
		</p>
	</fieldset>
	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Tumblr', 'text_domain' ); ?>:</legend>
		<p>
			<label for="<?php echo $this->get_field_id( 'tumblr' ); ?>"><?php _e( 'Tumblr URL', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tumblr' ); ?>" name="<?php echo $this->get_field_name( 'tumblr' ); ?>" type="text" value="<?php echo esc_attr( $tumblr ); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tumblr_label' ); ?>"><?php _e( 'Tumblr label', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'tumblr_label' ); ?>" name="<?php echo $this->get_field_name( 'tumblr_label' ); ?>" type="text" value="<?php echo esc_attr( $tumblr_label ); ?>" />
		</p>
	</fieldset>

	<fieldset style="border:1px solid #dfdfdf; padding:10px 10px 0; margin-bottom:1em;">
		<legend style="padding:0 5px;"><?php _e( 'Vimeo', 'text_domain' ); ?>:</legend>
		<p>
			<label for="<?php echo $this->get_field_id( 'vimeo' ); ?>"><?php _e( 'Vimeo URL', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'vimeo' ); ?>" name="<?php echo $this->get_field_name( 'vimeo' ); ?>" type="text" value="<?php echo esc_attr( $vimeo ); ?>" /></p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vimeo_label' ); ?>"><?php _e( 'Vimeo label', 'text_domain' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'vimeo_label' ); ?>" name="<?php echo $this->get_field_name( 'vimeo_label' ); ?>" type="text" value="<?php echo esc_attr( $vimeo_label ); ?>" />
		</p>
	</fieldset>


		<p><?php _e( 'Display', 'text_domain' ); ?>:</p>
		<label for="<?php echo $this->get_field_id( 'icons' ); ?>"><input type="radio" name="<?php echo $this->get_field_name( 'display' ); ?>" value="icons" id="<?php echo $this->get_field_id( 'icons' ); ?>" <?php checked( $display, 'icons' ); ?>></input>  <?php _e( 'Icons', 'text_domain' ); ?></label>
		<label for="<?php echo $this->get_field_id( 'labels' ); ?>"><input type="radio" name="<?php echo $this->get_field_name( 'display' ); ?>" value="labels" id="<?php echo $this->get_field_id( 'labels' ); ?>" <?php checked( $display, 'labels' ); ?>></input> <?php _e( 'Labels', 'text_domain' ); ?></label>
		<label for="<?php echo $this->get_field_id( 'both' ); ?>"><input type="radio" name="<?php echo $this->get_field_name( 'display' ); ?>" value="both" id="<?php echo $this->get_field_id( 'both' ); ?>" <?php checked( $display, 'both' ); ?>></input> <?php _e( 'Both', 'text_domain' ); ?></label>
		<?php
	}
}

function register_duck_widget() {
	register_widget( 'Duck_Social_Widget' );
}
add_action( 'widgets_init', 'register_duck_widget' );

?>
