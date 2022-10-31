jQuery(document).ready(function($){

	$('#wfc_pages').select2()

	// Check logined or not
	if (localStorage.getItem('accessToken')) {
		$('.wfc-account').css('display', 'inline-flex');
	}

	// When Click logout button
	$('.wfc-account').on('click', function(e) {
		localStorage.removeItem('accessToken');
		window.location.reload();
	});

	$('.wfc-tab-link').on('click', (e) => {
		$('.wfc-tabs-container .tab-container').css('display', 'none');
		$('.wfc-tabs .wfc-tab-link').removeClass('active');
		$(e.target).addClass('active');
		let targetContainer = $(e.target).attr('tab-target');
		$(targetContainer).css('display', 'block');
	});
});