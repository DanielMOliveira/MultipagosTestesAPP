jQuery(document).ready(function(){
  "use strict";
  //IE - remove height & width 
  if( !jQuery("html").hasClass('csstransforms') ) {
	  jQuery("body").find('img').each(function(){
		  jQuery(this).removeAttr('width');
		  jQuery(this).removeAttr('height');
	  });
  }
  
  if( mytheme_urls.scroll === "enable") {
	  jQuery("html").niceScroll({zindex:99999,cursorborder:"1px solid #424242"});
  }
  
  jQuery().UItoTop({ easingType: 'easeOutQuart' });
  
  jQuery("#main-menu ul:first li").hover(function(){
    jQuery(this).find('ul:first').stop().fadeIn('slow');
  },function(){
    jQuery(this).find('ul:first').stop().fadeOut('fast');
  });
  
  if( mytheme_urls.stickynav === "enable") {
  	jQuery("#header-wrapper").sticky({ topSpacing: 0 });
  }

  /* Mibile Nav */	
  jQuery('#main-menu > ul').mobileMenu({
    defaultText: 'Navigate to...',
    className: 'mobile-menu',
    subMenuDash: '&ndash;&nbsp;'
  });
  
  function applyIso(){
    jQuery("div.portfolio-container").css({overflow:'hidden'}).isotope({itemSelector : '.isotope-item'});
  }

  
  /*Portfolio Isotope*/
  var $container = jQuery('.portfolio-container');
  if($container.length){
    
    $container.isotope({
      filter: '*',
      animationOptions: { duration: 750, easing: 'linear', queue: false  }
    });
    
    if(jQuery("div.sorting-container").length){
      jQuery("div.sorting-container a").click(function(){
        jQuery("div.sorting-container a").removeClass("active-sort");
        var selector = jQuery(this).attr('data-filter');
        jQuery(this).addClass("active-sort");
        $container.isotope({ filter: selector, animationOptions: { duration: 750, easing: 'linear',  queue: false }});
        return false;
      });		
      
    }
    
    jQuery(window).smartresize(function(){ 
      applyIso();
    });	
  }/*Portfolio Isotope End*/
  
  jQuery("a[data-gal^='prettyPhoto']").prettyPhoto({
    animation_speed:'normal',
    theme:'light_square',
    slideshow:3000,
    autoplay_slideshow: false,
    social_tools: false,
    deeplinking:false
  });
  
  //Portfolio
  if( (jQuery(".portfolio-slider").length) && (jQuery(".portfolio-slider li").length > 1) ) {
   jQuery('.portfolio-slider').bxSlider({ auto:false, video:true, useCSS:false, pager:'', autoHover:true, adaptiveHeight:true });
  }

  /*Portfolio Carousel*/
  if(jQuery(".portfolio-carousel-wrapper").length) {
    jQuery('.portfolio-carousel').carouFredSel({
      responsive: true,
      auto: false,
      width: '100%',
      prev: '.portfolio-prev-arrow',
      next: '.portfolio-next-arrow',
      height: 'auto',
      scroll: 1,				
      items: { width: 340, visible: { min: 1, max: 3 } }				
    });			
  }
  
  /*Sending Mail*/
  if( jQuery(".contact-form").length ) {
    jQuery(".contact-form").each(function(){
      jQuery(this).submit(function(e){
        var $form = jQuery(this),
            $msg = jQuery(this).prev('div.message'),
            $action = $form.attr('action');
        
        jQuery.post($action,$form.serialize(),function(data){
          $form.fadeOut("fast", function(){$msg.hide().html(data).show('fast');});
        });
        e.preventDefault();
      });
    });
  }

  //Buddha Bar	
  jQuery("div#bbar-open").click(function(e){
    jQuery(this).hide();	
    jQuery("div#bbar-body").slideDown('slow',function(){jQuery("div#bbar-close").show();});
    e.preventDefault();
  });

  jQuery("div#bbar-close").click(function(e){
    jQuery("div#bbar-close").hide();
    jQuery("div#bbar-body").slideUp('slow');
    jQuery("div#bbar-open").slideDown();
    e.preventDefault();
  });//Buddha Bar End
  
  //Woo
  jQuery(".quantity input[type=number]").each(function(){
	var number = jQuery(this),
	newNum = jQuery(jQuery('<div />').append(number.clone(true)).html().replace('number','text')).insertAfter(number);
	number.remove();
  });//Woo
   
});