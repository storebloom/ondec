<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'OD_Complete_Registration' ) )
	return;

/**
 * OD_Complete_Registration
 */
class OD_Complete_Registration {

    public function complete_registration($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role) {
        
        global $reg_errors;
        
        if ( 1 > count( $reg_errors->get_error_messages() ) ) {
            
            $userdata = array(
            'user_login'    =>   $username,
            'user_email'    =>   $email,
            'user_pass'     =>   $password,
            'user_url'      =>   $website,
            'first_name'    =>   $first_name,
            'last_name'     =>   $last_name,
            'nickname'      =>   $nickname,
            'description'   =>   $bio,
            'role'          =>   $role,
            );
            
            $user = wp_insert_user( $userdata );
            
            echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
        }
    }
}