<?php $portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
	  $portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array();
	  
	  $layout = isset( $portfolio_settings['layout'] ) ? $portfolio_settings['layout'] : 'single-portfloio-layout-one';
	  $container_start =  $container_middle =  $container_end = "";
	
	if( $layout === "single-portfloio-layout-two" ):
		$container_start	=	'<div class="column two-third">';
		$container_middle	=	'</div>';
		$container_middle  .=	'<div class="column one-third last">'; 
		$container_end		=	'</div>';
		
	elseif( $layout === "single-portfloio-layout-three" ):	
		$container_start	=	'<div class="column two-third right-gallery">';
		$container_middle	=	'</div>';
		$container_middle  .=	'<div class="column one-third last">'; 
		$container_end		=	'</div>';
	endif;
	
	$image = wp_get_attachment_image(get_post_thumbnail_id($post->ID),'full');
	$has_items = array_key_exists("items",$portfolio_settings);
	
	echo $container_start;
	
	if(!empty($image) || $has_items):
		echo '<div class="portfolio-slider-container">';
		echo '	<ul class="portfolio-slider">';
				if(!empty($image)):
					$img_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
					$img_src = $img_src[0];
					echo "<li><img src='{$img_src}' /></li>";
					
				endif;
				
				if($has_items):
					foreach($portfolio_settings['items'] as $item):
						echo "<li>";
							if( is_numeric($item) ):
								$src = wp_get_attachment_image_src($item,'full');
								$src = $src[0];
								echo "<img src='{$src}' />";
							else:
								 $width = 1060;
								 $height = 800; 	
								 if ( $layout === "single-portfloio-layout-two"):
								 	$width = 700;
									$height = 528;
								 endif;
								$url = "";
								if ( strpos($item,"vimeo") ) : #For Vimeo
									$url = substr( strrchr($item,"/"),1);
									$out = "<iframe src='http://player.vimeo.com/video/{$url}' width='{$width}' height='{$height}' frameborder='0'></iframe>";									
								elseif( strpos($item,"?v=") ): #For Youtube
									$url = substr( strrchr($item,"="),1);
									$out = "<iframe src='http://www.youtube.com/embed/{$url}?wmode=opaque' width='{$width}' height='{$height}' frameborder='0'></iframe>";									
								endif;
								echo $out;
							endif;
						echo "</li>";
					endforeach;
				endif;
		echo '	</ul>';
		echo '</div>';
	endif;
	
	echo $container_middle;
	
		the_title( '<h3>', '</h3>' );
		if(isset($portfolio_settings['sub-title']) && !empty($portfolio_settings['sub-title'])):
			echo "<h6>{$portfolio_settings['sub-title']}</h6>"; 
		endif;
		
		the_content();
		
		wp_link_pages( array(	'before'=>'<div class="page-link">',	'after'=>'</div>',	'link_before'=>'<span>',	'link_after'=>'</span>',
					   'next_or_number'=>'number',	'pagelink' => '%',	'echo' => 1 ) );
					   
					   
		$display = 'none';
		$display =  array_key_exists("client",$portfolio_settings) ? 'block' : $display;
		$display =  array_key_exists("location",$portfolio_settings) ? 'block' : $display;
		$display =  array_key_exists("url",$portfolio_settings) ? 'block' : $display;?>
        
        <div style="display:<?php echo $display?>">

			<h5><?php _e('Project Details','dt_delicate');?></h5>

			<?php if(array_key_exists("client",$portfolio_settings)): ?>
                    <p> <span class="icon-user"> </span> <strong><?php _e('Client Name : ','dt_delicate');?></strong><?php echo $portfolio_settings['client'];?> </p>
            <?php endif; ?>
        

			<?php if(array_key_exists("location",$portfolio_settings)): ?>
                    <p> <span class="icon-map-marker"> </span> <strong><?php _e('Location : ','dt_delicate');?></strong><?php echo $portfolio_settings['location'];?> </p>
            <?php endif; ?>

			<?php if(array_key_exists("url",$portfolio_settings)): ?>
                    <p> <span class="icon-link"> </span> <strong><?php _e('Website : ','dt_delicate');?></strong>
                    <a href="<?php echo $portfolio_settings['url'];?>" target="_blank">
                    <?php $a = preg_replace('#^[^:/.]*[:/]+#i', '',urldecode( $portfolio_settings['url'] ));
                        echo preg_replace('!\bwww3?\..*?\b!', '', $a); ?> </a></p>
            <?php endif; ?>
        </div>

        <?php if(array_key_exists("show-social-share",$portfolio_settings)): ?>
               <div class="portfolio-share"><?php mytheme_social_bookmarks('sb-portfolio');?></div>
        <?php endif;
		echo $container_end; ?>        

    <!-- **Post Nav** -->
    <div class="post-nav-container">
        <div class="post-prev-link"><?php previous_post_link('%link','<i class="icon-circle-arrow-left"> </i> %title<span> ('.__('Prev Entry','dt_delicate').')</span>');?> </div>
        <div class="post-next-link"><?php next_post_link('%link','<span> ('.__('Next Entry','dt_delicate').')</span> %title <i class="icon-circle-arrow-right"> </i>');?></div>
    </div><!-- **Post Nav - End** -->
        
<?php	edit_post_link( __( 'Edit','dt_delicate')); ?>

	<?php $mytheme_options = get_option(IAMD_THEME_SETTINGS);
    $mytheme_general = $mytheme_options['general'];
	
	$disable_portfolio_comment = array_key_exists('disable-portfolio-comment',$mytheme_general) ? $mytheme_general['disable-portfolio-comment'] : false;
	$comment = array_key_exists('comment',$portfolio_settings) ? $portfolio_settings['comment'] : NULL;
	
	
    if(!$disable_portfolio_comment && $comment):	?>
        <div class="clear"> </div>

        <div class="commententries">
            <?php  comments_template('', true); ?>
        </div><!-- **Comment Entries - End** -->
    <?php endif; ?>

