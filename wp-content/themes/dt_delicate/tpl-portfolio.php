<?php /*Template Name: Portfolio Template*/?>
<?php get_header();?>
<?php $tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
	  $tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
	  
	  $page_layout 		= isset( $tpl_default_settings['layout'] ) ? $tpl_default_settings['layout'] : "content-full-width";
	  $show_sidebar		= false;
	  $sidebar_class	= "";
	  
	  $show_content_in_one_column = false;
	  
	  $post_layout		= isset( $tpl_default_settings['portfolio-post-layout'] ) ? $tpl_default_settings['portfolio-post-layout'] : "one-column";
	  $post_per_page 	= $tpl_default_settings['portfolio-post-per-page'];
	  $last 			= NULL;
	  $categories 		= isset($tpl_default_settings['portfolio-categories']) ? array_filter($tpl_default_settings['portfolio-categories']) : "";

	  if(empty($categories)):
		$categories = get_categories('taxonomy=portfolio_entries&hide_empty=1');
	  else:
		$args = array('taxonomy'=>'portfolio_entries','hide_empty'=>1,'include'=>$categories);
		$categories = get_categories($args);			
	  endif;
	  

	#TO SET PAGE LAYOUT
	switch($page_layout):
		case 'with-left-sidebar':
			$page_class = $page_layout;
			$show_sidebar = true;
			$sidebar_class = "left-sidebar";
		break;

		case 'with-right-sidebar':
			$show_sidebar = true;
		break;
	endswitch;

	#TO SET POST LAYOUT
	switch($post_layout):
		case 'one-column':
			$post_class = $show_sidebar ? " portfolio one-column-with-sidebar " : " portfolio one-column ";
			$show_content_in_one_column = true;
		break;
		
		case 'one-half-column';
			$post_class = $show_sidebar ? " portfolio two-column-with-sidebar " : " portfolio two-column ";
			$last = 2;
		break;
		
		case 'one-third-column':
			$post_class = $show_sidebar ? " portfolio three-column-with-sidebar " : " portfolio three-column ";
			$last = 3;
		break;

		case 'one-fourth-column':
			$post_class = $show_sidebar ? " portfolio four-column-with-sidebar " : "portfolio four-column ";
			$last = 4;
		break;
		
	endswitch;?>
       <!-- **Primary Section** -->
       <section id="primary" class="<?php echo $page_layout; ?>">
<?php	if( have_posts() ):
			while( have_posts() ):
				the_post(); ?>
                <!-- #post-<?php the_ID(); ?> -->
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php the_content(); 
					  wp_link_pages( array(	'before' =>	'<div class="page-link">', 'after' => '</div>','link_before' =>	'<span>','link_after' => '</span>',
					  						'next_or_number' =>	'number','pagelink' =>	'%', 'echo' =>1 ) );
					  edit_post_link( __( ' Edit ','dt_delicate' ) );?>
				</div><!-- #post-<?php the_ID(); ?> -->
<?php 		endwhile;
		endif;?>
<!-- ** Portfolio Item Loop ** -->        
<?php if( array_key_exists("filter",$tpl_default_settings) && (!empty($categories)) ):
			$post_class .= " all-sort ";?>        
        <div class="sorting-container">
        	<a href="#" class="active_sort" title="" data-filter=".all-sort"><?php _e('All','dt_delicate');?></a>
            <?php foreach( $categories as $category ): ?>
            		<a href='#' data-filter=".<?php echo $category->category_nicename;?>-sort"><?php echo $category->cat_name;?></a>
            <?php endforeach; ?>
        </div>
<?php endif; ?>

		<div class="portfolio-container gallery">
<?php 		$args = array();
			$categories = array_filter($tpl_default_settings['portfolio-categories']);
				if(is_array($categories) && !empty($categories)):
					$terms = $categories;
					$args = array(	'orderby' 			=> 'ID'
									,'order' 			=> 'ASC'
									#'orderby' 			=> 'rand'
									,'paged' 			=> get_query_var( 'paged' )
									,'posts_per_page' 	=> $post_per_page
									,'tax_query'		=> array( array( 'taxonomy'=>'portfolio_entries', 'field'=>'id', 'operator'=>'IN', 'terms'=>$terms  ) ) );
				  else:	
					  $args = array(/*'orderby'=> 'rand',*/'paged' => get_query_var( 'paged' ) ,'posts_per_page' => $post_per_page ,'post_type' => 'portfolio');
				  endif;
				  
				  query_posts($args);
					if( have_posts() ):
						while( have_posts() ):
							the_post();
							$the_id = get_the_ID();
							
							$portfolio_item_meta = get_post_meta($the_id,'_portfolio_settings',TRUE);
							$portfolio_item_meta = is_array($portfolio_item_meta) ? $portfolio_item_meta  : array();
							
							#Find sort class by using the portfolio_entries
							$sort = "";
							if( array_key_exists("filter",$tpl_default_settings) ):
								$item_categories = get_the_terms( $the_id, 'portfolio_entries' );
								if(is_object($item_categories) || is_array($item_categories)):
							  		foreach ($item_categories as $category):
										$sort .= $category->slug.'-sort ';
									endforeach;
							  	endif;
							endif;?>
                            
                            <div class="<?php echo $post_class.$sort;?>">
                            	<div class="portfolio-thumb">
                                <?php if(has_post_thumbnail()):
										the_post_thumbnail('portfolio-column');
                                      else:
									  	$image = array_key_exists("video_url",$portfolio_item_meta) ? "portfolio-column-video.jpg" : "portfolio-column.jpg";?>
                                         <img src="<?php echo IAMD_BASE_URL."images/dummy-images/{$image}";?>" alt="" />
                                <?php endif; ?>
                                	<div class="image-overlay">
                                    <?php $full = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
										  if(array_key_exists("video_url",$portfolio_item_meta)): ?>
										  	 <a href="<?php echo $portfolio_item_meta['video_url'];?>" target="_blank" data-gal="prettyPhoto[gallery]" class="zoom">
                                             <span class="icon-film"> </span></a>
									<?php elseif( $full ): ?>
                                    		 <a href="<?php echo $full[0];?>" data-gal="prettyPhoto[gallery]" class="zoom"><span class="icon-fullscreen"> </span></a>
									<?php else: ?>
                                    		<a href="<?php echo IAMD_BASE_URL."images/dummy-images/dummy-large.jpg";?>" data-gal="prettyPhoto[gallery]" class="zoom">
                                            <span class="icon-fullscreen"> </span></a>	                                    
									<?php endif;?>
                                    
                                    <?php if(array_key_exists("url",$portfolio_item_meta)): ?>
                                            <a href="<?php echo $portfolio_item_meta["url"];?>" target="_blank" class="link">  <span class="icon-external-link"> </span> </a>
                                    <?php else: ?>
                                           <a href="<?php the_permalink();?>" title="<?php printf( esc_attr__('%s'), the_title_attribute('echo=0'));?>"
                                              class="link">  <span class="icon-external-link"> </span> </a>
                                    <?php endif;?>
                                    </div>
                                </div>
                                
                                <div class="portfolio-detail">
                                	<h5><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></h5>
                                    <?php if( array_key_exists("sub-title",$portfolio_item_meta) ):
											echo "<p>{$portfolio_item_meta['sub-title']}</p>";
										  endif;?>

									<?php if($show_content_in_one_column): ?>
                                        <div class="portfolio-description">
                                            <?php echo mytheme_excerpt(70);?>
                                            <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('%s'), the_title_attribute('echo=0'));?>" class="button small">
                                                <?php _e('View Details ','dt_delicate');?></a>
                                        </div>
                                    <?php endif; ?>    
                                </div><!-- .portfolio=detail -->
                            </div>
														
<?php					endwhile;
					endif;?>
        </div>
<!-- ** Portfolio Item Loop  End** -->        
        <div class="clear"></div>
		<!-- **Pagination** -->
        <div class="pagination">
        	<div class="prev-post"><?php previous_posts_link('<span class="icon-double-angle-left"></span> Prev');?></div>
            <?php echo my_pagination();?>
            <div class="next-post"><?php next_posts_link('Next <span class="icon-double-angle-right"></span>');?></div>
        </div><!-- **Pagination - End** -->
        
       </section>

<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>       
    
<?php get_footer();?>