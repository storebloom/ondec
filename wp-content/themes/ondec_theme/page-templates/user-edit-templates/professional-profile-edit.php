<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$current_biz = null !== get_user_meta($current_user->ID, 'mybusinesses', false) ? get_user_meta($current_user->ID, 'mybusinesses', false) : array( 0 => array());
$my_dec_info = array();
$my_business_info = array();    
$myrequests = null !== get_user_meta($current_user->ID, 'business_requests', false) ? get_user_meta($current_user->ID, 'business_requests', false) : array( 0 => array());
$my_request_info = array();
$endorsement_user_info = array();
$endorsment_requests = null !== get_user_meta($current_user->ID, 'my_endorsements', false) ? get_user_meta($current_user->ID, 'my_endorsements', false) : array( 0 => array());
$current_endorsements = get_user_meta($current_user->ID, 'my_endorsements', false);
$current_app_settings = null !== get_user_meta($current_user->ID, 'app_settings', true) ? get_user_meta($current_user->ID, 'app_settings', true) : "";
$enabled = isset($current_app_settings['enabled']) ? $current_app_settings['enabled'] : 'false';

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
	<div id="primary" class="content-area">        
		<main id="main" class="site-main" role="main">
            <div class="message-notification"><h4>You Have <span id="new-message"></span> Messages!</h4></div>
            <div class="user-wrapper">
                <div class="user-information">
                    <?php echo "<h1>" . $current_user->display_name . "'s Profile</h2>"; ?>
            
                    <?php echo get_wp_user_avatar($current_user->ID, 96); ?>
            
                    <h3>current dec status:</h3>

                    <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo $negdecstatus; ?>">
                    <input class="<?php if($decstatus === "ondec"){ echo "currently-ondec"; } else { echo "currently-offdec"; } ?>" id="submit" type="button" value="<?php echo "Currently " . $decstatus; ?>">
            
                    <p>
                        <a href="/professionals/<?php echo $current_user->user_login; ?>">view profile</a> 
                            | 
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

                <?php if($enabled === 'false'): ?>
                    <button id="open-app-settings">Enable Appointments</button>
                    <div style="display:none;" class="appointment-options">
                <?php else: ?>
                <div class="appointment-options">
                <?php endif; ?>
                    <?php echo do_shortcode('[od-app-settings]'); ?>
                    <h3>Today's Appointments</h3>
                    <?php echo OD_Appointments::get_todays_appointments(); ?>
                    <button id="open-app-calendar">View Appointment Calendar</button>
                    <div class="my-appointments">
                        <div class="year-picker">
                            <label for="app-month-picker">Month:</label><input name="app-month-picker" type="number" class="current-app-month" min="01" max="12" value="<?php echo date('m'); ?>"/>
                            <label for="app-year-picker">Year:</label><input name="app-year-picker" type="number" class="current-app-year" value="<?php echo date('Y'); ?>" min="<?php echo date('Y'); ?>"/>
                        </div> 
                        <div class="calendar-wrapper">
                            <?php echo OD_Appointments::define_profile_calendar("now"); ?>
                        </div>
                    </div>
                    <div class="pop-notification" id="approval-appointment">
                        <h3>Approve Client Appointment?</h3>
                        <p>Send a message to the client along with your choice.(optional)</p>
                        <textarea id="app-approval-message"></textarea>
                        <button id="approve-appointment">Approve</button>
                        <button id="close-appointment">Close</button>
                        <button id="deny-appointment">Deny</button>
                    </div>
                </div>
            </div>
            </div>    
                
            <div class="member-lists">

                <div class="list-sections">

                    <div style="display:none;" id="rmsuccess">successfully removed!</div>

                    <div class="list-section-wrapper">
        
                        <?php 
                            if(isset($my_dec_info) && is_array($my_dec_info)) :
                                foreach($my_dec_info as $single_dec_info){                          

                                     $dec_count[] = $single_dec_info;                            
                                }
                            endif;
                        ?>


                        <h3>My Followers (<?php echo isset($dec_count) ? count($dec_count) : "0"; ?>) </h3>
                
                        <div class="od-my-list single-member-list">
                
                            <ul>

                                <?php foreach($my_dec_info as $single_dec_member) :

                                    $user_information = get_userdata($single_dec_member->ID);

                                    $user_type = $user_information->roles;
                                    ?>
                                    <li class="decmember-<?php echo $single_dec_member->ID; ?>">

                                        <a href='/clients/<?php echo $user_information->user_login; ?>'>

                                            <div class="dec-user">

                                                <?php echo $single_dec_member->display_name; ?>
                                            </div>

                                            <div class="dec-image">

                                                <?php echo get_wp_user_avatar($single_dec_member->ID, 130); ?>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                     
                    <div class="list-section-wrapper middle my-businesses">
                         <?php 
                        if(isset($current_biz[0][0]) && is_array($current_biz[0][0])) :
                            foreach($current_biz[0] as $single_biz_info){                          

                                $biz_count[] = $single_biz_info;                            
                            }
                        endif;
                        ?>
                        
                        <h3>My Business Locations (<span id="biz-count"><?php echo isset($biz_count) ? count($biz_count) : "0"; ?></span>) </h3>
						<div class="add-a-business">
							<h3>Can't find the business location you're working at?</h3>
							<button id="add-a-business">Add A Business!</button>
							<div class="add-a-business-form">
								<span class="close-aab-form">X</span>
								<h4>Create Business Account</h4>
								<label for="aab-business-name">Business Name</label>
								<input type="text" id="aab-business-name" placeholder="enter name" />
								<label for="aab-business-address">Address</label>
								<input type="text" id="aab-business-address" placeholder="enter address" />
								<button id="submit-new-business-aab">Submit</button>
							</div>
						</div>
            
                        <div class="od-my-businesses single-member-list">
                            <div style="display:none;" id="rmsuccess">successfully removed business!</div>
                            <div style="display:none;" id="currentlocmsg">successfully set current location!</div>
                            <div style="display:none;" id="bizapproved">Business approved!</div>
							
                                <ul>                                
                                <?php if(isset($current_biz[0])) :
									
									foreach($current_biz[0] as $single_dec_business) :

                                    $user_information = get_userdata(intVal($single_dec_business['user']));
                    
                                ?>
                                    <li class="decmember-<?php echo $single_dec_business['user']; ?>">

                                        <a href='/businesses/<?php echo $user_information->user_login; ?>'>

                                            <div class="dec-user">

                                                <?php echo $user_information->display_name; ?>

                                            </div>

                                            <div class="dec-image">

                                                <?php echo get_wp_user_avatar($single_dec_business['user'], 130); ?>
                                            </div>
                                        </a>

                                        <div>
                            
                                            <?php 

                                            $currentloc = $profile_pages->is_current_location($single_dec_business['user']);

                                            if($currentloc){
                                                $mybizloc = "current-location";
                                                $mybizmsg = "current location";
                                            }else {
                                                $mybizloc = "not-current-location-" . $single_dec_business['user'] . " not-current-location";
                                                $mybizmsg = "set location";
                                            }
                                            ?>
                                   
                                            <input <?php if($single_dec_business['approval_status'] === 'pending') : ?> style="display:none;" <?php endif; ?>    
                                            class="<?php echo $mybizloc; ?>" id="<?php echo $single_dec_business['user']; ?>" type="button" value="<?php echo $mybizmsg; ?>">

                                            <?php if($single_dec_business['approval_status'] === 'pending') : ?>  

                                                <input id="<?php echo $single_dec_business['user']; ?>" class="approve-biz approve-biz-<?php echo $single_dec_business['user']; ?>" value="approve" type="button">

                                            <?php endif; ?>
                                
                                            <input id="<?php echo $single_dec_business['user']; ?>" class="decremove" type="button" value="remove">
                                        </div>
                                    </li>
                                <?php endforeach; endif; ?>
                            </ul>
                        </div>
                        </div>
                     
                        <div class="list-section-wrapper my-endorsements">
                    
                            <?php if(isset($current_endorsements[0][0]) && is_array($current_endorsements[0][0])){
                    
                                foreach($current_endorsements as $endorsements) : 
                    
                                    foreach(array_reverse($endorsements) as $endorsement) :
                    
                                        $endorsement_user_info = isset($endorsement['user']) ? get_userdata($endorsement['user']) : "";
                
                                        if(isset($endorsement['approval_status'])){
                        
                                            $endorsement_count[] = $endorsement['approval_status'];
                                        }
                                endforeach; endforeach; } ?>
                            
                                
                                    
                                    <div class="endorsement-count">
                                        <h3>My Endorsements<span id="endorsement-count">
                                            (<?php if(isset($endorsement_count) && is_array($endorsement_count)){
                                            echo intVal(count($endorsement_count));
                                        }else{ echo "0"; } ?></span>)
                                        </h3>  
                                    </div> 
                                <div class="single-member-list my-endorsements">
                                    <ul>
      <?php if(isset($current_endorsements[0][0]) && is_array($current_endorsements[0][0])){ ?> 
                                    <?php foreach($current_endorsements as $endorsements) : 
                    
                                        foreach(array_reverse($endorsements) as $endorsement) :
                    
                                            $endorsement_user_info = isset($endorsement['user']) ? get_userdata($endorsement['user']) : "";
                                            ?> 
                
                                            <li class="decend-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">
                                                <div style="display:none;" class="endorsementWrap-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">
                                                    <div class="endorsementOverlay">
                                                        &nbsp;
                                                    </div>
                                                    <div class="vertical-offset">
                                                        <div class="endorsementBox">
                                                            <div class="view-endorsement-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" >
                                                                <h2>
                                                                    <?php echo isset($endorsement_user_info->display_name) ? $endorsement_user_info->display_name : ""; ?>
                                                                </h2>
                                                                <h3>
                                                                    <?php echo isset($endorsement['endorsement']) ? $endorsement['endorsement'] : ""; ?>
                                                                </h3>
                                                            </div>
                                                            <?php if($endorsement['approval_status'] === 'pending'): ?>
                                                                <input type="button" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" class="approve-endorsement" value="approve">
                                                            <?php endif; ?>
                                                            <a class="closeEndorsement" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">Close</a>
                                                        </div>
                                                    </div>
                                                </div>  
                                                <div class="message-date">
                                                    <?php echo isset($endorsement['endorsement_date']) ? date("F j, Y", $endorsement['endorsement_date']) : ""; ?>
                                                </div>
                                                <a href="/clients/<?php echo $endorsement_user_info->login_info; ?>">
                                                <div class="user-endorsement-info">
                                                    <div class="dec-user">
                                                        <?php echo isset($endorsement_user_info->display_name) ? $endorsement_user_info->display_name : ""; ?>
                                                    </div>
                                                    <div class="endorsement-image">
                                                        <?php echo isset($endorsement_user_info->ID) ? get_wp_user_avatar($endorsement_user_info->ID, 60) : ""; ?>
                                                    </div>
                                                </div>
                                                </a>
                                                <div class="user-endorsement">
                                                    <div class="endorsement-wrapper">
                                                        <?php echo isset($endorsement['endorsement']) ? substr($endorsement['endorsement'], 0, 80) . "..." : ""; ?>
                                                    </div>
                                                    <div class="view-endorsement">

                                                        <input type="button" class="view-endorsement-button <?php if($endorsement['approval_status'] !== 'pending') echo 'approved'; ?>" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" type="button" value="<?php if( $endorsement['approval_status'] !== 'pending'){ echo 'view endorsement '; }else{ echo $endorsement['approval_status'];} ?>">

                                                        <input id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" class="removeendorsement" type="button" value="remove">
                                                    </div>
                                                </div>
                                            </li>     
                                    <?php endforeach; endforeach;}else{
                                        if(array(0=> NULL) !== $current_endorsements) :
                                            foreach(array_reverse($current_endorsements) as $endorsement) :
                    
                                                $endorsement_user_info = isset($endorsement['user']) ? get_userdata($endorsement['user']) : "";
                    
                                                if(isset($endorsement['approval_status'])){
                        
                                                    $endorsement_count[] = $endorsement['approval_status'];
                                                 }
                                            endforeach; 
                                            
                                            foreach(array_reverse($current_endorsements) as $endorsement) :
                    
                                                $endorsement_user_info = isset($endorsement['user']) ? get_userdata($endorsement['user']) : ""; ?> 
                                                
                                               <li class="decend-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">
                                                     <div style="display:none;" class="endorsementWrap-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">
                                                        <div class="endorsementOverlay">
                                                            &nbsp;
                                                        </div>
                                                        <div class="vertical-offset">
                                                            <div class="endorsementBox">
                                                                <div class="view-endorsement-<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" >
                                                                    <h2>
                                                                        <?php echo isset($endorsement_user_info->display_name) ? $endorsement_user_info->display_name : ""; ?>
                                                                    </h2>
                                                                    <h3>
                                                                        <?php echo isset($endorsement['endorsement']) ? $endorsement['endorsement'] : ""; ?>
                                                                    </h3>
                                                                </div>
                                                                <?php if($endorsement['approval_status'] === 'pending'): ?>
                                                                <input type="button" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" class="approve-endorsement" value="approve">
                                                                <?php endif; ?>
                                                                <a class="closeEndorsement" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>">Close</a>
                                                            </div>
                                                        </div>
                                                    </div>    
                                                        <div class="message-date">
                                                            <?php echo isset($endorsement['endorsement_date']) ? date("F j, Y", $endorsement['endorsement_date']) : ""; ?>
                                                        </div>
                                                   <a href="/clients/<?php echo $endorsement_user_info->login_info; ?>">
                                                        <div class="user-endorsement-info">
                                                            <div class="dec-user">
                                                                <?php echo isset($endorsement_user_info->display_name) ? $endorsement_user_info->display_name : ""; ?>
                                                            </div>
                                                            <div class="endorsement-image">
                                                                <?php echo isset($endorsement_user_info->ID) ? get_wp_user_avatar($endorsement_user_info->ID, 60) : ""; ?>
                                                            </div>
                                                        </div>
                                                   </a>
                                                        <div class="user-endorsement">
                                                            <div class="endorsement-wrapper">
                                                                <?php echo isset($endorsement['endorsement']) ? substr($endorsement['endorsement'], 0, 80) . "..." : ""; ?>
                                                            </div>
                                                            <div class="view-endorsement">

                                                                <input type="button" class="view-endorsement-button <?php if($endorsement['approval_status'] !== 'pending') echo 'approved'; ?>" id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" type="button" value="<?php if( $endorsement['approval_status'] !== 'pending'){ echo 'view endorsement'; }else{ echo $endorsement['approval_status'];} ?>">

                                                                <input id="<?php echo isset($endorsement['endorsementid']) ? $endorsement['endorsementid'] : ""; ?>" class="removeendorsement" type="button" value="remove">
                                                            </div>
                                                        </div>
                                                    </li>

                                               <?php endforeach; endif; }?></ul>
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
                    endforeach; endforeach; } ?>
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