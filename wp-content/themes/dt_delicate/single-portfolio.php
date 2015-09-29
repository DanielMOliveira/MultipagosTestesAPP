<?php get_header(); ?>
<?php $portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
	  $portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array(); ?>
	<!-- **Primary Section** -->
	<section id="primary" class="content-full-width">
    
	    <!-- **Portfolio Detail** -->
    	<div class="portfolio-single">
        <?php	if( have_posts() ):
					while( have_posts() ):
						the_post();
						get_template_part( 'framework/loops/content', 'single-portfolio' );
					endwhile;
				endif;?>	
    	</div><!-- **Portfolio Detail End** -->
        
<?php if(array_key_exists("show-releated-items",$portfolio_settings)): 
      	echo '<div class="hr-invisible-small"> </div>';
		$category_ids = array();
		$input  = wp_get_object_terms( $post->ID, 'portfolio_entries');
		foreach($input as $category) $category_ids[] = $category->term_id;

		$args = array('orderby' =>	'rand','showposts' => '-1' ,'post__not_in' => array($post->ID),
	             'tax_query' => array( array( 'taxonomy'=>'portfolio_entries', 'field'=>'id', 'operator'=>'IN', 'terms'=>$category_ids )));
		query_posts($args);
		
		if( have_posts() ):
			echo '<div class="border-title"> <h2> Related Projects <span> </span> </h2> </div>';
			echo '	<!-- **Portfolio Carousel Wrapper** -->';
			echo '	<div class="portfolio-carousel-wrapper gallery">';
			echo '		<!-- **Portfolio Carousel** -->';
			echo '		<ul class="portfolio-carousel">';
			while( have_posts() ):
				the_post();
				$the_id = get_the_ID();
				$portfolio_item_meta = get_post_meta($the_id,'_portfolio_settings',TRUE);
				$portfolio_item_meta = is_array($portfolio_item_meta) ? $portfolio_item_meta  : array();
				
				echo '<li class="portfolio three-column">';?>
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
                </div>
<?php			echo '</li>';
			endwhile;
			echo '		</ul>';
            echo '      <div class="carousel-arrows">';
            echo '      	<a href="#" title="" class="portfolio-prev-arrow"> <span class="icon-chevron-left"> </span> </a>';
			echo '			<a href="#" title="" class="portfolio-next-arrow"> <span class="icon-chevron-right"> </span> </a>';
			echo '       </div>';
			echo '</div>';
		endif;	
	  endif; ?>
      
     </section><!-- **Primary Section** -->      
<?php get_footer();?>