<?php
/*
Plugin Name: Code Cleaner
Plugin URI: https://cleancoded.com/cleaner/
Description: This plugin cleans and optimizes WordPress code for improved website performance and faster page load times.
Version: 1.1.8
Author: CLEANCODED
Author URI: https://cleancoded.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//add our admin menus
if(is_admin()) {
	add_action('admin_menu', 'codecleaner_menu', 9);
}

//admin menu
function codecleaner_menu() {
	if(codecleaner_network_access()) {
		$pages = add_options_page( 'codecleaner', 'Code Cleaner', 'manage_options', 'codecleaner', 'codecleaner_admin');
	}
}

//admin settings page
function codecleaner_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//plugin admin scripts
function codecleaner_admin_scripts() {
	if(codecleaner_network_access()) {
		wp_register_style('codecleaner-styles', plugins_url('/css/style.css', __FILE__), array(), '1.1.8');
		wp_enqueue_style('codecleaner-styles');
	}
}
add_action('admin_enqueue_scripts', 'codecleaner_admin_scripts');

//check multisite and verify access
function codecleaner_network_access() {
	if(is_multisite()) {
		$codecleaner_network = get_site_option('codecleaner_network');
		if((!empty($codecleaner_network['access']) && $codecleaner_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}

//uninstall plugin and delete options
function codecleaner_uninstall() {

	//plugin options
	$codecleaner_options = array(
		'codecleaner_options',
		'codecleaner_cdn',
		'codecleaner_extras',
		'codecleaner_script_manager',
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

//all plugin file includes
include plugin_dir_path(__FILE__) . '/inc/settings.php';
include plugin_dir_path(__FILE__) . '/inc/functions.php';
include plugin_dir_path(__FILE__) . '/inc/network.php';
