<?php

	class UniteWpmlRev{
		
		
		/**
		 * 
		 * true / false if the wpml plugin exists
		 */
		public static function isWpmlExists(){
			
			if(class_exists("SitePress"))
				return(true);
			else
				return(false);
		}
		
		/**
		 * 
		 * valdiate that wpml exists
		 */
		private static function validateWpmlExists(){
			if(!self::isWpmlExists())
				UniteFunctionsRev::throwError("The wpml plugin don't exists");
		}
		
		/**
		 * 
		 * get languages array
		 */
		public static function getArrLanguages(){
			
			self::validateWpmlExists();
			$wpml = new SitePress();
			$arrLangs = $wpml->get_active_languages();
			
			$response = array();
			$response["all"] = __("All Languages",REVSLIDER_TEXTDOMAIN);
			foreach($arrLangs as $code=>$arrLang){
				$name = $arrLang["native_name"];
				$response[$code] = $name;
			}
			
			return($response);
		}
		
		
		/**
		 * 
		 * get langs with flags menu list
		 * @param $props
		 */
		public static function getLangsWithFlagsHtmlList($props = ""){
			$arrLangs = self::getArrLanguages();
			if(!empty($props))
				$props = " ".$props;
			
			$html = "<ul{$props}>"."\n";
			foreach($arrLangs as $code=>$title){
				$urlIcon = self::getFlagUrl($code);
				
				$html .= "<li><a data-lang='{$code}' href='javascript:void(0)'>"."\n";
				$html .= "<img src='{$urlIcon}'/> $title"."\n";				
				$html .= "</a></li>"."\n";
			}
			
			$html .= "</ul>";
			
			return($html);
		}
		
		
		/**
		 * get flag url
		 */
		public static function getFlagUrl($code){
			
			self::validateWpmlExists();
			$wpml = new SitePress();
			
			if(empty($code) || $code == "all")
				$url = ICL_PLUGIN_URL . '/res/img/icon16.png';
			else
				$url = $wpml->get_flag_url($code);
			
			//default: show all
			if(empty($url))
				$url = ICL_PLUGIN_URL . '/res/img/icon16.png';
			
			return($url);
		}
		
		
		/**
		/* get language details by code
		 */
		private function getLangDetails($code){
	        global $wpdb;
			
	        $details = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}icl_languages WHERE code='$code'");
	        
	        if(!empty($details))
	        	$details = (array)$details;
	        
	        return($details);
		}
		
		
		/**
		 * 
		 * get language title by code
		 */
		public static function getLangTitle($code){
			
			$langs = self::getArrLanguages();
			
			if($code == "all")
				return(__("All Languages"));
			
			if(array_key_exists($code, $langs))
				return($langs[$code]);
				
			$details = self::getLangDetails($code);
			if(!empty($details))			
	        	return($details["english_name"]);
	       	
			return("");
		}
		
		
		/**
		 * 
		 * get current language
		 */
		public static function getCurrentLang(){
			self::validateWpmlExists();
			$wpml = new SitePress();
			
		}
		
	}
	
	
	