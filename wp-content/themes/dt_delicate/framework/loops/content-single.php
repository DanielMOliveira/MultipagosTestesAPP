<?php $tpl_default_settings = get_post_meta($post->ID,'_dt_post_settings',TRUE);
	  $tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
	  
	  $post_layout = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
  	  $show_sidebar = false;
	  $sidebar_class= "";

	  switch($post_layout):
		case 'with-left-sidebar':
			$post_layout 	=	"with-left-sidebar";
			$show_sidebar 	= 	true;
			$sidebar_class 	=	"left-sidebar";
		break;
		
		case 'with-right-sidebar':
			$show_sidebar 	= 	true;
		break;
	  endswitch;?>
      <!-- **Primary Section** -->
      <section id="primary" class="<?php echo $post_layout;?>">
          <div class="blog-single-entry">
              <!-- **Blog Entry** -->
              <article id="post-<?php the_ID(); ?>" <?php post_class("blog-entry"); ?>>
              
              <?php if(has_post_thumbnail()):
			  			if(!(isset($tpl_default_settings['disable-featured-image']) ) ): ?>
                        <div class="entry-thumb-meta">
	                        <div class="entry-thumb">
    	                        <a href="<?php the_permalink();?>" title="<?php printf(esc_attr__('%s'),the_title_attribute('echo=0'));?>"><?php the_post_thumbnail();?></a>
        	                </div>
                            <?php	$date_class		=	(isset($tpl_default_settings['disable-date-info']) &&isset($tpl_default_settings['disable-date-info'])) ? "hidden" : '' ;
									$comment_class	=	(isset($tpl_default_settings['disable-comment-info']) && isset($tpl_default_settings['disable-comment-info'])) ? "hidden" : '' ;
									$author_class	=	(isset($tpl_default_settings['disable-author-info']) &&isset($tpl_default_settings['disable-author-info']))? "hidden" : '' ;
									$this_class 	= 	( (!empty($date_class)) && ( !empty($comment_class) ) && ( !empty($author_class) ) ) ? "hidden" : "";?>
                                    
	                        <div class="entry-meta <?php echo $this_class;?>">
    	                        <div class="date <?php echo $date_class;?>">
        	                        <span class="icon-calendar"> </span>
            	                    <p><?php echo get_the_date('F').' '.get_the_date('d').' '.get_the_date('Y');?></p>
                	            </div>
                            
                    	        <?php if(!isset($tpl_default_settings['disable-comment-info'])):
                                	    comments_popup_link('<span class="icon-comments"> </span> 0','<span class="icon-comments"> </span> 1',
                        	            '<span class="icon-comments"> </span> %',"comments",'<span class="icon-comments-off"> </span>');
                            	      endif;?>
                                  
	    	                        <a title="<?php the_author_meta('display_name'); ?>" href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" class="<?php echo $author_class; ?>">
    	                            <span class="icon-user"> </span></a>
                                    
		                            <span class="rounded-bend"> </span>
                        </div><!-- .entry-meta end -->
                </div><!-- .entry-thumb-meta -->
               <?php endif;
			   	endif;?> 
                
                <div class="entry-details">
                    <div class="entry-title">
                        <h4><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( '%s'), the_title_attribute( 'echo=0' ) ); ?>"><?php the_title(); ?></a></h4>
                    </div><!-- .entry-title -->
    
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
                        <?php the_content();
                              wp_link_pages( array(	'before'=>'<div class="page-link">', 'after'=>'</div>', 'link_before'=>'<span>', 'link_after'=>'</span>', 
                                            'next_or_number'=>'number',	'pagelink' => '%', 'echo' => 1 ) );
                                            
                              echo '<div class="social-bookmark">';
                                    show_fblike('post');
                                    show_googleplus('post');
                                    show_twitter('post');
                                    show_stumbleupon('post');
                                    show_linkedin('post');
                                    show_delicious('post');
                                    show_pintrest('post');
                                    show_digg('post');
                              echo '</div>';
                              
                              echo '<div class="social-share">';						
                                    mytheme_social_bookmarks('sb-post');
                              echo '</div>';
                              
                              edit_post_link( __( ' Edit ','dt_delicate' ) );?>
                    </div><!-- .entry-body -->
                    
    
                </div><!-- .entry-details -->
              </article><!-- **Blog Entry - End** -->
	          <div class="hr"> </div>
          </div>
          
<?php $mytheme_options = get_option(IAMD_THEME_SETTINGS);
	  $mytheme_general = $mytheme_options['general'];
	  $globally_disable_post_comment =  array_key_exists('global-post-comment',$mytheme_general) ? true : false; 
	  if( (!$globally_disable_post_comment) && (! isset($tpl_default_settings['disable-comment'])) ):?>
            <!-- **Comment Entries** -->   	
            <div class="commententries">
                <?php  comments_template('', true); ?>
            </div><!-- **Comment Entries - End** -->
<?php endif;?>          

          
      </section><!-- **Primary Section** -->

<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>