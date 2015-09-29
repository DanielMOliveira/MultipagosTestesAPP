<?php add_action("admin_init", "page_metabox");?>
<?php function page_metabox(){
			add_meta_box("page-template-slider-meta-container", __('Slider Options','dt_delicate'), "page_sllider_settings", "page", "normal", "high");	
			add_meta_box("page-template-meta-container", __('Default page Options','dt_delicate'), "page_settings", "page", "normal", "high");
			add_action('save_post','page_meta_save');
	} 
	
	
	#Slider Meta Box
	function page_sllider_settings($args){	
		global $post; 
		$tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
		$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();?>

		<!-- Show Slider -->        
        <div class="custom-box">
        	<div class="column one-sixth">
                <label><?php _e('Show Slider','dt_delicate');?> </label>
            </div>
            <div class="column four-sixth last">
				<?php $switchclass = array_key_exists("show_slider",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                      $checked = array_key_exists("show_slider",$tpl_default_settings) ? ' checked="checked"' : '';?>
                <div data-for="mytheme-show-slider" class="checkbox-switch <?php echo $switchclass;?>"></div>
                <input id="mytheme-show-slider" class="hidden" type="checkbox" name="mytheme-show-slider" value="true"  <?php echo $checked;?>/>
                <p class="note"> <?php _e('YES! to show slider on this page.','dt_delicate');?> </p>
            </div>
        </div><!-- Show Slider End-->

        <!-- Slider Types -->
        <div class="custom-box">
        	<div class="column one-sixth">
                <label><?php _e('Choose Slider','dt_delicate');?></label>
            </div>
            <div class="column four-sixth last">
	            <?php $slider_types = array( '' => __("Select",'dt_delicate'),
											 'layerslider' => __("Layer Slider",'dt_delicate'),
											 'revolutionslider' => __("Revolution Responsive",'dt_delicate'));
											 
	 				  $v =  array_key_exists("slider_type",$tpl_default_settings) ?  $tpl_default_settings['slider_type'] : '';
					  
					  echo "<select class='slider-type' name='mytheme-slider-type'>";
					  foreach($slider_types as $key => $value):
					  	$rs = selected($key,$v,false);
						echo "<option value='{$key}' {$rs}>{$value}</option>";
					  endforeach;
	 				 echo "</select>";?>
            <p class="note"> <?php _e("Choose which slider you wish to use ( eg: Layer or Revolution )",'dt_delicate');?> </p>
            </div>
        </div><!-- Slider Types End-->
        
        <!-- slier-container starts-->
    	<div id="slider-conainer">
        <?php $layerslider = $revolutionslider = 'style="display:none"';
			  if(isset($tpl_default_settings['slider_type'])&& $tpl_default_settings['slider_type'] == "layerslider"):
			  	$layerslider = 'style="display:block"';
			  elseif(isset($tpl_default_settings['slider_type'])&& $tpl_default_settings['slider_type'] == "revolutionslider"):
			  	$revolutionslider = 'style="display:block"';
			  endif;?>
              
          
              <!-- Layered Slider -->
              <div id="layerslider" class="custom-box" <?php echo $layerslider;?>>
              	<h3><?php _e('Layer Slider','dt_delicate');?></h3>
                <?php if(mytheme_is_plugin_active('LayerSlider/layerslider.php')):?>
                <?php // Get WPDB Object
					  global $wpdb;
					  // Table name
					  $table_name = $wpdb->prefix . "layerslider";
					  // Get sliders
					  $sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0'  ORDER BY date_c ASC LIMIT 100" );
					  
					  if($sliders != null && !empty($sliders)):
		 	                echo '<div class="one-half-content">';
							echo '	<div class="bpanel-option-set">';
							echo ' <div class="column one-sixth">';
                            echo '	<label>'.__('Select LayerSlider','dt_delicate').'</label>';
							echo ' 	</div>';
							echo ' <div class="column two-sixth">';
                            echo '	<select name="layerslider_id">';
                            echo '		<option value="0">'.__("Select Slider",'dt_delicate').'</option>';
									  	foreach($sliders as $item) :
											$name = empty($item->name) ? 'Unnamed' : $item->name;
											$id = $item->id;
											$rs = isset($tpl_default_settings['layerslider_id']) ? $tpl_default_settings['layerslider_id']:'';
											$rs = selected($id,$rs,false);
											echo "	<option value='{$id}' {$rs}>{$name}</option>";
										endforeach;
                            echo '	</select>';
                            echo '<p class="note">';
							_e("Choose Which LayerSlider you would like to use..",'dt_delicate');
							echo "</p>";
							echo ' 	</div>';
							echo '	</div>';
                            echo '</div>';
					  else:
					     echo '<p id="j-no-images-container">'.__('Please add atleat one layer slider','dt_delicate').'</p>';
					  endif;?>
                      
					<?php $layersliders = get_option('layerslider-slides');
                        if($layersliders):
                            $layersliders = is_array($layersliders) ? $layersliders : unserialize($layersliders);	
                            foreach($layersliders as $key => $val):
                                $layersliders_array[$key+1] = 'LayerSlider #'.($key+1);
                            endforeach;
                            echo '<div class="one-half-content">';
							echo '	<div class="bpanel-option-set">';
							echo ' <div class="column one-sixth">';
                            echo '	<label>'.__('Select LayerSlider','dt_delicate').'</label>';
                            echo '</div>';
							echo ' <div class="column two-sixth">';
                            echo '	<select name="layerslider_id">';
                            echo '		<option value="0">'.__("Select Slider",'dt_delicate').'</option>';
                            foreach($layersliders_array as $key => $value):
                                $rs = isset($tpl_default_settings['layerslider_id']) ? $tpl_default_settings['layerslider_id']:'';
                                $rs = selected($key,$rs,false);
                                echo "	<option value='{$key}' {$rs}>{$value}</option>";
                            endforeach;
                            echo '	</select>';
                            echo '<p class="note">';
							_e("Choose which LayerSlider would you like to use!",'dt_delicate');
							echo "</p>";
                            echo '</div>';
							echo '	</div>';
                            echo '</div>';
                        endif;
					  else:?>
                      <p id="j-no-images-container"><?php _e('Please activate Layered Slider','dt_delicate'); ?></p>
               <?php endif;?>         
                
              </div><!-- Layered Slider End-->

              <!-- Revolution Slider -->
              <div id="revolutionslider" class="custom-box" <?php echo $revolutionslider;?>>
	            <h3><?php _e('Revolution Slider','dt_delicate');?></h3>
                <?php $return = check_slider_revolution_responsive_wordpress_plugin();
					  if($return):
                        echo '<div class="one-half-content">';
						echo '	<div class="bpanel-option-set">';
						echo ' <div class="column one-sixth">';
						echo '	<label>'.__('Select Slider','dt_delicate').'</label>';
						echo '</div>';
						echo ' <div class="column three-sixth">';
						echo '	<select name="revolutionslider_id">';
						echo '		<option value="0">'.__("Select Slider",'dt_delicate').'</option>';
						foreach($return as $key => $value):
							$rs = isset($tpl_default_settings['revolutionslider_id']) ? $tpl_default_settings['revolutionslider_id']:'';
							$rs = selected($key,$rs,false);
							echo "	<option value='{$key}' {$rs}>{$value}</option>";
						endforeach;
						echo '</select>';
						echo '<p class="note">';
						_e("Choose which Revolution slider would you like to use!",'dt_delicate');
						echo "</p>";
						echo '</div>';
						echo '	</div>';
						echo '</div>';
                	  else: ?>
	                	<p id="j-no-images-container"><?php _e('Please activate Revolution Slider , and add at least one slider.','dt_delicate'); ?></p>
                <?php endif;?>
              </div><!-- Revolution Slider End-->
        </div><!-- slier-container ends-->

        
	
<?php  wp_reset_postdata();
	}

	#Page Meta Box	
	function page_settings($args){
		 
		global $post; 
		$tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
		$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();?>
        
        <div class="j-pagetemplate-container">
        
            
            	<div id="tpl-common-settings">
                    <!-- 1. Layout -->
                    <div class="custom-box ">
                        <div class="column one-sixth">
                            <label><?php _e('Layout','dt_delicate');?> </label>
                        </div>
                        <div class="column five-sixth last">                        
                            <ul class="bpanel-layout-set">
                                <?php $homepage_layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>'right-sidebar');
                                    $v =  array_key_exists("layout",$tpl_default_settings) ?  $tpl_default_settings['layout'] : 'content-full-width';
                                    foreach($homepage_layout as $key => $value):
                                        $class = ($key == $v) ? " class='selected' " : "";
                                        echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' /></a></li>";
                                    endforeach;?>
                            </ul>
                            <?php $v = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : 'content-full-width';?>
                            <input id="mytheme-page-layout" name="layout" type="hidden"  value="<?php echo $v;?>"/>
                            <p class="note"> <?php _e("You can choose between a left, right or no sidebar layout.",'dt_delicate');?> </p>
                        </div>
                    </div> <!-- Layout End-->
    
                    <!-- 2. Every Where Sidebar Start -->
                    <div class="custom-box">
                    	<div class="column one-sixth">
                            <label><?php _e('Disable Every Where Sidebar','dt_delicate');?></label>
                        </div>
                        <div class="column five-sixth last">
							<?php $switchclass = array_key_exists("disable-everywhere-sidebar",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                                  $checked = array_key_exists("disable-everywhere-sidebar",$tpl_default_settings) ? ' checked="checked"' : '';?>
                            <div data-for="mytheme-disable-everywhere-sidebar" class="checkbox-switch <?php echo $switchclass;?>"></div>
                            <input id="mytheme-disable-everywhere-sidebar" class="hidden" type="checkbox" name="disable-everywhere-sidebar" value="true"  <?php echo $checked;?>/>
                            <p class="note"> <?php _e('Yes! to hide "Every Where Sidear" on this page.','dt_delicate');?> </p>
                        </div>
                    </div><!-- Comment Section End-->
                </div><!-- .tpl-common-settings end -->    
                
                <div id="tpl-feature-settings">
                    <div class="custom-box">
                    	<div class="column one-sixth">
                            <label><?php _e('Menu Icon Class','dt_delicate');?></label>
                        </div>
                        <div class="column five-sixth last">
	                        <?php $menu_icon_class =  array_key_exists("menu-icon-class",$tpl_default_settings) ? stripcslashes($tpl_default_settings['menu-icon-class']) : "" ;?>
                            <input id="mytheme-menu-class" type="text" name="mytheme-menu-class" value="<?php echo $menu_icon_class;?>"  />
                            <p class="note"> <?php _e('Icon class for this page(eg: icon-home )','dt_delicate');?> </p>
                        </div>
                    </div>
                	
                </div>
                
				<div id="tpl-default-settings">
                
                    <!-- 4. Allow Commenet -->
                    <div class="custom-box">
                    	<div class="column one-sixth">
                            <label><?php _e('Allow Comments','dt_delicate');?></label>
                        </div>
                        <div class="column five-sixth last">
							<?php $switchclass = array_key_exists("comment",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                                  $checked = array_key_exists("comment",$tpl_default_settings) ? ' checked="checked"' : '';?>
                            <div data-for="mytheme-page-comment" class="checkbox-switch <?php echo $switchclass;?>"></div>
                            <input id="mytheme-page-comment" class="hidden" type="checkbox" name="mytheme-page-comment" value="true"  <?php echo $checked;?>/>
                            <p class="note"> <?php _e('YES! to allow comments on this page.','dt_delicate');?> </p>
                        </div>
                    </div><!-- Allow Commenet End-->
               </div><!-- tpl-default-settings end-->     


				<div id="tpl-contact-settings">
                    <div class="custom-box">
                    	<div class="column one-sixth">
                            <label><?php _e('Full Width Section','dt_delicate');?></label>
                        </div>
                        <div class="column five-sixth last">
							<?php $content =  array_key_exists("full-width-section",$tpl_default_settings) ? stripcslashes($tpl_default_settings['full-width-section']) : "" ;?>
                            <textarea name="page-full-width-section" class="widefat" rows="15"><?php echo $content; ?></textarea>
                            <p class="note"> <?php _e('This content will appear in full width','dt_delicate');?> </p>
                        </div>
                    </div>
               </div><!-- tpl-contact-settings end-->     
             
             
             
             <!-- Blog Template Settings -->
             <div id="tpl-blog">
             	<!-- Post Playout -->
                <div class="custom-box">
                    <div class="column one-sixth">                
                        <label><?php _e('Posts Layout','dt_delicate');?> </label>
                    </div>
                    <div class="column five-sixth last">                        
                        <ul class="bpanel-layout-set">
                        <?php $posts_layout = array(	'one-column'=>	__("Single post per row.",'dt_delicate'),
                                                        'one-half-column'=>	__("Two posts per row.",'dt_delicate'),
                                                        'one-third-column'=>	__("Three posts per row.",'dt_delicate'),
                                                        'thumb' => __("Thumb View of posts",'dt_delicate'));
                                $v = array_key_exists("blog-post-layout",$tpl_default_settings) ?  $tpl_default_settings['blog-post-layout'] : 'one-column';
                                foreach($posts_layout as $key => $value):
                                    $class = ($key == $v) ? " class='selected' " : "";
                                    echo "<li><a href='#' rel='{$key}' {$class} title='{$value}'><img src='".IAMD_FW_URL."theme_options/images/columns/{$key}.png' /></a></li>";
                                endforeach;?>
                        </ul>
                        <input id="mytheme-blog-post-layout" name="mytheme-blog-post-layout" type="hidden" value="<?php echo $v;?>"/>
                        <p class="note"> <?php _e("Choose layout style for your Blog",'dt_delicate');?> </p>
                    </div>
                </div><!-- Post Playout End-->

                <!-- Post Count-->
                <div class="custom-box">
                    <div class="column one-sixth"> 
                        <label><?php _e('Post per page','dt_delicate');?></label>
                    </div>
                    <div class="column five-sixth last"> 
                        <select name="mytheme-blog-post-per-page">
                            <option value="-1"><?php _e("All",'dt_delicate');?></option>
                            <?php $selected = 	array_key_exists("blog-post-per-page",$tpl_default_settings) ?  $tpl_default_settings['blog-post-per-page'] : ''; ?>
                            <?php for($i=1;$i<=30;$i++):
                                echo "<option value='{$i}'".selected($selected,$i,false).">{$i}</option>";
                                endfor;?>
                        </select>
                        <p class="note"> <?php _e("Your blog pages show at most selected number of posts per page.",'dt_delicate');?> </p>
                    </div>
                </div><!-- Post Count End-->

				<!-- Allow Excerpt -->
                <div class="custom-box">
                    <div class="column one-sixth">                 
                        <label><?php _e('Allow Excerpt','dt_delicate');?></label>
                    </div>
                    <div class="column five-sixth last">                     
						<?php $switchclass = array_key_exists("blog-post-excerpt",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                              $checked = array_key_exists("blog-post-excerpt",$tpl_default_settings) ? ' checked="checked"' : '';?>
                        <div data-for="mytheme-blog-post-excerpt" class="checkbox-switch <?php echo $switchclass;?>"></div>
                        <input id="mytheme-blog-post-excerpt" class="hidden" type="checkbox" name="mytheme-blog-post-excerpt" value="true"  <?php echo $checked;?>/>
                        <p class="note"> <?php _e('Enable Excerpt','dt_delicate');?> </p>
                    </div>
                </div><!-- Allow Excerpt End-->

                <!-- Excerpt Length-->
                <div class="custom-box">
                    <div class="column one-sixth">                                 
                        <label><?php _e('Excerpt Length','dt_delicate');?></label>
                    </div>
                    <div class="column five-sixth last">                     
						<?php $v = array_key_exists("blog-post-excerpt-length",$tpl_default_settings) ?  $tpl_default_settings['blog-post-excerpt-length'] : '45';?>
                        <input id="mytheme-blog-post-excerpt-length" name="mytheme-blog-post-excerpt-length" type="text" value="<?php echo $v;?>" />
                        <p class="note"> <?php _e("Limit! Number of charectors from the content to appear on each blog post (Number Only)",'dt_delicate');?> </p>
                    </div>
                </div><!-- Excerpt Length End-->

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
								"label"=>	__("Disable the comment",'dt_delicate'),
								"tooltip"=>	__("By default the comment will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
							),
							array(
								"id"=>		"disable-category-info",
								"label"=>	__("Disable the category",'dt_delicate'),
								"tooltip"=>	__("By default the category will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
							),
							array(
								"id"=>		"disable-tag-info",
								"label"=>	__("Disable the tag",'dt_delicate'),
								"tooltip"=>	__("By default the tag will display when viewing your posts. You can choose to disable it here.",'dt_delicate')
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
							echo "<input class='hidden' id='{$id}' type='checkbox' name='mytheme-blog-{$id}' value='{$id}' {$rs} />";
							echo '<p class="note">';
							echo ($tooltip);
							echo '</p>';
							echo '</div>';
							echo '</div>';
							
						$count++;	
						endforeach;?>
                </div><!-- Post Meta End-->
                
                <!-- Categories -->
                <div class="custom-box">
                	<h3><?php _e('Exclude Categories','dt_delicate');?></h3>
                    <?php if(array_key_exists("blog-post-exclude-categories",$tpl_default_settings)):
							 $exclude_cats = array_unique($tpl_default_settings["blog-post-exclude-categories"]);
							 foreach($exclude_cats as $cats):
								echo "<!-- Category Drop Down Container -->
									  <div class='multidropdown'>";
								echo  mytheme_categorylist("blog,exclude_cats",$cats,"multidropdown");
								echo "</div><!-- Category Drop Down Container end-->";		
							 endforeach;
						  else:
							echo "<!-- Category Drop Down Container -->";
							echo "<div class='multidropdown'>";
							echo  mytheme_categorylist("blog,exclude_cats","","multidropdown");
							echo "</div><!-- Category Drop Down Container end-->";
						   endif;?>
                    <p class="note"> <?php _e("You can choose certain categories to exclude from your blog page.",'dt_delicate');?> </p>
                </div><!-- Categories End-->
             </div><!-- Blog Template Settings End-->

             <!-- Portfolio Template Settings -->
             <div id="tpl-portfolio">
             	<!-- Post Playout -->
                <div class="custom-box">
                    <div class="column one-sixth">                 
                        <label><?php _e('Posts Layout','dt_delicate');?> </label>
                    </div>
                    <div class="column five-sixth last">       
                        <ul class="bpanel-layout-set">
                        <?php $posts_layout = array(	'one-column'=>	__("Single post per row.",'dt_delicate'),
                                                        'one-half-column'=>	__("Two posts per row.",'dt_delicate'),
                                                        'one-third-column'=>	__("Three posts per row.",'dt_delicate'),
                                                        'one-fourth-column' => __("Four Posts per row",'dt_delicate'));
                                $v = array_key_exists("portfolio-post-layout",$tpl_default_settings) ?  $tpl_default_settings['portfolio-post-layout'] : 'one-column';
                                foreach($posts_layout as $key => $value):
                                    $class = ($key == $v) ? " class='selected' " : "";
                                    echo "<li><a href='#' rel='{$key}' {$class} title='{$value}'><img src='".IAMD_FW_URL."theme_options/images/columns/{$key}.png' /></a></li>";
                                endforeach;?>
                        </ul>
                        <input id="mytheme-portfolio-post-layout" name="mytheme-portfolio-post-layout" type="hidden" value="<?php echo $v;?>"/>
                        <p class="note"> <?php _e("Choose layout style for your Portfolio",'dt_delicate');?> </p>
                    </div>      

                </div><!-- Post Playout End-->

                <!-- Post Count-->
                <div class="custom-box">
                    <div class="column one-sixth">                 
                        <label><?php _e('Post per page','dt_delicate');?></label>
                    </div>
                    <div class="column five-sixth last">   
                        <select name="mytheme-portfolio-post-per-page">
                            <option value="-1"><?php _e("All",'dt_delicate');?></option>
                            <?php $selected = 	array_key_exists("portfolio-post-per-page",$tpl_default_settings) ?  $tpl_default_settings['portfolio-post-per-page'] : ''; ?>
                            <?php for($i=1;$i<=30;$i++):
                                echo "<option value='{$i}'".selected($selected,$i,false).">{$i}</option>";
                                endfor;?>
                        </select>
                        <p class="note"> <?php _e("Your portfolio pages show at most selected number of posts per page.",'dt_delicate');?> </p>
                    </div>
                </div><!-- Post Count End-->

                <div class="custom-box">
                    <div class="column one-sixth">                
	                    <label><?php _e('Allow Filters','dt_delicate');?></label>
                    </div>
                    <div class="column five-sixth last">                       
						<?php $switchclass = array_key_exists("filter",$tpl_default_settings) ? 'checkbox-switch-on' :'checkbox-switch-off';
                              $checked = array_key_exists("filter",$tpl_default_settings) ? ' checked="checked"' : '';?>
                        <div data-for="mytheme-portfolio-filter" class="checkbox-switch <?php echo $switchclass;?>"></div>
                        <input id="mytheme-portfolio-filter" class="hidden" type="checkbox" name="mytheme-portfolio-filter" value="true"  <?php echo $checked;?>/>
                        <p class="note"> <?php _e('Allow filter options for portfolio items','dt_delicate');?> </p>
                    </div>
                </div>

                 <!-- Categories -->
                <div class="custom-box">
                	<h3><?php _e('Choose Categories','dt_delicate');?></h3>
                    <?php if(array_key_exists("portfolio-categories",$tpl_default_settings)):
							 $exclude_cats = array_unique($tpl_default_settings["portfolio-categories"]);
							 foreach($exclude_cats as $cats):
								echo "<!-- Category Drop Down Container -->
									  <div class='multidropdown'>";
								echo  mytheme_portfolio_categorylist("portfolio,cats",$cats,"multidropdown");
								echo "</div><!-- Category Drop Down Container end-->";		
							 endforeach;
						  else:
							echo "<!-- Category Drop Down Container -->";
							echo "<div class='multidropdown'>";
							echo  mytheme_portfolio_categorylist("portfolio,cats","","multidropdown");
							echo "</div><!-- Category Drop Down Container end-->";
						   endif;?>
                    <p class="note"> <?php _e("You can choose only certain categories to show in portfolio items. ",'dt_delicate');?> </p>
                </div><!-- Categories End-->                
                
             </div><!-- Portfolio Template Settings End-->
        </div>    
<?php  wp_reset_postdata();
   } 
   
	function page_meta_save($post_id){
		global $pagenow;
		if ( 'post.php' != $pagenow ) return $post_id;
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 	return $post_id;

			$settings = array();
			$settings['layout'] = $_POST['layout'];
			$settings['disable-everywhere-sidebar'] = $_POST['disable-everywhere-sidebar'];
			
			if(isset($_POST["page_template"]) && ( 'default' == $_POST["page_template"])  || 'tpl-sitemap.php' == $_POST["page_template"] 
			|| 'tpl-home.php' == $_POST["page_template"] || 'tpl-feature.php' ==  $_POST["page_template"] || 'tpl-contact.php' == $_POST["page_template"]) :
				$settings['show_slider'] =  $_POST['mytheme-show-slider'];
				$settings['slider_type'] = $_POST['mytheme-slider-type'];
				$settings['comment'] = $_POST['mytheme-page-comment'];
				#$settings['disable-featured-image'] = $_POST['page-featured-image'];
				$settings['layerslider_id'] = $_POST['layerslider_id'];
				$settings['revolutionslider_id'] = $_POST['revolutionslider_id'];
				
				$settings['menu-icon-class'] = $_POST['mytheme-menu-class'];
				/*#Font Settings
				$settings['font-settings'] = $_POST['mytheme-page-font-settings'];
				$settings['page-font-color'] = $_POST['mytheme-page-font-color'];
				$settings['page-primary-color'] = $_POST['mytheme-page-primary-color'];
				$settings['page-secondary-color'] = $_POST['mytheme-page-secondary-color'];
				$settings['page-title-color'] = $_POST['mytheme-page-title-color'];*/
				
				$settings['full-width-section'] = $_POST['page-full-width-section'];
			
			elseif( "tpl-blog.php" == $_POST['page_template'] ):
				$settings['blog-post-layout'] = $_POST['mytheme-blog-post-layout'];
				$settings['blog-post-per-page'] = $_POST['mytheme-blog-post-per-page'];
				$settings['blog-post-excerpt'] = $_POST['mytheme-blog-post-excerpt'];
				$settings['blog-post-excerpt-length'] = $_POST['mytheme-blog-post-excerpt-length'];
				$settings['blog-post-exclude-categories'] = $_POST['mytheme']['blog']['exclude_cats'];
				$settings['disable-date-info'] = $_POST['mytheme-blog-disable-date-info'];
				$settings['disable-author-info'] = $_POST['mytheme-blog-disable-author-info'];
				$settings['disable-comment-info'] = $_POST['mytheme-blog-disable-comment-info'];
				$settings['disable-category-info'] = $_POST['mytheme-blog-disable-category-info'];
				$settings['disable-tag-info'] = $_POST['mytheme-blog-disable-tag-info'];
			
			elseif( "tpl-portfolio.php" == $_POST['page_template'] ):
				$settings['portfolio-post-layout'] = $_POST['mytheme-portfolio-post-layout'];
				$settings['portfolio-post-per-page'] = $_POST['mytheme-portfolio-post-per-page'];
				$settings['filter'] = $_POST['mytheme-portfolio-filter'];	
				$settings['portfolio-categories'] = $_POST['mytheme']['portfolio']['cats'];
				
			endif;

			
		
		update_post_meta($post_id, "_tpl_default_settings", array_filter($settings));
		
	}?>