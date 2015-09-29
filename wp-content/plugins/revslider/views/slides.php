<?php

	$operations = new RevOperations();

	$sliderID = self::getGetVar("id");
	
	if(empty($sliderID))
		UniteFunctionsRev::throwError("Slider ID not found"); 
	
	$slider = new RevSlider();
	$slider->initByID($sliderID);
	$sliderParams = $slider->getParams();
	
	$arrSliders = $slider->getArrSlidersShort($sliderID);
	$selectSliders = UniteFunctionsRev::getHTMLSelect($arrSliders,"","id='selectSliders'",true);
	
	$numSliders = count($arrSliders);
	
	//set iframe parameters	
	$width = $sliderParams["width"];
	$height = $sliderParams["height"];
	
	$iframeWidth = $width+60;
	$iframeHeight = $height+50;
	
	$iframeStyle = "width:{$iframeWidth}px;height:{$iframeHeight}px;";

	//handle wpml
	$isWpmlExists = UniteWpmlRev::isWpmlExists();
	$useWpml = $slider->getParam("use_wpml","off");
	
	$langFilterValue = null;
	$wpmlActive = false;
	if($isWpmlExists && $useWpml == "on"){
		$wpmlActive = true;
		$arrLangs = UniteWpmlRev::getArrLanguages();		
		$langFilterValue = $operations->getLangFilterValue();		
		$selectLangsFilter = UniteFunctionsRev::getHTMLSelect($arrLangs,$langFilterValue,"id='select_lang_filter' ",true);
		$langFloatMenu = UniteWpmlRev::getLangsWithFlagsHtmlList("id='slides_langs_float' class='slides_langs_float'");
	}	
	
	$arrSlides = $slider->getSlides(false,$langFilterValue);
	
	$numSlides = count($arrSlides);
	
	$linksSliderSettings = self::getViewUrl(RevSliderAdmin::VIEW_SLIDER,"id=$sliderID");
	
	
	require self::getPathTemplate("slides");
	
?>
	