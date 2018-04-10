<?php
//register settings + options
function codecleanup_settings() {
	if(get_option('codecleanup_options') == false) {	
		add_option('codecleanup_options', apply_filters('codecleanup_default_options', codecleanup_default_options()));
	}

    //Options Primary Section
    add_settings_section('codecleanup_options', 'Options', 'codecleanup_options_callback', 'codecleanup_options');

    //Disable Emojis
    add_settings_field(
    	'disable_emojis', 
    	codecleanup_title('Disable Emojis', 'disable_emojis') . codecleanup_tooltip('https://codecleanup.io/docs/disable-emojis-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
            'id' => 'disable_emojis',
            'tooltip' => 'Removes WordPress Emojis JavaScript file (wp-emoji-release.min.js).'
        )
    );

    //Disable Embeds
    add_settings_field(
    	'disable_embeds', 
    	codecleanup_title('Disable Embeds', 'disable_embeds') . codecleanup_tooltip('https://codecleanup.io/docs/disable-embeds-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'disable_embeds',
    		'tooltip' => 'Removes WordPress Embed JavaScript file (wp-embed.min.js).'    		
    	)
    );

    //Remove Query Strings
    add_settings_field(
    	'remove_query_strings', 
    	codecleanup_title('Remove Query Strings', 'remove_query_strings') . codecleanup_tooltip('https://codecleanup.io/docs/remove-query-strings-from-static-resources/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_query_strings',
    		'tooltip' => 'Remove query strings from static resources (CSS, JS).'
    	)
    );

    //Query String Parameters
    /*add_settings_field(
        'query_string_parameters', 
        codecleanup_title('Additional Parameters', 'query_string_parameters') . codecleanup_tooltip(''), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_options', 
        array(
            'id' => 'query_string_parameters',
            'input' => 'text',
            'placeholder' => 'v,id',
            'tooltip' => ''
        )
    );*/

	//Disable XML-RPC
    add_settings_field(
    	'disable_xmlrpc', 
    	codecleanup_title('Disable XML-RPC', 'disable_xmlrpc') . codecleanup_tooltip('https://codecleanup.io/docs/disable-xml-rpc-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'disable_xmlrpc',
    		'tooltip' => 'Disables WordPress XML-RPC functionality.'
    	)
    );

	//Remove jQuery Migrate
    add_settings_field(
    	'remove_jquery_migrate', 
    	codecleanup_title('Remove jQuery Migrate', 'remove_jquery_migrate') . codecleanup_tooltip('https://codecleanup.io/docs/remove-jquery-migrate-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_jquery_migrate',
    		'tooltip' => 'Removes jQuery Migrate JavaScript file (jquery-migrate.min.js).'
    	)
    );

    //Hide WP Version
    add_settings_field(
    	'hide_wp_version', 
    	codecleanup_title('Hide WP Version', 'hide_wp_version') . codecleanup_tooltip('https://codecleanup.io/docs/remove-wordpress-version-number/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'hide_wp_version',
    		'tooltip' => 'Removes WordPress version meta tag.'
    	)
    );

    //Remove wlmanifest Link
    add_settings_field(
    	'remove_wlwmanifest_link', 
    	codecleanup_title('Remove wlwmanifest Link', 'remove_wlwmanifest_link') . codecleanup_tooltip('https://codecleanup.io/docs/remove-wlwmanifest-link-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options',
        array(
        	'id' => 'remove_wlwmanifest_link',
        	'tooltip' => 'Remove wlwmanifest (Windows Live Writer) link tag.'
        )
    );

    //Remove RSD Link
    add_settings_field(
    	'remove_rsd_link', 
    	codecleanup_title('Remove RSD Link', 'remove_rsd_link') . codecleanup_tooltip('https://codecleanup.io/docs/remove-rsd-link-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_rsd_link',
    		'tooltip' => 'Remove RSD (Real Simple Discovery) link tag.'
    	)
    );

    //Remove Shortlink
    add_settings_field(
    	'remove_shortlink', 
    	codecleanup_title('Remove Shortlink', 'remove_shortlink') . codecleanup_tooltip('https://codecleanup.io/docs/remove-shortlink-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_shortlink',
    		'tooltip' => 'Remove Shortlink link tag.'
    	)
    );

    //Disable RSS Feeds
    add_settings_field(
    	'disable_rss_feeds', 
    	codecleanup_title('Disable RSS Feeds', 'disable_rss_feeds') . codecleanup_tooltip('https://codecleanup.io/docs/disable-rss-feeds-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'disable_rss_feeds',
    		'tooltip' => 'Disable WordPress generated RSS feeds.'
    	)
    );

    //Remove Feed Links
    add_settings_field(
    	'remove_feed_links', 
    	codecleanup_title('Remove RSS Feed Links', 'remove_feed_links') . codecleanup_tooltip('https://codecleanup.io/docs/remove-rss-feed-links-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_feed_links',
    		'tooltip' => 'Disable WordPress generated RSS feed link tags.'
    	)
    );

    //Disable Self Pingbacks
    add_settings_field(
    	'disable_self_pingbacks', 
    	codecleanup_title('Disable Self Pingbacks', 'disable_self_pingbacks') . codecleanup_tooltip('https://codecleanup.io/docs/disable-self-pingbacks-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'disable_self_pingbacks',
    		'tooltip' => 'Disable Self Pingbacks (generated when linking to an article on your own blog).'
    	)
    );

    //Remove REST API Links
    add_settings_field(
    	'remove_rest_api_links', 
    	codecleanup_title('Remove REST API Links', 'remove_rest_api_links') . codecleanup_tooltip('https://codecleanup.io/docs/remove-wordpress-rest-api-links/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'remove_rest_api_links',
    		'tooltip' => 'Removes REST API link tag from the front end and the REST API header link from page requests.'
    	)
    );

    //Disable Dashicons
    add_settings_field(
        'disable_dashicons', 
        codecleanup_title('Disable Dashicons', 'disable_dashicons') . codecleanup_tooltip('https://codecleanup.io/docs/remove-dashicons-wordpress/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_options', 
        array(
            'id' => 'disable_dashicons',
            'tooltip' => 'Disables dashicons on the front end when not logged in.'
        )
    );

    //Disable Google Maps
    add_settings_field(
        'disable_google_maps', 
        codecleanup_title('Disable Google Maps', 'disable_google_maps') . codecleanup_tooltip('https://codecleanup.io/docs/disable-google-maps-api-wordpress/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_options', 
        array(
            'id' => 'disable_google_maps',
            'tooltip' => 'Removes any instances of Google Maps being loaded across your entire site.'
        )
    );

    //Disable Heartbeat
    add_settings_field(
    	'disable_heartbeat', 
    	'<label for=\'disable_heartbeat\'>' . __('Disable Heartbeat', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/disable-wordpress-heartbeat-api/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'disable_heartbeat',
    		'input' => 'select',
    		'options' => array(
    			'' => 'Default',
    			'disable_everywhere' => 'Disable Everywhere',
    			'allow_posts' => 'Only Allow When Editing Posts/Pages'
    		),
    		'tooltip' => 'Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).'
    	)
    );

    //Heartbeat Frequency
    add_settings_field(
    	'heartbeat_frequency', 
    	'<label for=\'heartbeat_frequency\'>' . __('Heartbeat Frequency', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/change-heartbeat-frequency-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'heartbeat_frequency',
    		'input' => 'select',
    		'options' => array(
    			'' => '15 Seconds (Default)',
    			'30' => '30 Seconds',
    			'45' => '45 Seconds',
    			'60' => '60 Seconds'
    		),
    		'tooltip' => 'Controls how often the WordPress Heartbeat API is allowed to run.'
    	)
    );

    //Limit Post Revisions
    add_settings_field(
    	'limit_post_revisions', 
    	'<label for=\'limit_post_revisions\'>' . __('Limit Post Revisions', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/disable-limit-post-revisions-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'limit_post_revisions',
    		'input' => 'select',
    		'options' => array(
    			'' => 'Default',
    			'false' => 'Disable Post Revisions',
    			'1' => '1',
    			'2' => '2',
    			'3' => '3',
    			'4' => '4',
    			'5' => '5',
    			'10' => '10',
    			'15' => '15',
    			'20' => '20',
    			'25' => '25',
    			'30' => '30'
    		),
    		'tooltip' => 'Limits the maximum amount of revisions that are allowed for posts and pages.'
    	)
    );

    //Autosave Interval
    add_settings_field(
    	'autosave_interval', 
    	'<label for=\'autosave_interval\'>' . __('Autosave Interval', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/change-autosave-interval-wordpress/'), 
    	'codecleanup_print_input', 
    	'codecleanup_options', 
    	'codecleanup_options', 
    	array(
    		'id' => 'autosave_interval',
    		'input' => 'select',
    		'options' => array(
    			'' => '1 Minute (Default)',
    			'120' => '2 Minutes',
    			'180' => '3 Minutes',
    			'240' => '4 Minutes',
    			'300' => '5 Minutes'
    		),
    		'tooltip' => 'Controls how often WordPress will auto save posts and pages while editing.'
    	)
    );

    //Change Login URL
    add_settings_field(
        'login_url', 
        codecleanup_title('Change Login URL', 'login_url') . codecleanup_tooltip('https://codecleanup.io/docs/change-wordpress-login-url/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_options', 
        array(
            'id' => 'login_url',
            'input' => 'text',
            'placeholder' => 'hideme',
            'tooltip' => 'When set, this will change your WordPress login URL (slug) to the provided string and will block wp-admin and wp-login endpoints from being directly accessed.'
        )
    );

    //WooCommerce Options Section
    add_settings_section('codecleanup_woocommerce', 'WooCommerce', 'codecleanup_woocommerce_callback', 'codecleanup_options');

    //Disable WooCommerce Scripts
    add_settings_field(
        'disable_woocommerce_scripts', 
        codecleanup_title('Disable Scripts', 'disable_woocommerce_scripts') . codecleanup_tooltip('https://codecleanup.io/docs/disable-woocommerce-scripts-and-styles/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_woocommerce', 
        array(
            'id' => 'disable_woocommerce_scripts',
            'tooltip' => 'Disables WooCommerce scripts and styles except on product, cart, and checkout pages.'
        )
    );

    //Disable WooCommerce Cart Fragmentation
    add_settings_field(
        'disable_woocommerce_cart_fragmentation', 
        codecleanup_title('Disable Cart Fragmentation', 'disable_woocommerce_cart_fragmentation') . codecleanup_tooltip('https://codecleanup.io/docs/disable-woocommerce-cart-fragments-ajax/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_woocommerce', 
        array(
            'id' => 'disable_woocommerce_cart_fragmentation',
            'tooltip' => 'Completely disables WooCommerce cart fragmentation script.'
        )
    );

    //Disable WooCommerce Status Meta Box
    add_settings_field(
        'disable_woocommerce_status', 
        codecleanup_title('Disable Status Meta Box', 'disable_woocommerce_status') . codecleanup_tooltip('https://codecleanup.io/docs/disable-woocommerce-status-meta-box/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_woocommerce', 
        array(
            'id' => 'disable_woocommerce_status',
            'tooltip' => 'Disables WooCommerce status meta box from the WP Admin Dashboard.'
        )
    );

    //Disable WooCommerce Widgets
    add_settings_field(
        'disable_woocommerce_widgets', 
        codecleanup_title('Disable Widgets', 'disable_woocommerce_widgets') . codecleanup_tooltip('https://codecleanup.io/docs/disable-woocommerce-widgets/'), 
        'codecleanup_print_input', 
        'codecleanup_options', 
        'codecleanup_woocommerce', 
        array(
            'id' => 'disable_woocommerce_widgets',
            'tooltip' => 'Disables all WooCommerce widgets.'
        )
    );

    register_setting('codecleanup_options', 'codecleanup_options');

    //CDN Option
    if(get_option('codecleanup_cdn') == false) {    
        add_option('codecleanup_cdn', apply_filters('codecleanup_default_cdn', codecleanup_default_cdn()));
    }

    //CDN Section
    add_settings_section('codecleanup_cdn', 'CDN', 'codecleanup_cdn_callback', 'codecleanup_cdn');

    //CDN URL
    add_settings_field(
        'enable_cdn', 
        codecleanup_title('Enable CDN Rewrite', 'enable_cdn') . codecleanup_tooltip('https://codecleanup.io/docs/cdn-rewrite/'), 
        'codecleanup_print_input', 
        'codecleanup_cdn', 
        'codecleanup_cdn', 
        array(
            'id' => 'enable_cdn',
            'option' => 'codecleanup_cdn',
            'tooltip' => 'Enables rewriting of your site URLs with your CDN URLs which can be configured below.'
        )
    );

    //CDN URL
    add_settings_field(
        'cdn_url', 
        codecleanup_title('CDN URL', 'cdn_url') . codecleanup_tooltip('https://codecleanup.io/docs/cdn-url/'), 
        'codecleanup_print_input', 
        'codecleanup_cdn', 
        'codecleanup_cdn', 
        array(
            'id' => 'cdn_url',
            'option' => 'codecleanup_cdn',
            'input' => 'text',
            'placeholder' => 'https://cdn.example.com',
            'tooltip' => 'Enter your CDN URL without the trailing backslash. Example: https://cdn.example.com'
        )
    );

    //CDN Included Directories
    add_settings_field(
        'cdn_directories', 
        codecleanup_title('Included Directories', 'cdn_directories') . codecleanup_tooltip('https://codecleanup.io/docs/cdn-included-directories/'), 
        'codecleanup_print_input', 
        'codecleanup_cdn', 
        'codecleanup_cdn', 
        array(
            'id' => 'cdn_directories',
            'option' => 'codecleanup_cdn',
            'input' => 'text',
            'placeholder' => 'wp-content,wp-includes',
            'tooltip' => 'Enter any directories you would like to be included in CDN rewriting, separated by commas (,). Default: wp-content,wp-includes'
        )
    );

    //CDN Exclusions
    add_settings_field(
        'cdn_exclusions', 
        codecleanup_title('CDN Exclusions', 'cdn_exclusions') . codecleanup_tooltip('https://codecleanup.io/docs/cdn-exclusions/'), 
        'codecleanup_print_input', 
        'codecleanup_cdn', 
        'codecleanup_cdn', 
        array(
            'id' => 'cdn_exclusions',
            'option' => 'codecleanup_cdn',
            'input' => 'text',
            'placeholder' => '.php',
            'tooltip' => 'Enter any directories or file extensions you would like to be excluded from CDN rewriting, separated by commas (,). Default: .php'
        )
    );

    register_setting('codecleanup_cdn', 'codecleanup_cdn');

    if(get_option('codecleanup_extras') == false) {    
        add_option('codecleanup_extras', apply_filters('codecleanup_default_extras', codecleanup_default_extras()));
    }
    add_settings_section('codecleanup_extras', 'Extras', 'codecleanup_extras_callback', 'codecleanup_extras');

    //Script Manager
    add_settings_field(
        'script_manager', 
        codecleanup_title('Script Manager', 'script_manager') . codecleanup_tooltip('https://codecleanup.io/docs/disable-scripts-per-post-page/'), 
        'codecleanup_print_input', 
        'codecleanup_extras', 
        'codecleanup_extras', 
        array(
        	'id' => 'script_manager',
        	'option' => 'codecleanup_extras',
        	'tooltip' => 'Enables the codecleanup Script Manager, which gives you the ability to disable CSS and JS files on a page by page basis.'
        )
    );

    //DNS Prefetch
    add_settings_field(
        'dns_prefetch', 
        codecleanup_title('DNS Prefetch', 'dns_prefetch') . codecleanup_tooltip('https://codecleanup.io/docs/dns-prefetching/'), 
        'codecleanup_print_dns_prefetch', 
        'codecleanup_extras', 
        'codecleanup_extras', 
        array(
            'id' => 'dns_prefetch',
            'option' => 'codecleanup_extras',
            'tooltip' => 'Resolve domain names before a user clicks. Format: //domain.tld (one per line)'
        )
    );

    //Preconnect
    add_settings_field(
        'preconnect', 
        codecleanup_title('Preconnect', 'preconnect') . codecleanup_tooltip('https://codecleanup.io/docs/preconnect/'), 
        'codecleanup_print_preconnect', 
        'codecleanup_extras', 
        'codecleanup_extras', 
        array(
            'id' => 'preconnect',
            'option' => 'codecleanup_extras',
            'tooltip' => 'Preconnect allows the browser to set up early connections before an HTTP request, eliminating roundtrip latency and saving time for users. Format: scheme://domain.tld (one per line)'
        )
    );

    //Clean Uninstall
    add_settings_field(
        'clean_uninstall', 
        codecleanup_title('Clean Uninstall', 'clean_uninstall') . codecleanup_tooltip('https://codecleanup.io/docs/clean-uninstall/'), 
        'codecleanup_print_input', 
        'codecleanup_extras', 
        'codecleanup_extras', 
        array(
            'id' => 'clean_uninstall',
            'option' => 'codecleanup_extras',
            'tooltip' => 'When enabled, this will cause all codecleanup options data to be removed from your database when the plugin is uninstalled.'
        )
    );

    //Accessibility Mode
    add_settings_field(
        'accessibility_mode', 
        codecleanup_title('Accessibility Mode', 'accessibility_mode', true), 
        'codecleanup_print_input',
        'codecleanup_extras', 
        'codecleanup_extras', 
        array(
        	'id' => 'accessibility_mode',
        	'input' => 'checkbox',
        	'option' => 'codecleanup_extras',
        	'tooltip' => 'Disable the use of visual UI elements in the plugin settings such as checkbox toggles and hovering tooltips.'
        )
    );

    register_setting('codecleanup_extras', 'codecleanup_extras', 'codecleanup_sanitize_extras');

    //edd license option
	register_setting('codecleanup_edd_license', 'codecleanup_edd_license_key', 'codecleanup_edd_sanitize_license');
}
add_action('admin_init', 'codecleanup_settings');

//options default values
function codecleanup_default_options() {
	$defaults = array(
		'disable_emojis' => "0",
		'disable_embeds' => "0",
		'remove_query_strings' => "0",
        //'query_string_parameters' => "",
		'disable_xmlrpc' => "0",
		'remove_jquery_migrate' => "0",
		'hide_wp_version' => "0",
		'remove_wlwmanifest_link' => "0",
		'remove_rsd_link' => "0",
		'remove_shortlink' => "0",
		'disable_rss_feeds' => "0",
		'remove_feed_links' => "0",
		'disable_self_pingbacks' => "0",
		'remove_rest_api_links' => "0",
        'disable_dashicons' => "0",
        'disable_google_maps' => "0",
		'disable_heartbeat' => "",
		'heartbeat_frequency' => "",
		'limit_post_revisions' => "",
		'autosave_interval' => "",
        'login_url' => "",
        'disable_woocommerce_scripts' => "0",
        'disable_woocommerce_cart_fragmentation' => "0",
        'disable_woocommerce_status' => "0",
        'disable_woocommerce_widgets' => "0"
	);
    codecleanup_network_defaults($defaults, 'codecleanup_options');
	return apply_filters('codecleanup_default_options', $defaults);
}

//cdn default values
function codecleanup_default_cdn() {
    $defaults = array(
        'enable_cdn' => "0",
        'cdn_url' => "0",
        'cdn_directories' => "wp-content,wp-includes",
        'cdn_exclusions' => ".php"
    );
    codecleanup_network_defaults($defaults, 'codecleanup_cdn');
    return apply_filters( 'codecleanup_default_cdn', $defaults );
}

//extras default values
function codecleanup_default_extras() {
    $defaults = array(
        'script_manager' => "0",
        'accessibility_mode' => "0"
    );
    codecleanup_network_defaults($defaults, 'codecleanup_extras');
    return apply_filters( 'codecleanup_default_extras', $defaults );
}

function codecleanup_network_defaults(&$defaults, $option) {
    if(is_multisite() && is_plugin_active_for_network('codecleanup/codecleanup.php')) {
        $codecleanup_network = get_site_option('codecleanup_network');
        if(!empty($codecleanup_network['default'])) {
            $networkDefaultOptions = get_blog_option($codecleanup_network['default'], $option);
            if($option == 'codecleanup_cdn') {
                unset($networkDefaultOptions['cdn_url']);
            }
            if(!empty($networkDefaultOptions)) {
                foreach($networkDefaultOptions as $key => $val) {
                    $defaults[$key] = $val;
                }
            }
        }
    }
}

//main options group callback
function codecleanup_options_callback() {
	echo '<p class="codecleanup-subheading">' . __( 'Select which performance options you would like to enable.', 'codecleanup' ) . '</p>';
}

//woocommerce options group callback
function codecleanup_woocommerce_callback() {
    echo '<p class="codecleanup-subheading">' . __( 'Disable specific elements of WooCommerce.', 'codecleanup' ) . '</p>';
}

//cdn group callback
function codecleanup_cdn_callback() {
    echo '<p class="codecleanup-subheading">' . __( 'CDN options that allow you to rewrite your site URLs with your CDN URLs.', 'codecleanup' ) . '</p>';
}

//extras group callback
function codecleanup_extras_callback() {
    echo '<p class="codecleanup-subheading">' . __( 'Extra options that pertain to codecleanup plugin functionality.', 'codecleanup' ) . '</p>';
}

//print form inputs
function codecleanup_print_input($args) {
    if(!empty($args['option'])) {
        $option = $args['option'];
        if($args['option'] == 'codecleanup_network') {
            $options = get_site_option($args['option']);
        }
        else {
            $options = get_option($args['option']);
        }
    }
    else {
        $option = 'codecleanup_options';
        $options = get_option('codecleanup_options');
    }
    if(!empty($args['option']) && $args['option'] == 'codecleanup_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('codecleanup_extras');
    }

    echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleanup-input-wrapper'>";

            //Text
            if(!empty($args['input']) && ($args['input'] == 'text' || $args['input'] == 'color')) {
                echo "<input type='text' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='" . (!empty($options[$args['id']]) ? $options[$args['id']] : '') . "' placeholder='" . (!empty($args['placeholder']) ? $args['placeholder'] : '') . "' />";
            }

            //Select
            elseif(!empty($args['input']) && $args['input'] == 'select') {
                echo "<select id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]'>";
                    foreach($args['options'] as $value => $title) {
                        echo "<option value='" . $value . "' "; 
                        if(!empty($options[$args['id']]) && $options[$args['id']] == $value) {
                            echo "selected";
                        } 
                        echo ">" . $title . "</option>";
                    }
                echo "</select>";
            }

            //Checkbox + Toggle
            else {
                if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && (empty($args['input']) || $args['input'] != 'checkbox')) {
                    echo "<label for='" . $args['id'] . "' class='switch'>";
                }
                    echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $option . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
                    if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
                        echo "checked";
                    }
                    echo ">";
                if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && (empty($args['input']) || $args['input'] != 'checkbox')) {
                       echo "<div class='slider'></div>";
                   echo "</label>";
                }
            }
            
        echo "</div>";

        if(!empty($args['tooltip'])) {
            if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && $args['id'] != 'accessibility_mode') {
                echo "<div class='codecleanup-tooltip-text-wrapper'>";
                    echo "<div class='codecleanup-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: middle;'>";
                                echo "<span class='codecleanup-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//print checkbox toggle option
function codecleanup_print_toggle($args) {
    if(!empty($args['section'])) {
        $section = $args['section'];
        $options = get_option($args['section']);
    }
    else {
        $section = 'codecleanup_options';
        $options = get_option('codecleanup_options');
    }
    if(!empty($args['section']) && $args['section'] == 'codecleanup_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('codecleanup_extras');
    }
	//$options = get_option('codecleanup_options');
    //$extras = get_option('codecleanup_extras');
    if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && empty($args['checkbox'])) {
        echo "<label for='" . $args['id'] . "' class='switch' style='font-size: 1px;'>";
            echo $args['label'];
    }
    	echo "<input type='checkbox' id='" . $args['id'] . "' name='" . $section . "[" . $args['id'] . "]' value='1' style='display: block; margin: 0px;' ";
    	if(!empty($options[$args['id']]) && $options[$args['id']] == "1") {
    		echo "checked";
    	}
        echo ">";
    if((empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") && empty($args['checkbox'])) {
	       echo "<div class='slider'></div>";
	   echo "</label>";
    }
}

//print select option
function codecleanup_print_select($args) {
	$options = get_option('codecleanup_options');
	echo "<select id='" . $args['id'] . "' name='codecleanup_options[" . $args['id'] . "]'>";
		foreach($args['options'] as $value => $title) {
			echo "<option value='" . $value . "' "; 
			if($options[$args['id']] == $value) {
				echo "selected";
			} 
			echo ">" . $title . "</option>";
		}
	echo "</select>";
}

//print DNS Prefetch
function codecleanup_print_dns_prefetch($args) {
    $extras = get_option('codecleanup_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleanup-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='codecleanup_extras[" . $args['id'] . "]' placeholder='//example.com'>";
                if(!empty($extras['dns_prefetch'])) {
                    foreach($extras['dns_prefetch'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='codecleanup-tooltip-text-wrapper'>";
                    echo "<div class='codecleanup-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='codecleanup-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//print Preconnect
function codecleanup_print_preconnect($args) {
    $extras = get_option('codecleanup_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleanup-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='codecleanup_extras[" . $args['id'] . "]' placeholder='https://example.com'>";
                if(!empty($extras['preconnect'])) {
                    foreach($extras['preconnect'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='codecleanup-tooltip-text-wrapper'>";
                    echo "<div class='codecleanup-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='codecleanup-tooltip-text'>" . $args['tooltip'] . "</span>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            else {
                echo "<p style='font-size: 12px; font-style: italic;'>" . $args['tooltip'] . "</p>";
            }
        }
    echo "</div>";
}

//sanitize extras
function codecleanup_sanitize_extras($values) {
    if(!empty($values['dns_prefetch'])) {
        $text = trim($values['dns_prefetch']);
        $text_array = explode("\n", $text);
        $text_array = array_filter($text_array, 'trim');
        $values['dns_prefetch'] = $text_array;
    }
    if(!empty($values['preconnect'])) {
        $text = trim($values['preconnect']);
        $text_array = explode("\n", $text);
        $text_array = array_filter($text_array, 'trim');
        $values['preconnect'] = $text_array;
    }
    return $values;
}

//sanitize EDD license
function codecleanup_edd_sanitize_license($new) {
	$old = get_option( 'codecleanup_edd_license_key' );
	if($old && $old != $new) {
		delete_option( 'codecleanup_edd_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

//print tooltip
function codecleanup_tooltip($link) {
	$var = "<a ";
        if(!empty($link)) {
            $var.= "href='" . $link . "' title='View Documentation' ";
        }
        $var.= "class='codecleanup-tooltip' target='_blank'>?";
    $var.= "</a>";
    return $var;
}

//print title
function codecleanup_title($title, $id, $checkbox = false) {
    if(!empty($title)) {
        $var = $title;
        if(!empty($id)) {
            $extras = get_option('codecleanup_extras');
            if((!empty($extras['accessibility_mode']) && $extras['accessibility_mode'] == "1") || $checkbox == true) {
                $var = "<label for='" . $id . "'>" . $var . "</label>";
            }
        }
        return $var;
    }
}