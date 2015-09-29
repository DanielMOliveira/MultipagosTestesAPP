<?php function enable_more_buttons($buttons) {
		$buttons[] = 'fontselect';		$buttons[] = 'fontsizeselect'; 		$buttons[] = 'backcolor'; 		$buttons[] = 'image';		$buttons[] = 'media'; 
		$buttons[] = 'anchor'; 			$buttons[] = 'sub'; 				$buttons[] = 'sup';				$buttons[] = 'hr';			$buttons[] = 'wp_page';
		$buttons[] = 'cut'; 			$buttons[] = 'copy'; 				$buttons[] = 'paste';			$buttons[] = 'newdocument'; $buttons[] = 'code';
		$buttons[] = 'cleanup'; 
 return $buttons;
}
#add_filter("mce_buttons_3", "enable_more_buttons");

add_action('admin_init','my_add_editor_buttons');
function my_add_editor_buttons(){
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) && get_user_option('rich_editing') == 'true' ) :
			#add_filter('mce_buttons_4', 'filter_mce_buttons');
			add_filter('mce_buttons_3', 'filter_mce_buttons');
			add_filter('mce_external_plugins', 'filter_mce_external_plugins');
			add_action('edit_form_advanced', 'advanced_buttons');
			add_action('edit_page_form', 'advanced_buttons');
	endif;
}

function filter_mce_buttons( $buttons ) {
	array_push($buttons,"mytheme_mce_columns","separator");
	array_push($buttons,"mytheme_twitter_widget","mytheme_portfolio_widget"/*,"mytheme_post_widget"*/,"mytheme_page_widget","separator");
	array_push($buttons,"mytheme_fancy_ul","mytheme_fancy_ol","separator");
	array_push($buttons,"mytheme_fancy_buttons","mytheme_box","separator");
	array_push($buttons,"mytheme_pullquote","mytheme_blockquote","separator");
	array_push($buttons,"mytheme_dropcap","mytheme_highlight","mytheme_map","mytheme_tooltip","separator");
	array_push($buttons,"mytheme_tab","mytheme_toggle","separator");
	array_push($buttons,"mytheme_hr","separator");
	array_push($buttons,"mytheme_team","mytheme_testimonial","mytheme_pricing_table");
	array_push($buttons,"mytheme_social_twitter","mytheme_social_googleplus","mytheme_social_fb","mytheme_social_digg","mytheme_social_stumbleupon"
	,"mytheme_social_linkedin","mytheme_social_pintrest","mytheme_social_delicious");
return $buttons;
}

function filter_mce_external_plugins( $plugins ) {
	$plugins['mytheme_quicktags'] = IAMD_FW_URL.'/theme_editor/js/editor_plugin.js';
return $plugins;
}

function advanced_buttons() {
	echo "\n"; ?>
    <style type="text/css">
		#TB_ajaxContent { height:531px !important;}
		#rte {	width:400px; min-height:200px; float:left;	border: 1px solid #c0c0c0;	}
		#rte ul { list-style:disc inside none; float:left; margin-left:10px; }
		#rte ol { list-style:decimal inside none; float:left; margin-left:10px; }
		.option { float:left; width:100%; }
		/*Color Picker*/
		#TB_ajaxContent .color_picker_element { width:75px; margin-right:4px;}
		.mytheme-add-fancy-buttons #color_picker-bgcolor { top:213px !important; }
		.mytheme-add-fancy-buttons #color_picker-textcolor{ top:310px !important;}
		.mytheme-add-highlight #color_picker-bgcolor { top:352px !important; }
		.mytheme-add-highlight #color_picker-textcolor{ top:380px !important;}
		.mytheme-add-box #color_picker-bgcolor , .mytheme-add-pullquote #color_picker-textcolor { top:360px !important; }
		.mytheme-add-box #color_picker-textcolor{ top:380px !important;}
		
		.clone { clear:both; float:left;}
		.clone input, .clone textarea { margin-right:24px; width:90%;}
	</style>
	<script type="text/javascript">
	
		//Cloning function
		function cloneMe(arg){
			$container = jQuery(arg).parents('td');
			$clone = jQuery(arg).parent().clone();
			$container.append($clone);
			return false;
		}
		function RemoveMe(arg){
			$class = jQuery(arg).parent().attr('class');
			if(jQuery("."+$class).length > 1) jQuery(arg).parent().remove();
			return false;
		}
		//Cloning Function End
	
		//Color Picker
		function myColorPicker(textFieldId,ColorPickerId){
			
			if(parseFloat(mysiteWpVersion) >= parseFloat("3.5")){
			} else {
				jQuery.farbtastic(jQuery(ColorPickerId), function(a) { jQuery(textFieldId).val(a).css('background', a); });
				jQuery(ColorPickerId).show();
				jQuery(document).mousedown( function() { jQuery(ColorPickerId).hide(); });
			}
		}
		
		
		
		function showColorPicker(arg){
			id = jQuery(arg).attr("id");
			txtid = id.split("-");
			colorpickerid = "color_picker-"+txtid[1];
			txtid = "mytheme-"+txtid[1];
			myColorPicker("#"+txtid,"#"+colorpickerid);
		return false;	
		}
		//Color Picker End
	
		<?php $categories = "var categories = {";
			  $cats = get_categories( 'orderby=name&hide_empty=0' );
			  foreach ($cats as $cat):
				$categories .=  esc_attr($cat->term_id).':"'.esc_html($cat->name).'"';
				#if (next($cats)==true) $categories .= ","; 
				if(end($cats) != $cat) $categories .=",";
			  endforeach;
			  $categories .= "};";
			  echo $categories."\n";
			  
			  $pages = "\t\tvar pages = {";
			  $pages_list = get_pages('title_li=&orderby=name');
			  foreach($pages_list as $page):
			  	$pages.= esc_attr($page->ID).':"'.esc_html($page->post_title).'"';
				if(end($pages_list) != $page) $pages .=",";
			  endforeach;
			  $pages .= "};";
			  echo $pages."\n";
			  
			  $portfolio_categories = "\t\tvar portfolio = { ";
			  $cats = get_categories('taxonomy=portfolio_entries&hide_empty=1');
			  foreach ($cats as $cat):
				$portfolio_categories .=  esc_attr($cat->term_id).':"'.esc_html($cat->name).'"';
				if(end($cats) != $cat) $portfolio_categories .=",";
			  endforeach;
			  $portfolio_categories .= "};";
			  echo $portfolio_categories."\n";

			  $variations = "\tvar color_variations = {";
			  global $mytheme_color_variations;
			  foreach($mytheme_color_variations as $variation):
			  	$id =  strtolower(str_replace(' ', '-', $variation));
				$variations .= "'{$id}':'{$variation}'";
					if(end($mytheme_color_variations) != $variation) $variations .=",";
			  endforeach;
			  $variations .= "};";
			  echo $variations."\n";?>
	
		//My Text Editor - used in  Fancy UL/OL Shortcode
		function mythemeTextEditor(command){
			switch(command){
				case 'ul':
					document.execCommand('insertunorderedlist', false, null);jQuery('#rte').focus();
				break;

				case 'ol':
					document.execCommand('insertorderedlist', false, null);jQuery('#rte').focus();
				break;
			}
		}
		
		//TextEditor - used in  Fancy UL/OL Shortcode
		function rtefocus(){
			a = jQuery("div#rte").prevAll("a.my-active").attr("id");
			mythemeTextEditor(a);
		}
		
		//TextEditor-Buttons Clicked - used in  Fancy UL/OL Shortcode
		function doMe(arg){
			jQuery('#rte').focus();
			arg = jQuery(arg).attr("id");
			mythemeTextEditor(arg);
			return false;
		}
		
		var defaultSettings = {}
			output 			= '';
		
		//Hr Rule
		defaultSettings['add-hr-rule'] = {
			type: {
				name:			'Chooose Divider',
				defaultValue:	'hr',
				description:	'Choose which type of divider you would like to use.',
				type:			'select_type2',
				options:		{'clear':'Clear','hr':'Horizontal Rule','hr-invisible':'Whitespace','hr-invisible-small':'Small Whitespace','hr-border':'Dashed Line'}

			},
			top:{
				name:			'Horizontal Rule with top link',
				defaultValue:	'no',
				description:	'Would you like to have top link in the Horizontal Rulw',
				type:			'select',
				options:		'yes|no' 
			},
			class:{
				name:			'Add extra Class',
				defaultValue: 	'',
				description: 	'You can add any number of css class here',
				type: 			'text'
			}
		}
		
		//Twitter input start
		defaultSettings['add-twitter'] = {
			username :{
				name: 			'Twitter username',
				defaultValue: 	'',
				description: 	'Enter your twitter username',
				type: 			'text'
			}
			,count:{
				name:			'Tweets count',
				defaultValue:	'3',
				description: 	'How many entries do you want to show?',
				type: 			'select',
				options: 		'1|2|3|4|5|6|7|8|9|10|11|12|13|14|15|16|17|18|19|20' 
			},

			consumer_key :{
				name: 			'Consumer Key',
				defaultValue: 	'',
				description: 	'',
				type: 			'text'
			},

			consumer_secret :{
				name: 			'Consumer Secret',
				defaultValue: 	'',
				description: 	'',
				type: 			'text'
			},

			access_token :{
				name: 			'Access Token',
				defaultValue: 	'',
				description: 	'',
				type: 			'text'
			},

			access_token_secret :{
				name: 			'Access Token Secret ',
				defaultValue: 	'',
				description: 	'',
				type: 			'text'
			}
			
			
			
			
			,exclude_replies:{
				name:			'Exclude @replies',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
				
			}
			,time:{
				name:			'Show time of tweet',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
			}
			,display_avatar:{
				name:			'Show user avatar',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
			}
		}//twitter Iput end
		
		//Column Layout input start
		defaultSettings['add-columns'] = {

			class : {
				name: 			'Custom Class',
				defaultValue: 	'',
				description: 	'Enter your custom class( eg : no-space)',
				type: 			'text'
			},
			
			layout_type :{ 
				name:	'Choose Layout Type',
				defaultValue:	'2',
				description:	'Choose which type of layout you would like to use.',
				type:			'select_type2',
				options:	{
						'1' :  '2 - Columns',		'2' : '3 - Columns',					'3' : '1/3 - 2/3',	
						'4' :  '4 - Columns',		'5' : '1/4 - 3/4',						'6' : '1/4 - 1/4 - 1/2',
						'7' :  '5 - Columns',		'8' : '1/5 - 1/5 - 1/5 - 2/5',			'9' : '1/5 - 1/5 - 3/5',
						'10':  '1/5 - 4/5',			'11': '1/5 - 2/5 - 2/5',				'12': '2/5 - 3/5', 
						'13':  '6 - Columns',		'14': '1/6 - 1/6 - 1/6 - 1/6 - 2/6 ',	'15': '1/6 - 1/6 - 1/6 - 3/6',
						'16':  '1/6 - 1/6 - 4/6',	'17': '1/6 - 5/6',						'18': '1/6 - 2/6 - 3/6',
						'19':  '3/6 - 3/6',			'20': '2/6 - 4/6'		
				}	
			}

			
		}//Column Layout input end
		

		//Portfolio Widget options start
		defaultSettings['add-portfolio-widget'] = {
			_post_count:{
				name: 			'Post Count',
				defaultValue: 	'-1',
				description: 	'No.of posts to show',
				type: 			'text'
			},
			
			_post_categories:{
				name: 			'Post Categories',
				defaultValue: 	'',
				description: 	'Choose the categories you want to display (multiple selection possible)',
				type: 			'multiple_select',
				options:	    portfolio
				
			},
			_excerpt:{
				name:			'Show Post Title / Title&amp; Excerpt',
				defaultValue:	'show title only',
				description:	'Display title only or title & excerpt',
				type:			'select',
				options:		'show title only|show title and excerpt' 
			},
			_enabled_image:{
				name:			'Show Post Featured Image',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
			}
		}//Portfolio Widget options end
		
		//Post Widget options start
		defaultSettings['add-post-widget'] = {
			_post_count:{
				name: 			'Post Count',
				defaultValue: 	'-1',
				description: 	'No.of posts to show',
				type: 			'text'
			},
			
			_post_categories:{
				name: 			'Post Categories',
				defaultValue: 	'',
				description: 	'Choose the categories you want to display (multiple selection possible)',
				type: 			'multiple_select',
				options:	    categories
				
			},
			_excerpt:{
				name:			'Show Post Title / Title&amp; Excerpt',
				defaultValue:	'show title only',
				description:	'Display title only or title & excerpt',
				type:			'select',
				options:		'show title only|show title and excerpt' 
			},
			_enabled_image:{
				name:			'Show Post Featured Image',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
			}
		}//Post Widget options end

		//Page Widget options start
		defaultSettings['add-page-widget'] = {
			
			_pages:{
				name: 			'Choose pages',
				defaultValue: 	'',
				description: 	'Choose the pages you want to display (multiple selection possible)',
				type: 			'multiple_select',
				options:	    pages
				
			},
			_excerpt:{
				name:			'Show Post Title / Title&amp; Excerpt',
				defaultValue:	'show title only',
				description:	'Display title only or title & excerpt',
				type:			'select',
				options:		'show title only|show title and excerpt' 
			},
			_enabled_image:{
				name:			'Show Post Featured Image',
				defaultValue:	'yes',
				description:	'',
				type:			'select',
				options:		'yes|no' 
			}
		}//Page Widget options end
		
		//Fancy Unordered List starts
		defaultSettings['add-fancy-ul'] = {
			style: {
				name:			'Style',
				defaultValue:	'arrow',
				description:	'Choose the style of list that you wish to use. Each one has a different icon.',
				type:			'select_type2',
				options: 		{'arrow':'Arrow','rounded-arrow':'Rounded Arrow','double-arrow':'Double Arrow','heart':'Heart','trash':'Trash','star':'Star','tick':'Tick','rounded-tick':'Rounded Tick','cross':'Cross','rounded-cross':'Rounded Cross','rounded-question':'Rounded Question','rounded-info':'Rounded Info','delete':'Delete','warning':'Warning','comment':'Comment','edit':'Edit','share':'Share','plus':'Plus','rounded-plus':'Rounded Plus','minus':'Minus','rounded-minus':'Rounded Minus','asterisk':'Asterisk','cart':'Cart','folder':'Folder','folder-open':'Opened Folder','desktop':'Desktop','tablet':'Tablet','mobile':'Mobile','reply':'Reply','quote':'Quote','mail':'Mail','external-link':'External Link','adjust':'Adjust','pencil':'pencil','print':'Print','tag':'Tag','thumbs-up':'Thumbs up','thumbs-down':'Thumbs down','time':'Time','globe':'Globe','pushpin':'Pushpin','map-marker':'Map Marker','link':'Link','paper-clip':'Paper Clip','download':'Download','key':'Key','search':'Search','rss':'RSS','twitter':'Twitter','facebook':'Facebook','linkedin':'Linkedin','google-plus':'Google Plus'}				 
			},
			variation:{
				name:			'Color Variation',
				defaultValue:	'',
				description:	'Choose one of our predefined color skins to use with your list.',
				type:			'select_type2',
				options:		color_variations		
			},
			class:{
				name:			'Add extra Class',
				defaultValue: 	'',
				description: 	'You can add any number of css class here',
				type: 			'text'
			},
			content:{
				name:			'Content',
				defaultValue:	'',
				description:	'Type out the content of your list.',
				type:			'my_editor',
				options:		'ul'
				
			}
		}//Fancy Unordered List end

		//Fancy Ordered List starts
		defaultSettings['add-fancy-ol'] = {

			style: {
				name:			'Style',
				defaultValue:	'decimal',
				description:	'Choose the style of list that you wish to use. Each one has a different icon.',
				type:			'select_type2',
				options: 		{'decimal':'Decimal','decimal-leading-zero':'Decimal Leading Zero','lower-alpha':'Lower Alpha','lower-roman':'Lower Roman',
				                'upper-alpha':'Upper Alpha','upper-roman':'Upper Roman'}				 
			},
			
			variation:{
				name:			'Color Variation',
				defaultValue:	'',
				description:	'Choose one of our predefined color skins to use with your list.',
				type:			'select_type2',
				options:		color_variations
			},
			class:{
				name:			'Add extra Class',
				defaultValue: 	'',
				description: 	'You can add any number of css class here',
				type: 			'text'
			},
			content:{
				name:			'Content',
				defaultValue:	'',
				description:	'Type out the content of your list.',
				type:			'my_editor',
				options:		'ol'
				
			}
		}//Fancy Ordered List end
	
		//Fancy Buttons starts
		defaultSettings['add-fancy-buttons'] ={
			content:{
				name:			'Button Text',
				defaultValue:	'Button',
				description:	'This is the text that will appear on your button.',
				type:			'text'
			},
			link:{
				name:			'Link Url',
				defaultValue:	'#',
				description:	'Paste a URL here to use as a link for your button.',
				type:			'text'
			},
			size:{
				name:			'Size',
				defaultValue:	'small',
				description:	'You can choose between three sizes for your button.',
				type:			'select_type2',
				options:		{'small':'Small','medium':'Medium','large':'Large','xlarge':'XLarge'}
			},
			
			bgcolor:{
				name:			'Custom BG Color',
				defaultValue:	'',
				description:	'Or you can also choose your own color to use as the background for your button',
				type:			'color_picker'
			},

			variation:{
				name:			'Color Variation',
				defaultValue:	'black',
				description:	'Choose one of our predefined color skins to use with your list.',
				type:			'select_type2',
				options:		color_variations
			},
			
			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your button.',
				type:			'color_picker'
			},
			align:{
				name:			'Align',
				defaultValue:	'',
				description:	'Set the alignment for your button here.<br/>Your button will float along the center, left or right hand sides depending on your choice.',
				type:			'select',
				options:		'|center|left|right'
			},
			target:{
				name:			'Target',
				defaultValue:	'',
				description:	"Setting the target to 'Blank' will open your page in a new tab when the reader clicks on the button.",
				type:			'select_type2',
				options:		{'_blank':'Blank' ,'_new':'New','_parent':'Parent','_self':'Self','_top':'Top'}
			}
		}
		//Fancy Buttons ends
		
		//Highlighter
		defaultSettings['add-highlight']={
			
			type :{
				name:			'Highlight Type',
				defaultValue:	'',
				description:	'Choose which type of highlight you wish to use.',
				type:			'select_type2',
				options:		{'':'Default','underlined':'Underlined','italic':'Italic','underlined-italic':'Underlined Italic'}
			},	

			content:{
				name:			'Highlight Text',
				defaultValue:	'',
				description:	'Type out the text that you wish to display with your highlight.',
				type:			'textarea'
			},

			variation:{
				name:			'Color Variation',
				defaultValue:	'yellow',
				description:	'Choose one of our predefined color skins to use with your box.',
				type:			'select_type2',
				options:		color_variations
			},

			
			bgcolor:{
				name:			'Custom BG Color',
				defaultValue:	'',
				description:	'Or you can also choose your own color to use as the background for your highlight',
				type:			'color_picker'
			},

			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your highlight.',
				type:			'color_picker'
			}			
		}//Highlighter
		
		//Box
		defaultSettings['add-box']={
			type :{
				name:			'Box Type',
				defaultValue:	'titled-box',
				description:	'Select which type of box you would like to use.',
				type:			'select_type2',
				options:		{'titled-box':'Titled Box','error-box':'Error Box','warning-box':'Warning Box','success-box':'Success Box', 'info-box':'Info Box'}
			},	
			title:{
				name:			'Box Title',
				defaultValue:	'',
				description:	'Type out the title to use with your titled box. The title will display above the content. (* Applicable for titled box only )',
				type:			'text'
			},
			content:{
				name:			'Box Content',
				defaultValue:	'',
				description:	'Type out the content that you wish to display inside your box.',
				type:			'textarea'
			},
			bgcolor:{
				name:			'Custom BG Color',
				defaultValue:	'',
				description:	'Or you can also choose your own color to use as the background for your box ( * Applicable for titled box only ) ',
				type:			'color_picker'
			},
			variation:{
				name:			'Color Variation',
				defaultValue:	'',
				description:	'Choose one of our predefined color skins to use with your box. ( * Applicable for titled box only )',
				type:			'select_type2',
				options:		color_variations
			},
			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your box. ( * Applicable for titled box only )',
				type:			'color_picker'
			}
		}//Box end
		
		//Drop Cap
		defaultSettings['add-dropcap']={
			type :{
				name:			'Drop Cap Type',
				defaultValue:	'dropcap-default',
				description:	'Select which type of box you would like to use.',
				type:			'select_type2',
				options:		{'dropcap-default':'Drop Cap','dropcap-circle':'Drop Cap Circle','dropcap-bordered-circle':'Drop Cap Bordered Circle'
								,'dropcap-square':'Drop Cap Square','dropcap-bordered-square':'Drop Cap Bordered Square'}
			},	
			content:{
				name:			'Drop Cap Text',
				defaultValue:	'',
				description:	'Type out the title to use with your titled box. The title will display above the content.',
				type:			'text'
			},
			bgcolor:{
				name:			'Custom BG Color',
				defaultValue:	'',
				description:	'Or you can also choose your own color to use as the background for your box',
				type:			'color_picker'
			},
			variation:{
				name:			'Color Variation',
				defaultValue:	'',
				description:	'Choose one of our predefined color skins to use with your box.',
				type:			'select_type2',
				options:		color_variations
			},
			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your box.',
				type:			'color_picker'
			}
		}//Dropcap End

		//Google Map
		defaultSettings['add-map'] = {
			type :{
				name:			'Map Type',
				defaultValue:	'ROADMAP',
				description:	'Select which type of map you would like to use.',
				type:			'select_type2',
				options:		{'ROADMAP':'Roadmap','SATELLITE':'Satellite','HYBRID':'Hybrid','TERRAIN':'Terrain'}
			},
			width:{
				name:			'Width',
				defaultValue:	'',
				description:	'Type out the width of your map.',
				type:			'text'
			},
			height:{
				name:			'Height',
				defaultValue:	'',
				description:	'Type out the height of your map.',
				type:			'text'
			},
			zoom:{
				name:			'Zoom',
				defaultValue:	'4',
				description:	'Select an initial zoom value for your map.',
				type:			'select',
				options:		'1|2|3|4|5|6|7|8|9|10|11|12|13|14|15'				
			},
			markers :{
				name: 			'Markers (Add markers you wish to display on your map.)',
				description:	'',
				type:			'clone',
				clone:			'<span class="clone">'
									+'Address : <input class="address" type="text" name="markers[]" />'
									+'Description : <input class="desc" type="text" name="markers_desc[]" />'
									+'<a href="#" onclick="cloneMe(this);">Add</a>|<a href="#" onclick="RemoveMe(this);">Remove</a>'
								+'</span>'
			}
		}//Google Map End
		
		//PullQuote
		defaultSettings['add-pullquote'] = {
			type :{
				name:			'Type',
				defaultValue:	'pullquote1',
				description:	'Choose which type of quote you wish to use.',
				type:			'select_type2',
				options:		{'pullquote1':'Pullquote 1','pullquote2':'Pullquote 2','pullquote3':'Pullquote 3','pullquote4':'Pullquote 4','pullquote5':'Pullquote 5'
								,'pullquote6':'Pullquote 6'}
			},
			content:{
				name:			'Pullquote Content',
				defaultValue:	'',
				description:	'Type out the text that you wish to display with your quote.',
				type:			'textarea'
			},
			quote_icon:{
				name:			'Quote Icon',
				defaultValue:	'yes',
				description:	'choose yes if you wish to have icons displayed with your quote.',
				type:			'select',
				options:		'yes|no' 
			},
			align:{
				name:			'Align',
				description:	'Set the alignment for your quote here.Your quote will float along the center, left or right hand sides depending on your choice.',
				type:			'select_type2',
				options:		{'left':'Left','right':'Right','center':'Center'}
			},
			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your quote.',
				type:			'color_picker'
			},
			
			cite:{
				name:			'Cite Name',
				defaultValue:	'',
				description:	'This is the name of the author. It will display at the end of the quote.',
				type:			'text'
			}
		}//PullQuote End

		//blockquote
		defaultSettings['add-blockquote'] = {
			content:{
				name:			'Blockquote Content',
				defaultValue:	'',
				description:	'Type out the text that you wish to display with your quote.',
				type:			'textarea'
			},
			align:{
				name:			'Align',
				description:	'Set the alignment for your quote here.Your quote will float along the center, left or right hand sides depending on your choice.',
				type:			'select_type2',
				options:		{'left':'Left','right':'Right','center':'Center'}
			},
			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text that appears on your quote.',
				type:			'color_picker'
			},
			variation:{
				name:			'Color Variation',
				defaultValue:	'',
				description:	'Choose one of our predefined color skins to use with your quote.',
				type:			'select_type2',
				options:		color_variations		
			},
			cite:{
				name:			'Cite Name',
				defaultValue:	'',
				description:	'This is the name of the author. It will display at the end of the quote.',
				type:			'text'
			}
			
		}//PullQuote End
		
		//tabs
		defaultSettings['add-tab'] = {
			type :{
				name:			'Type',
				defaultValue:	'tabs_framed',
				description:	'Choose which type of tabs you wish to use',
				type:			'select_type2',
				options:		{'tabs_horizontal':'Framed Horizontal Tabs','tabs_horizontal_frame_less':'Frameless Horizontal Tabs','tabs_vertical':'Vertical Tabs'}
			},
			
		
			tab :{
				name: 			'Tab (Type out the title for your tab and tab content.)',
				description:	'',
				type:			'clone',
				clone:			'<span class="clone">'
									+'Tab Title : <input class="title" type="text" name="tab_title[]" />'
									+'Tab Content : <textarea class="content" name="tab_content[]" rows="7" cols="35"></textarea>'
									+'<a href="#" onclick="cloneMe(this);">Add</a>|<a href="#" onclick="RemoveMe(this);">Remove</a>'
								+'</span>'
			}
		}//tabs End
		
		//Toggle
		defaultSettings['add-toggle'] = {
			type :{
				name:			'Type',
				defaultValue:	'tabs',
				description:	'Choose which type of toggle you wish to use.',
				type:			'select_type2',
				options:		{'toggle':'Default Toggle','toggle_framed':'Framed Toggle','accordion_group_toggle':'Accordion Toggle'
								,'accordion_group_framed_toggle':'Accordion Framed Toggle'}
			},

			class:{
				name:			'Add extra Class',
				defaultValue: 	'',
				description: 	'You can add any number of css class here( applied only for Accordion Toggle,Accordion Framed Toggle)',
				type: 			'text'
			},
			
			tab :{
				name: 			'Tab (Type out the title for your toggle and toggle content.)',
				description:	'',
				type:			'clone',
				clone:			'<span class="clone">'
									+'Tab Title : <input class="title" type="text" name="tab_title[]" />'
									+'Tab Content : <textarea class="content" name="tab_content[]" rows="7" cols="35"></textarea>'
									+'<a href="#" onclick="cloneMe(this);">Add</a>|<a href="#" onclick="RemoveMe(this);">Remove</a>'
								+'</span>'
			}
						
		}//Toggle End
		
		//Twitter Bookmark
		defaultSettings['add-twitter-boookmark'] = {
			username :{
				name: 			'Twitter username',
				defaultValue: 	'',
				description: 	'Enter your twitter username',
				type: 			'text'
			},
			layout:{
				name: 			'Tweet Position',
				defaultValue: 	'',
				description: 	'Choose whether you want your tweets to display vertically, horizontally, or none at all.',
				type: 			'select_type2',
				options:		{'none':'None','vertical':'Vertical','horizontal':'Horizontal'}
			},
			text:{
				name: 			'Custom Text',
				defaultValue: 	'',
				description: 	'This is the text that people will include in their Tweet when they share from your website.',
				type: 			'text'
			},
			url:{ 
				name: 			'Custom Url',
				defaultValue: 	'',
				description: 	'By default the URL from your page will be used but you can input a custom URL here.',
				type: 			'text'
			},
			related:{ 
				name: 			'Related Users',
				defaultValue: 	'',
				description: 	'By default the URL from your page will be used but you can input a custom URL here.',
				type: 			'text'
			},
			lang:{
				name: 			'Language',
				defaultValue: 	'',
				description: 	'Select which language you would like to display the button in.',
				type: 			'select_type2',
				options:		{'fr':'French','de':'German','it':'Italian','ja':'Japanese','ko':'Korean','ru':'Russian','es':'Spanish'}
			}
		}

		//Google+ Bookmark
		defaultSettings['add-googleplus-boookmark'] = {
			size:{
				name: 			'Size',
				defaultValue: 	'',
				description: 	'Choose how you would like to display the google plus button.',
				type: 			'select_type2',
				options:		{'small':'Small','standard':'Standard','medium':'Medium','tall':'Tall'}
			},
			lang:{
				name: 			'Language',
				defaultValue: 	'',
				description: 	'Select which language you would like to display the button in.',
				type: 			'select_type2',
				options:		{"ar": "Arabic","bn": "Bengali","bg": "Bulgarian","ca": "Catalan","zh": "Chinese","zh_CN": "Chinese (China)","zh_HK": "Chinese (Hong Kong)","zh_TW": "Chinese (Taiwan)","hr": "Croation","cs": "Czech","da": "Danish","nl": "Dutch","en_IN": "English (India)","en_IE": "English (Ireland)","en_SG": "English (Singapore)","en_ZA": "English (South Africa)","en_GB": "English (United Kingdom)","fil": "Filipino","fi": "Finnish","fr": "French","de": "German","de_CH": "German (Switzerland)","el": "Greek","gu": "Gujarati","iw": "Hebrew","hi": "Hindi","hu": "Hungarian","in": "Indonesian","it": "Italian","ja": "Japanese","kn": "Kannada","ko": "Korean","lv": "Latvian","ln": "Lingala","lt": "Lithuanian","ms": "Malay","ml": "Malayalam","mr": "Marathi","no": "Norwegian","or": "Oriya","fa": "Persian","pl": "Polish","pt_BR": "Portugese (Brazil)","pt_PT": "Portugese (Portugal)","ro": "Romanian","ru": "Russian","sr": "Serbian","sk": "Slovak","sl": "Slovenian","es": "Spanish","sv": "Swedish","gsw": "Swiss German","ta": "Tamil","te": "Telugu","th": "Thai","tr": "Turkish","uk": "Ukranian","vi": "Vietnamese"}
			}
			
		}

		//Facebook Bookmark
		defaultSettings['add-fb-boookmark'] = {
			layout:{
				name: 			'Layout',
				defaultValue: 	'',
				description: 	'Choose the layout you would like to use with your facebook button.',
				type: 			'select_type2',
				options:		{'standard':'Standard','box_count':'Box Count','button_count':'Button Count'}
			},
			
			send:{
				name: 			'Add send button?',
				defaultValue: 	'',
				description: 	'Choose yes to add the send button alongside the like button.',
				type: 			'select_type2',
				options:		{'true':'Yes','false':'No'}
			},
			
			show_faces:{
				name: 			'Show Faces?',
				defaultValue: 	'',
				description: 	'Choose yes to display faces.',
				type: 			'select_type2',
				options:		{'true':'Yes','false':'No'}
			},
			action:{
				name: 			'Action',
				defaultValue: 	'',
				description: 	'This is the text that gets displayed on the button.',
				type: 			'select_type2',
				options:		{'like':'Like','recommend':'Recommend'}
			},
			font:{
				name: 			'Font',
				defaultValue: 	'',
				description: 	'Select which font you would like to use.',
				type: 			'select_type2',
				options:		{'lucida+grande':'Lucida Grande','arial':'Arial','segoe+ui':'Segoe Ui','tahoma':'Tahoma','trebuchet+ms':'Trebuchet MS','verdana':'Verdana'}
			},
			colorscheme:{
				name: 			'Color Scheme',
				defaultValue: 	'',
				description: 	'Select the color scheme you would like to use.',
				type: 			'select_type2',
				options:		{'dark':'Dark','light':'Light'}
			}
			
		}

		//Digg Bookmark
		defaultSettings['add-digg-boookmark'] = {
			layout:{
				name: 			'Layout',
				defaultValue: 	'',
				description: 	'Choose how you would like to display the digg button.',
				type: 			'select_type2',
				options:		{'DiggWide':'Wide','DiggMedium':'Medium','DiggCompact':'Compact','DiggIcon':'Icon'}
			},
			url:{ 
				name: 			'Custom Url',
				defaultValue: 	'',
				description: 	'In case you wish to use a different URL you can input it here.',
				type: 			'text'
			},
			title:{ 
				name: 			'Custom Title',
				defaultValue: 	'',
				description: 	'In case you wish to use a different title you can input it here.',
				type: 			'text'
			},
			type:{ 
				name: 			'Article Type',
				defaultValue: 	'',
				description: 	'You can set the article type here for digg.For example if you wanted to set it in the gaming or entertainment topics then you would type this, "gaming, entertainment".',
				type: 			'text'
			},
			description:{ 
				name: 			'Custom Description',
				defaultValue: 	'',
				description: 	'You can set a custom description to be displayed within digg here.',
				type: 			'text'
			},
			related:{
				name: 			'Disable related Stories?',
				defaultValue: 	'',
				description: 	'This option allows you to specify whether links to related stories should be present in the pop up window that may appear when users click the button.',
				type: 			'select_type2',
				options:		{'true':'Yes'}
			}
		}

		//Stumbleupon Bookmark
		defaultSettings['add-stumbleupon-boookmark'] = {
			layout:{
				name: 			'Layout',
				defaultValue: 	'',
				description: 	'Choose how you would like to display the stumbleupon button.',
				type: 			'select_type2',
				options:		{'1':'Style 1','2':'Style 2','3':'Style 3','4':'Style 4','5':'Style 5','6':'Style 6'}
			},
			url:{ 
				name: 			'Custom Url',
				defaultValue: 	'',
				description: 	'You can set a custom URL to be displayed within stumbleupon here.',
				type: 			'text'
			}
		}

		//LinkedIn Bookmark
		defaultSettings['add-linkedin-boookmark'] = {
			layout:{
				name: 			'Layout',
				defaultValue: 	'',
				description: 	'Choose how you would like to display the linkedin button.',
				type: 			'select_type2',
				options:		{'1':'Style 1','2':'Style 2','3':'Style 3'}
			},
			url:{ 
				name: 			'Custom Url',
				defaultValue: 	'',
				description: 	'You can set a custom URL to be displayed within linkedin here.',
				type: 			'text'
			}
		}

		//Pintrest Bookmark
		defaultSettings['add-pintrest-boookmark'] = {
			text:{ 
				name: 			'Custom Text',
				defaultValue: 	'',
				description: 	'You can set some text to display alongside your delicious button.',
				type: 			'text'
			},
			layout:{
				name: 			'Layout',
				defaultValue: 	'',
				description: 	'Choose how you would like to display the Pinterest button.',
				type: 			'select_type2',
				options:		{'none':'None','vertical':'Vertical','horizontal':'Horizontal'}
			},
			image:{ 
				name: 			'Image URL',
				defaultValue: 	'',
				description: 	'Paste the URL of the image you want to be pinned here.',
				type: 			'text'
			},
			prompt:{
				name: 			'Auto Prompt',
				defaultValue: 	'',
				description: 	'Check this if you wish to have a prompt display to select your image when clicking on the Pinterest button. This will disable the count bubble.',
				type: 			'select_type2',
				options:		{'true':'Yes','false':'No'}
			}
			
		}

		//Delicious Bookmark
		defaultSettings['add-delicious-boookmark'] = {
			text:{ 
				name: 			'Description',
				defaultValue: 	'',
				description: 	'You can set some text to display alongside your delicious button.',
				type: 			'text'
			}
		}

		//Team Memeber
		defaultSettings['add-team-member'] = {

			name: {
				name: 			'Name',
				defaultValue: 	'',
				description: 	'You can set team member name',
				type: 			'text'
			},

			role: {
				name: 			'Role',
				defaultValue: 	'',
				description: 	'You can set team member role',
				type: 			'text'
			},
			
			image: {
				name: 			'Image',
				defaultValue: 	'',
				description: 	'Please specify member image url( size: 130*150)',
				type: 			'text'
			},
			
			
			twitter: {
				name: 			'Twitter',
				defaultValue: 	'',
				description: 	'Please specify twitter id',
				type: 			'text'
			},

			skype: {
				name: 			'Skype',
				defaultValue: 	'',
				description: 	'Please specify skype id',
				type: 			'text'
			},
			

			linkedin: {
				name: 			'Linkedin',
				defaultValue: 	'',
				description: 	'Please specify linkedin id',
				type: 			'text'
			},

			facebook: {
				name: 			'Facebook',
				defaultValue: 	'',
				description: 	'Please specify facebook id',
				type: 			'text'
			},

			flickr: {
				name: 			'Flickr',
				defaultValue: 	'',
				description: 	'Please specify flickr id',
				type: 			'text'
			},
			
			
			google: {
				name: 			'Google+',
				defaultValue: 	'',
				description: 	'Please specify googleplus id',
				type: 			'text'
			},

		}
		
		
		//Testimonial
		defaultSettings['add-team-testimonial'] = {
			item :{
				name: 			'Testimonial (Type out the Name ,image and content for your testimonial item.)',
				description:	'',
				type:			'clone',
				clone:			'<span class="clone">'
									+'Name : <input class="title" type="text" name="item_name[]" />'
									+'Image : <input class="image" type="text" name="item_image[]" />'
									+'Content : <textarea class="content" name="item_content[]" rows="7" cols="35"></textarea>'
									+'<a href="#" onclick="cloneMe(this);">Add</a>|<a href="#" onclick="RemoveMe(this);">Remove</a>'
								+'</span>'
				
			}
		}
		
		//Pricing Table (mytheme_pricing_table)
		defaultSettings['add-pricing-table'] = {

			type: {
				name:			'Chooose Type',
				defaultValue:	'',
				description:	'Choose which type of divider you would like to use.',
				type:			'select_type2',
				options:		{'':'Default','no-space':'No Space'}

			},

			layout_type :{ 
				name:	'Choose Layout Type',
				defaultValue:	'2',
				description:	'Choose which type of layout you would like to use.',
				type:			'select_type2',
				options:	{
						'1' :  '2 - Columns',		'2' : '3 - Columns',					'3' : '1/3 - 2/3',	
						'4' :  '4 - Columns',		'5' : '1/4 - 3/4',						'6' : '1/4 - 1/4 - 1/2',
						'7' :  '5 - Columns',		'8' : '1/5 - 1/5 - 1/5 - 2/5',			'9' : '1/5 - 1/5 - 3/5',
						'10':  '1/5 - 4/5',			'11': '1/5 - 2/5 - 2/5',				'12': '2/5 - 3/5', 
						'13':  '6 - Columns',		'14': '1/6 - 1/6 - 1/6 - 1/6 - 2/6 ',	'15': '1/6 - 1/6 - 1/6 - 3/6',
						'16':  '1/6 - 1/6 - 4/6',	'17': '1/6 - 5/6',						'18': '1/6 - 2/6 - 3/6',
						'19':  '3/6 - 3/6',			'20': '2/6 - 4/6'		
				}	
			}
			
			
		}
		
		defaultSettings['add-tooltip'] = {

			type :{
				name:			'Type',
				defaultValue:	'',
				description:	'Select which type of box you would like to use.',
				type:			'select_type2',
				options:		{'':'Default','boxed':'Boxed'}
			},	


			tooltip:{
				name:			'Tooltip',
				defaultValue:	'Default Tooltip Content',
				description:	'Type out the tooltip content ,The content will display as tooltip.',
				type:			'text'
			},

			
			bgcolor:{
				name:			'Custom BG Color',
				defaultValue:	'',
				description:	'Or you can also choose your own color to use as the background',
				type:			'color_picker'
			},

			textcolor:{
				name:			'Custom Text Color',
				defaultValue:	'',
				description:	'You can change the color of the text',
				type:			'color_picker'
			},


			position :{
				name:			'Type',
				defaultValue:	'top',
				description:	'Select the tooltip position',
				type:			'select_type2',
				options:		{'top':'Top','right':'Right','bottom':'Bottom','left':'Left'}
			},	

			link:{
				name:			'Link Url',
				defaultValue:	'#',
				description:	'Paste a URL here to use as a link for your button.',
				type:			'text'
			}

		}

		
		function CustomButtonClick(tag){
			var index = tag;
			//Build Form
			for (var index2 in defaultSettings[index]) {
				var current_item 	   = 		defaultSettings[index][index2],
					current_item_name  = 		current_item['name'],
					current_item_type  = 		current_item['type'],
					current_item_options  = 	current_item['options'],
					current_item_value  = 		current_item['defaultValue'],
					current_item_desc  = 		current_item['description'];
					
				output += '<tr>\n';
				output += '<th><label for="mytheme-' + index2 + '">'+ current_item_name +'</label></th>\n';
				output += '<td>';
				
				//Type
				if( current_item_type === 'text'){
					output += '\n<input type="text" name="mytheme-'+index2+'" id="mytheme-'+index2+'" value="'+current_item_value+'" />\n';
					
				}else if( current_item_type === 'textarea'){
					output += '<textarea name="mytheme-'+index2+'" id="mytheme-'+index2+'" cols="40" rows="10">'+current_item_value+'</textarea>';
					
				}else if( current_item_type === 'checkbox'){
					output += '\n<input type="checkbox" name="mytheme-'+index2+'" id="mytheme-'+index2+'" checked="checked" /> '+current_item_name +'\n';
					
				}else if( current_item_type === 'select'){
					var optionsArray = current_item_options.split('|');
						output += '\n<select name="mytheme-'+index2+'" id="mytheme-'+index2+'">\n';
							for( var index3 in optionsArray ){
								selected = (optionsArray[index3] === current_item_value) ? ' selected="selected"' : '';
								output += '<option value="'+optionsArray[index3]+'"'+ selected +'>'+optionsArray[index3]+'</option>\n';
							}
						output += '</select>\n';
						
				}else if( current_item_type === 'multiple_select'){
					output += '\n<select name="mytheme-'+index2+'[]" id="mytheme-'+index2+'" multiple="multiple">\n';
					output += "<option value='-'>Select</option>\n";
					 for (var option in current_item_options){
						 output += "<option value='"+option+"'>"+current_item_options[option]+"</option>\n";
					 }
					output += '</select>\n';
					
				}else if(current_item_type === 'select_type2'){
					output += '\n<select name="mytheme-'+index2+'" id="mytheme-'+index2+'">\n';
					output += "<option value='-'>Select</option>\n";
					 for (var option in current_item_options){
						 selected = (option === current_item_value) ? ' selected="selected"' : '';
						 output += "<option value='"+option+"'"+ selected +">"+current_item_options[option]+"</option>\n";
					 }
					output += '</select>\n';
					
				}else if(current_item_type==='my_editor'){
					$btn = current_item_options;
					$buttons = {'ul':'UL','ol':'OL'};
					for($b in $buttons ){
						$style = ( $btn != $b) ? 'style="display:none;" class="option" ':'class="option my-active"';
						output += '<a id="'+$b+'" href="#" onclick="doMe(this);"'+ $style +'><b><u></u>'+$b+'</b></a>';
					}
					output += '\n<div id="rte" contenteditable="true" unselectable="off" onclick="rtefocus(this);"></div>';
				}else if(current_item_type==='color_picker'){
					if(parseFloat(mysiteWpVersion) >= parseFloat("3.5")){
						output += '<input type="text" class="my-color-field" name="mytheme-'+index2+'" id="mytheme-'+index2+'" value="'+current_item_value+'" />';
					} else {
						output += '\n<input class="color_picker_element" type="text" name="mytheme-'+index2+'" id="mytheme-'+index2+'" value="'+current_item_value+'" />';
						output += '<input id="color_picker_btn-'+index2+'" type="button" class="button-secondary" onclick="showColorPicker(this);" value="pick Color">\n';
						output += '\n<div id="color_picker-'+index2+'"  class="color_picker" style="z-index:100; position:absolute;right:19px; display:none;"></div>';
					}
					
				}else if( current_item_type === 'clone'){
					clone = current_item['clone'];
					output += clone;
						
				}//Types End
				
				if( current_item_desc !='' ){
					output += '\n<br/><small>'+ current_item_desc +'</small>';
				}
				output += '\n</td>';
			}
			//End of Build Form
			
			var width = jQuery(window).width(),
				tbHeight = jQuery(window).height(),
				tbWidth = ( 720 < width ) ? 720 : width;
				tbWidth = tbWidth - 80;
				tbHeight = tbHeight - 84;
				
				var tbOptions = "<div id='mytheme_shortcodes_div'><form id='mytheme_shortcodes'><table id='shortcodes_table' class='form-table mytheme-"+ tag +"'>";
					tbOptions += output;
					tbOptions += '</table>\n<p class="submit">\n<input type="button" id="shortcodes-submit" class="button-primary" value="Add to content" name="submit" /></p>\n</form></div>';
				
				
				var form = jQuery(tbOptions);
				var table = form.find('table');
				form.appendTo('body').hide();
				
				//Add to content click function
				form.find('#shortcodes-submit').click(function(){
					var shortcode = "";
					//add-hr-rule
					if( tag === "add-hr-rule"){
						shortcode = "[";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							
							 switch(index){
								 case 'type':
								    value = ( value != "-") ? value : 'hr';
								 	shortcode +=  value+" ";
								 break;
								 
								 case 'top':
								 	if(table.find('#mytheme-type').val() == 'hr' || table.find('#mytheme-type').val() == 'hr-border'){
									 	shortcode += ' ' + index + '="' + value + '"';
									}
								 break;
								 
							  	 default:
								  if(value!==null) {
									  if((!("-" == value)) && (!(value=="")) ) {
									  	shortcode += ' ' + index + '="' + value + '"';
									  }
								  }
								 break;
							 }
						}
						shortcode += '/]<br/>';
					}//add-hr-rule
					
					//add-twitter
					else if(tag === 'add-twitter'){
						shortcode  = '[widget widget_name="MY_Twitter" widget_class_name="tweetbox" ';
						
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							
							  switch(index){
								  case 'exclude_replies':
								  case 'time':
								  case 'display_avatar':
								  	shortcode += ( value == "yes") ? ' ' + index + '="' + value + '"':'';
								  break;
								  default:
									  	shortcode += ' ' + index + '="' + value + '"';
								  break;
							  }//End Switch
							  
						}//End for
						
						shortcode += '/]<br/>';
					}// "add-twitter"
					
					//column layout
					else if(tag === 'add-columns'){
						var myclass = "";
						//For Loop
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							    
								//Attribute Switch 
								switch(index){
									
									case 'class':
										myclass = ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
									break;
									
									case 'layout_type':

										//Switch Start
										switch(value){
											case '1':
												shortcode += "[one-half"+ myclass +"]Content for 1/2 Column here[/one-half]<br/>";
												shortcode += "[one-half"+ myclass +" last]Content for 1/2 Column here[/one-half]<br/>";
											break;
												
											case '2':
												shortcode += "[one-third"+ myclass +"]Content for 1/3 Column here[/one-third]<br/>";
												shortcode += "[one-third"+ myclass +"]Content for 1/3 Column here[/one-third]<br/>";
												shortcode += "[one-third"+ myclass +" last]Content for 1/3 Column here[/one-third]<br/>";
											break;
											
											case '3':
												shortcode += "[one-third"+ myclass +"]Content for 1/3 Column here[/one-third]<br/>";
												shortcode += "[two-third"+ myclass +" last]Content for 2/3 Column here[/two-third]<br/>";
											break;
												
											case '4':
												shortcode += "[one-fourth"+ myclass +"]Content for 1/4 Column here[/one-fourth]<br/>";
												shortcode += "[one-fourth"+ myclass +"]Content for 1/4 Column here[/one-fourth]<br/>";
												shortcode += "[one-fourth"+ myclass +"]Content for 1/4 Column here[/one-fourth]<br/>";
												shortcode += "[one-fourth"+ myclass +" last]Content for 1/4 Column here[/one-fourth]<br/>";
											break;
											
											case '5':
												shortcode += "[one-fourth"+ myclass +"]Content for  1/4 Column here[/one-fourth]<br/>";
												shortcode += "[three-fourth"+ myclass +" last]Content for 3/4 Column here[/three-fourth]<br/>";
											break;
											
											case '6':
												shortcode += "[one-fourth"+ myclass +"]Content for 1/4 Column here[/one-fourth]<br/>";
												shortcode += "[one-fourth"+ myclass +"]Content for 1/4 Column here[/one-fourth]<br/>";
												shortcode += "[one-half"+ myclass +" last]Content for 1/2 Column here[/one-half]<br/>";
											break;
												
											case '7':
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +" last]Content for 1/5 Column here[/one-fifth]<br/>";
											break;
											
											case '8':
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[two-fifth"+ myclass +" last]Content for 2/5 Column here[/two-fifth]<br/>";
											break;
			
											case '9':
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[three-fifth"+ myclass +" last]Content for 3/5 Column here[/three-fifth]<br/>";
											break;
			
											case '10':
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[four-fifth"+ myclass +" last]Content for 4/5 Column here[/four-fifth]<br/>";
											break;
			
											case '11':
												shortcode += "[one-fifth"+ myclass +"]Content for 1/5 Column here[/one-fifth]<br/>";
												shortcode += "[two-fifth"+ myclass +"]Content for 2/5 Column here[/two-fifth]<br/>";
												shortcode += "[two-fifth"+ myclass +" last]Content for 2/5 Column here[/two-fifth]<br/>";
											break;
			
											case '12':
												shortcode += "[two-fifth"+ myclass +"]Content for 2/5 Column here[/two-fifth]<br/>";
												shortcode += "[three-fifth"+ myclass +" last]Content for 3/5 Column here[/three-fifth]<br/>";
												
											break;
												
											case '13':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +" last]Content for 1/6 Column here[/one-sixth]<br/>";
											break;
			
											case '14':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[two-sixth"+ myclass +" last]Content for 2/6 Column here[/two-sixth]<br/>";
											break;
			
											case '15':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[three-sixth"+ myclass +" last]Content for 3/6 Column here[/three-sixth]<br/>";
											break;
			
											case '16':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[four-sixth"+ myclass +" last]Content for 4/6 Column here[/four-sixth]<br/>";
											break;
			
											case '17':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[five-sixth"+ myclass +" last]Content for 5/6 Column here[/five-sixth]<br/>";
											break;
			
											case '18':
												shortcode += "[one-sixth"+ myclass +"]Content for 1/6 Column here[/one-sixth]<br/>";
												shortcode += "[two-sixth"+ myclass +"]Content for 2/6 Column here[/two-sixth]<br/>";
												shortcode += "[three-sixth"+ myclass +" last]Content for 3/6 Column here[/three-sixth]<br/>";
											break;
			
											case '19':
												shortcode += "[three-sixth"+ myclass +"]Content for 3/6 Column here[/three-sixth]<br/>";
												shortcode += "[three-sixth"+ myclass +" last]Content for 3/6 Column here[/three-sixth]<br/>";
											break;
			
											case '20':
												shortcode += "[two-sixth"+ myclass +"]Content for 2/6 Column here[/two-sixth]<br/>";
												shortcode += "[four-sixth"+ myclass +" last]Content for 4/6 Column here[/four-sixth]<br/>";
											break;
										}//Switch End
									
									break;	
								}//Attribute Switch End
								
						}//End For Loop
						
					}//column layout end
					
					//add-post-widget
					else if(tag == "add-post-widget" || tag == "add-portfolio-widget"){
						shortcode  = '[widget widget_name="MY_Recent_Posts" widget_class_name="recent-posts-box" ';
						
						if(tag == "add-portfolio-widget")
							shortcode  = '[widget widget_name="MY_Portfolio_Widget" widget_class_name="portfolio-widget-box" ';
						
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							
							 switch(index){
								 case '_enabled_image':
								 	shortcode += ( value == "yes") ? ' ' + index + '="1"':'';
								 break;
								 
							  	 default:
								  if(value!==null) {
									  if(!("-" == value)) {
									  	shortcode += ' ' + index + '="' + value + '"';
									  }
								  }
								 break;
							 }
						}
						shortcode += '/]<br/>';
					}//add-post-widget end
					
					//add-page-widget
					else if(tag == "add-page-widget"){
						shortcode  = '[widget widget_name="MY_Recent_Pages" widget_class_name="recent-pages-box" ';
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							 switch(index){
								 case '_enabled_image':
								 	shortcode += ( value == "yes") ? ' ' + index + '="1"':'';
								 break;
								 
							  	 default:
								  if(value !== null)
   							  	  	shortcode += ' ' + index + '="' + value + '"';
								 break;
							 }
						}
						shortcode += '/]<br/>';
					}//add-page-widget end
					
					//add-fancy-ul & add-fancy-ol
					else if(tag == "add-fancy-ul" || tag == "add-fancy-ol"){
						shortcode = (tag == "add-fancy-ul")?"[fancy-ul ":"[fancy-ol ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'style':
								case 'variation':
								case 'class':
									shortcode += ( (!("-" == value)) && (!(value=="")) ) ? ' '+ index + '="'+ value +'"':'';
								break;
							}
						}
						content = jQuery("div#rte").html();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += (tag == "add-fancy-ul")?"[/fancy-ul]<br/>":"[/fancy-ol]<br/>";
					}//add-fancy-ul & add-fancy-ol
					
					//add-fancy-buttons
					else if(tag=="add-fancy-buttons"){
						shortcode = "[button ";
						isbgcolorOff = true;
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
									case 'size':
									case 'target':
									case 'textcolor':
									case 'align':
									case 'link':
										shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
									break;

									case 'bgcolor':
										isbgcolorOff = 	(!(value=="")) ? false :isbgcolorOff;
										shortcode +=  (!(value=="")) ? ' ' + index + '="' + value + '"':'';
									break;
									
									case 'variation':
										if(isbgcolorOff)
											shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
									break;
									
							}
						}
						content = jQuery("input#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/button]<br/>';
					}//end add-fancy-buttons
					
					//add-highlight
					else if(tag == "add-highlight"){
						shortcode = "[highlight ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'type':
								case 'textcolor':
								case 'bgcolor':
								case 'variation':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
							}
						}
						content = jQuery("#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/highlight]<br/>';
					}//end add-highlight
					
					//add-box
					else if(tag == "add-box"){
						shortcode = "[box ";
						isbgcolorOff = true;
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'type':
								case 'title':
								case 'textcolor':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
								case 'bgcolor':
									isbgcolorOff = 	(!(value=="")) ? false :isbgcolorOff;
									shortcode +=  (!(value=="")) ? ' ' + index + '="' + value + '"':'';
								break;
								
								case 'variation':
									if(isbgcolorOff)
										shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
							}
						}
						content = jQuery("#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/box]<br/>';
					}//add-box end
					
					//dropcap
					else if(tag == "add-dropcap"){
						shortcode = "[dropcap ";
						isDefaultDropcap = true;
						isbgcolorOff = true;
						
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								
								case 'type':
									isDefaultDropcap = ( (!("-" == value)) && (!(value=="dropcap-default")) )?false:isDefaultDropcap;
									shortcode +=   (!("-" == value))  ? ' ' + index + '="' + value + '"':'';
								break;															

								case 'bgcolor':
									value =  isDefaultDropcap ? "":value;
									isbgcolorOff = 	(!(value=="")) ? false :isbgcolorOff;									
									shortcode +=  (!(value=="")) ? ' ' + index + '="' + value + '"':'';
								break;
							
								case 'variation':
									if(isbgcolorOff)
										shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;

								case 'textcolor':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
							}
						}
						content = jQuery("#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/dropcap]<br/>';
					}//dropcap end
					
					//map
					else if(tag=="add-map"){
						shortcode = "[map ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							
							 switch(index){
								case 'type':
								case 'width':
								case 'height':
								case 'zoom':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
								 
								case 'markers':
									shortcode += "]<br/>";
									table.find("span.clone").each(function(){
										$address = jQuery(this).find(".address").val();
										$desc = jQuery(this).find(".desc").val();
										 if($address!=="") shortcode += '[marker address="'+$address+'"]'+$desc+'[/marker]<br/>';
									});	
								break;	
							 }
						}
						shortcode += '[/map]<br/>';
					}//end map
					
					//pullquote
					else if(tag =="add-pullquote"){
						shortcode = "[pullquote ";
						istxtcolorOff = true;
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'type':
								case 'quote_icon':
								case 'align':
								case 'cite':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;

								case 'textcolor':
									istxtcolorOff = 	(!(value=="")) ? false :istxtcolorOff;
									shortcode +=  (!(value=="")) ? ' ' + index + '="' + value + '"':'';
								break;
								
								case 'variation':
									if(istxtcolorOff)
										shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
								/*
								default:
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;*/
							}
						}
						
						content = jQuery("#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/pullquote]<br/>';
						
					}//end pullqote
					
					//blockquote
					else if(tag =="add-blockquote"){
						shortcode = "[blockquote ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'align':
								case 'cite':
								case 'textcolor':
								case 'variation':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;	
							}
						}
						content = jQuery("#mytheme-content").val();
						shortcode = shortcode.replace(/^\s+|\s+$/g,"");
						shortcode += "]"+content;
						shortcode += '[/blockquote]<br/>';
					}//end blockquote
					
					//Tabs
					else if(tag=="add-tab"){
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'type':
									shortcode =  ( !("-" == value) ) ? "["+value+"]" :'[tabs_horizontal]';
									shortcode_end = ( !("-" == value) ) ? "[/"+value+"]<br/>" :'[/tabs_horizontal]<br/>';
								break;
								
								case 'tab':
									shortcode += "<br/>";
									table.find("span.clone").each(function(){
										$title = jQuery(this).find(".title").val();
										$content = jQuery(this).find(".content").val();
										shortcode += '[tab title="'+$title+'"]'+$content+'[/tab]<br/>';
									});
								break;
							}
						}
						
						shortcode += shortcode_end;
					}//tabs end
					
					//Toggle
					else if(tag == "add-toggle"){
						var $class;
						var $start_code  = "[toggle";
						var $end_code  = "[/toggle]<br/>";
						var is_accordion_group = false;
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'class':
									var $class =  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
								
								case 'type':
									if(!("-" == value) && !(value == "toggle")){
										if(value == "accordion_group_toggle"){
											var $start_code  = "[toggle ";
											var $end_code  = "[/toggle]<br/>";
											var is_accordion_group = true;
										}else if(value== "accordion_group_framed_toggle"){
											var $start_code  = "[toggle_framed ";
											var $end_code  = "[/toggle_framed]<br/>";
											var is_accordion_group = true;											
									   }else{
											var $start_code  = "[toggle_framed ";
											var $end_code  = "[/toggle_framed]<br/>";
									   }
									}
								break;
								
								case 'tab':
									table.find("span.clone").each(function(){
										$title = jQuery(this).find(".title").val();
										$content = jQuery(this).find(".content").val();
										shortcode += $start_code+' title="'+$title+'"]'+$content+$end_code;
									});
								break;
							}
						}
						if(is_accordion_group)	shortcode = "[accordion_group  "+$class+"]<br/>"+shortcode+"[/accordion_group]<br/>";	
					}//End Toggle
					 
					//add-twitter-boookmark || add-googleplus-boookmark || add-fb-boookmark
					else if(tag == "add-twitter-boookmark" || tag == "add-googleplus-boookmark" || tag == "add-fb-boookmark" || tag == "add-stumbleupon-boookmark" ||
							tag == "add-linkedin-boookmark" || tag == "add-delicious-boookmark"	|| tag == "add-pintrest-boookmark" || tag == "add-digg-boookmark"){ 
						shortcode = "[twitter ";
						if(tag == "add-googleplus-boookmark")  shortcode = "[googleplusone ";
						else if(tag == "add-fb-boookmark" )    shortcode = "[fblike ";
						else if(tag == "add-stumbleupon-boookmark" )    shortcode = "[stumbleupon ";
						else if(tag == "add-linkedin-boookmark")		shortcode = "[linkedin ";
						else if(tag == "add-delicious-boookmark")		shortcode = "[delicious ";
						else if(tag == "add-pintrest-boookmark")		shortcode = "[pintrest ";
						else if(tag == "add-digg-boookmark")			shortcode = "[digg ";

						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								default:
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
							}
						}
					   shortcode += '/]<br/>';
					}//end add-twitter-boookmark || add-googleplus-boookmark || add-fb-boookmark || add-stumbleupon-boookmark
					
					else if(tag=="add-team-member"){
						shortcode = "[team ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								 default:
									 shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								 break;
								
							}
						}
						shortcode += '/]<br/>';
					} // End for Team Member
					
					else if(tag == "add-team-testimonial"){
						shortcode = "";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								case 'item':
									table.find("span.clone").each(function(){
										var $title = jQuery(this).find(".title").val(),
											$image = jQuery(this).find(".image").val(),
											$content = jQuery(this).find(".content").val();
											
											$title = ( !($title == "") ) ? 'name="'+$title+'"' : "";
											$image = ( !($image == "") ) ? 'image="'+$image+'"': "";
											shortcode +='[testimonial '+$title+' '+$image+']'+ $content +'[/testimonial]<br/>';
									});
								break;
							}
						}
					}//End of add-team-testimonial
					
					//Tooltip
					else if(tag == "add-tooltip"){
						shortcode = "[tooltip ";
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							switch(index){
								default:
									 shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
								break;
							}								
						}
						shortcode += '] Tooltip Example Content [/tooltip]<br/>';
					}
					
					else if(tag == "add-pricing-table"){
						
						shortcode = "[pricing-table ";
						
						var $dummy_content = "<ul>";
							$dummy_content += "<li>Text</li>";
							$dummy_content += "<li>Text</li>";
							$dummy_content += "<li>Text</li>";
							$dummy_content += "</ul>";

						//For Loop
						for( var index in defaultSettings[tag]) {
							var value = table.find('#mytheme-' + index).val();
							
							switch(index){
								case 'type':
									shortcode +=  ( (!("-" == value)) && (!(value=="")) ) ? ' ' + index + '="' + value + '"':'';
									shortcode += " ]<br/>";
								break;
								
								case 'layout_type':
									//Switch Start
									switch(value){
										case '1':
											shortcode += "[one-half]<br/>";
											shortcode += '[pricing-table-item ]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-half]<br/>";
											
											shortcode += "[one-half last]<br/>";
											shortcode += '[pricing-table-item  selected]<br/>';
											shortcode += $dummy_content;											
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-half]<br/>";
										break;
											
										case '2':
											shortcode += "[one-third]<br/>";
											shortcode += '[pricing-table-item ]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-third]<br/>";
											
											shortcode += "[one-third]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-third]<br/>";
											
											shortcode += "[one-third last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-third]<br/>";
										break;
										
										case '3':
											shortcode += "[one-third]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-third]<br/>";
											
											shortcode += "[two-third last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-third]<br/>";
										break;
											
										case '4':
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[one-fourth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
										break;
										
										case '5':
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[three-fourth last]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-fourth]<br/>";
										break;
										
										case '6':
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[one-fourth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fourth]<br/>";
											
											shortcode += "[one-half last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-half]<br/>";
										break;
											
										case '7':
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
										break;
										
										case '8':
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[two-fifth last]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-fifth]<br/>";
										break;
		
										case '9':
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[three-fifth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-fifth]<br/>";
										break;
		
										case '10':
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[four-fifth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/four-fifth]<br/>";
										break;
		
										case '11':
											shortcode += "[one-fifth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-fifth]<br/>";
											
											shortcode += "[two-fifth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-fifth]<br/>";
											
											shortcode += "[two-fifth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-fifth]<br/>";
										break;
		
										case '12':
											shortcode += "[two-fifth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-fifth]<br/>";
											
											shortcode += "[three-fifth last]<br/>";
											shortcode += '[pricing-table-item ]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-fifth]<br/>";
											
										break;
											
										case '13':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
		
										break;
		
										case '14':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[two-sixth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-sixth]<br/>";
										break;
		
										case '15':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[three-sixth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-sixth]<br/>";
										break;
		
										case '16':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[four-sixth last]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/four-sixth]<br/>";
										break;
		
										case '17':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
											
											shortcode += "[five-sixth last]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/five-sixth]<br/>";
										break;
		
										case '18':
											shortcode += "[one-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/one-sixth]<br/>";
		
											shortcode += "[two-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-sixth]<br/>";
		
											shortcode += "[three-sixth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-sixth]<br/>";
											
										break;
		
										case '19':
											shortcode += "[three-sixth]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-sixth]<br/>";
		
											shortcode += "[three-sixth last]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/three-sixth]<br/>";
										break;
		
										case '20':
											shortcode += "[two-sixth]<br/>";
											shortcode += '[pricing-table-item selected]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/two-sixth]<br/>";
											
											shortcode += "[four-sixth last]<br/>";
											shortcode += '[pricing-table-item]<br/>';
											shortcode += $dummy_content;
											shortcode += "[/pricing-table-item]<br/>";
											shortcode += "[/four-sixth]<br/>";
										break;
											
									}//Switch End
								break;
								
							}
							
						}//End For Loop
						shortcode += "[/pricing-table]<br/>";
					}//End of table

					
					tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode + ' ');
					tb_remove();
				});

		tb_show( 'Design Themes: ' + tag + ' Shortcode', '#TB_inline?width=' + tbWidth + '&height=' + tbHeight + '&inlineId=mytheme_shortcodes_div' );
		jQuery('#mytheme_shortcodes_div').remove();
		output = '';
		}
	</script>
<?php }?>