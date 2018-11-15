//Dynamic Form Selection
jQuery(document).ready(function($) {

	/*Disable Radio*/
	$('.codecleaner-disable-select').on('change', function(ev) {
		if($(this).val() == 'everywhere') {
			$(this).closest('.codecleaner-script-manager-controls').find('.codecleaner-script-manager-enable').show();
		}
		else {
			$(this).closest('.codecleaner-script-manager-controls').find('.codecleaner-script-manager-enable').hide();
		}
	});	

	/*Script Status*/
	$('.codecleaner-script-manager-status .codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('tr').find('.codecleaner-script-manager-controls').hide();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('tr').find('.codecleaner-script-manager-controls').show();
		}
	});
	$('.codecleaner-script-manager-status .codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('tr').find('.codecleaner-script-manager-controls').show();
		}
		else {
			$(this).closest('tr').find('.codecleaner-script-manager-controls').hide();
		}
	});
	
	/*Group Status*/
	$('.codecleaner-script-manager-group-status .codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section .codecleaner-script-manager-assets-disabled').hide();
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section table').show();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section table').hide();
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section .codecleaner-script-manager-assets-disabled').show();
		}
	});
	$('.codecleaner-script-manager-group-status .codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section table').hide();
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section .codecleaner-script-manager-assets-disabled').show();
		}
		else {
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section .codecleaner-script-manager-assets-disabled').hide();
			$(this).closest('.codecleaner-script-manager-group').find('.codecleaner-script-manager-section table').show();
		}
	});

});