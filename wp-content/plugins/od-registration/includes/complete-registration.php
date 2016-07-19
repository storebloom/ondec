<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_Complete_Registration
 */
class OD_Complete_Registration {

    public function complete_registration($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $role, $address ="", $business_type="") {
        
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
            
            if($address !== "" || $business_type !== ""){
                update_user_meta($user, 'address', $address);
                update_user_meta($user, 'business_type', $business_type);
            }
            echo 'Registration Successful!';  
            
            $site_url = home_url( '/' );
            
            $page = get_page_by_path( 'my-profile' , OBJECT );

            if ( isset($page) ){
                $location = $site_url . 'my-profile';
            } else {
                $location = $site_url;
            }
            
            $_SESSION['user'] = isset($username) ? $username : "";
            $_SESSION['pass'] = isset($password) ? $password : "";
            
            $string = '<script type="text/javascript">';
            $string .= 'window.location = "' . $location . '"';
            $string .= '</script>';

            echo $string;
        }
    }
}

$od_complete_registration = new OD_Complete_Registration();