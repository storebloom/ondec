<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$mybusinesses = null !== get_user_meta($current_user->ID, 'mybusinesses', false) ? get_user_meta($current_user->ID, 'mybusinesses', false) : array( 0 => array());
$professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");
$pro_types_str = str_replace("pro-types=", "", get_user_meta($current_user->ID, 'protype', true));
$current_pro_types = explode("&", $pro_types_str );
$my_dec_info = array();
$my_business_info = array();    
$myrequests = null !== get_user_meta($current_user->ID, 'business_requests', false) ? get_user_meta($current_user->ID, 'business_requests', false) : array( 0 => array());
$my_request_info = array();

if(isset($mybusinesses[0])){
    foreach($mybusinesses[0] as $single_business){
        $my_business_info[] = get_userdata($single_business);
    }
}

if(isset($mydec[0])){
    foreach($mydec[0] as $single_member){
        $my_dec_info[] = get_userdata($single_member);
    }
}

if(isset($myrequests[0])){
    foreach($myrequests[0] as $single_requests){
        $my_request_info[] = get_userdata($single_requests);
    }
}

if($decstatus === "ondec" ){
    
    $negdecstatus = "offdec";
}else{
    
    $negdecstatus = "ondec";
}

get_header(); 
?>
<style type="text/css">

.messageWrap { /* The div that shows/hides. */
    display:none; /* starts out hidden */
    z-index:40001; /* High z-index to ensure it appears above all content */
}

.messageOverlay { /* Shades out background when selector is active */
    position:fixed;
    width:100%;
    height:100%;
    background-color:black;
    opacity:.5; /* Sets opacity so it's partly transparent */
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; /* IE transparency */
    filter:alpha(opacity=50); /* More IE transparency */
    z-index:40001;
}

.vertical-offset { /* Fixed position to provide the vertical offset */
    position:fixed;
    top:30%;
    width:100%;
    z-index:40002; /* ensures box appears above overlay */  
}
    
.messageBox { /* The actual box, centered in the fixed-position div */
    width:405px; /* Whatever width you want the box to be */
    position:relative;
    margin:0 auto;
    /* Everything below is just visual styling */
    background-color:white;
    padding:10px;
    border:1px solid black;
}
</style>

	<div id="primary" class="content-area">
        
		<main id="main" class="site-main" role="main">
            
            <?php echo "<h1>" . $current_user->display_name . "'s Profile</h2>"; ?>
            
            <?php echo get_wp_user_avatar($current_user->ID, 96); ?>
            
            <h3>current dec status:</h3>
                        
            <form id="decform" name="decform">
                <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo $negdecstatus; ?>">
                <input id="submit" type="button" value="<?php echo "Currently " . $decstatus; ?>">
            </form>
            <a href="/professionals/<?php echo $current_user->user_login; ?>">view my profile</a>
            <span>
                <div style="display:none;" id="msgsuccess">success!</div>
            </span>
            
            <form id="decmsgform" name="decmsgform">
                <input type="text" placeholder="what's up?" name="decmessage" id="decmessage" value="<?php echo $current_decmessage; ?>">
                <input id="msgsubmit" type="button" value="update">
            </form>
        
            <h3>My Followers</h3>  
            
            <div class="od-my-followers">
                
                <ul>
                    
                <?php foreach($my_dec_info as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member->ID);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member->ID; ?>">
                        
                        <a href='/clients/<?php echo $user_information->user_login; ?>'>
                            
                            <div class="dec-name">

                                <?php echo $single_dec_member->display_name; ?>

                            </div>

                            <div class="dec-image">

                                <?php echo get_wp_user_avatar($single_dec_member->ID, 96); ?>

                            </div>
                            
                        </a>

                    </li>
                    
                <?php endforeach; ?>
                    
                </ul>
                
            </div>
 
            <h3>My Business Locations</h3>
            
            <div class="od-my-businesses">
                
                <span>

                    <div style="display:none;" id="rmsuccess">successfully removed business!</div>
                    <div style="display:none;" id="currentlocmsg">successfully set current location!</div>
                    
                </span>
                    
                <ul id="business-list">
                    
                    <?php foreach($my_business_info as $single_dec_business) :

                        $user_information = get_userdata($single_dec_business->ID);
                    ?>
                        <li class="decbiz-<?php echo $single_dec_business->ID; ?>">

                            <a href='/businesses/<?php echo $single_dec_business->user_login; ?>'>

                                <div class="dec-name">

                                    <?php echo $single_dec_business->display_name; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($single_dec_business->ID, 96); ?>

                                </div>

                            </a>

                            <div>
                                <form id="current-biz-<?php echo $single_dec_business->ID; ?>" name="current-biz-<?php echo $single_dec_business->ID; ?>">
                                <?php $currentloc = $profile_pages->is_current_location($single_dec_business->ID);
            
                                    if($currentloc){
                                        $mybizloc = "current-location";
                                        $mybizmsg = "My Current Location";
                                    }else {
                                        $mybizloc = "not-current-location";
                                        $mybizmsg = "Set as Current Location";
                                    }
                                    ?>
                                   
                                    <input class="<?php echo $mybizloc; ?>" id="<?php echo $single_dec_business->ID; ?>" type="button" value="<?php echo $mybizmsg; ?>">

                                </form>

                                <form id="decrmbizform-<?php echo $single_dec_business->ID; ?>" name="decrmbizform-<?php echo $single_dec_business->ID; ?>">

                                    <input id="<?php echo $single_dec_business->ID; ?>" class="decremovebiz" type="button" value="remove from list">

                                </form>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>
            
            <h3>Requests From Businesses</h3>
            
            <div class="od-my-biz-requests">
                
                <span>

                    <div style="display:none;" id="approvesuccess">successfully approved request!</div>
                    
                </span>
                <span>

                    <div style="display:none;" id="removesuccess">successfully removed request!</div>
                    
                </span>
                    
                <ul id="request-list">
                    
                    <?php foreach($my_request_info as $single_dec_request) :

                        $user_information = get_userdata($single_dec_request->ID);
                    ?>
                        <li class="decrequest-<?php echo $single_dec_request->ID; ?>">

                            <a href='/businesses/<?php echo $single_dec_request->user_login; ?>'>

                                <div class="dec-name">

                                    <?php echo $single_dec_request->display_name; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($single_dec_request->ID, 96); ?>

                                </div>

                            </a>

                            <div>

                                <form id="decrequestform-<?php echo $single_dec_request->ID; ?>" name="decrequestform-<?php echo $single_dec_request->ID; ?>">

                                    <input id="<?php echo $single_dec_request->ID; ?>" class="approvebiz" type="button" value="approve">
                                    <input id="<?php echo $single_dec_request->ID; ?>" class="removebiz" type="button" value="remove">
                                    
                                </form>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>
            
            <div class="pro-type-section">
                <h3>Choose your professional type</h3>
                
                <form id="profesional-type-<?php echo $current_user->ID; ?>" name="professional-type-<?php echo $current_user->ID; ?>">
                    
                    <div style="display:none;" id="typesuccess">successfully updated!</div>
                    
                        <ul>
                            <?php foreach($professional_types as $profession => $professional_readable): ?>
                            
                            <li>
                                <?php if(array_intersect($current_pro_types, array($profession))){ ?>
                                <input name="pro-types" class="pro-types" type="checkbox" value="<?php echo $profession; ?>" checked>
                               <? } else { ?>
                                <input name="pro-types" class="pro-types" type="checkbox" value="<?php echo $profession; ?>">
                              <?php  } ?>
                                <?php echo $professional_readable; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    
                        <input id="professional-type" class="professional-type" type="button" value="update">
                </form>
            </div>
            <p>
        <a href="edit-profile-info">Edit Profile</a>
            </p>
            <div class="mymessages">
            <h3>My Messages</h3>    
            <?php $current_messages = get_user_meta($current_user->ID, 'my_messages', false);
                
                if(isset($current_messages)){
                    
                if(isset($current_messages[0][0]) && is_array($current_messages[0][0])){
                    
                    foreach($current_messages as $messagess) : 
                    
                    foreach(array_reverse($messagess) as $messages) :
                    
                    $user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";

                ?> 
                
                  <li class="message-item">
                            <div style="display:none;" class="messageWrap-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">
                            <div class="messageOverlay">
                                &nbsp;
                            </div>
                            <div class="vertical-offset">
                                <div class="messageBox">
                                    <div class="view-message-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >
                                        <h3>
                                            <?php echo isset($messages['message']) ? $messages['message'] : ""; ?>
                                        </h3>
                                    </div>
                                    <a id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a> <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">Close</a>
                                </div>
                            </div>
                        </div>  
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y", $messages['message_date']) : ""; ?>
                            </div>
                            <div class="user-message-info">
                                <div class="message-user">
                                    <?php echo isset($user_info->display_name) ? $user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($user_info->ID) ? get_wp_user_avatar($user_info->ID, 36) : ""; ?>
                                </div>
                            </div>
                            <div class="user-message">
                                <div class="message-wrapper">
                                    <?php echo isset($messages['message']) ? substr($messages['message'], 0, 80) . "..." : ""; ?>
                                </div>
                                <div class="view-message">
                                    <input class="view-button" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" type="button" value="<?php echo isset($messages['read_status']) ? $messages['read_status'] : ""; ?>">
                                </div>
                            </div>
                        </li>
   
                  
                   <?php endforeach; endforeach; }else{
                    
                    foreach(array_reverse($current_messages) as $messages) :
                    
                    $user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                ?> 
                
                   <li class="message-item">
                         <div style="display:none;" class="messageWrap-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">
                            <div class="messageOverlay">
                                &nbsp;
                            </div>
                            <div class="vertical-offset">
                                <div class="messageBox">
                                    <div class="view-message-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >
                                        <h3>
                                            <?php echo isset($messages['message']) ? $messages['message'] : ""; ?>
                                        </h3>
                                    </div>
                                    <a id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a> <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">Close</a>
                                </div>
                            </div>
                        </div>    
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y", $messages['message_date']) : ""; ?>
                            </div>
                            <div class="user-message-info">
                                <div class="message-user">
                                    <?php echo isset($user_info->display_name) ? $user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($user_info->ID) ? get_wp_user_avatar($user_info->ID, 36) : ""; ?>
                                </div>
                            </div>
                            <div class="user-message">
                                <div class="message-wrapper">
                                    <?php echo isset($messages['message']) ? substr($messages['message'], 0, 80) . "..." : ""; ?>
                                </div>
                                <div class="view-message">
                                    <input class="view-button" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" type="button" value="<?php echo isset($messages['read_status']) ? $messages['read_status'] : ""; ?>">
                                </div>
                            </div>
                        </li>
   
                  
                   <?php endforeach; }}?>
            </div>
<?php
get_footer();

?>
<script>
    jQuery(document).ready(function() {
        
        jQuery('.decremove').click(function(){
            var rmdecid = jQuery(this).attr('id');
            var rmclass = ".decmember-" + rmdecid; 

            if (window.confirm("Do you really want to remove this follower from your list?")) {

                jQuery.post(

                    ajaxurl,
                        {   
                            'action': 'remove_decmember',
                            'rmdecid': rmdecid
                        }, 
                        function(response){

                        jQuery(rmclass).slideDown(800).fadeOut(400);    
                        jQuery("#rmsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
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
            
            jQuery(this).val('read');
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

                        jQuery('.current-location').removeClass('current-location').addClass('not-current-location').val('Set as Current Location');
                        jQuery(specificid).removeClass('not-current-location').addClass('current-location');
                        jQuery('.current-location').val('My Current Location');
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
                        
                        jQuery('.current-location').val('Set as Current Location');
                        jQuery('.current-location').addClass('not-current-location').removeClass('current-location');
                    }
                );
            }
        });
        
        jQuery('.decremovebiz').click(function(){
            var rmbizid = jQuery(this).attr('id');
            var rmbizclass = ".decbiz-" + rmbizid; 

            if (window.confirm("Do you really want to remove this business from your list?")) {

                jQuery.post(

                    ajaxurl,
                        {   
                            'action': 'remove_biz',
                            'rmbizid': rmbizid
                        }, 
                        function(response){

                        jQuery(rmbizclass).slideDown(800).fadeOut(400);    
                        jQuery("#rmbizsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
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
        
});
 
</script>