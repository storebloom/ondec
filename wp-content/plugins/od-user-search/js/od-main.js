jQuery(document).ready(function(){

    jQuery('input.typeahead').typeahead({
        name: 'typeahead',
        remote:'/wp-content/plugins/od-user-search/includes/od-search-data.php?key=%QUERY',
        limit : 10
    });

    //Show modal box
    //Hide modal box
    jQuery('#closeSearchModal').click(
        
        function() {
            jQuery('.od-searchBoxWrap').hide();
        }
    );
    
        jQuery('.addtoyourdec' ).click(function(){
        var adddecid = jQuery(this).attr('id');
        var addclass = '#decaddmeform-' + adddecid;
        jQuery.post( 
            ajaxurl,
            {   
                'action': 'add_decmember',
                'adddecid': adddecid
            }, 
            function(response){

                jQuery(addclass).slideDown(800).fadeOut(400);    
                jQuery('.successadd').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);   
            }
        );
    });
    
    jQuery('.requesttoyourdec').click(function(){
        
        var requestdecid = jQuery(this).attr('id');
        var requestclass = '#decrequestmeform-' + requestdecid;    
        jQuery.post( 
            ajaxurl,
            {   
                'action': 'request_decmember',
                'requestdecid': requestdecid
            }, 
            function(response){
                jQuery(requestclass).slideDown(800).fadeOut(400);    
                jQuery('.successrequest').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);
            }
        );
    });  
        
    jQuery("#msgsend").click(function(){    

        var usermessage = jQuery('#usermsginput').val();
        var msgid = jQuery('#usermsginput').attr('class');
        var x = Math.floor((Math.random() * 100000000000) + 1);
        var messageid = msgid + "_" + x;

        jQuery.post( 
            ajaxurl,
            {   
                'action': 'add_usermessage',
                'usermessage': usermessage,
                'msgid' : msgid,
                'messageid' : messageid
            }, 
            function(response){

                jQuery('#usermsginput').val("");    
                jQuery(".message-sent-sucess").slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);    
            }
        );
    });
    
    jQuery("#endorsesend").click(function(){    
            
        var userendorse = jQuery('#endorseinput').val();
        var endorseusrid = jQuery('#endorseinput').attr('class');
        var x = Math.floor((Math.random() * 100000000000) + 1);
        var endorseid = endorseusrid + "_" + x;
            
        jQuery.post( 
        ajaxurl,
            {   
                'action': 'add_userendorse',
                'userendorse': userendorse,
                'endorseusrid' : endorseusrid,
                'endorseid' : endorseid
            }, 
            function(response){

            jQuery('#endorseinput').val("");    
            jQuery(".endorse-sent-sucess").slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);    

            }
        );
    });   
});