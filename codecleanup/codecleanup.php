<?php
/*
Plugin Name: WordPress Code Cleanup
Plugin URI: https://github.com/cleancoded/wordpress-code-cleanup/
Description: This plugin cleans and optimizes WordPress code for improved website performance and faster page load times.
Version: 1.1.8
Author: CLEANCODED
Author URI: https://cleancoded.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('CODECLEANUP_STORE_URL', 'https://codecleanup.io/');
define('CODECLEANUP_ITEM_NAME', 'codecleanup');

//load EDD custom updater class
if(!class_exists('EDD_SL_Plugin_Updater')) {
	include( dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

//EDD updater function
function codecleanup_edd_plugin_updater() {

	//setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(CODECLEANUP_STORE_URL, __FILE__, array(
			'version' 	=> '1.1.8',
			'item_name' => CODECLEANUP_ITEM_NAME,
			'author' 	=> 'cleancoded'
		)
	);
}
add_action('admin_init', 'codecleanup_edd_plugin_updater', 0);

//add our admin menus
if(is_admin()) {
	add_action('admin_menu', 'codecleanup_menu', 9);
}

//admin menu
function codecleanup_menu() {
	if(codecleanup_network_access()) {
		$pages = add_options_page( 'codecleanup', 'Code Cleanup', 'manage_options', 'codecleanup', 'codecleanup_admin');
	}
}

//admin settings page
function codecleanup_admin() {
	include plugin_dir_path(__FILE__) . '/inc/admin.php';
}

//plugin admin scripts
function codecleanup_admin_scripts() {
	if(codecleanup_network_access()) {
		wp_register_style('codecleanup-styles', plugins_url('/css/style.css', __FILE__), array(), '1.1.8');
		wp_enqueue_style('codecleanup-styles');
	}
}
add_action('admin_enqueue_scripts', 'codecleanup_admin_scripts');

//check multisite and verify access
function codecleanup_network_access() {
	if(is_multisite()) {
		$codecleanup_network = get_site_option('codecleanup_network');
		if((!empty($codecleanup_network['access']) && $codecleanup_network['access'] == 'super') && !is_super_admin()) {
			return false;
		}
	}
	return true;
}


/*function codecleanup_deactivate() {
}
register_deactivation_hook(__FILE__, 'codecleanup_deactivate');*/

//uninstall plugin + delete options
function codecleanup_uninstall() {

	//plugin options
	$codecleanup_options = array(
		'codecleanup_options',
		'codecleanup_cdn',
		'codecleanup_extras',
		'codecleanup_script_manager',
	);

	if(is_multisite()) {
		$codecleanup_network = get_site_option('codecleanup_network');
		if(!empty($codecleanup_network['clean_uninstall']) && $codecleanup_network['clean_uninstall'] == 1) {
			delete_site_option('codecleanup_network');

			$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
			if(is_array($sites) && $sites !== array()) {
				foreach($sites as $site) {
					foreach($codecleanup_options as $option) {
						delete_blog_option($site['blog_id'], $option);
					}
				}
			}
		}
	}
	else {
		$codecleanup_extras = get_option('codecleanup_extras');
		if(!empty($codecleanup_extras['clean_uninstall']) && $codecleanup_extras['clean_uninstall'] == 1) {
			foreach($codecleanup_options as $option) {
				delete_option($option);
			}
		}
	}
}
register_uninstall_hook(__FILE__, 'codecleanup_uninstall');

//all plugin file includes
include plugin_dir_path(__FILE__) . '/inc/settings.php';
include plugin_dir_path(__FILE__) . '/inc/functions.php';
include plugin_dir_path(__FILE__) . '/inc/network.php';
