<?php 
/**
 * Accordion Group
 **/
add_shortcode('accordion_group','my_accordion_group');
function my_accordion_group($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array('class' => ''), $attrs));
	$out = my_shortcode_helper($content);
	$out = str_replace('<h5 class="toggle', '<h5 class="toggle-accordion ', $out);
	$out = "<div class='toggle-frame-set $class'>".$out.'</div>';
	return $out;
}
/**
 * Toggle
 **/
add_shortcode('toggle', 'my_toggle');
function my_toggle( $attrs, $content = null ) {
	extract(shortcode_atts(array('title' => '','variation' => '', 'class' =>''), $attrs));
	$variation = ( $variation ) ? ' ' . trim( $variation ) . '_sprite' : '';

	$out = '<h5 class="toggle ' . $variation . $class. '"><a href="#">' . $title . '</a></h5>';
	$out .= '<div class="toggle-content" style="display: none;">';
	$out .= '<div class="block">';
	$out .=  my_shortcode_helper( $content );
	$out .= '</div>';
	$out .= '</div>';
return $out; 
}

/**
 * Toggle Framed
 **/
add_shortcode('toggle_framed', 'my_toggle_framed');
function my_toggle_framed( $attrs, $content = null ) {
	extract(shortcode_atts(array('title' => '','variation' => '', 'class' =>''), $attrs));
	$variation = ( $variation ) ? ' ' . trim( $variation ) . '_sprite' : '';
	$out = "<div class='toggle-frame {$class}'>";
	$out .= '<h5 class="toggle' . $variation . '"><a href="#">' . $title . '</a></h5>';
	$out .= '<div class="toggle-content" style="display: none;">';
	$out .= '<div class="block">';
	$out .=  my_shortcode_helper( $content );
	$out .= '</div>';
	$out .= '</div>';
	$out .= '</div>';
return $out;
}?>