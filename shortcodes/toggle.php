<?php
/**
 * Toggle for Bootstrap 4.0
 *
 */
global $my_accordion_shortcode_count;
$my_accordion_shortcode_count = 0;

global $my_global_var;
$my_global_var = rand();
if (!function_exists('my_display_shortcode_accordion')) {
	function my_display_shortcode_accordion( $atts, $content = null, $shortcodename = '' ) {
		global $my_global_var, $post, $my_accordion_shortcode_count;
		extract(shortcode_atts(array(
			'title' => null,
			'class' => null,
			'visible' => null
		), $atts));

		$toggleid = rand();

		if($visible!='') {
			$inClass = "show";
			$activeClass = "active";
			$collapsed = "";
		} else {
			$inClass = "";
			$activeClass = "";
			$collapsed = "collapsed";
		}
		$custom_class = ( $class ) ? ' ' . esc_attr( $class ) : '';
		$output = '<div class="card'.$custom_class.'">';
			$output .= '<div class="card-header '.$activeClass.'" id="heading-'.$toggleid.'" data-target="#collapse-'.$toggleid.'" data-parent="#accordion" data-toggle="collapse">';
			$output .= '<h4 class="panel-title"><button class="btn btn-link" data-toggle="collapse" data-target="#collapse-'.$toggleid.'">'.$title.'</button></h4>';
			$output .= '</div>';
			$output .= '<div id="collapse-'.$toggleid.'" class="collapse '.$inClass.'" aria-labelledby="heading-'.$toggleid.'" data-parent="#accordion">';
				$output .= ' <div class="card-body">';
					$output .= do_shortcode( $content );
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$my_accordion_shortcode_count++;

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('accordion', 'my_display_shortcode_accordion'); // Single accordion
}
if (!function_exists('my_display_shortcode_accordions')) {
	function my_display_shortcode_accordions( $atts, $content = null, $shortcodename = '' ){
		// wordpress function
		global $my_accordion_shortcode_count,$post,$my_global_var;

		$output = '<div id="accordion">';
		$output .= do_shortcode( $content );
		$output .= '</div>';

		$my_global_var++;
		$output = str_replace("\r\n", '',$output);

		$output = apply_filters( 'dd_shortcodes_output', $output, $atts, $shortcodename );

		return $output;
	}
	add_shortcode('accordions', 'my_display_shortcode_accordions'); // Accordion Wrapper
}
?>
