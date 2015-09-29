<?php
/** widget
  * Objective:
  *		1.Used to add widgets in content area.
**/
function my_shortcode_widget($attrs){
	
	global $wp_widget_factory;
	extract(shortcode_atts(array( 'widget_name' => FALSE,'widget_class_name' => FALSE ), $attrs));
	
   	foreach($attrs as $key=>$value):
		switch($key):
			case 'exclude_replies':
			case 'time':
			case 'display_avatar':
				$instance[$key] = ( $value == "yes" ) ? $value : NULL;
			break;
			
			default:
				$instance[$key] = $value;			
			break;
		endswitch;
	endforeach;
	
	$instance = array_filter($instance);
	

    $widget_name = esc_html($widget_name);
	
	 if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
	 	 $wp_class = 'WP_Widget_'.ucwords(strtolower($class));

       if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
          return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct",'dt_delicate'),'<strong>'.$class.'</strong>').'</p>';
        else:
          $class = $wp_class;
        endif;
	 endif;

    ob_start();
    the_widget($widget_name, $instance, array('before_widget' => '', 'after_widget' => '', 'before_title' => '', 'after_title' => ''));
    $output = ob_get_contents();
    ob_end_clean();
return $output;
}
add_shortcode('widget','my_shortcode_widget');?>