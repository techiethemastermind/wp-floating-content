

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
});