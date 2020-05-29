<?php
$slides = array();
$args = apply_filters('dd_filter_slider_args', array( 'post_type' => 'slider', 'orderby' => 'menu_order', 'order' => 'ASC' ));
$slider_query = new WP_Query( $args );
if ( $slider_query->have_posts() ) {
    while ( $slider_query->have_posts() ) {
        $slider_query->the_post();
        if ( has_post_thumbnail() ) {
            $temp = array();
            $thumb_id = get_post_thumbnail_id();
            $thumb_url_array = wp_get_attachment_image_src( $thumb_id, apply_filters('dd_slider_image_size', 'slider-post-thumbnail'), true );
            $thumb_url = $thumb_url_array[ 0 ];
            $temp[ 'title' ] = get_the_title();
            $temp[ 'excerpt' ] = get_post_meta( $post->ID, 'slider_caption', true );
            $temp[ 'image' ] = $thumb_url;
            $temp[ 'link' ] = get_post_meta( $post->ID, 'slider_link', true );
            $slides[] = $temp;
        }
    }
}

$fade = (get_theme_mod('dd_slider_transition')=='fade') ? ' carousel-fade' : '';

wp_reset_postdata();
?>
<?php if(count($slides) > 0) { ?>
<?php do_action('dd_before_slider');?>
<div class="container-fluid">
    <div class="row">
        <div id="dd-carousel" class="carousel slide<?php echo $fade;?>" data-ride="carousel">
            <?php if (get_theme_mod('dd_slider_navs')) :?>
            <ol class="carousel-indicators">
                <?php for($i=0;$i<count($slides);$i++) { ?>
                <li data-target="#dd-carousel" data-slide-to="<?php echo $i ?>" <?php if($i==0) { ?>class="active"
                <?php } ?>></li>
                <?php } ?>
                </ol>
            <?php endif; ?>
            <div class="carousel-inner" role="listbox">
                <?php $i=0; foreach($slides as $slide) { extract($slide); ?>
                <div class="carousel-item <?php if($i == 0) { ?>active<?php } ?>"> <a href="<?php echo $link; ?>" class="carousel-item-image-link"><img src="<?php echo $image ?>" alt="<?php echo esc_attr($title); ?>">
                    <div class="carousel-caption">
                        <p>
                            <?php echo $excerpt; ?>
                            </p>
                        </div></a>
                    </div>
                <?php $i++; } ?>
                </div>
            <a class="carousel-control-prev" href="#dd-carousel" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">Previous</span></a> <a class="carousel-control-next" href="#dd-carousel" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">Next</span></a>
        </div>
    </div>
</div>
<?php do_action('dd_after_slider');?>
<?php } ?>
