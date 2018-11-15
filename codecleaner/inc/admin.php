<?php 
//if no tab is set, default to first/options tab
if(empty($_GET['tab'])) {
	$_GET['tab'] = 'default';
} 
?>
<div class="wrap codecleaner-admin">

	<!-- Plugin Admin Page Title -->
	<h2><?php _e('Code Cleaner Settings', 'codecleaner'); ?></h2>

    <!-- Tab Navigation -->
	<h2 class="nav-tab-wrapper">
		<a href="?page=codecleaner&tab=default" class="nav-tab <?php echo $_GET['tab'] == 'default' || '' ? 'nav-tab-active' : ''; ?>"><?php _e('Default', 'codecleaner'); ?></a>
		<a href="?page=codecleaner&tab=cdn" class="nav-tab <?php echo $_GET['tab'] == 'cdn' ? 'nav-tab-active' : ''; ?>"><?php _e('CDN', 'codecleaner'); ?></a>
		<a href="?page=codecleaner&tab=ga" class="nav-tab <?php echo $_GET['tab'] == 'ga' ? 'nav-tab-active' : ''; ?>"><?php _e('Google Analytics', 'codecleaner'); ?></a>
		<a href="?page=codecleaner&tab=more" class="nav-tab <?php echo $_GET['tab'] == 'more' ? 'nav-tab-active' : ''; ?>"><?php _e('More', 'codecleaner'); ?></a>
		<?php if(!is_plugin_active_for_network('codecleaner/codecleaner.php')) { ?>
			<a href="?page=codecleaner&tab=license" class="nav-tab <?php echo $_GET['tab'] == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e('License', 'codecleaner'); ?></a>
		<?php } ?>
		<a href="?page=codecleaner&tab=support" class="nav-tab <?php echo $_GET['tab'] == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'codecleaner'); ?></a>
	</h2>

	<!-- Plugin Options Form -->
	<form method="post" action="options.php">

		<!-- Main Options Tab -->
		<?php if($_GET['tab'] == 'default') { ?>

		    <?php settings_fields('codecleaner_options'); ?>
		    <?php do_settings_sections('codecleaner_options'); ?>
			<?php submit_button(); ?>

		<!-- CDN Tab -->
		<?php } elseif($_GET['tab'] == 'cdn') { ?>

			<?php settings_fields('codecleaner_cdn'); ?>
		    <?php do_settings_sections('codecleaner_cdn'); ?>
			<?php submit_button(); ?>

		<!-- Google Analytics Tab -->
		<?php } elseif($_GET['tab'] == 'ga') { ?>

			<?php settings_fields('codecleaner_ga'); ?>
		    <?php do_settings_sections('codecleaner_ga'); ?>
			<?php submit_button(); ?>

		<!-- Extras Tab -->
		<?php } elseif($_GET['tab'] == 'more') { ?>

			<?php settings_fields('codecleaner_extras'); ?>
		    <?php do_settings_sections('codecleaner_extras'); ?>
			<?php submit_button(); ?>

		<!-- License and Activation Tab -->
		<?php } elseif($_GET['tab'] == 'license') { ?>

			<?php require_once('license.php'); ?>

		<!-- Support Tab -->
		<?php } elseif($_GET['tab'] == 'support') { ?>

			<h2><?php _e('Support', 'codecleaner'); ?></h2>
			<p><?php _e("For plugin support and documentation, please visit <a href='https://cleancoded.com/cleaner/' title='codecleaner' target='_blank'>cleancoded.com</a>.", 'codecleaner'); ?></p>

		<?php } ?>
	</form>

	<?php if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') { ?>

		<div id="codecleaner-legend">
			<div id="codecleaner-tooltip-legend">
				<span>?</span><?php _e('Click on tooltip icons to view full documentation.', 'codecleaner'); ?>
			</div>
		</div>

	<?php } ?>

	<script>
		(function ($) {
			$(".codecleaner-tooltip").hover(function(){
			    $(this).closest("tr").find(".codecleaner-tooltip-text-container").show();
			},function(){
			    $(this).closest("tr").find(".codecleaner-tooltip-text-container").hide();
			});
		}(jQuery));
	</script>
	
</div>