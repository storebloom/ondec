<?php
$user_role = $current_user->roles[0];

$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$biz_title = array("client" => "dec", "professional" => "Followers", "business" => "Current Professoinal");  
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
            
            <p><a href="/businesses/<?php echo $current_user->user_login; ?>">view my profile</a>
            </p>
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
            <h3>My Pro Requests</h3>
            
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

                <?php                /* Get user info. */

                //get_currentuserinfo(); //deprecated since 3.1

                /* Load the registration file. */
                //require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
                $error = array();    
                /* If profile was saved, update profile. */
                if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

                    /* Update user password. */
                    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
                        if ( $_POST['pass1'] == $_POST['pass2'] )
                            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
                        else
                            $error[] = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
                    }

                    /* Update user information. */
                    if ( !empty( $_POST['url'] ) )
                        wp_update_user( array( 'ID' => $current_user->ID, 'user_url' => esc_url( $_POST['url'] ) ) );
                    if ( !empty( $_POST['email'] ) ){
                        if (!is_email(esc_attr( $_POST['email'] )))
                            $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
                        elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
                            $error[] = __('This email is already used by another user.  try a different one.', 'profile');
                        else{
                            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
                        }
                    }

                    if ( !empty( $_POST['first-name'] ) )
                        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first-name'] ) );
                    if ( !empty( $_POST['last-name'] ) )
                        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last-name'] ) );
                    if ( !empty( $_POST['description'] ) )
                        update_user_meta( $current_user->ID, 'description', esc_attr( $_POST['description'] ) );

                    /* Redirect so the page will show updated info.*/
                  /*I am not Author of this Code- i dont know why but it worked for me after changing below line to if ( count($error) == 0 ){ */
                    if ( count($error) == 0 ) {
                        //action hook for plugins and extra fields saving
                        do_action('edit_user_profile_update', $current_user->ID);
                        wp_redirect( get_permalink() );
                        exit;
                    }
                }
                ?>

                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <div id="post-<?php the_ID(); ?>">
                        <div class="entry-content entry">
                            <?php the_content(); ?>
                            <?php if ( !is_user_logged_in() ) : ?>
                                    <p class="warning">
                                        <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                                    </p><!-- .warning -->
                            <?php else : ?>
                                <?php if ( count($error) > 0 ) echo '<p class="error">' . implode("<br />", $error) . '</p>'; ?>
                                <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                                    <p class="form-username">
                                        <label for="first-name"><?php _e('First Name', 'profile'); ?></label>
                                        <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                                    </p><!-- .form-username -->
                                    <p class="form-username">
                                        <label for="last-name"><?php _e('Last Name', 'profile'); ?></label>
                                        <input class="text-input" name="last-name" type="text" id="last-name" value="<?php the_author_meta( 'last_name', $current_user->ID ); ?>" />
                                    </p><!-- .form-username -->
                                    <p class="form-email">
                                        <label for="email"><?php _e('E-mail *', 'profile'); ?></label>
                                        <input class="text-input" name="email" type="text" id="email" value="<?php the_author_meta( 'user_email', $current_user->ID ); ?>" />
                                    </p><!-- .form-email -->
                                    <p class="form-url">
                                        <label for="url"><?php _e('Website', 'profile'); ?></label>
                                        <input class="text-input" name="url" type="text" id="url" value="<?php the_author_meta( 'user_url', $current_user->ID ); ?>" />
                                    </p><!-- .form-url -->
                                    <p class="form-password">
                                        <label for="pass1"><?php _e('Password *', 'profile'); ?> </label>
                                        <input class="text-input" name="pass1" type="password" id="pass1" />
                                    </p><!-- .form-password -->
                                    <p class="form-password">
                                        <label for="pass2"><?php _e('Repeat Password *', 'profile'); ?></label>
                                        <input class="text-input" name="pass2" type="password" id="pass2" />
                                    </p><!-- .form-password -->
                                    <p class="form-textarea">
                                    <label for="description"><?php _e('Biographical Information', 'profile') ?></label>
                                    <textarea name="description" id="description" rows="3" cols="50"><?php the_author_meta( 'description', $current_user->ID ); ?></textarea>
                                </p><!-- .form-textarea -->

                                <?php 
                                    //action hook for plugin and extra fields
                                    //do_action('edit_user_profile',$current_user); 
                                ?>
                                <p class="form-submit">
                                    <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
                                    <?php wp_nonce_field( 'update-user' ) ?>
                                    <input name="action" type="hidden" id="action" value="update-user" />
                                </p><!-- .form-submit -->
                                    
                            </form><!-- #adduser -->
                            
                        <?php endif; ?>
                    </div><!-- .entry-content -->
                </div><!-- .hentry .post -->
           
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-data">
                    <?php _e('Sorry, no page matched your criteria.', 'profile'); ?>
                </p><!-- .no-data -->
            <?php endif; ?>                
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();

?>
<script>
    jQuery(document).ready(function() {
        
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
});
 
</script>