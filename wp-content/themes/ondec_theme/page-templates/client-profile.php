<?php
session_start();
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
                        
                        <div class="dec-message">
                            
                            <?php echo get_user_meta($single_dec_member, 'decmessage', true); ?>
                        </div>
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
