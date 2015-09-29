        <!-- **Header** -->
        <header id="header">
        
            <!-- **Header Container** -->
            <div class="container">
                <!-- **Logo - End** -->
                <div id="logo">
                <?php if( mytheme_option('general', 'logo') ):
                            $url = mytheme_option('general', 'logo-url');
                            $url = !empty( $url ) ? $url : IAMD_BASE_URL."/images/logo.png"; ?>
                            <a href="<?php echo home_url();?>" title="<?php echo mytheme_blog_title();?>">
                                <img src="<?php echo $url;?>" alt="<?php echo mytheme_blog_title(); ?>" title="<?php echo mytheme_blog_title(); ?>" />
                            </a>
                <?php else: ?>
                            <h2><a href="<?php echo home_url();?>" title="<?php echo mytheme_blog_title();?>"><?php echo do_shortcode(get_option('blogname')); ?></a></h2>
                <?php endif;?>
                </div><!-- **Logo - End** -->
    
                <!-- **Navigation** -->
                <div id="primary-menu">
                    <nav id="main-menu">
                    <?php $primaryMenu = NULL;
                    if (function_exists('wp_nav_menu')) :
                            $primaryMenu = wp_nav_menu(array('theme_location'=>'primary-menu','menu_id'=>'','menu_class'=>'menu','fallback_cb'=>'mytheme_default_navigation'
                            ,'echo'=>false,'container'=>'false','walker' => new my_menu_walker()));
                    endif;
                    if(!empty($primaryMenu))	echo $primaryMenu;?>
                    </nav><!-- **Navigation - End** -->
                 </div>
                
            </div><!-- **Header Container End** -->
            
        </header><!-- **Header - End** -->
