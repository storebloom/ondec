<?php
session_start();
global $profiles_pages;
/**
 * Template Name: Client Profile
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post();?>
            
            <?php 
                global $wp_query;
            
                if(isset($wp_query->query_vars['client'])){
                    
                    $user_info = get_user_by('login', $wp_query->query_vars['client'] );
                    
                }
                
            ?>
            <h1 class="title-user-name"><?php echo $user_info->first_name . " " . $user_info->last_name; ?></h1>
            <div class="profile-part profile-image">
                <?php echo get_wp_user_avatar($user_info->ID, 96); ?>
            </div>
            <?php if($current_user->roles[0] === 'client' && $profile_pages->is_not_on_list($user_info->ID, 'myfriends')): ?>
            <div class='decrequestbutton_wrapper'>
                <span style='display: none;' id='successrequest-<?php echo $user_info->ID; ?>'>Friend Request submitted!</span>
                <form id='decrequestmeform-<?php echo $user_info->ID; ?>' name='decrequestmeform'>
                    <input type='hidden' id='decrequestmebutton' value='<?php echo $user_info->ID; ?>'>
                    <input type='button' id='requesttoyourdec' value='Friend Me'>
                </form>
            </div>
            
            <?php endif; ?>
            <div class="profile-part profile-status">
                <?php echo get_user_meta($user_info->ID, 'decstatus', true); ?>
            </div>
            <div class="profile-part profile-message">
                <h3>Status:</h3>
                <?php echo get_user_meta($user_info->ID, 'decmessage', true); ?>
            </div>
            <div class="profile-part profile-bio">
                <h3>Bio:</h3>
                <?php echo get_user_meta($user_info->ID, 'description', true); ?>
            </div>
            <div class="profile-part profile-dec">
                <h3>I'm Following:</h3>
                <ul>
                    <?php 
                    
                    $my_dec_info = get_user_meta( $user_info->ID, 'mydec', false);
                    
                    if(isset($my_dec_info[0])):
                    
                    foreach($my_dec_info[0] as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member; ?>">
                        <a href='/professionals/'.$user_information->user_login; ?>'>
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
                    <?php $my_friend_info = get_user_meta( $user_info->ID, 'myfriends', false);
                 
            if(isset($my_friend_info[0][0]) && is_array($my_friend_info[0][0])) :
                            foreach($my_friend_info[0] as $single_friend_info){
                                
                                if($single_friend_info['approval_status'] === 'approved'){
                                    $friend_count[] = $single_friend_info;
                                }
                            }
                          if(isset($friend_count)) :
            ?>
            
            <div class="profile-part profile-dec">
                <h3>My Friends (<?php echo count($friend_count); ?>) </h3>
                <ul>
                    <?php
                    if(isset($my_friend_info[0][0]) && is_array($my_friend_info[0][0])) :
                    
                    foreach($my_friend_info[0] as $single_friend_member) :
                    
                    $friend_information = get_userdata($single_friend_member['user']);
                ?>
                    <li class="decmember-<?php echo $single_friend_member; ?>">
                        <a href='<?php echo '/clients/'.$friend_information->user_login; ?>'>
                        <div class="dec-name">
                            
                            <?php echo $friend_information->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_friend_member['user'], 96); ?>                     
                        </div>                            
                        </a>
                    </li>
                    
                <?php endforeach; endif; endif; endif; ?>
                    
                </ul>
            </div>
            
           <?php  $my_like_info = get_user_meta( $user_info->ID, 'mylikes', false);
                 
            if(isset($my_like_info[0]) && $my_like_info[0] !== "") :
                            foreach($my_like_info[0] as $single_like_info){
                                if(isset($single_like_info) && "" !== $single_like_info) :
                                $like_count[] = $single_like_info;
                                endif; 
                            }
                          if(isset($like_count)) :
            ?>
            
            <div class="profile-part profile-dec">
                <h3>Businesses I Like (<?php echo count($like_count); ?>) </h3>
                <ul>
                    <?php 
                    
                    $my_like_info = get_user_meta( $user_info->ID, 'mylikes', false);
                    
                    if(isset($my_like_info[0])):
                    
                    foreach($my_like_info[0] as $single_like_member) :
                    if(isset($single_like_member) && "" !== $single_like_member) :
                        
                        $like_information = get_userdata($single_like_member);
                    
                ?>
                    <li class="decmember-<?php echo $single_like_member; ?>">
                        <a href='<?php echo '/businesses/'.$like_information->user_login; ?>'>
                        <div class="dec-name">
                            
                            <?php echo $like_information->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_like_member, 96); ?>                     
                        </div>                            
                        </a>
                        
                        <div class="dec-status">
                            We Are <?php echo get_user_meta($single_like_member, 'decstatus', true); ?>  
                        </div>
                    </li>
                    
                <?php endif; endforeach; endif; endif; endif; ?>
                    
                </ul>
            </div>
            
              <?php if(isset($current_user->roles[0])) : ?>
            <a name="messages"></a>
                <div class="messge-me-section">
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
get_footer(); ?>

<script>
    jQuery(document).ready(function() {
        
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
    jQuery('#requesttoyourdec').click(function(){
        var requestdecid = jQuery('#decrequestmebutton').val();
        jQuery.post( 
            ajaxurl,
                {   
                    'action': 'request_decmember',
                    'requestdecid': requestdecid
                }, 
                function(response){
                jQuery('#decrequestmeform-<?php echo $user_info->ID; ?>').slideDown(800).fadeOut(400);    
                jQuery('#successrequest-<?php echo $user_info->ID; ?>').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);
            }
        );
    });      
    
});
</script>
        
<?php
