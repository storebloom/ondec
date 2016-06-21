<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_Form_Validation
 */
class OD_Form_Validation {
    
    public function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {
        
        global $reg_errors;
        $reg_errors = new WP_Error;

        if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
            
            $reg_errors->add('field', 'Required form field is missing');
        }
        
        if ( 4 > strlen( $username ) ) {
            
            $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
        }
        
        if ( username_exists( $username ) ) {
            
            $reg_errors->add('user_name', 'Sorry, that username already exists!');
        }
        
        if ( ! validate_username( $username ) ) {
            
            $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
        }
        
        if ( 5 > strlen( $password ) ) {
        
            $reg_errors->add( 'password', 'Password length must be greater than 5' );
        }
        
        if ( !is_email( $email ) ) {
            
            $reg_errors->add( 'email_invalid', 'Email is not valid' );
        }
        
        if ( email_exists( $email ) ) {
            
            $reg_errors->add( 'email', 'Email Already in use' );
        }
    
        if ( ! empty( $website ) ) {
           
            if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
                
                $reg_errors->add( 'website', 'Website is not a valid URL' );
            }
        }
    
        if ( is_wp_error( $reg_errors ) ) {

            foreach ( $reg_errors->get_error_messages() as $error ) {

                echo '<div>';
                echo '<strong>ERROR</strong>:';
                echo $error . '<br/>';
                echo '</div>';
            }
        }
    }
    
        public function my_sanitize_image( $input ){

        /* default output */
        $output = '';

        /* check file type */
        $filetype = wp_check_filetype( $input );
        $mime_type = $filetype['type'];

        /* only mime type "image" allowed */
        if ( strpos( $mime_type, 'image' ) !== false ){
            $output = $input;
        }

        return $output;
    }
}

$od_form_validation = new OD_Form_Validation();