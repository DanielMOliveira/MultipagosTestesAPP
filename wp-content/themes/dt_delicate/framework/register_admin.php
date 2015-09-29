<?php
add_action('admin_init', 'mytheme_admin_options_init', 1);
add_action('admin_print_styles', 'my_admin_panel_styles');
add_action('admin_print_scripts', 'my_admin_panel_scripts');

##Admin panel media uploader hooks( to alter the media uploder used to upload logo , favicon ... )
if (isset($_GET['mytheme_upload_button']) || isset($_POST['mytheme_upload_button']) && (isset($_GET['page']) && $_GET['page'] == 'parent')) :
	add_action('admin_init', 'mytheme_image_upload_option');
endif;
## End hook

function my_admin_panel_styles() {
	global $wp_version;

	wp_enqueue_style('thickbox');

	if (version_compare($wp_version, '3.5', '>=')) :
		wp_enqueue_script('wp-color-picker'); #New Color Picker
	else :
		wp_enqueue_script('farbtastic'); #Color picker
	endif;

	wp_enqueue_style('my-adminpanel', IAMD_FW_URL.'theme_options/style.css');
}

function my_admin_panel_scripts() {
	global $wp_version;

	echo "<script type=\"text/javascript\">
	//<![CDATA[
	var mysiteWpVersion = '$wp_version';
	//]]>\r</script>\r";

	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-slider');

	if (version_compare($wp_version, '3.5', '>=')) :
		wp_enqueue_style('wp-color-picker'); #New Color Picker
	else :
		wp_enqueue_style('farbtastic'); #Color Picker
	endif;

	wp_enqueue_script('mytheme-tooltip', IAMD_FW_URL.'js/admin/jquery.tools.min.js');
	wp_enqueue_script('mytheme', IAMD_FW_URL.'js/admin/mytheme.admin.js');
	
	wp_localize_script('mytheme', 'objectL10n', array(
		'resetConfirm' => __('This will restore all of your options to default. Are you sure?', 'dt_delicate'),
		'importConfirm' => __('You are going to import the dummy data provided with the theme, kindly confirm?', 'dt_delicate'),
		'disableImportMsg' => __('Importing is disabled.. :), Please set Disable Import to NO in Buddha Panel General Settings', 'dt_delicate'),
		'backupMsg' => __('Click OK to backup your current saved options.', 'dt_delicate'),
		'backupSuccess' => __('Your options are backuped successfully', 'dt_delicate'),
		'backupFailure' => __('Backup Process not working', 'dt_delicate'),
		'restoreMsg' => __('Warning: All of your current options will be replaced with the data from your last backup! Proceed?', 'dt_delicate'),
		'restoreSuccess' => __('Your options are restored from previous backup successfully', 'dt_delicate'),
		'restoreFailure' => __('Restore Process not working', 'dt_delicate'),
		'importMsg' => __('Click ok import options from the above textarea', 'dt_delicate'),
		'importSuccess' => __('Your options are imported successfully', 'dt_delicate'),
		'importFailure' => __('Import Process not working', 'dt_delicate'),
		'saveall' => __('Save All','dt_delicate'),
		'saving' => __('Saving ...','dt_delicate')));
}

function mytheme_admin_options_init() {
	register_setting(IAMD_THEME_SETTINGS, IAMD_THEME_SETTINGS);
	add_option(IAMD_THEME_SETTINGS, mytheme_default_option());
	if (isset($_POST['mytheme-option-save'])) :
		mysite_ajax_option_save();
	endif;
	
	if (isset($_POST['mytheme']['reset'])) :
		delete_option(IAMD_THEME_SETTINGS);
		update_option(IAMD_THEME_SETTINGS, mytheme_default_option()); # To set Default options
		wp_redirect(admin_url('admin.php?page=parent&reset=true'));
		exit;
	endif;
}

function mysite_ajax_option_save() {
	check_ajax_referer(IAMD_THEME_SETTINGS.'_wpnonce', 'mytheme_admin_wpnonce');
	$data = $_POST;
	unset($data['_wp_http_referer'], $data['_wpnonce'], $data['action']);
	unset($data['mytheme_admin_wpnonce'], $data['mytheme-option-save'], $data['option_page']);

	$msg = array('success' => false, 'message' => __('Error: Options not saved, please try again.', 'dt_delicate'));

	$data = array_filter($data[IAMD_THEME_SETTINGS]);

	if (get_option(IAMD_THEME_SETTINGS) != $data) {
		if (update_option(IAMD_THEME_SETTINGS, $data))
			$msg = array('success' => 'options_saved', 'message' => __('Options Saved.', 'dt_delicate'));
	} else {
		$msg = array('success' => true, 'message' => __('Options Saved.', 'dt_delicate'));
	}

	$echo = json_encode($msg);
	@header('Content-Type: application/json; charset='.get_option('blog_charset'));
	echo $echo;
	exit;
}

add_action('admin_head-toplevel_page_parent', 'mytheme_admin_print_scripts');
function mytheme_admin_print_scripts() {
	echo "<script type=\"text/javascript\">
	//<![CDATA[
	jQuery(document).ready(function(){
		mythemeAdmin.menuSort();
	});
	//]]>\r</script>\r";
}

function custom_login_logo() {
	$logo = mytheme_option('advance', 'admin-login-logo-url');

	if ("true" == mytheme_option('advance', 'enable-admin-login-logo-url')) :
		if (!empty($logo))
			echo '<style type="text/css">  div#login h1 a { background-image:url('.$logo.')} </style>';
	endif;
}
add_action('login_head', 'custom_login_logo');

function custom_logo() {
	$logo = mytheme_option('advance', 'admin-logo-url');

	if ("true" == mytheme_option('advance', 'enable-admin-logo-url')) :
		if (!empty($logo))
			echo '<style type="text/css"> #wp-admin-bar-wp-logo .ab-icon { background-image: url('.$logo.') !important;  background-position:0px !important;}</style>';
	endif;

}
add_action('admin_head', 'custom_logo');

#Ajax Import functionality
add_action('wp_ajax_my_ajax_import_data', 'my_ajax_import_data');
function my_ajax_import_data() {
	get_template_part('framework/wordpress-importer/my-importer');
}
#Ajax Import functionality end

######### SAMPLE FONT PREVIEW ##########
add_action('wp_ajax_mytheme_font_url', 'mytheme_font_url');
function mytheme_font_url() {
	$recieve_font = $_POST['font'];
	$font_url = array('url' => 'http://fonts.googleapis.com/css?family='.str_replace(' ', '+', $recieve_font));
	die(json_encode($font_url));
}

#### BACKUP OPTION #####
add_action('wp_ajax_mytheme_backup_and_restore_action', 'mytheme_backup_and_restore_action');
function mytheme_backup_and_restore_action() {

	$save_type = $_POST['type'];

	if ($save_type == 'backup_options') :
		$data = array('general' => mytheme_option('general'),
			'appearance' => mytheme_option('appearance'),
			'integration' => mytheme_option('integration'),
			'seo' => mytheme_option('seo'),
			'specialty' => mytheme_option('specialty'),
			'widgetarea' => mytheme_option("widgetarea"),
			'mobile' => mytheme_option('mobile'),
			'advance' => mytheme_option('advance'),
			'bbar' => mytheme_option('bbar'),
			'backup' => date('r'));
		update_option("mytheme_backup", $data);
		die('1');
	elseif ($save_type == 'restore_options') :
		$data = get_option("mytheme_backup");
		update_option(IAMD_THEME_SETTINGS, $data);
		die('1');
	elseif ($save_type == "import_options") :
		$data = $_POST['data'];
		$data = unserialize(base64_decode($data)); //100% safe - ignore theme check nag
		update_option(IAMD_THEME_SETTINGS, $data);
		die('1');
	elseif( $save_type == "reset_options") :
		delete_option(IAMD_THEME_SETTINGS);
		update_option(IAMD_THEME_SETTINGS, mytheme_default_option()); # To set Default options
		die('1');
	endif;
}?>