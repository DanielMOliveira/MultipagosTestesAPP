<!-- #appearance -->
<div id="appearance" class="bpanel-content">
    <!-- .bpanel-main-content -->
    <div class="bpanel-main-content">
        <ul class="sub-panel">
			<?php $sub_menus = array(
						array("title"=>__("Header",'dt_delicate'), "link" => "#appearance-header" ),
						array("title"=>__("Menu",'dt_delicate'), "link"=>"#appearance-menu"),
						array("title"=>__("Body",'dt_delicate'), "link"=>"#appearance-body"),
						array("title"=>__("Footer",'dt_delicate'), "link"=>"#appearance-footer"),
						array("title"=>__("Typography",'dt_delicate'), "link"=>"#appearance-typography"),
						array("title"=>__("Layout & BG",'dt_delicate'), "link"=>"#appearance-layout"));
						
				  foreach($sub_menus as $menu): ?>
                  	<li><a href="<?php echo $menu['link']?>"><?php echo $menu['title'];?></a></li>
			<?php endforeach?>
        </ul>
        
        <!-- Header Section -->
        <div id="appearance-header" class="tab-content">
        	<div class="bpanel-box">
                <div class="box-title"><h3><?php _e('Choose Header','dt_delicate');?></h3></div>
                <div class="box-content">
                	<h6><?php _e('Header','dt_delicate'); ?></h6>
                    <p class="note no-margin"> <?php _e("Choose the header type",'dt_delicate');?> </p>
                    <div class="hr_invisible"> </div>
					<div class="bpanel-option-set">    
                         <ul class="bpanel-layout-set">
                         <?php $header_types = array("header1","header2","header3","header4");
							 foreach( $header_types as $header_type):
							 	$class = ( $header_type ==  mytheme_option('appearance','header_type')) ? " class='selected' " : "";?>
                                <li class="headerlayout"><a href="#" rel="<?php echo $header_type;?>" <?php echo $class;?> title="<?php echo $header_type;?>">
                                    <img src="<?php echo IAMD_FW_URL."theme_options/images/headers/{$header_type}.png";?>" />
                                </a></li>
						 <?php endforeach; ?>
                         </ul>
                         <input id="mytheme[appearance][header_type]" name="mytheme[appearance][header_type]" type="hidden" value="<?php echo  mytheme_option('appearance','header_type');?>"/>                         
                    </div>
                </div>
            </div>
        </div><!-- Header Section End -->
        
        
        <!-- Menu Section -->
        <div id="appearance-menu" class="tab-content">
        	<div class="bpanel-box">
            
                <!-- Header Font -->
                <div class="box-title"><h3><?php _e('Menu Font','dt_delicate');?></h3></div>
                <div class="box-content">
            
                    <div class="bpanel-option-set">
                    	<h6><?php _e('Disable Menu Settings','dt_delicate');?></h6>
                        <?php mytheme_switch("",'appearance','disable-menu-settings');?>
                        <p class="note"> <?php _e('Enable or Disable Menu section apperance settings.','dt_delicate');?>  </p>                        
                    </div>
                    
                    <div class="clear"> </div>
                    <div class="hr"> </div>
                
                    <div class="column one-column bpanel-option-set">
                        <?php mytheme_admin_fonts(__('Menu Font','dt_delicate'),'mytheme[appearance][menu-font]',mytheme_option('appearance','menu-font'));?>
                        <p class="note"> <?php _e('Choose the menu font','dt_delicate');?> </p>                        
                        <div class="clear"></div>
                        <?php mytheme_admin_jqueryuislider(__('Menu Font Size','dt_delicate'),"mytheme[appearance][menu-font-size]",
						mytheme_option('appearance',"menu-font-size"));?>
                    </div>
                    
                    <div class="hr"> </div>

                    <div class="column one-half">
                    <?php $label = 		__("Primary / Default Color",'dt_delicate');
                          $name  =		"mytheme[appearance][menu-primary-color]";	
						  $value =  	(mytheme_option('appearance','menu-primary-color') != NULL) ? mytheme_option('appearance','menu-primary-color') : "#";
                          $tooltip = 	__("Pick a custom primary color for the menu e.g. #564912",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>	
                          <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                    
                    </div>

                    <div class="column one-half last">
                    <?php $label = 		__("Hover Color",'dt_delicate');
                          $name  =		"mytheme[appearance][menu-secondary-color]";	
						  $value =  	(mytheme_option('appearance','menu-secondary-color')  != NULL) ? mytheme_option('appearance','menu-secondary-color') : "#";
                          $tooltip = 	__("Pick a custom hover state color for the menu e.g. #564912",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>	
                          <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                    
                    </div>
                </div><!-- Header Font End-->
            </div>
        </div><!-- Menu Section (#appearance-menu) End-->
        
        <!-- Body Section -->
        <div id="appearance-body" class="tab-content">
        	<div class="bpanel-box">
            	
                <!-- Body Font Settings -->
                <div class="box-title"><h3><?php _e('Body Font','dt_delicate');?></h3></div>
                <div class="box-content">
                
                    <div class="bpanel-option-set">
                    	<h6><?php _e('Disable Body Settings','dt_delicate');?></h6>
                        <?php mytheme_switch("",'appearance','disable-boddy-settings');?>
                        <p class="note"> <?php _e('Enable or Disable Body apperance settings.','dt_delicate');?>  </p>
                    </div>    
                    
                    <div class="hr"> </div>
                
                	<div class="column one-column">
                    	<div class="bpanel-option-set">
                        <?php mytheme_admin_fonts(__('Body Font','dt_delicate'),'mytheme[appearance][body-font]',
													  mytheme_option('appearance','body-font'));?>
                        <div class="clear"></div>                                                      
                        <p class="note"> <?php _e('Choose the body font','dt_delicate');?> </p>
                        </div>
                    </div>

                	<div class="column one-half">
                    <?php $label = 		__("Body Font Color",'dt_delicate');
						  $name  =		"mytheme[appearance][body-font-color]";	
						  $value =  	(mytheme_option('appearance','body-font-color') != NULL) ? mytheme_option('appearance','body-font-color') :"#";
						  $tooltip = 	__("Pick a custom font color for body/content e.g. #a314a3",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6> 
                          <?php mytheme_admin_color_picker('',$name,$value,"");?> 
                          <p class="note no-margin"><?php echo $tooltip;?></p>   
                    </div>
                	<div class="column one-half last">
						  <?php mytheme_admin_jqueryuislider(__('Body Font Size','dt_delicate'),"mytheme[appearance][body-font-size]",
                                  mytheme_option('appearance',"body-font-size"));?>                                             
 					</div>                               
                    <div class="hr"> </div>

                	<div class="one-half-content">
                    <?php $label = 		__("Primary / Default Color for Links",'dt_delicate');
						  $name  =		"mytheme[appearance][body-primary-color]";	
						  $value =  	(mytheme_option('appearance','body-primary-color') != NULL) ? mytheme_option('appearance','body-primary-color') :"#";
						  $tooltip = 	__("Pick a custom primary color to links and buttons for body/content e.g. #551256",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>	
						  <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                     
                    </div>

                	<div class="one-half-content last">
                    <?php $label = 		__("Hover Color for Links",'dt_delicate');
						  $name  =		"mytheme[appearance][body-secondary-color]";
						  $value =  	(mytheme_option('appearance','body-secondary-color') != NULL) ? mytheme_option('appearance','body-secondary-color') :"#";
						  $tooltip = 	__("Pick a custom hover state color to links and buttons for body/content e.g. #564912",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>	
                          <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                   
                    </div>
                </div>
                <!-- Body Font Settings End-->
                 
            </div>
        </div><!-- Body Section(#appearance-body) end -->
        
        <!-- Footer Section -->
        <div id="appearance-footer" class="tab-content">
        	<div class="bpanel-box">

                <!-- Footer Font -->
                <div class="box-title"><h3><?php _e('Footer Font','dt_delicate');?></h3></div>
                <div class="box-content">
                
                    <div class="bpanel-option-set">
                    	<h6><?php _e('Disable Footer Settings','dt_delicate');?></h6>
                        <?php mytheme_switch(__("Disable Footer Settings",'dt_delicate'),'appearance','disable-footer-settings');?>
                        <p class="note"><?php _e('Enable or Disable Footer apperance settings.','dt_delicate');?>  </p>
                    </div>
                    
                    <div class="hr"> </div>

                    <div class="column one-column bpanel-option-set">
                        <?php mytheme_admin_fonts(__('Footer Title Font','dt_delicate'),'mytheme[appearance][footer-title-font]',
                        mytheme_option('appearance','footer-title-font'));?>
                        <div class="clear"></div>
                        <p class="note"> <?php _e('Choose the footer font','dt_delicate');?> </p>
                    </div>
                    
                    <div class="column one-half last">
                    <?php $label = 		__("Footer Title Font Color",'dt_delicate');
                          $name  =		"mytheme[appearance][footer-title-font-color]";
						  $value =  	(mytheme_option('appearance','footer-title-font-color') != NULL) ? mytheme_option('appearance','footer-title-font-color') :"#";
						  $tooltip = 	__("Pick a custom footer title font color to the theme e.g. #a314a3",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>
                          <?php mytheme_admin_color_picker("",$name,$value,'');?>   
                    <p class="note"><?php echo $tooltip;?></p>
                    </div>
                    
                    <div class="column one-half last">
					<?php mytheme_admin_jqueryuislider(__('Footer Font Size','dt_delicate'),"mytheme[appearance][footer-font-size]",
                          mytheme_option('appearance',"footer-font-size"));?>
                    </div>
                    
                    <div class="hr"> </div>

                    <div class="column one-column bpanel-option-set">
                        <?php mytheme_admin_fonts(__('Footer Content Font','dt_delicate'),'mytheme[appearance][footer-content-font]',
                        mytheme_option('appearance','footer-content-font'));?>
                        <div class="clear"></div>
                        <p class="note"> <?php _e('Choose the footer content font','dt_delicate');?> </p>
                    </div>
                    
                    <div class="hr_invisible"> </div>
                    
                    <div class="column one-half">
                    <?php $label = 		__("Footer Content Font Color",'dt_delicate');
                          $name  =		"mytheme[appearance][footer-content-font-color]";
						  $value =  	(mytheme_option('appearance','footer-content-font-color') != NULL) ? mytheme_option('appearance','footer-content-font-color') :"#";
						  $tooltip = 	__("Pick a custom font color for footer content e.g. #a314a3",'dt_delicate'); ?>
						  <h6><?php echo $label;?></h6>
                          <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                  
                    </div>
                    
                    <div class="column one-half last">
						<?php mytheme_admin_jqueryuislider(__('Footer Content Font Size','dt_delicate'),"mytheme[appearance][footer-content-font-size]",
                              mytheme_option('appearance',"footer-content-font-size"));?>                    
                    </div>
                    
                    <div class="hr"> </div>
                    
                    <div class="column one-half">
                    <?php $label = 		__("Primary / Default Color for Links",'dt_delicate');
                          $name  =		"mytheme[appearance][footer-primary-color]";	
						  $value =  	(mytheme_option('appearance','footer-primary-color') != NULL) ? mytheme_option('appearance','footer-primary-color') :"#";
                          $tooltip = 	__("Pick a custom primary color for links in footer e.g. #551256",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>
                	      <?php mytheme_admin_color_picker("",$name,$value,'');?>  
                          <p class="note"><?php echo $tooltip;?></p>                  
                    </div>

                    <div class="column one-half last">
                    <?php $label = 		__("Hover Color for Links",'dt_delicate');
                          $name  =		"mytheme[appearance][footer-secondary-color]";	
						  $value =  	(mytheme_option('appearance','footer-secondary-color') != NULL) ? mytheme_option('appearance','footer-secondary-color') :"#";
                          $tooltip = 	__("Pick a custom color for footer links hover state e.g. #564912",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>
                	      <?php mytheme_admin_color_picker("",$name,$value,'');?>   
                          <p class="note"><?php echo $tooltip;?></p>                 
                    </div>
                    
                    <div class="hr"> </div>

                    <div class="column one-half">
                    <?php $label = 		__("Footer BG Color",'dt_delicate');
    	                  $name  =		"mytheme[appearance][footer-bg-color]";	
        	              $value =  	(mytheme_option('appearance','footer-bg-color')  != NULL) ? mytheme_option('appearance','footer-bg-color') : "#";
            	          $tooltip = 	__("Pick a custom background color of the theme's footer section.(e.g. #a314a3)",'dt_delicate'); ?>
                          <h6><?php echo $label;?></h6>
                	      <?php mytheme_admin_color_picker("",$name,$value,'');?>
                          <p class="note"><?php echo $tooltip;?></p>
                    </div>
                    <div class="column one-half last">
                    <?php $label = 		__("Copyright Section BG Color",'dt_delicate');
    	                  $name  =		"mytheme[appearance][copyright-bg-color]";	
        	              $value =  	(mytheme_option('appearance','copyright-bg-color')  != NULL) ? mytheme_option('appearance','copyright-bg-color') : "#";
            	          $tooltip = 	__("Pick a custom background color of the theme's copyright section.(e.g. #a314a3)",'dt_delicate'); ?>
						  <h6><?php echo $label;?></h6>
                	      <?php mytheme_admin_color_picker("",$name,$value,'');?> 
                          <p class="note"><?php echo $tooltip;?></p>                         
                	</div>
                    
                </div>
                <!-- Footer Font End-->
			
            
            </div>
        </div><!-- Footer Section (#appearance-footer) End-->
        
        
        <!-- Typography Section -->
        <div id="appearance-typography" class="tab-content">
	        <div class="bpanel-box">
            
                <div class="box-title">
                	<h3><?php _e("Disable Typography Settings",'dt_delicate'); ?></h3>
                </div>
                <div class="box-content">
                    <div class="bpanel-option-set">
                    	<h6><?php _e('Disable Typography Settings','dt_delicate');?></h6>
                        <?php mytheme_switch("",'appearance','disable-typography-settings');?>
                        <p class="note"> <?php _e('Enable or Disable the typography settings','dt_delicate');?>  </p>
                    </div>
                </div>
            
            	<?php for($i=1;$i<=6;$i++): ?>
                    <div class="box-title">
                    	<h3><?php echo "H{$i} ";?><?php _e('Font Family, Size and Color','dt_delicate');?></h3>
                        
                    </div>
                    <div class="box-content">
                    	 <p class="note"> <?php _e("Choose Font Family, Size and Color for",'dt_delicate'); echo " H{$i}"?> </p>
                         <div class="column one-column">
                            <div class="bpanel-option-set">
                            	<?php mytheme_admin_fonts("H{$i} ".__('Font','dt_delicate'),"mytheme[appearance][H{$i}-font]",mytheme_option('appearance',"H{$i}-font"));?>
                            </div>
                         </div>
                         <div class="hr_invisible"> </div>
                         <div class="column one-half last">
							<?php $label = 		"H{$i} ".__("Font Color",'dt_delicate');
                                  $name  =		"mytheme[appearance][H{$i}-font-color]";
								  $value =  	(mytheme_option('appearance',"H{$i}-font-color") != NULL) ? mytheme_option('appearance',"H{$i}-font-color") :"#"; ?>
								  <h6><?php echo $label;?></h6>	
                                  <?php mytheme_admin_color_picker("",$name,$value);?>                    
                         </div>
                         <div class="column one-half last">
						 	<?php mytheme_admin_jqueryuislider("H{$i} ".__('Font Size','dt_delicate'),
                           		"mytheme[appearance][H{$i}-size]",mytheme_option('appearance',"H{$i}-size"));?>
                    	 </div>     
                    </div>
                <?php endfor;?>
            </div><!-- .bpanel-box end -->
        </div><!-- Typography Section -->



        <!--Layout Section -->
        <div id="appearance-layout" class="tab-content">
        	<!-- Layout Selection-->
	        <div class="bpanel-box">
                <div class="box-title">
                	<h3><?php _e('Choose Layout','dt_delicate');?></h3>
                    
                </div>
                <div class="box-content">
                	<h6><?php _e('Layout','dt_delicate');?></h6>
                	<p class="note no-margin"> <?php _e("Choose the Layout Style(Boxed / Fullwidth)","dt_delicate");?> </p>
                    <div class="hr_invisible"> </div>
					<div class="bpanel-option-set">    
                         <ul class="bpanel-layout-set">
                         	<?php $layouts = array("boxed","wide");
								  foreach($layouts as $layout):
								  	$class = ( $layout ==  mytheme_option('appearance','layout')) ? " class='selected' " : "";?>
                                  	<li class="themelayout"><a href="#" rel="<?php echo $layout;?>" <?php echo $class;?> title="<?php echo $layout;?>">
                                    	<img src="<?php echo IAMD_FW_URL."theme_options/images/layouts/{$layout}.png";?>" />
                                    </a></li>
                            <?php endforeach;?>	      
                         </ul>
                         <input id="mytheme[appearance][layout]" name="mytheme[appearance][layout]" type="hidden" 
                         		value="<?php echo  mytheme_option('appearance','layout');?>"/>
                    </div>
                </div><!-- .box-content -->
			</div><!-- Layout Selection End-->
            
        	<!-- Boxed Layout Settings -->
            <?php $style = (mytheme_option('appearance','layout') == "boxed") ? '' :'style="display:none;"'; ?>
	        <div id="boxed" class="bpanel-box" <?php echo $style;?>>
                <div class="box-title"><h3><?php _e('Boxed Layout BG Background','dt_delicate');?></h3></div>
                <div class="box-content">
                
                    <?php mytheme_bgtypes("mytheme[appearance][bg-type]","appearance","bg-type");?>
                 
                    <?php $bg_pattern = ( mytheme_option('appearance','bg-type')=="bg-patterns" ) ? 'style="display:block"' : 'style="display:none"'; ?>
                    <?php $bg_custom = ( mytheme_option('appearance','bg-type')=="bg-custom" ) ? 'style="display:block"' : 'style="display:none"'; ?>
                
                	<!-- In-built BG Patterns starts-->
                    <div class="bg-pattern" <?php echo $bg_pattern;?>>
                    	<div class="hr_invisible"> </div>
                    	<h6> <?php _e('Choose Patterns','dt_delicate');?> </h6>
                    	<!-- Pattern Sets Start -->
                    	<div class="bpanel-option-set">
                        	
                            <ul class="bpanel-layout-set">
                            <?php $pattrens  = mytheme_listImage(IAMD_FW."theme_options/images/patterns/");
								foreach($pattrens as $key => $value):
									$class = ( $key ==  mytheme_option('appearance','boxed-layout-pattern')) ? " class='selected' " : "";
									echo "<li>";
									echo "<a href='#' rel='{$key}' {$class}><img width='80px' height='80px' src='".IAMD_FW_URL."theme_options/images/patterns/$key' /></a>";
									echo "</li>";
								endforeach;?></ul>
                            <input id="mytheme[appearance][boxed-layout-pattern]" name="mytheme[appearance][boxed-layout-pattern]" type="hidden" 
                         			value="<?php echo  mytheme_option('appearance','boxed-layout-pattern');?>"/>
                           <p class="note">	<?php _e('Choose background pattern, you can add patterns by placing the png files in the folder ','dt_delicate');
						   	echo ('<b>framework/theme_options/images/pattern/</b>');?>   </p>
                        </div><!-- Patterns set End -->

                        <!-- Pattern BG Settings -->
                        <div class="column one-column">
                        	<div class="bpanel-option-set">
                                <h6><?php _e('Boxed Layout Background Pattern repeat','dt_delicate');?></h6>
                                <div class="clear"></div>
                                <select name="mytheme[appearance][boxed-layout-pattern-repeat]">
                                    <option value=""><?php _e("Select",'dt_delicate');?></option>
                                    <?php $options = array("repeat","repeat-x","repeat-y","no-repeat");
										foreach($options as $option):?>
                                        <option value="<?php echo $option;?>"
                                            <?php selected($option,mytheme_option('appearance','boxed-layout-pattern-repeat')); ?>><?php echo $option;?></option>
                                    <?php endforeach;?>
                                </select>
                                <p class="note"> <?php _e("Select how would you like to repeat the pattern image",'dt_delicate');?> </p>
                            </div>
                            
                        </div>
                        
                        <div class="hr"> </div>
                        
                        <div class="column one-half">
                            <h6><?php _e("Disable Background Color",'dt_delicate');?></h6>
                            <?php mytheme_switch("",'appearance','disable-boxed-layout-pattern-color');?>
                        </div>
                            
                        
                        <div class="column one-half last">
                        
                        <?php $label = 		__("Choose Background Color",'dt_delicate');
                              $name  =		"mytheme[appearance][boxed-layout-pattern-color]";
                              $value =  	(mytheme_option('appearance','boxed-layout-pattern-color') != NULL) ? mytheme_option('appearance','boxed-layout-pattern-color') :"#";
                              $tooltip = 	__("Pick a custom background color of the theme.(e.g. #a314a3)",'dt_delicate');
                                mytheme_admin_color_picker($label,$name,$value,'');?>    
                                
                                <p class="note"> <?php echo $tooltip;?></p>
                        </div>
                        <!-- Pattern BG Settings end-->    
                        
                        <div class="hr"> </div>
                        
                        <div class="bpanel-option-set">
                        <?php echo mytheme_admin_jqueryuislider( __("Background opacity",'dt_delicate'),	"mytheme[appearance][boxed-layout-pattern-opacity]",
                                                                          mytheme_option("appearance","boxed-layout-pattern-opacity"),"");?>
                        </div> 
                        
                    </div><!-- In-built BG Patterns ends-->
                     	
                	<!-- Upload custom BG option Starts -->
                    <div class="bg-custom" <?php echo $bg_custom;?>>
                        
                        <div class="hr_invisible"> </div>
                        <h6><?php _e("Boxed Layout Background Image",'dt_delicate');?></h6>
                        <div class="clear"></div>
                        <div class="image-preview-container">
                            <input id="mytheme-boxed-layout-bg" name="mytheme[appearance][boxed-layout-bg]" type="text" class="uploadfield medium" readonly="readonly"
                                    value="<?php echo mytheme_option('appearance','boxed-layout-bg');?>"/>
                            <input type="button" value="<?php _e('Upload','dt_delicate');?>" class="upload_image_button show_preview" />
                            <input type="button" value="<?php _e('Remove','dt_delicate');?>" class="upload_image_reset" />
                            <?php mytheme_adminpanel_image_preview(mytheme_option('appearance','boxed-layout-bg'));?>
                        </div>
                        <p class="note"> <?php _e("Upload an image for the theme's background",'dt_delicate');?> </p>
                       
                       <div class="hr_invisible"> </div>                       
                
                        <!-- Boxed Layout BG Settings -->
                        <div class="column one-half">
                        <?php $bg_settings = array(
                                    array(
                                        "label"=>	__('Background Image Repeat','dt_delicate'),
                                        "tooltip"=>	__("Select how would you like to repeat the background image",'dt_delicate'),
                                        "name" => "mytheme[appearance][boxed-layout-bg-repeat]",
                                        "db-key"=>"boxed-layout-bg-repeat",
                                        "options"=>  array("repeat","repeat-x","repeat-y","no-repeat")
                                    ),
                                    array(
                                        "label"=>__('Background Image Position','dt_delicate'),
                                        "tooltip"=>	__("Select how would you like to position the background",'dt_delicate'),
                                        "name" => "mytheme[appearance][boxed-layout-bg-position]",
                                        "db-key"=>"boxed-layout-bg-position",
                                        "options"=>  array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right")
                                    )
                                );
                    
                              foreach($bg_settings as $bgsettings): ?>
                                  <div class="bpanel-option-set">
                                    <label><?php echo $bgsettings['label'];?></label>
                                    <div class="clear"></div>
                                    <select name="<?php echo $bgsettings['name'];?>">
                                        <option value=""><?php _e("Select",'dt_delicate');?></option>
                                        <?php foreach($bgsettings['options'] as $option):?>
                                            <option value="<?php echo $option;?>"
                                                <?php selected($option,mytheme_option('appearance',$bgsettings['db-key'])); ?>><?php echo $option;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <p class="note"> <?php echo $bgsettings['tooltip'];?>  </p>
                                    <div class="hr_invisible"> </div>
                                  </div>
                        <?php endforeach;?>
                        		 <div class="bpanel-option-set">	
                                     
                                 	 <h6><?php _e("Disable Background Color",'dt_delicate');?></h6>
	                        		 <?php mytheme_switch("",'appearance','disable-boxed-layout-bg-color');?>
                                 </div>    
                        </div><!-- Boxed Layout BG Settings End -->
                        
                         <!-- Boxed Layout BG Color -->
                         <div class="column one-half last">
	                        
                            <?php $label = 		__("Background Color",'dt_delicate');
                                  $name  =		"mytheme[appearance][boxed-layout-bg-color]";
                                  $value =  	(mytheme_option('appearance','boxed-layout-bg-color') != NULL) ? mytheme_option('appearance','boxed-layout-bg-color') :"#";
                                  $tooltip = 	__("Pick a background color of the theme.(e.g. #a314a3)",'dt_delicate');
                                mytheme_admin_color_picker($label,$name,$value,'');?>
                                
                                <p class="note"> <?php echo $tooltip;?> </p>
                                
                                <div class="hr_invisible"> </div>
                                
							 <?php echo mytheme_admin_jqueryuislider( __("Background opacity",'dt_delicate'),	"mytheme[appearance][boxed-layout-bg-opacity]",
                                                                      mytheme_option("appearance","boxed-layout-bg-opacity"),"");?>                                
                         </div><!-- Boxed Layout BG Color -->
                    </div><!-- Upload custom BG option Ends -->
                     
                </div><!-- .box-content -->   
            </div><!-- .bpanel-box -->    
        </div><!--Layout Section  End-->

        

        
    </div><!-- .bpanel-main-content end -->
</div><!-- #appearance  end-->