<?php
/**
 * Template Name: Dashboard Profile
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */

session_start();

global $current_user, $wp_roles, $profile_pages;

$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$current_userstatus = get_user_meta($current_user->ID, 'userstatus', true);
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
        <div class="user-wrapper">
            <div class="user-information">
                <h2><?php echo esc_html( $current_user->display_name ); ?>'s Profile</h2>

                <?php echo wp_kses_post( get_wp_user_avatar( $current_user->ID, 96 ) ); ?>

                <h3>current dec status:</h3>

                <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo esc_attr( $negdecstatus ); ?>">
                <input class="<?php 'ondec' === $decstatus ? 'currently-ondec' : 'currently-offdec'; ?>" id="submit-decstatus" type="button" value="<?php echo esc_html( "Currently $decstatus" ); ?>">

                <p>
                    <a href="/<?php echo esc_attr( $current_user->roles[0] ); ?>/<?php echo esc_attr( $current_user->user_login ); ?>">view profile</a>
                    |
                    <a href="edit-profile-info">edit profile</a>
                </p>

                <div id="userstatuswrapper" >
                    <input type="text" placeholder="what's up?" id="userstatus" value="<?php echo esc_html( $current_userstatus ); ?>">
                    <input id="statussubmit" type="button" value="update">
                </div>
            </div>
            <div class="user-tools">
                <h2>Your Tools</h2>

            <?php if ( 'false' === $enabled ) : ?>
                <button id="open-app-settings">Enable Appointments</button>

                <div class="appointment-options">
                </div>
            <?php else: ?>
                <div class="appointment-options">
                    <?php echo do_shortcode('[od-app-settings]'); ?>

                    <h3>Today's Appointments</h3>

                    <?php echo wp_kses_post( $od_appointments->get_todays_appointments() ); ?>

                    <button id="open-app-calendar">View Appointment Calendar</button>

                    <div class="my-appointments">
                        <div class="year-picker">
                            <label for="app-month-picker">Month:</label><input name="app-month-picker" type="number" class="current-app-month" min="01" max="12" value="<?php echo date('m'); ?>"/>
                            <label for="app-year-picker">Year:</label><input name="app-year-picker" type="number" class="current-app-year" value="<?php echo date('Y'); ?>" min="<?php echo date('Y'); ?>"/>
                        </div>
                        <div class="calendar-wrapper"></div>
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
            <?php endif; ?>
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
                <div id="load_messages_container">

                </div>
            </div>
            <div id="messagepop-wrapper">
                <div class="messageOverlay">
                </div>
                <div class="vertical-offset">
                    <div class="messageBox">
                        <div class="view-message" >
                            <h2 id="messagepop-user">
                            </h2>
                            <h3 id="messagepop-message">
                            </h3>
                        </div>
                        <a href="#" id="reply-message" >reply</a>  <a id="closeMessage" >close</a>
                    </div>
                </div>
            </div>
    </main></div>
<?php
get_footer();
