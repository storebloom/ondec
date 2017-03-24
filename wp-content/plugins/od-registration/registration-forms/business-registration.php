<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Business_Registration
 */
class Business_Registration {
    
    public function __construct(){
        
        add_shortcode( 'business-registration-form', array( $this, 'business_registration_function' ) );
		add_action( 'wp_ajax_register_temp_business',          array($this, 'register_temp_business') );
        add_action( 'wp_ajax_nopriv_register_temp_business',   array($this, 'register_temp_business') );
    }
	
	public function register_temp_business(){
		
		$name = isset($_POST['business_name']) ? $_POST['business_name'] : "";
		$username = str_replace(" ","", $name);
		$address = isset($_POST['business_address']) ? $_POST['business_address'] : "";
		
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 30; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        $random = implode($pass);
		$email = $username.'_'.$random.'@tempbusiness.com';
		$userid = register_new_user($username, $email);
		update_user_meta($userid, 'address', $address);
		update_user_meta($userid, 'od_capabilities', array('business'));
		wp_update_user( array( 'ID' => $userid, 'display_name' => $name ) );
		
		//Add temp business to current user's list
		Decstatus::prefix_ajax_approve_friend('biz', $userid);
		
		echo $userid;
	}
    
    private static function business_registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $address, $business_type ) {
    
        echo '
        <form class="registration-form" action="' . $_SERVER['REQUEST_URI'] . '" method="post">
        <h1>Business Signup</h1>
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
            <label for="business_type">Select your business type</label>

            <select name="business_type" id="business_type">
            ';
                $business_type = array();
                $business_type['tattoo']  = 'Tattoo Shop';
                $business_type['barber']  = 'Barber / Hair Cutter';
                $business_type['weed'] = 'Dispensary';
                $business_type['salon'] = 'Beauty Salon';
                $business_type['bar'] = 'Bar / Restaurant';
                $business_type['contruction'] = 'Construction / Landscaping';
                $business_type['grooming'] = 'Pet Shop/Groomers';

                foreach ( $business_type as $id => $item ) {
            
            echo '        
                <option '. selected( $business_type, $item ) . '>' . $item .'</option>
            ';
                }
            echo '
            </select>
        </div>
        <div>        
        <label for="address">Address (leave blank to remain off the map)</label>
        <input type="text" name="address" value="' . ( isset( $_POST['address']) ? $address : null ) . '">
        </div>
        <div>        
        <label for="website">Website</label>
        <input type="text" name="website" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
        </div>

        <div>
        <label for="firstname">Contact First Name</label>
        <input type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
        </div>

        <div>
        <label for="website">Contact Last Name</label>
        <input type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
        </div>

        <div>
        <label for="nickname">Business Name</label>
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
    
    public function business_registration_function() {
        
            $username      = "";
            $password      = "";
            $email         = "";
            $website       = "";
            $first_name    = "";
            $last_name     = "";
            $nickname      = "";
            $bio           = "";
            $role          = "";
            $address       = "";
            $business_type = "";
        
        if(is_user_logged_in ()){ 
            echo '<h2>You are already logged in.  To create a new account log out first and revist this form.</h2>';
            exit;
        }
        
        $baseplugin = str_replace('registration-forms', 'includes', __DIR__);
        
        include_once($baseplugin . "/form-validation.php");
        include_once($baseplugin . "/complete-registration.php");
        
        $role = $GLOBALS['wp_roles']->is_role( 'business' );
        
        if($role){ $role = 'business'; } else { $role = ""; }
    
        if ( isset($_POST['submit'] ) ) {
            
            $od_form_validation->registration_validation(
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
            global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio, $address, $business_type;
            $username   =   sanitize_user( $_POST['username'] );
            $password   =   esc_attr( $_POST['password'] );
            $email      =   sanitize_email( $_POST['email'] );
            $website    =   esc_url( $_POST['website'] );
            $first_name =   sanitize_text_field( $_POST['fname'] );
            $last_name  =   sanitize_text_field( $_POST['lname'] );
            $nickname   =   sanitize_text_field( $_POST['nickname'] );
            $bio        =   esc_textarea( $_POST['bio'] );
            $role       =   isset($role) ? $role : "";
            $address    =   sanitize_text_field( $_POST['address'] );
            $business_type = isset($_POST['business_type']) ? $_POST['business_type'] : "";

            // call @function complete_registration to create the user
            // only when no WP_error is found
            $od_complete_registration->complete_registration(
                $username,
                $password,
                $email,
                $website,
                $first_name,
                $last_name,
                $nickname,
                $bio,
                $role,
                $address,
                $business_type
            );
        }

        $this::business_registration_form(
            $username,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio, 
            $address,
            $business_type
        );
    } 
}

$business_registration = new Business_Registration();