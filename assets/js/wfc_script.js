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
	} else {
		if ($('.wfc-account').length > 0) {
			$('.wfc-account').css('display', 'inline-flex');
		} else {
			$('#top-menu-nav ul').append(login_menu);
			$('.wfc-account').css('display', 'inline-flex');
		}
	}

	// When Click logout button
	$('.wfc-account').on('click', function(e) {

		if ($(this).attr('data-action') == 'logout') {
			localStorage.removeItem('accessToken');
			window.location.reload();
		}

		if ($(this).attr('data-action') == 'login') {
			let loginUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '#/authentication/login'
			window.location.href = loginUrl;
		}
	});

	$('.wfc-tab-link').on('click', (e) => {
		$('.wfc-tabs-container .tab-container').css('display', 'none');
		$('.wfc-tabs .wfc-tab-link').removeClass('active');
		$(e.target).addClass('active');
		let targetContainer = $(e.target).attr('tab-target');
		$(targetContainer).css('display', 'block');
	});
});

const login_menu = `<li id="menu-item-999" data-action="login" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
					  <a href="#">Login</a>
				   </li>`;

const logout_menu = `<li id="menu-item-999" data-action="logout" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
					  <a href="#">Logout</a>
				   </li>`;