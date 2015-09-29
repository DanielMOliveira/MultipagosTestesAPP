<?php 
add_shortcode('team','my_team');
function my_team($attrs, $content=null, $shortcodename =""){
	$dir = get_template_directory()."/images/sociable/team-social/";
	$sociables_icons = mytheme_listImage($dir);
	$sociables = array_values($sociables_icons);
	
	$attributes = array('name'=>'','image'=>'','role'=>'');
	foreach($sociables as $sociable):
		$attributes[$sociable] = '';
	endforeach;
	
	extract(shortcode_atts($attributes, $attrs));


	$image = empty($image)? IAMD_BASE_URL."images/dummy-images/team.jpg" : $image;
	$image = "<div class='image'><img src='{$image}' alt='member-image' /></div>";
	$name  = empty($name) ? "" : "<h4>{$name}</h4>";
	$role  = empty($role) ? "" : "<h6>{$role}</h6>";
	
	$s = "";
	$path = IAMD_BASE_URL."/images/sociable/";
	foreach($sociables as $sociable):
		$img = array_search($sociable,$sociables_icons);
	    $s .=  empty($$sociable) ? "" : "<li><a href='{$$sociable}' target='_blank'> <img src='{$path}hover/{$img}' alt='{$sociable}'/> <img src='{$path}team-social/{$img}' alt='{$sociable}'/></a></li>";
	endforeach;
	
	$s = !empty($s) ? "<ul class='social-icons'>$s</ul>":"";
	$out  = '<div class="team">';
	$out .= $image.$name.$role.$s;
	$out .= '</div>';
return $out;	
}?>