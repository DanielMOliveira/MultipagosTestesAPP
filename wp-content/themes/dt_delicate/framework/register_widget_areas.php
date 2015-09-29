<?php 
	#Display Everywhere
	register_sidebar(array(
		'name' 			=>	'Display Everywhere',
		'id'			=>	'display-everywhere-sidebar',
		'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> 	'</aside>',
		'before_title' 	=> 	'<h3 class="widgettitle"><span>',
		'after_title' 	=> 	'</span></h3>'));
		
if( class_exists('woocommerce')	):
	#Shop Everywhere Sidebar
	register_sidebar(array(
		'name' 			=>	'Shop Everywhere',
		'id'			=>	'shop-everywhere-sidebar',
		'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> 	'</aside>',
		'before_title' 	=> 	'<h3 class="widgettitle"><span>',
		'after_title' 	=> 	'</span></h3>'));
endif;

	#Footer Columnns		
	$footer_columns =  mytheme_option('general','footer-columns');
	mytheme_footer_widgetarea($footer_columns);
	
	#Custom sidebars for Pages
	$page = mytheme_option("widgetarea","pages");	
	$page = !empty($page) ? $page : array();
	$widget_areas_for_pages = array_filter(array_unique($page));
	foreach($widget_areas_for_pages as $page_id):
		$title = get_the_title($page_id);	
		register_sidebar(array(
			'name' 			=>	"Page: {$title}",
			'id'			=>	"page-{$page_id}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;
	
	#Custom sidebars for Posts
	$posts = mytheme_option("widgetarea","posts");
	$posts = !empty($posts) ? $posts : array();
	$widget_areas_for_posts = array_filter(array_unique($posts));
	foreach($widget_areas_for_posts as $post_id):
		$title = get_the_title($post_id);	
		register_sidebar(array(
			'name' 			=>	"Post: {$title}",
			'id'			=>	"post-{$post_id}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;
	#Custom sidebars for categories 
	$cats = mytheme_option("widgetarea","cats");
	$cats = !empty($cats) ? $cats : array();
	$widget_areas_for_cats = array_filter(array_unique($cats));
	foreach($widget_areas_for_cats as $cat_id):
		$title = get_the_category_by_ID($cat_id);
		register_sidebar(array(
			'name' 			=>	"Category: {$title}",
			'id'			=>	"category-{$cat_id}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;


if( class_exists('woocommerce')	):
	#Custom Sidebars for Product
	$products = mytheme_option("widgetarea","products");
	$products = !empty($products) ? $products : array();
	$widget_areas_for_products = array_filter(array_unique($products));
	foreach($widget_areas_for_products as $id):
		$title = get_the_title($id);
		register_sidebar(array(
			'name' 			=>	"Product: {$title}",
			'id'			=>	"product-{$id}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;


	#Custom Sidebars for Product Category
	$product_categories = mytheme_option("widgetarea","product-category");
	$product_categories = !empty($product_categories) ? $product_categories : array();
	$widget_areas_for_product_categories = array_filter(array_unique($product_categories));
	
	foreach($widget_areas_for_product_categories as $id):
	
		$title = $wpdb->get_var( $wpdb->prepare("SELECT name FROM $wpdb->terms  WHERE term_id = %s",$id));
		$slug  = $wpdb->get_var( $wpdb->prepare("SELECT slug FROM $wpdb->terms  WHERE term_id = %s",$id));	
		
		register_sidebar(array(
			'name' 			=>	"Product Category: {$title}",
			'id'			=>	"product-category-{$slug}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;
	
	#Custom Sidebars for Product Tag
	$product_tags = mytheme_option("widgetarea","product-tag");
	$product_tags = !empty($product_tags) ? $product_tags : array();
	$widget_areas_for_product_tags = array_filter(array_unique($product_tags));
	foreach($widget_areas_for_product_tags as $id):
		$title = $wpdb->get_var( $wpdb->prepare("SELECT name FROM $wpdb->terms  WHERE term_id = %s",$id));
		$slug  = $wpdb->get_var( $wpdb->prepare("SELECT slug FROM $wpdb->terms  WHERE term_id = %s",$id));	
		register_sidebar(array(
			'name' 			=>	"Product Tag: {$title}",
			'id'			=>	"product-tag-{$slug}-sidebar",
			'before_widget' => 	'<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> 	'</aside>',
			'before_title' 	=> 	'<h3 class="widgettitle"><span>',
			'after_title' 	=> 	'</span></h3>'));
	endforeach;
endif;?>