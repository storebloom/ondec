jQuery( document ).ready( function () {
	jQuery( document ).on( 'click', '#open-app-calendar', function () {
		jQuery( '.my-appointments' ).toggle();
	} );
	
	var datepickcontainer = jQuery( '#ui-datepicker' );
	var appid = datepickcontainer.attr( 'userid' );
	var app_day = datepickcontainer.val();
	
	datepickcontainer.datepicker( {
		dateFormat: 'yy-mm-dd',
		onSelect: function ( app_day ) {
			jQuery.post(
				ajaxurl,
				{
					'action': 'define_calendar',
					'appid': appid,
					'app_day': app_day,
					'nonce': ODApp.AppNonce
				},
				function ( response ) {
					jQuery( '.current_apps' ).html( response );
				}
			);
		}
	} );
	
	jQuery.post(
		ajaxurl,
		{
			'action': 'define_calendar',
			'appid': appid,
			'app_day': app_day,
			'nonce': ODApp.AppNonce
		},
		function ( response ) {
			jQuery( '.current_apps' ).html( response );
		}
	);
	
	// TODO Convert all confrims to dialogue boxes.
	jQuery( document ).on( 'click', '.cancel-app', function () {
		if ( confirm( 'Confirm you\'d like to cancel this appointment.' ) ) {
			var app_day = jQuery( this ).attr( 'app-day' );
			var app_month = jQuery( '.current-app-month' ).val();
			var app_year = jQuery( '.current-app-year' ).val();
			var app_user = jQuery( this ).attr( 'app-user' );
			var app_time = jQuery( this ).attr( 'app-time' );
			var app_num = jQuery( this ).attr( 'num' );
			var app_close_class = '#client-app-' + app_day + '-' + app_num;
			
			jQuery.post(
				ajaxurl, {
					'action': 'approve_cancel_app',
					'app_day': app_day,
					'app_month': app_month,
					'app_year': app_year,
					'app_user': app_user,
					'app_time': app_time,
					'nonce': ODApp.AppNonce
				},
				function ( response ) {
					jQuery( app_close_class ).fadeOut();
				}
			);
		}
	} );
	
	jQuery( document ).on( 'click', '.approve-app', function () {
		var app_day = jQuery( this ).attr( 'app-day' );
		var app_month = jQuery( this ).attr( 'app-month' );
		var app_year = jQuery( this ).attr( 'app-year' );
		var app_user = jQuery( this ).attr( 'app-user' );
		var app_time = jQuery( this ).attr( 'app-time' );
		var app_num = jQuery( this ).attr( 'num' );
		
		jQuery( '#approve-appointment, #deny-appointment' ).attr( 'app-day', app_day ).attr( 'app-month', app_month ).attr( 'app-year', app_year ).attr( 'app-user', app_user ).attr( 'app-time', app_time ).attr( 'num', app_num );
		jQuery( '#approval-appointment' ).show().center();
		jQuery( '.selected-pending' ).removeClass( 'selected-pending' );
		jQuery( this ).addClass( 'selected-pending' );
	} );
	
	jQuery( document ).on( 'click', '#close-appointment', function () {
		jQuery( '#approval-appointment' ).hide();
	} );
	
	// TODO Look into the approve functionality.
	jQuery( document ).on( 'click', '#approve-appointment, #deny-appointment', function() {
		var app_day = jQuery( this ).attr( 'app-day' );
		var app_month = jQuery( this ).attr( 'app-month' );
		var app_year = jQuery( this ).attr( 'app-year' );
		var app_user = jQuery( this ).attr( 'app-user' );
		var app_time = jQuery( this ).attr( 'app-time' );
		var app_num = jQuery( this ).attr( 'num' );
		var app_message = jQuery( '#app-approval-message' ).val();
		var x = Math.floor( ( Math.random() * 100000000000 ) + 1 );
		var messageid = app_user + "_" + x;
		var type = jQuery( this ).attr( 'id' );
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'add_usermessage',
				'usermessage': app_message,
				'msgid': app_user,
				'messageid': messageid,
				'type': type,
				'nonce': ODApp.AppNonce
			},
			function() {
				jQuery( '#app-approval-message' ).val( '' );
			}
		);
		
		if ( type === 'deny-appointment' ) {
			var app_close_class = '#client-app-' + app_day + '-' + app_num;
			
			jQuery.post(
				ajaxurl, {
					'action': 'approve_cancel_app',
					'app_day': app_day,
					'app_month': app_month,
					'app_year': app_year,
					'app_user': app_user,
					'app_time': app_time,
					'nonce': ODApp.AppNonce
				},
				function() {
					jQuery( '#approval-appointment' ).hide();
					jQuery( app_close_class ).fadeOut();
				}
			);
		}
		
		if ( type === 'approve-appointment' ) {
			jQuery.post(
				ajaxurl, {
					'action': 'approve_cancel_app',
					'app_day': app_day,
					'app_month': app_month,
					'app_year': app_year,
					'app_user': app_user,
					'app_time': app_time,
					'type': 'approve',
					'nonce': ODApp.AppNonce
				},
				function() {
					jQuery( '#approval-appointment' ).hide();
				}
			);
		}
		
		jQuery( '.selected-pending' ).fadeOut();
	} );
	
	jQuery( document ).on( 'click', '#submit-appointment-settings', function() {
		var count = jQuery( '#app-settings-count' ).val();
		var duration = jQuery( '#app-settings-duration' ).val();
		var start = jQuery( '#app-settings-start option:selected' ).val();
		var enabled = jQuery( '#enable-appointments' ).prop( 'checked' );
		
		jQuery.post(
			ajaxurl, {
				'action': 'appointment_settings',
				'count': count,
				'duration': duration,
				'start': start,
				'enabled': enabled,
				'nonce': ODApp.AppNonce
			},
			function() {
				jQuery( '.setting-success' ).fadeIn().delay( 400 ).fadeOut();
			}
		);
	} );
	
	jQuery( '#open-app-settings' ).click( function() {
		jQuery( '.appointment-options' ).toggle();
	} );
	
	jQuery( document ).on( 'click', '.app-time', function() {
		var appentry = jQuery( this ).val();
		var iteration = jQuery( this ).attr( 'id' );
		var dayclass = ".apps-" + iteration;
		var app_day = jQuery( dayclass ).attr( 'id' );
		var appid = jQuery( dayclass ).val();
		var app_close = ".app-" + iteration;
		
		if ( confirm( 'Confirm you would like to set an appointment at ' + appentry ) ) {
			jQuery.post(
				ajaxurl,
				{
					'action': 'add_app',
					'appid': appid,
					'app_day': app_day,
					'appentry': appentry,
					'nonce': ODApp.AppNonce
				},
				function() {
					jQuery( app_close ).fadeOut( 300 );
					jQuery( '.app_success' ).slideUp( 800 ).fadeIn( 400 ).slideDown( 300 ).delay( 800 ).fadeOut( 400 );
				}
			);
		}
	} );
	
	jQuery.post(
		ajaxurl,
		{
			'action': 'define_profile_calendar',
			'nonce': ODApp.AppNonce
		},
		function( response ) {
			jQuery( '.calendar-wrapper' ).html( response );
		}
	);
	
	jQuery( document ).on( 'change', '.current-app-year, .current-app-month', function() {
		
		var app_year = jQuery( '.current-app-year' ).val();
		var app_month = jQuery( '.current-app-month' ).val();
		
		if ( app_month.toString().length === 1 ) {
			app_month = "0" + app_month;
		}
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'define_profile_calendar',
				'app_month': app_month,
				'app_year': app_year,
				'nonce': ODApp.AppNonce
			},
			function( response ) {
				jQuery( '.calendar-wrapper' ).html( response );
			}
		);
	} );
	
	jQuery.fn.center = function () {
		this.css( "position", "absolute" );
		this.css( "top", Math.max( 0, (
				(
				jQuery( window ).height() - jQuery( this ).outerHeight()
				) / 2
				) + jQuery( window ).scrollTop() ) + "px" );
		this.css( "left", Math.max( 0, (
				(
				jQuery( window ).width() - jQuery( this ).outerWidth()
				) / 2
				) + jQuery( window ).scrollLeft() ) + "px" );
		return this;
	}
} );