<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

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
            echo 'Registration Successful!';  
            
            $site_url = home_url( '/' );
            
            $page = get_page_by_path( 'my-profile' , OBJECT );

            if ( isset($page) ){
                $location = $site_url . 'my-profile';
            } else {
                $location = $site_url;
            }
            
            $string = '<script type="text/javascript">';
            $string .= 'window.location = "' . $location . '?user='.$username.'&pass='.$password.'"';
            $string .= '</script>';

            echo $string;
        }
    }
}

$od_complete_registration = new OD_Complete_Registration();