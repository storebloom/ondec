<?php
session_start();
global $profiles_pages;
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
            
                if(isset($wp_query->query_vars['professional'])){
                    
                    $user_info = get_user_by('login', $wp_query->query_vars['professional'] );
                    
                }
                
            ?>
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

                $user_information = get_userdata($user_location); ?>
                
                Current Location:
                <a href='/businesses/<?php echo $user_information->user_login; ?>'>                

                    <?php echo $user_information->display_name; ?>

                </a>
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
                <h3>My Followers:</h3>
                <ul>
                    <?php 
                    
                    $my_dec_info = get_user_meta( $user_info->ID, 'mydec', false);
                    
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
                    
                <?php endforeach; ?>
                    
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
            
                <?php the_content(); ?>
            
            <?php endwhile; endif; ?>
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
