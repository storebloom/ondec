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
                    
                    $is_not_on_list = $profile_pages->is_not_on_list($user_info->ID);
                }
            
                            //response generation function
                  $response = "";

                  //function to generate response
                  function my_contact_form_generate_response($type, $message){

                    global $response;

                    if($type == "success") $response = "<div class='success'>{$message}</div>";
                    else $response = "<div class='error'>{$message}</div>";

                  }
                //response messages
                $not_human       = "Human verification incorrect.";
                $missing_content = "Please supply all information.";
                $email_invalid   = "Email Address Invalid.";
                $message_unsent  = "Message was not sent. Try Again.";
                $message_sent    = "Thanks! Your message has been sent.";

                //user posted variables
                $name = isset($_POST['message_name']) ? $_POST['message_name'] : "";
                $email = isset($_POST['message_email']) ? $_POST['message_email'] : "";
                $message = isset($_POST['message_text']) ? $_POST['message_text'] : "";
                $human = isset($_POST['message_human']) ? $_POST['message_human'] : "";

                //php mailer variables
                $to = $user_info->user_email;
                $subject = "Someone sent a message from ".get_bloginfo('name');
                $headers[] = 'From: ondec <'. $email . '>';
                $headers[] = 'Reply-To: '. $email;

                if(!$human == 0){

                    if($human != 2){ 

                        my_contact_form_generate_response("error", $not_human); //not human! 
                    } else { 

                        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                            my_contact_form_generate_response("error", $email_invalid);
                        } else {

                            if(empty($name) || empty($message)){

                                my_contact_form_generate_response("error", $missing_content);
                            } else {

                                $sent = wp_mail($to, $subject, strip_tags($message), $headers);

                                if($sent){

                                    my_contact_form_generate_response("success", $message_sent);
                                } else {

                                    my_contact_form_generate_response("error", $message_unsent); 
                                }
                            }
                        }
                    } 
                } elseif (isset($_POST['submitted'])){

                        my_contact_form_generate_response("error", $missing_content);
                }
            
            ?>
            <h1 class="title-user-name"><?php echo $user_info->nick_name; ?></h1>
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
            
            <?php if($current_user->roles[0] === 'client' && $is_not_on_list): ?>
            
            <div class='decaddbutton_wrapper'>
                <span style='display: none;' id='successadd-<?php echo $user_info->ID; ?>'>I'm on your dec list now!</span>
                <form id='decaddmeform-<?php echo $user_info->ID; ?>' name='decaddmeform'>
                    <input type='hidden' id='decaddmebutton-<?php echo $user_info->ID; ?>' value='<?php echo $user_info->ID; ?>'>
                    <input type='button' id='addtoyourdec-<?php echo $user_info->ID; ?>' value='Follow Me'>
                </form>
            </div>
            
            <?php elseif($current_user->roles[0] === 'business'): ?>
            
            <div class='decrequestbutton_wrapper'>
                <span style='display: none;' id='successrequest-<?php echo $user_info->ID; ?>'>Request submitted!</span>
                <form id='decrequestmeform-<?php echo $user_info->ID; ?>' name='decrequestmeform'>
                    <input type='hidden' id='decrequestmebutton-<?php echo $user_info->ID; ?>' value='<?php echo $user_info->ID; ?>'>
                    <input type='button' id='requesttoyourdec-<?php echo $user_info->ID; ?>' value='Request Professional'>
                </form>
            </div>
            
            <?php endif; ?>
             <script>
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
                        jQuery('#requesttoyourdec-<?php echo $user_info->ID; ?>').click(function(){
                        var requestdecid = jQuery('#decrequestmebutton-<?php echo $user_info->ID; ?>').val();
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
                    </script>                   
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
            <h3>My Business Locations</h3>
            
            <div class="od-my-businesses">
                
                <ul id="business-list">
                    
                    <?php 
                    
                    $mybusinesses = null !== get_user_meta($user_info->ID, 'mybusinesses', false) ? get_user_meta($user_info->ID, 'mybusinesses', false) : array( 0 => array());
                    $my_business_info = array();
                    
                    if(isset($mybusinesses[0])){
                        foreach($mybusinesses[0] as $single_business){
                            $my_business_info[] = get_userdata($single_business);
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
            
            <div class="profile-contact-form">
                
            <h3>Email Me!</h3>
                
<style type="text/css">
  .error{
    padding: 5px 9px;
    border: 1px solid red;
    color: red;
    border-radius: 3px;
  }
 
  .success{
    padding: 5px 9px;
    border: 1px solid green;
    color: green;
    border-radius: 3px;
  }
 
  form span{
    color: red;
  }
</style>
 
<div id="respond">
  <?php echo $response; ?>
  <form action="" method="post">
    <p><label for="name">Name: <span>*</span> <br><input type="text" name="message_name" value="<?php isset($_POST['message_name']) ? esc_attr($_POST['message_name']) : ""; ?>"></label></p>
    <p><label for="message_email">Email: <span>*</span> <br><input type="text" name="message_email" value="<?php isset($_POST['message_email']) ? esc_attr($_POST['message_email']) : ""; ?>"></label></p>
    <p><label for="message_text">Message: <span>*</span> <br><textarea type="text" name="message_text"><?php isset($_POST['message_text']) ? esc_textarea($_POST['message_text']) : ""; ?></textarea></label></p>
    <p><label for="message_human">Human Verification: <span>*</span> <br><input type="text" style="width: 60px;" name="message_human"> + 3 = 5</label></p>
    <input type="hidden" name="submitted" value="1">
    <p><input type="submit" value="Send"></p>
  </form>
</div>
            
            </div>
            
                <?php the_content(); ?>
            
            <?php endwhile; endif; ?>
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
