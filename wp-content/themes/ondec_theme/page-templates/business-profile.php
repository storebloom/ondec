<?php
session_start();
global $profiles_pages, $current_user;
/**
 * Template Name: Professional Profile
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
            
                if(isset($wp_query->query_vars['business'])){
                    
                    $user_info = get_user_by('login', $wp_query->query_vars['business'] );
                    
                    $is_not_on_list = $profile_pages->is_not_on_list($user_info->ID, 'mylikes');
                    
                    $openclosed = get_user_meta($user_info->ID, 'decstatus', true);
                }
            ?>
            <h1 class="title-user-name"><?php echo $user_info->display_name; ?></h1>
            <div class="profile-part profile-image">
                <?php echo get_wp_user_avatar($user_info->ID, 200); ?>
            </div>
            
            <?php if($current_user->roles[0] === 'client' && $is_not_on_list): ?>
            
            <div class='declikebutton_wrapper'>
                <span style='display: none;' id='successlike-<?php echo $user_info->ID; ?>'>We like you too!</span>
                <form id='declikemeform-<?php echo $user_info->ID; ?>' name='declikemeform'>
                    <input type='hidden' id='declikemebutton-<?php echo $user_info->ID; ?>' value='<?php echo $user_info->ID; ?>'>
                    <input type='button' id='addtoyourlikes' value='Like us'>
                </form>
            </div>
            
            <?php elseif($current_user->roles[0] === 'professional' && $profile_pages->is_not_on_list($user_info->ID, 'mybusinesses')): ?>
            
            <div class='decrequestbutton_wrapper'>
                <span style='display: none;' id='successrequest-<?php echo $user_info->ID; ?>'>Request submitted!</span>
                <form id='decrequestmeform-<?php echo $user_info->ID; ?>' name='decrequestmeform'>
                    <input type='hidden' id='decrequestmebutton' value='<?php echo $user_info->ID; ?>'>
                    <input type='button' id='requesttoyourdec' value='Request Business'>
                </form>
            </div>
            
            <?php endif; ?>
                        
            <div class="profile-part profile-message">
                <h3>We Are: <?php echo "" !== $openclosed ? $openclosed : "Undecided"; ?></h3>
                
            </div>
            <?php if(!empty(get_user_meta($user_info->ID, 'address'))): ?>
            <div class="direction_address">
                <a target="_blank" href="https://www.google.com/maps/dir/<?php echo get_user_meta($user_info->ID, 'address', true); ?>'">
                    <?php echo get_user_meta($user_info->ID, 'address', true); ?>
                </a>
            </div>
            <?php endif;?>
            <div class="profile-part profile-bio">
                <h3>Bio:</h3>
                <?php echo html_entity_decode(get_user_meta($user_info->ID, 'description', true)); ?>
            </div>
                    
                    <?php 
                    
                    $mylikes = null !== get_user_meta($user_info->ID, 'mylikers', false) ? get_user_meta($user_info->ID, 'mylikers', false) : array( 0 => array());
                    
                    if(isset($mylikes[0]) && $mylikes[0] !== "") :
                        foreach($mylikes[0] as $single_like){
                            $like_count[] = $single_like;
                        }
                      if(isset($like_count)) :      
                    ?>
                <div class="od-my-likes">
                    <h3>Likes (<?php echo count($like_count); ?>)</h3>
                
                    <ul id="like-list">
                        
                    <?php
                        
                    $my_likes_info = array();
                    
                        foreach($mylikes[0] as $single_like){
                            $my_likes_info[] = get_userdata($single_like);
                        }

                    foreach($my_likes_info as $single_dec_like) : ?>
                        
                        <li class="declike-<?php echo $single_dec_like->ID; ?>">
                            <div class="dec-image">

                                <?php echo get_wp_user_avatar($single_dec_like->ID, 30); ?>
                            </div>
                        </li>  
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; endif;
               
            $my_dec_info = get_user_meta( $user_info->ID, 'mydec', false);
                 
            if(isset($my_dec_info[0]) && $my_dec_info[0] !== "") :
                            foreach($my_dec_info[0] as $single_dec_info){
                                $pro_count[] = $single_dec_info;
                            }
                          if(isset($pro_count)) :
            ?>
            <div class="profile-part profile-dec">
                <h3>My Professionals (<?php echo count($pro_count); endif; ?>)</h3>
                <ul>
                    <?php 
                     
                    
                    
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
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member, 'decstatus', true); ?>  
                        </div>
                    </li>
                <?php endforeach; endif; ?>
                    
                </ul>
            </div>
            
            <div class="our-location">
            <h3>Our Location</h3>
               <?php null !== get_user_meta($user_info->ID, 'our_location', false) ? get_user_meta($user_info->ID, 'mylikes', false) : ""; ?>
            </div>
            
            <h3>Message Us:</h3>
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

    jQuery('#addtoyourdec-<?php echo $user_info->ID; ?>' ).click(function(){
        var adddecid = jQuery('#decaddmebutton-<?php echo $user_info->ID; ?>').val();
        jQuery.post( 
            ajaxurl,
                {   
                    'action': 'add_decmember',
                    'adddecid': adddecid
                }, 
                function(response){
                jQuery('#decaddmeform-<?php echo $user_info->ID; ?>').slideDown(800).fadeOut(400);    
                jQuery('#successadd-<?php echo $user_info->ID; ?>').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);   
            }
        );
    });
    jQuery('#addtoyourlikes' ).click(function(){
        var likedecid = jQuery('#declikemebutton-<?php echo $user_info->ID; ?>').val();
        jQuery.post( 
            ajaxurl,
                {   
                    'action': 'like_decmember',
                    'likedecid': likedecid
                }, 
                function(response){
                jQuery('#declikemeform-<?php echo $user_info->ID; ?>').slideDown(800).fadeOut(400);    
                jQuery('#successlike-<?php echo $user_info->ID; ?>').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);   
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

