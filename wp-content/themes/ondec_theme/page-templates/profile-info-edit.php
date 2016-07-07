<?php 
/**
 * Template Name: Edit Profile Info
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */
get_header();
?>
<h1><?php echo $current_user->display_name; ?>'s Profile Information</h1>
<h3>Profile Photo:</h3>

            <?php                /* Get user info. */
            
            //get_currentuserinfo(); //deprecated since 3.1

            $content = html_entity_decode(get_the_author_meta( 'description', $current_user->ID ));

            if($current_user->roles[0] === 'business'){
                
                $first_name_label = "Contact First Name";
                $last_name_label = "Contact Last Name";
            }else{
                $first_name_label = "First Name";
                $last_name_label = "Last Name";
            }
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
                if ( !empty( $_POST['display_name'] ) )
                    wp_update_user( array ('ID' => $current_user->ID, 'display_name' => esc_attr( $_POST['display_name'] )));
                if ( !empty( $_POST['email'] ) ){
                    if (!is_email(esc_attr( $_POST['email'] )))
                        $error[] = __('The Email you entered is not valid.  please try again.', 'profile');
                    elseif(email_exists(esc_attr( $_POST['email'] )) != $current_user->id )
                        $error[] = __('This email is already used by another user.  try a different one.', 'profile');
                    else{
                        wp_update_user( array ('ID' => $current_user->ID, 'user_email' => esc_attr( $_POST['email'] )));
                    }
                }
                
                
                if ( !empty( $_POST['nickname'] ) )
                    update_user_meta( $current_user->ID, 'nickname', esc_attr( $_POST['nickname'] ) );
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
                        <h3>Your Information:</h3>
                            <form method="post" id="adduser" action="<?php the_permalink(); ?>">

                                <p>
                                    <label for="display_name"><?php _e('Display name publicly as') ?></label>
                                    <select name="display_name" id="display_name">
                                    <?php
                                        $public_display = array();
                                        $public_display['display_nickname']  = $current_user->nickname;
                                        $public_display['display_username']  = $current_user->user_login;

                                        if ( !empty($profileuser->first_name) )
                                            $public_display['display_firstname'] = $current_user->first_name;

                                        if ( !empty($profileuser->last_name) )
                                            $public_display['display_lastname'] = $current_user->last_name;

                                        if ( !empty($profileuser->first_name) && !empty($current_user->last_name) ) {
                                            $public_display['display_firstlast'] = $current_user->first_name . ' ' . $current_user->last_name;
                                            $public_display['display_lastfirst'] = $current_user->last_name . ' ' . $current_user->first_name;
                                        }

                                        if ( !in_array( $current_user->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
                                            $public_display = array( 'display_displayname' => $current_user->display_name ) + $public_display;

                                        $public_display = array_map( 'trim', $public_display );
                                        $public_display = array_unique( $public_display );

                                        foreach ( $public_display as $id => $item ) {
                                    ?>
                                        <option <?php selected( $current_user->display_name, $item ); ?>><?php echo $item; ?></option>
                                    <?php
                                        }
                                    ?>
                                    </select>
                                </p>
                                 
                                <?php if($current_user->roles[0] === 'business'): ?>
                                
                                <p class="form-username">
                                    <label for="nickname"><?php _e('Business Name', 'profile'); ?></label>
                                    <input class="text-input" name="nickname" type="text" id="nickname" value="<?php the_author_meta( 'nickname', $current_user->ID ); ?>" />
                                </p><!-- .form-username -->
                                
                                <?php endif; ?>
                                
                                <p class="form-username">
                                    <label for="first-name"><?php _e($first_name_label, 'profile'); ?></label>
                                    <input class="text-input" name="first-name" type="text" id="first-name" value="<?php the_author_meta( 'first_name', $current_user->ID ); ?>" />
                                </p><!-- .form-username -->
                                <p class="form-username">
                                    <label for="last-name"><?php _e($last_name_label, 'profile'); ?></label>
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
                                    <label for="description"><?php _e('About You:', 'profile') ?></label>
                                    <?php wp_editor($content, 'description', $settings = array('textarea_name' => 'description')); ?>
                                </p><!-- .form-textarea -->
                                
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
            
	<a href="/my-profile">back to my profile</a>

<?php
get_footer();