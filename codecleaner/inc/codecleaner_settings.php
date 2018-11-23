<?php
//options default values
function codecleaner_default_options() {	
		$defaults = array(
		'disable_post_revisions' => "0",
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
        'disable_password_strength_meter' => "0",
		'disable_heartbeat' => "",
		'heartbeat_frequency' => "",
		'limit_post_revisions' => "",
		'autosave_interval' => "",
        'login_url' => ""
	);
    codecleaner_network_defaults($defaults, 'codecleaner_options');
	return apply_filters('codecleaner_default_options', $defaults);
}

//woocommerce default values
function codecleaner_default_woocommerce() {
    $defaults = array(
        'disable_woocommerce_scripts' => "0",
        'disable_woocommerce_cart_fragmentation' => "0",
        'disable_woocommerce_status' => "0",
        'disable_woocommerce_widgets' => "0"
    );
    codecleaner_network_defaults($defaults, 'codecleaner_woocommerce');
    return apply_filters('codecleaner_default_woocommerce', $defaults);
}

//extras default values
function codecleaner_default_extras() {
    $defaults = array(
        'deep_cleaning' => "0",
        'accessibility_mode' => "0"
    );
    codecleaner_network_defaults($defaults, 'codecleaner_extras');
    return apply_filters( 'codecleaner_default_extras', $defaults );
}

function codecleaner_network_defaults(&$defaults, $option) {
    if(is_multisite()) {
        $codecleaner_network = get_site_option('codecleaner_network');
        if(!empty($codecleaner_network['default'])) {
            $networkDefaultOptions = get_blog_option($codecleaner_network['default'], $option);
            if(!empty($networkDefaultOptions)) {
                foreach($networkDefaultOptions as $key => $val) {
                    $defaults[$key] = $val;
                }
            }
        }
    }
}

//register settings + options
function codecleaner_settings() {
	if(get_option('codecleaner_options') == false) {	
		add_option('codecleaner_options', apply_filters('codecleaner_default_options', codecleaner_default_options()));
	}

    //Autosave Interval
    add_settings_field(
    	'autosave_interval', 
    	'<label for=\'autosave_interval\'>' . __('Autosave Interval', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/change-autosave-interval-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'autosave_interval',
    		'input' => 'select',
    		'options' => array(
    			''    => __('1 Minute', 'codecleaner') . ' (' . __('Default', 'codecleaner') . ')',
                '120' => sprintf(__('%s Minutes', 'codecleaner'), '2'),
                '180' => sprintf(__('%s Minutes', 'codecleaner'), '3'),
                '240' => sprintf(__('%s Minutes', 'codecleaner'), '4'),
                '300' => sprintf(__('%s Minutes', 'codecleaner'), '5')
    		),
    		'tooltip' => __('Controls how often WordPress will auto save posts and pages while editing.', 'codecleaner')
    	)
    );

    //Disable Dashicons
    add_settings_field(
        'disable_dashicons', 
        codecleaner_title(__('Disable Dashicons', 'codecleaner'), 'disable_dashicons') . codecleaner_tooltip('https://cleancoded.com/docs/remove-dashicons-wordpress/'), 
        'codecleaner_print_input', 
        'codecleaner_options', 
        'codecleaner_options', 
        array(
            'id' => 'disable_dashicons',
            'tooltip' => __('Disables dashicons on the front end when not logged in.', 'codecleaner')
        )
    );

    //Disable Embeds
    add_settings_field(
    	'disable_embeds', 
    	codecleaner_title(__('Disable Embeds', 'codecleaner'), 'disable_embeds') . codecleaner_tooltip('https://cleancoded.com/docs/disable-embeds-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'disable_embeds',
    		'tooltip' => __('Removes WordPress Embed JavaScript file (wp-embed.min.js).', 'codecleaner')   		
    	)
    );
		
    //Disable Emojis
    add_settings_field(
    	'disable_emojis', 
    	codecleaner_title(__('Disable Emojis', 'codecleaner'), 'disable_emojis') . codecleaner_tooltip('https://cleancoded.com/docs/disable-emojis-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
            'id' => 'disable_emojis',
            'tooltip' => __('Removes WordPress Emojis JavaScript file (wp-emoji-release.min.js).', 'codecleaner')
        )
    );

    //Disable Heartbeat
    add_settings_field(
    	'disable_heartbeat', 
    	'<label for=\'disable_heartbeat\'>' . __('Disable Heartbeat', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/disable-wordpress-heartbeat-api/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'disable_heartbeat',
    		'input' => 'select',
    		'options' => array(
    			''                   => __('Default', 'codecleaner'),
    			'disable_everywhere' => __('Disable Everywhere', 'codecleaner'),
    			'allow_posts'        => __('Only Allow When Editing Posts/Pages', 'codecleaner')
    		),
    		'tooltip' => __('Disable WordPress Heartbeat everywhere or in certain areas (used for auto saving and revision tracking).', 'codecleaner')
    	)
    );
	
    //Heartbeat Frequency
    add_settings_field(
    	'heartbeat_frequency', 
    	'<label for=\'heartbeat_frequency\'>' . __('Heartbeat Frequency', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/change-heartbeat-frequency-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'heartbeat_frequency',
    		'input' => 'select',
    		'options' => array(
    			''   => sprintf(__('%s Seconds', 'codecleaner'), '15') . ' (' . __('Default', 'codecleaner') . ')',
                '30' => sprintf(__('%s Seconds', 'codecleaner'), '30'),
                '45' => sprintf(__('%s Seconds', 'codecleaner'), '45'),
                '60' => sprintf(__('%s Seconds', 'codecleaner'), '60')
    		),
    		'tooltip' => __('Controls how often the WordPress Heartbeat API is allowed to run.', 'codecleaner')
    	)
    );
	
	//Disable Post Revisions
    add_settings_field(
    	'disable_post_revisions', 
    	codecleaner_title(__('Disable Post Revisions', 'codecleaner'), 'disable_post_revisions') . codecleaner_tooltip('https://cleancoded.com/docs/disable-limit-post-revisions-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
            'id' => 'disable_post_revisions',
            'tooltip' => __('Limits the maximum amount of revisions that are allowed for posts and pages.', 'codecleaner')
        )
    );
	
    //Limit Post Revisions
	add_settings_field(
    	'limit_post_revisions', 
    	'<div id="label_limit_post_revisions"><label for=\'limit_post_revisions\'>' . __('Limit Post Revisions', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/disable-limit-post-revisions-wordpress/').'</div>', 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'limit_post_revisions',
    		'input' => 'select',
    		'options' => array(
    			''      => __('Default', 'codecleaner'),
    			'false' => __('Disable Post Revisions', 'codecleaner'),
    			'1'     => '1',
    			'2'     => '2',
    			'3'     => '3',
    			'4'     => '4',
    			'5'     => '5',
    			'10'    => '10',
    			'15'    => '15',
    			'20'    => '20',
    			'25'    => '25',
    			'30'    => '30'
    		),
    		'tooltip' => __('Limits the maximum amount of revisions that are allowed for posts and pages.', 'codecleaner')
    	)
    );
	
    //Disable RSS Feeds
    add_settings_field(
    	'disable_rss_feeds', 
    	codecleaner_title(__('Disable RSS Feeds', 'codecleaner'), 'disable_rss_feeds') . codecleaner_tooltip('https://cleancoded.com/docs/disable-rss-feeds-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'disable_rss_feeds',
    		'tooltip' => __('Disable WordPress generated RSS feeds and 301 redirect URL to parent.', 'codecleaner')
    	)
    );

    //Disable Self Pingbacks
    add_settings_field(
    	'disable_self_pingbacks', 
    	codecleaner_title(__('Disable Self Pingbacks', 'codecleaner'), 'disable_self_pingbacks') . codecleaner_tooltip('https://cleancoded.com/docs/disable-self-pingbacks-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'disable_self_pingbacks',
    		'tooltip' => __('Disable Self Pingbacks (generated when linking to an article on your own blog).', 'codecleaner')
    	)
    );

	//Disable XML-RPC
    add_settings_field(
    	'disable_xmlrpc', 
    	codecleaner_title(__('Disable XML-RPC', 'codecleaner'), 'disable_xmlrpc') . codecleaner_tooltip('https://cleancoded.com/docs/disable-xml-rpc-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'disable_xmlrpc',
    		'tooltip' => __('Disables WordPress XML-RPC functionality.', 'codecleaner')
    	)
    );

	//Remove jQuery Migrate
    add_settings_field(
    	'remove_jquery_migrate', 
    	codecleaner_title(__('Remove jQuery Migrate', 'codecleaner'), 'remove_jquery_migrate') . codecleaner_tooltip('https://cleancoded.com/docs/remove-jquery-migrate-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_jquery_migrate',
    		'tooltip' => __('Removes jQuery Migrate JavaScript file (jquery-migrate.min.js).', 'codecleaner')
    	)
    );

    //Remove Password Strength Meter
    add_settings_field(
        'disable_password_strength_meter', 
        codecleaner_title(__('Remove Password Strength Meter', 'codecleaner'), 'disable_password_strength_meter') . codecleaner_tooltip('https://cleancoded.com/docs/disable-password-meter-strength/'),
        'codecleaner_print_input', 
        'codecleaner_options', 
        'codecleaner_options', 
        array(
            'id' => 'disable_password_strength_meter',
            'tooltip' => __('Removes WordPress and WooCommerce Password Strength Meter scripts from non essential pages.', 'codecleaner')
        )
    );

    //Remove Query Strings
    add_settings_field(
    	'remove_query_strings', 
    	codecleaner_title(__('Remove Query Strings', 'codecleaner'), 'remove_query_strings') . codecleaner_tooltip('https://cleancoded.com/docs/remove-query-strings-from-static-resources/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_query_strings',
    		'tooltip' => __('Remove query strings from static resources (CSS, JS).', 'codecleaner')
    	)
    );
	
    //Remove REST API Links
    add_settings_field(
    	'remove_rest_api_links', 
    	codecleaner_title(__('Remove REST API Links', 'codecleaner'), 'remove_rest_api_links') . codecleaner_tooltip('https://cleancoded.com/docs/remove-wordpress-rest-api-links/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_rest_api_links',
    		'tooltip' => __('Removes REST API link tag from the front end and the REST API header link from page requests.', 'codecleaner')
    	)
    );

    //Remove RSD Link
    add_settings_field(
    	'remove_rsd_link', 
    	codecleaner_title(__('Remove RSD Link', 'codecleaner'), 'remove_rsd_link') . codecleaner_tooltip('https://cleancoded.com/docs/remove-rsd-link-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_rsd_link',
    		'tooltip' => __('Remove RSD (Real Simple Discovery) link tag.', 'codecleaner')
    	)
    );

    //Remove RSS Feed Links
    add_settings_field(
    	'remove_feed_links', 
    	codecleaner_title(__('Remove RSS Feed Links', 'codecleaner'), 'remove_feed_links') . codecleaner_tooltip('https://cleancoded.com/docs/remove-rss-feed-links-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_feed_links',
    		'tooltip' => __('Disable WordPress generated RSS feed link tags.', 'codecleaner')
    	)
    );

    //Remove Shortlink
    add_settings_field(
    	'remove_shortlink', 
    	codecleaner_title(__('Remove Shortlink', 'codecleaner'), 'remove_shortlink') . codecleaner_tooltip('https://cleancoded.com/docs/remove-shortlink-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'remove_shortlink',
    		'tooltip' => __('Remove Shortlink link tag.', 'codecleaner')
    	)
    );

    //Remove wlmanifest Link
    add_settings_field(
    	'remove_wlwmanifest_link', 
    	codecleaner_title(__('Remove wlwmanifest Link', 'codecleaner'), 'remove_wlwmanifest_link') . codecleaner_tooltip('https://cleancoded.com/docs/remove-wlwmanifest-link-wordpress/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options',
        array(
        	'id' => 'remove_wlwmanifest_link',
        	'tooltip' => __('Remove wlwmanifest (Windows Live Writer) link tag.', 'codecleaner')
        )
    );

    //Options Primary Section
    add_settings_section('codecleaner_options', __('Default', 'codecleaner'), 'codecleaner_options_callback', 'codecleaner_options');

    //Query String Parameters
    /*add_settings_field(
        'query_string_parameters', 
        codecleaner_title(__('Additional Parameters', 'codecleaner'), 'query_string_parameters') . codecleaner_tooltip(''), 
        'codecleaner_print_input', 
        'codecleaner_options', 
        'codecleaner_options', 
        array(
            'id' => 'query_string_parameters',
            'input' => 'text',
            'placeholder' => 'v,id',
            'tooltip' => __('', 'codecleaner')
        )
    );*/

    //Hide WP Version
    add_settings_field(
    	'hide_wp_version', 
    	codecleaner_title(__('Remove WP Version Meta Tag', 'codecleaner'), 'hide_wp_version') . codecleaner_tooltip('https://cleancoded.com/docs/remove-wordpress-version-number/'), 
    	'codecleaner_print_input', 
    	'codecleaner_options', 
    	'codecleaner_options', 
    	array(
    		'id' => 'hide_wp_version',
    		'tooltip' => __('Removes WordPress version meta tag.', 'codecleaner')
    	)
    );

    //Disable Google Maps
/*    add_settings_field(
        'disable_google_maps', 
        codecleaner_title(__('Disable Google Maps', 'codecleaner'), 'disable_google_maps') . codecleaner_tooltip('https://cleancoded.com/docs/disable-google-maps-api-wordpress/'), 
        'codecleaner_print_input', 
        'codecleaner_options', 
        'codecleaner_options', 
        array(
            'id' => 'disable_google_maps',
            'tooltip' => __('Removes any instances of Google Maps being loaded across your entire site.', 'codecleaner')
        )
    );*/

    //Change Login URL
/*    add_settings_field(
        'login_url', 
        codecleaner_title(__('Change Login URL', 'codecleaner'), 'login_url') . codecleaner_tooltip('https://cleancoded.com/docs/change-wordpress-login-url/'), 
        'codecleaner_print_input', 
        'codecleaner_options', 
        'codecleaner_options', 
        array(
            'id' => 'login_url',
            'input' => 'text',
            'placeholder' => 'hideme',
            'tooltip' => __('When set, this will change your WordPress login URL (slug) to the provided string and will block wp-admin and wp-login endpoints from being directly accessed.', 'codecleaner')
        )
    );*/

	    register_setting('codecleaner_options', 'codecleaner_options');

    //Google Analytics Option
    if(get_option('codecleaner_woocommerce') == false) {    
    add_option('codecleaner_woocommerce', apply_filters('codecleaner_default_woocommerce', codecleaner_default_woocommerce()));
    }

    //WooCommerce Options Section
    add_settings_section('codecleaner_woocommerce', 'WooCommerce', 'codecleaner_woocommerce_callback', 'codecleaner_woocommerce');

    //Disable WooCommerce Scripts
    add_settings_field(
        'disable_woocommerce_scripts', 
        codecleaner_title(__('Disable Scripts', 'codecleaner'), 'disable_woocommerce_scripts') . codecleaner_tooltip('https://cleancoded.com/docs/disable-woocommerce-scripts-and-styles/'), 
        'codecleaner_print_input', 
        'codecleaner_woocommerce', 
        'codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_scripts',
            'option' => 'codecleaner_woocommerce',
            'tooltip' => __('Disables WooCommerce scripts and styles except on product, cart, and checkout pages.', 'codecleaner')
        )
    );

    //Disable WooCommerce Cart Fragmentation
    add_settings_field(
        'disable_woocommerce_cart_fragmentation', 
        codecleaner_title(__('Disable Cart Fragmentation', 'codecleaner'), 'disable_woocommerce_cart_fragmentation') . codecleaner_tooltip('https://cleancoded.com/docs/disable-woocommerce-cart-fragments-ajax/'), 
        'codecleaner_print_input', 
        'codecleaner_woocommerce', 
        'codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_cart_fragmentation',
            'option' => 'codecleaner_woocommerce',
            'tooltip' => __('Completely disables WooCommerce cart fragmentation script.', 'codecleaner')
        )
    );

    //Disable WooCommerce Status Meta Box
    add_settings_field(
        'disable_woocommerce_status', 
        codecleaner_title(__('Disable Status Meta Box', 'codecleaner'), 'disable_woocommerce_status') . codecleaner_tooltip('https://cleancoded.com/docs/disable-woocommerce-status-meta-box/'), 
        'codecleaner_print_input', 
        'codecleaner_woocommerce', 
        'codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_status',
            'option' => 'codecleaner_woocommerce',
            'tooltip' => __('Disables WooCommerce status meta box from the WP Admin Dashboard.', 'codecleaner')
        )
    );

    //Disable WooCommerce Widgets
    add_settings_field(
        'disable_woocommerce_widgets', 
        codecleaner_title(__('Disable Widgets', 'codecleaner'), 'disable_woocommerce_widgets') . codecleaner_tooltip('https://cleancoded.com/docs/disable-woocommerce-widgets/'), 
        'codecleaner_print_input', 
        'codecleaner_woocommerce', 
        'codecleaner_woocommerce', 
        array(
            'id' => 'disable_woocommerce_widgets',
            'option' => 'codecleaner_woocommerce',
            'tooltip' => __('Disables all WooCommerce widgets.', 'codecleaner')
        )
    );

    register_setting('codecleaner_woocommerce', 'codecleaner_woocommerce');

    //Google Analytics Option
    if(get_option('codecleaner_ga') == false) {    
        add_option('codecleaner_ga', apply_filters('codecleaner_default_ga', codecleaner_default_ga()));
    }

    //Google Analytics Section
    add_settings_section('codecleaner_ga', __('Google Analytics', 'codecleaner'), 'codecleaner_ga_callback', 'codecleaner_ga');

    //Enable Local GA
    add_settings_field(
        'enable_local_ga', 
        codecleaner_title(__('Enable Local Analytics', 'codecleaner'), 'enable_local_ga') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/'),
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'enable_local_ga',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Enable syncing of the Google Analytics script to your own server.', 'codecleaner')
        )
    );

    //Tracking Code Position
    add_settings_field(
        'tracking_code_position', 
        codecleaner_title(__('Tracking Code Position', 'codecleaner'), 'tracking_code_position') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#trackingcodeposition'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'tracking_code_position',
            'option' => 'codecleaner_ga',
            'input' => 'select',
            'options' => array(
            	"" => __('Header', 'codecleaner') . ' (' . __('Default', 'codecleaner') . ')',
            	"footer" => __('Footer', 'codecleaner')
            	),
            'tooltip' => __('Load your analytics script in the header (default) or footer of your site. Default: Header', 'codecleaner')
        )
    );
	
	//Disable Google Maps
    add_settings_field(
        'disable_google_maps', 
        codecleaner_title(__('Disable Google Maps', 'codecleaner'), 'disable_google_maps') . codecleaner_tooltip('https://cleancoded.com/docs/disable-google-maps-api-wordpress/'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'disable_google_maps',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Removes any instances of Google Maps being loaded across your entire site.', 'codecleaner')
        )
    );

    //Disable Display Features
    add_settings_field(
        'disable_display_features', 
        codecleaner_title(__('Disable Display Features', 'codecleaner'), 'disable_display_features') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#disabledisplayfeatures'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'disable_display_features',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Disable remarketing and advertising which generates a 2nd HTTP request.', 'codecleaner')
        )
    );

    //Anonymize IP
    add_settings_field(
        'anonymize_ip', 
        codecleaner_title(__('Anonymize IP', 'codecleaner'), 'anonymize_ip') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#anonymize-ip'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'anonymize_ip',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Shorten visitor IP to comply with privacy restrictions in some countries.', 'codecleaner')
        )
    );

    //Google Analytics ID
    add_settings_field(
        'tracking_id', 
        codecleaner_title(__('Tracking ID', 'codecleaner'), 'tracking_id') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#trackingid'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'tracking_id',
            'option' => 'codecleaner_ga',
            'input' => 'text',
            'tooltip' => __('Input your Google Analytics tracking ID.', 'codecleaner')
        )
    );

    //Track Logged In Admins
    add_settings_field(
        'track_admins', 
        codecleaner_title(__('Track Logged In Admins', 'codecleaner'), 'track_admins') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#track-logged-in-admins'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'track_admins',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Include logged-in WordPress admins in your Google Analytics reports.', 'codecleaner')
        )
    );

    //Adjusted Bounce Rate
    add_settings_field(
        'adjusted_bounce_rate', 
        codecleaner_title(__('Adjusted Bounce Rate', 'codecleaner'), 'adjusted_bounce_rate') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#adjusted-bounce-rate'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'adjusted_bounce_rate',
            'option' => 'codecleaner_ga',
            'input' => 'text',
            'tooltip' => __('Set a timeout limit in seconds to better evaluate the quality of your traffic. (1-100)', 'codecleaner')
        )
    );

    //Use MonsterInsights
    add_settings_field(
        'use_monster_insights', 
        codecleaner_title(__('Use MonsterInsights', 'codecleaner'), 'use_monster_insights') . codecleaner_tooltip('https://cleancoded.com/docs/local-analytics/#monster-insights'), 
        'codecleaner_print_input', 
        'codecleaner_ga', 
        'codecleaner_ga', 
        array(
            'id' => 'use_monster_insights',
            'option' => 'codecleaner_ga',
            'tooltip' => __('Allows MonsterInsights to manage your Google Analaytics while still using the locally hosted analytics.js file generated by Codecleaner.', 'codecleaner')
        )
    );

    register_setting('codecleaner_ga', 'codecleaner_ga');

    if(get_option('codecleaner_extras') == false) {    
        add_option('codecleaner_extras', apply_filters('codecleaner_default_extras', codecleaner_default_extras()));
    }
    add_settings_section('codecleaner_extras', __('More', 'codecleaner'), 'codecleaner_extras_callback', 'codecleaner_extras');

    //Deep Cleaning
    add_settings_field(
        'deep_cleaning', 
        codecleaner_title(__('Deep Cleaning', 'codecleaner'), 'deep_cleaning') . codecleaner_tooltip('https://cleancoded.com/docs/disable-scripts-per-post-page/'), 
        'codecleaner_print_input', 
        'codecleaner_extras', 
        'codecleaner_extras', 
        array(
        	'id' => 'deep_cleaning',
        	'option' => 'codecleaner_extras',
        	'tooltip' => __('Enables the Codecleaner Deep Cleaning, which gives you the ability to disable CSS and JS files on a page by page basis.', 'codecleaner')
        )
    );

    //Preconnect
    add_settings_field(
        'preconnect', 
        codecleaner_title(__('Preconnect', 'codecleaner'), 'preconnect') . codecleaner_tooltip('https://cleancoded.com/docs/preconnect/'), 
        'codecleaner_print_preconnect', 
        'codecleaner_extras', 
        'codecleaner_extras', 
        array(
            'id' => 'preconnect',
            'option' => 'codecleaner_extras',
            'tooltip' => __('Preconnect allows the browser to set up early connections before an HTTP request, eliminating roundtrip latency and saving time for users. Format: scheme://domain.tld (one per line)', 'codecleaner')
        )
    );

    //DNS Prefetch
    add_settings_field(
        'dns_prefetch', 
        codecleaner_title(__('DNS Prefetch', 'codecleaner'), 'dns_prefetch') . codecleaner_tooltip('https://cleancoded.com/docs/dns-prefetching/'), 
        'codecleaner_print_dns_prefetch', 
        'codecleaner_extras', 
        'codecleaner_extras', 
        array(
            'id' => 'dns_prefetch',
            'option' => 'codecleaner_extras',
            'tooltip' => __('Resolve domain names before a user clicks. Format: //domain.tld (one per line)', 'codecleaner')
        )
    );

    if(!is_multisite()) {

        //Clean Uninstall
        add_settings_field(
            'clean_uninstall', 
            codecleaner_title(__('Clean Uninstall', 'codecleaner'), 'clean_uninstall') . codecleaner_tooltip('https://cleancoded.com/docs/clean-uninstall/'), 
            'codecleaner_print_input', 
            'codecleaner_extras', 
            'codecleaner_extras', 
            array(
                'id' => 'clean_uninstall',
                'option' => 'codecleaner_extras',
                'tooltip' => __('When enabled, this will cause all Codecleaner options data to be removed from your database when the plugin is uninstalled.', 'codecleaner')
            )
        );

    }

    //Accessibility Mode
    add_settings_field(
        'accessibility_mode', 
        codecleaner_title(__('Accessibility Mode', 'codecleaner'), 'accessibility_mode', true), 
        'codecleaner_print_input',
        'codecleaner_extras', 
        'codecleaner_extras', 
        array(
        	'id' => 'accessibility_mode',
        	'input' => 'checkbox',
        	'option' => 'codecleaner_extras',
        	'tooltip' => __('Disable the use of visual UI elements in the plugin settings such as checkbox toggles and hovering tooltips.', 'codecleaner')
        )
    );

    register_setting('codecleaner_extras', 'codecleaner_extras', 'codecleaner_sanitize_extras');

}
add_action('admin_init', 'codecleaner_settings');



//google analytics group callback
function codecleaner_ga_callback() {
    echo '<p class="codecleaner-subheading">' . __('Optimization options for Google Analytics.', 'codecleaner') . '</p>';
}

//main options group callback
function codecleaner_options_callback() {
	echo '<p class="codecleaner-subheading">' . __('Select which performance default you would like to enable.', 'codecleaner') . '</p>';
}

//print form inputs
function codecleaner_print_input($args) {
    if(!empty($args['option'])) {
        $option = $args['option'];
        if($args['option'] == 'codecleaner_network') {
            $options = get_site_option($args['option']);
        }
        else {
            $options = get_option($args['option']);
        }
    }
    else {
        $option = 'codecleaner_options';
        $options = get_option('codecleaner_options');
    }
    if(!empty($args['option']) && $args['option'] == 'codecleaner_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('codecleaner_extras');
    }

    echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleaner-input-wrapper'>";

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
                echo "<div class='codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: middle;'>";
                                echo "<span class='codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
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

//extras group callback
function codecleaner_extras_callback() {
    echo '<p class="codecleaner-subheading">' . __('More options that pertain to Codecleaner plugin functionality.', 'codecleaner') . '</p>';
}

//woocommerce options group callback
function codecleaner_woocommerce_callback() {
    echo '<p class="codecleaner-subheading">' . __('Disable specific elements of WooCommerce.', 'codecleaner') . '</p>';
}


//google analytics default values
function codecleaner_default_ga() {
    $defaults = array(
    	'enable_local_ga' => "0",
        'tracking_id' => "",
        'tracking_code_position' => "",
        'disable_display_features' => "0",
        'anonymize_ip' => "0",
        'track_admins' => "0",
        'adjusted_bounce_rate' => "",
        'use_monster_insights' => "0"
    );
    codecleaner_network_defaults($defaults, 'codecleaner_ga');
    return apply_filters('codecleaner_default_ga', $defaults);
}

//print checkbox toggle option
function codecleaner_print_toggle($args) {
    if(!empty($args['section'])) {
        $section = $args['section'];
        $options = get_option($args['section']);
    }
    else {
        $section = 'codecleaner_options';
        $options = get_option('codecleaner_options');
    }
    if(!empty($args['section']) && $args['section'] == 'codecleaner_extras') {
        $extras = $options;
    }
    else {
        $extras = get_option('codecleaner_extras');
    }
	//$options = get_option('codecleaner_options');
    //$extras = get_option('codecleaner_extras');
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

//print tooltip
function codecleaner_tooltip($link) {
	$var = "<a ";
        if(!empty($link)) {
            $var.= "href='" . $link . "' title='" . __('View Documentation', 'codecleaner') . "' ";
        }
        $var.= "class='codecleaner-tooltip' target='_blank'>?";
    $var.= "</a>";
    return $var;
}

//print DNS Prefetch
function codecleaner_print_dns_prefetch($args) {
    $extras = get_option('codecleaner_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleaner-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='codecleaner_extras[" . $args['id'] . "]' placeholder='//example.com'>";
                if(!empty($extras['dns_prefetch'])) {
                    foreach($extras['dns_prefetch'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
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
function codecleaner_print_preconnect($args) {
    $extras = get_option('codecleaner_extras');
     echo "<div style='display: table; width: 100%;'>";
        echo "<div class='codecleaner-input-wrapper'>";
            echo "<textarea id='" . $args['id'] . "' name='codecleaner_extras[" . $args['id'] . "]' placeholder='https://example.com'>";
                if(!empty($extras['preconnect'])) {
                    foreach($extras['preconnect'] as $line) {
                        echo $line . "\n";
                    }
                }
            echo "</textarea>";
        echo "</div>";
        if(!empty($args['tooltip'])) {
            if(empty($extras['accessibility_mode']) || $extras['accessibility_mode'] != "1") {
                echo "<div class='codecleaner-tooltip-text-wrapper'>";
                    echo "<div class='codecleaner-tooltip-text-container'>";
                        echo "<div style='display: table; height: 100%; width: 100%;'>";
                            echo "<div style='display: table-cell; vertical-align: top;'>";
                                echo "<span class='codecleaner-tooltip-text'>" . $args['tooltip'] . "</span>";
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

//print title
function codecleaner_title($title, $id, $checkbox = false) {
    if(!empty($title)) {
        $var = $title;
        if(!empty($id)) {
            $extras = get_option('codecleaner_extras');
            if((!empty($extras['accessibility_mode']) && $extras['accessibility_mode'] == "1") || $checkbox == true) {
                $var = "<label for='" . $id . "'>" . $var . "</label>";
            }
        }
        return $var;
    }
}

//sanitize extras
function codecleaner_sanitize_extras($values) {
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

//print select option
function codecleaner_print_select($args) {
	$options = get_option('codecleaner_options');
	echo "<select id='" . $args['id'] . "' name='codecleaner_options[" . $args['id'] . "]'>";
		foreach($args['options'] as $value => $title) {
			echo "<option value='" . $value . "' "; 
			if($options[$args['id']] == $value) {
				echo "selected";
			} 
			echo ">" . $title . "</option>";
		}
	echo "</select>";
}
