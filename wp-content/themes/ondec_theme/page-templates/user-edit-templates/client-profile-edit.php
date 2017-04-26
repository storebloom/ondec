<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$biz_title = array("client" => "dec", "professional" => "Followers", "business" => "Current Professoinal"); 
$professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");
$current_friends = null !== get_user_meta($current_user->ID, 'myfriends') ? get_user_meta($current_user->ID, 'myfriends') : array();

date_default_timezone_set('America/Los_Angeles');

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
            <div class="message-notification"></div>
            <div class="user-wrapper">
                <div class="user-information">
                <?php echo "<h2>" . $current_user->display_name . "'s Profile</h2>"; ?>
                <?php echo get_wp_user_avatar($current_user->ID, 170); ?>

                <p>
                    <a href="/clients/<?php echo $current_user->user_login; ?>">view profile</a> | 
                <a href="edit-profile-info">edit profile</a>
                </p>

                <div style="display:none;" id="msgsuccess">success!</div>
 
                <form id="decmsgform" name="decmsgform">
                    <input type="text" placeholder="what's up?" name="decmessage" id="decmessage" value="<?php echo $current_decmessage; ?>">
                    <input id="msgsubmit" type="button" value="update">
                </form>
                </div>
                <div class="user-tools">
                <h2>Your Tools</h2>
                    
                    <h3>My Appointments</h3>
                    <?php echo $od_appointments->get_all_appointments(); ?>
                </div>
            </div>
            
            <div class="member-lists">
                
                <div class="list-sections">
                                
                <div style="display:none;" id="rmsuccess">successfully removed!</div>
            
                <div class="list-section-wrapper">
                 <?php 
              
            if(isset($my_dec_info[0]) && is_array($my_dec_info)) :
                            foreach($my_dec_info as $single_dec_info){                          
        
                                    $dec_count[] = $single_dec_info;                            
                            }
                          endif;
            ?>
            

                <h3>My dec (<span id="count"><?php echo isset($dec_count) ? count($dec_count) : "0"; ?></span>) </h3>
                
                <div class="od-my-list single-member-list">
                  
                <ul>
                <?php if(isset($my_dec_info[0]) && $my_dec_info[0] !== false) :
                    
                    foreach($my_dec_info as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member->ID);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member->ID; ?>">
                        <a href='/<?php echo $user_type[0].'s/'.$user_information->user_login; ?>'>
                        <div class="dec-user">
                            
                            <?php echo $single_dec_member->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_dec_member->ID, 130); ?>
                            
                        </div>
                            
                        </a>
                        
                        <div class="dec-status <?php if(get_user_meta($single_dec_member->ID, 'decstatus', true) === "offdec"){ echo "offdec";} ?>">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decstatus', true); ?>
                            
                        </div>
                        
                        <div class="dec-message">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decmessage', true); ?>
                            
                        </div>
                        
                        <div>
                            
                            <form id="decmsgform-<?php echo $single_dec_member->ID; ?>" name="decmsgform-<?php echo $single_dec_member->ID; ?>">
                                
                                <input id="like-<?php echo $single_dec_member->ID; ?>" value="follow" type="hidden">
                                
                                <input id="<?php echo $single_dec_member->ID; ?>" class="decremove" type="button" value="remove from list">
                                
                            </form>
                            
                        </div>
                        
                    </li>
                    
                <?php endforeach; endif; ?>
                    
                </ul>
                
            </div>
                    </div>
                    
                    
            <div class="list-section-wrapper middle">
             <?php 
            if(isset($current_friends[0][0]) && is_array($current_friends[0][0])) :
                            foreach($current_friends[0] as $single_friend_info){                          
        
                                    $friend_count[] = $single_friend_info;                            
                            }
                          endif;
            ?>
            

                <h3>My Friends (<?php echo isset($friend_count) ? count($friend_count) : "0"; ?>) </h3>
               <div class="friends single-member-list">
                <div style="display:none;" id="friendapproved">Now your friends!</div>
                
                <?php if(isset($current_friends[0])) : ?>
                <ul>
                    <?php foreach($current_friends[0] as $friends) : ?>
                    
                <?php $friend_user_info = isset($friends['user']) ? get_userdata($friends['user']) : ""; ?>
                
                   <li class="decmember-<?php echo $friend_user_info->ID; ?>">
                            <div class="user-friend-info">
                                <a href="/clients/<?php echo $friend_user_info->user_login; ?>" >
                                <div class="dec-user">
                                    <?php echo isset($friend_user_info->display_name) ? $friend_user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-friend">
                                    <?php echo isset($friend_user_info->ID) ? get_wp_user_avatar($friend_user_info->ID, 130) : ""; ?>
                                </div>
                                </a>
                            </div>
                       <?php if($friends['approval_status'] === 'pending') : ?>  
                            <div class="user-friend">
                                <input id="<?php echo $friend_user_info->ID; ?>" class="approve-friend approve-friend-<?php echo $friend_user_info->ID; ?>" value="accept friend" type="button">
                            </div>
                       <?php endif; ?>
                            <input id="like-<?php echo $friend_user_info->ID; ?>" type="hidden" value="friend">
                            <input id="<?php echo $friend_user_info->ID; ?>" class="decremove" type="button" value="remove friend">
                        </li>
   
                  
                   <?php endforeach;?>
                </ul>
                <?php endif; ?>
                </div>
            </div>
                 
                    
                    <div class="list-section-wrapper">
                        <?php
                         $mylikes = null !== get_user_meta($current_user->ID, 'mylikes', false) ? get_user_meta($current_user->ID, 'mylikes', false) : "";
                    $like_count = array();
                if(isset($mylikes[0]) && is_array($mylikes[0])){
                    foreach($mylikes[0] as $single_like){
                        if(null !== $single_like && "" !== $single_like){
                            
                            $like_count[] = $single_like;
                        
                        $my_likes_info[] = get_userdata($single_like);
                        }
                    }
                }
           
                
                    $like_count = intval(count($like_count));
                ?>
                         <h3>Likes (<?php echo $like_count; ?>)</h3>
                    
               
                <div class="od-my-likes single-member-list">
                    
                   
                    
                    <ul id="like-list">
                    <?php 
                    if(isset($my_likes_info)):
                    foreach($my_likes_info as $single_dec_like) : ?>
                        
                      <?php if(isset($single_dec_like->ID)) : ?>
                        
                        <li>
                        <a href='/businesses/<?php echo $single_dec_like->user_login; ?>'>

                                <div class="dec-user">

                                    <?php echo isset($single_dec_like->display_name) ? $single_dec_like->display_name : ""; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($single_dec_like->ID, 130); ?>

                                </div>
                        </a>
                            <div class="biz-status <?php if(get_user_meta($single_dec_like->ID, 'decstatus', true) === "Closed"){ echo "closed";} ?>">
                                
                                <?php echo get_user_meta($single_dec_like->ID, 'decstatus', true); ?>
                            </div>
                            <div>
                             
                            
                            <form id="declikeform-<?php echo $single_dec_like->ID; ?>" name="declikeform-<?php echo $single_dec_like->ID; ?>">
                                <input id="like-<?php echo $single_dec_like->ID; ?>" type="hidden" value="like">
                                <input id="<?php echo $single_dec_like->ID; ?>" class="decremove" type="button" value="remove from list">
                                
                            </form>
                            
                        </div>

                        </li> 
                        
                    <?php endif; endforeach; ?>
                </ul>
            
                    
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

   <div class="mymessages">
                
                <div class="message-section">
               
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
                    endforeach; endforeach; } else {
                    
                    foreach(array_reverse($current_messages) as $messages) :
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                    
                    if(isset($messages['read_status'])){
                        
                        if($messages['read_status'] === 'unread' ){
                            
                            $unread_count[] = $messages['read_status'];
                        }
                        
                         $message_count[] = $messages['read_status'];
                    }
                    endforeach;
                }
                
                if(isset($unread_count) && intval(count($unread_count)) !== 1){ $singleor = "Messages"; } else { $singleor = "Message"; }
                if(isset($unread_count) && intval(count($unread_count)) > 0) { echo '
                
                <script>
                
                    jQuery(".message-notification").append("<h4 style=\'color: red;\'>You Have ' . intval(count($unread_count)) . ' New ' . $singleor . '!</h4>");</script>'; } 
                ?>
                <div class="message-count">
                    
                <h3>Messages (<?php if(isset($message_count) && is_array($message_count)){
                        echo count($message_count);
                    }else{ echo "0";} ?>) <?php if(isset($unread_count) && is_array($unread_count)){ ?> | Unread (<span id="unread-count"><?php echo intval(count($unread_count)); ?></span>)</h3>
                   
               <?php } ?>
                     
                </div> 
                
                <ul class="messages-ul">       
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
                                    <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID) . "/#messages"; ?>" class="replyMessage" id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a>  <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">close</a>
                                </div>
                            </div>
                        </div>  
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y h:i A", $messages['message_date']) : ""; ?>
                            </div>
                            <div class="user-message-info">
                                <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID); ?>">
                                <div class="message-user">
                                    <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($message_user_info->ID) ? get_wp_user_avatar($message_user_info->ID, 36) : ""; ?>
                                </div>
                                </a>    
                            </div>
                            <div class="user-message">
                                <div class="message-wrapper">
                                    <?php echo isset($messages['message']) ? substr($messages['message'], 0, 80) . "..." : ""; ?>
                                </div>
                                <div class="view-message">
                                    <input class="view-button <?php if($messages['read_status'] === "read") : echo "read-message"; endif; ?>" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" type="button" value="<?php echo isset($messages['read_status']) ? $messages['read_status'] : ""; ?>">
                                </div>
                            </div>
                        </li>
   
                  
                   <?php endforeach; endforeach; }else{
                    
                       ?>
                    
                <?php  foreach(array_reverse($current_messages) as $messages) :
                    
                    $message_user_info = isset($messages['user']) ? get_userdata($messages['user']) : "";
                    
                   if(isset($unread_count) && intval(count($unread_count)) !== 1){ $singleor = "Messages"; } else { $singleor = "Message"; }
                    
                    if( isset($unread_count) && intval(count($unread_count)) > 0){ echo '<script>
                
                    jQuery(".message-notification").append("<h4 style=\'color: red;\'>You Have ' . intval(count($unread_count)) . ' New ' . $singleor . '!</h4>");</script>'; } 
                                                                                                                                
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
                                    <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID) . "/#messages"; ?>" class="replyMessage" id="reply-<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" >reply</a>  <a class="closeMessage" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>">close</a>
                                </div>
                            </div>
                        </div>    
                            <div class="message-date">
                                <?php echo isset($messages['message_date']) ? date("F j, Y h:i A", $messages['message_date']) : ""; ?>
                            </div>
                            <a href="<?php echo $profile_pages->get_user_profile_url($message_user_info->ID); ?>">
                            <div class="user-message-info">
                                <div class="message-user">
                                    <?php echo isset($message_user_info->display_name) ? $message_user_info->display_name : ""; ?>
                                </div>
                                <div class="prof-image-message">
                                    <?php echo isset($message_user_info->ID) ? get_wp_user_avatar($message_user_info->ID, 36) : ""; ?>
                                </div>
                            </div>
                            </a>    
                            <div class="user-message">
                                <div class="message-wrapper">
                                    <?php echo isset($messages['message']) ? substr($messages['message'], 0, 80) . "..." : ""; ?>
                                </div>
                                <div class="view-message">
                                    <input class="view-button <?php if($messages['read_status'] === "read") : echo "read-message"; endif; ?>" id="<?php echo isset($messages['messageid']) ? $messages['messageid'] : ""; ?>" type="button" value="<?php echo isset($messages['read_status']) ? $messages['read_status'] : ""; ?>">
                                </div>
                            </div>
                        </li>
   
                  
                    <?php endforeach; }}?></ul>
            </div>
                </div>
        </main></div>
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
            
            jQuery(this).val('read').addClass('read-message');
        });
        
        jQuery('.decremove').click(function(){
            var rmdecid = jQuery(this).attr('id');
            var rmclass = ".decmember-" + rmdecid;
            var rmtypeclass = "#like-" + rmdecid;
            var rmtype = jQuery(rmtypeclass).val();

            if (window.confirm("Do you really want to remove them from your list?")) {

                jQuery.post(

                    ajaxurl,
                        {   
                            'action': 'remove_decmember',
                            'rmdecid': rmdecid,
                            'rmtype' : rmtype
                        }, 
                        function(response){

                        jQuery(rmclass).slideDown(800).fadeOut(400);    
                        jQuery("#rmsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
                        jQuery('#count').text(jQuery('#count').text()-1); 
                    }
                );
            }
        });
        
        jQuery(".approve-friend").click(function(){
            
            var friendid = jQuery(this).attr('id');
            var approveclass = ".approve-friend-" + friendid;
            
            jQuery.post(
                
                ajaxurl,
                    {
                        'action': 'approve_friend',
                        'friendid' : friendid
                    },
                    function(response){
                        
                        jQuery('#friendapproved').slideUp(800).fadeIn(400).delay(800).fadeOut(400);
                        jQuery(approveclass).hide();
                    }
            );

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