<?php
$codecleaner_options = get_option('codecleaner_options');
$codecleaner_cdn = get_option('codecleaner_cdn');
$codecleaner_ga = get_option('codecleaner_ga');
$codecleaner_extras = get_option('codecleaner_extras');

/* Disable XML-RPC
/***********************************************************************/
if(!empty($codecleaner_options['disable_xmlrpc']) && $codecleaner_options['disable_xmlrpc'] == "1") {
	add_filter('xmlrpc_enabled', '__return_false');
	add_filter('wp_headers', 'codecleaner_remove_x_pingback');
	add_filter('pings_open', '__return_false', 9999);
}

function codecleaner_remove_x_pingback($headers) {
    unset($headers['X-Pingback'], $headers['x-pingback']);
    return $headers;
}

if(!empty($codecleaner_options['remove_jquery_migrate']) && $codecleaner_options['remove_jquery_migrate'] == "1") {
	add_filter('wp_default_scripts', 'codecleaner_remove_jquery_migrate');
}
if(!empty($codecleaner_options['hide_wp_version']) && $codecleaner_options['hide_wp_version'] == "1") {
	remove_action('wp_head', 'wp_generator');
	add_filter('the_generator', 'codecleaner_hide_wp_version');
}
if(!empty($codecleaner_options['remove_wlwmanifest_link']) && $codecleaner_options['remove_wlwmanifest_link'] == "1") {
	remove_action('wp_head', 'wlwmanifest_link');
}
if(!empty($codecleaner_options['remove_rsd_link']) && $codecleaner_options['remove_rsd_link'] == "1") {
	remove_action('wp_head', 'rsd_link');
}

/* Options Actions + Filters
/***********************************************************************/
if(!empty($codecleaner_options['disable_emojis']) && $codecleaner_options['disable_emojis'] == "1") {
	add_action('init', 'codecleaner_disable_emojis');
}
if(!empty($codecleaner_options['disable_embeds']) && $codecleaner_options['disable_embeds'] == "1") {
	add_action('init', 'codecleaner_disable_embeds', 9999);
}
if(!empty($codecleaner_options['remove_query_strings']) && $codecleaner_options['remove_query_strings'] == "1") {
	add_action('init', 'codecleaner_remove_query_strings');
}

/* Disable RSS Feeds
/***********************************************************************/
if(!empty($codecleaner_options['disable_rss_feeds']) && $codecleaner_options['disable_rss_feeds'] == "1") {
	add_action('template_redirect', 'codecleaner_disable_rss_feeds', 1);
}

function codecleaner_disable_rss_feeds() {
	if(!is_feed() || is_404()) {
		return;
	}
	
	global $wp_rewrite;
	global $wp_query;

	//check for GET feed query variable firet and redirect
	if(isset($_GET['feed'])) {
		wp_redirect(esc_url_raw(remove_query_arg('feed')), 301);
		exit;
	}

	//unset wp_query feed variable
	if(get_query_var('feed') !== 'old') {
		set_query_var('feed', '');
	}
		
	//redirect to the proper URL
	redirect_canonical();

	// display error message or redirect failed
	wp_die(sprintf(__("No feed available, please visit the <a href='%s'>homepage</a>!"), esc_url(home_url('/'))));
}
/* Remove RSS Feed Links
/***********************************************************************/
if(!empty($codecleaner_options['remove_feed_links']) && $codecleaner_options['remove_feed_links'] == "1") {
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
}



function codecleaner_disable_self_pingbacks(&$links) {
	$home = get_option('home');
	foreach($links as $l => $link) {
		if(strpos($link, $home) === 0) {
			unset($links[$l]);
		}
	}
}

/* Remove Shortlink
/***********************************************************************/
if(!empty($codecleaner_options['remove_shortlink']) && $codecleaner_options['remove_shortlink'] == "1") {
	remove_action('wp_head', 'wp_shortlink_wp_head');
	remove_action ('template_redirect', 'wp_shortlink_header', 11, 0);
}

/* Remove REST API Links
/***********************************************************************/
if(!empty($codecleaner_options['remove_rest_api_links']) && $codecleaner_options['remove_rest_api_links'] == "1") {
	remove_action('wp_head', 'rest_output_link_wp_head');
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);
}

/* Disable Self Pingbacks
/***********************************************************************/
if(!empty($codecleaner_options['disable_self_pingbacks']) && $codecleaner_options['disable_self_pingbacks'] == "1") {
	add_action('pre_ping', 'codecleaner_disable_self_pingbacks');
}

/* Disable WooCommerce Widgets
/***********************************************************************/
if(!empty($codecleaner_options['disable_woocommerce_widgets']) && $codecleaner_options['disable_woocommerce_widgets'] == "1") {
	add_action('widgets_init', 'codecleaner_disable_woocommerce_widgets', 99);
}
function codecleaner_disable_woocommerce_widgets() {
	global $codecleaner_options;

	unregister_widget('WC_Widget_Products');
	unregister_widget('WC_Widget_Product_Categories');
	unregister_widget('WC_Widget_Product_Tag_Cloud');
	unregister_widget('WC_Widget_Cart');
	unregister_widget('WC_Widget_Layered_Nav');
	unregister_widget('WC_Widget_Layered_Nav_Filters');
	unregister_widget('WC_Widget_Price_Filter');
	unregister_widget('WC_Widget_Product_Search');
	unregister_widget('WC_Widget_Recently_Viewed');

	if(empty($codecleaner_options['disable_woocommerce_reviews']) || $codecleaner_options['disable_woocommerce_reviews'] == "0") {
		unregister_widget('WC_Widget_Recent_Reviews');
		unregister_widget('WC_Widget_Top_Rated_Products');
		unregister_widget('WC_Widget_Rating_Filter');
	}
}

if(!empty($codecleaner_options['disable_heartbeat'])) {
	add_action('init', 'codecleaner_disable_heartbeat', 1);
}
if(!empty($codecleaner_options['heartbeat_frequency'])) {
	add_filter('heartbeat_settings', 'codecleaner_heartbeat_frequency');
}
if(!empty($codecleaner_options['limit_post_revisions'])) {
	define('WP_POST_REVISIONS', $codecleaner_options['limit_post_revisions']);
}
if(!empty($codecleaner_options['autosave_interval'])) {
	define('AUTOSAVE_INTERVAL', $codecleaner_options['autosave_interval']);
}

if(!empty($codecleaner_extras['dns_prefetch'])) {
	add_action('wp_head', 'codecleaner_dns_prefetch', 1);
}

if(!empty($codecleaner_extras['script_manager']) && $codecleaner_extras['script_manager'] == "1") {
	add_action('admin_bar_menu', 'codecleaner_script_manager_admin_bar', 1000);
	add_action('wp_footer', 'codecleaner_script_manager', 1000);
	add_action('script_loader_src', 'codecleaner_dequeue_scripts', 1000, 2);
	add_action('style_loader_src', 'codecleaner_dequeue_scripts', 1000, 2);
	add_action('template_redirect', 'codecleaner_script_manager_update', 10, 2);
	add_action('wp_enqueue_scripts', 'codecleaner_script_manager_scripts');
}
/* Disable Google Maps
/***********************************************************************/
if(!empty($codecleaner_options['disable_google_maps']) && $codecleaner_options['disable_google_maps'] == "1") {
	add_action('wp_loaded', 'codecleaner_disable_google_maps');
}

function codecleaner_disable_google_maps() {
	ob_start('codecleaner_disable_google_maps_regex');
}

function codecleaner_disable_google_maps_regex($html) {
	$html = preg_replace('/<script[^<>]*\/\/maps.(googleapis|google|gstatic).com\/[^<>]*><\/script>/i', '', $html);
	return $html;
}

/* Disable Dashicons
/***********************************************************************/
if(!empty($codecleaner_options['disable_dashicons']) && $codecleaner_options['disable_dashicons'] == "1") {
	add_action('wp_enqueue_scripts', 'codecleaner_disable_dashicons');
}

function codecleaner_disable_dashicons() {
	if(!is_user_logged_in()) {
		wp_dequeue_style('dashicons');
	    wp_deregister_style('dashicons');
	}
}

/* Disable WooCommerce Scripts
/***********************************************************************/
if(!empty($codecleaner_options['disable_woocommerce_scripts']) && $codecleaner_options['disable_woocommerce_scripts'] == "1") {
	add_action('wp_enqueue_scripts', 'codecleaner_disable_woocommerce_scripts', 99);
}

function codecleaner_disable_woocommerce_scripts() {
	if(function_exists('is_woocommerce')) {
		if(!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page() && !is_product() && !is_product_category() && !is_shop()) {
			global $codecleaner_options;
			
			//Dequeue WooCommerce Styles
			wp_dequeue_style('woocommerce-general');
			wp_dequeue_style('woocommerce-layout');
			wp_dequeue_style('woocommerce-smallscreen');
			wp_dequeue_style('woocommerce_frontend_styles');
			wp_dequeue_style('woocommerce_fancybox_styles');
			wp_dequeue_style('woocommerce_chosen_styles');
			wp_dequeue_style('woocommerce_prettyPhoto_css');
			//Dequeue WooCommerce Scripts
			wp_dequeue_script('wc_price_slider');
			wp_dequeue_script('wc-single-product');
			wp_dequeue_script('wc-add-to-cart');
			wp_dequeue_script('wc-checkout');
			wp_dequeue_script('wc-add-to-cart-variation');
			wp_dequeue_script('wc-single-product');
			wp_dequeue_script('wc-cart');
			wp_dequeue_script('wc-chosen');
			wp_dequeue_script('woocommerce');
			wp_dequeue_script('prettyPhoto');
			wp_dequeue_script('prettyPhoto-init');
			wp_dequeue_script('jquery-blockui');
			wp_dequeue_script('jquery-placeholder');
			wp_dequeue_script('fancybox');
			wp_dequeue_script('jqueryui');

			if(empty($codecleaner_options['disable_woocommerce_cart_fragmentation']) || $codecleaner_options['disable_woocommerce_cart_fragmentation'] == "0") {
				wp_dequeue_script('wc-cart-fragments');
			}
		}
	}
}

/* Disable WooCommerce Cart Fragmentation
/***********************************************************************/
if(!empty($codecleaner_options['disable_woocommerce_cart_fragmentation']) && $codecleaner_options['disable_woocommerce_cart_fragmentation'] == "1") {
	add_action('wp_enqueue_scripts', 'codecleaner_disable_woocommerce_cart_fragmentation', 99);
}

function codecleaner_disable_woocommerce_cart_fragmentation() {
	if(function_exists('is_woocommerce')) {
		wp_dequeue_script('wc-cart-fragments');
	}
}

/* Disable Password Strength Meter
/***********************************************************************/
if(!empty($codecleaner_options['disable_password_strength_meter']) && $codecleaner_options['disable_password_strength_meter'] == "1") {
	add_action('wp_print_scripts', 'codecleaner_disable_password_strength_meter', 100);
}

function codecleaner_disable_password_strength_meter() {
	global $wp;

	$wp_check = isset($wp->query_vars['lost-password']) || (isset($_GET['action']) && $_GET['action'] === 'lostpassword') || is_page('lost_password');

	$wc_check = (class_exists('WooCommerce') && (is_account_page() || is_checkout()));

	if(!$wp_check && !$wc_check) {
		if(wp_script_is('zxcvbn-async', 'enqueued')) {
			wp_dequeue_script('zxcvbn-async');
		}

		if(wp_script_is('password-strength-meter', 'enqueued')) {
			wp_dequeue_script('password-strength-meter');
		}

		if(wp_script_is('wc-password-strength-meter', 'enqueued')) {
			wp_dequeue_script('wc-password-strength-meter');
		}
	}
}


/* Disable WooCommerce Status Meta Box
/***********************************************************************/
if(!empty($codecleaner_options['disable_woocommerce_status']) && $codecleaner_options['disable_woocommerce_status'] == "1") {
	add_action('wp_dashboard_setup', 'codecleaner_disable_woocommerce_status');
}

function codecleaner_disable_woocommerce_status() {
	remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
}

/* Disable Emojis
/***********************************************************************/
function codecleaner_disable_emojis() {
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');	
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');	
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('tiny_mce_plugins', 'codecleaner_disable_emojis_tinymce');
	add_filter('wp_resource_hints', 'codecleaner_disable_emojis_dns_prefetch', 10, 2);
	add_filter('emoji_svg_url', '__return_false');
}

function codecleaner_disable_emojis_tinymce($plugins) {
	if(is_array($plugins)) {
		return array_diff($plugins, array('wpemoji'));
	} else {
		return array();
	}
}

function codecleaner_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if('dns-prefetch' == $relation_type) {
		$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2.2.1/svg/');
		$urls = array_diff($urls, array($emoji_svg_url));
	}
	return $urls;
}

/* Disable Embeds
/***********************************************************************/
function codecleaner_disable_embeds() {
	global $wp;
	$wp->public_query_vars = array_diff($wp->public_query_vars, array('embed',));
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'codecleaner_disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'codecleaner_disable_embeds_rewrites' );
	remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}

function codecleaner_disable_embeds_tiny_mce_plugin($plugins) {
	return array_diff($plugins, array('wpembed'));
}

function codecleaner_disable_embeds_rewrites($rules) {
	foreach($rules as $rule => $rewrite) {
		if(false !== strpos($rewrite, 'embed=true')) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}

/* Remove Query Strings
/***********************************************************************/
function codecleaner_remove_query_strings() {
	if(!is_admin()) {
		add_filter('script_loader_src', 'codecleaner_remove_query_strings_split', 15);
		add_filter('style_loader_src', 'codecleaner_remove_query_strings_split', 15);
	}
}

function codecleaner_remove_query_strings_split($src){
	$output = preg_split("/(&ver|\?ver)/", $src);
	return $output[0];
}

/* Remove jQuery Migrate
/***********************************************************************/
function codecleaner_remove_jquery_migrate(&$scripts) {
    if(!is_admin()) {
        $scripts->remove('jquery');
        $scripts->add('jquery', false, array( 'jquery-core' ), '1.12.4');
    }
}

/* Hide WordPress Version
/***********************************************************************/
function codecleaner_hide_wp_version() {
	return '';
}

/* Disable Heartbeat
/***********************************************************************/
function codecleaner_disable_heartbeat() {
	global $codecleaner_options;
	if(!empty($codecleaner_options['disable_heartbeat'])) {
		if($codecleaner_options['disable_heartbeat'] == 'disable_everywhere') {
			wp_deregister_script('heartbeat');
			/*wp_dequeue_script('heartbeat');
			if(is_admin()) {
				wp_register_script('hearbeat', plugins_url('js/heartbeat.js', dirname(__FILE__)));
				wp_enqueue_script('heartbeat', plugins_url('js/heartbeat.js', dirname(__FILE__)));
			}*/
		}
		elseif($codecleaner_options['disable_heartbeat'] == 'allow_posts') {
			global $pagenow;
			if($pagenow != 'post.php' && $pagenow != 'post-new.php') {
				wp_deregister_script('heartbeat');
				/*wp_dequeue_script('heartbeat');
				if(is_admin()) {
					wp_register_script('hearbeat', plugins_url('js/heartbeat.js', dirname(__FILE__)));
					wp_enqueue_script('heartbeat', plugins_url('js/heartbeat.js', dirname(__FILE__)));
				}*/
			}
		}
	}
}

/* Heartbeat Frequency
/***********************************************************************/
function codecleaner_heartbeat_frequency($settings) {
	global $codecleaner_options;
	if(!empty($codecleaner_options['heartbeat_frequency'])) {
		$settings['interval'] = $codecleaner_options['heartbeat_frequency'];
	}
	return $settings;
}

/* Change Login URL
/***********************************************************************/
$codecleaner_wp_login = false;

if(!empty($codecleaner_options['login_url']) && !defined('WP_CLI')) {
	add_action('plugins_loaded', 'codecleaner_plugins_loaded', 2);
	add_action('wp_loaded', 'codecleaner_wp_loaded');
	add_action('setup_theme', 'codecleaner_disable_customize_php', 1);
	add_filter('site_url', 'codecleaner_site_url', 10, 4);
	add_filter('network_site_url', 'codecleaner_network_site_url', 10, 3);
	add_filter('wp_redirect', 'codecleaner_wp_redirect', 10, 2);
	add_filter('site_option_welcome_email', 'codecleaner_welcome_email');
	remove_action('template_redirect', 'wp_redirect_admin_locations', 1000);
}

function codecleaner_site_url($url, $path, $scheme, $blog_id) {
	return codecleaner_filter_wp_login($url, $scheme);
}

function codecleaner_network_site_url($url, $path, $scheme) {
	return codecleaner_filter_wp_login($url, $scheme);
}

function codecleaner_wp_redirect($location, $status) {
	return codecleaner_filter_wp_login($location);
}

function codecleaner_filter_wp_login($url, $scheme = null) {

	//wp-login.php Being Requested
	if(strpos($url, 'wp-login.php') !== false) {

		//Set HTTPS Scheme if SSL
		if(is_ssl()) {
			$scheme = 'https';
		}

		//Check for Query String and Craft New Login URL
		$query_string = explode('?', $url);
		if(isset($query_string[1])) {
			parse_str($query_string[1], $query_string);
			$url = add_query_arg($query_string, codecleaner_login_url($scheme));
		} 
		else {
			$url = codecleaner_login_url($scheme);
		}
	}

	//Return Finished Login URL
	return $url;
}

function codecleaner_login_url($scheme = null) {

	//Return Full New Login URL Based on Permalink Structure
	if(get_option('permalink_structure')) {
		return codecleaner_trailingslashit(home_url('/', $scheme) . codecleaner_login_slug());
	} 
	else {
		return home_url('/', $scheme) . '?' . codecleaner_login_slug();
	}
}

function codecleaner_trailingslashit($string) {

	//Check for Permalink Trailing Slash and Add to String
	if((substr(get_option('permalink_structure'), -1, 1)) === '/') {
		return trailingslashit($string);
	}
	else {
		return untrailingslashit($string);
	}
}

function codecleaner_login_slug() {

	//Declare Global Variable
	global $codecleaner_options;

	//Return Login URL Slug if Available
	if(!empty($codecleaner_options['login_url'])) {
		return $codecleaner_options['login_url'];
	} 
}

function codecleaner_plugins_loaded() {

	//Declare Global Variables
	global $pagenow;
	global $codecleaner_wp_login;

	//Parse Requested URI
	$URI = parse_url($_SERVER['REQUEST_URI']);
	$path = untrailingslashit($URI['path']);
	$slug = codecleaner_login_slug();

	//Non Admin wp-login.php URL
	if(!is_admin() && (strpos(rawurldecode($_SERVER['REQUEST_URI']), 'wp-login.php') !== false || $path === site_url('wp-login', 'relative'))) {

		//Set Flag
		$codecleaner_wp_login = true;

		//Prevent Redirect to Hidden Login
		$_SERVER['REQUEST_URI'] = codecleaner_trailingslashit('/' . str_repeat('-/', 10));
		$pagenow = 'index.php';
	} 
	//Hidden Login URL
	elseif($path === home_url($slug, 'relative') || (!get_option('permalink_structure') && isset($_GET[$slug]) && empty($_GET[$slug]))) {
		
		//Override Current Page w/ wp-login.php
		$pagenow = 'wp-login.php';
	}
}

function codecleaner_wp_loaded() {

	//Declare Global Variables
	global $pagenow;
	global $codecleaner_wp_login;

	//Parse Requested URI
	$URI = parse_url($_SERVER['REQUEST_URI']);

	//Disable Normal WP-Admin
	if(is_admin() && !is_user_logged_in() && !defined('DOING_AJAX') && $pagenow !== 'admin-post.php' && (isset($_GET) && empty($_GET['adminhash']) && empty($_GET['newuseremail']))) {
        wp_die(__('This has been disabled.', 'codecleaner'), 403);
	}

	//Requesting Hidden Login Form - Path Mismatch
	if($pagenow === 'wp-login.php' && $URI['path'] !== codecleaner_trailingslashit($URI['path']) && get_option('permalink_structure')) {

		//Local Redirect to Hidden Login URL
		$URL = codecleaner_trailingslashit(codecleaner_login_url()) . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
		wp_safe_redirect($URL);
		die();
	}
	//Requesting wp-login.php Directly, Disabled
	elseif($codecleaner_wp_login) {
		wp_die(__('This has been disabled.', 'codecleaner'), 403);
	} 
	//Requesting Hidden Login Form
	elseif($pagenow === 'wp-login.php') {

		//Declare Global Variables
		global $error, $interim_login, $action, $user_login;
		
		//User Already Logged In
		if(is_user_logged_in() && !isset($_REQUEST['action'])) {
			wp_safe_redirect(admin_url());
			die();
		}

		//Include Login Form
		@require_once ABSPATH . 'wp-login.php';
		die();
	}
}

function codecleaner_disable_customize_php() {

	//Declare Global Variable
	global $pagenow;

	//Disable customize.php from Redirecting to Login URL
	if(!is_user_logged_in() && $pagenow === 'customize.php') {
		wp_die(__('This has been disabled.', 'codecleaner'), 403);
	}
}

function codecleaner_welcome_email($value) {

	//Declare Global Variable
	global $codecleaner_options;

	//Check for Custom Login URL and Replace
	if(!empty($codecleaner_options['login_url'])) {
		$value = str_replace(array('wp-login.php', 'wp-admin'), trailingslashit($codecleaner_options['login_url']), $value);
	}

	return $value;
}

/* CDN Rewrite URLs
/***********************************************************************/
if(!empty($codecleaner_cdn['enable_cdn']) && $codecleaner_cdn['enable_cdn'] == "1" && !empty($codecleaner_cdn['cdn_url'])) {
	add_action('template_redirect', 'codecleaner_cdn_rewrite');
}

function codecleaner_cdn_rewrite() {
	ob_start('codecleaner_cdn_rewriter');
}

function codecleaner_cdn_rewriter($html) {
	global $codecleaner_cdn;

	//Prep Site URL
    $escapedSiteURL = quotemeta(get_option('home'));
	$regExURL = '(https?:|)' . substr($escapedSiteURL, strpos($escapedSiteURL, '//'));

	//Prep Included Directories
	$directories = 'wp\-content|wp\-includes';
	if(!empty($codecleaner_cdn['cdn_directories'])) {
		$directoriesArray = array_map('trim', explode(',', $codecleaner_cdn['cdn_directories']));
		if(count($directoriesArray) > 0) {
			$directories = implode('|', array_map('quotemeta', array_filter($directoriesArray)));
		}
	}
  
  	//Rewrite URLs + Return
	$regEx = '#(?<=[(\"\'])(?:' . $regExURL . ')?/(?:((?:' . $directories . ')[^\"\')]+)|([^/\"\']+\.[^/\"\')]+))(?=[\"\')])#';
	$cdnHTML = preg_replace_callback($regEx, 'codecleaner_cdn_rewrite_url', $html);
	return $cdnHTML;
}

function codecleaner_cdn_rewrite_url($url) {
	global $codecleaner_cdn;

	//Make Sure CDN URL is Set
	if(!empty($codecleaner_cdn['cdn_url'])) {

		//Don't Rewrite if Excluded
		if(!empty($codecleaner_cdn['cdn_exclusions'])) {
			$exclusions = array_map('trim', explode(',', $codecleaner_cdn['cdn_exclusions']));
			foreach($exclusions as $exclusion) {
	            if(!empty($exclusion) && stristr($url[0], $exclusion) != false) {
	                return $url[0];
	            }
	        }
		} 

	    //Don't Rewrite if Previewing
	    if(is_admin_bar_showing() && isset($_GET['preview']) && $_GET['preview'] == 'true') {
	        return $url[0];
	    }

	    //Prep Site URL
	    $siteURL = get_option('home');
	    $siteURL = substr($siteURL, strpos($siteURL, '//'));

	    //Replace URL w/ No HTTP/S Prefix
	    if(strpos($url[0], '//') === 0) {
	        return str_replace($siteURL, $codecleaner_cdn['cdn_url'], $url[0]);
	    }

	    //Found Site URL, Replace Non Relative URL w/ HTTP/S Prefix
	    if(strstr($url[0], $siteURL)) {
	        return str_replace(array('http:' . $siteURL, 'https:' . $siteURL), $codecleaner_cdn['cdn_url'], $url[0]);
	    }

	    //Replace Relative URL
    	return $codecleaner_cdn['cdn_url'] . $url[0];
    }

    //Return Original URL
    return $url[0];
}

//update analytics.js
function cleadcoded_update_ga() {
	//paths
	$local_file = dirname(dirname(__FILE__)) . '/js/analytics.js';
	$host = 'www.google-analytics.com';
	$path = '/analytics.js';

	//open connection
	$fp = @fsockopen($host, '80', $errno, $errstr, 10);

	if($fp){	
		//send headers
		$header = "GET $path HTTP/1.0\r\n";
		$header.= "Host: $host\r\n";
		$header.= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6\r\n";
		$header.= "Accept: */*\r\n";
		$header.= "Accept-Language: en-us,en;q=0.5\r\n";
		$header.= "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n";
		$header.= "Keep-Alive: 300\r\n";
		$header.= "Connection: keep-alive\r\n";
		$header.= "Referer: https://$host\r\n\r\n";
		fwrite($fp, $header);
		$response = '';
		
		//get response
		while($line = fread($fp, 4096)) {
			$response.= $line;
		}

		//close connection
		fclose($fp);

		//remove headers
		$position = strpos($response, "\r\n\r\n");
		$response = substr($response, $position + 4);

		//create file if needed
		if(!file_exists($local_file)) {
			fopen($local_file, 'w');
		}

		//write response to file
		if(is_writable($local_file)) {
			if($fp = fopen($local_file, 'w')) {
				fwrite($fp, $response);
				fclose($fp);
			}
		}
	}
}
add_action('cleadcoded_update_ga', 'cleadcoded_update_ga');

/* Google Analytics
/***********************************************************************/

//enable/disable local analytics scheduled event
if(!empty($codecleaner_ga['enable_local_ga']) && $codecleaner_ga['enable_local_ga'] == "1") {
	if(!wp_next_scheduled('cleadcoded_update_ga')) {
		wp_schedule_event(time(), 'daily', 'cleadcoded_update_ga');
	}

	if(!empty($codecleaner_ga['use_monster_insights']) && $codecleaner_ga['use_monster_insights'] == "1") {
		add_filter('monsterinsights_frontend_output_analytics_src', 'codecleaner_monster_ga', 1000);
	}
	else {
		if(!empty($codecleaner_ga['tracking_code_position']) && $codecleaner_ga['tracking_code_position'] == 'footer') {
			$tracking_code_position = 'wp_footer';
		}
		else {
			$tracking_code_position = 'wp_head';
		}
		add_action($tracking_code_position, 'cleadcoded_print_ga', 0);
	}
}
else {
	if(wp_next_scheduled('cleadcoded_update_ga')) {
		wp_clear_scheduled_hook('cleadcoded_update_ga');
	}
}

//print analytics script
function cleadcoded_print_ga() {
	global $codecleaner_ga;

	//dont print for logged in admins
	if(current_user_can('manage_options') && empty($codecleaner_ga['track_admins'])) {
		return;
	}

	if(!empty($codecleaner_ga['tracking_id'])) {
		echo "<!-- Local Analytics generated with codecleaner. -->";
		echo "<script>";
		    echo "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','" . plugins_url() . "/codecleaner/js/analytics.js','ga');";
		    echo "ga('create', '" . $codecleaner_ga['tracking_id'] . "', 'auto');";

		    //disable display features
		    if(!empty($codecleaner_ga['disable_display_features']) && $codecleaner_ga['disable_display_features'] == "1") {
		    	echo "ga('set', 'allowAdFeatures', false);";
		    }

		    //anonymize ip
		   	if(!empty($codecleaner_ga['anonymize_ip']) && $codecleaner_ga['anonymize_ip'] == "1") {
		   		echo "ga('set', 'anonymizeIp', true);";
		   	}

		    echo "ga('send', 'pageview');";

		    //adjusted bounce rate
		    if(!empty($codecleaner_ga['adjusted_bounce_rate'])) {
		    	echo 'setTimeout("ga(' . "'send','event','adjusted bounce rate','" . $codecleaner_ga['adjusted_bounce_rate'] . " seconds')" . '"' . "," . $codecleaner_ga['adjusted_bounce_rate'] * 1000 . ");";
		    }
	    echo "</script>";
	}
}

//return local anlytics url for Monster Insights
function codecleaner_monster_ga($url) {
	return plugins_url() . "/codecleaner/js/analytics.js";
}

/* Script Manager
/***********************************************************************/

//Script Manager Admin Bar Link
function codecleaner_script_manager_admin_bar($wp_admin_bar) {
	if(!current_user_can('manage_options') || is_admin() || !codecleaner_network_access()) {
		return;
	}

	global $wp;

	$href = add_query_arg(str_replace(array('&codecleaner', 'codecleaner'), '', $_SERVER['QUERY_STRING']), '', home_url($wp->request));

	if(!isset($_GET['codecleaner'])) {
		$href.= !empty($_SERVER['QUERY_STRING']) ? '&codecleaner' : '?codecleaner';
		$menu_text = __('Script Manager', 'codecleaner');
	}
	else {
		$menu_text = __('Close Script Manager', 'codecleaner');
	}

	$args = array(
		'id'    => 'codecleaner_script_manager',
		'title' => $menu_text,
		'href'  => $href
	);
	$wp_admin_bar->add_node($args);
}

//Script Manager Front End
function codecleaner_script_manager() {
	require_once('script_manager.php');
}

//Script Manager Scripts
function codecleaner_script_manager_scripts() {
	if(!current_user_can('manage_options') || is_admin() || !isset($_GET['codecleaner']) || !codecleaner_network_access()) {
		return;
	}
	wp_register_script('codecleaner-script-manager-js', plugins_url('js/script-manager.js', dirname(__FILE__)), array('jquery-core'), CODECLEANER_VERSION);
	wp_enqueue_script('codecleaner-script-manager-js');
}

function codecleaner_script_manager_load_master_array() {

	if(!function_exists('get_plugins')) {
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
	}

	global $wp_scripts;
	global $wp_styles;

	$master_array = array('plugins' => array(), 'themes' => array(), 'misc' => array());

	$codecleaner_filters = array(
		"js" => array (
			"title" => "JS",
			"scripts" => $wp_scripts
		),
		"css" => array(
			"title" => "CSS",
			"scripts" => $wp_styles
		)
	);

	$loaded_plugins = array();
	$loaded_themes = array();

	foreach($codecleaner_filters as $type => $data) {

		if(!empty($data["scripts"]->done)) {
			$plug_org_scripts = $data["scripts"]->done;

			uasort($plug_org_scripts, function($a, $b) use ($type) {
				global $codecleaner_filters;
			    if($codecleaner_filters[$type]['scripts']->registered[$a]->src == $codecleaner_filters[$type]['scripts']->registered[$b]->src) {
			        return 0;
			    }
			    return ($codecleaner_filters[$type]['scripts']->registered[$a]->src < $codecleaner_filters[$type]['scripts']->registered[$b]->src) ? -1 : 1;
			});

			foreach($plug_org_scripts as $key => $val) {
				$src = $codecleaner_filters[$type]['scripts']->registered[$val]->src;

				if(strpos($src, "/wp-content/plugins/") !== false) {
					$explode = explode("/wp-content/plugins/", $src);
					$explode = explode('/', $explode[1]);
					if(!array_key_exists($explode[0], $loaded_plugins)) {
						$file_plugin = get_plugins('/' . $explode[0]);
						$loaded_plugins[$explode[0]] = $file_plugin;
						$master_array['plugins'][$explode[0]] = array('name' => $file_plugin[key($file_plugin)]['Name']);
					}
					else {
						$file_plugin = $loaded_plugins[$explode[0]];
					}
			    	$master_array['plugins'][$explode[0]]['assets'][] = array('type' => $type, 'handle' => $val);
			    }
			    elseif(strpos($src, "/wp-content/themes/") !== false) {
					$explode = explode("/wp-content/themes/", $src);
					$explode = explode('/', $explode[1]);
					if(!array_key_exists($explode[0], $loaded_themes)) {
						$file_theme = wp_get_theme('/' . $explode[0]);
						$loaded_themes[$explode[0]] = $file_theme;
						$master_array['themes'][$explode[0]] = array('name' => $file_theme->get('Name'));
					}
					else {
						$file_theme = $loaded_themes[$explode[0]];
					}
					
			    	$master_array['themes'][$explode[0]]['assets'][] = array('type' => $type, 'handle' => $val);
			    }
			    else {
			    	$master_array['misc'][] = array('type' => $type, 'handle' => $val);
			    }
			}
		}
	}
	if(isset($master_array['plugins']['codecleaner'])) {
		unset($master_array['plugins']['codecleaner']);
	}
	return $master_array;
}

function codecleaner_script_manager_print_section($category, $group, $scripts) {
	global $codecleaner_script_manager_options;
	global $currentID;

	$options = $codecleaner_script_manager_options;

	$statusDisabled = false;
	if(isset($options['disabled'][$category][$group]['everywhere']) || (isset($options['disabled'][$category][$group]['current']) && in_array($currentID, $options['disabled'][$category][$group]['current']))) {
		$statusDisabled = true;
	}

	echo "<div class='codecleaner-script-manager-section'>";
		echo "<table " . ($statusDisabled ? "style='display: none;'" : "") . ">";
			echo "<thead>";
				echo "<tr>";
					echo "<th style='width: 120px;'>" . __('Status', 'codecleaner') . "</th>";
					echo "<th style=''>" . __('Script', 'codecleaner') . "</th>";
					echo "<th style='width: 100px; text-align: center;'>" . __('Type', 'codecleaner') . "</th>";
					echo "<th style='width: 100px; text-align: center;'>" . __('Size', 'codecleaner') . "</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
				foreach($scripts as $key => $details) {
					codecleaner_script_manager_print_script($category, $group, $details['handle'], $details['type']);
				}
			echo "</tbody>";
		echo "</table>";

		if($category != "misc") {
			
			echo "<div class='codecleaner-script-manager-assets-disabled' " . (!$statusDisabled ? "style='display: none;'" : "") . ">";

				echo "<div class='codecleaner-script-manager-controls'>";

					//Disable
					codecleaner_script_manager_print_disable($category, $group);

					//Enable
					codecleaner_script_manager_print_enable($category, $group);

				echo "</div>";

				echo "<p>All assets in this group have been disabled. Please enable the group to individually manage assets.</p>";
			echo "</div>";
		}
	echo "</div>";
}
 
function codecleaner_script_manager_print_status($type, $handle) {
	global $codecleaner_extras;
	global $codecleaner_script_manager_options;
	global $currentID;
	$options = $codecleaner_script_manager_options;

	global $statusDisabled;
 
	$statusDisabled = false;
	if(isset($options['disabled'][$type][$handle]['everywhere']) || (isset($options['disabled'][$type][$handle]['current']) && in_array($currentID, $options['disabled'][$type][$handle]['current']))) {
		$statusDisabled = true;
	} 
	if(!empty($codecleaner_extras['accessibility_mode']) && $codecleaner_extras['accessibility_mode'] == "1") {
		echo "<select name='status[" . $type . "][" . $handle . "]' class='codecleaner-status-select" . ($statusDisabled ? "disabled" : "") . "'>";
			echo "<option value='enabled' class='codecleaner-option-enabled'>" . __('ON', 'codecleaner') . "</option>";
			echo "<option value='disabled' class='codecleaner-option-everywhere' " . ($statusDisabled ? "selected" : "") . ">" . __('OFF', 'codecleaner') . "</option>";
		echo "</select>";
	}
	else {
		echo "<input type='hidden' name='status[" . $type . "][" . $handle . "]' value='enabled' />";
        echo "<label for='status_" . $type . "_" . $handle . "' class='codecleaner-script-manager-switch'>";
        	echo "<input type='checkbox' id='status_" . $type . "_" . $handle . "' name='status[" . $type . "][" . $handle . "]' value='disabled' " . ($statusDisabled ? "checked" : "") . " class='codecleaner-status-toggle'>";
        	echo "<div class='codecleaner-script-manager-slider'></div>";
       	echo "</label>";
	}
}


function codecleaner_script_manager_print_script($category, $group, $script, $type) {
 
	global $codecleaner_extras;
	global $codecleaner_script_manager_settings;
	global $codecleaner_filters;
	global $codecleaner_disables;
	global $codecleaner_script_manager_options;
	global $currentID;
	global $statusDisabled;
	global $pmsm_jquery_disabled;

	$options = $codecleaner_script_manager_options;

	$data = $codecleaner_filters[$type];

	if(!empty($data["scripts"]->registered[$script]->src)) {
 
		//Check for disables already set
		if(!empty($codecleaner_disables)) {
			foreach($codecleaner_disables as $key => $val) {
				if(strpos($data["scripts"]->registered[$script]->src, $val) !== false) {
					//continue 2;
					return;
				}
			}
		} 

		$handle = $data["scripts"]->registered[$script]->handle;
		echo "<tr>";	

			//Status
			echo "<td class='codecleaner-script-manager-status'>";

				codecleaner_script_manager_print_status($type, $handle);

			echo "</td>";

			//Script Cell
			echo "<td class='codecleaner-script-manager-script'>";

				//Script Handle
				echo "<span>" . $handle . "</span>";

				//Script Path
				echo "<a href='" . $data["scripts"]->registered[$script]->src . "' target='_blank'>" . str_replace(get_home_url(), '', $data["scripts"]->registered[$script]->src) . "</a>";

				echo "<div class='codecleaner-script-manager-controls' " . (!$statusDisabled ? "style='display: none;'" : "") . ">";

					//Disable
					codecleaner_script_manager_print_disable($type, $handle);

					//Enable
					codecleaner_script_manager_print_enable($type, $handle);

				echo "</div>";

				if($category != "misc") {
					echo "<input type='hidden' name='relations[" . $type . "][" . $handle . "][category]' value='" . $category . "' />";
					echo "<input type='hidden' name='relations[" . $type . "][" . $handle . "][group]' value='" . $group . "' />";
				}

				//jquery override message
				if($type == 'js' && $handle == 'jquery-core' && $pmsm_jquery_disabled) {
					echo "<div id='jquery-message'>jQuery has been temporarily enabled in order for the Script Manager to function properly.</div>";
				}
				 
			echo "</td>";
  
			//Type
			echo "<td class='codecleaner-script-manager-type'>";
				if(!empty($type)) {
					echo $type;
				}
			echo "</td>";
 
			//Size					
			echo "<td class='codecleaner-script-manager-size'>";
				if(file_exists(ABSPATH . str_replace(get_home_url(), '', $data["scripts"]->registered[$script]->src))) {
					echo round(filesize(ABSPATH . str_replace(get_home_url(), '', $data["scripts"]->registered[$script]->src)) / 1024, 1 ) . ' KB';
				}
			echo "</td>";

		echo "</tr>";
  
	} 
} 

function codecleaner_script_manager_print_enable($type, $handle) {
	global $codecleaner_script_manager_settings;
	global $codecleaner_script_manager_options;
	global $currentID;
	$options = $codecleaner_script_manager_options;

	echo "<div class='codecleaner-script-manager-enable'"; if(empty($options['disabled'][$type][$handle]['everywhere'])) { echo " style='display: none;'"; } echo">";

		echo "<div style='font-size: 16px;'>" . __('Exceptions', 'codecleaner') . "</div>";

		//Current URL
		echo "<input type='hidden' name='enabled[" . $type . "][" . $handle . "][current]' value='' />";
		echo "<label for='" . $type . "-" . $handle . "-enable-current'>";
			echo "<input type='checkbox' name='enabled[" . $type . "][" . $handle . "][current]' id='" . $type . "-" . $handle . "-enable-current' value='" . $currentID ."' ";
				if(isset($options['enabled'][$type][$handle]['current'])) {
					if(in_array($currentID, $options['enabled'][$type][$handle]['current'])) {
						echo "checked";
					}
				}
			echo " />" . __('Current URL', 'codecleaner');
		echo "</label>";

		//Post Types
		echo "<span style='display: block; font-size: 10px; font-weight: bold; margin: 0px;'>Post Types:</span>";
		$post_types = get_post_types(array('public' => true), 'objects', 'and');
		if(!empty($post_types)) {
			if(isset($post_types['attachment'])) {
				unset($post_types['attachment']);
			}
			echo "<input type='hidden' name='enabled[" . $type . "][" . $handle . "][post_types]' value='' />";
			foreach($post_types as $key => $value) {
				echo "<label for='" . $type . "-" . $handle . "-enable-" . $key . "'>";
					echo "<input type='checkbox' name='enabled[" . $type . "][" . $handle . "][post_types][]' id='" . $type . "-" . $handle . "-enable-" . $key . "' value='" . $key ."' ";
						if(isset($options['enabled'][$type][$handle]['post_types'])) {
							if(in_array($key, $options['enabled'][$type][$handle]['post_types'])) {
								echo "checked";
							}
						}
					echo " />" . $value->label;
				echo "</label>";
			}
		}

		//Archives
		if(!empty($codecleaner_script_manager_settings['separate_archives']) && $codecleaner_script_manager_settings['separate_archives'] == "1") {
			echo "<span style='display: block; font-size: 10px; font-weight: bold; margin: 0px;'>Archives:</span>";
			echo "<input type='hidden' name='enabled[" . $type . "][" . $handle . "][archives]' value='' />";

			//Built-In Tax Archives
			//$wp_archives = array('category' => 'Categories', 'post_tag' => 'Tags', 'author' => 'Authors', 'date' => 'Dates');
			$wp_archives = array('category' => 'Categories', 'post_tag' => 'Tags', 'author' => 'Authors');
			foreach($wp_archives as $key => $value) {
				echo "<label for='" . $type . "-" . $handle . "-enable-archive-" . $key . "' title='" . $key . " (WordPress Taxonomy Archive)'>";
					echo "<input type='checkbox' name='enabled[" . $type . "][" . $handle . "][archives][]' id='" . $type . "-" . $handle . "-enable-archive-" . $key . "' value='" . $key ."' ";
						if(isset($options['enabled'][$type][$handle]['archives'])) {
							if(in_array($key, $options['enabled'][$type][$handle]['archives'])) {
								echo "checked";
							}
						}
					echo " />" . $value;
				echo "</label>";
			}

			//Custom Tax Archives
			$taxonomies = get_taxonomies(array('public' => true, '_builtin' => false), 'objects', 'and');
			if(!empty($taxonomies)) {
				foreach($taxonomies as $key => $value) {
					echo "<label for='" . $type . "-" . $handle . "-enable-archive-" . $key . "' title='" . $key . " (Custom Taxonomy Archive)'>";
						echo "<input type='checkbox' name='enabled[" . $type . "][" . $handle . "][archives][]' id='" . $type . "-" . $handle . "-enable-archive-" . $key . "' value='" . $key ."' ";
							if(isset($options['enabled'][$type][$handle]['archives'])) {
								if(in_array($key, $options['enabled'][$type][$handle]['archives'])) {
									echo "checked";
								}
							}
						echo " />" . $value->label;
					echo "</label>";
				}
			}

			//Post Type Archives
			$archive_post_types = get_post_types(array('public' => true, 'has_archive' => true), 'objects', 'and');
			if(!empty($archive_post_types)) {
				foreach($archive_post_types as $key => $value) {
					echo "<label for='" . $type . "-" . $handle . "-enable-archive-" . $key . "' title='" . $key . " (Post Type Archive)'>";
						echo "<input type='checkbox' name='enabled[" . $type . "][" . $handle . "][archives][]' id='" . $type . "-" . $handle . "-enable-archive-" . $key . "' value='" . $key ."' ";
							if(isset($options['enabled'][$type][$handle]['archives'])) {
								if(in_array($key, $options['enabled'][$type][$handle]['archives'])) {
									echo "checked";
								}
							}
						echo " />" . $value->label;
					echo "</label>";
				}
			}
		}

	echo "</div>";
}

function codecleaner_script_manager_update() {

	if(isset($_GET['codecleaner']) && !empty($_POST['codecleaner_script_manager'])) {

		$currentID = get_queried_object_id();

		$codecleaner_filters = array("js", "css", "plugins", "themes");

		$options = get_option('codecleaner_script_manager');
		$settings = get_option('codecleaner_script_manager_settings');

		foreach($codecleaner_filters as $type) {

			if(isset($_POST['disabled'][$type])) {

				foreach($_POST['disabled'][$type] as $handle => $value) {

					$groupDisabled = false;
					if(isset($_POST['relations'][$type][$handle])) {
						$relationInfo = $_POST['relations'][$type][$handle];
						if($_POST['status'][$relationInfo['category']][$relationInfo['group']] == "disabled" && isset($_POST['disabled'][$relationInfo['category']][$relationInfo['group']])) {
							$groupDisabled = true;
						}
					}

					if(!$groupDisabled && $_POST['status'][$type][$handle] == 'disabled' && !empty($value)) {
						if($value == "everywhere") {
							$options['disabled'][$type][$handle]['everywhere'] = 1;
							if(!empty($options['disabled'][$type][$handle]['current'])) {
								unset($options['disabled'][$type][$handle]['current']);
							}
						}
						elseif($value == "current") {
							if(isset($options['disabled'][$type][$handle]['everywhere'])) {
								unset($options['disabled'][$type][$handle]['everywhere']);
							}
							if(!is_array($options['disabled'][$type][$handle]['current'])) {
								$options['disabled'][$type][$handle]['current'] = array();
							}
							if(!in_array($currentID, $options['disabled'][$type][$handle]['current'])) {
								array_push($options['disabled'][$type][$handle]['current'], $currentID);
							}
						}
					}
					else {
						unset($options['disabled'][$type][$handle]['everywhere']);
						if(isset($options['disabled'][$type][$handle]['current'])) {
							$current_key = array_search($currentID, $options['disabled'][$type][$handle]['current']);
							if($current_key !== false) {
								unset($options['disabled'][$type][$handle]['current'][$current_key]);
								if(empty($options['disabled'][$type][$handle]['current'])) {
									unset($options['disabled'][$type][$handle]['current']);
								}
							}
						}
					}

					if(empty($options['disabled'][$type][$handle])) {
						unset($options['disabled'][$type][$handle]);
						if(empty($options['disabled'][$type])) {
							unset($options['disabled'][$type]);
							if(empty($options['disabled'])) {
								unset($options['disabled']);
							}
						}
					}
				}
			}

			if(isset($_POST['enabled'][$type])) {

				foreach($_POST['enabled'][$type] as $handle => $value) {

					$groupDisabled = false;
					if(isset($_POST['relations'][$type][$handle])) {
						$relationInfo = $_POST['relations'][$type][$handle];
						if($_POST['status'][$relationInfo['category']][$relationInfo['group']] == "disabled" && isset($_POST['disabled'][$relationInfo['category']][$relationInfo['group']])) {
							$groupDisabled = true;
						}
					}

					if(!$groupDisabled && $_POST['status'][$type][$handle] == 'disabled' && (!empty($value['current']) || $value['current'] === "0")) {
						if(!is_array($options['enabled'][$type][$handle]['current'])) {
							$options['enabled'][$type][$handle]['current'] = array();
						}
						if(!in_array($value['current'], $options['enabled'][$type][$handle]['current'])) {
							array_push($options['enabled'][$type][$handle]['current'], $value['current']);
						}
					}
					else {
						if(isset($options['enabled'][$type][$handle]['current'])) {
							$current_key = array_search($currentID, $options['enabled'][$type][$handle]['current']);
							if($current_key !== false) {
								unset($options['enabled'][$type][$handle]['current'][$current_key]);
								if(empty($options['enabled'][$type][$handle]['current'])) {
									unset($options['enabled'][$type][$handle]['current']);
								}
							}
						}
					}

					if(!$groupDisabled && $_POST['status'][$type][$handle] == 'disabled' && !empty($value['post_types'])) {
						$options['enabled'][$type][$handle]['post_types'] = array();
						foreach($value['post_types'] as $key => $post_type) {
							if(isset($options['enabled'][$type][$handle]['post_types'])) {
								if(!in_array($post_type, $options['enabled'][$type][$handle]['post_types'])) {
									array_push($options['enabled'][$type][$handle]['post_types'], $post_type);
								}
							}
						}
					}
					else {
						unset($options['enabled'][$type][$handle]['post_types']);
					}

					//filter out empty child arrays
					if(!empty($settings['separate_archives']) && $settings['separate_archives'] == "1") {
						$value['archives'] = array_filter($value['archives']);
						if(!$groupDisabled && $_POST['status'][$type][$handle] == 'disabled' && !empty($value['archives'])) {
							$options['enabled'][$type][$handle]['archives'] = array();
							foreach($value['archives'] as $key => $archive) {
								if(!in_array($archive, $options['enabled'][$type][$handle]['archives'])) {
									array_push($options['enabled'][$type][$handle]['archives'], $archive);
								}
							}
						}
						else {
							unset($options['enabled'][$type][$handle]['archives']);
						}
					}

					if(empty($options['enabled'][$type][$handle])) {
						unset($options['enabled'][$type][$handle]);
						if(empty($options['enabled'][$type])) {
							unset($options['enabled'][$type]);
							if(empty($options['enabled'])) {
								unset($options['enabled']);
							}
						}
					}
				}
			}
		}
		update_option('codecleaner_script_manager', $options);
	}
}

function codecleaner_dequeue_scripts($src, $handle) {
	if(is_admin()) {
		return $src;
	}

	//get script type
	$type = current_filter() == 'script_loader_src' ? "js" : "css";

	//load options
	$options = get_option('codecleaner_script_manager');
	$settings = get_option('codecleaner_script_manager_settings');
	$currentID = get_queried_object_id();

	//get category + group from src
	preg_match('/\/wp-content\/(.*?\/.*?)\//', $src, $match);
	if(!empty($match[1])) {
		$match = explode("/", $match[1]);
		$category = $match[0];
		$group = $match[1];
	}

	//check for group disable settings and override
	if(!empty($category) && !empty($group) && isset($options['disabled'][$category][$group])) {
		$type = $category;
		$handle = $group;
	}

	//disable is set, check options
	if((!empty($options['disabled'][$type][$handle]['everywhere']) && $options['disabled'][$type][$handle]['everywhere'] == 1) || (!empty($options['disabled'][$type][$handle]['current']) && in_array($currentID, $options['disabled'][$type][$handle]['current']))) {

		if($handle == 'jquery-core' && $type == 'js' && isset($_GET['codecleaner']) && current_user_can('manage_options')) {
			global $pmsm_jquery_disabled;
			$pmsm_jquery_disabled = true;
			return $src;
		}
	
		if(!empty($options['enabled'][$type][$handle]['current']) && in_array($currentID, $options['enabled'][$type][$handle]['current'])) {
			return $src;
		}

		if(!empty($settings['separate_archives']) && $settings['separate_archives'] == "1") {
			if(is_archive()) {
				$object = get_queried_object();
				$objectClass = get_class($object);
				if($objectClass == "WP_Post_Type") {
					if(!empty($options['enabled'][$type][$handle]['archives']) && in_array($object->name, $options['enabled'][$type][$handle]['archives'])) {
						return $src;
					}
					else {
						return false;
					}
				}
				elseif($objectClass == "WP_User")
				{
					if(!empty($options['enabled'][$type][$handle]['archives']) && in_array("author", $options['enabled'][$type][$handle]['archives'])) {
						return $src;
					}
					else {
						return false;
					}
				}
				else {
					if(!empty($options['enabled'][$type][$handle]['archives']) && in_array($object->taxonomy, $options['enabled'][$type][$handle]['archives'])) {
						return $src;
					}
					else {
						return false;
					}
				}
			}
		}

		if(is_front_page() || is_home()) {
			if(get_option('show_on_front') == 'page' && !empty($options['enabled'][$type][$handle]['post_types']) && in_array('page', $options['enabled'][$type][$handle]['post_types'])) {
				return $src;
			}
		}
		else {
			if(!empty($options['enabled'][$type][$handle]['post_types']) && in_array(get_post_type(), $options['enabled'][$type][$handle]['post_types'])) {
				return $src;
			}
		}

		return false;
	}

	//original script src
	return $src;
}

function codecleaner_script_manager_print_disable($type, $handle) {
	global $codecleaner_script_manager_options;
	global $currentID;
	$options = $codecleaner_script_manager_options;

	echo "<div class='codecleaner-script-manager-disable'>";
		echo "<div style='font-size: 16px;'>" . __('Disabled', 'codecleaner') . "</div>";
		echo "<label for='disabled-" . $type . "-" . $handle . "-everywhere'>";
			echo "<input type='radio' name='disabled[" . $type . "][" . $handle . "]' id='disabled-" . $type . "-" . $handle . "-everywhere' class='codecleaner-disable-select' value='everywhere' ";
			echo (!empty($options['disabled'][$type][$handle]['everywhere']) ? "checked" : "");
			echo " />";
			echo __('Everywhere', 'codecleaner');
		echo "</label>";

		echo "<label for='disabled-" . $type . "-" . $handle . "-current'>";
			echo "<input type='radio' name='disabled[" . $type . "][" . $handle . "]' id='disabled-" . $type . "-" . $handle . "-current' class='codecleaner-disable-select' value='current' ";
			echo (isset($options['disabled'][$type][$handle]['current']) && in_array($currentID, $options['disabled'][$type][$handle]['current']) ? "checked" : "");
			echo " />";
			echo __('Current URL', 'codecleaner');
		echo "</label>";
	echo "</div>";
}



/* Preconnect
/***********************************************************************/
if(!empty($codecleaner_extras['preconnect'])) {
	add_action('wp_head', 'codecleaner_preconnect', 1);
}

function codecleaner_preconnect() {
	global $codecleaner_extras;
	if(!empty($codecleaner_extras['preconnect']) && is_array($codecleaner_extras['preconnect'])) {
		foreach($codecleaner_extras['preconnect'] as $url) {
			echo "<link rel='preconnect' href='" . $url . "' crossorigin>" . "\n";
		}
	}
}

/* EDD License Functions
/***********************************************************************/
function codecleaner_edd_activate_license() {

	//listen for our activate button to be clicked
	if(isset($_POST['codecleaner_edd_license_activate'])) {

		//run a quick security check
	 	if(!check_admin_referer('codecleaner_edd_nonce', 'codecleaner_edd_nonce')) {
			return; // get out if we didn't click the Activate button
	 	}

		//retrieve the license from the database
		$license = trim( get_option('codecleaner_edd_license_key'));

		//data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode(CODECLEANER_ITEM_NAME), // the name of our product in EDD
			'url'       => home_url()
		);

		//Call the custom API.
		$response = wp_remote_post(CODECLEANER_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

		//make sure the response came back okay
		if(is_wp_error($response)) {
			return false;
		}

		//decode the license data
		$license_data = json_decode(wp_remote_retrieve_body($response));

		//$license_data->license will be either "valid" or "invalid"
		update_option('codecleaner_edd_license_status', $license_data->license);
	}
}
add_action('admin_init', 'codecleaner_edd_activate_license');

function codecleaner_edd_deactivate_license() {

	// listen for our activate button to be clicked
	if(isset($_POST['codecleaner_edd_license_deactivate'])) {

		// run a quick security check
	 	if(!check_admin_referer('codecleaner_edd_nonce', 'codecleaner_edd_nonce')) {
			return; // get out if we didn't click the Activate button
	 	}

		// retrieve the license from the database
		$license = trim( get_option('codecleaner_edd_license_key'));

		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode(CODECLEANER_ITEM_NAME), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post(CODECLEANER_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

		// make sure the response came back okay
		if(is_wp_error($response)) {
			return false;
		}

		// decode the license data
		$license_data = json_decode(wp_remote_retrieve_body($response));

		// $license_data->license will be either "deactivated" or "failed"
		if($license_data->license == 'deactivated') {
			delete_option('codecleaner_edd_license_status');
		}
	}
}
add_action('admin_init', 'codecleaner_edd_deactivate_license');

function codecleaner_edd_check_license() {

	global $wp_version;

	$license = trim(get_option('codecleaner_edd_license_key'));

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode(CODECLEANER_ITEM_NAME),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(CODECLEANER_STORE_URL, array('timeout' => 15, 'sslverify' => true, 'body' => $api_params));

	if(is_wp_error($response)) {
		return false;
	}

	$license_data = json_decode(wp_remote_retrieve_body($response));

	if($license_data->license == 'valid') {
		update_option('codecleaner_edd_license_status', "valid");
	}
	else {
		update_option('codecleaner_edd_license_status', "invalid");
	}
	
	return($license_data);
}

/* DNS Prefetch
/***********************************************************************/
function codecleaner_dns_prefetch() {
	global $codecleaner_extras;
	if(!empty($codecleaner_extras['dns_prefetch']) && is_array($codecleaner_extras['dns_prefetch'])) {
		foreach($codecleaner_extras['dns_prefetch'] as $url) {
			echo "<link rel='dns-prefetch' href='" . $url . "'>" . "\n";
		}
	}
}