<?php wp_reset_query();
	global $post;
	
	function shop_sidebar( $arg ) {
		if(function_exists('dynamic_sidebar') && dynamic_sidebar( $arg ) ):
		else:
			if(function_exists('dynamic_sidebar') && dynamic_sidebar(('shop-everywhere-sidebar')) ): endif;
		endif;
	}
	
	function display_everywhere_sidebar( $id ) {
		$tpl_default_settings = get_post_meta($id,'_dt_post_settings',TRUE);
		$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
		
		if ( !array_key_exists("disable-everywhere-sidebar",$tpl_default_settings) ):
			if(function_exists('dynamic_sidebar') && dynamic_sidebar(('display-everywhere-sidebar')) ): endif;
		endif;
	}
	
	if( ("product" === get_post_type()) and (is_post_type_archive('product')) ):
		display_everywhere_sidebar(get_option('woocommerce_shop_page_id'));
		shop_sidebar('page-'.get_option('woocommerce_shop_page_id').'-sidebar');
		
	elseif( class_exists('woocommerce') && is_product_category() ): 
		shop_sidebar('product-category-'.get_query_var('product_cat').'-sidebar');
	
	elseif( class_exists('woocommerce') && is_product_tag() ):
		shop_sidebar('product-tag-'.get_query_var('product_tag').'-sidebar');
		
	elseif( is_singular('product') ):
		shop_sidebar('product-'.$post->ID.'-sidebar');
	
	elseif(class_exists('woocommerce') && ( is_cart() || is_checkout() || is_order_received_page() || is_account_page() )):
		display_everywhere_sidebar($post->ID);
		shop_sidebar('page-'.$post->ID.'-sidebar');
	
	elseif( class_exists('woocommerce') && is_page(get_option('woocommerce_change_password_page_id')) ):
		display_everywhere_sidebar(get_option('woocommerce_change_password_page_id'));
		shop_sidebar('page-'.get_option('woocommerce_change_password_page_id').'-sidebar');
		
	elseif( class_exists('woocommerce') && is_page(get_option('woocommerce_edit_address_page_id')) ):
		display_everywhere_sidebar(get_option('woocommerce_edit_address_page_id'));
		shop_sidebar('page-'.get_option('woocommerce_edit_address_page_id').'-sidebar');
		
	elseif( class_exists('woocommerce') && is_page(get_option('woocommerce_view_order_page_id')) ):
		display_everywhere_sidebar(get_option('woocommerce_view_order_page_id'));
		shop_sidebar('page-'.get_option('woocommerce_view_order_page_id').'-sidebar');

	elseif( is_category() ):
		if(function_exists('dynamic_sidebar') && dynamic_sidebar(('category-'.get_query_var('cat').'-sidebar')) ):
		else:
			if(function_exists('dynamic_sidebar') && dynamic_sidebar(('display-everywhere-sidebar')) ): endif;
		endif;
	
	elseif( is_page() || ( is_single() and 'post'== get_post_type() ) ):
		display_everywhere_sidebar($post->ID);		
		if( is_page() ):
			if(function_exists('dynamic_sidebar') && dynamic_sidebar(('page-'.get_the_ID().'-sidebar')) ):  endif;
		elseif( is_single() and "post" === get_post_type() ):
			if(function_exists('dynamic_sidebar') && dynamic_sidebar(('post-'.get_the_ID().'-sidebar')) ):  endif;
		endif;
	else:
		if(function_exists('dynamic_sidebar') && dynamic_sidebar(('display-everywhere-sidebar')) ): endif;
	endif;?>