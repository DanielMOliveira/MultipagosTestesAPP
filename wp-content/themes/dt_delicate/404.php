<?php get_header();?>
<?php $page_layout =  mytheme_option('specialty','404-layout');
	  $show_sidebar = false;
	  $sidebar_class="";
	  
	 
	switch($page_layout):
		case 'full-width':
			$page_layout 	= 	"content-full-width";
		break;
		
		case 'with-left-sidebar':
			$page_layout 	=	"with-left-sidebar";
			$show_sidebar 	= 	true;
			$sidebar_class 	=	"left-sidebar";
		break;
		
		case 'with-right-sidebar':
			$show_sidebar 	= 	true;
		break;
		
		
	endswitch;?>
      <!-- **Primary Section** -->
      <section id="primary" class="<?php echo $page_layout;?>">
        <div class="error-info">
       		<?php echo stripcslashes(mytheme_option('specialty','404-message'));?>
            <?php get_search_form();?>
        </div>
      </section><!-- **Primary Section** -->
        
<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>
    
<?php get_footer();?>