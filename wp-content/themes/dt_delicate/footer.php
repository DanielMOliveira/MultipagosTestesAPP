    	</div><!-- **Container - End** -->
    </div><!-- **Main - End** -->
    
    <?php $mytheme_options = get_option(IAMD_THEME_SETTINGS);
	$mytheme_general = $mytheme_options['general'];?>
    <!-- **Footer** -->
    <footer id="footer">
    <?php if(!empty($mytheme_general['show-footer'])): ?>
    		<div class="container"><?php show_footer_widgetarea($mytheme_general['footer-columns']);?></div>
    <?php endif; 
    	if( !empty($mytheme_general['show-copyrighttext']) ): ?>
			<div class="copyright">
            	<div class="container"><?php echo stripslashes($mytheme_general['copyright-text']);?></div>
            </div>
    <?php endif;?>        
    </footer><!-- **Footer - End** -->

</div><!-- **Wrapper - End** -->
<?php	if (is_singular() AND comments_open())
			wp_enqueue_script( 'comment-reply');

		if(mytheme_option('integration', 'enable-body-code') != '') 
			echo stripslashes(mytheme_option('integration', 'body-code'));
		wp_footer(); ?>
</body>
</html>