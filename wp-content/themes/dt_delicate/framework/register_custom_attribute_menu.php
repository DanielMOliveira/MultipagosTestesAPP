<?php

/**
 *  dt_alternate_to_lambada() - used in _add_fields() 
 *
 **/
 function dt_alternate_to_lambada($arg) { return '{' . $arg . '}'; }


/**
 * Proof of concept for how to add new fields to nav_menu_item posts in the WordPress menu editor.
 * @author Weston Ruter (@westonruter), X-Team
 */
 
add_action( 'init', array( 'XTeam_Nav_Menu_Item_Custom_Fields', 'setup' ) );
 
class XTeam_Nav_Menu_Item_Custom_Fields {
	static $options = array(
		'item_tpl' => '
			<p class="additional-menu-field-{name} description description-thin">
				<label for="edit-menu-item-{name}-{id}">
					{label}<br>
					<input
						type="{input_type}"
						id="edit-menu-item-{name}-{id}"
						class="widefat code edit-menu-item-{name}"
						name="menu-item-{name}[{id}]"
						value="{value}">
				</label>
			</p>
		',
	);
 
	static function setup() {
		// @todo we can do some merging of provided options from WP options for from config
		self::$options['fields'] = array(
			'menu-icon' => array(
				'name' => 'menu_icon',
				'label' => __('Icon Class', 'dt_delicate'),
				'container_class' => 'menu-icon-container',
				'input_type' => 'textbox',
			),
		);
 
		#add_filter( 'wp_edit_nav_menu_walker', function () { return 'XTeam_Walker_Nav_Menu_Edit'; });
		add_filter( 'wp_edit_nav_menu_walker', 'dt_wp_edit_nav_menu_walker');
		function dt_wp_edit_nav_menu_walker() { return 'XTeam_Walker_Nav_Menu_Edit'; }
		
		
		add_filter( 'xteam_nav_menu_item_additional_fields', array( __CLASS__, '_add_fields' ), 10, 5 );
		add_action( 'save_post', array( __CLASS__, '_save_post' ) );
	}
 
	static function get_fields_schema() {
		$schema = array();
		foreach(self::$options['fields'] as $name => $field) {
			if (empty($field['name'])) {
				$field['name'] = $name;
			}
			$schema[] = $field;
		}
		return $schema;
	}
 
	static function get_menu_item_postmeta_key($name) {
		return '_menu_item_' . $name;
	}

	
 
	/**
	 * Inject the 
	 * @hook {action} save_post
	 */
	static function _add_fields($new_fields, $item_output, $item, $depth, $args) {
		$schema = self::get_fields_schema($item->ID);
		foreach($schema as $field) {
			$field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
			$field['id'] = $item->ID;
			
			// lambarda function doesn't work in loweser version of php 1.3
			/*$new_fields .= str_replace( 
				array_map(function($key){ return '{' . $key . '}'; }, array_keys($field)), #Search
				array_values(array_map('esc_attr', $field)),	# Replce
				self::$options['item_tpl']	#Subject
			);*/
			
			$new_fields .= str_replace(
				array_map( "dt_alternate_to_lambada", array_keys($field) ),  #search - dt_alternate_to_lambada
				array_values(array_map('esc_attr', $field)), #Replce
				self::$options['item_tpl'] #Subject
			);
			
			
		}
		return $new_fields;
	}
 
	/**
	 * Save the newly submitted fields
	 * @hook {action} save_post
	 */
	static function _save_post($post_id) {
		if (get_post_type($post_id) !== 'nav_menu_item') {
			return;
		}
		$fields_schema = self::get_fields_schema($post_id);
		foreach($fields_schema as $field_schema) {
			$form_field_name = 'menu-item-' . $field_schema['name'];
			if (isset($_POST[$form_field_name][$post_id])) {
				$key = self::get_menu_item_postmeta_key($field_schema['name']);
				$value = stripslashes($_POST[$form_field_name][$post_id]);
				update_post_meta($post_id, $key, $value);
			}
		}
	}
 
}
 
require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
class XTeam_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
	function start_el(&$output, $item, $depth, $args) {
		$item_output = '';
		parent::start_el($item_output, $item, $depth, $args);
		$new_fields = apply_filters( 'xteam_nav_menu_item_additional_fields', '', $item_output, $item, $depth, $args );
		// Inject $new_fields before: <div class="menu-item-actions description-wide submitbox">
		if ($new_fields) {
			$item_output = preg_replace('/(?=<div[^>]+class="[^"]*submitbox)/', $new_fields, $item_output);
		}
		$output .= $item_output;
	}
}?>