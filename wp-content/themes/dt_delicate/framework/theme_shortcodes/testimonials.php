<?php 
add_shortcode('testimonial_item','my_testimonial_item');
function my_testimonial_item($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array('name'=>'','image'=>''), $attrs));
	
	$out  = "<li>";
	$out .= '<div class="testimonial-wrapper">';
	
	$content = my_shortcode_helper($content);
	$name	 = !empty($name) ? "<span class='author-name'> - {$name}</span>" : "";
	$content = !empty($content) ? "{$content}":"";
	
	if(!empty($image)):
		$img = "<img src='{$image}' alt='Testimonial Image' />";
		$out .= "<div class='rounded-image border'><span>".$img.'</span></div>';
	endif;
	
	$out .= '<div class="testimonial-content-wrapper">';
	$out .= '	<div class="testimonial-content">';
	$out .= "	<p>$content</p>$name";
	$out .= '	</div>';
	$out .= '</div>';
	
	$out .= '</div>';
	$out .= "</li>";
	
return $out;
}

add_shortcode('testimonial','my_testimonial');
function my_testimonial($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array('name'=>'','image'=>''), $attrs));
	
	$content = my_shortcode_helper($content);
	$content = !empty($content) ? "<q>{$content}</q>":"";	
	$name	 = !empty($name) ? "<cite> - {$name}</cite>" : "";
	$content = ( !empty($content) && !empty($name) ) ?"<blockquote>$content$name</blockquote>": "";
	
	$out = '<div class="testimonial">';
	if(!empty($image)):
		$img = "<img src='{$image}' alt='' />";
		$out .= "<div class='author'>$img</div>";
	endif;
	$out .= $content;
	$out .= '</div>';
	
return $out;
}?>