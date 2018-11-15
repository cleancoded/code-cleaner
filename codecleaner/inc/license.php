<?php
$license_info = codecleaner_edd_check_license();
$license = get_option('codecleaner_edd_license_key');
$status = get_option('codecleaner_edd_license_status');
settings_fields('codecleaner_edd_license');
?>
<table class="form-table">
	<tbody>

		<!-- License Key -->
		<tr>
			<th>
				<label for='codecleaner_edd_license_key'><?php _e('License Key', 'codecleaner'); ?></label>
			</th>
			<td>
				<input id="codecleaner_edd_license_key" name="codecleaner_edd_license_key" type="password" class="regular-text" value="<?php esc_attr_e($license); ?>" />
				<label class="description" for="codecleaner_edd_license_key"><?php _e('Enter your license key', 'codecleaner'); ?></label>
			</td>
		</tr>
        
		<!-- Activate/Deactivate License -->
		<?php if( false !== $license ) { ?>

			<tr>
				<th>
					<?php _e('Activate License'); ?>
				</th>
				<td>
					<?php if( $status !== false && $status == 'valid' ) { ?>
						<?php wp_nonce_field( 'codecleaner_edd_nonce', 'codecleaner_edd_nonce' ); ?>
						<input type="submit" class="button-secondary" name="codecleaner_edd_license_deactivate" value="<?php _e('Deactivate License', 'codecleaner'); ?>"/>
						<span style="color:green; display: block; margin-top: 10px;"><?php _e('License is activated.', 'codecleaner'); ?></span>
					<?php } else {
						wp_nonce_field( 'codecleaner_edd_nonce', 'codecleaner_edd_nonce' ); ?>
						<input type="submit" class="button-secondary" name="codecleaner_edd_license_activate" value="<?php _e('Activate License', 'codecleaner'); ?>"/>
						<span style="color:red; display: block; margin-top: 10px;"><?php _e('License is not activated.', 'codecleaner'); ?></span>
					<?php } ?>
				</td>
			</tr>
			<?php if(!empty($license_info)) { ?>

				<!-- Customer Email Address -->
				<?php if(!empty($license_info->customer_email)) { ?>
					<tr>
						<th><?php _e('Customer Email', 'codecleaner'); ?></th>
						<td><?php echo $license_info->customer_email; ?></td>
					</tr>
				<?php } ?>

				<!-- License Status (Active/Expired) -->
				<?php if(!empty($license_info->license)) { ?>
					<tr>
						<th><?php _e('License Status', 'codecleaner'); ?></th>
						<td <?php if($license_info->license == "expired"){echo "style='color: red;'";} ?>>
							<?php echo $license_info->license; ?>
							<?php if(!empty($license) && $license_info->license == "expired") { ?>
								<br /><a href="https://cleancoded.com/checkout/?edd_license_key=<?php echo $license; ?>&download_id=696" class="button-primary" style="margin-top: 10px;" target="_blank"><?php _e('Renew Your License for Updates + Support!', 'codecleaner'); ?></a>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>

				<!-- Licenses Used -->
				<?php if(!empty($license_info->site_count) && !empty($license_info->license_limit)) { ?>
					<tr>
						<th><?php _e('Licenses Used', 'codecleaner'); ?></th>
						<td><?php echo $license_info->site_count . "/" . $license_info->license_limit; ?></td>
					</tr>
				<?php } ?>

			<?php } ?>

		<?php } ?>
	</tbody>
</table>
<?php 
if($license === false) {
	submit_button(__('Save License', 'codecleaner'));
}