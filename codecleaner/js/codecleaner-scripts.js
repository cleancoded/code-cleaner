//Dynamic Form Selection
jQuery(document).ready(function($) {

	/*Disable Radio*/
	$('.codecleaner-disable-select').on('change', function(ev) {
		if($(this).val() == 'everywhere') {
			$(this).closest('.codecleaner-deep-cleaning-controls').find('.codecleaner-deep-cleaning-enable').show();
		}
		else {
			$(this).closest('.codecleaner-deep-cleaning-controls').find('.codecleaner-deep-cleaning-enable').hide();
		}
	});	

	/*Script Status*/
	$('.codecleaner-deep-cleaning-status .codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('tr').find('.codecleaner-deep-cleaning-controls').hide();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('tr').find('.codecleaner-deep-cleaning-controls').show();
		}
	});
	$('.codecleaner-deep-cleaning-status .codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('tr').find('.codecleaner-deep-cleaning-controls').show();
		}
		else {
			$(this).closest('tr').find('.codecleaner-deep-cleaning-controls').hide();
		}
	});
	
	/*Group Status*/
	$('.codecleaner-deep-cleaning-group-status .codecleaner-status-select').on('change', function(ev) {
		if($(this).children(':selected').val() == 'enabled') {
			$(this).removeClass('disabled');
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section .codecleaner-deep-cleaning-assets-disabled').hide();
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section table').show();
		}
		else {
			$(this).addClass('disabled');
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section table').hide();
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section .codecleaner-deep-cleaning-assets-disabled').show();
		}
	});
	$('.codecleaner-deep-cleaning-group-status .codecleaner-status-toggle').on('change', function(ev) {
		if($(this).is(':checked')) {
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section table').hide();
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section .codecleaner-deep-cleaning-assets-disabled').show();
		}
		else {
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section .codecleaner-deep-cleaning-assets-disabled').hide();
			$(this).closest('.codecleaner-deep-cleaning-group').find('.codecleaner-deep-cleaning-section table').show();
		}
	});

});