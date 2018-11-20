<?php
/*
Plugin Name: Code Cleaner
Plugin URI: https://cleancoded.com/cleaner/
Description: The Code Cleaner plugin cleans and optimizes WordPress code for improved website performance and faster page load times.
Version: 2.0.1
Author: CLEANCODED
Author URI: https://cleancoded.com/cleaner/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: cleancoded
Domain Path: /languages
*/

/*****************************************************************************************
* EDD License
*****************************************************************************************/
define('CODECLEANER_STORE_URL', 'https://cleancoded.com/cleaner/');
define('CODECLEANER_ITEM_NAME', 'codecleaner');
define('CODECLEANER_VERSION', '2.0.1');

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
		$pages = add_options_page('Code Cleaner', 'Code Cleaner', 'manage_options', 'codecleaner', 'codecleaner_admin');
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

//Optimization Notice
function codecleaner_guide_notice() {
    if(get_current_screen()->base == 'settings_page_codecleaner') {
        echo "<div class='notice notice-info'>";
        	echo "<p>";
        		_e("Have a look at our <a href='https://cleancoded.com/speed-up-wordpress/' title='WordPress Optimization Guide' target='_blank'>WordPress Optimization Guide</a> for even more ways to make WordPress faster!", 'codecleaner');
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
		'codecleaner_woocommerce',
		'codecleaner_ga',
		'codecleaner_extras',
		'codecleaner_deep_cleaning',
		'codecleaner_deep_cleaning_settings',
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

//files include in plugin
include plugin_dir_path(__FILE__) . '/inc/codecleaner_settings.php';
include plugin_dir_path(__FILE__) . '/inc/codecleaner_functions.php';
include plugin_dir_path(__FILE__) . '/inc/codecleaner_network.php';