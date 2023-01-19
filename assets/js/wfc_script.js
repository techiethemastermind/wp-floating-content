jQuery(document).ready(function($){

	$('#wfc_pages').select2();

	// Check logined or not
	if (localStorage.getItem('accessToken') && localStorage.getItem('user')) {

		let accountMenu = $(account_menu);
		let user = JSON.parse(localStorage.getItem('user'));

		accountMenu.find('span#imma-account-name').text(user.name);

		if ($('.wfc-account').length < 1) {
			$('#top-menu-nav ul').append(accountMenu);
			$('#mobile_menu').append(logout_menu);
		}
		
	} else {
		if ($('.wfc-account').length < 1) {
			$('#top-menu-nav ul').append(login_menu);
			$('#mobile_menu').append(login_menu);
		}
	}

	$('#top-menu-nav ul').find('.wfc-account').addClass('wfc-flex');
	$('#mobile_menu').find('.wfc-account').addClass('wfc-block');

	// When Click logout button
	$('.wfc-account').on('click', function(e) {

		e.preventDefault();
		e.stopPropagation();

		if ($(this).attr('data-action') == 'login') {
			let loginUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + '/imma/#/authentication/login'
			window.location.href = loginUrl;
		}

		if ($(e.target).attr('id') && $(e.target).attr('id') === 'imma-account-logout') {
			localStorage.removeItem('accessToken');
			localStorage.removeItem('user');
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
						<a href="/imma/#/authentication/login">Login</span>
				   	</li>`;

const logout_menu = `<li id="menu-item-999" data-action="logout" 
						class="wfc-account menu-item menu-item-type-post_type menu-item-object-page menu-item-51 et_first_mobile_item">
						<a href="javascript:void(0)" id="imma-account-logout">Logout</a>
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