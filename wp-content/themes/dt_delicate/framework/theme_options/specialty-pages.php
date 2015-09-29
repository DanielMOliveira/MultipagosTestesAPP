<!-- #specialty-pages -->
<div id="specialty-pages" class="bpanel-content">
    <!-- .bpanel-main-content -->
    <div class="bpanel-main-content">
    	 <ul class="sub-panel">
         <?php $sub_menus = array(
					array("title"=>__("Archives",'dt_delicate'), "link"=>"#archives"),
					array("title"=>__("Search",'dt_delicate'), "link"=>"#search"),
					array("title"=>__("404",'dt_delicate'), "link"=>"#404"));
				  foreach($sub_menus as $menu): ?>
                  	<li><a href="<?php echo $menu['link']?>"><?php echo $menu['title'];?></a></li>
		 <?php endforeach?>
         </ul>
         
         <?php $tabs = array(
			 			array(	"id"=>"archives", 
								"layout-title"=>__("Archives Layout",'dt_delicate'),
								"layout-tooltip"=>__("You can choose between a left, right or no sidebar layout for the Archive page.",'dt_delicate'),								
								
								"post-layout-title" => __("Posts Layout",'dt_delicate'),
								"post-layout-tooltip"=>__("Your archive results will use the layout you select below.",'dt_delicate'),								
								
								
								"bg-title"=>__("Archives Background",'dt_delicate'),
								"bg-label"=>__("Archives background image",'dt_delicate'),
								"bg-tooltip"=>__('Upload an image for the theme\'s Archives page background','dt_delicate'),
								
								//bg Color
								"bg-color-label" =>__("Archives Background Color",'dt_delicate'),
								"bg-color-tooltip" =>__("Pick a custom color for background color of the theme's archive page.(e.g. #a314a3)",'dt_delicate')
						 ),
						array(  "id"=>"search",
								"layout-title"=>__("Search Layout",'dt_delicate'),
								"layout-tooltip"=>__("You can choose between a left, right or no sidebar layout for your Search page.",'dt_delicate'),								

								"post-layout-title" => __("Posts Layout",'dt_delicate'),
								"post-layout-tooltip"=>__("Your Search results will use the layout you select below.",'dt_delicate'),								
								
								"bg-title"=>__("Search Background",'dt_delicate'),
								"bg-label"=>__("Search background image",'dt_delicate'),
								"bg-tooltip"=>__('Upload an image for the theme\'s Search page background','dt_delicate'),

								//bg Color
								"bg-color-label" =>__("Search Background Color",'dt_delicate'),
								"bg-color-tooltip" =>__("Pick a custom color for background color of the theme's search page.(e.g. #a314a3)",'dt_delicate')
								
						),
						array(  "id"=>"404",
								"layout-title"=>__("404 Layout",'dt_delicate'),
								"layout-tooltip"=>__("You can choose between a left, right or no sidebar layout for your 404 page.",'dt_delicate'),
								
								"bg-title"=>__("404 Background",'dt_delicate'),
								"bg-label"=>__("404 background image",'dt_delicate'),
								"bg-tooltip"=>__('Upload an image for the theme\'s 404 page background','dt_delicate'),

								//bg Color
								"bg-color-label" =>__("404 Background Color",'dt_delicate'),
								"bg-color-tooltip" =>__("Pick a custom color for background color of the theme's 404 page.(e.g. #a314a3)",'dt_delicate'),
								
								"content-title" => __("404 Message",'dt_delicate'),
								"content-tooltip"=>__("You can give custom 404 page message",'dt_delicate')
						));?>
        <?php foreach($tabs as $tab): 
				$id =  $tab['id'];?>
        	<div id="<?php echo $id;?>" class="tab-content">
            	 <div class="bpanel-box">
                 
                 	<!-- Section 1 -->	
                    <div class="box-title"><h3><?php echo $tab['layout-title'];?></h3></div>
                    <div class="box-content">
                    	<p class="note"> <?php echo ($tab['layout-tooltip']);?></p>
                    	<div class="bpanel-option-set">
                        	<ul class="bpanel-layout-set">
                           	<?php $layout = array('content-full-width'=>'without-sidebar','with-left-sidebar'=>'left-sidebar','with-right-sidebar'=>	'right-sidebar');
							foreach($layout as $key => $value):
								$class = ( $key ==  mytheme_option('specialty',"{$id}-layout")) ? " class='selected' " : "";
								echo "<li><a href='#' rel='{$key}' {$class}><img src='".IAMD_FW_URL."theme_options/images/columns/{$value}.png' /></a></li>";
							endforeach; ?>
                            </ul>
                            <input id="mytheme[specialty][<?php echo $id;?>-layout]" name="mytheme[specialty][<?php echo $id;?>-layout]" type="hidden"  
                            	value="<?php echo mytheme_option('specialty',"{$id}-layout");?>"/>
                        </div>
                    </div><!-- Section 1 End -->

                    <?php if( ($id == "archives") or ($id == "search") ): ?>
                    <!-- Post Layout Section -->
	                <div class="box-title"><h3><?php echo $tab['post-layout-title'];?></h3></div>
                    <div class="box-content">
                    	<p class="note"><?php echo $tab['post-layout-tooltip'];?></p>
                    	<div class="bpanel-option-set">
                        	<ul class="bpanel-layout-set">
                            <?php $posts_layout = array('one-column'=>__("Single post per row.",'dt_delicate'),'one-half-column'=>__("Two posts per row.",'dt_delicate'),
														'one-third-column' => __("Three post per row.",'dt_delicate'));
									$v = mytheme_option('specialty',"{$id}-post-layout");
									$v = !empty($v) ? $v : "one-column";
								  foreach($posts_layout as $key => $value):
									$class = ( $key ==  $v ) ? " class='selected' " :"";								  
									echo "<li><a href='#' rel='{$key}' {$class} title='{$value}'><img src='".IAMD_FW_URL."theme_options/images/columns/{$key}.png' /></a></li>";
                           		 endforeach;?>
                    		</ul>
                            <input id="mytheme[specialty][<?php echo $id;?>-post-layout]" name="mytheme[specialty][<?php echo $id;?>-post-layout]" type="hidden"  
                            	value="<?php echo mytheme_option('specialty',"{$id}-post-layout");?>"/>
                        </div>
                    </div>
                    <!-- Post Layout Section End-->
                    <?php endif; ?>
                    
                    <!-- 404 Content -->
                    <?php if($id == "404"): ?>
                        <div class="box-title"><h3><?php echo $tab['content-title'];?></h3></div>
                        <div class="box-content">
                        	<p class="note"><?php echo ($tab['content-tooltip']);?></p>
                            
                            
                            <div class="bpanel-option-set">
                                <h6><?php _e('404 Custom Message','dt_delicate');?></h6>
                                <textarea id="mytheme-404-text" name="mytheme[specialty][404-message]" rows="" 
                                	cols=""><?php echo stripslashes(mytheme_option('specialty','404-message'));?></textarea>
                            </div>
                            <div class="hr"></div>
                            
                            <h6><?php _e("Disable Font Settings",'dt_delicate')?></h6>
                            <div class="column one-fifth bpanel-option-set">
                            	<?php mytheme_switch("",'specialty','disable-404-font-settings');?>
                            </div>
                            <div class="column four-fifth last"><p class="note"><?php _e('Enable / Disable 404 Font settings','dt_delicate');?></p></div>
                            <div class="hr"></div>
                        
                        	<!-- Font Section -->                        	
                            <div class="column one-column">
                                <div class="bpanel-option-set">
                                    <?php mytheme_admin_fonts(__('Message Font','dt_delicate'),'mytheme[specialty][message-font]',mytheme_option('specialty','message-font'));?>
                                </div>
                            </div>
                            <!-- Font Section -->
                            <div class="hr_invisible"> </div>
                            <!-- Font Color Section -->
                            <div class="column one-half">
        	                    <?php $label = 		__("Message Font Color",'dt_delicate');
									  $name  =		"mytheme[specialty][message-font-color]";	
									  $value =  	 (mytheme_option('specialty','message-font-color')!= NULL) ? mytheme_option('specialty','message-font-color') : "#";
									  $tooltip = 	__("Pick a custom color for 404 message font color of the theme e.g. #a314a3",'dt_delicate'); ?>
									  <h6> <?php echo $label;?> </h6>
                                  <?php mytheme_admin_color_picker("",$name,$value,'');?>
                            
                            </div><!-- Font Color Section -->
                            <div class="column one-half last">
								<?php mytheme_admin_jqueryuislider(__('Message Font Size','dt_delicate'),"mytheme[specialty][message-font-size]",
    	                        mytheme_option('specialty',"message-font-size"));?>
                            </div>
                            
                        </div>
                    <?php endif;?>
                    <!-- 404 Content End-->

                 </div><!-- .bpanel-box end -->
            </div><!-- .tab-content end -->
        <?php endforeach;?>
    </div>
</div><!-- #specialty-pages end-->