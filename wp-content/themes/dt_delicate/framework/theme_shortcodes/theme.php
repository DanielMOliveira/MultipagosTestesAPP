<?php
/**
 * Grey Box = grey-box
 */ 
 
add_shortcode('grey-box','my_grey_box'); 
function my_grey_box($attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array( 'id'=>'', 'class'=>''), $attrs));
	$class	= $class <> '' ? $class : '';
	$id  = $id <> '' ? " id = '{$id}'" : '';
	$content = my_shortcode_helper($content);
	$output = "<div {$id} class='{$shortcodename} {$class}'>{$content}</div>";
return $output;	
}
/**
 * Intro Text Shortcode
 **/
add_shortcode('intro-text','my_intro_text');
function my_intro_text($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array( 'type'=>'type1','class'=>''), $attrs));
	$content = my_shortcode_helper( $content );
	$output  = "<div class='intro-text $type $class'>";
	$output .= $content;
	$output .= "</div>";
return $output;
}

/**
 * Portfolio Carousel 
 **/
add_shortcode('portfolio-carousel','my_portfolio_carousel');
function my_portfolio_carousel( $attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array("showposts"=>"-1","categories"=>""), $attrs));
	
	if(empty($categories)):
		$args = array(	'paged' => get_query_var( 'paged' ) ,'posts_per_page' =>$showposts  ,'post_type' => 'portfolio');
	else:
		
		$categories = explode(",",$categories);
		$categories = array_filter($categories);
		 
		$args = array(	'orderby' 	=> 'ID'
						,'order' 			=> 'ASC'
						,'paged' 			=> get_query_var( 'paged' )
						,'posts_per_page' 	=> $showposts
						,'tax_query'		=> array( array( 'taxonomy'=>'portfolio_entries', 'field'=>'id', 'operator'=>'IN', 'terms'=>$categories  ) ) );	
	endif;
	
	$out = "";
		query_posts($args);
		if( have_posts() ):
			$out .= '	<!-- **Portfolio Carousel Wrapper** -->';
			$out .= '	<div class="portfolio-carousel-wrapper gallery">';
			$out .= '		<!-- **Portfolio Carousel** -->';
			$out .= '		<ul class="portfolio-carousel">';
			while( have_posts() ):
				the_post();
				$the_id = get_the_ID();
				$title = get_the_title();
				$permalink = get_permalink( $the_id );
				
				$portfolio_item_meta = get_post_meta($the_id,'_portfolio_settings',TRUE);
				$portfolio_item_meta = is_array($portfolio_item_meta) ? $portfolio_item_meta  : array();
				
				$out .= '		<li class="portfolio three-column">';
				$out .=	'		<div class="portfolio-thumb">';
									if(has_post_thumbnail( $the_id )):
									$out .= get_the_post_thumbnail($the_id,'portfolio-column');
									else:
										$image = array_key_exists("video_url",$portfolio_item_meta) ? "portfolio-column-video.jpg" : "portfolio-column.jpg";
										$image = IAMD_BASE_URL."images/dummy-images/{$image}";
										$out  .= "<img src='{$image}' />";
									endif;
				$out .= '			<div class="image-overlay">';
										 $full = wp_get_attachment_image_src(get_post_thumbnail_id($the_id), 'full', false);
									 if(array_key_exists("video_url",$portfolio_item_meta)):
										$out .="<a href='{$portfolio_item_meta['video_url']}' target='_blank' data-gal='prettyPhoto[gallery]' class='zoom'><span class='icon-film'></span></a>";
									 elseif( $full ):
									 	$out .="<a href='{$full[0]}' data-gal='prettyPhoto[gallery]' class='zoom'><span class='icon-fullscreen'> </span></a>";
									 else:
									 	$img = IAMD_BASE_URL."images/dummy-images/dummy-large.jpg";
									 	$out .= "<a href={$img} data-gal='prettyPhoto[gallery]' class='zoom'><span class='icon-fullscreen'> </span></a>"; 
									 endif;

                    				 if(array_key_exists("url",$portfolio_item_meta)):
									 	$out .= "<a href='{$portfolio_item_meta['url']}' target='_blank' class='link'><span class='icon-external-link'> </span> </a>";
									else:
										$out .= "<a href='{$permalink}' title='{$title}' class='link'> <span class='icon-external-link'> </span> </a>";
									endif;
				$out .= '			</div>';
				$out .= '		</div>';
				
				$out .= '		<div class="portfolio-detail">';
				$out .= "			<h5><a href='{$permalink}' title='{$title}'>{$title}</a></h5>";
									if( array_key_exists("sub-title",$portfolio_item_meta) ):
		                            	$out .="<p>{$portfolio_item_meta['sub-title']}</p>";
									endif;				
				$out .= '		</div>';
				$out .= '		</li>';
			endwhile;
			$out .= '		</ul>';
            $out .= '      <div class="carousel-arrows">';
            $out .= '      	<a href="#" title="" class="portfolio-prev-arrow"> <span class="icon-chevron-left"> </span> </a>';
			$out .= '			<a href="#" title="" class="portfolio-next-arrow"> <span class="icon-chevron-right"> </span> </a>';
			$out .= '       </div>';
			$out .= '	</div>';
		else:
			$out .= "<p>".__("Please add few portfolio items before using this shortcode",'dt_delicate')."</p>";	
		endif;
		wp_reset_query();		
return $out;		 }


/**
 * posts Shortcode
 * categories - specify more categories with comma  4,5,6 
 * column = 2,3
 **/
add_shortcode('posts','my_posts');
function my_posts($attrs, $content=null, $shortcodename =""){
	global $post;
		$tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
		$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
		$page_layout 	= isset( $tpl_default_settings['layout'] ) ? $tpl_default_settings['layout'] : "content-full-width";
		$post_class = " column one-half ";
		$last = NULL;
		$out = NULL;
		
		extract(shortcode_atts(array( 'categories'=>'','count'=>"-1",'column'=>2,'read_more_text'=>__('Read More','dt_delicate'),'excerpt_length'=>12), $attrs));

	switch($column):
		
		default:
		case '2':
		default:
			$post_class  = ($page_layout == "content-full-width") ?  $post_class : $post_class." with-sidebar";
			$last = 2;		
		break;

		case '3':
			$post_class = ($page_layout == "content-full-width") ? " column one-third " : " column one-third with-sidebar ";
			$last = 3;
		break;
	endswitch;
	
	#$categories =  explode(",",$categories);
	#$categories = array_filter(array_unique($categories));
	if(empty($categories)):
		$args = array('paged'=>get_query_var('paged'),'posts_per_page'=>$count,'post_type'=> 'post');
	else:
		$args = array('paged'=>get_query_var('paged'),'posts_per_page'=>$count,'cat'=>$categories,'post_type'=>'post');
	endif;
	query_posts($args);
	if( have_posts() ):
		$count = 1;
		while( have_posts() ):
			the_post();
			$id = get_the_ID();
			$title = get_the_title();
			$permalink = get_permalink( $id );

			$categories = get_the_category();
			$cats = "";
			if($categories){
				foreach($categories as $category) {
					$cats .= '<a href="'.get_category_link($category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s",'dt_delicate' ), $category->name ) ) . '">'.$category->cat_name.'</a>,';
				}
			$cats = substr($cats,0, (strlen($cats)-1));	
			}
			
			$class = ( ($last>1) && ($count%$last == 0) ) ? $post_class.' last': $post_class;
			
			$out .= "<div class='{$class}'>";
			$out .= '	<article class="blog-entry">';
			$out .= '		<div class="entry-thumb-meta">';
			$out .= "		<div class='entry-thumb'><a href='{$permalink}'>";
							if( has_post_thumbnail( $id )):
								$out .= get_the_post_thumbnail($id,"blog-column");	
							else:
								$image = IAMD_BASE_URL."images/dummy-images//blog-column.jpg";
								$out  .= "<img src='{$image}' />";
							endif;
			$out .= '		</a></div>';
			$out .= '		<div class="entry-meta">';
			$out .= '			<div class="date">';
			$out .= "				<span class='icon-calendar'></span><p>".get_the_date('F').' '.get_the_date('d').' '.get_the_date('Y')."</p>";
			$out .= '			</div>';
			
								$commtext = "";
								if((wp_count_comments($id)->approved) == 0)	$commtext = '0';
								else $commtext = wp_count_comments($id)->approved;
			$out .= "			<a href='{$permalink}/#respond' class='comments' title='{$title}'><span class='icon-comments'> </span>".$commtext."</a>";								
			$out .= '			<span class="rounded-bend"> </span>';
			$out .= '		</div>';
			$out .= '		</div>';
			
			$out .= '		<div class="entry-details">';
			$out .= "			<div class='entry-title'><h4><a href='{$permalink}'>{$title}</a></h4></div>";
			$out .=	'			<div class="entry-metadata">';
			#$out .=  				get_the_tag_list('<div class="tags"><span class="icon-tags"> </span>'.'',', ','</div>');
			$out .= "				<div class='categories'><span class='icon-pushpin'> </span>{$cats}</div>";				
			$out .= '			</div>';
			$out .= '			<div class="entry-body">';
			$out .=				mytheme_excerpt($excerpt_length);
			$out .= "	 		<a href='{$permalink}' class='read-more'>{$read_more_text} <span class='icon-double-angle-right'> </span></a>";
			$out .= '			</div>';
			$out .= '		</div>';
			
			$out .= '	</article>';
			$out .= '<div class="hr-invisible-small"> </div>';
			$out .= "</div>";
		$count++;	
		endwhile;
	endif;
	wp_reset_query();
return $out; }


/**
 * Phone Number
 **/
add_shortcode('phone','my_phone_no');
function my_phone_no($attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array( 'no'=>''), $attrs));
	$out = !empty($no) ? "<p> <span class='icon-phone'> </span> <strong>".__('Phone','dt_delicate')."</strong> : {$no} </p>" : "";
	return $out;
}

/**
 * Email id
<p> <span class="icon-envelope-alt"> </span> <strong>Email</strong> : <a href="mailto:delicate@someemail.com"> delicate@someemail.com </a> </p> 
 **/
add_shortcode('email','my_email_id');
function my_email_id($attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array( 'id'=>''), $attrs));
	$out = !empty($id) ? "<p> <span class='icon-envelope-alt'> </span> <strong>".__('Email','dt_delicate')."</strong> : <a href='mailto:{$id}'>{$id}</a> </p>" : "";
	return $out;
}

/**
 * Website
 **/
add_shortcode('website','my_website');
function my_website($attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array( 'url'=>''), $attrs));
	$out = "";
	if ( !empty( $url) ):
		$out  = "<p> <span class='icon-globe'> </span> <strong>".__('Website','dt_delicate')."</strong> : <a href={$url}>";
		$url = preg_replace('#^[^:/.]*[:/]+#i', '',urldecode( $url ));
		$url = preg_replace('!\bwww3?\..*?\b!', '', $url);
		$out .= $url;	
		$out .= "</a> </p>";
	endif;
	return $out;
}

/**
 * Working Hours
 **/
add_shortcode('working-hours','my_working_hours'); 
function my_working_hours($attrs, $content=null,$shortcodename="") {
	$out = "";
	if( sizeof($attrs) > 0):
		$out .= '<p class="working-hours"> <span class="icon-time"></span>';
		$out .=  implode("<br/>",$attrs);
		$out .= '</p>'; 
	endif;
return $out;	
}

/**
 * FullWidth Section
 **/
add_shortcode('full-width','my_fullwidth_section'); 
function my_fullwidth_section($attrs, $content=null,$shortcodename="") {
	extract(shortcode_atts(array( 'class'=>''), $attrs));
	$class	= $class <> '' ? $class : '';
	$content = my_shortcode_helper($content);
	$output = "<section id='{$shortcodename}' class='{$class}'>{$content}</section>";
return $output;	
}

/**
 * FullWidth Section
 **/
add_shortcode('social','my_social'); 
function my_social($attrs, $content=null,$shortcodename="") {
	$mytheme_options = get_option(IAMD_THEME_SETTINGS);

	if( isset($mytheme_options['general']['show-sociables']) && isset($mytheme_options['social']) ):
		$out = "<ul class='social-icons'>";
			foreach($mytheme_options['social'] as $social):
				$link = $social['link'];
				$custom_image = isset($social['custom-image']) && !empty($social['custom-image']) ? "<img src='{$custom_image}' />": '';
				$icon = $social['icon'];
				$out .= "<li><a href='{$link}' target='_blank'>";
				if(!empty($custom_image)):
					$out .=	$custom_image;
				else:
					$out .= "<img src='".IAMD_BASE_URL."/images/sociable/hover/{$icon}' alt='{$icon}' />";
				endif;
				
				$out .= "<img src='".IAMD_BASE_URL."/images/sociable/{$icon}' alt='{$icon}' />";
				$out .="	</a>";
				$out .= "</li>"; 
			endforeach;
		$out .= "</ul>";
	return $out;	
	endif;	
}


?>