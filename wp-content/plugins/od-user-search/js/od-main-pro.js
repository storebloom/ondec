jQuery(document).ready(function(){
	
	jQuery("#edit-datepicker").datepicker( {
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'MM yy',
		onClose: function(dateText, inst) { 
			var month = jQuery("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var year = jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
			jQuery(this).datepicker('setDate', new Date(year, month, 1));
		}
	});
    
    jQuery(".approve-biz").click(function(){
            
		var bizid = jQuery(this).attr('id');
		var approveclass = ".approve-biz-" + bizid;
		var notlocation = ".not-current-location-" + bizid;

		jQuery.post(

			ajaxurl,
			{
				'action': 'approve_friend',
				'bizid' : bizid,
				'type' : 'biz'
			},
			function(response){

				jQuery('#bizapproved').slideUp(800).fadeIn(400).delay(800).fadeOut(400);
				jQuery(approveclass).hide();
				jQuery(notlocation).fadeIn(400);
			}
		);
	});
        
	jQuery('.decremove').click(function(){
		var rmdecid = jQuery(this).attr('id');
		var rmclass = ".decmember-" + rmdecid; 

		if (window.confirm("Do you really want to remove this follower from your list?")) {

			jQuery.post(

				ajaxurl,
					{   
						'action': 'remove_decmember',
						'rmdecid': rmdecid,
						'rmtype' : 'biz'
					}, 
					function(response){

					jQuery(rmclass).slideDown(800).fadeOut(400);    
					jQuery("#rmsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
					jQuery('#biz-count').text(jQuery('#biz-count').text()-1);
				}
			);
		}
	});
        
	jQuery(".closeMessage").click(function(){

		var closemessage_id = jQuery(this).attr('id');
		var closemessageclass = ".messageWrap-" + closemessage_id;
		var messagebuttonclass = "#" + closemessage_id;

		jQuery(closemessageclass).hide();

	});
        
	jQuery(".view-button").click(function(){    

		var message_id = jQuery(this).attr('id');
		var messageWrap = ".messageWrap-" + message_id;

		jQuery(messageWrap).show();    

		jQuery.post( 
			ajaxurl,
				{   
					'action': 'add_read_status',
					'message_id': message_id
				}, 
				function(response){

			}
		);

		if(jQuery(this).val() == 'unread'){ jQuery('#unread-count').text(jQuery('#unread-count').text()-1); }

		jQuery(this).val('read');

	});
        
	jQuery(".closeEndorsement").click(function(){

		var closeendorsement_id = jQuery(this).attr('id');
		var closeendorsementclass = ".endorsementWrap-" + closeendorsement_id;
		var endorsementbuttonclass = "#" + closeendorsement_id;

		jQuery(closeendorsementclass).hide();

	});
        
	jQuery(".view-endorsement-button").click(function(){    

		var endorsement_id = jQuery(this).attr('id');
		var endorsementWrap = ".endorsementWrap-" + endorsement_id;

		jQuery(endorsementWrap).show();    

	});
        
	jQuery(".approve-endorsement").click(function(){

		var endorseid = jQuery(this).attr('id');
		var closeendorsementclass = ".endorsementWrap-" + endorseid;

		jQuery.post( 
			ajaxurl,
				{   
					'action': 'approve_endorsement',
					'endorseid': endorseid
				}, 
				function(response){
		jQuery(this).addClass('hide');
		jQuery('.view-endorsement-button').val('view endorsement').addClass('approved');
		jQuery(closeendorsementclass).fadeOut(400);
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
    
	jQuery("#submit").click(function() {

		if(jQuery("#decstatus").val() === 'ondec'){ 

			jQuery('#submit').removeClass('currently-offdec').addClass('currently-ondec');

			jQuery( "#decstatus").val('offdec');

			var decstatus = 'ondec';

			jQuery( "#submit" ).val('Currently ondec');
		} else {

			jQuery('#submit').removeClass('currently-ondec').addClass('currently-offdec');

			jQuery( "#decstatus").val('ondec');

			var decstatus = 'offdec';

			jQuery( "#submit").val('Currently offdec');
		}    

		jQuery.post( 
			ajaxurl,
				{   
					'action': 'add_decstatus',
					'decstatus': decstatus,
				}, 
				function(response){

				//alert('The server responded: ' + response);
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
});