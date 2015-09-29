                            <!-- #post-<?php the_ID()?> starts -->
                            <div id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
                            
                                <div class="column one-column blog-fullwidth">
                                    <!-- **Blog Entry** -->
                                    <article class="blog-entry">
                                        <?php if(has_post_thumbnail()): ?>
                                    	<div class="entry-thumb-meta">
		                                       	<div class="entry-thumb">
                                                	<a href="<?php the_permalink();?>" title="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>">
                                                    <?php the_post_thumbnail("blog-full-width"); ?>
                                                	</a>
                                                </div>
                                            	<div class="entry-meta">
                                                
                                            		<div class="date">
                                                    	<span class="icon-calendar"> </span><p><?php echo get_the_date('F').' '.get_the_date('d').' '.get_the_date('Y');?></p></div>
                                                    
                                                    <?php comments_popup_link('<span class="icon-comments"> </span> 0','<span class="icon-comments"> </span> 1',
															'<span class="icon-comments"> </span> %',"comments",'<span class="icon-comments-off"> </span>');?>
                                                        
                                                    <a title="<?php the_author_meta('display_name'); ?>"
                                                    	href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><span class="icon-user"> </span></a>
                                                        
                                                	<span class="rounded-bend"> </span>
                                               </div><!-- .entry-meta end -->
                                        
                                        </div><!-- .entry-thumb-meta -->
                                        <?php endif;?>
                                        
                                        <div class="entry-details">
                                        
                                            <!-- .post-title -->
                                            <?php if(is_sticky()): ?>
                                            <div class="featured-post"><span><?php _e('Featured','dt_delicate');?> </span></div>
                                            <?php endif;?>
                                        
                                        	<div class="entry-title">
                                            	<h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s'), the_title_attribute( 'echo=0' ) ); ?>">
													<?php the_title(); ?></a></h4></div><!-- .entry-title -->
                                                    
                                                <div class="entry-metadata">
                                            		<?php the_tags('<div class="tags"><span class="icon-tags"> </span>'.'',', ','</div>');?>
                                           			<div class="categories"><span class="icon-pushpin"> </span><?php the_category(', '); ?></div>
    	                                        </div><!-- .entry-meta -->
                                            
                                            <div class="entry-body">
                                             	<?php echo mytheme_excerpt(50);?>
                                                <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('%s'), the_title_attribute('echo=0'));?>" class="read-more">
	                                             	<?php _e('Read More','dt_delicate');?> <span class="icon-double-angle-right"> </span></a>
                                            </div><!-- .entry-body -->
                                            
                                        </div><!-- .entry-details -->
                                        
                                    </article><!-- **Blog Entry - End** -->
                                    <div class="hr"> </div>
                                </div>
                                
                            </div><!-- #post-<?php the_ID()?> Ends -->
