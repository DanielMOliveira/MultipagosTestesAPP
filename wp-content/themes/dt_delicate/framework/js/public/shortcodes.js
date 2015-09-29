jQuery.noConflict();
jQuery(document).ready(function() {
  
  // Tabs Shortcodes
  "use strict";
  if(jQuery('ul.tabs').length > 0) {
    jQuery('ul.tabs').tabs('> .tabs-content');
  }
  
  if(jQuery('ul.tabs-frame').length > 0){
    jQuery('ul.tabs-frame').tabs('> .tabs-frame-content');
  }
  
  if(jQuery('.tabs-vertical-frame').length > 0){
    
    jQuery('.tabs-vertical-frame').tabs('> .tabs-vertical-frame-content');
    
    jQuery('.tabs-vertical-frame').each(function(){
      jQuery(this).find("li:first").addClass('first').addClass('current');
      jQuery(this).find("li:last").addClass('last');
    });
    
    jQuery('.tabs-vertical-frame li').click(function(){
      jQuery(this).parent().children().removeClass('current');
      jQuery(this).addClass('current');
    });
    
  }/*Tabs Shortcode Ends*/
  
  /*Toggle shortcode*/
  jQuery('.toggle').toggle(function(){ jQuery(this).addClass('active'); },function(){ jQuery(this).removeClass('active'); });
  jQuery('.toggle').click(function(){ jQuery(this).next('.toggle-content').slideToggle(); });
  jQuery('.toggle-frame-set').each(function(){
    var $this = jQuery(this),
        $toggle = $this.find('.toggle-accordion');
    
    $toggle.click(function(){
      if( jQuery(this).next().is(':hidden') ) {
        $this.find('.toggle-accordion').removeClass('active').next().slideUp();
        jQuery(this).toggleClass('active').next().slideDown();
      }
      return false;
    });
    
    //Activate First Item always
    $this.find('.toggle-accordion:first').addClass("active");
    $this.find('.toggle-accordion:first').next().slideDown();
  });/* Toggle Shortcode end*/
  
  /*Tooltip*/
  if(jQuery(".tooltip-bottom").length){
    jQuery(".tooltip-bottom").each(function(){	jQuery(this).tipTip({maxWidth: "auto"}); });
  }
  
  if(jQuery(".tooltip-top").length){
    jQuery(".tooltip-top").each(function(){ jQuery(this).tipTip({maxWidth: "auto",defaultPosition: "top"}); });
  }
  
  if(jQuery(".tooltip-left").length){
    jQuery(".tooltip-left").each(function(){ jQuery(this).tipTip({maxWidth: "auto",defaultPosition: "left"}); });
  }
  
  if(jQuery(".tooltip-right").length){
    jQuery(".tooltip-right").each(function(){ jQuery(this).tipTip({maxWidth: "auto",defaultPosition: "right"}); });
  }/*Tooltip End*/
  
  //Scroll to top
  jQuery("a.scrollTop").each(function(){
    jQuery(this).click(function(e){
      jQuery("html, body").animate({ scrollTop: 0 }, 600);
      e.preventDefault();
    });
  });//Scroll to top end
  
  //Skillset
  animateSkillBars();
  jQuery(window).scroll(function(){ animateSkillBars(); });
  function animateSkillBars(){
    var applyViewPort = ( jQuery("html").hasClass('csstransforms') ) ? ":in-viewport" : "";
    jQuery('.progress'+applyViewPort).each(function(){
      var progressBar = jQuery(this),
          progressValue = progressBar.find('.bar').attr('data-value');
      
      if (!progressBar.hasClass('animated')) {
        progressBar.addClass('animated');
        progressBar.find('.bar').animate({width: progressValue + "%"},600,function(){ progressBar.find('.bar-text').fadeIn(400); });
      }
    });
  }
  
  /*Partner Carousel*/
  if(jQuery(".partner-carousel-wrapper").length) {
    jQuery(".partner-carousel-wrapper ul.partner-carousel").each(function(){
      var $item = jQuery(this),
          $max  = $item.data("max"),
          $min  = $item.data("min"),
          $width = $item.data("item-width"),
          $scroll = $item.data("scroll"),
          $prev = $item.next('div.carousel-arrows').find('a.partner-prev-arrow'),
          $next = $item.next('div.carousel-arrows').find('a.partner-next-arrow');
      
      $item.carouFredSel({
        responsive: true,
        auto: false,
        width: '100%',
        prev: $prev,
        next: $next,
        height: 'auto',
        scroll: $scroll,
        items: { width: $width,visible: { min: $min, max:$max}  }
      });
    });
  }
});