<?php 
function delete_htmltags($content,$paragraph_tag=false,$br_tag=false){	
	$content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
	$content = preg_replace('#<br \/>#', '', $content);
	if ( $paragraph_tag ) $content = preg_replace('#<p>|</p>#', '', $content);
	return trim($content);
}

function my_shortcode_helper($content,$paragraph_tag=false,$br_tag=false){
	 return delete_htmltags( do_shortcode(shortcode_unautop($content)), $paragraph_tag, $br_tag );
}

function wpex_clean_shortcodes($content){   
	$array = array ( '<p>[' => '[', ']</p>' => ']', ']<br />' => ']','<p> </p>'=>'');
	$content = strtr($content, $array);
	return $content;
}
add_filter('the_content', 'wpex_clean_shortcodes');

/**
 * code - to print the shortcode's source code version
 **/
add_shortcode('code','my_code');
function my_code($attrs, $content=null, $shortcodename =""){
	$array = array ('['=>'&#91;',']'=>'&#93;','/'=>'&#47;','<'=>'&#60;','>'=>'&#62;','<br />'=>'&nbsp;');
	$content = strtr($content, $array);
	$out = "<pre>{$content}</pre>";
return $out;	
}?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/others.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/widgets.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/social.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/map.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/tabs.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/toggles.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/team.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/testimonials.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/contactform.php'); ?>
<?php require_once(TEMPLATEPATH.'/framework/theme_shortcodes/theme.php'); ?>