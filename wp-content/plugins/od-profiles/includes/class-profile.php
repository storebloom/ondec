<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Profile_Pages
 */
class Profile_Pages {
    
    public function __construct(){
        add_action('init', array($this, 'custom_rewrite_tag'), 10, 0);
        add_action('init', array($this, 'custom_rewrite_rule'), 10, 0);
        add_action('init', array($this, 'homepage_swap'), 10, 0);
		add_action( 'wp_ajax_add_decmember', array( $this, 'prefix_ajax_add_decmember' ) );
		add_action( 'wp_ajax_nopriv_add_decmember', array( $this, 'prefix_ajax_add_decmember' ) );
		add_action( 'wp_ajax_request_decmember', array( $this, 'prefix_ajax_request_decmember' ) );
		add_action( 'wp_ajax_nopriv_request_decmember', array( $this, 'prefix_ajax_request_decmember' ) );
		add_action( 'wp_ajax_add_usermessage', array( $this, 'prefix_ajax_add_usermessage' ) );
		add_action( 'wp_ajax_nopriv_add_usermessage', array( $this, 'prefix_ajax_add_usermessage' ) );
		add_action( 'wp_ajax_like_decmember', array( $this, 'prefix_ajax_like_decmember' ) );
		add_action( 'wp_ajax_nopriv_like_decmember', array( $this, 'prefix_ajax_like_decmember' ) );
		add_action( 'wp_ajax_add_userendorse', array( $this, 'prefix_ajax_add_userendorse' ) );
		add_action( 'wp_ajax_nopriv_add_userendorse', array( $this, 'prefix_ajax_add_userendorse' ) );
    }


	public function prefix_ajax_add_decmember() {

		global $current_user;

		$user_role = $current_user->roles[0];

		$adddecid = isset( $_POST['adddecid'] ) ? $_POST['adddecid'] : "";

		$current_dec_members = get_user_meta( $current_user->ID, 'mydec', false );

		$current_followers = get_user_meta( $adddecid, 'mydec', false );

		$current_dec_members = array() !== $current_dec_members ? $current_dec_members : array( 0 => array() );

		$current_followers = array() !== $current_followers ? $current_followers : array( 0 => array() );

		$new_array = array_merge( $current_dec_members[0], array( $adddecid ) );

		$new_followers = array_merge( $current_followers[0], array( $current_user->ID ) );

		update_user_meta( $current_user->ID, 'mydec', $new_array );

		if ( $user_role === 'client' ) {

			update_user_meta( $adddecid, 'mydec', $new_followers );
		}

		print_r( $new_array, true );
	}

	public function prefix_ajax_add_usermessage() {

		global $current_user;

		date_default_timezone_set( 'America/Los_Angeles' );

		$usermessage = isset( $_POST['usermessage'] ) ? $_POST['usermessage'] : "";
		$msgid       = isset( $_POST['msgid'] ) ? $_POST['msgid'] : "";
		$messageid   = isset( $_POST['messageid'] ) ? $_POST['messageid'] : "";
		$type        = isset( $_POST['type'] ) ? $_POST['type'] : "";
		$c_date      = time();

		if ( $usermessage === "" && $type === 'deny-appointment' ) {

			$usermessage = 'We\'re sorry, but you\'re appointment request has been denied.';
		} elseif ( $usermessage === "" && $type === 'approve-appointment' ) {

			$usermessage = 'You\'re appointment has been approved.  See you soon!';
		}

		$usermessage_id = array(
			'messageid'    => $messageid,
			'message_date' => $c_date,
			'user'         => $current_user->ID,
			'message'      => $usermessage,
			'read_status'  => 'unread'
		);

		$current_message_array = get_user_meta( $msgid, 'my_messages', false );

		$current_messages = isset( $current_message_array ) ? $current_message_array : "";

		if ( null === $current_messages[0] ) {

			update_user_meta( $msgid, 'my_messages', $usermessage_id );

		} elseif ( 1 === count( $current_messages ) && 5 === count( $current_messages[0] ) && null === $current_messages[0][0] ) {

			$new_message_array = array_merge( $current_messages, array( $usermessage_id ) );

			update_user_meta( $msgid, 'my_messages', $new_message_array );
		} elseif ( 2 <= count( $current_messages[0] ) ) {

			$new_message_array = array_merge( $current_messages[0], array( $usermessage_id ) );

			update_user_meta( $msgid, 'my_messages', $new_message_array );
		}

		echo "success!";
	}

	public function prefix_ajax_like_decmember() {

		global $current_user;

		$user_role = $current_user->roles[0];

		$likedecid = isset( $_POST['likedecid'] ) ? $_POST['likedecid'] : "";

		$current_dec_members = get_user_meta( $current_user->ID, 'mylikes', false );

		$current_followers = get_user_meta( $likedecid, 'mylikers', false );

		$current_dec_members = array() !== $current_dec_members ? $current_dec_members : array( 0 => array() );

		$current_followers = array() !== $current_followers ? $current_followers : array( 0 => array() );

		$new_array = array_merge( $current_dec_members[0], array( $likedecid ) );

		$new_followers = array_merge( $current_followers[0], array( $current_user->ID ) );

		update_user_meta( $current_user->ID, 'mylikes', $new_array );

		if ( $user_role === 'client' ) {

			update_user_meta( $likedecid, 'mylikers', $new_followers );
		}

		print_r( $new_array, true );
	}

	public function prefix_ajax_request_decmember() {

		global $current_user;

		$user_role = $current_user->roles[0];

		$requestdecid = isset( $_POST['requestdecid'] ) ? $_POST['requestdecid'] : "";

		if ( $user_role === 'professional' ) {

			$business_pros = array() !== $business_pros ? $business_pros : array( 0 => array() );

			$new_pros = array_merge( $business_pros[0], array( $current_user->ID ) );

			update_user_meta( $requestdecid, 'pro_requests', $new_pros );

		} elseif ( $user_role === 'business' ) {

			$business_prots = get_user_meta( $requestdecid, 'mybusinesses', false );

			$business_pros = array() !== $business_prots ? $business_prots : array( 0 => array() );

			if ( ! is_array( $business_pros[0][0] ) ) {

				$new_biz = array_merge( $business_pros[0], array(
					array(
						'user'            => $current_user->ID,
						'approval_status' => 'pending'
					)
				) );

				update_user_meta( $requestdecid, 'mybusinesses', array( $new_biz ) );
			} else {
				$new_biz = array_merge( $business_pros[0], array(
					array(
						'user'            => $current_user->ID,
						'approval_status' => 'pending'
					)
				) );

				update_user_meta( $requestdecid, 'mybusinesses', $new_biz );
			}

			update_user_meta( $requestdecid, 'mybusinesses', $new_biz );
		} elseif ( $user_role === 'client' ) {

			$current_friends = get_user_meta( $requestdecid, 'myfriends', false );

			$my_friends = array() !== $current_friends ? $current_friends : array( 0 => array() );

			if ( ! is_array( $my_friends[0][0] ) ) {

				$new_friends = array_merge( $my_friends[0], array(
					'user'            => $current_user->ID,
					'approval_status' => 'pending'
				) );

				update_user_meta( $requestdecid, 'myfriends', array( $new_friends ) );
			} else {
				$new_friends = array_merge( $my_friends[0], array(
					array(
						'user'            => $current_user->ID,
						'approval_status' => 'pending'
					)
				) );

				update_user_meta( $requestdecid, 'myfriends', $new_friends );
			}
		}
		print_r( $new_array, true );
	}
    
    public function homepage_swap(){
        
        if ( is_user_logged_in() ) {
            $page = get_page_by_title( 'My Profile' );
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
        } else {
            
            $page = get_page_by_title( 'Home' );
            update_option( 'page_on_front', $page->ID );
            update_option( 'show_on_front', 'page' );
            
        }
    }
    
    public function custom_rewrite_tag() {
    
        add_rewrite_tag('%professional%', '([^&]+)');
        add_rewrite_tag('%business%', '([^&]+)');
        add_rewrite_tag('%client%', '([^&]+)');
    }

    public function custom_rewrite_rule() {    
        
        add_rewrite_rule('^professional/([^/]*)/?','index.php?page_id=22&professional=$matches[1]','top');
        add_rewrite_rule('^business/([^/]*)/?','index.php?page_id=27&business=$matches[1]','top');
        add_rewrite_rule('^client/([^/]*)/?','index.php?page_id=42&client=$matches[1]','top');

    }
    
    
 
    public function new_mail_from($old) {
     return 'notification@ondec.info';
    }
    
    public function new_mail_from_name($old) {
     return 'ondec user';
    }
    
    public function get_pro_type_readable($pro_id){
        
        $professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");
        
        $pro_types_str = str_replace("pro-types=", "", get_user_meta($pro_id, 'protype', true));

        foreach(explode("&", $pro_types_str ) as $single_type){

            $professional_new_types[]=$professional_types[$single_type];

        }

        return $professional_new_types;
    }
    
    public function is_current_location($id){
        global $current_user;
        
        $current_location = intval(get_user_meta($current_user->ID, 'current_location', true));

        if($id === $current_location){
            return true;
        } else {
            return false;
        }
        
    }
    
    public function is_not_on_list($id, $type = 'mydec'){
        
        global $current_user;
        
        $current_dec = get_user_meta($current_user->ID, $type, true);
        $request_dec = get_user_meta($id, $type, true);

        if(isset($current_dec) && $current_dec !== "" && $current_dec !== false || "" !== $request_dec){

            if($type === "myfriends"){

                if(isset($request_dec[0][0]) && null !== $request_dec[0][0]){
                  
                    foreach($request_dec[0] as $dec_members){

                        if(isset($dec_members['user']) && intval($dec_members['user']) === intval($current_user->ID)){

                            return false;
                        }
                    }
                } elseif(isset($request_dec[0]) && intval($request_dec[0]['user']) === intval($current_user->ID)){

                        return false;
                }
            
            } 
            
            if($type === 'mybusinesses'){

                    foreach($request_dec as $checker){

                        if($current_user->ID === $checker['user']){

                            return false;
                        }
                    }
                }
            }
        
        
        
        return true;
    }
    
    public function get_user_profile_url( $id ){
        $user_data = get_userdata( $id );
        $display_name = $user_data->display_name;
        $user_role = $user_data->roles[0];
        $user_url = "";

        if($user_role === 'professional' || $user_role === 'administrator'){
            $user_url = "/professionals/" . $display_name;
        }elseif($user_role === 'business'){
            $user_url = "/businesses/" . $display_name;
        }elseif($user_role === 'client'){
            $user_url = "/clients/" . $display_name;
        }
        
        return $user_url;
    }
}

global $profile_pages;

$profile_pages = new Profile_Pages();
