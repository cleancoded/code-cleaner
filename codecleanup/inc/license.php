<?php
$license_info = codecleanup_edd_check_license();
$license = get_option('codecleanup_edd_license_key');
$status = get_option('codecleanup_edd_license_status');
settings_fields('codecleanup_edd_license');
?>
<table class="form-table">
	<tbody>

		<!-- License Key -->
		<tr valign="top">
			<th scope="row" valign="top">
				<label for='codecleanup_edd_license_key'><?php _e('License Key'); ?></label>
			</th>
			<td>
				<input id="codecleanup_edd_license_key" name="codecleanup_edd_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
				<label class="description" for="codecleanup_edd_license_key"><?php _e('Enter your license key'); ?></label>
			</td>
		</tr>
		<?php if( false !== $license ) { ?>

			<!-- Activate/Deactivate License -->
			<tr valign="top">
				<th scope="row" valign="top">
					<?php _e('Activate License'); ?>
				</th>
				<td>
					<?php if( $status !== false && $status == 'valid' ) { ?>
						<?php wp_nonce_field( 'codecleanup_edd_nonce', 'codecleanup_edd_nonce' ); ?>
						<input type="submit" class="button-secondary" name="codecleanup_edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
						<span style="color:green; display: block; margin-top: 10px;"><?php _e('License is activated.'); ?></span>
					<?php } else {
						wp_nonce_field( 'codecleanup_edd_nonce', 'codecleanup_edd_nonce' ); ?>
						<input type="submit" class="button-secondary" name="codecleanup_edd_license_activate" value="<?php _e('Activate License'); ?>"/>
						<span style="color:red; display: block; margin-top: 10px;"><?php _e('License is not activated.'); ?></span>
					<?php } ?>
				</td>
			</tr>
			<?php if(!empty($license_info)) { ?>

				<!-- Customer Email Address -->
				<?php if(!empty($license_info->customer_email)) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Customer Email'); ?>
						</th>
						<td>
							<?php echo $license_info->customer_email; ?>
						</td>
					</tr>
				<?php } ?>

				<!-- License Status (Active/Expired) -->
				<?php if(!empty($license_info->license)) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Status'); ?>
						</th>
						<td <?php if($license_info->license == "expired"){echo "style='color: red;'";} ?>>
							<?php echo $license_info->license; ?>
							<?php if(!empty($license) && $license_info->license == "expired") { ?>
								<br /><a href="https://codecleanup.io/checkout/?edd_license_key=<?php echo $license; ?>&download_id=696" class="button-primary" style="margin-top: 10px;" target="_blank"><?php _e('Renew Your License for Updates + Support!'); ?></a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>

				<!-- Licenses Used -->
				<?php if(!empty($license_info->site_count) && !empty($license_info->license_limit)) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('Licenses Used'); ?>
						</th>
						<td>
							<?php echo $license_info->site_count . "/" . $license_info->license_limit; ?>
						</td>
					</tr>
				<?php } ?>

			<?php } ?>

		<?php } ?>
	</tbody>
</table>
<?php 
if($license === false) {
	submit_button("Save License");
}