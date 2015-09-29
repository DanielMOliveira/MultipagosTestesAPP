<?php get_header();?>
<?php #Page Top Code Section
	$mytheme_options = get_option(IAMD_THEME_SETTINGS);
	$mytheme_integration = $mytheme_options['integration'];
	if(isset($mytheme_integration['enable-single-post-top-code']))	echo stripslashes($mytheme_integration['single-post-top-code']);?>        

       	<?php if( have_posts() ): ?>
       	<?php 	while ( have_posts() ) : the_post(); ?>
        <?php 		get_template_part( 'framework/loops/content', 'single' ); ?>
        <?php 	endwhile;
           	  endif;?>
<?php #Page Top Code Section
	$mytheme_integration = $mytheme_options['integration'];
	if(isset($mytheme_integration['enable-single-post-bottom-code']))	echo stripslashes($mytheme_integration['single-post-bottom-code']);?>        
<?php get_footer();?>