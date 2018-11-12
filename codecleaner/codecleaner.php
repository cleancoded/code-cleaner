<?php
/*
Plugin Name: Codecleaner
Plugin URI: https://cleancoded.com/
Description: This plugin adds an assortment of performance and speed improvements to your WordPress installation.
Version: 1.0.1
Author: cleancoded
Author URI: https://cleancoded.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cleancoded
Domain Path: /languages
*/

/*****************************************************************************************
* EDD License
*****************************************************************************************/
define('CODECLEANER_STORE_URL', 'https://cleancoded.com/');
define('CODECLEANER_ITEM_NAME', 'codecleaner');
define('CODECLEANER_VERSION', '1.0.1');

//load translations
function codecleaner_load_textdomain() {
	load_plugin_textdomain('codecleaner', false, dirname(plugin_basename( __FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'codecleaner_load_textdomain');

// admin menus loading
if(is_admin()) {
	add_action('admin_menu', 'codecleaner_menu', 9);
}

//admin menu
function codecleaner_menu() {
	if(codecleaner_network_access()) {
		$pages = add_options_page('codecleaner', 'Codecleaner', 'manage_options', 'codecleaner', 'codecleaner_admin');
	}
}

//admin page settings
function codecleaner_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//load EDD updater class
if(!class_exists('EDD_SL_Plugin_Updater')) {
	include(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

//EDD function updater
function codecleaner_plugin_updater() {

	//get license key from the DB
	if(is_network_admin()) {
		$license_key = trim(get_site_option('codecleaner_edd_license_key'));
	}
	else {
		$license_key = trim(get_option('codecleaner_edd_license_key'));
	}
	
	//updater setting
	$edd_updater = new EDD_SL_Plugin_Updater(CODECLEANER_STORE_URL, __FILE__, array(
			'version' 	=> CODECLEANER_VERSION,
			'license' 	=> $license_key,
			'item_name' => CODECLEANER_ITEM_NAME,
			'author' 	=> 'forgemedia'
		)
	);
}
add_action('admin_init', 'codecleaner_plugin_updater', 0);

//load plugin scripts (.css/.js)
function codecleaner_admin_scripts() {
	if(codecleaner_network_access()) {
		wp_register_style('codecleaner-styles', plugins_url('/css/style.css', __FILE__), array(), CODECLEANER_VERSION);
		wp_enqueue_style('codecleaner-styles');
	}
}
add_action('admin_enqueue_scripts', 'codecleaner_admin_scripts');

//verify access and identify problems
function codecleaner_network_access() {
	if(is_multisite()) {
		$codecleaner_network = get_site_option('codecleaner_network');
		if((!empty($codecleaner_network['access']) && $codecleaner_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}

function codecleaner_activate() {
	$codecleaner_ga = get_option('codecleaner_ga');

	//enable local analytics scheduled event
	if(!empty($codecleaner_ga['enable_local_ga']) && $codecleaner_ga['enable_local_ga'] == "1") {
		if(!wp_next_scheduled('cleadcoded_update_ga')) {
			wp_schedule_event(time(), 'daily', 'cleadcoded_update_ga');
		}
	}
}
register_activation_hook(__FILE__, 'codecleaner_activate');

//register a license deactivation
function codecleaner_deactivate() {

	//remove local analytics scheduled event
	if(wp_next_scheduled('cleadcoded_update_ga')) {
		wp_clear_scheduled_hook('cleadcoded_update_ga');
	}
	
	//get the license from the database
	$license = trim(get_option('codecleaner_edd_license_key'));
	$license_status = get_option('codecleaner_edd_license_status');

	if(!empty($license) && (!empty($license_status) && $license_status == "valid")) {

		//data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode(CODECLEANER_ITEM_NAME),
			'url'       => home_url()
		);

		//call the custom API
		$response = wp_remote_post(CODECLEANER_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

		//make sure the response came back okay
		if(is_wp_error($response)) {
			return false;
		}

		//decode the license data
		$license_data = json_decode(wp_remote_retrieve_body($response));

		//$license_data->license will be either "deactivated" or "failed"
		if($license_data->license == 'deactivated') {
			delete_option('codecleaner_edd_license_status');
		}
	}	
}
register_deactivation_hook(__FILE__, 'codecleaner_deactivate');

//Optimization Notice
function codecleaner_guide_notice() {
    if(get_current_screen()->base == 'settings_page_codecleaner') {
        echo "<div class='notice notice-info'>";
        	echo "<p>";
        		_e("Check out our <a href='https://woorkup.com/speed-up-wordpress/' title='WordPress Optimization Guide' target='_blank'>complete optimization guide</a> for more ways to speed up WordPress.", 'codecleaner');
        	echo "</p>";
        echo "</div>";
    }
}
add_action('admin_notices', 'codecleaner_guide_notice');

//uninstall\remove plugin + delete options
function codecleaner_uninstall() {

	//plugin options
	$codecleaner_options = array(
		'codecleaner_options',
		'codecleaner_cdn',
		'codecleaner_ga',
		'codecleaner_extras',
		'codecleaner_script_manager',
		'codecleaner_script_manager_settings',
		'codecleaner_edd_license_key',
		'codecleaner_edd_license_status'
	);

	if(is_multisite()) {
		$codecleaner_network = get_site_option('codecleaner_network');
		if(!empty($codecleaner_network['clean_uninstall']) && $codecleaner_network['clean_uninstall'] == 1) {
			delete_site_option('codecleaner_network');

			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
			if(is_array($sites) && $sites !== array()) {
				foreach($sites as $site) {
					foreach($codecleaner_options as $option) {
						delete_blog_option($site['blog_id'], $option);
					}
				}
			}
		}
	}
	else {
		$codecleaner_extras = get_option('codecleaner_extras');
		if(!empty($codecleaner_extras['clean_uninstall']) && $codecleaner_extras['clean_uninstall'] == 1) {
			foreach($codecleaner_options as $option) {
				delete_option($option);
			}
		}
	}
}
register_uninstall_hook(__FILE__, 'codecleaner_uninstall');

//license messages in plugins table
function codecleaner_meta_links($links, $file) {
	if(strpos($file, 'codecleaner.php' ) !== false) {

		if(is_network_admin()) {
			$license_info = codecleaner_edd_check_network_license();
			$license_url = network_admin_url('settings.php?page=codecleaner');
		}
		else {
			$license_info = codecleaner_edd_check_license();
			$license_url = admin_url('options-general.php?page=codecleaner');
		}

		$codecleaner_links = array();
		$codecleaner_links[] = '<a href="' . $license_url . '">' . esc_html__('Settings', 'codecleaner') . '</a>';

		if(!is_plugin_active_for_network('codecleaner/codecleaner.php') || is_network_admin()) {

			if(!empty($license_info->license) && $license_info->license == "valid") {
				$codecleaner_links[] = '<a href="' . $license_url . '&tab=license" style="color: green;">' . __('License is Activated', 'codecleaner') . '</a>';
			}
			elseif(!empty($license_info->license) && $license_info->license == "expired") {
				$codecleaner_links[] = '<a href="' . $license_url . '&tab=license" style="color: orange;">' . __('Renew License', 'codecleaner') . '</a>';
			}
			else {
				$codecleaner_links[] = '<a href="' . $license_url . '&tab=license" style="color: red;">' . __('Activate License', 'codecleaner') . '</a>';
			}

		}

		$links = array_merge($links, $codecleaner_links);
	}
	return $links;
}
add_filter('plugin_row_meta', 'codecleaner_meta_links', 10, 2);


//files include in plugin
include plugin_dir_path(__FILE__) . '/inc/codecleaner_settings.php';
include plugin_dir_path(__FILE__) . '/inc/codecleaner_functions.php';
include plugin_dir_path(__FILE__) . '/inc/codecleaner_network.php';