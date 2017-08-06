<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dashboard Profile Class
 */
class Dashboard_Profile {

	/**
	 * Ajax actions nonce.
	 *
	 * @var string
	 */
	private $ajax_nonce = 'od-dashboard-ajax';

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_dashboard_scripts' ) );
		add_action( 'wp_ajax_update_userdata', array( $this, 'update_userdata' ) );
		add_action( 'wp_ajax_nopriv_update_userdata', array( $this, 'update_userdata' ) );
		add_action( 'wp_ajax_approve_user', array( $this, 'approve_user' ) );
		add_action( 'wp_ajax_nopriv_approve_user', array( $this, 'approve_user' ) );
		add_action( 'wp_ajax_remove_user', array( $this, 'remove_user' ) );
		add_action( 'wp_ajax_nopriv_remove_user', array( $this, 'remove_user') );
		add_action( 'wp_ajax_remove_decmember', array( $this, 'prefix_ajax_remove_decmember' ) );
		add_action( 'wp_ajax_nopriv_remove_decmember', array( $this, 'prefix_ajax_remove_decmember' ) );
		add_action( 'wp_ajax_approve_pro', array( $this, 'prefix_ajax_approve_pro' ) );
		add_action( 'wp_ajax_nopriv_approve_pro', array( $this, 'prefix_ajax_appove_pro' ) );
		add_action( 'wp_ajax_remove_pro', array( $this, 'prefix_ajax_remove_pro' ) );
		add_action( 'wp_ajax_nopriv_remove_pro', array( $this, 'prefix_ajax_remove_pro' ) );
		add_action( 'wp_ajax_remove_biz', array( $this, 'prefix_ajax_remove_biz' ) );
		add_action( 'wp_ajax_nopriv_remove_biz', array( $this, 'prefix_ajax_remove_biz' ) );
		add_action( 'wp_ajax_add_pro_type', array( $this, 'prefix_ajax_add_pro_type' ) );
		add_action( 'wp_ajax_nopriv_add_pro_type', array( $this, 'prefix_ajax_add_pro_type' ) );
		add_action( 'wp_ajax_add_current_location', array( $this, 'prefix_ajax_add_current_location' ) );
		add_action( 'wp_ajax_nopriv_add_current_location', array( $this, 'prefix_ajax_add_current_location' ) );
		add_action( 'wp_ajax_remove_current_location', array( $this, 'prefix_ajax_remove_current_location' ) );
		add_action( 'wp_ajax_nopriv_remove_current_location', array( $this, 'prefix_ajax_remove_current_location' ) );
		add_action( 'wp_ajax_approve_biz_request', array( $this, 'prefix_ajax_approve_biz_request' ) );
		add_action( 'wp_ajax_nopriv_approve_biz_request', array( $this, 'prefix_ajax_approve_biz_request' ) );
		add_action( 'wp_ajax_remove_biz_request', array( $this, 'prefix_ajax_remove_biz_request' ) );
		add_action( 'wp_ajax_nopriv_remove_biz_request', array( $this, 'prefix_ajax_remove_biz_request' ) );
		add_action( 'wp_ajax_add_read_status', array( $this, 'prefix_ajax_add_read_status' ) );
		add_action( 'wp_ajax_nopriv_add_read_status', array( $this, 'prefix_ajax_add_read_status' ) );
		add_action( 'wp_ajax_remove_end', array( $this, 'prefix_ajax_remove_end' ) );
		add_action( 'wp_ajax_nopriv_remove_end', array( $this, 'prefix_ajax_remove_end' ) );
		add_action( 'wp_ajax_approve_endorsement', array( $this, 'prefix_ajax_approve_endorsement' ) );
		add_action( 'wp_ajax_nopriv_approve_endorsement', array( $this, 'prefix_ajax_approve_endorsement' ) );
		add_action( 'wp_ajax_approve_friend', array( $this, 'prefix_ajax_approve_friend' ) );
		add_action( 'wp_ajax_nopriv_approve_friend', array( $this, 'prefix_ajax_approve_friend' ) );
		add_action( 'wp_ajax_load_messages', array( $this, 'load_messages' ) );
		add_action( 'wp_ajax_nopriv_load_messages', array( $this, 'load_messages' ) );
		add_action( 'wp_ajax_get_message', array( $this, 'get_message' ) );
		add_action( 'wp_ajax_nopriv_get_message', array( $this, 'get_message' ) );
	}

	public function get_dashboard_usermeta( $userid ) {
		$user_array = array(
			'decstatus' => get_user_meta( $userid, 'decstatus', true ),
			'userstatus' => get_user_meta( $userid, 'userstatus', true ),
		);

		return (array) $user_array;
	}

	public function get_message() {
		global $current_user;

		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		if ( ! isset( $_POST['message_id'] ) || '' === $_POST['message_id'] ) {
			wp_send_json_error( 'get message failed' );
		}

		$message_id       = sanitize_text_field( wp_unslash( $_POST['message_id'] ) );
		$current_messages = '' !== get_user_meta( $current_user->ID, 'my_messages', true ) && is_array( get_user_meta( $current_user->ID, 'my_messages', true ) ) ? get_user_meta( $current_user->ID, 'my_messages', true ) : array();

		if ( array() !== $current_messages ) {
			foreach ( $current_messages as $pos => $message ) {
				if ( $message_id === $message['messageid'] ) {
					if ( 'unread' === $message['read_status'] ) {
						$current_messages[ $pos ]['read_status'] = 'read';

						// Update selected message's read status if unread.
						update_user_meta( $current_user->ID, 'my_messages', $current_messages );
					}

					$user_info = get_userdata( (int) $message['user'] );
					$message_info = array(
						'user' => $user_info->display_name,
						'message' => $message['message'],
					);

					wp_send_json_success( $message_info );
				}
			}
		}

		wp_send_json_error( 'no messages available' );
	}

	public function load_messages() {
		global $current_user, $profile_pages;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$current_messages = '' !== get_user_meta( $current_user->ID, 'my_messages', true ) ? get_user_meta( $current_user->ID, 'my_messages', true ) : '';
		$unread_count = '' !== $current_messages ? $this->get_message_count( $current_messages ) : array();

		include_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . '../templates/message.php' );

		wp_die();
	}

	private function get_message_count( $messages ) {
		foreach ( $messages as $message ) {
			if ( 'unread' === $message['read_status'] ) {
				$undread[] = $message;
			}
		}

		return (int) count( $undread );
	}

	/**
	 * AJAX call back function to get current list members for dashboard.
	 */
	public function get_list_members() {
		global $current_user;

		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		if ( ! isset( $_POST['type'] ) ) { // WPSC: input var ok.
			return;
		}

		$meta_key = sanitize_text_field( wp_unslash( $_POST['type'] ) ); // WPSC: input var ok.
		$meta_array = get_user_meta( $current_user->ID, $meta_key, true );

		if ( is_array( $meta_array ) && array() !== $meta_array ) {
			foreach ( $meta_array as $field => $value ) {
				if ( !is_string( $field ) ) {
					$user_data = get_userdata( (int) $value );

					include( trailingslashit( plugin_dir_path( __FILE__ ) ) . '../templates/list-user.php' );
				}
			}
		}

		wp_die();
	}

	public function register_dashboard_scripts() {
		wp_register_script( 'dashboard-profile', plugins_url( 'od-profiles/js/dashboard-profile.js'), array( 'jquery' ) );
		wp_register_style( 'dashboard-profile', plugins_url( 'od-profiles/css/dashboard-profile.css' ) );

		if( is_page_template( 'page-templates/dashboard-profile.php' ) ) {
			wp_enqueue_script( 'dashboard-profile' );
			wp_enqueue_style( 'dashboard-profile' );

			wp_localize_script( 'dashboard-profile', 'ODDash', array(
					'DashNonce' => wp_create_nonce( $this->ajax_nonce ),
				)
			);
		}
	}

	public function update_userdata() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$datatype = isset( $_POST['datatype'] ) ? sanitize_text_field( wp_unslash( $_POST['datatype'] ) ) : '';
		$data = isset( $_POST['data'] ) ? sanitize_text_field( wp_unslash( $_POST['data'] ) ) : '';
		$userid = isset( $_POST['userid'] ) ? intval( wp_unslash( $_POST['userid'] ) ) : $current_user->ID;

		if ( '' !== $datatype && '' !== $data ) {
			update_user_meta( $userid, $datatype, $data );
		}
	}

	public function approve_user() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$requestdecid = isset( $_POST['requestid'] ) ? intval( wp_unslash( $_POST['requestid'] ) ) : '';
		$approvemetakey = isset( $_POST['approvemeta'] ) ? sanitize_text_field( wp_unslash( $_POST['approvemeta'] ) ) : '';
		$requestmetakey = isset( $_POST['requestmeta'] ) ? sanitize_text_field( wp_unslash( $_POST['requestmeta'] ) ) : $approvemetakey;
		$request_meta = '' !== get_user_meta( $requestdecid, $requestmetakey, true ) && is_array( get_user_meta( $requestdecid, $requestmetakey, true ) )  ? get_user_meta( $requestdecid, $requestmetakey, true ) : '';
		$user_meta  = '' !== get_user_meta( $current_user->ID, $approvemetakey, true ) && is_array( get_user_meta( $current_user->ID, $approvemetakey, true ) ) ? get_user_meta( $current_user->ID, $approvemetakey, true ) : '';

		if ( '' !== $user_meta ) {
			foreach ( $user_meta as $user_item ) {
				if ( isset( $user_item['user'], $user_item['approval_status'] ) && (int) $requestdecid === (int) $user_item['user'] && $user_item['approval_status'] === 'pending' ) {
					$user_pos = array_search( $user_item, $user_meta, true );
					$user_meta[$user_pos]['approval_status'] = 'approved';
				}
			}

			if( '' !== $request_meta ){
				array_push( $request_meta, array( 'user' => $current_user->ID, 'approval_status' => 'approved' ) );
			} else {
				$request_meta[] = array( 'user' => $current_user->ID, 'approval_status' => 'approved' );
			}

			update_user_meta( $current_user->ID, $approvemetakey, $user_meta );
			update_user_meta( $requestdecid, $requestmetakey, $request_meta );
		}
	}

	public function remove_user() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$removeid = isset( $_POST['removeid'] ) ? intval( wp_unslash( $_POST['removeid'] ) ) : '';
		$removemetakey = isset( $_POST['removemeta'] ) ? sanitize_text_field( wp_unslash( $_POST['removemeta'] ) ) : '';
		$requestmetakey = isset( $_POST['requestmeta'] ) ? sanitize_text_field( wp_unslash( $_POST['requestmeta'] ) ) : '';
		$request_meta = '' !== get_user_meta( $requestdecid, $requestmetakey, true ) && is_array( get_user_meta( $requestdecid, $requestmetakey, true ) )  ? get_user_meta( $requestdecid, $requestmetakey, true ) : '';
		$user_meta  = '' !== get_user_meta( $current_user->ID, $removemetakey, true ) && is_array( get_user_meta( $current_user->ID, $removemetakey, true ) ) ? get_user_meta( $current_user->ID, $approvemetakey, true ) : '';

		if ( '' !== $user_meta ) {
			foreach ( $user_meta as $user_item ) {
				if ( isset( $user_item['user'] ) && (int) $requestdecid === (int) $user_item['user'] ) {
					$user_pos = array_search( $user_item, $user_meta, true );

					unset($user_meta[$user_pos]);
				}
			}

			update_user_meta( $current_user->ID, $removemetakey, $user_meta );

			if( '' !== $request_meta ){
				foreach ( $request_meta as $request_item ) {
					if ( isset( $request_item['user'] ) && (int) $current_user->ID === (int) $request_item['user'] ) {
						$request_pos = array_search( $request_item, $request_meta, true );

						unset($request_meta[$request_pos]);
					}
				}
				update_user_meta( $requestdecid, $requestmetakey, $request_meta );
			}

		}
	}

	public function prefix_ajax_approve_biz_request() {

		global $current_user;

		$requestdecid = isset( $_POST['approvebizrequest'] ) ? $_POST['approvebizrequest'] : "";

		$business_requests = get_user_meta( $current_user->ID, 'business_requests', false );
		$current_pros = get_user_meta( $requestdecid, 'mydec', false );
		$current_biz = get_user_meta( $current_user->ID, 'mybusinesses', false );

		$business_requests = array() !== $business_requests ? $business_requests : array( 0 => array() );
		$current_pros      = array() !== $current_pros ? $current_pros : array( 0 => array() );
		$current_biz       = array() !== $current_biz ? $current_biz : array( 0 => array() );

		$new_apbiz = array();

		if ( isset( $business_requests[0] ) ) {
			foreach ( $business_requests[0] as $bizs => $biz ) {

				if ( intval( $biz ) !== intval( $requestdecid ) ) {

					$new_apbiz[] = $biz;
				}
			}
		}

		$new_pros = array_merge( $current_pros[0], array( $current_user->ID ) );
		$new_bizs = array_merge( $current_biz[0], array( $requestdecid ) );

		update_user_meta( $current_user->ID, 'business_requests', $new_apbiz );
		update_user_meta( $requestdecid, 'mydec', $new_pros );
		update_user_meta( $current_user->ID, 'mybusinesses', $new_bizs );

		print_r( $new_array, true );
	}

	public function prefix_ajax_remove_biz_request() {

		global $current_user;

		$requestdecid = isset( $_POST['removebizrequest'] ) ? $_POST['removebizrequest'] : "";

		$business_requests = get_user_meta( $current_user->ID, 'business_requests', false );

		$business_requests = array() !== $business_requests ? $business_requests : array( 0 => array() );

		$new_rmbizs = array();

		if ( isset( $business_requests[0] ) ) {
			foreach ( $business_requests[0] as $bizs => $biz ) {

				if ( $biz !== $requestdecid ) {

					$new_rmbizs[] = $biz;
				}
			}
		}

		update_user_meta( $current_user->ID, 'business_requests', $new_rmbizs );

		print_r( $new_rmpros, true );
	}

	/**
	 *
	 */
	public function prefix_ajax_add_current_location() {

		global $current_user;

		$current_location = isset( $_POST['currentloc'] ) ? $_POST['currentloc'] : "";

		update_user_meta( $current_user->ID, 'current_location', $current_location );

		echo "location=" . $current_location;
	}

	public function prefix_ajax_remove_current_location() {

		global $current_user;

		update_user_meta( $current_user->ID, 'current_location', '' );

	}

	public function prefix_ajax_add_pro_type() {

		global $current_user;

		$pro_types = isset( $_POST['typeselected'] ) ? $_POST['typeselected'] : "";
		print_r( $pro_types );
		update_user_meta( $current_user->ID, 'protype', $pro_types );


	}

	public static function prefix_ajax_approve_friend( $type = "", $bizid = "" ) {

		global $current_user;

		$friendid = isset( $_POST['friendid'] ) ? $_POST['friendid'] : "";
		$bizid    = isset( $_POST['bizid'] ) ? $_POST['bizid'] : $bizid;
		$type     = isset( $_POST['type'] ) ? $_POST['type'] : $type;

		if ( $type !== 'biz' && $type !== 'pro' ) {
			$current_friends = get_user_meta( $current_user->ID, 'myfriends' );
			$request_friends = get_user_meta( $friendid, 'myfriends', false );
		} else {
			$current_friends = get_user_meta( $current_user->ID, 'mybusinesses' );
			$request_friends = get_user_meta( $bizid, 'mydec', false );
		}

		if ( is_array( $current_friends[0][0] ) ) {

			if ( $type !== 'biz' && $type !== 'pro' ) {
				foreach ( $current_friends[0] as $message_key => $message ) {
					foreach ( $message as $messages_key => $messages ) {
						if ( $message['user'] === intVal( $friendid ) ) {

							$current_friends[0][ $message_key ]['approval_status'] = 'approved';
						}
					}
				}
			} else {

				foreach ( $current_friends[0] as $message_key => $message ) {

					foreach ( $message as $messages_key => $messages ) {

						if ( $message['user'] === intVal( $bizid ) ) {

							$current_friends[0][ $message_key ]['approval_status'] = 'approved';
						}
					}
				}
			}
		} else {

			if ( $type !== 'biz' && $type !== 'pro' ) {

				if ( $current_friends[0]['user'] === intVal( $friendid ) ) {

					$current_friends[0]['approval_status'] = 'approved';
				}
			} else {

				if ( $current_friends[0]['user'] === intVal( $bizid ) ) {

					$current_friends[0]['approval_status'] = 'approved';
				}
			}
		}

		if ( $type !== 'biz' && $type !== 'pro' ) {
			update_user_meta( $current_user->ID, 'myfriends', $current_friends[0] );

			$approved_friends = get_user_meta( $friendid, 'myfriends', false );

			$approver_friends = array() !== $approved_friends ? $approved_friends : array( 0 => array() );

			if ( ! is_array( $approver_friends[0] ) ) {

				$new_friends = array_merge( $approver_friends[0], array(
					'user'            => $current_user->ID,
					'approval_status' => 'approved'
				) );

				update_user_meta( $friendid, 'myfriends', array( $new_friends ) );
			} else {
				$new_friends = array_merge( $approver_friends[0], array(
					array(
						'user'            => $current_user->ID,
						'approval_status' => 'approved'
					)
				) );

				update_user_meta( $friendid, 'myfriends', $new_friends );
			}
		} else {

			update_user_meta( $current_user->ID, 'mybusinesses', $current_friends[0] );

			$approved_friends  = get_user_meta( $bizid, 'mydec', false );
			$approved_temp_biz = get_user_meta( $current_user->ID, 'mybusinesses', true );

			$approver_temp_biz = array() !== $approved_temp_biz ? $approved_temp_biz : array( 0 => array() );
			$approver_friends  = array() !== $approved_friends ? $approved_friends : array( 0 => array() );

			if ( ! is_array( $approver_friends[0] ) ) {

				$new_friends = array_merge( $approver_friends[0], array(
					'user'            => $current_user->ID,
					'approval_status' => 'approved'
				) );

				update_user_meta( $bizid, 'mydec', array( $new_friends ) );
			} else {
				$new_friends = array_merge( $approver_friends[0], array(
					array(
						'user'            => $current_user->ID,
						'approval_status' => 'approved'
					)
				) );

				update_user_meta( $bizid, 'mydec', $new_friends );
			}

			//Temp busines additions
			if ( ! is_array( $approver_temp_biz ) ) {

				$new_temp_biz = array_merge( $approver_temp_biz, array(
					'user'            => $bizid,
					'approval_status' => 'approved'
				) );

				update_user_meta( $current_user->ID, 'mybusinesses', array( $new_temp_biz ) );
			} else {
				$new_temp_biz = array_merge( $approver_temp_biz, array(
					array(
						'user'            => $bizid,
						'approval_status' => 'approved'
					)
				) );

				update_user_meta( $current_user->ID, 'mybusinesses', $new_temp_biz );
			}
		}
	}

	public function prefix_ajax_approve_endorsement() {

		global $current_user;

		$endorseid = isset( $_POST['endorseid'] ) ? $_POST['endorseid'] : "";

		$current_endorsements = get_user_meta( $current_user->ID, 'my_endorsements' );

		if ( is_array( $current_endorsements[0][0] ) ) {
			foreach ( $current_endorsements[0] as $message_key => $message ) {
				foreach ( $message as $messages_key => $messages ) {
					if ( $message['endorseid'] === $messageid ) {

						$current_endorsements[0][ $message_key ]['approval_status'] = 'approved';
					}
				}
			}
		} else {


			if ( $current_endorsements[0]['endorseid'] === $messageid ) {

				$current_endorsements[0]['approval_status'] = 'approved';


			}
		}

		update_user_meta( $current_user->ID, 'my_endorsements', $current_endorsements[0] );

	}

	public function prefix_ajax_add_userendorse() {

		global $current_user;

		date_default_timezone_set( 'America/Los_Angeles' );

		$usermessage = isset( $_POST['userendorse'] ) ? $_POST['userendorse'] : "";
		$msgid       = isset( $_POST['endorseusrid'] ) ? $_POST['endorseusrid'] : "";
		$messageid   = isset( $_POST['endorseid'] ) ? $_POST['endorseid'] : "";
		$c_date      = time();

		$usermessage_id = array(
			'endorsementid'    => $messageid,
			'endorsement_date' => $c_date,
			'user'             => $current_user->ID,
			'endorsement'      => $usermessage,
			'approval_status'  => 'pending'
		);

		$current_message_array = get_user_meta( $msgid, 'my_endorsements', false );

		$current_messages = isset( $current_message_array ) ? $current_message_array : "";

		if ( null === $current_messages[0] ) {

			update_user_meta( $msgid, 'my_endorsements', $usermessage_id );

		} elseif ( 1 === count( $current_messages ) && 5 === count( $current_messages[0] ) && null === $current_messages[0][0] ) {

			$new_message_array = array_merge( $current_messages, array( $usermessage_id ) );

			update_user_meta( $msgid, 'my_endorsements', $new_message_array );
		} elseif ( 2 <= count( $current_messages[0] ) ) {

			$new_message_array = array_merge( $current_messages[0], array( $usermessage_id ) );

			update_user_meta( $msgid, 'my_endorsements', $new_message_array );
		}


		echo "success!";
	}

	public function prefix_ajax_remove_end() {

		global $current_user;

		$rmendid = isset( $_POST['rmendid'] ) ? $_POST['rmendid'] : "";

		$current_message_array = get_user_meta( $current_user->ID, 'my_endorsements', false );

		$current_messages = isset( $current_message_array ) ? $current_message_array : "";

		if ( null === $current_messages[0][0] ) {

			delete_user_meta( $current_user->ID, 'my_endorsements' );

		} elseif ( 2 === count( $current_messages[0] ) ) {

			foreach ( $current_messages[0] as $endorsement ) {

				if ( $endorsement['endorsementid'] !== $rmendid ) {

					$new_message_array[] = $endorsement;
				}
			}

			update_user_meta( $current_user->ID, 'my_endorsements', $new_message_array[0] );
		} elseif ( 2 < count( $current_messages[0] ) ) {

			foreach ( $current_messages[0] as $endorsement ) {

				if ( $endorsement['endorsementid'] !== $rmendid ) {

					$new_message_array[] = $endorsement;
				}
			}

			update_user_meta( $current_user->ID, 'my_endorsements', $new_message_array );
		}

	}

	public function prefix_ajax_add_read_status() {

		global $current_user;

		$messageid = isset( $_POST['message_id'] ) ? $_POST['message_id'] : "";

		$current_messages = get_user_meta( $current_user->ID, 'my_messages', false );

		if ( is_array( $current_messages[0][0] ) ) {
			foreach ( $current_messages[0] as $message_key => $message ) {
				foreach ( $message as $messages_key => $messages ) {
					if ( $message['messageid'] === $messageid ) {

						$current_messages[0][ $message_key ]['read_status'] = 'read';
					}
				}
			}
		} else {


			if ( $current_messages[0]['messageid'] === $messageid ) {

				$current_messages[0]['read_status'] = 'read';


			}
		}

		update_user_meta( $current_user->ID, 'my_messages', $current_messages[0] );
	}

	public function prefix_ajax_remove_decmember() {

		global $current_user;

		$user_role = $current_user->roles[0];

		$rmdecid = isset( $_POST['rmdecid'] ) ? $_POST['rmdecid'] : "";
		$rmtype  = isset( $_POST['rmtype'] ) ? $_POST['rmtype'] : "";

		$current_dec_members = get_user_meta( $current_user->ID, 'mydec', false );
		$current_biz         = get_user_meta( $rmdecid, 'mybusinesses', false );
		$client_pro          = get_user_meta( $rmdecid, 'mydec', false );
		$client_likes        = get_user_meta( $current_user->ID, 'mylikes', false );
		$client_friends      = get_user_meta( $current_user->ID, 'myfriends', false );
		$client_frienders    = get_user_meta( $rmdecid, 'myfriends', false );
		$biz_likers          = get_user_meta( $rmdecid, 'mylikers', false );
		$professional_biz    = get_user_meta( $current_user->ID, 'mybusinesses', false );

		$new_array = array();

		if ( $user_role === 'business' ) {
			$new_biz = array();

			if ( isset( $current_biz[0] ) ) {
				foreach ( $current_biz[0] as $c_bizs => $c_biz ) {

					if ( intval( $c_biz ) !== intval( $current_user->ID ) ) {

						$new_biz[] = $c_biz;
					}
				}

				update_user_meta( $rmdecid, 'mybusinesses', $new_biz );
			}
		}

		if ( $user_role === 'client' && $rmtype === "follow" ) {

			$new_follower = array();
			$new_followey = array();

			if ( isset( $client_pro[0] ) && $current_dec_members[0] ) {
				foreach ( $client_follower[0] as $c_followey ) {

					if ( intval( $c_follower ) !== intval( $current_user->ID ) ) {

						$new_followey[] = $c_followey;
					}
				}

				update_user_meta( $rmdecid, 'mydec', $new_followey );

				foreach ( $current_dec_members[0] as $c_follower ) {

					if ( intval( $c_follower ) !== intval( $rmdecid ) ) {

						$new_follower[] = $c_follower;
					}
				}

				update_user_meta( $current_user->ID, 'mydec', $new_follower );
			}
		} elseif ( $user_role === 'client' && $rmtype === "like" ) {

			$new_like = array();

			if ( isset( $client_likes[0] ) ) {
				foreach ( $client_likes[0] as $c_likes => $c_like ) {

					if ( intval( $c_like ) !== intval( $rmdecid ) ) {

						$new_like[] = $c_like;
					}
				}

				update_user_meta( $current_user->ID, 'mylikes', $new_like );
			}


			$new_liker = array();

			if ( isset( $biz_likers[0] ) ) {
				foreach ( $biz_likers[0] as $b_likers => $b_liker ) {

					if ( intval( $b_liker ) !== intval( $current_user->ID ) ) {

						$new_liker[] = $b_liker;
					}
				}

				update_user_meta( $rmdecid, 'mylikers', $new_liker );
			}
		} elseif ( $user_role === 'client' && $rmtype === "friend" ) {

			$new_friend = array();

			if ( isset( $client_friends[0] ) ) {
				foreach ( $client_friends[0] as $c_friends ) {

					if ( intval( $c_friends['user'] ) !== intval( $rmdecid ) ) {

						$new_friend[] = $c_friends;
					}
				}

				update_user_meta( $current_user->ID, 'myfriends', $new_friend );
			}


			$new_friender = array();

			if ( isset( $client_frienders[0] ) ) {
				foreach ( $client_frienders[0] as $client_friender ) {

					if ( intval( $client_friender['user'] ) !== intval( $current_user->ID ) ) {

						$new_friender[] = $client_friender;
					}
				}

				update_user_meta( $rmdecid, 'myfriends', $new_friender );
			}
		}

		if ( $user_role === 'professional' && $rmtype === "biz" ) {

			$new_biz = array();

			if ( isset( $professional_biz[0] ) ) {
				foreach ( $professional_biz[0] as $c_biz ) {

					if ( intval( $c_biz['user'] ) !== intval( $rmdecid ) ) {

						$new_biz[] = $c_biz;
					}
				}

				update_user_meta( $current_user->ID, 'mybusinesses', $new_biz );
			}


			$new_pro = array();

			if ( isset( $client_pro[0] ) ) {
				foreach ( $client_pro[0] as $biz_pro ) {

					if ( intval( $biz_pro['user'] ) !== intval( $current_user->ID ) ) {

						$new_pro[] = $biz_pro;
					}
				}

				update_user_meta( $rmdecid, 'mydec', $new_pro );
			}
		}
		//print_r($rmdecid);
	}

	public function prefix_ajax_remove_biz() {

		global $current_user;

		$rmbizid            = isset( $_POST['rmbizid'] ) ? $_POST['rmbizid'] : "";
		$current_businesses = get_user_meta( $current_user->ID, 'mybusinesses', false );
		$current_pros       = get_user_meta( $rmbizid, 'mydec', false );

		$new_array = array();

		$new_pro_biz = array();

		if ( isset( $current_businesses[0] ) ) {
			foreach ( $current_businesses[0] as $c_bizs => $c_biz ) {

				if ( intval( $c_biz ) !== intval( $rmdecid ) ) {

					$new_pro_biz[] = $c_biz;
				}
			}

			update_user_meta( $current_user->ID, 'mybusinesses', $new_pro_biz );
		}
		var_dump( $new_pro_biz );
		$new_biz_pro = array();

		if ( isset( $current_pros[0] ) ) {
			foreach ( $current_pros[0] as $c_bizs => $c_biz ) {

				if ( intval( $c_biz ) !== intval( $current_user->ID ) ) {

					$new_biz_pro[] = $c_biz;
				}
			}

			update_user_meta( $current_user->ID, 'mybusinesses', $new_biz_pro );
		}
	}
}

$dash_profile = new Dashboard_Profile();