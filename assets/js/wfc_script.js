jQuery(document).ready(function($){

	$('#wfc_pages').select2()

	// Check logined or not
	if (localStorage.getItem('accessToken')) {

		if ($('.wfc-account').length > 0) {
			$('.wfc-account').css('display', 'inline-flex');
		} else {
			$('#top-menu-nav ul').append(logout_menu);
			$('.wfc-account').css('display', 'inline-flex');
		}
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

let logout_menu = `<li id="menu-item-999" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
					  <a href="#">Logout</a>
				   </li>`;