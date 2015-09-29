<?php get_header();?>
<?php $page_layout 	= mytheme_option('specialty','search-layout');
  	  $page_layout 	= !empty($page_layout) ? $page_layout : "content-full-width";
	  
	  $post_layout 	= mytheme_option('specialty','search-post-layout'); 
	  $post_layout 	= !empty($post_layout) ? $post_layout : "one-column";
	  $post_image 	= "blog-column";
	  $show_sidebar = false;
	  $sidebar_class = "";
	  $post_class = "";
	  $last = NULL;
	  
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
	endswitch;?>
      <!-- **Primary Section** -->
      <section id="primary" class="<?php echo $page_layout;?>">
<?php	if( have_posts() ):
			$count = 1;
			while( have_posts() ):
				the_post();
				$class = ( ($last>1) && ($count%$last == 0) ) ? ' last': '';?>
                
                <!-- #post-<?php the_ID()?> starts -->
                <div id="post-<?php the_ID(); ?>">
                    <div class="<?php echo $post_class.$class;?>">
                        <!-- **Blog Entry** -->
                        <article class="blog-entry">
                        
                        	<?php if(has_post_thumbnail()): ?>
                            <div class="entry-thumb-meta">
                            
                                <div class="entry-thumb">
                                    <a href="<?php the_permalink();?>" title="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>">
                                    <?php the_post_thumbnail($post_image);?></a>
                                </div>
                                
                                <div class="entry-meta">
                                
                                    <div class="date">
                                        <span class="icon-calendar"> </span><p><?php echo get_the_date('F').' '.get_the_date('d').' '.get_the_date('Y');?></p></div>
                                    
                                    <?php if( "post" === get_post_type( $post->ID ) ):
	                                            comments_popup_link('<span class="icon-comments"> </span> 0','<span class="icon-comments"> </span> 1',
    	                                        '<span class="icon-comments"> </span> %',"comments",'<span class="icon-comments-off"> </span>');
                                        endif;?>
                                        
                                    <a title="<?php the_author_meta('display_name'); ?>"
                                        href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><span class="icon-user"> </span></a>
                                        
                                    <span class="rounded-bend"> </span>
                                </div><!-- .entry-meta end -->
                            </div><!-- .entry-thumb-meta -->
                            <?php endif;?>
                            
                            <div class="entry-details">
                            
                                <!-- .post-title -->
                                <?php if(is_sticky()): ?>
                                <div class="featured-post"><span> <?php _e('Featured','dt_delicate');?> </span></div>
                                <?php endif;?>
                            
                                <div class="entry-title">
                                    <h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s'), the_title_attribute( 'echo=0' ) ); ?>">
                                        <?php the_title(); ?></a></h4></div><!-- .entry-title -->
                                        
                                 <?php if( "post" === get_post_type( $post->ID ) ): ?>
                                 	<div class="entry-metadata">
                                    	<?php the_tags('<div class="tags"><span class="icon-tags"> </span>'.'',', ','</div>');?>
                                        <div class="categories"><span class="icon-pushpin"> </span><?php the_category(', '); ?></div>
                                     </div><!-- .entry-meta -->
                                  <?php endif;?>   
                                     
                                     <div class="entry-body"><?php echo mytheme_excerpt(20);?></div><!-- .entry-body -->
                            
                            </div><!-- .entry-details -->
                        
                        </article><!-- **Blog Entry - End** -->
                        <div class="hr"> </div>
                </div>
                
                </div><!-- #post-<?php the_ID()?> Ends -->                
<?php				$count++;
						endwhile;
					else: ?>
                    	<div class="hr_invisible"> </div>
                        	<h1><?php _e( 'Nothing Found','dt_delicate'); ?></h1>
                            <h3><?php _e( 'Apologies, but no results were found for the requested archive.', 'dt_delicate'); ?></h3><?php
							get_search_form();
					endif;?>
					<!-- **Pagination** -->
                   <div class="pagination">
						<div class="prev-post"><?php previous_posts_link('<span class="icon-double-angle-left"></span> Prev');?></div>
                        <?php echo my_pagination();?>
                        <div class="next-post"><?php next_posts_link('Next <span class="icon-double-angle-right"></span>');?></div>
                   </div><!-- **Pagination - End** -->
      </section><!-- **Primary Section** -->
        
<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>
          
<?php get_footer();?>