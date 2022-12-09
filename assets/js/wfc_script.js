jQuery(document).ready(function($){

	$('#wfc_pages').select2()

	// Check logined or not
	if (localStorage.getItem('accessToken')) {

		let accountMenu = $(account_menu);

		let user = JSON.parse(localStorage.getItem('user'));

		if (user) {
			accountMenu.find('span#imma-account-name').text(user.name)
		}

		if ($('.wfc-account').length > 0) {
			$('.wfc-account').css('display', 'inline-flex');
		} else {
			$('#top-menu-nav ul').append(accountMenu);
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

		e.preventDefault();
		e.stopPropagation();

		if ($(this).attr('data-action') == 'login') {
			let loginUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '#/authentication/login-unguarded'
			window.location.href = loginUrl;
		}

		if ($(e.target).attr('id') && $(e.target).attr('id') === 'imma-account-logout') {
			localStorage.removeItem('accessToken');
			$('.wfc-account').find('.imma-dropdown').hide('fast');
			window.location.reload();
		} else if ($(this).attr('data-action') == 'account') {
			
			if ($(e.target).attr('id') !== 'imma-account-name') {
				$dropdown = $(this).find('.imma-dropdown').show('fast');
			}			
		}
	});

	$('.wfc-tab-link').on('click', (e) => {
		$('.wfc-tabs-container .tab-container').css('display', 'none');
		$('.wfc-tabs .wfc-tab-link').removeClass('active');
		$(e.target).addClass('active');
		let targetContainer = $(e.target).attr('tab-target');
		$(targetContainer).css('display', 'block');
	});

	// Close the dropdown menu if the user clicks outside of it
	window.onclick = function (event) {
		if (!event.target.matches('.imma-dropdown')) {
			$('.wfc-account').find('.imma-dropdown').hide('fast');
		}
	}
});

const login_menu = `<li id="menu-item-999" data-action="login" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
						<span>Login</span>
				   	</li>`;

const logout_menu = `<li id="menu-item-999" data-action="logout" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
						<span>Logout</span>
				   	</li>`;

const account_menu = `<li id="menu-item-999" data-action="account" class="wfc-account menu-item menu-item-type-custom menu-item-object-custom">
					   	<span>My Account</span>
					   	<div class="imma-dropdown">
							<span id="imma-account-name" class="border-bottom">Member</span>
						   	<span>
								<button id="imma-account-logout">Logout</button>
							</span>
					   	</div>
					</li>`;