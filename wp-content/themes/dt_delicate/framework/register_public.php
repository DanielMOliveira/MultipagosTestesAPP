<?php											####################################################################################
												#############################--------------------------#############################
												#############################     FRONT END ACTIONS    #############################
												#############################--------------------------#############################
												####################################################################################

function mytheme_blog_title() {
	$the_content = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', get_option('blogname'));  # strip shortcodes, keep shortcode conten
	return $the_content;
}

function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

remove_action( 'wp_head', 'rel_canonical' );																								
add_action('wp_head', 'my_render_ie_pie',8);												
function my_render_ie_pie() {
	echo ' <!--[if IE]>
    <style type="text/css" media="screen">
			.ico-content.type1 .icon span, .product-overlay a, .product-overlay a span, h5.faq:before, .carousel-arrows a, .testimonial .author img, .testimonial .author, #secondary .widget h3.widgettitle a:before, .portfolio .image-overlay a, .portfolio .image-overlay a span, .team .social-icons li {
				behavior: url('.get_template_directory_uri().'/PIE.php);
               }
     </style>
     <![endif]-->';
     echo "\n";
}

#Remove rel attribute from the category list ( Validation purpose)
function remove_category_list_rel( $output ) {
    return str_replace( ' rel="category tag"', '', $output ); }
 
add_filter( 'wp_list_categories', 'remove_category_list_rel' );
add_filter( 'the_category', 'remove_category_list_rel' );
#To remove rel attribute from the category list

												
add_filter('widget_text', 'do_shortcode');

/** load_mytheme_textdomain()
  * Objective:
  *		To setup TEXT DOMAIN .
**/
add_action('after_setup_theme', 'load_mytheme_textdomain');
if ( ! function_exists('load_mytheme_textdomain')){
	function load_mytheme_textdomain(){
		load_theme_textdomain('dt_delicate',get_template_directory().'/languages');
	}
}
### --- ****  load_mytheme_textdomain() *** --- ###

#FILTER TO MODIFY THE DEFAULT CATEGORY WIDGET
add_filter('wp_list_categories', 'my_wp_list_categories');
function my_wp_list_categories($output) {
	if(strpos($output, "</span>") <= 0) {
		$output = str_replace('</a> (','<span> ',$output);
		$output = str_replace(')','</span></a> ',$output);
	}
	return $output;
}

add_filter('get_archives_link', 'my_wp_list_archive');
function my_wp_list_archive($output) {
	$output = str_replace('</a>&nbsp;(','<span> ',$output);
	$output = str_replace(')','</span></a> ',$output);
	return $output;
}

/** mytheme_default_navigation()
  * Objective:
  *		To setup default navigation  when no menu is selected
**/
function mytheme_default_navigation(){
	echo '<ul class="menu">';
		$args = array('depth'=>0,'title_li'=>'','echo'=>0,'post_type'=>'page','post_status'=>'publish'	);
		$pages = wp_list_pages($args);
		if($pages)
			echo $pages;
	echo '</ul>'; }
### --- ****  mytheme_default_navigation() *** --- ###


add_action('load_head_styles_scripts','plugin_head_styles_scripts');
	function plugin_head_styles_scripts(){

		#Theme urls for Style Picker
			$stickynav = ( mytheme_option("general","enable-sticky-nav") ) ? "enable" : "disable";
			$scroll = mytheme_option('general','disable-custom-scroll') ? "disable" : "enable";
			
			echo "\n <script type='text/javascript'>\n\t";
			echo "var mytheme_urls = {\n";
			echo "\t\t theme_base_url:'".IAMD_BASE_URL."'";
			echo "\n \t\t,framework_base_url:'".IAMD_FW_URL."'";
			echo "\n \t\t,ajaxurl:'".admin_url( 'admin-ajax.php' )."'";
			echo "\n \t\t,url:'".get_site_url()."'";
			echo "\n \t\t,stickynav:'".$stickynav."'";
			echo "\n \t\t,scroll:'".$scroll."'";
			echo "\n\t};\n";
			echo " </script>\n";
		#Theme urls for Style Picker End
		
	
		wp_enqueue_script('modernizr-script', IAMD_FW_URL.'js/public/modernizr-2.6.2.min.js');
		wp_enqueue_script('jquery');
		
		wp_enqueue_script('easing-script', IAMD_FW_URL.'js/public/easing.js',array(),false,true);
		wp_enqueue_script('nicescroll-script', IAMD_FW_URL.'js/public/jquery.nicescroll.min.js',array(),false,true);
		wp_enqueue_script('uitotop-script', IAMD_FW_URL.'js/public/jquery.ui.totop.min.js',array(),false,true);
		
		if(mytheme_option("general","enable-sticky-nav")):
			wp_enqueue_script('sticky-nav', IAMD_FW_URL.'js/public/jquery.sticky.js',array(),false,true);
		endif;

		
		
		wp_register_script('html5-script','http://html5shiv.googlecode.com/svn/trunk/html5.js',array(),'3.6.2',true);
		
		global $is_IE;
		if( $is_IE )
			wp_enqueue_script('html5-script');
		
		wp_enqueue_script('carouFredSel-script', IAMD_FW_URL.'js/public/jquery.carouFredSel-6.2.0-packed.js',array(),false,true);
		
		if( is_page_template('tpl-portfolio.php') ):
			wp_enqueue_script('isotope-script', IAMD_FW_URL.'js/public/isotope.js',array(),false,true);
		endif;	

		wp_enqueue_style('prettyphoto',IAMD_BASE_URL.'css/prettyPhoto.css');
		wp_enqueue_script('prettyphoto-script', IAMD_FW_URL.'js/public/jquery.prettyPhoto.js',array(),false,true);
		
		
		if( is_singular('portfolio')):
			wp_enqueue_script('fitvids-script', IAMD_FW_URL.'js/public/jquery.fitvids.js',array(),false,true);
			wp_enqueue_script('bx-script', IAMD_FW_URL.'js/public/jquery.bxslider.js',array(),false,true);
		endif;
		
		#Theme Picker 		
		if( mytheme_option("general","disable-picker") === NULL ):
			wp_enqueue_script('theme-cookies', IAMD_FW_URL.'js/public/jquery.cookie.js',array(),false,true);
			wp_enqueue_script('theme-picker', IAMD_FW_URL.'js/public/picker.js',array(),false,true);
		endif;
		
		wp_enqueue_script('mobile-script',IAMD_FW_URL.'js/public/jquery.mobilemenu.js',array(),false,true);
		wp_enqueue_script('tooltip-script',IAMD_FW_URL.'js/public/jquery.tipTip.minified.js',array(),false,true);
		wp_enqueue_script('viewport-script',IAMD_FW_URL.'js/public/jquery.viewport.js',array(),false,true);
		wp_enqueue_script('tabs-script',IAMD_FW_URL.'js/public/jquery.tabs.min.js',array(),false,true); //Tabs Shortcode
		wp_enqueue_script('custom-script',IAMD_FW_URL.'js/public/delicate-custom.js',array(),false,true);
		wp_enqueue_script('shortcodes-script',IAMD_FW_URL.'js/public/shortcodes.js',array(),false,true);
			
	}

												

/** mytheme_seo_meta()
  * Objective:
  *		To generate meta tags based on the backend options.
**/

if( !(mytheme_is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || mytheme_is_plugin_active('wordpress-seo/wp-seo.php')) ) {
	add_action( 'wp_head', 'mytheme_seo_meta',1 );
}

function mytheme_seo_meta(){
	global $post;
	$output = "";
	$meta_description = '';
	$meta_keywords = '';
	
	if( is_feed() )
		return;
		
	if ( is_404() || is_search() ) return;	
		
	# meta robots Noindex ,NoFollow
	if(is_category() && (mytheme_option('seo','use_noindex_in_cats_page'))):
		$output .= '<meta name="robots" content="noindex,follow" />'."\r";
	elseif(is_archive() && (mytheme_option('seo','use_noindex_in_archives_page'))):	
		$output .= '<meta name="robots" content="noindex,follow" />'."\r";
	elseif(is_tag() && !(mytheme_option('seo','use_noindex_in_tags_archieve_page'))):	
		$output .= '<meta name="robots" content="noindex,follow" />'."\r";
	endif;
	#End

	### Meta Description ###	
	if(is_page()):
		$meta_description = get_post_meta($post->ID,'_seo_description',true);
		if(empty($meta_description) && mytheme_option('seo','auto_generate_desc')):
			$meta_description =  substr( strip_shortcodes( strip_tags( $post->post_content ) ), 0, 155 );
		endif;
	#post	
	elseif(is_singular() || is_single()):
		$meta_description = get_post_meta($post->ID,'_seo_description',true);
		if(empty($meta_description) && mytheme_option('seo','auto_generate_desc')):
			$meta_description = trim(substr(strip_shortcodes( strip_tags( $post->post_content ) ), 0, 155 ));
		endif;
	#is_category()
	elseif(is_category()):
		#$categories = get_the_category();
		#$meta_description = $categories[0]->description;
		$meta_description = strip_tags(category_description());
	#is_tag()
	elseif(is_tag()):
		$meta_description = strip_tags(tag_description());
	#is_author
	elseif(is_author()):
	   $author_id  = get_query_var('author');
	   if(!empty($author_id)):
		   $meta_description = get_the_author_meta('description',$author_id);
	   endif;
	endif;

	if( !empty( $meta_description ) ) {
		$meta_description =  trim(substr($meta_description,0,155));
		$meta_description = htmlspecialchars($meta_description);
		$output .= "<meta name='description' content='{$meta_description}' />\r";
		
	}
	### Meta Description End###
	
	if(is_page()):
		$meta_keywords = get_post_meta($post->ID,'_seo_keywords',true);
	#post	
	elseif(is_singular() || is_single()):
		$meta_keywords = get_post_meta($post->ID,'_seo_keywords',true);
		
		#Use Categories in Keyword
		if(mytheme_option('seo','use_cats_in_meta_keword')):
			 $categories = get_the_category();
			 $c = '';
			 foreach($categories as $category):
		 		$c .= $category->name.',';
			 endforeach;
			 $c = substr(trim($c),"0",strlen(trim($c))-1);
		$meta_keywords = $meta_keywords.','.$c;	 
		endif;
		
		#Use Tags in Keyword
		if(mytheme_option('seo','use_tags_in_meta_keword')):
			 $posttags = get_the_tags();
			 $ptags='';
			 if($posttags):
			 	foreach($posttags as $posttag):
					$ptags .= $posttag->name.',';
				endforeach;
				$ptags = substr(trim($ptags),"0",strlen(trim($ptags))-1);
				$meta_keywords = $meta_keywords.','.$ptags;	 				
			 endif;
		endif;
	
	#Archive
	elseif(is_archive()):
	
		global $posts;
		$keywords = array();
		
		foreach($posts as $post ):
			# If attachment then use parent post id
			$id = ( is_attachment() ? $post->post_parent : ( !empty( $post->ID ) ? $post->ID : '' ) );
			
			$keywords_from_posts = get_post_meta($id,'_seo_keywords',true);
			if(!empty($keywords_from_posts)):
				$traverse = explode( ',', $keywords_from_posts );
				foreach ($traverse as $keyword):
					$keywords[] = $keyword;
				endforeach;
			endif;
			
			#Use Tags in Keyword
			if(mytheme_option('seo','use_tags_in_meta_keword')):
				$tags = get_the_tags($id);
				if($tags && is_array($tags)):
					foreach($tags as $tag):
						$keywords[] = $tag->name;
					endforeach;
				endif;
			endif;
			
			#Use categories in Keywords
			if(mytheme_option('seo','use_cats_in_meta_keword')):
		    	$categories = get_the_category($id); 
		      	foreach ( $categories as $category ):
			    	$keywords[] = $category->cat_name;
			   endforeach;			
			endif;
			
		endforeach;
		
		# Make keywords lowercase
		$keywords = array_unique($keywords);
		$small_keywords = array();
		$final_keywords = array();
		foreach ( $keywords as $word ):
			$final_keywords[]  = strtolower($word);
		endforeach;
		
		if(!empty($final_keywords)):
			$meta_keywords = implode(",",$final_keywords);
		endif;

		
	#search || 404 page	
	elseif( is_404() || is_search() ):
		$meta_keywords = '';
	endif;	
	if( !empty( $meta_keywords ) )
		$output .= "\t<meta name='keywords' content='{$meta_keywords}'/>\r";
	### Meta Keyword End###
	
	
	#Generate canonical_url
	if(mytheme_option('seo','use_canonical_urls')):
		$url = mytheme_canonical();
		if ($url)
			$output .= "<link rel='canonical' href='{$url}'/>\r";
	endif;
echo $output;	 }
### --- ****  mytheme_seo_meta() *** --- ###

add_action('wp_head','mytheme_appearance_load_fonts',7);
/** mytheme_appearance_load_fonts()
  * Objective:
  *		To load google fonts based on appearance settings in admin panel.
**/
function mytheme_appearance_load_fonts(){
	$custom_fonts = array();
	$output = "";
	
	$subset = mytheme_option('general','google-font-subset');
	if( $subset ){
		$subset = strtolower(str_replace(' ', '', $subset));
	}

	#Menu Section
	$disable_menu = mytheme_option("appearance","disable-menu-settings");
	if(empty($disable_menu)):
		$font = mytheme_option("appearance","menu-font");
		if(!empty($font)):
			$font = str_replace(" ", "+",$font);
			array_push($custom_fonts,$font);
		endif;
	endif; #Menu Secion End
	
	#Body Section
	$disable_boddy_settings =  mytheme_option("appearance","disable-boddy-settings");
	
	if(empty($disable_boddy_settings)):
		$font = mytheme_option("appearance","body-font");
		$font = str_replace(" ", "+",$font);
		if(!empty($font)):
			array_push($custom_fonts,$font);
		endif;
	endif;
	
	#Footer Section
	$disable_footer =  mytheme_option("appearance","disable-footer-settings");
	if(empty($disable_footer)):
		$footer_title_font = mytheme_option("appearance","footer-title-font");
		$footer_title_font = !empty($footer_title_font) ? str_replace(" ","+",$footer_title_font) : NULL;
		if( !empty($footer_title_font) ):
			array_push($custom_fonts,$footer_title_font);
		endif;
		
		$footer_content_font = mytheme_option("appearance","footer-content-font");
		$footer_content_font = !empty($footer_content_font) ? str_replace(" ","+",$footer_content_font) : NULL;
		if( !empty($footer_content_font) ):
			array_push($custom_fonts,$footer_content_font);
		endif;

	endif;#Footer Section End	
	
	#Typography Section
	$disable_typo = mytheme_option("appearance","disable-typography-settings");
	if(empty($disable_typo)):
		for($i=1; $i<=6; $i++):
			$font  = mytheme_option("appearance","H{$i}-font");
			if(!empty($font)):
				$font = str_replace(" ", "+",$font);
				array_push($custom_fonts,$font);
			endif;
		endfor;
	endif;#Typography Section End
	
	#404 Section
	$disable_404_settings = mytheme_option("specialty","disable-404-font-settings");
	if( empty($disable_404_settings)):
		$font = mytheme_option("specialty","message-font");
		if(!empty($font)):
			$font = str_replace(" ", "+",$font);
			array_push($custom_fonts,$font);
		endif;
	endif;
	
	#Buddha Bar Font
	if(mytheme_option('bbar','show-bbar')):
		$font = mytheme_option('bbar','message-font');
		if(!empty($font)):
			$font = str_replace(" ", "+",$font);
			array_push($custom_fonts,$font);
		endif;
	endif; 
	 
	if( !empty($custom_fonts) ):
		$custom_fonts = array_unique($custom_fonts);
		$font = implode(":300,400,400italic,700|",$custom_fonts);
		$font .= ":300,400,400italic,700|Arvo:400,700";
	 	$protocol = is_ssl() ? 'https' : 'http';
	    $query_args = array('family' => $font,'subset' =>$subset );
	 	wp_enqueue_style('mytheme-google-fonts', add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),array(),null);
	endif; }
### --- ****  mytheme_appearance_load_fonts() *** --- ###

add_action('wp_head','mytheme_appearance_css',9);
/** mytheme_appearance_css()
  * Objective:
  *		To generate inline style based on appearance settings in admin panel.
**/
function mytheme_appearance_css(){
	
	$output = NULL;
	
	#Layout Section
	if(mytheme_option("appearance","layout") == "boxed"):
		if(mytheme_option("appearance","bg-type") == "bg-patterns"):
			$pattern = mytheme_option("appearance","boxed-layout-pattern");
			$pattern_repeat = mytheme_option("appearance","boxed-layout-pattern-repeat");
			$pattern_opacity = mytheme_option("appearance","boxed-layout-pattern-opacity");
			$disable_color = mytheme_option("appearance","disable-boxed-layout-pattern-color");
			$pattern_color =  mytheme_option("appearance","boxed-layout-pattern-color");
			$output .= "body { ";
				if(!empty($pattern))
					$output .= "background-image:url('".IAMD_FW_URL."theme_options/images/patterns/{$pattern}');"; 
						
				$output .= "background-repeat:$pattern_repeat;";
				if(empty($disable_color)){
					if(!empty($pattern_opacity)){
						$color = hex2rgb($pattern_color);
						$output .= "background-color:rgba($color[0],$color[1],$color[2],$pattern_opacity); ";
					}else{
						$output .= "background-color:$pattern_color;";
					}
				}
			$output .= "}\r\t";
		
		elseif(mytheme_option("appearance","bg-type") == "bg-custom"):
			$bg = mytheme_option("appearance","boxed-layout-bg");
			$bg_repeat = mytheme_option("appearance","boxed-layout-bg-repeat");
			$bg_opacity = mytheme_option("appearance","boxed-layout-bg-opacity");
			$bg_color =  mytheme_option("appearance","boxed-layout-bg-color");
			$disable_color = mytheme_option("appearance","disable-boxed-layout-bg-color");
			$bg_position =  mytheme_option("appearance","boxed-layout-bg-position");
			$output .= "body { ";
			if(!empty($bg)) {
				$output .= "background-image:url($bg);";
				$output .= "background-repeat:$bg_repeat;";
				$output .= "background-position:$bg_position;";
			}
			
			if(empty($disable_color)){
				if(!empty($bg_opacity)){	
					$color = hex2rgb($bg_color);
					$output .= "background-color:rgba($color[0],$color[1],$color[2],$bg_opacity);";
				}else{
					$output .= "background-color:$bg_color;";
				}
			}
			$output .= "}\r\t";
		endif;
	endif;#Layout Section End
	
	#Menu Section
	$disable_menu = mytheme_option("appearance","disable-menu-settings");
	if(empty($disable_menu)):
			$font  = mytheme_option("appearance","menu-font");
			$size = mytheme_option("appearance","menu-font-size");
			$primary_color = mytheme_option("appearance","menu-primary-color");
			$secondary_color = mytheme_option("appearance","menu-secondary-color");
			
			if(!empty($font) || (!empty($primary_color) and $primary_color!= "#") || !empty($size)):
			 $output .= "nav#main-menu ul li a, .mobile-menu { ";
				if(!empty($font)) $output .= "font-family:{$font},sans-serif; ";
				if(!empty($primary_color) && ($primary_color !='#')) $output .= "color:{$primary_color}; ";
				if(!empty($size) and ($size > 0)) $output .= "font-size:{$size}px; ";
			 $output .= "}\r\t";	
			endif;
			
			if(!empty($secondary_color) and $secondary_color!= "#"):
			 $output .= "nav#main-menu ul > li:hover > a,#main-menu ul ul li.current_page_item a,#main-menu ul ul li.current_page_item ul li.current_page_item a,#main-menu ul ul li.current_page_item ul li a:hover { ";
				 if(!empty($secondary_color) && ($secondary_color !='#')) $output .= "color:{$secondary_color}; ";
			 $output .= "}\r\t";
			 
			endif;

	endif;
	#Menu Section End
	
	#Body Section
	$disable_boddy_settings =  mytheme_option("appearance","disable-boddy-settings");
	
	if(empty($disable_boddy_settings)):
		$body_font = mytheme_option("appearance","body-font");
		$body_font_size = mytheme_option("appearance","body-font-size");
		$body_font_color = mytheme_option("appearance","body-font-color");
		
		$body_primary_color =  mytheme_option("appearance","body-primary-color");
		$body_secondary_color = mytheme_option("appearance","body-secondary-color");
		
		if(!empty($body_font) || (!empty($body_font_color) and $body_font_color!= "#") || !empty($body_font_size)):
			$output .= "body {";
					if(!empty($body_font)) $output .= "font-family:{$body_font} , sans-serif; ";
					if(!empty($body_font_color) && ($body_font_color !='#')) $output .= "color:{$body_font_color}; ";
					if(!empty($body_font_size)) $output .= "font-size:{$body_font_size}px; ";
			$output .= "}\r\t";
		endif;
		
		if((!empty($body_primary_color) and $body_primary_color!= "#") || (!empty($body_secondary_color) and $body_secondary_color!= "#")):
			if(!empty($body_primary_color) && ($body_primary_color !='#')) $output .= "a { color:{$body_primary_color}; }";
			if(!empty($body_secondary_color) && ($body_secondary_color !='#')) $output .= "a:hover { color:{$body_secondary_color}; }";
		endif;
		
	endif;
	#Body Section End
	
	#Footer Section
	$disable_footer =  mytheme_option("appearance","disable-footer-settings");
	if(empty($disable_footer)):
		$footer_title_font = mytheme_option("appearance","footer-title-font");
		$footer_title_font_color = mytheme_option("appearance","footer-title-font-color");
		$footer_title_font_size = mytheme_option("appearance","footer-font-size"); 
		$footer_primary_color = mytheme_option("appearance","footer-primary-color");
		$footer_secondary_color = mytheme_option("appearance","footer-secondary-color");
		$footer_bg_color = mytheme_option("appearance","footer-bg-color");
		$copyright_bg_color = mytheme_option("appearance","copyright-bg-color");

		if(!empty($footer_title_font) || (!empty($footer_title_font_color) and $footer_title_font_color!= "#") || !empty($footer_title_font_size)):
			$output .= "#footer h1, #footer h2, #footer h3, #footer h4, #footer h5, #footer h6, #footer h1 a, #footer h2 a, #footer h3 a, #footer h4 a, #footer h5 a, #footer h6 a {";
				if(!empty($footer_title_font)) $output .= "font-family:{$footer_title_font}; ";
				if(!empty($footer_title_font_color) && ($footer_title_font_color !='#')) $output .= "color:{$footer_title_font_color}; ";
				if(!empty($footer_title_font_size)) $output .= "font-size:{$footer_title_font_size}px; ";
			$output .= "}\r\t";
		endif;

		if((!empty($footer_primary_color) and $footer_primary_color!= "#") || (!empty($footer_secondary_color) and $footer_secondary_color!= "#")):
			if(!empty($footer_primary_color) && ($footer_primary_color !='#')){
				$output .= "#footer ul li a, #footer .widget_categories ul li a, #footer .widget.widget_recent_entries .entry-metadata .tags a, #footer .categories a, .copyright a { color:{$footer_primary_color}; }";
			}
			
			if(!empty($footer_secondary_color) && ($footer_secondary_color !='#')) {
				$output .= "#footer h1 a:hover, #footer h2 a:hover, #footer h3 a:hover, #footer h4 a:hover, #footer h5 a:hover, #footer h6 a:hover, #footer ul li a:hover, #footer .widget.widget_recent_entries .entry-metadata .tags a:hover, #footer .categories a:hover, .copyright a:hover { color:{$footer_secondary_color}; }";
			}
		endif;
		
		$footer_content_font = mytheme_option("appearance","footer-content-font");
		$footer_content_font_color = mytheme_option("appearance","footer-content-font-color");
		$footer_content_font_size = mytheme_option("appearance","footer-content-font-size");
		
		if(!empty($footer_content_font) || (!empty($footer_content_font_color) and $footer_content_font_color!= "#") || !empty($footer_content_font_size)):
			$output .= "#footer .widget.widget_recent_entries .entry-metadata .author, #footer .widget.widget_recent_entries .entry-meta .date, #footer label, #footer .widget ul li, #footer .widget ul li:hover, .copyright, #footer .widget.widget_recent_entries .entry-metadata .tags, #footer .categories {";
				if(!empty($footer_content_font)) $output .= "font-family:{$footer_content_font} !important; ";
				if(!empty($footer_content_font_color) && ($footer_content_font_color !='#')) $output .= "color:{$footer_content_font_color} !important; ";
				if(!empty($footer_content_font_size)) $output .= "font-size:{$footer_content_font_size}px !important; ";
			$output .= "}\r\t";	
		endif;

		if(!empty($footer_bg_color) and $footer_bg_color!= "#") {
				$output .= "#footer { background-color: $footer_bg_color; }";
		}

		if(!empty($copyright_bg_color) and $copyright_bg_color!= "#") {
				$output .= ".copyright { background-color: $copyright_bg_color; }";
		}
		
	endif;
	#Footer Section End
	
	#Typography Settings
	$disable_typo = mytheme_option("appearance","disable-typography-settings");
	if(empty($disable_typo)):
		for($i=1;$i<=6;$i++):
			$font  = mytheme_option("appearance","H{$i}-font");
			$color = mytheme_option("appearance","H{$i}-font-color");
			$size = mytheme_option("appearance","H{$i}-size");
			
			if(!empty($font) || (!empty($color) and $color!= "#") || !empty($size)):
				$output .= "H{$i}{ ";
					if(!empty($font)) $output .= "font-family:{$font}; ";
					if(!empty($color) && ($color !='#')) $output .= "color:{$color}; ";
					if(!empty($size)) $output .= "font-size:{$size}px; ";
				$output .= "}\r\t";                        
			endif;
		endfor;
	endif;
	#Typography Settings end
	
	#404 Settings
	$disable_404_settings = mytheme_option("specialty","disable-404-font-settings");
	if( empty($disable_404_settings)):
		$font = mytheme_option("specialty","message-font");
		$color = mytheme_option("specialty","message-font-color");
		$size = mytheme_option("specialty","message-font-size");
		if(!empty($font) || (!empty($color) and $color!= "#") || !empty($size)):
			$output .= "div.error-info { ";
				if(!empty($font)) $output .= "font-family:{$font}; ";
				if(!empty($color) && ($color !='#')) $output .= "color:{$color}; ";
				if(!empty($size)) $output .= "font-size:{$size}px; ";
			$output .= "}\r\t";	

			$output .= "div.error-info h1, div.error-info h2, div.error-info h3,div.error-info h4,div.error-info h5,div.error-info h6 { ";
				if(!empty($font)) $output .= "font-family:{$font}; ";
				if(!empty($color) && ($color !='#')) $output .= "color:{$color}; ";
			$output .= "}\r\t";	
			
		endif;	
	endif;#404 Settings end
	

	#Buddha Bar Font
	if(mytheme_option('bbar','show-bbar')):
		$font = mytheme_option('bbar','message-font');
		$font = !empty($font) ? "font-family:{$font};": NULL;
		
		$color  = mytheme_option('bbar','message-font-color');
		$color  = ($color!="#") && !empty($color) ? "color:{$color};" : NULL;
		$color	= mytheme_option('bbar','disable-message-font-color') ? NULL : $color;
		
		$size = mytheme_option('bbar','message-font-size');
		$size = ($size > 0) ? "font-size: {$size}px;" : NULL;
		
		$link_color = mytheme_option('bbar','link-color');
		$link_color  = ($link_color!="#") && !empty($link_color) ? "div#bbar-body a { color:{$link_color}; }" : NULL;
		$link_hover_color = mytheme_option('bbar','link-hover-color');
		$link_hover_color  = ($link_hover_color!="#") && !empty($link_hover_color) ? "div#bbar-body a:hover { color:{$link_hover_color}; }" : NULL;
		
		
		$output .= "div#bbar-body{ {$font} {$color} {$size}}\r {$link_color} {$link_hover_color}";
	endif;
	#Buddha Bar End 
	
	
	#custom CSS
	if(mytheme_option('integration','enable-custom-css')):
		$css = mytheme_option('integration','custom-css');
		$output .= stripcslashes($css);
	endif;
	#custom CSS eND

	if(!empty($output)):
		$output = "\r" . '<style type="text/css">'."\r\t". $output ."\r".'</style>'."\r";
		echo $output;
	endif; }
### --- ****  mytheme_appearance_css() *** --- ###


function mytheme_slider_section( $post_id ) {
	$tpl_default_settings = get_post_meta($post_id,'_tpl_default_settings',TRUE);
	$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
	if(array_key_exists('show_slider',$tpl_default_settings) && array_key_exists('slider_type',$tpl_default_settings) ):
		echo '<!-- **Slider Section** -->';
    	echo '<section id="slider">';
		if($tpl_default_settings['slider_type'] === "layerslider"):
			$id = $tpl_default_settings['layerslider_id'];
			echo do_shortcode("[layerslider id='{$id}']");
		elseif($tpl_default_settings['slider_type'] === "revolutionslider"):
			$id = $tpl_default_settings['revolutionslider_id'];
			echo do_shortcode("[rev_slider $id]");
		endif;
	    echo '</section><!-- **Slider Section - End** -->';
	endif;	
}

/** mytheme_excerpt()
  * Objective:
  *		To produce the excerpt for the posts.
**/
function mytheme_excerpt($limit=NULL) {
	$limit = !empty($limit) ? $limit : 10;
	
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	$excerpt = array_filter($excerpt);
	
	if(!empty($excerpt)) {
		if (count($excerpt)>=$limit) {
			array_pop($excerpt);
			$excerpt = implode(" ",$excerpt).'...';
		}
		else {
			$excerpt = implode(" ",$excerpt);
		} 
		$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
		return "<p>{$excerpt}</p>";
	} }
### --- ****  mytheme_excerpt() *** --- ###


/** mytheme_custom_comments()
  * Objective:
  *		To customize the post/page comments view.
**/
function mytheme_custom_comments($comment,$args,$depth){
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
	   case 'pingback' :
  	   case 'trackback' :
	   		   echo '<li class="post pingback">';
			   echo "<p>";
				   _e( 'Pingback:','dt_delicate');
				   comment_author_link();
				   edit_comment_link( __('Edit','dt_delicate'), ' ' ,'');
			   echo "</p>";
	   break;
	  
	   default :                                        
	   case '' :
				echo "<li ";
					#comment_class();
				echo ' id="li-comment-';
					comment_ID();
				echo '">';
					echo '<article class="comment" id="comment-';
							comment_ID();
					echo '">';
	
						echo '<header class="comment-author">'.get_avatar( $comment, 81 ).'</header>';
						
						echo '<section class="comment-details">';
						echo '	<div class="author-name">'.ucfirst(get_comment_author_link()).'</div>';
						echo '	<div class="commentmetadata">'.get_comment_date('D M d, Y').'</div>';
						echo '  <div class="comment-body">';
						echo '		<div class="comment-content">';
									comment_text();
									if ( $comment->comment_approved == '0' ) :
										_e( 'Your comment is awaiting moderation.','dt_delicate'); 
									endif;
									edit_comment_link( __('Edit','dt_delicate') );
						echo '		</div>';
						echo '	</div>';
						echo '	<div class="reply">';
						echo 	comment_reply_link( array_merge( $args, array('reply_text'=>__('Reply','dt_delicate'),
															'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); 
						
						echo '	</div>';
						echo '</section>';
					echo '</article><!-- .comment-ID -->';
	   break;
	endswitch; }
### --- ****  mytheme_custom_comments() *** --- ###


/** my_menu_walker()
  * Objective:
  *	 My Nav Menu Walker to add span at thetop MENU
**/
class my_menu_walker extends Walker_Nav_Menu {

	function start_el(&$output, $item, $depth, $args) {

		global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		  
          $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
          $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
          $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
          $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		  $attributes .= ! empty( $mytheme_menu_class) ? ' class="'   . esc_attr( $mytheme_menu_class) .'"': '';
		  $span = '<span class="arrow"> </span>';

           if($depth != 0) 
		   	$span = "";

            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_after;
			
			$icon_class = get_post_meta($item->ID,'_menu_item_menu_icon',true);
			if( !empty( $icon_class ) ):
				$item_output .= "<span class='menu-icon {$icon_class}'> </span>";
			endif;
			
            $item_output .= $args->link_before.apply_filters( 'the_title', $item->title, $item->ID );
			
            $item_output .= '</a>'.$span;
            $item_output .= $args->after;
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    } }


#BREADCRUMB
class my_breadcrumb {
	var $options;

	function my_breadcrumb(){
		
		$delimiter =  ' class = '.mytheme_option('general', 'breadcrumb-delimiter');
		$this->options = array( 'before' => "<span $delimiter > ",'after' => ' </span>');
		$markup = $this->options['before'].$this->options['after'];
		
		global $post;
		
		echo '<div class="container">
				<div class="breadcrumb">				
					<a href="'.home_url().'">'.__('Home','dt_delicate').'</a>';
				
			if( !is_front_page() && !is_home()) {
				echo $markup;
			}
			
			$output = $this->simple_breadcrumb_case($post);

		if ( is_page() || is_single()) {
			echo "<h1 class='adjust_breadcrumb'>";
					the_title();
			echo "</h1>";		
		}else if($output != NULL){
			echo "<h1 class='adjust_breadcrumb'>".$output."</h1>";
		}else {
			$title =  (get_option( 'page_for_posts' ) > 0) ? get_the_title( get_option( 'page_for_posts' ))  :NULL;
			echo $markup;
			echo "<h1 class='adjust_breadcrumb'>".$title."</h1>";
		}
		echo "</div>
		</div> <!-- ** container - End -->";
	}
	
	function simple_breadcrumb_case($der_post){
		$markup = $this->options['before'].$this->options['after'];
		if (is_page()){
			 if($der_post->post_parent) {
				 $my_query = get_post($der_post->post_parent);			 
				 $this->simple_breadcrumb_case($my_query);
				 $link = '<a href="'.get_permalink($my_query->ID).'">';
				 $link .= ''. get_the_title($my_query->ID) . '</a>'. $markup;
				 echo $link;
			 }
		return;	 	
		} 

		if(is_single()){
			$category = get_the_category();
			if (is_attachment()){
				$my_query = get_post($der_post->post_parent);			 
				$category = get_the_category($my_query->ID);
				$ID = $category[0]->cat_ID;
				echo get_category_parents($ID, TRUE, $markup, FALSE );
				previous_post_link("%link $markup");
			}else{
				$postType = get_post_type();

				if($postType == 'post')	{
					$ID = $category[0]->cat_ID;
					echo get_category_parents($ID, TRUE,$markup, FALSE );
					
				} else if($postType == 'portfolio') {
					global $post;
					$terms = get_the_term_list( $post->ID, 'portfolio_entries', '', '$$$', '' );
					$terms =  array_filter(explode('$$$',$terms));
					if( !empty($terms)):
						echo $terms[0].$markup;
				    endif;
				} else if($postType == 'product') {
					global $post;
					$terms = get_the_term_list( $post->ID, 'product_cat', '', '$$$', '' );
					$terms =  array_filter(explode('$$$',$terms));
					if( !empty($terms)):
						echo $terms[0].$markup;
				    endif;
				}
			}
		return;
		}

		if(is_tax()){
			  $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			  return $term->name;
		}

		if(is_category()){
			$category = get_the_category(); 
			$i = $category[0]->cat_ID;
			$parent = $category[0]-> category_parent;
			if($parent > 0 && $category[0]->cat_name == single_cat_title("", false)){
				echo get_category_parents($parent, TRUE, $markup, FALSE);
			}
		return __("Archive for Category: ",'dt_delicate').single_cat_title('',FALSE);
		}

		if(is_author()){
			$curauth = get_userdatabylogin(get_query_var('author_name'));
			return __("Archive for Author: ",'dt_delicate').$curauth->nickname;
		}

		if(is_tag()){ return __("Archive for Tag: ",'dt_delicate').single_tag_title('',FALSE); }

		if(is_404()){ return __("LOST",'dt_delicate'); }

		if(is_search()){ return __("Search",'dt_delicate'); }	

		if(is_year()){ return get_the_time('Y'); }

		if(is_month()){
			$k_year = get_the_time('Y');
			echo "<a href='".get_year_link($k_year)."'>".$k_year."</a>".$markup;
			return get_the_time('F'); 
		}

		if(is_day() || is_time()){ 
			$k_year = get_the_time('Y');
			$k_month = get_the_time('m');
			$k_month_display = get_the_time('F');
			echo "<a href='".get_year_link($k_year)."'>".$k_year."</a>".$markup;
			echo "<a href='".get_month_link($k_year, $k_month)."'>".$k_month_display."</a>".$markup;
		return get_the_time('jS (l)'); 
		}
		
		if(is_post_type_archive('product')){
			return 'Products';
		}


	}
}
#END OF BREADCRUMB
####################################

#MyTheme Color Picker
function mytheme_color_picker(){

	$patterns_url = IAMD_FW_URL."theme_options/images/pattern/";
	$skins_url = IAMD_BASE_URL."images/style-picker/";
	
	$patterns = "";
	$patterns_array =  mytheme_listImage(TEMPLATEPATH."/images/style-picker/patterns/");
	
	foreach($patterns_array as $k => $v){
		$img = 	IAMD_BASE_URL."images/style-picker/patterns/".$k;
		$patterns .= '<li>';
		$patterns .= "<a id='{$v}' href='' title=''>";
		$patterns .= "<img src='$img' alt='$v' title='$v' width='30' height='30' />";
		$patterns .= '</a>';
		$patterns .= '</li>'; 
	}
	
	$colors = "";
	foreach(getFolders(get_template_directory()."/skins") as $skin ):
		$img = 	$skins_url.$skin.".jpg";
		$colors .= '<li>';
		$colors .= '<a id="'.$skin.'" href="" title="">';
		$colors .= '<img src="'.$img.'" alt="color-'.$skin.'" title="color-'.$skin.'"/>';
		$colors .= '</a>';
		$colors .= '</li>';
	endforeach;
	

	
	$str = '<!-- **Delicate Style Picker Wrapper** -->';
	$str .= '<div class="delicate-style-picker-wrapper">';
	$str .= '	<a href="" title="" class="style-picker-ico"> <img src="'.IAMD_BASE_URL.'images/style-picker/picker-icon.png" alt="" title="" /> </a>';
	$str .= '	<div id="delicate-style-picker">';
	$str .= '   	<h2>'.__('Select Your Style','dt_delicate').'</h2>';
	
	$str .= '       <h3>'.__('Choose your layout','dt_delicate').'</h3>';
	$str .= '		<ul class="layout-picker">';
	$str .= '       	<li> <a id="fullwidth" href="" title="" class="selected"> <img src="'.IAMD_BASE_URL.'images/style-picker/fullwidth.jpg" alt="" title="" /> </a> </li>';
	$str .= '       	<li> <a id="boxed" href="" title=""> <img src="'.IAMD_BASE_URL.'images/style-picker/boxed.jpg" alt="" title="" /> </a> </li>';
	$str .= '		</ul>';
	$str .= '		<div class="hr"> </div>';
	$str .= '		<div id="pattern-holder" style="display:none;">';
	$str .='			<h3>'.__('Patterns for Boxed Layout','dt_delicate').'</h3>';
	$str .= '			<ul class="pattern-picker">';
	$str .= 				$patterns;
	$str .= '			</ul>';
	$str .= '			<div class="hr"> </div>';
	$str .= '		</div>';
	
	$str .= '		<h3>'.__('Color scheme','dt_delicate').'</h3>';
	$str .= '		<ul class="color-picker">';
	$str .= 		$colors;
	$str .= '		</ul>';
	
	
	$str .= '	</div>';
	$str .= '</div><!-- **Delicate Style Picker Wrapper - End** -->';
	
echo $str;
}?>