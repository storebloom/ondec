jQuery( document ).ready( function () {
	'use strict';
	
	reloadList( 'load_messages' );
	
	jQuery( document ).on( 'click', '#submit-decstatus', function() {
		var decContainer = jQuery( '#decstatus' );
		
		if ( decContainer.val() === 'ondec' ) {
			jQuery( this ).removeClass( 'currently-offdec' ).addClass( 'currently-ondec' );
			jQuery( this ).val( 'Currently ondec' );
			decContainer.val( 'offdec' );
			
			var decstatus = 'ondec';
		} else {
			jQuery( this ).removeClass( 'currently-ondec' ).addClass( 'currently-offdec' );
			decContainer.val( 'ondec' );
			jQuery( this ).val( 'Currently offdec' );
			
			decstatus = 'offdec';
		}
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'update_userdata',
				'datatype': 'decstatus',
				'data': decstatus,
				'nonce': ODDash.DashNonce
			}
		);
	} );
	
	jQuery( '#edit-datepicker' ).datepicker( {
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'MM yy',
		onClose: function( dateText, inst ) {
			var month = jQuery( '#ui-datepicker-div .ui-datepicker-month :selected' ).val();
			var year = jQuery( '#ui-datepicker-div .ui-datepicker-year :selected' ).val();
			
			jQuery( this).datepicker( 'setDate', new Date( year, month, 1 ) );
		}
	});
	
	jQuery( document ).on( 'click', '.approve-biz', function() {
		var bizid = jQuery( this ).attr( 'id' );
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'approve_user',
				'requestid': bizid,
				'approvemeta': 'my_businesses',
				'requestmeta': 'my_professionals',
				'nonce': ODDash.DashNonce
			},
			function(){
				jQuery( '#dash-success' ).html( 'Business Approved!' ).slideUp( 800 ).fadeIn( 400 ).delay( 800 ).fadeOut( 400 );
				reloadList( 'business' );
			}
		);
	} );
	
	jQuery( document ).on( 'click', '.decremove', function() {
		var rmdecid = jQuery( this ).attr( 'id' );
		var rmclass = '.decmember-' + rmdecid;
		
		if ( confirm( 'Do you really want to remove this user from your list?' ) ) {
			jQuery.post(
				ajaxurl,
				{
					'action': 'remove_user',
					'removeid': rmdecid,
					'removemeta': 'my_followers',
					'requestmeta': 'my_dec',
					'nonce': ODDash.DashNonce
				},
				function(){
					jQuery( rmclass ).slideDown( 800 ).fadeOut( 400 );
					jQuery( '#dash-success' ).html( 'User Removed!' ).slideUp( 800 ).fadeIn( 400 ).delay( 800 ).fadeOut( 400 );
					reloadList( 'follower' );
				}
			);
		}
	});
	
	jQuery( document ).on( 'click', '#statussubmit', function() {
		var userstatus = jQuery( '#userstatus' ).val();
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'update_userdata',
				'datatype': 'userstatus',
				'data': userstatus,
				'nonce': ODDash.DashNonce
			},
			function() {
				successMessage( this, 'Status Update!' );
			}.bind( this )
		);
	});
	
	function reloadList( type ) {
		jQuery.post(
			ajaxurl,
			{
				'action': type,
				'nonce': ODDash.DashNonce
			},
			function( results ) {
				var container = '#' + type + '_container';
				
				jQuery( container ).html( results );
				
				if ( 'load_messages' === type ) {
					var unreadCount = jQuery( 'span#unread-count' ).html();
					
					notifyUnread( unreadCount );
				}
			}
		)
	}
	
	function notifyUnread( count ) {
		if ( 1 === count ) {
			jQuery( '#main' ).prepend( '<div>You have ' + count + ' unread message!</div>' );
		}
		
		if ( 1 < count ) {
			jQuery( '#main' ).prepend( '<div>You have ' + count + ' unread messages!</div>' );
		}
	}
	
	jQuery( document ).on( 'click', '#closeMessage', function() {
		jQuery( '#messagepop-user' ).html( '' );
		jQuery( '#messagepop-message' ).html( '' );
		jQuery( '#messagepop-wrapper' ).hide();
	});
	
	jQuery( document ).on( 'click', '.view-button', function() {
		var message_id = jQuery( this ).attr( 'id' );
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'get_message',
				'message_id': message_id,
				'nonce': ODDash.DashNonce
			},
			function( messageInfo ) {
				jQuery( '#messagepop-wrapper' ).show().center();
				jQuery( '#messagepop-user' ).html( messageInfo.data.user );
				jQuery( '#messagepop-message' ).html( messageInfo.data.message );
			}
		)
		
		if( jQuery( this ).val() === 'unread' ){
			reloadList( 'message' );
		}
		
		jQuery( this ).val( 'read' );
	} );
	
	jQuery( document ).on( 'click', '.closeEndorsement', function() {
		jQuery( '#endorsepop-user' ).html( '' );
		jQuery( '#endorsepop-message' ).html( '' );
		jQuery( '#endorsepop-wrapper' ).hide();
	});
	
	jQuery( document ).on( 'click', '.view-endorsement-button', function() {
		var endorsement_id = jQuery( this ).attr( 'id' );
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'get_endorsement',
				'endorsement_id': endorsement_id,
				'nonce': ODDash.DashNonce
			},
			function( endorsement ) {
				jQuery( '#endorsepop-user' ).html( endorsement.user );
				jQuery( '#endorsepop-message' ).html( endorsement.message );
				jQuery( '.approve-endorsement' ).attr( 'id', endorsement_id );
				jQuery( '#endorsepop-wrapper' ).center();
			}
		)
	});
	
	jQuery( document ).on( 'click', '.approve-endorsement', function() {
		var endorseid = jQuery( this ).attr( 'id' );
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'approve_user',
				'requestid': endorseid,
				'approvemeta': 'my_endorsements',
				'nonce': ODDash.DashNonce
			},
			function(response){
				jQuery( '#dash-success' ).html( 'Endorsement Approved!' ).slideUp( 800 ).fadeIn( 400 ).delay( 800 ).fadeOut( 400 );
				reloadList( 'endorsement' );
			}
		);
		
	});
	
	jQuery('.removeendorsement').click(function(){
		var rmendid = jQuery(this).attr('id');
		var rmendclass = ".decend-" + rmendid;
		
		if (window.confirm("Do you really want to remove this endorsement?")) {
			
			jQuery.post(
				
				ajaxurl,
				{
					'action': 'remove_end',
					'rmendid': rmendid
				},
				function(response){
					
					jQuery(rmendclass).slideDown(800).fadeOut(400);
					jQuery("#rmendsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
				}
			);
			
			jQuery('#endorsement-count').text(jQuery('#endorsement-count').text()-1);
		}
	});
	
	jQuery('.not-current-location').click(function(){
		var currentloc = jQuery(this).attr('id');
		var specificid = "#" + currentloc;
		
		if (window.confirm("Confirm this is your current location?")) {
			jQuery.post(
				ajaxurl,
				{
					'action': 'add_current_location',
					'currentloc': currentloc
				},
				function(response){
					jQuery('.current-location').removeClass('current-location').addClass('not-current-location').val('set location');
					jQuery(specificid).removeClass('not-current-location').addClass('current-location');
					jQuery('.current-location').val('current location');
					jQuery('#currentlocmsg').slideUp(800).fadeIn(400).delay(800).fadeOut(400);
				}
			);
		}
	});
	
	jQuery('.current-location').click(function(){
		
		if (window.confirm("Remove this is as your current location?")) {
			
			jQuery.post(
				
				ajaxurl,
				{
					'action': 'remove_current_location'
				},
				function(response){
					
					jQuery('.current-location').val('set location');
					jQuery('.current-location').addClass('not-current-location').removeClass('current-location');
				}
			);
		}
	});
	
	jQuery('#professional-type').click(function(){
		var typeselected = jQuery('.pro-types:checked').serialize();
		ajaxurl,
			{
				'action': 'add_pro_type',
				'typeselected': typeselected
			},
			function(response) {
				alert(response);
				jQuery("#typesuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
			}
	});
	
	jQuery("#professional-type").click(function(){
		
		var typeselected = jQuery('.pro-types:checked').serialize();
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'add_pro_type',
				'typeselected': typeselected
			},
			function(response){
				
				jQuery("#typesuccess").slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);
			}
		);
	});
	
	jQuery("#msgsubmit").click(function(){
		
		var decmessage = jQuery('#decmessage').val();
		
		jQuery.post(
			ajaxurl,
			{
				'action': 'add_decmessage',
				'decmessage': decmessage
			},
			function(response){
				
				jQuery("#msgsuccess").slideUp(800).fadeIn(400);
				jQuery("#msgsuccess").slideDown(300).delay(800).fadeOut(400);
				
			}
		);
	});
	
	jQuery('.approvebiz').click(function(){
		var approvedecid = jQuery(this).attr('id');
		var approveclass = ".decrequest-" + approvedecid;
		
		jQuery.post(
			
			ajaxurl,
			{
				'action': 'approve_biz_request',
				'approvebizrequest': approvedecid
			},
			function(response){
				
				jQuery(approveclass).slideDown(800).fadeOut(400);
				jQuery("#approvesuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
			}
		);
	});
	
	jQuery('.removebiz').click(function(){
		var removedecid = jQuery(this).attr('id');
		var removeclass = ".decrequest-" + removedecid;
		
		jQuery.post(
			
			ajaxurl,
			{
				'action': 'remove_biz_request',
				'removebizrequest': removedecid
			},
			function(response){
				
				jQuery(removeclass).slideDown(800).fadeOut(400);
				jQuery("#removesuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
			}
		);
	});
	
	//new message notification
	var new_message_count = jQuery('#unread-count').html();
	jQuery('#new-message').html(new_message_count);
	
	jQuery('input.typeahead').typeahead({
		name: 'typeahead',
		remote:'/wp-content/plugins/od-user-search/includes/od-search-data-pro.php?key=%QUERY',
		limit : 10
	});
	
	//Show modal box
	//Hide modal box
	jQuery('#closeSearchModal').click(
		
		function() {
			jQuery('.od-searchBoxWrap').hide();
		}
	);
	
	jQuery(document).on('click', '#add-a-business', function(){
		
		jQuery('.add-a-business-form').show().center();
	});
	
	jQuery(document).on('click', '.close-aab-form', function(){
		
		jQuery('.add-a-business-form').hide();
	});
	
	//Submit business registration temp account for professionals
	jQuery(document).on('click', '#submit-new-business-aab', function(){
		
		var business_name = jQuery('#aab-business-name').val();
		var business_address = jQuery('#aab-business-address').val();
		alert(business_name);
		jQuery.post(
			ajaxurl,
			{
				'action': 'register_temp_business',
				'business_name': business_name,
				'business_address': business_address
			},
			function(response){
				alert(response);
				jQuery('.add-a-business-form').hide();
				
			}
		);
	});
	
	jQuery.fn.center = function () {
		this.css("position","absolute");
		this.css("top", Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) +
		                jQuery(window).scrollTop()) + "px");
		this.css("left", Math.max(0, ((jQuery(window).width() - jQuery(this).outerWidth()) / 2) +
		                 jQuery(window).scrollLeft()) + "px");
		return this;
	}
	
	function successMessage( button, message ) {
		jQuery( button ).after( '<div id="dash-success">' + message + '</div>' );
		jQuery( '#dash-success' ).delay( 800 ).fadeOut( 400 );
	}

} );