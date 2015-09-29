<!DOCTYPE html>
<!--[if lt IE 7]>  <html class="ie ie6 lte9 lte8 lte7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>     <html class="ie ie7 lte9 lte8 lte7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>     <html class="ie ie8 lte9 lte8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>     <html class="ie ie9 lte9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]>  <html> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php is_mytheme_moible_view();?>
<title><?php mytheme_public_title();?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php	#To load basic css
		load_mytheme_basic_css();
			
		#To Load responsive.css 
		set_mytheme_layout();
		
		do_action('load_head_styles_scripts');
		
		
		if(mytheme_option('integration', 'enable-header-code'))
			echo stripslashes(mytheme_option('integration', 'header-code'));
			
		#WordPress Default head hook
		wp_head(); ?>
</head>
<?php $body_class_arg  = ( mytheme_option("appearance","layout") === "boxed" ) ? array("boxed") : array(); ?>
<body <?php body_class( $body_class_arg ); ?>>
<?php if( mytheme_option("general","disable-picker") === NULL ) : mytheme_color_picker();  endif;?>
<!-- **Wrapper** -->
<div class="wrapper">

	<?php my_theme_front_bbar(); ?>
    
    <div id="header-wrapper">
	    <?php $header = mytheme_option("appearance","header_type");
			  $header = !empty($header) ? $header : "header1";	
			  require_once(TEMPLATEPATH."/framework/headers/{$header}.php");?>	    
    </div><!-- Header Wrapper -->
    
    <!-- **Main** -->
    <div id="main">
    <?php if( is_page() ):
			global $post;
			mytheme_slider_section( $post->ID);	
		  elseif( is_post_type_archive('product') ):
		  	mytheme_slider_section( get_option('woocommerce_shop_page_id') );	
    	  endif; ?>    
    
    <?php $disable_breadcrumb = mytheme_option('general','disable-breadcrumb');
		if( empty($disable_breadcrumb) and ( !is_front_page() ) ):
		if(!is_page_template('tpl-home.php')):
			echo '<!-- **Breadcrumb** -->';
			echo '<section class="breadcrumb-section">';
				new my_breadcrumb;
			echo '</section><!-- **Breadcrumb** -->';	
		endif;	
		endif;
		
		if( is_page_template('tpl-contact.php') ):
			global $post;
			$tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
			$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
			if(array_key_exists("full-width-section",$tpl_default_settings)):
				echo '<div class="fullwidth-map">';
				echo do_shortcode($tpl_default_settings['full-width-section']);
				echo '</div>';
			endif;
		endif;?>
        
        <!-- **Container** -->
        <div id="main-container" class="container">
