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
            
            $creds = $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = true;
            
            wp_signon( $creds, false );
            
            $site_url = home_url( '/' );
            
            $page = get_page_by_path( 'my-profile' , OBJECT );

            if ( isset($page) ){
                $location = $site_url . 'my-profile';
            } else {
                $location = $site_url;
            }
            
            wp_safe_redirect( $location );
            exit;
        }
    }
}