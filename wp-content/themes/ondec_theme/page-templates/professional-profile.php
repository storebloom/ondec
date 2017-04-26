<?php
session_start();
global $profiles_pages, $current_user, $od_appointments;
/**
 * Template Name: Professional Profile
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */
get_header(); 

date_default_timezone_set('America/Los_Angeles');
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post();?>
            
            <?php 
                global $wp_query;
            
                if(isset($wp_query->query_vars['professional'])){
                    
                    $user_info = get_user_by('login', $wp_query->query_vars['professional'] );
                    
                    $is_not_on_list = $profile_pages->is_not_on_list($user_info->ID);
                    $current_app_settings = null !== get_user_meta($user_info->ID, 'app_settings', true) ? get_user_meta($user_info->ID, 'app_settings', true) : "";
                    $enabled = isset($current_app_settings['enabled']) ? $current_app_settings['enabled'] : 'false';
                }
            ?>
   <div class="profile-content-wrapper">    
        <div class="user-info-wrapper">
            <div class="user-info">
                <h1 class="title-user-name"><?php echo $user_info->first_name . " " . $user_info->last_name; ?></h1>
            <div class="profile-part profile-image">
                <?php echo get_wp_user_avatar($user_info->ID, 96); ?>
            </div>
            <div class="profile-part profile-status">
                <?php echo get_user_meta($user_info->ID, 'decstatus', true); ?>
            </div>
            <div class="current-location-profile">
               <?php 
                            
                $user_location = intval(get_user_meta($user_info->ID, 'current_location', true));

                $user_information = get_userdata($user_location);
                
                if(isset($user_information->display_name)) : ?>
                    Current Location:
                    <a href='/businesses/<?php echo $user_information->user_login; ?>'>                

                        <?php echo isset($user_information->display_name) ? $user_information->display_name : ""; ?>

                    </a>
                <?php endif; ?>
            </div>
            
            <?php if(isset($current_user->roles[0]) && $current_user->roles[0] === 'client' && $is_not_on_list): ?>
            
            <div class='decaddbutton_wrapper'>
                <span style='display: none;' class='successadd'>I'm on your dec list now!</span>
                <input type='button' class='addtoyourdec' id='<?php echo $user_info->ID; ?>' value='Follow Me'>
            </div>
            
            <?php elseif(isset($current_user->roles[0]) && $current_user->roles[0] === 'business' && $profile_pages->is_not_on_list($user_info->ID, 'mybusinesses')): ?>
            
            <div class='decrequestbutton_wrapper'>
                <span style='display: none;' id='successrequest-<?php echo $user_info->ID; ?>'>Request submitted!</span>
                <form id='decrequestmeform-<?php echo $user_info->ID; ?>' name='decrequestmeform'>
                    <input type='button' class='requesttoyourdec' id='<?php echo $user_info->ID; ?>' value='Request Professional'>
                </form>
            </div>
            
            <?php endif; ?>
                 
            <div class="profile-part profile-message">
                <h3>Status:</h3>
                <?php echo get_user_meta($user_info->ID, 'decmessage', true); ?>
            </div>
            <div class="profile-part profile-bio">
                <h3>Bio:</h3>
                <?php echo get_user_meta($user_info->ID, 'description', true); ?>
            </div>
            <div class="profile-part profile-dec">
                <h3>My Followers:</h3>
                <ul>
                    <?php 
                   
                    $my_dec_info = get_user_meta( $user_info->ID, 'mydec', false);
                     
                    if(isset($my_dec_info[0])) :
                    
                    foreach($my_dec_info[0] as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member; ?>">
                        <a href='/<?php echo $user_type[0].'s/'.$user_information->user_login; ?>'>
                        <div class="dec-name">
                            
                            <?php echo $user_information->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_dec_member, 96); ?>                     
                        </div>                            
                        </a>
                        
                        <div class="pro-type">
                            <ul>
                            <?php 
                                if( "" !== get_user_meta($single_dec_member, 'protype', true) && null !== get_user_meta($single_dec_member, 'protype', true)){
                                $pro_types = $profile_pages->get_pro_type_readable($single_dec_member);
                            
                            foreach($pro_types as $pro_type): ?>
                                
                            
                                <li><?php echo $pro_type; ?></li>   
                            <?php endforeach; } ?>   
                                
                            </ul>
                        </div>
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member, 'decstatus', true); ?>  
                        </div>
                        
                        <div class="dec-message">
                            
                            <?php echo get_user_meta($single_dec_member, 'decmessage', true); ?>
                        </div>
                    </li>
                <?php endforeach; endif; ?>
                    
                </ul>
            </div>
            <?php if(is_user_logged_in() && $current_user->roles[0] === 'client' && $enabled === 'true') : ?>

            <div class="appointments">
                <div class="app_success" style="display: none;">Your appointment has been submited!</div>
                
                <div class="app_controls">
                    <input userid="<?php echo (int) $user_info->ID; ?>" value="<?php echo esc_html( date('Y-m-d') ); ?>" type="" id="ui-datepicker" />
                </div>
                
                <div class="current_apps">
                </div> 
            <?php endif; ?> 
            <h3>My Business Locations</h3>
            
            <div class="od-my-businesses">
                
                <ul id="business-list">
                    
                    <?php $mybusinesses = null !== get_user_meta($user_info->ID, 'mybusinesses', false) ? get_user_meta($user_info->ID, 'mybusinesses', false) : array( 0 => array());
                    $my_business_info = array();
                    
                    if(isset($mybusinesses[0])){
                        foreach($mybusinesses[0] as $single_business){
                            $my_business_info[] = get_userdata($single_business['user']);
                        }
                    }

                    foreach($my_business_info as $single_dec_business) :

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
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <?php if(null !== get_user_meta($user_info->ID, 'my_endorsements')) : ?>
            
            <div class="my-endorsements">
                <h3>My Endorsements</h3>
                
                <ul>
                    <?php $my_endorsements = get_user_meta($user_info->ID, 'my_endorsements', true);
                    
                foreach($my_endorsements as $single_endorsement) :
                    if($single_endorsement['approval_status'] === 'approved'):
                        $user_information = get_userdata($single_endorsement['user']);
                    ?>
                        <li class="decbiz-<?php echo $user_information->ID; ?>">

                            <a href='/clients/<?php echo $user_information->user_login; ?>'>

                                <div class="dec-name">

                                    <?php echo $user_information->display_name; ?>

                                </div>

                                <div class="dec-image">

                                    <?php echo get_wp_user_avatar($user_information->ID, 96); ?>

                                </div>
                                

                            </a>
                            
                            <div class="endorsement-message">
                            <em><?php echo $single_endorsement['endorsement']; ?></em>
                            </div>
                        </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php if(isset($current_user->roles[0])) : ?>
            
            <?php if($current_user->roles[0] === 'client') : ?>

                <div class="endorse-me">
                    <h3>Endorse Me</h3>
                    <p class="endorse-sent-sucess" style="display:none;">
                     Your endorsement has been submitted!    
                    </p>    
                    <form id="endorseuserform" name="endorseuserform">
                        <input id="endorseinput" type="text" placeholder="write endorsement here" name="decendorse" class="<?php echo $user_info->ID; ?>" value="">
                        <input id="endorsesend" class="endorsesend" type="button" value="submit">
                    </form>
                </div>
            <?php endif; ?>
            
            <a name="messages"></a>
                <div class="messge-me-section">
                    <h3>Message Me</h3>
                <p class="message-sent-sucess" style="display:none;">
                 Your message has been sent!    
                </p>    
                <form id="msguserform" name="msguserform">
                    <input id="usermsginput" type="text" placeholder="write message here" name="decmessage" class="<?php echo $user_info->ID; ?>" value="">
                    <input id="msgsend" class="msgsend" type="button" value="send">
                </form>
            </div>
            <?php endif; ?>
            
                <?php the_content(); ?>
            
            <?php endwhile; endif; ?>
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();