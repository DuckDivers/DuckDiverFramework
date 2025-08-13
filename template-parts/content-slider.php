<?php
/**
 * Slider Template
 *
 * @package Duck Diver Framework
 */

$slides       = array();
$args         = apply_filters(
	'dd_filter_slider_args',
	array(
		'post_type' => 'slider',
		'orderby'   => 'menu_order',
		'order'     => 'ASC',
	)
);
$slider_query = new WP_Query( $args );
if ( $slider_query->have_posts() ) {
	while ( $slider_query->have_posts() ) {
		$slider_query->the_post();
		if ( has_post_thumbnail() ) {
			$temp            = array();
			$thumb_id        = get_post_thumbnail_id();
			$thumb_url_array = wp_get_attachment_image_src( $thumb_id, apply_filters( 'dd_slider_image_size', 'slider-post-thumbnail' ), true );
			$thumb_url       = $thumb_url_array[0];
			$temp['title']   = get_the_title();
			$temp['excerpt'] = apply_filters( 'the_content', get_post_meta( $post->ID, 'slider_caption', true ) );
			$temp['image']   = $thumb_url;
			$temp['link']    = get_post_meta( $post->ID, 'slider_link', true );
			if ( function_exists( 'get_field' ) ) {
				$mobile_image = get_field( 'mobile_slider_image' );
			} else {
				$mobile_image = false;
			}
			$mobile_fallback = wp_get_attachment_image_src( $thumb_id, 'mobile-slider' );
			$temp['mobile']  = ( $mobile_image ) ? $mobile_image['sizes']['large'] : $mobile_fallback[0];
			$slides[]        = $temp;
		}
	}
}

$fade = ( get_theme_mod( 'dd_slider_transition' ) === 'fade' ) ? ' carousel-fade' : '';

wp_reset_postdata();
?>
<?php if ( count( $slides ) > 0 ) { ?>
	<?php do_action( 'dd_before_slider' ); ?>
	<div class="container-fluid">
		<div class="row">
			<div id="dd-carousel" class="carousel slide<?php echo esc_attr( $fade ); ?>" data-ride="carousel">
				<?php do_action( 'dd_slider_extra_content' ); ?>
				<?php if ( get_theme_mod( 'dd_slider_navs' ) ) : ?>
					<ol class="carousel-indicators">
						<?php $count = count( $slides ); ?>
						<?php for ( $i = 0;$i < $count;$i++ ) { ?>
							<li data-target="#dd-carousel" data-slide-to="<?php echo esc_attr( $i ); ?>" <?php if ( 0 === $i ) { ?>
								class="active" <?php } ?>></li>
						<?php } ?>
					</ol>
				<?php endif; ?>
				<div class="carousel-inner" role="listbox">
					<?php
					$i = 0;
					foreach ( $slides as $slide ) {
						extract( $slide ); //phpcs:ignore
						?>
						<div class="carousel-item<?php if ( 0 === $i ) :?> active<?php endif; // phpcs:ignore?>">
							<a href="<?php echo esc_url( $link ); ?>" class="carousel-item-image-link">
								<?php if ( $i <= 1 ) : ?>
									<picture>
										<source media="(max-width: 767px)" srcset="<?php echo esc_url( $mobile ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100">
										<source media="(min-width: 768px)" srcset="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100">
										<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100">
									</picture>
								<?php else : ?>
									<picture>
										<source media="(max-width: 767px)" data-srcset="<?php echo esc_url( $mobile ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100 mobile-slider-image">
										<source media="(min-width: 768px)" data-srcset="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100 desktop-slider-image">
										<img data-src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="d-block w-100 desktop-slider-image">
									</picture>
								<?php endif; ?>
								<div class="carousel-caption">
									<?php echo wp_kses_post( $excerpt ); ?>
								</div>
							</a>
						</div>
						<?php $i++; } ?>
				</div>
				<a class="carousel-control-prev" href="#dd-carousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a> <a class="carousel-control-next" href="#dd-carousel" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>
			</div>
		</div>
	</div>
	<?php do_action( 'dd_after_slider' ); ?>
<?php } ?>
