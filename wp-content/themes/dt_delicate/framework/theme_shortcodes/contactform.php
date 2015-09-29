<?php 
add_shortcode('contact-form-type1', 'contact_form_type1');
function contact_form_type1( $attrs, $content = null ) {
	extract(shortcode_atts(array('to' => get_option('admin_email'),'title'=>__('Send us a message','dt_delicate')), $attrs));
	$path = IAMD_FW_URL."sendmail.php";
	$title = !empty($title) ?"<h3>$title</h3>": "";
	
	$out  = $title; 
	$out .= '<div class="message"></div>';	
	$out .= "<form class='contact-form' action='{$path}' method='get'>";
	$out .= "<p><input type='hidden' name='to'  value='{$to}' /></p>";
	$out .= '<p class="column one-half"><input name="name" title="please enter vaid name" type="text" placeholder="'.__("Your Name",'dt_delicate').'" required></p>';
	$out .= '<p class="column one-half last"><input name="email" type="email"  autocomplete="off" placeholder="'.__("E-mail",'dt_delicate').'" required></p>';
	$out .= '<p class="clear"><textarea name="message" cols="5" rows="3"  placeholder="'.__("Message",'dt_delicate').'" required></textarea></p>';
	$out .= '<p><input name="submit" type="submit" value="'.__('Send Email','dt_delicate').'"></p>';	
	$out .= '</form>';
return $out;
}?>