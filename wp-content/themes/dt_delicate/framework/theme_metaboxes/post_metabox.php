<?php add_action("admin_init", "post_metabox");?>
<?php function post_metabox(){
			add_meta_box("post-template-meta-container", __('Post Options','dt_delicate'), "post_settings","post", "normal", "high");
			add_action('save_post','post_meta_save');
	} 
	
	function post_settings($args){ 
		global $post; 
		$tpl_default_settings = get_post_meta($post->ID,'_dt_post_settings',TRUE);
		$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();?>
        
        <!-- Layout Start -->
        <div class="custom-box">
			<div class="column one-sixth">                        
                <label><?php _e('Layout','dt_delicate');?> </label>
            </div>
			<div class="column five-sixth last">  
                <ul class="bpanel-layout-set">
                    <?php $homepage_layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>	'right-sidebar');
                        $v =  array_key_exists("layout",$tpl_default_settings) ?  $tpl_default_settings['layout'] : 'content-full-width';
                        foreach($homepage_layout as $key => $value):
                            $class = ($key == $v) ? " class='selected' " : "";
                            echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' /></a></li>";
                        endforeach;?>
                </ul>
                <?php $v = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : 'content-full-width';?>
                <input id="mytheme-post-layout" name="layout" type="hidden"  value="<?php echo $v;?>"/>
                <p class="note"> <?php _e("You can choose between a left, right or no sidebar layout.",'dt_delicate');?> </p>
            </div>
        </div><!-- Layout End-->
        
        <!-- Comment Section Start -->
        <div class="custom-box">
			<div class="column one-sixth">                        
                <label><?php _e('Disable Comments','dt_delicate');?></label>
            </div>
			<div class="column five-sixth last">  
				<?php $switchclass = array_key_exists("disable-comment",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                      $checked = array_key_exists("disable-comment",$tpl_default_settings) ? ' checked="checked"' : '';?>
                <div data-for="mytheme-post-comment" class="checkbox-switch <?php echo $switchclass;?>"></div>
                <input id="mytheme-post-comment" class="hidden" type="checkbox" name="post-comment" value="true"  <?php echo $checked;?>/>
                <p class="note"> <?php _e('YES! to disable Comments.','dt_delicate');?> </p>
            </div>	
        </div><!-- Comment Section End-->

        <!-- Featured Image Section Start -->
        <div class="custom-box">
			<div class="column one-sixth">                        
        	    <label><?php _e('Disable Featured Image','dt_delicate');?></label>
            </div>
			<div class="column five-sixth last">  
				<?php $switchclass = array_key_exists("disable-featured-image",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                      $checked = array_key_exists("disable-featured-image",$tpl_default_settings) ? ' checked="checked"' : '';?>
                <div data-for="mytheme-post-featured-image" class="checkbox-switch <?php echo $switchclass;?>"></div>
                <input id="mytheme-post-featured-image" class="hidden" type="checkbox" name="post-featured-image" value="true"  <?php echo $checked;?>/>
                <p class="note"> <?php _e('YES! to disable featured image','dt_delicate');?> </p>
            </div>
        </div><!-- Featured Image Section End-->
        
        <!-- Every Where Sidebar Start -->
        <div class="custom-box">
			<div class="column one-sixth">   
                <label><?php _e('Disable Every Where Sidebar','dt_delicate');?></label>
            </div>
			<div class="column five-sixth last">  
				<?php $switchclass = array_key_exists("disable-everywhere-sidebar",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                      $checked = array_key_exists("disable-everywhere-sidebar",$tpl_default_settings) ? ' checked="checked"' : '';?>
                <div data-for="mytheme-disable-everywhere-sidebar" class="checkbox-switch <?php echo $switchclass;?>"></div>
                <input id="mytheme-disable-everywhere-sidebar" class="hidden" type="checkbox" name="disable-everywhere-sidebar" value="true"  <?php echo $checked;?>/>
                <p class="note"> <?php _e('YES! to disable Every Where Sidebar','dt_delicate');?> </p>
            </div>
        </div><!-- Comment Section End-->

        <!-- Post Meta-->
        <div class="custom-box">
            <h3><?php _e('Post Meta Options','dt_delicate');?></h3>
            <?php $post_meta = array(array(
                    "id"=>		"disable-author-info",
                    "label"=>	__("Disable the Author info.",'dt_delicate'),
                    "tooltip"=>	__("By default the author info will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
                ), array(
                    "id"=>		"disable-date-info",
                    "label"=>	__("Disable the date info.",'dt_delicate'),
                    "tooltip"=>	__("By default the date info will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
                ),
                array(
                    "id"=>		"disable-comment-info",
                    "label"=>	__("Disable the comment info.",'dt_delicate'),
                    "tooltip"=>	__("By default the comment info will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
                ),
                array(
                    "id"=>		"disable-category-info",
                    "label"=>	__("Disable the category info.",'dt_delicate'),
                    "tooltip"=>	__("By default the category info will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
                ),
                array(
                    "id"=>		"disable-tag-info",
                    "label"=>	__("Disable the tag info.",'dt_delicate'),
                    "tooltip"=>	__("By default the tag info will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
                ));
            $count = 1;
            foreach($post_meta as $p_meta):
                $last = ($count%3 == 0)?"last":'';
                $id = 		$p_meta['id'];
                $label =	$p_meta['label'];
                $tooltip =  $p_meta['tooltip'];
                $v =  array_key_exists($id,$tpl_default_settings) ?  $tpl_default_settings[$id] : '';
                $rs =		checked($id,$v,false);
                $switchclass = ($v<>'') ? 'checkbox-switch-on' :'checkbox-switch-off';
                
                echo "<div class='one-third-content {$last}'>";
                echo '<div class="bpanel-option-set">';
                echo "<label>{$label}</label>";							
                echo "<div data-for='{$id}' class='checkbox-switch {$switchclass}'></div>";
                echo "<input class='hidden' id='{$id}' type='checkbox' name='{$id}' value='{$id}' {$rs} />";
				echo '<p class="note">';
				echo ($tooltip);
				echo '</p>';
                echo '</div>';
                echo '</div>';
                
            $count++;	
            endforeach;?>
        </div><!-- Post Meta End-->
<?php
		wp_reset_postdata();
    }
	
	function post_meta_save($post_id){
		global $pagenow;
		if ( 'post.php' != $pagenow ) return $post_id;
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 	return $post_id;
		
		$settings = array();
		$settings['layout'] = isset($_POST['layout']) ? $_POST['layout'] : "";
		$settings['disable-comment'] = isset( $_POST['post-comment'] ) ? $_POST['post-comment'] : "";
		$settings['disable-everywhere-sidebar'] = isset($_POST['disable-everywhere-sidebar']) ? $_POST['disable-everywhere-sidebar'] : "";
		$settings['disable-featured-image'] = isset($_POST['post-featured-image']) ? $_POST['post-featured-image'] : "";
		$settings['disable-author-info']	= isset($_POST['disable-author-info']) ? $_POST['disable-author-info'] : "";
		$settings['disable-date-info']	= isset($_POST['disable-date-info']) ? $_POST['disable-date-info'] : "";
		$settings['disable-comment-info']	= isset($_POST['disable-comment-info']) ? $_POST['disable-comment-info'] : "";
		$settings['disable-category-info']	= isset($_POST['disable-category-info'])?$_POST['disable-category-info']: "";
		$settings['disable-tag-info']	= isset($_POST['disable-tag-info']) ? $_POST['disable-tag-info'] : "";
		
		update_post_meta($post_id, "_dt_post_settings", array_filter($settings));
	}?>