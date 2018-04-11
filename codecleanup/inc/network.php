<?php
function codecleanup_network_admin_menu() {

	//Add Network Settings Menu Item
    add_submenu_page('settings.php', 'codecleanup Network Settings', 'codecleanup', 'manage_network_options', 'codecleanup', 'codecleanup_network_page_callback');

    //Create Site Option if Not Found
    if(get_site_option('codecleanup_network') == false) {    
        add_site_option('codecleanup_network', true);
    }
 
 	//Add Settings Section
    add_settings_section('codecleanup_network', 'Network Setup', 'codecleanup_network_callback', 'codecleanup_network');
   
   	//Add Options Fields
	add_settings_field(
		'access', 
		'<label for=\'access\'>' . __('Network Access', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/wordpress-multisite/'),
		'codecleanup_network_access_callback', 
		'codecleanup_network', 
		'codecleanup_network'
	);

	add_settings_field(
		'default', 
		'<label for=\'default\'>' . __('Network Default', 'codecleanup') . '</label>' . codecleanup_tooltip('https://codecleanup.io/docs/wordpress-multisite/'),
		'codecleanup_network_default_callback', 
		'codecleanup_network', 
		'codecleanup_network'
	);

	//Clean Uninstall
    add_settings_field(
        'clean_uninstall', 
        codecleanup_title('Clean Uninstall', 'clean_uninstall') . codecleanup_tooltip(''), 
        'codecleanup_print_input', 
        'codecleanup_network', 
        'codecleanup_network', 
        array(
            'id' => 'clean_uninstall',
            'option' => 'codecleanup_network',
            'tooltip' => 'When enabled, this will cause all codecleanup options data to be removed from your database when the plugin is uninstalled.'
        )
    );

	//Register Setting
	register_setting('codecleanup_network', 'codecleanup_network');
}
add_filter('network_admin_menu', 'codecleanup_network_admin_menu');

//codecleanup Network Section Callback
function codecleanup_network_callback() {
	echo '<p class="codecleanup-subheading">' . __( 'Manage network access control and setup a network default site.', 'codecleanup' ) . '</p>';
}
 
//codecleanup Network Access
function codecleanup_network_access_callback() {
	$codecleanup_network = get_site_option('codecleanup_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='codecleanup-input-wrapper'>";
			echo "<select name='codecleanup_network[access]' id='access'>";
				echo "<option value=''>Site Admins (Default)</option>";
				echo "<option value='super' " . ((!empty($codecleanup_network['access']) && $codecleanup_network['access'] == 'super') ? "selected" : "") . ">Super Admins Only</option>";
			echo "<select>";
		echo "</div>";
		echo "<div class='codecleanup-tooltip-text-wrapper'>";
			echo "<div class='codecleanup-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='codecleanup-tooltip-text'>Choose who has access to manage codecleanup plugin settings.</span>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}

//codecleanup Network Default
function codecleanup_network_default_callback() {
	$codecleanup_network = get_site_option('codecleanup_network');
	echo "<div style='display: table; width: 100%;'>";
		echo "<div class='codecleanup-input-wrapper'>";
			echo "<select name='codecleanup_network[default]' id='default'>";
				$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
				if(is_array($sites) && $sites !== array()) {
					echo "<option value=''>None</option>";
					foreach($sites as $site) {
						echo "<option value='" . $site['blog_id'] . "' " . ((!empty($codecleanup_network['default']) && $codecleanup_network['default'] == $site['blog_id']) ? "selected" : "") . ">" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
					}
				}
			echo "<select>";
		echo "</div>";
		echo "<div class='codecleanup-tooltip-text-wrapper'>";
			echo "<div class='codecleanup-tooltip-text-container' style='display: none;'>";
				echo "<div style='display: table; height: 100%; width: 100%;'>";
					echo "<div style='display: table-cell; vertical-align: middle;'>";
						echo "<span class='codecleanup-tooltip-text'>Choose a subsite that you want to pull default settings from.</span>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
}
 
//codecleanup Network Settings Page
function codecleanup_network_page_callback() {
	if(isset($_POST['codecleanup_apply_defaults'])) {
		check_admin_referer('codecleanup-network-apply');
		if(isset($_POST['codecleanup_network_apply_blog']) && is_numeric($_POST['codecleanup_network_apply_blog'])) {
			$blog = get_blog_details($_POST['codecleanup_network_apply_blog']);
			if($blog) {

				//apply default settings to selected blog
				if(is_multisite()) {
					$codecleanup_network = get_site_option('codecleanup_network');

					if(!empty($codecleanup_network['default'])) {

						if($blog->blog_id != $codecleanup_network['default']) {

							$option_names = array(
								'codecleanup_options',
								'codecleanup_cdn',
								'codecleanup_extras'
							);

							foreach($option_names as $option_name) {

								//clear selected blog previous option
								delete_blog_option($blog->blog_id, $option_name);

								//grab new option from default blog
								$new_option = get_blog_option($codecleanup_network['default'], $option_name);

								//remove options we don't want to copy
								if($option_name == 'codecleanup_cdn') {
									unset($new_option['cdn_url']);
								}

								//update selected blog with default option
								update_blog_option($blog->blog_id, $option_name, $new_option);

							}

							//Default Settings Updated Notice
							echo "<div class='notice updated is-dismissible'><p>" . __('Default settings applied!', 'codecleanup') . "</p></div>";
						}
						else {
							//Can't Apply to Network Default
							echo "<div class='notice error is-dismissible'><p>" . __('Select a site that is not already the Network Default.', 'codecleanup') . "</p></div>";
						}
					}
					else {
						//Network Default Not Set
						echo "<div class='notice error is-dismissible'><p>" . __('Network Default not set.', 'codecleanup') . "</p></div>";
					}
				}
			}
			else {
				//Blog Not Found Notice
				echo "<div class='notice error is-dismissible'><p>" . __('Error: Blog Not Found.', 'codecleanup') . "</p></div>";
			}
		}
	}

	//Options Updated
	if(isset($_GET['updated'])) {
		echo "<div class='notice updated is-dismissible'><p>" . __('Options saved.', 'codecleanup') . "</p></div>";
	}

	//if no tab is set, default to first/network tab
	if(empty($_GET['tab'])) {
		$_GET['tab'] = 'network';
	} 

	echo "<div class='wrap codecleanup-admin'>";

		//Admin Page Title
  		echo "<h1>" . __('codecleanup Network Settings', 'codecleanup') . "</h1>";

  		//Tab Navigation
		echo "<h2 class='nav-tab-wrapper'>";
			echo "<a href='?page=codecleanup&tab=network' class='nav-tab " . ($_GET['tab'] == 'network' ? 'nav-tab-active' : '') . "'>Network</a>";
			echo "<a href='?page=codecleanup&tab=license' class='nav-tab " . ($_GET['tab'] == 'license' ? 'nav-tab-active' : '') . "'>License</a>";
			echo "<a href='?page=codecleanup&tab=support' class='nav-tab " . ($_GET['tab'] == 'support' ? 'nav-tab-active' : '') . "'>Support</a>";
		echo "</h2>";

		//Network Tab Content
		if($_GET['tab'] == 'network') {

	  		echo "<form method='POST' action='edit.php?action=codecleanup_update_network_options' style='overflow: hidden;'>";
			    settings_fields('codecleanup_network');
			    do_settings_sections('codecleanup_network');
			    submit_button();
	  		echo "</form>";

	  		echo "<form method='POST'>";
	 
	  			echo "<h2>" . __('Apply Default Settings', 'codecleanup') . "</h2>";
	  			echo '<p class="codecleanup-subheading">' . __( 'Choose a site to apply the settings from your network default site.', 'codecleanup' ) . '</p>';

				wp_nonce_field('codecleanup-network-apply', '_wpnonce', true, true);
				echo "<p>" . __('Select a site from the dropdown and click to apply the settings from your network default (above).', 'codecleanup') . "</p>";

				echo "<select name='codecleanup_network_apply_blog'>";
					$sites = array_map('get_object_vars', get_sites(array('deleted' => 0)));
					if(is_array($sites) && $sites !== array()) {
						echo "<option value=''>Select a Site</option>";
						foreach($sites as $site) {
							echo "<option value='" . $site['blog_id'] . "'>" . $site['blog_id'] . ": " . $site['domain'] . $site['path'] . "</option>";
						}
					}
				echo "<select>";

				echo "<input type='submit' name='codecleanup_apply_defaults' value='" . __('Apply Default Settings', 'codecleanup') . "' class='button' />";
			echo "</form>";
		}
		//License Tab Content
		elseif($_GET['tab'] == 'license') {
			if(isset($_POST['codecleanup_save_license'])) {
				if(isset($_POST['codecleanup_edd_license_key'])) {
					//Save License Key
					update_site_option('codecleanup_edd_license_key', $_POST['codecleanup_edd_license_key']);
				}
			}
			if(isset($_POST['codecleanup_edd_license_activate'])) {
				codecleanup_edd_activate_network_license();
			}
			if(isset($_POST['codecleanup_edd_license_deactivate'])) {
				codecleanup_edd_deactivate_network_license();
			}

			$license_info = codecleanup_edd_check_network_license();
			$license = get_site_option('codecleanup_edd_license_key');
			$status = get_site_option('codecleanup_edd_license_status');

			echo "<form method='POST'>";
				echo "<table class='form-table'>";
					echo "<tbody>";

						//License Key
						echo "<tr>";
							echo "<th><label for='codecleanup_edd_license_key'>" . __('License Key', 'codecleanup') . "</label></th>";
							echo "<td>";
								echo "<input id='codecleanup_edd_license_key' name='codecleanup_edd_license_key' type='password' class='regular-text' value='" . $license . "' />";
								echo "<label class='description' for='codecleanup_edd_license_key'>" . __('Enter your license key', 'codecleanup') . "</label>";
							echo "</td>";
						echo "</tr>";
						
						if($license !== false) {

							//Activate/Deactivate License
							echo "<tr>";
								echo "<th>" . __('Activate License', 'codecleanup') . "</th>";
								echo "<td>";
									wp_nonce_field('codecleanup_edd_nonce', 'codecleanup_edd_nonce');
									if($status !== false && $status == 'valid') {
										echo "<input type='submit' class='button-secondary' name='codecleanup_edd_license_deactivate' value='" . __('Deactivate License', 'codecleanup') . "' />";
										echo "<span style='color:green; display: block; margin-top: 10px;'>" . __('License is activated.', 'codecleanup') . "</span>";
									} else {
										if(!empty($license_info->activations_left) && $license_info->activations_left == 'unlimited') {
											echo "<input type='submit' class='button-secondary' name='codecleanup_edd_license_activate' value='" . __('Activate License', 'codecleanup') . "' />";
											echo "<span style='color:red; display: block; margin-top: 10px;'>" . __('License is not activated.', 'codecleanup') . "</span>";
										}
										else {
											echo "<span style='color:red; display: block;'>" . __('Unlimited License needed for use in a multisite environment. Please contact support to upgrade.', 'codecleanup') . "</span>";
										}
									}
								echo "</td>";
							echo "</tr>";

							if(!empty($license_info)) {

								//Customer Email Address
								if(!empty($license_info->customer_email)) {
									echo "<tr>";
										echo "<th>" . __('Customer Email', 'codecleanup') . "</th>";
										echo "<td>" . $license_info->customer_email . "</td>";
									echo "</tr>";
								}

								//License Status (Active/Expired)
								if(!empty($license_info->license)) {
									echo "<tr>";
										echo "<th>" . __('License Status', 'codecleanup') . "</th>";
										echo "<td " . ($license_info->license == "expired" ? "style='color: red;'" : "") . ">";
											echo $license_info->license;
											if(!empty($license) && $license_info->license == "expired") {
												echo "<br /><a href='https://codecleanup.io/checkout/?edd_license_key=" . $license . "&download_id=696' class='button-primary' style='margin-top: 10px;' target='_blank'>" . __('Renew Your License for Updates + Support!', 'codecleanup') . "</a>";
											}
										echo "</td>";
									echo "</tr>";
								}

								//Licenses Used
								if(!empty($license_info->site_count) && !empty($license_info->license_limit)) {
									echo "<tr>";
										echo "<th>" . __('Licenses Used', 'codecleanup') . "</th>";
										echo "<td>" . $license_info->site_count . "/" . $license_info->license_limit . "</td>";
									echo "</tr>";
								}
							}
						}
					echo "</tbody>";
				echo "</table>";
 
 				//Save License Button
				echo "<p class='submit'><input type='submit' name='codecleanup_save_license' class='button button-primary' value='Save License'></p>";

			echo "</form>";
		}

		//Support Tab Content
		elseif($_GET['tab'] == 'support') {
			echo "<h2>" . __('Support', 'codecleanup') . "</h2>";
			echo "<p>For plugin support and documentation, please visit <a href='https://codecleanup.io/' title='codecleanup' target='_blank'>codecleanup.io</a>.</p>";
		}

		//Tooltip Legend
		if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') {
			echo "<div id='codecleanup-legend'>";
				echo "<div id='codecleanup-tooltip-legend'>";
					echo "<span>?</span>Click on tooltip icons to view full documentation.";
				echo "</div>";
			echo "</div>";
		}

		//Tooltip Display Script
		echo "<script>
			(function ($) {
				$('.codecleanup-tooltip').hover(function(){
				    $(this).closest('tr').find('.codecleanup-tooltip-text-container').show();
				},function(){
				    $(this).closest('tr').find('.codecleanup-tooltip-text-container').hide();
				});
			}(jQuery));
		</script>";

	echo "</div>";
}
 
//Update codecleanup Network Options
function codecleanup_update_network_options() {

	//Verify Post Referring Page
  	check_admin_referer('codecleanup_network-options');
 
	//Get Registered Options
	global $new_whitelist_options;
	$options = $new_whitelist_options['codecleanup_network'];

	//Loop Through Registered Options
	foreach($options as $option) {
		if(isset($_POST[$option])) {

			//Update Site Uption
			update_site_option($option, $_POST[$option]);
		}
	}

	//Redirect to Network Settings Page
	wp_redirect(add_query_arg(array('page' => 'codecleanup', 'updated' => 'true'), network_admin_url('settings.php')));

	exit;
}
add_action('network_admin_edit_codecleanup_update_network_options',  'codecleanup_update_network_options');

function codecleanup_edd_activate_network_license() {

	//retrieve the license from the database
	$license = trim(get_site_option('codecleanup_edd_license_key'));

	//data to send in our API request
	$api_params = array(
		'edd_action'=> 'activate_license',
		'license' 	=> $license,
		'item_name' => urlencode(codecleanup_ITEM_NAME), // the name of our product in EDD
		'url'       => home_url()
	);

	//Call the custom API.
	$response = wp_remote_post(codecleanup_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

	//make sure the response came back okay
	if(is_wp_error($response)) {
		return false;
	}

	//decode the license data
	$license_data = json_decode(wp_remote_retrieve_body($response));

	//$license_data->license will be either "valid" or "invalid"
	update_site_option('codecleanup_edd_license_status', $license_data->license);
}

function codecleanup_edd_deactivate_network_license() {

	// retrieve the license from the database
	$license = trim(get_site_option('codecleanup_edd_license_key'));

	// data to send in our API request
	$api_params = array(
		'edd_action'=> 'deactivate_license',
		'license' 	=> $license,
		'item_name' => urlencode(codecleanup_ITEM_NAME), // the name of our product in EDD
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(codecleanup_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

	// make sure the response came back okay
	if(is_wp_error($response)) {
		return false;
	}

	// decode the license data
	$license_data = json_decode(wp_remote_retrieve_body($response));

	// $license_data->license will be either "deactivated" or "failed"
	if($license_data->license == 'deactivated') {
		delete_site_option('codecleanup_edd_license_status');
	}
}

function codecleanup_edd_check_network_license() {

	global $wp_version;

	$license = trim(get_site_option('codecleanup_edd_license_key'));

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode(codecleanup_ITEM_NAME),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post(codecleanup_STORE_URL, array('timeout' => 15, 'sslverify' => false, 'body' => $api_params));

	if(is_wp_error($response)) {
		return false;
	}

	$license_data = json_decode(wp_remote_retrieve_body($response));

	if($license_data->license == 'valid') {
		update_site_option('codecleanup_edd_license_status', "valid");
	}
	else {
		update_site_option('codecleanup_edd_license_status', "invalid");
	}
	
	return($license_data);
}
