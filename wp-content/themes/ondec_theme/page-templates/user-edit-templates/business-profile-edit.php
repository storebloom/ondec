<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "undecided";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$biz_title = array("client" => "dec", "professional" => "Followers", "business" => "Current Professionals");  
$myrequests = null !== get_user_meta($current_user->ID, 'pro_requests', false) ? get_user_meta($current_user->ID, 'pro_requests', false) : array( 0 => array());

$my_dec_info = array();
$my_request_info = array();

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

if($decstatus === "Open" ){
    $negdecstatus = "Closed";
}else{
    $negdecstatus = "Open";
}
get_header(); 
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php echo "<h1>" . $current_user->display_name . "'s Profile</h2>"; ?>
            <?php echo get_wp_user_avatar($current_user->ID, 200); ?>
            
            <p><a href="/businesses/<?php echo $current_user->user_login; ?>">view my profile</a>
            </p>
            
            <h3>Are you Opened or Closed?:</h3>
                        
            <form id="decform" name="decform">
                <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo $negdecstatus; ?>">
                <input id="submit" type="button" value="<?php echo "Currently " . $decstatus; ?>">
            </form>
            <p>
            <a href="/professionals/<?php echo $current_user->user_login; ?>">View Profile</a> | <a href="edit-profile-info">Edit Profile</a>
            </p>
            <span>
                <div style="display:none;" id="msgsuccess">success!</div>
            </span>
            <?php foreach($my_dec_info as $single_dec_member) :
                $professional_count[] = $single_dec_member;
            endforeach;

            if(isset($professional_count)):
            $professional_count = intval(count($professional_count));
            ?>
            <h3>My <?php echo $biz_title[$user_role]; ?> (<?php echo $professional_count; ?>) </h3>
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
            <?php endif; ?>
            <?php foreach($my_request_info as $single_dec_request) :
                $pro_count[] = $single_dec_request;
            endforeach;

            if(isset($pro_count)):
            $pro_count = intval(count($pro_count));
            ?>
            <h3>My Professional Requests (<?php echo $pro_count; ?>) </h3>
        
            <div class="od-my-pro-requests">
                
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

                            <a href='/professionals/<?php echo $single_dec_request->user_login; ?>'>

                                <div class="dec-name">

                                    <?php echo $single_dec_request->display_name; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($single_dec_request->ID, 96); ?>

                                </div>

                            </a>

                            <div>

                                <form id="decrequestform-<?php echo $single_dec_request->ID; ?>" name="decrequestform-<?php echo $single_dec_request->ID; ?>">

                                    <input id="<?php echo $single_dec_request->ID; ?>" class="approvepro" type="button" value="approve">
                                    <input id="<?php echo $single_dec_request->ID; ?>" class="removepro" type="button" value="remove">
                                    
                                </form>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>
            <?php endif; ?>
            
            <?php 
                    
                $mylikes = null !== get_user_meta($current_user->ID, 'mylikers', false) ? get_user_meta($current_user->ID, 'mylikers', false) : "";

                if(isset($mylikes[0]) && is_array($mylikes[0])){
                    foreach($mylikes[0] as $single_like){
                        $like_count[] = $single_like;
                        
                        $my_likes_info[] = get_userdata($single_like);
                    }
                }
                if(isset($like_count)):
                    $like_count = intval(count($like_count));
                ?>
                <div class="od-my-likes">
                    
                    <h3>Likes (<?php echo $like_count; ?>)</h3>
                    
                    <ul id="like-list">
                    <?php 
                    
                    foreach($my_likes_info as $single_dec_like) : ?>
                        
                        <a href='/clients/<?php echo $single_dec_like->user_login; ?>'>

                                <div class="dec-name">

                                    <?php echo $single_dec_like->display_name; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($single_dec_like->ID, 96); ?>

                                </div>

                            </a>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
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
                
                   
                    
               <?php if(isset($current_messages[0][0]) && is_array($current_messages[0][0])){
                     ?><div class="message-count">
                <h3>My Messages ( <?php if(isset($message_count) &&is_array($message_count)){
                        echo count($message_count);
                    }else{ echo "0";} ?> )</h3>  
                    
                    <h4><?php if(isset($unread_count) && is_array($unread_count)) : ?>| unread( 
                        <span id="unread-count"><?php echo intval(count($unread_count)); ?></span> ) <?php endif; ?>
                </h4>
                     
                </div> 
                <ul>
                <?php 
                    
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
                 <h3>My Messages (<?php if(isset($message_count) &&is_array($message_count)){
                        echo count($message_count);
                    }else{ echo "0";} ?>)</h3>  
 <div class="message-count">
                <h4><?php if(isset($unread_count) && is_array($unread_count)){ ?>unread( <span id="unread-count">
                    <?php echo intval(count($unread_count)); 
                 ?></span>)<?php }?></h4>
                     
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
                </ul>
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
        
        jQuery('.approvepro').click(function(){
            var approvedecid = jQuery(this).attr('id');
            var approveclass = ".decrequest-" + approvedecid; 

            jQuery.post(

                ajaxurl,
                    {   
                        'action': 'approve_pro',
                        'approvepro': approvedecid
                    }, 
                    function(response){

                    jQuery(approveclass).slideDown(800).fadeOut(400);    
                    jQuery("#approvesuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
                }
            );
        });
        
        jQuery('.removepro').click(function(){
            var removedecid = jQuery(this).attr('id');
            var removeclass = ".decrequest-" + removedecid; 

            jQuery.post(

                ajaxurl,
                    {   
                        'action': 'remove_pro',
                        'removovepro': removedecid
                    }, 
                    function(response){

                    jQuery(removeclass).slideDown(800).fadeOut(400);    
                    jQuery("#removesuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
                }
            );
        });
    
        jQuery("#submit").click(function() {
        
            if(jQuery("#decstatus").val() === 'Open'){ 

                jQuery('#submit').removeClass('currently-closed').addClass('currently-open');

                jQuery( "#decstatus").val('Closed');

                var decstatus = 'Open';

                jQuery( "#submit" ).val('Currently Open');
            } else {

                jQuery('#submit').removeClass('currently-open').addClass('currently-closed');

                jQuery( "#decstatus").val('Open');

                var decstatus = 'Closed';

                jQuery( "#submit").val('Currently Closed');
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
});
 
</script>