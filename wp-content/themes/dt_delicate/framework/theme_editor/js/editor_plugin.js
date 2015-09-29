(function(){
	tinymce.create('tinymce.plugins.mytheme_quicktags', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		 
		 init : function(ed, url) {
			 
					ed.addButton('mytheme_mce_columns', {
						title : 'Add Cloumn layout',
						image : url + '/../images/icon-columns.png',
						onclick : function() {
							CustomButtonClick('add-columns');
						}
					}); // Add Columns
			 
					ed.addButton('mytheme_twitter_widget', {
						title : 'Add Twitter Widget',
						image : url + '/../images/icon-twitter.png',
						onclick : function() {
							CustomButtonClick('add-twitter');
						}
					}); // Add Twitter
					
					ed.addButton('mytheme_post_widget',{
						title: 'Add Post Widget',
						image : url + '/../images/icon-post.png',
						onclick: function(){
							CustomButtonClick('add-post-widget');
						}
					});//Post Widget
					
					/*ed.addButton('mytheme_page_widget',{
						title: 'Add Page Widget',
						image : url + '/../images/icon-page.png',
						onclick: function(){
							CustomButtonClick('add-page-widget');
						}
					});//Page Widget*/
					
					ed.addButton('mytheme_portfolio_widget',{
						title: 'Add Portfolio Widget',
						image : url + '/../images/icon-portfolio.png',
						onclick: function(){
							CustomButtonClick('add-portfolio-widget');
						}
					});//Portfolio Widget
					
					ed.addButton('mytheme_hr',{
						title: 'Add Horizontal Rule',
						image : url + '/../images/icon-hr.png',
						onclick: function(){
							CustomButtonClick('add-hr-rule');
						}
					});//Horizontal Rule

					ed.addButton('mytheme_fancy_ul',{
						title: 'Add Fancy Unorderd List',
						image : url + '/../images/icon-fancy-ul.png',
						onclick: function(){
							CustomButtonClick('add-fancy-ul');
						}
					});//Unorderd List

					ed.addButton('mytheme_fancy_ol',{
						title: 'Add Fancy Orderd List',
						image : url + '/../images/icon-fancy-ol.png',
						onclick: function(){
							CustomButtonClick('add-fancy-ol');
						}
					});//Unorderd List

					ed.addButton('mytheme_fancy_buttons',{
						title: 'Add Fancy Buttons',
						image : url + '/../images/icon-fancy-buttons.png',
						onclick: function(){
							CustomButtonClick('add-fancy-buttons');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Fancy Buttons
					
					ed.addButton('mytheme_highlight',{
						title: 'Highlight',
						image : url + '/../images/icon-highlight.png',
						onclick: function(){
							CustomButtonClick('add-highlight');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Fancy Buttons
					
					ed.addButton('mytheme_box',{
						title: 'Add Box',
						image : url + '/../images/icon-fancy-box.png',
						onclick: function(){
							CustomButtonClick('add-box');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Fancy Box

					ed.addButton('mytheme_dropcap',{
						title: 'Add Dropcap',
						image : url + '/../images/icon-dropcap.png',
						onclick: function(){
							CustomButtonClick('add-dropcap');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Dropcap

					ed.addButton('mytheme_map',{
						title: 'Add Google Map',
						image : url + '/../images/icon-map.png',
						onclick: function(){
							CustomButtonClick('add-map');
						}
					});//Dropcap

					ed.addButton('mytheme_pullquote',{
						title: 'Add Pullquote',
						image : url + '/../images/icon-pullquote.png',
						onclick: function(){
							CustomButtonClick('add-pullquote');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Pullquote

					ed.addButton('mytheme_blockquote',{
						title: 'Add Blockquote',
						image : url + '/../images/icon-blockquote.png',
						onclick: function(){
							CustomButtonClick('add-blockquote');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Pullquote

					ed.addButton('mytheme_tab',{
						title: 'Add Tab',
						image : url + '/../images/icon-tab.png',
						onclick: function(){
							CustomButtonClick('add-tab');
						}
					});//Tab

					ed.addButton('mytheme_toggle',{
						title: 'Add Toggle',
						image : url + '/../images/icon-toggle.png',
						onclick: function(){
							CustomButtonClick('add-toggle');
						}
					});//Toggle

					ed.addButton('mytheme_social_twitter',{
						title: 'Add Twitter Bookmark ',
						image : url + '/../images/icon-social-twitter.png',
						onclick: function(){
							CustomButtonClick('add-twitter-boookmark');
						}
					});//Twitter Bookmark

					ed.addButton('mytheme_social_googleplus',{
						title: 'Add Google+ Bookmark ',
						image : url + '/../images/icon-social-google-plus.png',
						onclick: function(){
							CustomButtonClick('add-googleplus-boookmark');
						}
					});//Google + Bookmark

					ed.addButton('mytheme_social_fb',{
						title: 'Add Facebook Bookmark ',
						image : url + '/../images/icon-social-fb.png',
						onclick: function(){
							CustomButtonClick('add-fb-boookmark');
						}
					});//Facebook Bookmark

					ed.addButton('mytheme_social_digg',{
						title: 'Add Digg Bookmark ',
						image : url + '/../images/icon-social-digg.png',
						onclick: function(){
							CustomButtonClick('add-digg-boookmark');
						}
					});//Digg Bookmark

					ed.addButton('mytheme_social_stumbleupon',{
						title: 'Add Stumbleupon Bookmark ',
						image : url + '/../images/icon-social-stumbleupon.png',
						onclick: function(){
							CustomButtonClick('add-stumbleupon-boookmark');
						}
					});//Stumbleupon Bookmark

					ed.addButton('mytheme_social_linkedin',{
						title: 'Add Linkedin Bookmark ',
						image : url + '/../images/icon-social-linkedin.png',
						onclick: function(){
							CustomButtonClick('add-linkedin-boookmark');
						}
					});//LinkedIn Bookmark

					ed.addButton('mytheme_social_pintrest',{
						title: 'Add Pintrest Bookmark ',
						image : url + '/../images/icon-social-pintrest.png',
						onclick: function(){
							CustomButtonClick('add-pintrest-boookmark');
						}
					});//Pintrest Bookmark

					ed.addButton('mytheme_social_delicious',{
						title: 'Add Delicious Bookmark ',
						image : url + '/../images/icon-social-delicious.png',
						onclick: function(){
							CustomButtonClick('add-delicious-boookmark');
						}
					});//Pintrest Bookmark
					
					ed.addButton('mytheme_team',{
						title: 'Add Team Member ',
						image : url + '/../images/icon-team.png',
						onclick: function(){
							CustomButtonClick('add-team-member');
						}
					});//Team

					ed.addButton('mytheme_testimonial',{
						title: 'Add Testimonial',
						image : url + '/../images/icon-testimonial.png',
						onclick: function(){
							CustomButtonClick('add-team-testimonial');
						}
					});//Team
					
					//icon-table
					ed.addButton('mytheme_pricing_table',{
						title: 'Add Pricing Table',
						image : url + '/../images/icon-pricing-table.png',
						onclick: function(){
							CustomButtonClick('add-pricing-table');
						}
					});//Table

					//icon-table
					ed.addButton('mytheme_tooltip',{
						title: 'Add Tooltip',
						image : url + '/../images/icon-tooltip.png',
						onclick: function(){
							CustomButtonClick('add-tooltip');
							jQuery(".my-color-field").wpColorPicker();
						}
					});//Table

					
		 } //init()

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		,createControl : function(n, cm) {
			return null;
		}

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		,getInfo : function() {
			return {
				longname : 		'Design Themes Shortcodes',
				author : 		'Design Themes',
				authorurl : 	'http://www.iamdesigning.com/',
				infourl : 		'http://www.iamdesigning.com/',
				version : 		'1.0'
			};
		}
		 
	});
	
	tinymce.PluginManager.add('mytheme_quicktags', tinymce.plugins.mytheme_quicktags);
})();