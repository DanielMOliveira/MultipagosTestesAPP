jQuery(document).ready(function($){

  "use strict";
  var $picker_container = jQuery("div.delicate-style-picker-wrapper"),
      $theme_url = mytheme_urls.theme_base_url,
      $fw_url = mytheme_urls.framework_base_url,
      $patterns_url = $fw_url+"theme_options/images/patterns/";
  
  //Applying Cookies
  //if($.cookie("mytheme_skin")!== null){
  if( typeof $.cookie("mytheme_skin") !== "undefined" ){
    var $href = $("link[id='skin-css']").attr("href");
    $href = $href.substr(0,$href.lastIndexOf("/"));
    $href = $href.substr(0,$href.lastIndexOf("/"))+"/"+$.cookie("mytheme_skin")+"/style.css";
    
    $("link[id='skin-css']").attr("href",$href);
    $("ul.color-picker a[id='"+$.cookie("mytheme_skin")+"']").addClass("selected");
  }else{
	$("ul.color-picker a:first").addClass("selected");
  }
  
  if ( $.cookie('control-open') === 1 ) {
    $picker_container.animate( { left: -230 } );
    $('a.style-picker-ico').addClass('control-open');
  }
  
  //1. Applying Layout & patterns
  if($.cookie("mytheme_layout") === "boxed"){
    $("ul.layout-picker li a").removeAttr("class");
    $("ul.layout-picker li a[id='"+$.cookie("mytheme_layout")+"']").addClass("selected");
    $("div#pattern-holder").removeAttr("style");
    
    var $i = ($.cookie("mytheme_pattern")) ? $.cookie("mytheme_pattern")  : 'pattern1';
    var $img = $patterns_url+$i+".jpg";
    
    $('body').css('background-image', 'url('+$img+')').addClass('boxed');
    $("ul.pattern-picker a[id="+$.cookie("mytheme_pattern")+"]").addClass('selected');
    
  }//Applying Cookies End
  
  //Picker On/Off
  $("a.style-picker-ico").click(function(e){
    var $this = $(this);	
    
    if($this.hasClass('control-open')){
      $picker_container.animate({left: 0},function(){$this.removeClass('control-open');});
      $.cookie('control-open', 0);	
    }else{
      $picker_container.animate({left: -230},function(){$this.addClass('control-open');});
      $.cookie('control-open', 1);
	}
	e.preventDefault();
   });//Picker On/Off end

  //Layout Picker
  $("ul.layout-picker a").click(function(e){
    var $this = $(this);
    $("ul.layout-picker a").removeAttr("class");
    $this.addClass("selected");
    $.cookie("mytheme_layout", $this.attr("id"), { path: '/' });

    if( $.cookie("mytheme_layout") === "boxed") {
      $("body").addClass("boxed");
      $("div#pattern-holder").slideDown();
			
      if( $.cookie("mytheme_pattern") === null ){
        $("ul.pattern-picker a:first").addClass('selected');
        $.cookie("mytheme_pattern","pattern1",{ path: '/' });
      }else{
        $("ul.pattern-picker a[id="+$.cookie("mytheme_pattern")+"]").addClass('selected');
      }
      $img = $patterns_url+$.cookie("mytheme_pattern")+".jpg";
      $('body').css('background-image', 'url('+$img+')');
    } else {
      $("body").removeAttr("style").removeClass("boxed");
      $("div#pattern-holder").slideUp();
      $("ul.pattern-picker a").removeAttr("class");
    }
    window.location.href = location.href;
    e.preventDefault();
  });//Layout Picker End

  //Pattern Picker
  $("ul.pattern-picker a").click(function(e){
    if($.cookie("mytheme_layout") === "boxed"){
      var $this = $(this);
      $("ul.pattern-picker a").removeAttr("class");
      $this.addClass("selected");
      $.cookie("mytheme_pattern", $this.attr("id"), { path: '/' });
      var $img = $patterns_url+$.cookie("mytheme_pattern")+".jpg";
      $('body').css('background-image', 'url('+$img+')');
    }
    e.preventDefault();
  });//Pattern Picker End

  //Color Picker
  $("ul.color-picker a").click(function(e){
    var $this = $(this);
    $("ul.color-picker a").removeAttr("class");
    $this.addClass("selected");
    $.cookie("mytheme_skin", $this.attr("id"), { path: '/' });
    $href = $("link[id='skin-css']").attr("href");
    $href = $href.substr(0,$href.lastIndexOf("/"));
    $href = $href.substr(0,$href.lastIndexOf("/"))+"/"+$this.attr("id")+"/style.css";
    $("link[id='skin-css']").attr("href",$href);
    e.preventDefault();
  });//Color Picker End
});