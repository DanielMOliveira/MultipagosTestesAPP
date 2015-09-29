<?php add_action('init', 'portfolio_register');
function portfolio_register() {
		$labels = array(
		'name' => __('Portfolios','dt_delicate'),
		'all_items'=>__('All Portfolios','dt_delicate'),
		'singular_name' =>__('Portfolio Entry','dt_delicate'),
		'add_new' => __('Add New','dt_delicate'),
		'add_new_item' => __('Add New Portfolio Entry','dt_delicate'),
		'edit_item' => __('Edit Portfolio Entry','dt_delicate'),
		'new_item' => __('New Portfolio Entry','dt_delicate'),
		'view_item' => __('View Portfolio Entry','dt_delicate'),
		'search_items' => __('Search Portfolio Entries','dt_delicate'),
		'not_found' =>  __('No Portfolio Entries found','dt_delicate'),
		'not_found_in_trash' => __('No Portfolio Entries found in Trash','dt_delicate'), 
		'parent_item_colon' => ''
	);
	
	$args = array(
		'labels' => $labels,
		'description'=>'This is cstom post type to hold Portfolio items',
		'public' => true,
		'has_archive' =>true,
		'show_ui' => true,
		'capability_type' => 'post',
		'rewrite' => array('slug'=>'portfolio','with_front'=>true),
		'query_var' => true,
		'show_in_nav_menus'=> false,
		'menu_position' => 21,
		'supports' => array('title','thumbnail','excerpt','editor','comments'),
		'menu_icon' => IAMD_FW_URL.'images/icon_portfolio.png',
		'show_in_nav_menus' => true
		
	);
	
	 register_post_type( 'portfolio',$args);
	 
	 register_taxonomy("portfolio_entries", 
		array("portfolio"), 
		array(	"hierarchical" => true, 
		"label" => "Categories", 
		"singular_label" => "Category", 
		"rewrite" => true,
		"query_var" => true
	));  
}

add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns");
add_action("manage_posts_custom_column",  "portfolio_columns_display",10,2);
function portfolio_edit_columns($portfolio_columns){
	$portfolio_columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"portfolio-image"=>"Image",
		"title" => "Title",
		"author"=>"Author",
		"portfolio_entries"=>"Categories",
		"comments"=>"<span class='vers'><img src='".home_url()."/wp-admin/images/comment-grey-bubble.png' alt='Comments'></span>",
		"date"=>"Date"
	);

	return $portfolio_columns;
}
 
function portfolio_columns_display($portfolio_columns,$id){
	global $post;
	switch ($portfolio_columns):
		case "portfolio-image":
		
			  $image = wp_get_attachment_image(get_post_thumbnail_id($id),'thumb');
			  if(!empty($image)):
			  	echo $image;
			  else:
			  	$data = get_post_meta( $id, '_portfolio_settings', true );
				$items = isset($data["items"]) ? $data["items"] : NULL;
				
				if(!empty($items)):
					if(is_numeric($items[0])):
						wp_get_attachment_image($items[0]);
					else:
						global $wp_embed;
						echo $wp_embed->run_shortcode("[embed width='300']".$items[0]."[/embed]");					
					endif;
				else:
				  	echo __("No Featured Image",'dt_delicate');
				endif;	
			  endif;
		break;
		
		case "portfolio_entries":
			echo get_the_term_list($post->ID, 'portfolio_entries', '', ', ','');
		break;
	endswitch;
}

function save_custom_post ( $post_id, $post = null ) {
  global $post;
   if ( !$post ) $post = get_post($post_id);
   if ( !current_user_can( 'edit_posts', $post->ID ) ) return;
   if ( 'portfolio' != $post->post_type ) return;
   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
   
   $terms = wp_get_object_terms($post->ID,'portfolio_entries');
   if( empty( $terms) ):
      wp_set_object_terms( $post->ID, 'Uncategorized', 'portfolio_entries',true );
   endif;
   
}
add_action("pre_post_update","save_custom_post", 10, 2 );
add_action( "save_post", "save_custom_post", 10, 2 );
?>