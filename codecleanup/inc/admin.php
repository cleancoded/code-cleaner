<?php 
//if no tab is set, default to first/options tab
if(empty($_GET['tab'])) {
	$_GET['tab'] = 'options';
} 
?>
<div class="wrap codecleanup-admin">

	<!-- Plugin Admin Page Title -->
	<h2>Code Cleanup Settings</h2>

	<!-- Optimization Guide Notice -->
	<div class="notice notice-info">
    	<p><?php _e( 'Check out our <a href="https://cleancoded.com/speed-up-wordpress/" title="WordPress Optimization Guide" target="_blank">complete optimization guide</a> for more ways to speed up WordPress.', 'codecleanup' ); ?></p>
    </div>

    <!-- Tab Navigation -->
	<h2 class="nav-tab-wrapper">
		<a href="?page=codecleanup&tab=options" class="nav-tab <?php echo $_GET['tab'] == 'options' || '' ? 'nav-tab-active' : ''; ?>">Options</a>
		<a href="?page=codecleanup&tab=cdn" class="nav-tab <?php echo $_GET['tab'] == 'cdn' ? 'nav-tab-active' : ''; ?>">CDN</a>
		<a href="?page=codecleanup&tab=extras" class="nav-tab <?php echo $_GET['tab'] == 'extras' ? 'nav-tab-active' : ''; ?>">Extras</a>
		<a href="?page=codecleanup&tab=support" class="nav-tab <?php echo $_GET['tab'] == 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
	</h2>

	<!-- Plugin Options Form -->
	<form method="post" action="options.php">

		<!-- Main Options Tab -->
		<?php if($_GET['tab'] == 'options') { ?>

		    <?php settings_fields( 'codecleanup_options' ); ?>
		    <?php do_settings_sections( 'codecleanup_options' ); ?>
			<?php submit_button(); ?>

		<!-- CDN Tab -->
		<?php } elseif($_GET['tab'] == 'cdn') { ?>

			<?php settings_fields( 'codecleanup_cdn' ); ?>
		    <?php do_settings_sections( 'codecleanup_cdn' ); ?>
			<?php submit_button(); ?>

		<!-- Extras Tab -->
		<?php } elseif($_GET['tab'] == 'extras') { ?>

			<?php settings_fields( 'codecleanup_extras' ); ?>
		    <?php do_settings_sections( 'codecleanup_extras' ); ?>
			<?php submit_button(); ?>

		
		<?php } elseif($_GET['tab'] == 'support') { ?>

			<h2>Support</h2>
			<p>For plugin support and documentation, please visit <a href='https://codecleanup.io/' title='codecleanup' target='_blank'>codecleanup.io</a>.</p>

		<?php } ?>
	</form>

	<?php if($_GET['tab'] != 'support') { ?>

		<div id="codecleanup-legend">
			<div id="codecleanup-tooltip-legend">
				<span>?</span>Click on tooltip icons to view full documentation.
			</div>
		</div>

	<?php } ?>

	<script>
		(function ($) {
			$(".codecleanup-tooltip").hover(function(){
			    $(this).closest("tr").find(".codecleanup-tooltip-text-container").show();
			},function(){
			    $(this).closest("tr").find(".codecleanup-tooltip-text-container").hide();
			});
		}(jQuery));
	</script>
	
</div>