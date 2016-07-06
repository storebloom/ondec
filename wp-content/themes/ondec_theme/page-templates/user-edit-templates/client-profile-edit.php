<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$biz_title = array("client" => "dec", "professional" => "Followers", "business" => "Current Professoinal"); 
$professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");

$my_dec_info = array();

if(isset($mydec[0])){
    foreach($mydec[0] as $single_member){
        $my_dec_info[] = get_userdata($single_member);
    }
}

if($decstatus === "ondec" ){
    $negdecstatus = "offdec";
}else{
    $negdecstatus = "ondec";
}
get_header(); 
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php echo "<h1>" . $current_user->display_name . "'s Profile</h2>"; ?>
            <?php echo get_wp_user_avatar($current_user->ID, 96); ?>
            
            <?php if($user_role !== "business" ) : ?>
            <h3>current dec status:</h3>
                        
            <form id="decform" name="decform">
                <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo $negdecstatus; ?>">
                <select id="decmood">
                    <option disabled selected value="">Choose mood</option>
                    <option value="Happiness">Happiness</option>
                    <option value="Sadness">Sadness</option>
                    <option value="Excitement">Excitement</option>
                    <option value="Coolness">Coolness</option>
                </select>
                <input id="submit" type="button" value="<?php echo "Currently " . $decstatus; ?>">
            </form>
            
            <a href="/clients/<?php echo $current_user->user_login; ?>">view my profile</a>
            
            <span>
            <div style="display:none;" id="msgsuccess">success!</div>
            </span>
            <form id="decmsgform" name="decmsgform">
                <input type="text" placeholder="what's up?" name="decmessage" id="decmessage" value="<?php echo $current_decmessage; ?>">
                <input id="msgsubmit" type="button" value="update">
            </form>
            <?php endif; ?> 
        
            <h3>My <?php echo $biz_title[$user_role]; ?></h3>  
            
            <div class="od-my-list">
                <span>
                                
                                <div style="display:none;" id="rmsuccess">successfully removed member!</div>
                                
                            </span>
                <ul>
                <?php foreach($my_dec_info as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member->ID);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member->ID; ?>">
                        <a href='/<?php echo $user_type[0].'s/'.$user_information->user_login; ?>'>
                        <div class="dec-name">
                            
                            <?php echo $single_dec_member->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_dec_member->ID, 96); ?>
                            
                        </div>
                            
                        </a>
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decstatus', true); ?>
                            
                        </div>
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decstatus', true); ?>
                            
                        </div>
                        
                        <div class="dec-message">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decmessage', true); ?>
                            
                        </div>
                        
                        <div>
                            
                            <form id="decmsgform-<?php echo $single_dec_member->ID; ?>" name="decmsgform-<?php echo $single_dec_member->ID; ?>">
                                
                                <input id="<?php echo $single_dec_member->ID; ?>" class="decremove" type="button" value="remove from list">
                                
                            </form>
                            
                        </div>
                        
                    </li>
                    
                <?php endforeach; ?>
                    
                </ul>
                
            </div>
            
            <?php if($user_role === "professional"):
            
                $mybusinesses = null !== get_user_meta($current_user->ID, 'mybusinesses', false) ? get_user_meta($current_user->ID, 'mybusinesses', false) : array( 0 => array());
                $my_business_info = array();    
            
                if(isset($mybusinesses[0])){
                    foreach($mybusinesses[0] as $single_business){
                        $my_business_info[] = get_userdata($single_business);
                    }
                }
            ?>  
                <h3>My Business Locations</h3>
                <div class="od-my-businesses">
                    <span>

                        <div style="display:none;" id="rmsuccess">successfully removed business!</div>

                    </span>
                    
                    <ul id="business-list">
                    <?php foreach($my_business_info as $single_dec_business) :
                        
                     $user_information = get_userdata($single_dec_business->ID);
                    ?>
                        <li class="decmember-<?php echo $single_dec_business->ID; ?>">
                            <a href='/businesses/<?php echo $single_dec_business->user_login; ?>'>
                            <div class="dec-name">

                                <?php echo $single_dec_business->display_name; ?>

                            </div>

                            <div class="dec-image">

                                <?php echo get_wp_user_avatar($single_dec_business->ID, 96); ?>

                            </div>

                            </a>

                            <div class="dec-status">

                                <?php echo get_user_meta($single_dec_business->ID, 'decstatus', true); ?>

                            </div>

                            <div class="dec-message">

                                <?php echo get_user_meta($single_dec_business->ID, 'decmessage', true); ?>

                            </div>

                            <div>

                                <form id="decmsgform-<?php echo $single_dec_business->ID; ?>" name="decmsgform-<?php echo $single_dec_business->ID; ?>">

                                    <input id="<?php echo $single_dec_business->ID; ?>" class="decremovebiz" type="button" value="remove from list">

                                </form>

                            </div>

                        </li>

                    <?php endforeach; ?>

                    </ul>

                </div>
            <?php endif; ?>

 <p>
        <a href="edit-profile-info">Edit Profile</a>
            </p>
            <div class="mymessages">
               
            <?php $current_messages = get_user_meta($current_user->ID, 'my_messages', false);
                
                if(isset($current_messages)){
                    
                if(isset($current_messages[0][0]) && is_array($current_messages[0][0])){
                    
                    foreach($current_messages as $messagess) : 
                    
                    foreach(array_reverse($messagess) as $messages) :
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                
                    if(isset($messages['read_status'])){
                        
                        if($messages['read_status'] === 'unread' ){
                            
                            $unread_count[] = $messages['read_status'];
                        }
                        
                         $message_count[] = $messages['read_status'];
                    }
                    endforeach; endforeach; } 
                
                if(isset($unread_count) && intval(count($unread_count)) !== 1){ $singleor = "Messages"; } else { $singleor = "Message"; }
                if(isset($unread_count) && intval(count($unread_count)) > 0) { echo '<script>alert("You Have ' . intval(count($unread_count)) . ' New ' . $singleor . '!")</script>'; } 
                ?>
                
                   
                <div class="message-count">
                <h3>My Messages ( <?php if(isset($message_count) &&is_array($message_count)){
                        echo count($message_count);
                    }else{ echo "0";} ?> )</h3>  
                    
                    <h4><?php if(isset($unread_count) && is_array($unread_count)) : ?>| unread( 
                        <span id="unread-count"><?php echo intval(count($unread_count)); ?></span> ) <?php endif; ?>
                </h4>
                     
                </div> 
                    
               <?php if(isset($current_messages[0][0]) && is_array($current_messages[0][0])){
                    
                    foreach($current_messages as $messagess) : 
                    
                    foreach(array_reverse($messagess) as $messages) :
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                    
                    if(isset($messages['read_status'])){
                        
                        if($messages['read_status'] === 'unread' ){
                            
                            $unread_count[] = $messages['read_status'];
                        }else{
                        
                            $read_count[] = $messages['read_status'];
                        }
                    }
                ?> 
                
                  <li class="message-item">
                            <div style="display:none;" class="messageWrap-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">
                            <div class="messageOverlay">
                                &nbsp;
                            </div>
                            <div class="vertical-offset">
                                <div class="messageBox">
                                    <div class="view-message-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >
                                        <h2>
                                            <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                        </h2>
                                        <h3>
                                            <?php echo isset($messages['message']) ? $messages['message'] : ""; ?>
                                        </h3>
                                    </div>
                                    <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID) . "/#messages"; ?>" id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a> <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">Close</a>
                                </div>
                            </div>
                        </div>  
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y", $messages['message_date']) : ""; ?>
                            </div>
                            <div class="user-message-info">
                                <div class="message-user">
                                    <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($message_user_info->ID) ? get_wp_user_avatar($message_user_info->ID, 36) : ""; ?>
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
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                    
                    if(isset($messages['read_status'])){
                        
                        if($messages['read_status'] === 'unread' ){
                            
                            $unread_count[] = $messages['read_status'];
                        }
                        
                         $message_count[] = $messages['read_status'];
                    }
                    endforeach; ?>
                    
                <?php  foreach(array_reverse($current_messages) as $messages) :
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                    
                   if(isset($unread_count) && intval(count($unread_count)) !== 1){ $singleor = "Messages"; } else { $singleor = "Message"; }
                    
                    if( isset($unread_count) && intval(count($unread_count)) > 0){ echo '<script>alert("You Have ' . intval(count($unread_count)) . ' New ' . $singleor . '!")</script>'; }
                                                                                                                                
                ?> 
                
                <div class="message-count">
                <h4>messages( <?php if(is_array($message_count) && isset($message_count)){
                        echo count($message_count);
                    }else{ echo "0";} ?> ) | unread( <span id="unread-count"><?php if(is_array($unread_count) && isset($unread_count)){
                    echo intval(count($unread_count)); 
                }else{ echo "0";} ?></span> )</h4>
                     
                </div> 
                
                   <li class="message-item">
                         <div style="display:none;" class="messageWrap-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">
                            <div class="messageOverlay">
                                &nbsp;
                            </div>
                            <div class="vertical-offset">
                                <div class="messageBox">
                                    <div class="view-message-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >
                                        <h2>
                                            <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                        </h2>
                                        <h3>
                                            <?php echo isset($messages['message']) ? $messages['message'] : ""; ?>
                                        </h3>
                                    </div>
                                    <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID) . "/#messages"; ?>" id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a> <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">Close</a>
                                </div>
                            </div>
                        </div>    
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y", $messages['message_date']) : ""; ?>
                            </div>
                            <div class="user-message-info">
                                <div class="message-user">
                                    <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($message_user_info->ID) ? get_wp_user_avatar($message_user_info->ID, 36) : ""; ?>
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
        
        jQuery('.decremove').click(function(){
            var rmdecid = jQuery(this).attr('id');
            var rmclass = ".decmember-" + rmdecid; 

            if (window.confirm("Do you really want to remove them from your list?")) {

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
    
        jQuery("#submit").click(function() {
        
            if(jQuery("#decstatus").val() === 'ondec'){ 

                jQuery('#submit').removeClass('currently-offdec').addClass('currently-ondec');

                jQuery( "#decstatus").val('offdec');
                
                var decmood = jQuery('#decmood option:selected').val() + " ";

                var decstatus = decmood + 'ondec';

                jQuery( "#submit" ).val('Currently ondec');
            } else {

                jQuery('#submit').removeClass('currently-ondec').addClass('currently-offdec');

                jQuery( "#decstatus").val('ondec');

                var decstatus = decmood + ' ' + 'offdec';

                jQuery( "#submit").val('Currently offdec');
            }    

            jQuery.post( 
                ajaxurl,
                    {   
                        'action': 'add_decstatus',
                        'decstatus': decstatus
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
});
 
</script>