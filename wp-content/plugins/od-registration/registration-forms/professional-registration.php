<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'Professional Registration' ) )
	return;

/**
 * Professional Registration
 */
class Professional_Registration {
    
    public function __construct(){
        
        add_shortcode( 'professional-registration-form', array( $this, 'professional_registration_function' ) );
    }
    
    private static function professional_registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
    
        echo '
        <style>
        div {
            margin-bottom:2px;
        }

        input{
            margin-bottom:4px;
        }
        </style>
        ';

        echo '
        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <div>
        <label for="username">Username <strong>*</strong></label>
        <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
        </div>

        <div>
        <label for="password">Password <strong>*</strong></label>
        <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
        </div>

        <div>
        <label for="email">Email <strong>*</strong></label>
        <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
        </div>

        <div>
        <label for="website">Website</label>
        <input type="text" name="website" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
        </div>

        <div>
        <label for="firstname">First Name</label>
        <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
        </div>

        <div>
        <label for="website">Last Name</label>
        <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
        </div>

        <div>
        <label for="nickname">Nickname</label>
        <input type="text" name="nickname" value="' . ( isset( $_POST['nickname']) ? $nickname : null ) . '">
        </div>

        <div>
        <label for="bio">About / Bio</label>
        <textarea name="bio">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea>
        </div>
        <input type="submit" name="submit" value="Register"/>
        </form>
        ';
    }
    
    public function professional_registration_function() {
        
        $baseplugin = str_replace('registration-forms', 'includes', __DIR__);
        
        include_once($baseplugin . "/form-validation.php");
        include_once($baseplugin . "/complete-registration.php");
        
        $role = $GLOBALS['wp_roles']->is_role( 'professional' );
        
        if($role){ $role = 'professional'; } else { $role = ""; }
            
        
        
        if ( isset($_POST['submit'] ) ) {
            
            OD_Form_Validation::registration_validation(
                $_POST['username'],
                $_POST['password'],
                $_POST['email'],
                $_POST['website'],
                $_POST['fname'],
                $_POST['lname'],
                $_POST['nickname'],
                $_POST['bio']
            );

            // sanitize user form input
            global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
            $username   =   sanitize_user( $_POST['username'] );
            $password   =   esc_attr( $_POST['password'] );
            $email      =   sanitize_email( $_POST['email'] );
            $website    =   esc_url( $_POST['website'] );
            $first_name =   sanitize_text_field( $_POST['fname'] );
            $last_name  =   sanitize_text_field( $_POST['lname'] );
            $nickname   =   sanitize_text_field( $_POST['nickname'] );
            $bio        =   esc_textarea( $_POST['bio'] );
            $role       =   isset($role) ? $role : "";

            // call @function complete_registration to create the user
            // only when no WP_error is found
            OD_Complete_Registration::complete_registration(
                $username,
                $password,
                $email,
                $website,
                $first_name,
                $last_name,
                $nickname,
                $bio,
                $role
            );
        }

        $this::professional_registration_form(
            $username,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio
        );
    } 
}

$professional_registration = new Professional_Registration();