<?php 
add_theme_support( 'woocommerce' );

global $product_container_class;

//register my own styles, remove wootheme stylesheet
if(!is_admin()){
	add_action('init', 'dt_woocommerce_register_assets');
}

function dt_woocommerce_register_assets() {
	wp_enqueue_style( 'dt-woocommerce-css', IAMD_FW_URL.'woocommerce/style.css');
}


//	Disable WooCommerce styles 
define('WOOCOMMERCE_USE_CSS', false);

/*No of products per row*/
add_filter( 'loop_shop_columns', 'dt_woocommerce_loop_columns' );	
if (!function_exists('dt_woocommerce_loop_columns')) {
	function dt_woocommerce_loop_columns() {
		
		$shop_layout = mytheme_option('woo',"shop-page-product-layout");
		$shop_layout = !empty( $shop_layout ) ? $shop_layout : "one-half-column";
		$shop_layout = ( $shop_layout === "one-half-column" ) ? 2 : 3;
		
		return $shop_layout;
	}
}

// No of products per page
add_filter( 'loop_shop_per_page', 'dt_woocommerce_product_count' );
if (!function_exists('dt_woocommerce_product_count')) {
	function dt_woocommerce_product_count() {
		$shop_product_per_page = trim(stripslashes(mytheme_option('woo','shop-product-per-page')));
		$shop_product_per_page = !empty( $shop_product_per_page)  ? $shop_product_per_page : 10;
		return $shop_product_per_page;
	}
}


#update woocommerce v2
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 ); #remove result count above products
#remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 ); #remove woocommerce ordering dropdown
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 ); #remove rating
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 ); //remove woo pagination

#Adjust markup on all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10); # To remove add to cart in shop
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action( 'woocommerce_pagination', 'woocommerce_catalog_ordering', 20 );
remove_action( 'woocommerce_pagination', 'woocommerce_pagination', 10 );


add_action( 'woocommerce_before_main_content', 'dt_woocommerce_before_main_content', 10);
if( !function_exists('dt_woocommerce_before_main_content') ) {
	function dt_woocommerce_before_main_content() {
		global $product_container_class;
		$product_layout = mytheme_option('woo',"shop-page-product-layout");
		$product_layout = !empty( $product_layout ) ? $product_layout : "one-half-column";
		$product_layout = ( $product_layout === "one-half-column" ) ? 2 : 3;
		
		if( is_shop() ):
			#Page Settings
			$tpl_default_settings = get_post_meta( get_option('woocommerce_shop_page_id') ,'_tpl_default_settings',TRUE);
			$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
		
			$page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
			$show_sidebar = false;
			
		elseif( is_product() ):
			$page_layout = mytheme_option('woo',"product-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			
		elseif( is_product_category() ):
			$page_layout = mytheme_option('woo',"product-category-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";

		elseif( is_product_tag() ):
			$page_layout = mytheme_option('woo',"product-tag-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
		endif;
		
		#Define Product Layout 
		switch( $product_layout ):
			case '2':
				$product_container_class = "product-two-column";
			break;
			
			case '3':
				$product_container_class = "product-three-column";
			break;
		endswitch;
		
		#Define Page Layout
		switch($page_layout):
			case 'with-left-sidebar':
				$product_container_class = $product_container_class."-with-sidebar";
				$page_layout =  $page_layout." page-with-sidebar";
			break;
			
			case 'with-right-sidebar':
				$product_container_class = $product_container_class."-with-sidebar";
				$page_layout =  $page_layout." page-with-sidebar";				
			break;
			
			default:
				$product_container_class = $product_container_class;
			break;
		endswitch;
		

		echo  "<!-- **Primary Section** -->";
		echo  "<section id='primary' class='{$page_layout}'>";
	}
}



add_action( 'woocommerce_after_main_content', 'dt_woocommerce_after_main_content', 20);
if( !function_exists('dt_woocommerce_after_main_content') ) {
	function dt_woocommerce_after_main_content() {

		 echo " </section><!-- **Primary Section** -->";		

		if( is_shop() ):
		
			#Page Settings
			$tpl_default_settings = get_post_meta( get_option('woocommerce_shop_page_id') ,'_tpl_default_settings',TRUE);
			$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
			
			$page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
			
		elseif( is_product() ):
			$page_layout = mytheme_option('woo',"product-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";

		elseif( is_product_category() ):
			$page_layout = mytheme_option('woo',"product-category-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			
		elseif( is_product_tag() ):
			$page_layout = mytheme_option('woo',"product-tag-layout");
			$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
			
		endif;


		$show_sidebar = false;
		$sidebar_class= "";
			
		switch($page_layout):
			case 'with-left-sidebar':
				$show_sidebar 	= 	true;
				$sidebar_class 	=	"left-sidebar";
			break;
			
			case 'with-right-sidebar':
				$show_sidebar 	= 	true;
			break;
		endswitch;
		

	  if($show_sidebar):
	  	echo "<!-- **Secondary Section ** -->";
		echo "<section id='secondary' class='{$sidebar_class}'>";
		get_sidebar();
		echo '</section><!-- **Secondary Section - End** -->';
	  endif;
	} }


#Prodcut Loop
#
# wrap products on overview pages into an extra div for improved styling options. adds "product_on_sale" class if prodct is on sale
#
add_action( 'woocommerce_before_shop_loop_item', 'dt_woocommerce_shop_overview_extra_div', 5);
function dt_woocommerce_shop_overview_extra_div() {
	global $product, $product_container_class;
	
	$class = "";
	if( $product->is_featured() )
		$class .= " featured-product ";
		
	if( $product->is_on_sale() )
		$class .= " on-sale-product ";

	if( $product->is_in_stock() )
		$class .= " in-stock-product ";
	else	
		$class .= " out-of-stock-product ";
	
	$out = '<!-- Prodcut Wrapper -->';
	$out .= "<div class='product-wrapper {$class} {$product_container_class}'>";
	$out .= '<!-- Product Thumnail -->';
	$out .= "<div class='product-thumb'>";
	echo $out; }

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'dt_woocommerce_show_product_loop_sale_flash', 10 );
function dt_woocommerce_show_product_loop_sale_flash() {
	global $product;
	$out = "";
	if( $product->is_on_sale() and $product->is_in_stock() )
		$out = '<span class="onsale">'.__('Sale!','dt_delicate').'</span>';
		
	elseif(!$product->is_in_stock())
		$out = '<span class="out-of-stock">'.__('Out of Stock','dt_delicate').'</span>';
	
	if( $product->is_featured() )
		$out .= '<span class="featured">'.__('Featured','dt_delicate').'</span>';
		
	echo $out;
}

#Thumbnail
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'dt_woocommerce_template_loop_product_thumbnail', 10);
function dt_woocommerce_template_loop_product_thumbnail() {
	global $product;
	
	$out = "";
	$id = get_the_ID();
	$image =  get_the_post_thumbnail( $id );
	$image = !empty( $image ) ? $image : '<img  width="100%" height="100%" src="'.get_template_directory_uri() . '/images/dummy-images/dummy-large.jpg'.'" />';
	
	$rating = $product->get_rating_html(); //get rating
	$rating = !empty( $rating ) ? "<div class='product-rating-wrapper'><div class='product-rating-container'>{$rating}</div></div>" : "";
	
	$out .= $image;
	$out .= $rating;
	$out .= "</div><!-- Product Thumnail -->";
	$out .= '<!-- Product Details -->';
	$out .= "<div class='details'>";
	
echo $out;	 }

add_action( 'woocommerce_after_shop_loop_item', 'dt_woocommerce_shop_overview_show_price', 999);
function dt_woocommerce_shop_overview_show_price() {
	global $product;
	ob_start();
	woocommerce_template_loop_price();
	$price = ob_get_clean();
	echo $price; }

add_action( 'woocommerce_after_shop_loop_item', 'dt_woocommerce_shop_overview_extra_div_close', 1000);
function dt_woocommerce_shop_overview_extra_div_close() {

	$out = "";	
	global $product;
	if ($product->product_type == 'bundle' ){
		$product = new WC_Product_Bundle($product->id);
	}

	ob_start();
	woocommerce_template_loop_add_to_cart();
	$add_to_cart = ob_get_clean();
	
	if( !empty($add_to_cart) ) {
		$add_to_cart = str_replace(' class="',' class="small ',$add_to_cart);
	}

	$out .= $add_to_cart;
	$out .= '</div><!-- Product Details -->';
	$out .= "</div> <!-- Prodcut Wrapper End-->";
	echo $out; }


#To Pagination
add_action( 'woocommerce_after_shop_loop', 'dt_woocommerce_after_shop_loop', 10);
function dt_woocommerce_after_shop_loop() { ?>
	<div class="pagination">
    	<div class="prev-post"><?php previous_posts_link('<span class="icon-double-angle-left"></span> Prev');?></div>
        <?php   echo my_pagination(); ?>
        <div class="next-post"><?php next_posts_link('Next <span class="icon-double-angle-right"></span>');?></div>
	</div>
<?php    
}


#SingleProduct 

#Showing Releated Products
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',20);
remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products',10);
add_action( 'woocommerce_after_single_product_summary', 'dt_woocommerce_output_related_products', 20);

function dt_woocommerce_output_related_products() {
	
	$page_layout = mytheme_option('woo',"product-layout");
	$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
	
	$related_products = ( $page_layout === "content-full-width" ) ? 3 : 2;
	
	$output = "";
	ob_start();
	woocommerce_related_products($related_products,$related_products); // X products, X columns
	$content = ob_get_clean();
	if($content):
		$content =  str_replace('<h2>','<div class="border-title"><h2>', $content);
        $content =  str_replace('</h2>','<span></span></h2></div>', $content);
		$output .= "<div class='related-products-container'>{$content}</div>";
	endif;
	echo $output;
}

#Showing Upsell Products( You may also like)
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product', 'woocommerce_upsell_display',10);
add_action( 'woocommerce_after_single_product_summary', 'dt_woocommerce_output_upsells', 21); // needs to be called after the "related product" function to inherit columns and product count
function dt_woocommerce_output_upsells() {
	
	$page_layout = mytheme_option('woo',"product-layout");
	$page_layout = !empty($page_layout) ? $page_layout : "content-full-width";
	
	$upsell_products = ( $page_layout === "content-full-width" ) ? 3 : 2;
	
	$output = "";
	ob_start();
	woocommerce_upsell_display($upsell_products,$upsell_products); // X products, X columns
	$content = ob_get_clean();
	if($content):
		$content =  str_replace('<h2>','<div class="border-title"><h2>', $content);
        $content =  str_replace('</h2>','<span></span></h2></div>', $content);
		$output .= "<div class='upsell-products-container'>{$content}</div>";
	endif;
	echo $output;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action('woocommerce_before_single_product_summary','dt_woocommerce_show_product_sale_flash',10);
function dt_woocommerce_show_product_sale_flash() {
	global $product;
	$out = "";
	if( $product->is_on_sale() and $product->is_in_stock() )
		$out = '<span class="onsale">'.__('Sale!','woocommerce').'</span>';
		
	elseif(!$product->is_in_stock())
		$out = '<span class="out-of-stock">'.__('Out of Stock','dt_delicate').'</span>';
		
	echo $out;
}

remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'dt_woocommerce_show_product_images', 20 );
function dt_woocommerce_show_product_images() {
	
	global $product;
	ob_start();
	woocommerce_show_product_images();
	$content = ob_get_clean();
	
	if( $product->is_featured() )
		$content =  str_replace('<div class="images">','<div class="images"><span class="featured-product">'.__('Featured','dt_delicate').'</span>', $content);
	
	echo $content;
}
?>