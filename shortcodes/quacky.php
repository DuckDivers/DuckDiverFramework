<?php

//Anti-Spambot Mailto

if (!function_exists('dd_secure_mail')) {
    function dd_secure_mail($atts) {
        extract(shortcode_atts(array(
            "mailto" => '',
            "txt" => ''
        ), $atts));
        $mailto = antispambot($mailto);
        $txt = antispambot($txt);
        return '<a href="mailto:' . $mailto . '">' . $txt . '</a>';
    }
    add_shortcode('mailto', 'dd_secure_mail');
}

// Variable Spacer Shortcode //
if (!function_exists('space_shortcode')){
    function space_shortcode( $atts, $content = null ) {
        extract( shortcode_atts( array(
            'height' => '',
        ), $atts ) );

	return '<div class="space" style="height:' . $height . 'px;"></div>';
    }
    add_shortcode('space', 'space_shortcode');
}

if (!function_exists('dd_fontawesome_shortcode')){
    function dd_fontawesome_shortcode ($atts) {
	extract (shortcode_atts( array(
		'icon' => '',
		'size' => '14',
		'color' => '#000',
		'type' => 'fa'
	), $atts ) );
	$output = '<i class="'.$type.' '. $icon .'" style="font-size:  '. $size .'px; color: '. $color .';"></i>';
	
return $output;
	}
add_shortcode ('dd-fontawesome' , 'dd_fontawesome_shortcode');

}

function dd_icon_shortcode($atts){
	$atts = shortcode_atts(
		array(
			'icon' => '',
			'size' => '14',
			'color' => '#000'
		), $atts, 'dd_icon' );
		$output = '<i class="dd-'.$atts['icon'].'" style="font-size: '.$atts['size'].'px; color: '.$atts['color'].';"></i>';
		
		return $output;
	}
	
add_shortcode('dd-icon', 'dd_icon_shortcode');
