<?php

function codecleaner_network_admin_menu() {

	//Add Network Settings Menu Item
    add_submenu_page('settings.php', 'Codecleaner Network Settings', 'Codecleaner', 'manage_network_options', 'codecleaner', 'codecleaner_network_page_callback');

    //Create Site Option if Not Found
    if(get_site_option('codecleaner_network') == false) {    
        add_site_option('codecleaner_network', true);
    }
 
 	//Add Settings Section
    add_settings_section('codecleaner_network', 'Network Setup', 'codecleaner_network_callback', 'codecleaner_network');
   
   	//Add Options Fields
	add_settings_field(
		'access', 
		'<label for=\'access\'>' . __('Network Access', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/wordpress-multisite/'),
		'codecleaner_network_access_callback', 
		'codecleaner_network', 
		'codecleaner_network'
	);
 
	add_settings_field(
		'default', 
		'<label for=\'default\'>' . __('Network Default', 'codecleaner') . '</label>' . codecleaner_tooltip('https://cleancoded.com/docs/wordpress-multisite/'),
		'codecleaner_network_default_callback', 
		'codecleaner_network', 
		'codecleaner_network'
	);

	//Full Uninstall
    add_settings_field(
        'clean_uninstall', 
        codecleaner_title(__('Clean Uninstall', 'codecleaner'), 'clean_uninstall') . codecleaner_tooltip('https://cleancoded.com/docs/clean-uninstall/'), 
        'codecleaner_print_input', 
        'codecleaner_network', 
        'codecleaner_network', 
        array( 
            'id' => 'clean_uninstall',
            'option' => 'codecleaner_network',
            'tooltip' => __('When enabled, this will cause all Codecleaner options data to be removed from your database when the plugin is uninstalled.', 'codecleaner')
        )
    );

	//Register Setting
	register_setting('codecleaner_network', 'codecleaner_network');
}
add_filter('network_admin_menu', 'codecleaner_network_admin_menu');

//Codecleaner Network Section Callback
function codecleaner_network_callback() {
	echo '<p class="codecleaner-subheading">' . __('Manage network access control and setup a network default site.', 'codecleaner') . '</p>';
} 

//Codecleaner Network Settings Page
function codecleaner_network_page_callback() {
	if(isset($_POST['codecleaner_apply_defaults'])) {
		check_admin_referer('codecleaner-network-apply');
		if(isset($_POST['codecleaner_network_apply_blog']) && is_numeric($_POST['codecleaner_network_apply_blog'])) {
			$blog = get_blog_details($_POST['codecleaner_network_apply_blog']);
			if($blog) {
 
				//Reset all settings to select blog
				if(is_multisite()) {
					$codecleaner_network = get_site_option('codecleaner_network');
 
					if(!empty($codecleaner_network['default'])) {

						if($blog->blog_id != $codecleaner_network['default']) {

							$option_names = array(
								'codecleaner_options',
								'codecleaner_woocommerce',
								'codecleaner_extras'
							);

							foreach($option_names as $option_name) {

								//Remove\Clear previous option
								delete_blog_option($blog->blog_id, $option_name);

								//catch new option from default blog
								$new_option = get_blog_option($codecleaner_network['default'], $option_name);


								//update selected blog with default option
								update_blog_option($blog->blog_id, $option_name, $new_option);

							} 

							//Updated Notice About Default Settings 
							echo "<div class='notice updated is-dismissible'><p>" . __('Default settings applied!', 'codecleaner') . "</p></div>";
						} 
						else {
							//Can't Apply to Network Default
							echo "<div class='notice error is-dismissible'><p>" . __('Select a site that is not already the Network Default.', 'codecleaner') . "</p></div>";
						}
					}
					else {
						//Network Default Not Set
						echo "<div class='notice error is-dismissible'><p>" . __('Network Default not set.', 'codecleaner') . "</p></div>";
					}
				} 
			}
			else {
				//Not Found Message
				echo "<div class='notice error is-dismissible'><p>" . __('Error: Blog Not Found.', 'codecleaner') . "</p></div>";
			}
		}
	}

	//Options Updated
	if(isset($_GET['updated'])) {
		echo "<div class='notice updated is-dismissible'><p>" . __('Options saved.', 'codecleaner') . "</p></div>";
	}

	//if no tab is set, default to first/network tab
	if(empty($_GET['tab'])) {
		$_GET['tab'] = 'network';
	}  

	echo "<div class='wrap codecleaner-admin'>";

		//Admin Page Title
  		echo "<h1>" . __('Codecleaner Network Settings', 'codecleaner') . "</h1>";
 
  		//Tab Navigation
		echo "<h2 class='nav-tab-wrapper'>";
			echo "<a href='?page=codecleaner&tab=network' class='nav-tab " . ($_GET['tab'] == 'network' ? 'nav-tab-active' : '') . "'>" . __('Network', 'codecleaner') . "</a>";
			echo "<a href='?page=codecleaner&tab=license' class='nav-tab " . ($_GET['tab'] == 'license' ? 'nav-tab-active' : '') . "'>" . __('License', 'codecleaner') . "</a>";
			echo "<a href='?page=codecleaner&tab=support' class='nav-tab " . ($_GET['tab'] == 'support' ? 'nav-tab-active' : '') . "'>" . __('Support', 'codecleaner') . "</a>";
		echo "</h2>";

		//Tab Content
		if($_GET['tab'] == 'network') {

	  		echo "<form method='POST' action='edit.php?action=codecleaner_update_network_options' style='overflow: hidden;'>";
			    settings_fields('codecleaner_network');
			    do_settings_sections('codecleaner_network');
			    submit_button();
	  		echo "</form>";
 
	  		echo "<form method='POST'>";
	  
	  			echo "<h2>" . __('Apply Default Settings', 'codecleaner') . "</h2>";
	  			echo '<p class="codecleaner-subheading">' . __('Choose a site to apply the settings from your network default site.', 'codecleaner') . '</p>';
 
				wp_nonce_field('codecleaner-network-apply', '_wpnonce', true, true);
				echo "<p>" . __('Select a site from the dropdown and click to apply the settings from your network default (above).', 'codecleaner') . "</p>";

				echo "<select name='codecleaner_network_apply_blog'>";
					$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
					if(is_array($sites) && $sites !== array()) {
						echo "<option value=''>" . __('Select a Site', 'codecleaner') . "</option>";
						foreach($sites as $site) {
							echo "<option value='" . $site['blog_id'] . "'>" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
						}
					}
				echo "<select>";

				echo "<input type='submit' name='codecleaner_apply_defaults' value='" . __('Apply Default Settings', 'codecleaner') . "' class='button' />";
			echo "</form>";
		}
		//License Tab Content
		elseif($_GET['tab'] == 'license') {
			if(isset($_POST['codecleaner_save_license'])) {
				if(isset($_POST['codecleaner_edd_license_key'])) {
					//Save License Key
					update_site_option('codecleaner_edd_license_key', $_POST['codecleaner_edd_license_key']);
				}
			}
			if(isset($_POST['codecleaner_edd_license_activate'])) {
				codecleaner_edd_activate_network_license();
			}
			if(isset($_POST['codecleaner_edd_license_deactivate'])) {
				codecleaner_edd_deactivate_network_license();
			}

			$license_info = codecleaner_edd_check_network_license();
			$license = get_site_option('codecleaner_edd_license_key');
			$status = get_site_option('codecleaner_edd_license_status');

			echo "<form method='POST'>";
				echo "<table class='form-table'>";
					echo "<tbody>";

						//License Key
						echo "<tr>";
							echo "<th><label for='codecleaner_edd_license_key'>" . __('License Key', 'codecleaner') . "</label></th>";
							echo "<td>";
								echo "<input id='codecleaner_edd_license_key' name='codecleaner_edd_license_key' type='password' class='regular-text' value='" . $license . "' />";
								echo "<label class='description' for='codecleaner_edd_license_key'>" . __('Enter your license key', 'codecleaner') . "</label>";
							echo "</td>";
						echo "</tr>";
						
						if($license !== false) {

							//Activate/Deactivate License
							echo "<tr>";
								echo "<th>" . __('Activate License', 'permatters') . "</th>";
								echo "<td>";
									wp_nonce_field('codecleaner_edd_nonce', 'codecleaner_edd_nonce');
									if($status !== false && $status == 'valid') {
										echo "<input type='submit' class='button-secondary' name='codecleaner_edd_license_deactivate' value='" . __('Deactivate License', 'codecleaner') . "' />";
										echo "<span style='color:green; display: block; margin-top: 10px;'>" . __('License is activated.', 'codecleaner') . "</span>";
									} else {
										if(!empty($license_info->activations_left) && $license_info->activations_left == 'unlimited') {
											echo "<input type='submit' class='button-secondary' name='codecleaner_edd_license_activate' value='" . __('Activate License', 'codecleaner') . "' />";
											echo "<span style='color:red; display: block; margin-top: 10px;'>" . __('License is not activated.', 'codecleaner') . "</span>";
										}
										else {
											echo "<span style='color:red; display: block;'>" . __('Unlimited License needed for use in a multisite environment. Please contact support to upgrade.', 'codecleaner') . "</span>";
										}
									}
								echo "</td>";
							echo "</tr>";

							if(!empty($license_info)) {

								//Email Address
								if(!empty($license_info->customer_email)) {
									echo "<tr>";
										echo "<th>" . __('Customer Email', 'codecleaner') . "</th>";
										echo "<td>" . $license_info->customer_email . "</td>";
									echo "</tr>";
								}

								//License Status (Active/Expired)
								if(!empty($license_info->license)) {
									echo "<tr>";
										echo "<th>" . __('License Status', 'codecleaner') . "</th>";
										echo "<td " . ($license_info->license == "expired" ? "style='color: red;'" : "") . ">";
											echo $license_info->license;
											if(!empty($license) && $license_info->license == "expired") {
												echo "<br /><a href='https://cleancoded.com/checkout/?edd_license_key=" . $license . "&download_id=696' class='button-primary' style='margin-top: 10px;' target='_blank'>" . __('Renew Your License for Updates + Support!', 'codecleaner') . "</a>";
											}
										echo "</td>";
									echo "</tr>";
								}

								//Licenses Used
								if(!empty($license_info->site_count) && !empty($license_info->license_limit)) {
									echo "<tr>";
										echo "<th>" . __('Licenses Used', 'codecleaner') . "</th>";
										echo "<td>" . $license_info->site_count . "/" . $license_info->license_limit . "</td>";
									echo "</tr>";
								}
							}
						}
					echo "</tbody>";
				echo "</table>";
 
 				//Button To Save License 
				echo "<p class='submit'><input type='submit' name='codecleaner_save_license' class='button button-primary' value='" . __('Save License', 'codecleaner') . "'></p>";

			echo "</form>";
		}

		//Support Tab Content
		elseif($_GET['tab'] == 'support') {
			echo "<h2>" . __('Support', 'codecleaner') . "</h2>";
			echo "<p>" . __("For plugin support and documentation, please visit <a href='https://cleancoded.com/cleaner/' title='codecleaner' target='_blank'>cleancoded.com</a>.", 'codecleaner') . "</p>";
		}

		//Tooltip Legend
		if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') {
			echo "<div id='codecleaner-legend'>";
				echo "<div id='codecleaner-tooltip-legend'>";
					echo "<span>?</span>" . __('Click on tooltip icons to view full documentation.', 'codecleaner');
				echo "</div>";
			echo "</div>";
		}

		//Tooltip Display Script (.css/.js)
		echo "<script>
			(function ($) {
				$('.codecleaner-tooltip').hover(function(){
				    $(this).closest('tr').find('.codecleaner-tooltip-text-container').show();
				},function(){
				    $(this).closest('tr').find('.codecleaner-tooltip-text-container').hide();
				});
			}(jQuery));
		</script>";

	echo "</div>";
}

//Codecleaner Network Default
function codecleaner_network_default_callback() {
	$codecleaner_network = get_site_option('codecleaner_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='codecleaner-input-wrapper'>";
			echo "<select name='codecleaner_network[default]' id='default'>";
				$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
				if(is_array($sites) && $sites !== array()) {
					echo "<option value=''>" . __('None', 'codecleaner') . "</option>";
					foreach($sites as $site) {
						echo "<option value='" . $site['blog_id'] . "' " . ((!empty($codecleaner_network['default']) && $codecleaner_network['default'] == $site['blog_id']) ? "selected" : "") . ">" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
					} 
				}
			echo "<select>";
		echo "</div>";
		echo "<div class='codecleaner-tooltip-text-wrapper'>";
			echo "<div class='codecleaner-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='codecleaner-tooltip-text'>" . __('Choose a subsite that you want to pull default settings from.', 'codecleaner') . "</span>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}

//Update Codecleaner Network Options
function codecleaner_update_network_options() {

	//Verify Post Referring Page
  	check_admin_referer('codecleaner_network-options');
 
	//Get Registered Options
	global $new_whitelist_options;
	$options = $new_whitelist_options['codecleaner_network'];

	//Loop Through Registered Options
	foreach($options as $option) {
		if(isset($_POST[$option])) {

			//Update Site Option
			update_site_option($option, $_POST[$option]);
		}
	}

	//Redirect to Network Settings Page
	wp_redirect(add_query_arg(array('page' => 'codecleaner', 'updated' => 'true'), network_admin_url('settings.php')));

	exit;
}
add_action('network_admin_edit_codecleaner_update_network_options',  'codecleaner_update_network_options');
    
//Codecleaner Network Access
function codecleaner_network_access_callback() {
	$codecleaner_network = get_site_option('codecleaner_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='codecleaner-input-wrapper'>";
			echo "<select name='codecleaner_network[access]' id='access'>";
				echo "<option value=''>" . __('Site Admins (Default)', 'codecleaner') . "</option>";
				echo "<option value='super' " . ((!empty($codecleaner_network['access']) && $codecleaner_network['access'] == 'super') ? "selected" : "") . ">" . __('Super Admins Only', 'codecleaner') . "</option>";
			echo "<select>";
		echo "</div>";
		echo "<div class='codecleaner-tooltip-text-wrapper'>";
			echo "<div class='codecleaner-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='codecleaner-tooltip-text'>" . __('Choose who has access to manage Codecleaner plugin settings.', 'codecleaner') . "</span>";  
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
} 

function codecleaner_edd_check_network_license() {

	global $wp_version;

	$license = trim(get_site_option('codecleaner_edd_license_key'));

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
		update_site_option('codecleaner_edd_license_status', "valid");
	}
	else {
		update_site_option('codecleaner_edd_license_status', "invalid");
	}
	
	return($license_data);
}

function codecleaner_edd_deactivate_network_license() {

	// retrieve the license from the database
	$license = trim(get_site_option('codecleaner_edd_license_key'));

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
		delete_site_option('codecleaner_edd_license_status');
	}
}

function codecleaner_edd_activate_network_license() {

	//retrieve the license from the database
	$license = trim(get_site_option('codecleaner_edd_license_key'));

	//data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode(CODECLEANER_ITEM_NAME), // name of product in EDD
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
	update_site_option('codecleaner_edd_license_status', $license_data->license);
}

