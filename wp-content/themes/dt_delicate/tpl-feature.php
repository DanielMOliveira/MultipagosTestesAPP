<?php /*Template Name: Feature Template*/?>
<?php get_header();?>
<?php $tpl_default_settings = get_post_meta($post->ID,'_tpl_default_settings',TRUE);
	  $tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
	  
	  $page_layout  = array_key_exists("layout",$tpl_default_settings) ? $tpl_default_settings['layout'] : "content-full-width";
  	  $show_sidebar = false;
	  $sidebar_class= "";
	  
	  switch($page_layout):
		case 'with-left-sidebar':
			$page_layout 	=	"with-left-sidebar";
			$show_sidebar 	= 	true;
			$sidebar_class 	=	"left-sidebar";
		break;
		
		case 'with-right-sidebar':
			$show_sidebar 	= 	true;
		break;
	  endswitch; ?>
      <!-- **Primary Section** -->
      <section id="primary" class="<?php echo $page_layout;?>">
      	<!-- **Side Navigation** -->
        <div class="column one-third">
	        <div class="side-nav-container">
	            <ul class="side-nav">
                <?php	if( $post->post_parent ):
							$args = array('child_of' => $post->post_parent,'title_li' => '','sort_order'=> 'ASC','sort_column'	=> 'menu_order');
						else:
							$args = array('child_of' => $post->ID,'title_li' => '','sort_order'=> 'ASC','sort_column'	=> 'menu_order');
						endif;
						
						$pages = get_pages( $args );
						$ids = array();
						$page_id = $post->ID;
						foreach($pages as $value){
							$ids[] = $value->ID;
						}
						
						foreach( $ids as $id ) {
							$title = get_the_title($id);
							$permalink = get_permalink( $id );
							
							$tpl_default_settings = get_post_meta($id,'_tpl_default_settings',TRUE);
							$tpl_default_settings = is_array($tpl_default_settings) ? $tpl_default_settings  : array();
							
							$class = array_key_exists("menu-icon-class",$tpl_default_settings) ? $tpl_default_settings['menu-icon-class'] : "icon-bullseye";
							
							$current = ( $id ===  $page_id) ? "current_page_item" : "";
							
							echo "<li class='{$current}'>";
							echo "<a href='{$permalink}' title='{$title}'  >$title <span class='{$class}'></span></a>";
							echo "</li>";
						}?></ul>
            </div>    
        </div><!-- **Side Navigation End** -->

        <!-- **Main Content** -->
        <div class="column two-third last">               
<?php		if( have_posts() ):
				while( have_posts() ):
					the_post();
					get_template_part( 'framework/loops/content', 'page' );
				endwhile;
			endif;?>     
        </div><!-- **Main Content End** -->    
        
      </section><!-- **Primary Section** -->
        
<?php if($show_sidebar): ?>
	  <!-- **Secondary Section ** -->
      <section id="secondary" class="<?php echo $sidebar_class; ?>">
<?php 	get_sidebar();?>      
      </section><!-- **Secondary Section - End** -->
<?php endif; ?>
<?php get_footer();?>