<?php
/**
 * Misc
 */

//Close
if (!function_exists('shortcode_close_icon')) {
	function shortcode_close_icon( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(
				array(
					'dismiss' => 'alert'
				), $atts));

		$output = '<a class="close" href="#" data-dismiss="'.$dismiss.'">&times;</a>';

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('close', 'shortcode_close_icon');
}

//Well
if (!function_exists('shortcode_well')) {
	function shortcode_well( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(
				array(
					'size' => 'normal'
				), $atts));

		$output = '<div class="card '.$size.'">';
		$output .= do_shortcode($content);
		$output .= '</div>';

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('well', 'shortcode_well');
}

//Small
if (!function_exists('shortcode_small')) {
	function shortcode_small( $atts, $content = null, $shortcodename = '' ) {
		$output = '<small>'.do_shortcode($content).'</small>';

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('small', 'shortcode_small');
}

// Shortcode site map
if (!function_exists('shortcode_site_map')) {
	function shortcode_site_map( $atts, $content = null, $shortcodename = '' ) {
		extract(shortcode_atts(array(
			'title' => '',
			'type' => 'Lines',
			'custom_class' => ''
		), $atts));

		$title = ($title!='') ? '<h2 class="site_map_title"><span class="icon-sitemap"></span>'.$title.'</h2>' : '' ;
		$args=array('public'   => true, '_builtin' => false);
		$post_types=get_post_types($args,'names', 'or');

		$sort_array = array('page' => '', 'post' => '', 'services' => '', 'portfolio' => '', 'slider' => '', 'team' => '', 'testi' => '', 'faq' => '');
		$post_types = array_merge($sort_array, $post_types);
		unset($post_types['attachment'], $post_types['wpcf7_contact_form']);
		$span_counter=0;
		$wrapp_class = ($type!='Lines') ? 'group' : '';
		$item_class = ($type!='Lines') ? 'grid  clearfix' : 'line clearfix';
		$output = '<div class="site_map '.$custom_class.' clearfix">'.$title;

		foreach( $post_types as $post_type ) {
			if(!empty($post_type)){
				$output .= ($span_counter==0 && $type!='line') ? '<div class="'.$wrapp_class.'">' : '' ;

				$pt = get_post_type_object( $post_type );
				$output .= '<div class="'.$item_class.'"><h2>'.$pt->labels->name.'</h2><ul>';

				query_posts('post_type='.$post_type.'&posts_per_page=-1&orderby=title&order=ASC');
				if ( have_posts() ) while( have_posts() )  {
					the_post();
					$output .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
				}
				wp_reset_query();
				if($span_counter>2 && $type!='line'){
					$span_counter=0;
					$output .= '</div>';
				}else{
					$span_counter++;
				}
				$output .= '</ul></div>';
			}
		}
		$output .= '</div>';

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('site_map', 'shortcode_site_map');
}

// Add YouTube Embed Responsive
if(!function_exists('dd_yt_embed_responsive')){
    function dd_yt_embed_responsive($atts){
        $args = shortcode_atts(array(
            'id'    => '',
            'class' => '',
            'ratio' => ''
        ), $atts, 'dd_yt_embed_responsive');

        $out  = "<div class='embed-responsive embed-responsive-{$args['ratio']}";
        $out .= (!empty($args['class'])) ? ' ' . $args['class'] : '';
        $out .= "'><iframe src='https://youtube.com/embed/{$args['id']}?rel=0 class='embed-item'></iframe></div>";

        return $out;
    }
    add_shortcode('dd-youtube', 'dd_yt_embed_responsive');
}

?>
