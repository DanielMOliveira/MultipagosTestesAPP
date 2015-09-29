<?php add_action("admin_init", "portfolio_videourl_metabox");?>
<?php function portfolio_videourl_metabox(){
			add_meta_box("portfolio-default-container", __('Default Options','dt_delicate'), "portfolio_default_settings", "portfolio", "normal", "default");
			add_meta_box("portfolio-meta-container", __('Project Details','dt_delicate'), "portfolio_meta_settings", "portfolio", "side", "default");
			add_meta_box("portfolio-featured-video-meta-container", __('Featured Video','dt_delicate'), "portfolio_featured_video_settings", "portfolio", "normal", "default");
			add_meta_box("portfolio-media-container",__('Media Settings','dt_delicate'),"portfolio_media_container","portfolio","normal","default");
			add_action('save_post','portfolio_meta_save');
	} 
	
	function portfolio_default_settings($args){
		global $post;
		$portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
		$portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array();?>	

            <!-- Sub Title-->
            <div class="custom-box">
                <div class="column one-sixth"> 
                    <label><?php _e('Sub Title','dt_delicate');?></label>
                </div>
                <div class="column five-sixth last">                 
					<?php $v = array_key_exists("sub-title",$portfolio_settings) ?  $portfolio_settings['sub-title'] : '';?>
                    <input id="sub-title" name="_sub-title" type="text" class="large" value="<?php echo $v;?>" />
                    <p class="note"> <?php _e("If you wish! You can add sub title.",'dt_delicate');?> </p>
                </div>
            </div><!-- Sub Title End-->

            <!-- 1. Layout -->
            <div class="custom-box ">
                <div class="column one-sixth">             
                    <label><?php _e('Layout','dt_delicate');?> </label>
                </div>
                <div class="column five-sixth last">                 
                    <ul class="bpanel-layout-set">
                        <?php $homepage_layout = array('single-portfloio-layout-one'=>'portfolio-fullwidth','single-portfloio-layout-two'=>'portfolio-with-left-gallery','single-portfloio-layout-three'=>'portfolio-with-right-gallery');
                            $v =  array_key_exists("layout",$portfolio_settings) ?  $portfolio_settings['layout'] : 'single-portfloio-layout-one';
                            foreach($homepage_layout as $key => $value):
                                $class = ($key == $v) ? " class='selected' " : "";
                                echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' /></a></li>";
                            endforeach;?>
                    </ul>
                    <?php $v = array_key_exists("layout",$portfolio_settings) ? $portfolio_settings['layout'] : 'single-portfloio-layout-one';?>
                    <input id="mytheme-portfolio-layout" name="layout" type="hidden"  value="<?php echo $v;?>"/>
                    <p class="note"> <?php _e("You can choose between a left, right or full width.",'dt_delicate');?> </p>
                </div>
            </div> <!-- Layout End-->

            <div class="custom-box">
                <div class="column one-sixth">             
                    <label><?php _e('Show Related Projects','dt_delicate');?></label>
                </div>
                <div class="column five-sixth last">  
					<?php $switchclass = array_key_exists("show-releated-items",$portfolio_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                          $checked = array_key_exists("show-releated-items",$portfolio_settings) ? ' checked="checked"' : '';?>
                    <div data-for="mytheme-related-item" class="checkbox-switch <?php echo $switchclass;?>"></div>
                    <input id="mytheme-related-item" class="hidden" type="checkbox" name="mytheme-related-item" value="true"  <?php echo $checked;?>/>
                    <p class="note"> <?php _e('Would you like to show the related projects at the bottom','dt_delicate');?> </p>
                </div>
            </div>
            
            
            <div class="custom-box">
                <div class="column one-sixth">  
                    <label><?php _e('Show Social Share','dt_delicate');?></label>
                </div>
                <div class="column five-sixth last">  
					<?php $switchclass = array_key_exists("show-social-share",$portfolio_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                          $checked = array_key_exists("show-social-share",$portfolio_settings) ? ' checked="checked"' : '';?>
                    <div data-for="mytheme-social-share" class="checkbox-switch <?php echo $switchclass;?>"></div>
                    <input id="mytheme-social-share" class="hidden" type="checkbox" name="mytheme-social-share" value="true"  <?php echo $checked;?>/>
                    <p class="note"> <?php _e('Would you like to show the social share at the bottom','dt_delicate');?> </p>
                </div>
            </div>

            <div class="custom-box">
                <div class="column one-sixth">             
                    <label><?php _e('Allow Comments','dt_delicate');?></label>
                </div>
                <div class="column five-sixth last">  
					<?php $switchclass = array_key_exists("comment",$portfolio_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                          $checked = array_key_exists("comment",$portfolio_settings) ? ' checked="checked"' : '';?>
                    <div data-for="mytheme-portfolio-comment" class="checkbox-switch <?php echo $switchclass;?>"></div>
                    <input id="mytheme-portfolio-comment" class="hidden" type="checkbox" name="mytheme-portfolio-comment" value="true"  <?php echo $checked;?>/>
                    <p class="note"> <?php _e('YES! to allow comments on this page.','dt_delicate');?> </p>
                </div>
            </div>
            
        	
<?php        
	}
	
	function portfolio_meta_settings($args){
		global $post;
		$portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
		$portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array();?>
        
        <div class="custom-box">
            <div class="column one-third">  
                <label><?php _e('Client Name','dt_delicate');?></label>
            </div>
            <div class="column two-third last">  
				<?php $v = array_key_exists("client",$portfolio_settings) ?  $portfolio_settings['client'] : '';?>
                <input id="client" name="_client" class="large" type="text" value="<?php echo $v;?>" />
                <p class="note"> <?php _e("Enter the Client Name",'dt_delicate');?> </p>
            </div>            
        </div>

        <div class="custom-box">
            <div class="column one-third">  
                <label><?php _e('Location','dt_delicate');?></label>
            </div>
            <div class="column two-third last">  
				<?php $v = array_key_exists("location",$portfolio_settings) ?  $portfolio_settings['location'] : '';?>
                <input id="location" name="_location" class="large" type="text" value="<?php echo $v;?>" />
                <p class="note"> <?php _e("Enter the Client Location",'dt_delicate');?> </p>
            </div>
        </div>

        <div class="custom-box">
            <div class="column one-third">  
                <label><?php _e('Project Url','dt_delicate');?></label>
            </div>
            <div class="column two-third last">  
				<?php $v = array_key_exists("url",$portfolio_settings) ?  $portfolio_settings['url'] : '';?>
                <input id="url" name="_url" class="large" type="text" value="<?php echo $v;?>" />
                <p class="note"> <?php _e("Enter the Client Project URL",'dt_delicate');?> </p>
            </div>
        </div>
<?php
		wp_reset_postdata();
 	}
	
	function portfolio_featured_video_settings($args){ 
		global $post;
		$portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
		$portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array();?>
        
        <div class="custom-box">
            <div class="column one-sixth">                     
                <label><?php _e('Video Url','dt_delicate');?> </label>
            </div>
            <div class="column five-sixth last">  
				<?php $v = array_key_exists("video_url",$portfolio_settings) ?  $portfolio_settings['video_url'] : '';?>
                <input name="_video_url" type="text" class="large"  value="<?php echo $v;?>" />
                <br/>
                <p class="note"> <?php _e('Enter a Video URL you wish to show.','dt_delicate');?> </p>
                <p class="note">http://vimeo.com/18439821 , http://www.youtube.com/watch?v=G0k3kHtyoqc</p>
                
            </div>
        </div>
<?php
		wp_reset_postdata();
    }
	
	function portfolio_media_container($args){ 
		global $post;
		$portfolio_settings = get_post_meta($post->ID,'_portfolio_settings',TRUE);
		$portfolio_settings = is_array($portfolio_settings) ? $portfolio_settings  : array(); 
		
		$args = array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'post_mime_type' => 'image', 'posts_per_page' => 15 );
		$media_query = new WP_Query($args);?>
    	<div class="custom-box">

            <!-- Used Image -->
            <h3><?php _e("Used Images / Videos",'dt_delicate');?></h3>
            
            <span id="clone_me" class="hidden">
               <li><img src="<?php echo IAMD_FW_URL.'theme_options/images/video-slider.jpg';?>" /> <span class="my_delete">x</span></li>
               
            </span>	
            <p id="j-no-images-container"><?php _e('Please, add some image','dt_delicate'); ?></p>
            <ul id="j-used-sliders-containers">
            <?php if(array_key_exists("items",$portfolio_settings)): 
				 	foreach($portfolio_settings['items'] as $item): 
						 if( is_numeric($item) ):
						 	 $image = wp_get_attachment_image($item);?>
                             	<li data-attachment-id="<?php echo(esc_attr($item));?>">
                            		<?php echo($image); ?>
	                               	<span class="my_delete">x</span>
    	                           	<input type="hidden" value="<?php echo(esc_attr($item));?>" name="sliders[]" />
	    	                    </li>                              
				   <?php else:
				   				$img = "<img width='150' height='150' src='".IAMD_FW_URL."theme_options/images/video-slider.jpg' title='{$item}' />";
								echo "<li>";
								echo  $img;
								echo "<span class='my_delete'>x</span>";
								echo "<input type='hidden' value='{$item}' name='sliders[]' />";
								echo "</li>";
						 endif;
                	endforeach;
                endif;?>
            </ul><!-- Used Sliders End -->
        
            <!-- Available Images -->
            <span id="add-video" data-post-id="<?php echo $post->ID;?>"><?php _e('Add Videos','dt_delicate');?></span>
            <div class="clear"> </div>
            <h3 class="slider-info"><?php _e("Available Images",'dt_delicate');?></h3>
            <ul id="j-available-sliders">
            <?php foreach ($media_query->posts as $attachment):
                    @$added_class = (  in_array( $attachment->ID, $portfolio_settings['items'] ,false ) ) ? ' class="my_added"' : ''; ?>
                        <li <?php echo($added_class);?> data-attachment-id="<?php echo(esc_attr($attachment->ID));?>">
                            <?php echo(wp_get_attachment_image( $attachment->ID));?>
                            <span class="my_delete">x</span>
                        </li>                    
            <?php endforeach;?>	
            </ul><!-- Available Images  End-->

            <!-- Pagination -->
            <?php if ( $media_query->max_num_pages > 1 ): ?>
                    <div id="j-slider-pagination" class="admin-pagination j-for-portfolio">
                      <?php  for ( $i=1; $i <= $media_query->max_num_pages; $i++ ): ?>
                        <a href="#" <?php echo( 1 == $i ? ' class="active_page"' : '' ) ?>><?php echo($i);?></a>
                      <?php endfor;?>
                    </div>
            <?php endif; ?>	
            
        </div>
<?php
		wp_reset_postdata();    
	}
	
	function portfolio_meta_save($post_id){
		global $pagenow;
		if ( 'post.php' != $pagenow ) return $post_id; 
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
		
		$settings = array();
		$settings['layout']		=	$_POST['layout'];
		$settings['video_url']		=	$_POST['_video_url'];
		$settings['url']  			= 	$_POST['_url'];
		$settings['client']  		= 	$_POST['_client'];
		$settings['location']  		= 	$_POST['_location'];
		$settings['sub-title'] 		= 	$_POST['_sub-title'];
		$settings['items']  		= 	$_POST['sliders'];
		$settings['show-social-share'] = $_POST['mytheme-social-share'];
		$settings['show-releated-items'] = $_POST['mytheme-related-item'];
		$settings['comment'] = $_POST['mytheme-portfolio-comment'];
		
		update_post_meta($post_id, "_portfolio_settings", array_filter($settings));
	}?>