<?php add_shortcode('map','my_map');
function my_map($attrs, $content=null, $shortcodename =""){
	extract(shortcode_atts(array('width'=>'','height'=>'400','zoom'=>'10','type'=>'ROADMAP'), $attrs));
		$map_id = 'gmap_id_'.rand();

		// Load google maps api 
		$out = '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>';
		
		$out .= '<script type = "text/javascript">';
		$out .= 'jQuery(document).ready(function(){';
		
		// Setup options
		$out .= 'var options'.$map_id.' = {';
		$out .= 'zoom: '.$zoom.',';
		$out .= 'controls: [],';
		$out .= 'mapTypeId: google.maps.MapTypeId.'.$type;
		$out .= '};';
		
		// Initialize map
		$out .= 'var map'.$map_id.' = new google.maps.Map(document.getElementById("'.$map_id.'"), options'.$map_id.');';
		
		if ( !preg_match_all( '/(.?)\[(marker)\b(.*?)(?:(\/))?\](?:(.+?)\[\/marker\])?(.?)/s', $content, $matches ) ) {
	
			// No markers, do nothing

		} else {
		
	
		
			for ($i = 0; $i < count( $matches[0] ); $i++ ) {
			
				$options = explode('"', $matches[0][$i]);
				
				$address = $options[1];
				$icon = isset($options[3]) ? $options[3] : '';
			
				$search_string = $matches[0][$i];
				$url_search = str_replace('[/marker]', '', $search_string);
				$info_content = substr($url_search, strpos($url_search, ']') + 1, strlen($url_search));
				$info_content = trim($info_content);
			
				// Setup a new Geocode for the current marker address
				$out .= 'var address'.$i.' = "";';
				$out .= 'var g'.$i.' = new google.maps.Geocoder();';
				$out .= 'g'.$i.'.geocode({ "address" : "'.$address.'" }, function (results, status) {';
					$out .= 'if (status == google.maps.GeocoderStatus.OK) {';
						$out .= 'address'.$i.' = results[0].geometry.location;';
						
						// Center map on last marker added
						$out .= 'map'.$map_id.'.setCenter(results[0].geometry.location);';
						
						// Setup Marker
						$out .= 'var marker'.$i.' = new google.maps.Marker({';
						$out .= 'position: address'.$i.','; 
						$out .= 'map: map'.$map_id.',';
						$out .= 'clickable: true,';
						$out .= 'icon: "'.$icon.'",';
						$out .= '});'; 
						
						// Setup info window for marker
						$out .= 'var infowindow'.$i.' = new google.maps.InfoWindow({ content: "'.$info_content.'" });';
						$out .= 'google.maps.event.addListener(marker'.$i.', "click", function() {';
						$out .= 'infowindow'.$i.'.open(map'.$map_id.', marker'.$i.');';
						$out .= '});';
						
					$out .= '}';
				$out .= '});';
				
			}
		}
		
		$out .= '});';
		$out .= '</script>';
		
		// Output our map container
		$out .= '<div id="'.$map_id.'" class = "mytheme-map" style = "width: '.$width.'px; height: '.$height.'px;"></div>';
		
		return $out;
	
}
?>