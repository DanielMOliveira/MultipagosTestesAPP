<?php /*Template Name: Blog Template*/?>
<?php get_header();?>
<?php $tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
	  $tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
	  
  	$page_layout 	= isset( $tpl_default_settings['layout'] ) ? $tpl_default_settings['layout'] : "content-full-width";
	$page_class 	= "";
	$show_sidebar 	= false;
	$sidebar_class 	= "";
	
	$post_layout	= isset( $tpl_default_settings['blog-post-layout'] ) ? $tpl_default_settings['blog-post-layout'] : "one-column";
	$post_image 	= "blog-column"; 
	$post_class 	= "";
	$last			= "";
	
	$categories 	= isset($tpl_default_settings['blog-post-exclude-categories']) ? array_filter($tpl_default_settings['blog-post-exclude-categories']) : NULL;
	$post_per_page 	= $tpl_default_settings['blog-post-per-page'];

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
			$post_class = $show_sidebar ? " column one-column with-sidebar blog-fullwidth" : " column one-column blog-fullwidth";
			$post_image 	= "blog-full-width"; 
		break;
		
		case 'one-half-column';
			$post_class = $show_sidebar ? " column one-half with-sidebar" : " column one-half";
			$last = 2;
		break;
		
		case 'one-third-column':
			$post_class = $show_sidebar ? " column one-third with-sidebar" : " column one-third";
			$last = 3;
		break;

		case 'thumb':
			$post_class = $show_sidebar ? " column one-column with-sidebar blog-thumb" : " column one-column blog-thumb";
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
        
        <div class="clear"></div>
<!--- Start loop to show blog posts -->
<?php 			  if(empty($categories)):
					$args = array('paged'=>get_query_var('paged'),'posts_per_page'=>$post_per_page,'post_type'=> 'post');
				  else:
					$exclude_cats = array_unique($categories);
					$args = array('paged'=>get_query_var('paged'),'posts_per_page'=>$post_per_page,'category__not_in'=>$exclude_cats,'post_type'=>'post');
				   endif;
				   
				   query_posts($args);
				   if( have_posts() ):
				   	$count = 1;
						while( have_posts() ):
							the_post();
							$class = ( ($last>1) && ($count%$last == 0) ) ? ' last': '';?>
                            
                            <!-- #post-<?php the_ID()?> starts -->
                            <div id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
                            
                                <div class="<?php echo $post_class.$class;?>">
                                    <!-- **Blog Entry** -->
                                    <article class="blog-entry">
                                    
                                    	<div class="entry-thumb-meta">
		                                       	<div class="entry-thumb">
                                                    <a href="<?php the_permalink();?>" title="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>">
                                                    <?php if(has_post_thumbnail()): 
                                                    		the_post_thumbnail($post_image);
														  else:	
														  	$img = ( $post_image === "blog-full-width") ? "blog-fullwidth.jpg" : "blog-column.jpg";
														  	echo "<img class='dummy-content-image' src='".IAMD_BASE_URL."images/dummy-images/{$img}' />";
                                                    	  endif;?>
                                                	</a>
                                                </div>
                                                
                                                <?php $date_class		=	(isset($tpl_default_settings['disable-date-info']) &&
																	 		isset($tpl_default_settings['disable-date-info'])) ? "hidden" : '' ;
																	 
													  $comment_class	=	(isset($tpl_default_settings['disable-comment-info']) && 
													  						isset($tpl_default_settings['disable-comment-info'])) ? "hidden" : '' ;
																		
													  $author_class		=	(isset($tpl_default_settings['disable-author-info']) &&
													  					 	isset($tpl_default_settings['disable-author-info']))? "hidden" : '' ;
																	
												      $this_class 		= ( (!empty($date_class)) && ( !empty($comment_class) ) && ( !empty($author_class) ) ) ? "hidden" : "";
													  
													  $entry_class = ( isset($tpl_default_settings['blog-post-excerpt']) ) ? "" : "hidden" ;?>    
                                            	<div class="entry-meta <?php echo $this_class;?>">
                                                
                                            		<div class="date <?php echo $date_class;?>">
                                                    	<span class="icon-calendar"> </span><p><?php echo get_the_date('M').' '.get_the_date('d').' '.get_the_date('Y');?></p></div>
                                                    
                                                    <?php if(!isset($tpl_default_settings['disable-comment-info'])): 
															comments_popup_link('<span class="icon-comments"> </span> 0','<span class="icon-comments"> </span> 1',
															'<span class="icon-comments"> </span> %',"comments",'<span class="icon-comments-off"> </span>');
														endif;?>
                                                        
                                                    <a class="<?php echo $author_class; ?>" title="<?php the_author_meta('display_name'); ?>"
                                                    	href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><span class="icon-user"> </span></a>
                                                        
                                                	<span class="rounded-bend"> </span>
                                               </div><!-- .entry-meta end -->
                                        
                                        </div><!-- .entry-thumb-meta -->
                                        
                                        <div class="entry-details <?php echo $entry_class;?>">
                                        
                                            <!-- .post-title -->
                                            <?php if(is_sticky()): ?>
                                            <div class="featured-post"> <span> <?php _e('Featured','dt_delicate');?> </span></div>
                                            <?php endif;?>
                                                                               
                                        	<div class="entry-title">
                                            	<h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s'), the_title_attribute( 'echo=0' ) ); ?>">
													<?php the_title(); ?></a></h4></div><!-- .entry-title -->
                                                    
                                            	<?php $class = (isset($tpl_default_settings['disable-category-info']) && isset($tpl_default_settings['disable-tag-info'])) ? "hidden" : '';?>
                                                <div class="entry-metadata <?php echo $class;?>">
                                            		<?php 	if(!isset($tpl_default_settings['disable-tag-info'])):
																the_tags('<div class="tags"><span class="icon-tags"> </span>'.'',', ','</div>');
															 endif;?>
                                                	<?php if(!isset($tpl_default_settings['disable-category-info'])): ?>
                                                			<div class="categories"><span class="icon-pushpin"> </span><?php the_category(', '); ?></div>
	                                                <?php endif; ?>
    	                                        </div><!-- .entry-meta -->
                                            
                                            <div class="entry-body">
                                             	<?php echo mytheme_excerpt($tpl_default_settings['blog-post-excerpt-length']);?>
                                                <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('%s'), the_title_attribute('echo=0'));?>" class="read-more">
	                                             	<?php _e('Read More','dt_delicate');?> <span class="icon-double-angle-right"> </span></a>
                                            </div><!-- .entry-body -->
                                            
                                        </div><!-- .entry-details -->
                                        
                                    </article><!-- **Blog Entry - End** -->
                                    <div class="hr"> </div>
                                </div>
                                
                            </div><!-- #post-<?php the_ID()?> Ends -->
<?php				$count++;
						endwhile;
				   endif;?>
                   
                   <!-- **Pagination** -->
                   <div class="pagination">
						<div class="prev-post"><?php previous_posts_link('<span class="icon-double-angle-left"></span> Prev');?></div>
                        <?php echo my_pagination();?>
                        <div class="next-post"><?php next_posts_link('Next <span class="icon-double-angle-right"></span>');?></div>
                   </div><!-- **Pagination - End** -->
<!--- End of loop to show blog posts -->        
       </section>

<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>       
       

<?php get_footer();?>