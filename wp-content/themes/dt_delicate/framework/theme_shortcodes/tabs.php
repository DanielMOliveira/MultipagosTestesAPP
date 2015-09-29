<?php 
/**
 * Tab
 **/
add_shortcode('tab','my_tab');
function my_tab($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array('title'=>''), $attrs));
	$out = '<li class="tab_head"><a href="#">'.$title.'</a></li><div class="tabs_content">'.my_shortcode_helper($content).'</div>';
return $out;	
}
/**
 * Tabs
 **/
add_shortcode('tabs', 'my_tabs');
function my_tabs( $attrs, $content = null ) {
	
	$out = my_shortcode_helper($content);
	$E = $C = "";
	
	preg_match_all("'<li class=\"tab_head\">(.*?)</li>'si", $out, $match);	
	foreach($match[0] as $val) {
		$val = str_replace('<li class="tab_head">', '<li>', $val);
		$E .= $val;
	}	
	preg_match_all("'<div class=\"tabs_content\">(.*?)</div>'si", $out, $match);
	foreach($match[0] as $val) {
		$C .= $val;
	}
	$out = '<div class="tabs-container">'.'<ul class="tabs">'.$E.'</ul>'.$C.'</div>';
return $out; 
}



/**
 * Frame Less Horizontal Tabs
 **/
add_shortcode('tabs_horizontal_frame_less', 'my_tabs_horizontal_frame_less');
function my_tabs_horizontal_frame_less( $attrs, $content = null ) {
	
	$out = my_shortcode_helper($content);
	$E = $C = "";
	
	preg_match_all("'<li class=\"tab_head\">(.*?)</li>'si", $out, $match);	
	
	foreach($match[0] as $val) {
		$val = str_replace('<li class="tab_head">', '<li>', $val);
		$E .= $val;
	}
		
	preg_match_all("'<div class=\"tabs_content\">(.*?)</div>'si", $out, $match);
	
	foreach($match[0] as $val) {
		$val = str_replace('<div class="tabs_content">', '<div class="tabs-content">', $val);		
		$C .= $val;
	}
	$out = '<div class="tabs-container">'.'<ul class="tabs">'.$E.'</ul>'.$C.'</div>';
	
	return $out; 
}


/**
 * Framed Tabs
 **/
add_shortcode('tabs_horizontal', 'my_tabs_framed');
function my_tabs_framed( $attrs, $content = null ) {
	
	$out = my_shortcode_helper($content);
	$E = $C = "";
	
	preg_match_all("'<li class=\"tab_head\">(.*?)</li>'si", $out, $match);	
	foreach($match[0] as $val) {
		$val = str_replace('<li class="tab_head">', '<li>', $val);
		$E .= $val;
	}	
	preg_match_all("'<div class=\"tabs_content\">(.*?)</div>'si", $out, $match);
	foreach($match[0] as $val) {
		$val = str_replace('<div class="tabs_content">', '<div class="tabs-frame-content">', $val);		
		$C .= $val;
	}
	$out = '<div class="tabs-container">'.'<ul class="tabs-frame">'.$E.'</ul>'.$C.'</div>';
	return $out; 
}

/**
 * Vertical Tabs
 **/
add_shortcode('tabs_vertical', 'my_tabs_vertical'); 
function my_tabs_vertical( $attrs, $content = null ) {
	
	$out = my_shortcode_helper($content);
	$E = $C = "";
	
	preg_match_all("'<li class=\"tab_head\">(.*?)</li>'si", $out, $match);
	foreach($match[0] as $val) {
		$val = str_replace('<li class="tab_head">', '<li>', $val);
		$val = str_replace('</a></li>', '<span></span></a></li>', $val);
		$E .= $val;
	}	
	preg_match_all("'<div class=\"tabs_content\">(.*?)</div>'si", $out, $match);
	foreach($match[0] as $val) {
		$val = str_replace('<div class="tabs_content">', '<div class="tabs-vertical-frame-content">', $val);
		$C .= $val;
	}
	$out = "<ul class='tabs-vertical-frame'>$E</ul>";
	$out = "<div class='tabs-vertical-container'>$out"."$C</div>";
	return $out; 
}?>