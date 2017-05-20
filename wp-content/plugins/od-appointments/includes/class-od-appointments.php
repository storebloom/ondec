<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OD Appointments Class
 */
class OD_Appointments {

	/**
	 * Ajax actions nonce.
	 *
	 * @var string
	 */
	private $ajax_nonce = 'od-appointment-ajax';

	public function __construct() {

		add_shortcode( 'od-app-settings', array( $this, 'od_app_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_appointment_scripts') );
		add_action( 'wp_ajax_add_app', array( $this, 'add_app' ) );
		add_action( 'wp_ajax_nopriv_add_app', array( $this, 'add_app' ) );
		add_action( 'wp_ajax_define_calendar', array( $this, 'define_calendar' ) );
		add_action( 'wp_ajax_nopriv_define_calendar', array( $this, 'define_calendar' ) );
		add_action( 'wp_ajax_approve_cancel_app', array( $this, 'approve_cancel_app' ) );
		add_action( 'wp_ajax_nopriv_approve_cancel_app', array( $this, 'approve_cancel_app' ) );
		add_action( 'wp_ajax_define_profile_calendar', array( $this, 'define_profile_calendar' ) );
		add_action( 'wp_ajax_nopriv_define_profile_calendar', array( $this, 'define_profile_calendar' ) );
		add_action( 'wp_ajax_appointment_settings', array( $this, 'appointment_settings' ) );
		add_action( 'wp_ajax_nopriv_appointment_settings', array( $this, 'appointment_settings' ) );
	}

	public function register_appointment_scripts() {

		wp_enqueue_script( 'app-main', '/wp-content/plugins/od-appointments/js/app-main.js', array( 'jquery' ) );
		wp_localize_script( 'app-main', 'ODApp', array(
				'AppNonce' => wp_create_nonce( $this->ajax_nonce ),
			)
		);
	}

	/**
	 * Creates the professional/business appointment settings section in their dashboard
	 *
	 * @access public
	 * @return string
	 */
	public function od_app_settings() {

		global $current_user;

		$current_app_settings = null !== get_user_meta( $current_user->ID, 'app_settings', true ) ? get_user_meta( $current_user->ID, 'app_settings', true ) : '';
		$enabled              = isset( $current_app_settings['enabled'] ) ? esc_html( $current_app_settings['enabled'] ) : false;
		$count                = isset( $current_app_settings['count'] ) ? ( int ) $current_app_settings['count'] : '';
		$duration             = isset( $current_app_settings['duration'] ) ? ( int ) $current_app_settings['duration'] : 0;
		$start                = isset( $current_app_settings['start'] ) ? (int) $current_app_settings['start'] : '';

		if ( $enabled ) {

			$enabled = 'checked="checked"';
		} else {

			$enabled = '';
		}

		$html = '<div style="display:none;" class="setting-success">Update successful.</div><div class="app-setting-wrapper">';
		$html .= '<input type="checkbox" id="enable-appointments" ' . esc_html( $enabled ) . '><label for="enable-appointments">Check to enable customer appointments on your profile.</label>';
		$html .= '<input id="app-settings-count" value="' . (int) $count . '" class="settings-input" type="number"><label for="app-settings-count">Insert the amount of appointments you would like to display per day.</label>';
		$html .= '<input id="app-settings-duration" value="' . (int) $duration . '" class="settings-input" type="number" max="24"><label for="app-settings-duration">Insert the length of each appointment. Max 24 Ex:  2 = 2hours, 1 = 1hour, .5 = 30min</label>';
		$html .= $this->get_time_array( $start );
		$html .= '<label for="app-settings-start">Insert the time your first appointment will begin.  Ex: 9 = 9AM, 17 = 9PM</label>';
		$html .= '<button id="submit-appointment-settings">Submit</button>';
		$html .= '</div>';

		return $html;
	}

	/**
	 *
	 * Return an HTML string of options with the selected time option selected.
	 *
	 * @param string $start The start time defined in the user's appointment settings.
	 *
	 * @access private
	 * @return string
	 */
	private function get_time_array( $start ) {

		$time_array = array(
			1  => '1:00AM',
			2  => '2:00AM',
			3  => '3:00AM',
			4  => '4:00AM',
			5  => '5:00AM',
			6  => '6:00AM',
			7  => '7:00AM',
			8  => '8:00AM',
			9  => '9:00AM',
			10 => '10:00AM',
			11 => '11:00AM',
			12 => '12:00PM',
			13 => '1:00PM',
			14 => '2:00PM',
			15 => '3:00PM',
			16 => '4:00PM',
			17 => '5:00PM',
			18 => '6:00PM',
			19 => '7:00PM',
			20 => '8:00PM',
			21 => '9:00PM',
			22 => '10:00PM',
			23 => '11:00PM',
			24 => '12:00AM',
		);

		$html = '<select id="app-settings-start" class="settings-input">';

		foreach ( $time_array as $num => $time ) {

			if ( $num === $start ) {

				$html .= '<option value="' . esc_attr( $num ) . '" selected="selected">' . esc_attr( $time ) . '</option>';
			} else {
				$html .= '<option value="' . esc_attr( $num ) . '">' . esc_attr( $time ) . '</option>';
			}
		}

		$html .= '</select>';

		return $html;
	}

	/**
	 * Approves the specified appointment created by a client.
	 *
	 * @return array
	 */
	public function approve_cancel_app() {

		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$app_day           = isset( $_POST['app_day'] ) ? intval( wp_unslash( $_POST['app_day'] ) ) : '';
		$app_month         = isset( $_POST['app_month'] ) ? sanitize_text_field( wp_unslash( $_POST['app_month'] ) ) : '';
		$app_year          = isset( $_POST['app_year'] ) ? intval( wp_unslash( $_POST['app_year'] ) ) : '';
		$app_time          = isset( $_POST['app_time'] ) ? sanitize_text_field( wp_unslash( $_POST['app_time'] ) ) : '';
		$app_user          = isset( $_POST['app_user'] ) ? intval( wp_unslash( $_POST['app_user'] ) ) : '';
		$type              = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : 'cancel';
		$current_apps      = '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ) ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';
		$current_user_apps = '' !== get_user_meta( (int) $app_user, 'my_appointments', true ) && is_array( get_user_meta( (int) $app_user, 'my_appointments', true ) ) ? get_user_meta( (int) $app_user, 'my_appointments', true ) : '';

		if ( '' !== $current_apps && isset( $current_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ] ) ) {
			if ( $type === 'approve' ) {
				$current_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ]['status'] = 'approved';
			} elseif ( $type === 'cancel' ) {
				unset( $current_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ] );
			}
		}

		if ( '' !== $current_user_apps && isset( $current_user_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ] ) ) {
			if ( $type === 'approve' ) {
				$current_user_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ]['status'] = 'approved';
			} elseif ( $type === 'cancel' ) {
				unset( $current_user_apps[ $app_year ][ $app_month ][ $app_day ][ $app_time ] );
			}
		}

		update_user_meta( $current_user->ID, 'my_appointments', $current_apps );
		update_user_meta( $app_user, 'my_appointments', $current_user_apps );
	}

	/**
	 * Add an appointment to the professional/business and client's user meta.
	 *
	 * @access public
	 */
	public function add_app() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );
		date_default_timezone_set( 'America/Los_Angeles' );

		$appid             = isset( $_POST['appid'] ) ? intval( wp_unslash( $_POST['appid'] ) ) : '';
		$app_time          = isset( $_POST['app_day'] ) ? intval( wp_unslash( $_POST['app_day'] ) ) : '';
		$app_year          = (int) date( 'Y', $app_time );
		$app_month         = date( 'm', $app_time );
		$app_day           = (int) date( 'd', $app_time );
		$appentry          = isset( $_POST['appentry'] ) ? sanitize_text_field( wp_unslash( $_POST['appentry'] ) ) : '';
		$current_apps      = '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ) ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';
		$current_user_apps = '' !== get_user_meta( (int) $appid, 'my_appointments', true ) && is_array( get_user_meta( (int) $appid, 'my_appointments', true ) ) ? get_user_meta( (int) $appid, 'my_appointments', true ) : '';

		$new_app = array(
			$app_year => array(
				$app_month => array(
					$app_day => array(
						$appentry => array(
							'userid' => $appid,
							'status' => 'pending',
						),
					),
				),
			),
		);

		$new_user_app = array(
			$app_year => array(
				$app_month => array(
					$app_day => array(
						$appentry => array(
							'userid' => $current_user->ID,
							'status' => 'pending',
						),
					),
				),
			),
		);

		if ( '' !== $current_apps && is_array( $current_apps ) && $this->app_not_in_list( (int) $current_user->ID, (int) $app_year, (int) $app_month, (int) $app_day, esc_attr( $appentry ) ) ) {
			$current_apps[ $app_year ][ $app_month ][ $app_day ][ $appentry ] = array(
				'userid' => (int) $appid,
				'status' => 'pending',
			);

			$new_app = $current_apps;
		}

		if ( '' !== $current_user_apps && is_array( $current_user_apps ) && $this->app_not_in_list( (int) $appid, (int) $app_year, (int) $app_month, (int) $app_day, esc_attr( $appentry ) ) ) {
			$current_user_apps[ $app_year ][ $app_month ][ $app_day ][ $appentry ] = array(
				'userid' => $current_user->ID,
				'status' => 'pending',
			);

			$new_user_app = $current_user_apps;
		}

		update_user_meta( $current_user->ID, 'my_appointments', $new_app );
		update_user_meta( (int) $appid, 'my_appointments', $new_user_app );
	}

	/**
	 *
	 * Unlike app_not_in_list() this checks a specific professional/buisness appointments for availibilities.
	 *
	 * @param string $app The appointment time.
	 * @param integer $appid The user id of the professional/business you're checking availability.
	 * @param integer $year The appointment year.
	 * @param integer $month The appointment month.
	 * @param integer $day The appointment day.
	 *
	 * @return bool
	 */
	private function is_app_available( $app, $appid, $year, $month, $day ) {
		$current_appointments = '' !== get_user_meta( (int) $appid, 'my_appointments', true ) && is_array( get_user_meta( (int) $appid, 'my_appointments', true ) ) ? get_user_meta( (int) $appid, 'my_appointments', true ) : '';

		if ( '' !== $current_appointments && ! in_array( $app, array_keys( $current_appointments[ $year ][ $month ][ $day ] ), true ) ) {
			return true;
		} elseif ( '' === $current_appointments ){
			return true;
		}

		return false;
	}

	/**
	 *
	 * Checks to see if specified appointment is not in the current user's list.
	 *
	 * @param integer $userid The user id of the appointment in question.
	 * @param integer $app_year The year of the appointment.
	 * @param integer $app_month The month of the appointment.
	 * @param integer $app_day The day of the appointment.
	 * @param string $app_entry The appointment time.
	 *
	 * @return bool
	 */
	private function app_not_in_list( $userid, $app_year, $app_month, $app_day, $app_entry ) {
		global $current_user;

		$current_user_apps = '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ), true ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';

		if ( '' !== $current_user_apps && ! isset( $current_user_apps[ $app_year ][ $app_month ][ $app_day ][ $app_entry ] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Creates the appointment calendar shown in professional/business profile dashboards.
	 * Displays the current users appointments in the calendar.
	 */
	public function define_profile_calendar() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );
		date_default_timezone_set( 'America/Los_Angeles' );

		$selected_month = isset( $_POST['app_month'] ) ? sanitize_text_field( wp_unslash( $_POST['app_month'] ) ) : '';
		$selected_year  = isset( $_POST['app_year'] ) ? intval( wp_unslash( $_POST['app_year'] ) ) : '';
		$current_user_apps = null !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ) ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';
		$app_year          = '' !== $selected_year ? $selected_year : date( 'Y' );
		$app_month         = '' !== $selected_month ? $selected_month : date( 'm' );
		$month_check       = array( '02', '04', '06', '08', '10', '12' );

		//TODO Need to future proof the leap years.
		$year_check = array( 2016, 2020, 2024, 2028, 2032, 2036, 2040 );

		if ( in_array( $app_month, $month_check, true ) ) {
			$day_count = 30;
		} elseif ( $app_month === 2 ) {
			$day_count = 28;
		} elseif ( $app_month === 2 && in_array( $app_year, $year_check, true ) ) {
			$day_count = 29;
		} else {
			$day_count = 31;
		}

		$calendar = '<div class="year-' . esc_attr( $app_year ) . '">';
		$calendar .= '<div class="calendar-month month-' . esc_attr( $app_month ) . '">';

		for ( $i = 1; $i <= $day_count; $i ++ ) {
			$calendar .= '<div class="calendar-day day-' . esc_attr( $i ) . '"><p>' . (int) $i . '</p>';
			$calendar .= '<div class="app-wrappers">';

			if ( '' !== $current_user_apps ):
				foreach ( $current_user_apps[ $app_year ][ $app_month ][ $i ] as $apps => $app ) :
					$app_user_info = get_userdata( (int) $app['userid'] );
					$pending       = '';

					if ( isset( $app['status'] ) && $app['status'] === 'pending' ) {
						$pending = '<button app-year="' . (int) $app_year . '" app-month="' . esc_attr( $app_month ) . '" app-time="' . esc_attr( $apps ) . '" app-day="' . (int) $i . '" app-user="' . (int) $app_user_info->ID . '" num="' . (int) $i . '" class="approve-app">pending</button>';
					}

					$calendar .= '<li id="client-app-' . (int) $i . '-' . (int) $i . '" class="app_time"><span app-year="' . (int) $app_year . '" app-month="' . esc_attr( $app_month ) . '" app-time="' . esc_attr( $apps ) . '" app-day="' . (int) $i . '" app-user="' . (int) $app_user_info->ID . '" num="' . (int) $i . '" class="cancel-app">X</span>' . $pending;
					$calendar .= '<a href="/clients/' . esc_attr( $app_user_info->user_login ) . '">' . esc_html( $app_user_info->display_name ) . ': ' . esc_html( $apps ) . '</a>';
					$calendar .= '</li>';
				endforeach;
			endif;
			$calendar .= '</div></div>';
		}

		$calendar .= '</div></div>';

		echo $calendar;

		wp_die();
	}

	/**
	 * Returns the day of the month with the proper suffix.
	 *
	 * @param integer $number The number of the day of the month.
	 *
	 * @return string
	 */
	private function ordinal( $number ) {
		$ends = array( 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' );

		if ( ( ( (int) $number % 100 ) >= 11 ) && ( ( (int) $number % 100 ) <= 13 ) ) {
			return esc_html( $number . 'th' );
		}

		return esc_html( $number . $ends[ $number % 10 ] );
	}


	/**
	 * Are there any apps scheduled from today and on.
	 *
	 * @param array $apps The current user's scheduled appointments.
	 *
	 * @return bool
	 */
	private function are_there_future_apps( $apps ) {
		$current_year  = date( 'Y' );
		$current_month = date( 'm' );
		$current_day   = date( 'd' );

		if ( is_array( $apps ) ) {

			foreach ( $apps as $app1 => $month_array ) {

				if ( is_array( $month_array ) && $app1 >= $current_year ) {

					foreach ( $month_array as $app2 => $day_array ) {

						if ( is_array( $day_array ) && $app2 >= $current_month ) {

							foreach ( $day_array as $day => $appointment ) {

								if ( is_array( $appointment ) && array() !== $appointment && $day >= $current_day ) {

									return true;
								}
							}
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * Builds a list of appointments for the current client's dashboard.
	 *
	 * @return string
	 */
	public function get_all_appointments() {
		global $current_user;

		date_default_timezone_set( 'America/Los_Angeles' );

		$current_appointments = '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ) ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';

		$current_year  = (int) date( 'Y' );
		$current_month = (int) date( 'm' );
		$current_day   = (int) date( 'd' );

		if ( '' !== $current_appointments && is_array( $current_appointments ) && $this->are_there_future_apps( $current_appointments ) ) {

			$html = '<div class="client-appointments"><ul class="app-year">';

			foreach ( $current_appointments as $year => $month_array ) {

				if ( (int) $year >= $current_year ) {

					$i = 0;

					$html .= '<li>' . esc_html( $year ) . ': <ul class="app-month">';

					foreach ( $month_array as $monthNum => $day_array ) {

						if ( (int) $monthNum >= $current_month ) {

							$dateObj = DateTime::createFromFormat( '!m', $monthNum );
							$month   = $dateObj->format( 'F' );

							$html .= '<li>' . $month . ': <ul class="app-day">';

							foreach ( $day_array as $day => $apps ) {

								if ( (int) $day >= $current_day && $apps !== array() ) {
									$html .= '<li>' . esc_html( $this->ordinal( (int) $day ) ) . ': <ul>';

									foreach ( $apps as $app_entry => $app ) {
										$app_user_info = get_userdata( (int) $app['userid'] );

										if ( isset( $app['status'] ) && $app['status'] === 'approved' ) {

											$html .= '<li id="client-app-' . esc_attr( $day ) . '-' . esc_attr( $i ) . '" class="app_time"><span app-year="' . (int) $year . '" app-month="' . (int) $monthNum . '" app-time="' . esc_attr( $app_entry ) . '" app-day="' . (int) $day . '" app-user="' . (int) $app_user_info->ID . '" num="' . (int) $i . '" class="cancel-app">X</span>';
											$html .= '<a href="/professionals/' . esc_attr( $app_user_info->user_login ) . '">' . esc_html( $app_user_info->display_name ) . ': ' . esc_html( $app_entry ) . '</a>';
											$html .= '</li>';
										}
									}

									$html .= '</ul></li>';
								}
							}

							$html .= '</ul></li>';
						}

						$html .= '</ul></li>';

						$i ++;
					}
				}
			}

			$html .= '</ul></div>';

		} else {

			$html = '<p>No appointments scheduled.</p>';
		}

		return $html;
	}

	/**
	 * Creates and returns a list of appointments for the current day.
	 * TODO create single function that retuns appointments for all or a specific day based on parameter.
	 *
	 * @return string
	 */
	public function get_todays_appointments() {

		global $current_user;

		date_default_timezone_set( 'America/Los_Angeles' );

		$current_appointments = '' !== get_user_meta( (int) $current_user->ID, 'my_appointments', true ) && is_array( get_user_meta( (int) $current_user->ID, 'my_appointments', true ) ) ? get_user_meta( (int) $current_user->ID, 'my_appointments', true ) : '';

		$app_year  = date( 'Y' );
		$app_month = date( 'm' );
		$app_day   = date( 'd' );

		if ( '' !== $current_appointments && isset( $current_appointments[ $app_year ][ $app_month ][ $app_day ] ) && is_array( $current_appointments[ $app_year ][ $app_month ][ $app_day ] ) ) {

			$html = '<ul>';
			$i    = 0;

			foreach ( $current_appointments[ $app_year ][ $app_month ][ $app_day ] as $appentry => $app_info ) :

				$app_user_info = get_userdata( (int) $app_info['userid'] );
				$pending       = '';

				if ( isset( $app_info['status'] ) && $app_info['status'] === 'pending' ) {

					$pending = '<button app-year="' . (int) $app_year . '" app-month="' . esc_attr( $app_month ) . '" app-time="' . esc_attr( $appentry ) . '" app-day="' . (int) $app_day . '" app-user="' . (int) $app_user_info->ID . '" num="' . (int) $i . '" class="approve-app">pending</button>';
				}

				$html .= '<li id="client-app-' . esc_attr( $app_day ) . '-' . esc_attr( $i ) . '" class="app_time"><span app-time="' . esc_attr( $appentry ) . '" app-day="' . (int) $app_day . '" app-user="' . (int) $app_user_info->ID . '" num="' . (int) $i . '" class="cancel-app">X</span>' . $pending;
				$html .= '<a href="/clients/' . esc_attr( $app_user_info->user_login ) . '">' . esc_html( $app_user_info->display_name ) . ': ' . esc_html( $appentry ) . '</a></li>';

				$i ++;
			endforeach;

			$html .= '</ul>';
		} else {

			$html = '<p>No appointments for today.</p>';
		}

		return $html;
	}

	/**
	 * AJAX call back function for saving professional/business appointment settings.
	 */
	public function appointment_settings() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		$app_count            = isset( $_POST['count'] ) ? intval( wp_unslash( $_POST['count'] ) ) : '';
		$appointment_duration = isset( $_POST['duration'] ) ? intval( wp_unslash( $_POST['duration'] ) ) : '';
		$start_time           = isset( $_POST['start'] ) ? intval( wp_unslash( $_POST['start'] ) ) : '';
		$enabled              = isset( $_POST['enabled'] ) ? sanitize_text_field( wp_unslash( $_POST['enabled'] ) ) : '';
		$new_app_settings = array(
			'enabled'  => $enabled,
			'count'    => $app_count,
			'duration' => $appointment_duration,
			'start'    => $start_time,
		);

		update_user_meta( $current_user->ID, 'app_settings', $new_app_settings );
	}

	/**
	 * AJAX call back function for client's day selection on professional/business profiles.
	 */
	public function define_calendar() {
		global $current_user;

		// Security check.
		check_ajax_referer( $this->ajax_nonce, 'nonce' );

		date_default_timezone_set( 'America/Los_Angeles' );

		$appuserid            = isset( $_POST['appid'] ) ? intval( wp_unslash( $_POST['appid'] ) ) : '';
		$day                  = isset( $_POST['app_day'] ) ? strtotime( sanitize_text_field( wp_unslash( $_POST['app_day'] ) ) ) : 'now';
		$user_app_settings    = '' !== get_user_meta( (int) $appuserid, 'app_settings', true ) && is_array( get_user_meta( (int) $appuserid, 'app_settings', true ) ) ? get_user_meta( (int) $appuserid, 'app_settings', true ) : '';
		$current_appointments = '' !== get_user_meta( (int) $appuserid, 'my_appointments', true ) && is_array( get_user_meta( (int) $appuserid, 'my_appointments', true ) ) ? get_user_meta( (int) $appuserid, 'my_appointments', true ) : '';
		$app_count            = isset( $user_app_settings['count'] ) ? (int) $user_app_settings['count'] : '';
		$appointment_duration = isset( $user_app_settings['duration'] ) ? (int) $user_app_settings['duration'] : '';
		$start_time           = isset( $user_app_settings['start'] ) ? (int) $user_app_settings['start'] : '';
		$app_year             = date( 'Y', $day );
		$app_month            = date( 'm', $day );
		$app_day              = date( 'd', $day );
		$app                  = array();
		$tod                  = 'am';
		$tod2                 = 'am';
		$tod3                 = 'am';
		$tod4                 = 'am';

		for ( $i = 0; $i < $app_count; $i ++ ) {

			$adder = $i;

			switch ( $appointment_duration ) {

				case 2 :
					$adder = $i + $i;
					break;
				case 3 :
					$adder = $i + $i + $i;
					break;
				case 4 :
					$adder = $i + $i + $i + $i;
					break;
				case 5 :
					$adder = $i + $i + $i + $i + $i;
					break;
				case 6 :
					$adder = $i + $i + $i + $i + $i + $i;
					break;
				case 7 :
					$adder = $i + $i + $i + $i + $i + $i + $i;
					break;
				case 8 :
					$adder = $i + $i + $i + $i + $i + $i + $i + $i;
					break;
				case 9 :
					$adder = $i + $i + $i + $i + $i + $i + $i + $i + $i;
					break;
				case 10 :
					$adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
					break;
				case 11 :
					$adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
					break;
				case 12 :
					$adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
					break;
			}

			$loopfirst_time = $start_time + $adder;
			$loopsec_time = $loopfirst_time + $appointment_duration;

			if ( $loopfirst_time >= 12 ) {
				if ( $loopfirst_time > 12 ) {
					$loopfirst_time = $loopfirst_time - 12;
				}

				$tod = 'pm';

				if ( $loopfirst_time >= 12 ) {
					if ( $loopfirst_time > 12 ) {
						$loopfirst_time = $loopfirst_time - 12;
					}

					$tod = 'pm';

					if ( $loopfirst_time >= 12 ) {
						if ( $loopfirst_time > 12 ) {
							$loopfirst_time = $loopfirst_time - 12;
						}

						$tod = 'pm';

						if ( $loopfirst_time >= 12 ) {
							if ( $loopfirst_time > 12 ) {
								$loopfirst_time = $loopfirst_time - 12;
							}

							$tod = 'pm';
						}
					}
				}
			}

			if ( $loopsec_time >= 12 ) {
				if ( $loopsec_time > 12 ) {
					$loopsec_time = $loopsec_time - 12;
				}

				$tod2 = 'pm';

				if ( $loopsec_time >= 12 ) {
					if ( $loopsec_time > 12 ) {
						$loopsec_time = $loopsec_time - 12;
					}

					$tod2 = 'pm';

					if ( $loopsec_time >= 12 ) {
						if ( $loopsec_time > 12 ) {
							$loopsec_time = $loopsec_time - 12;
						}

						$tod2 = 'pm';

						if ( $loopsec_time >= 12 ) {
							if ( $loopsec_time > 12 ) {
								$loopsec_time = $loopsec_time - 12;
							}

							$tod2 = 'pm';
						}
					}
				}
			}

			$the_appointment = esc_html( $loopfirst_time ) . ":00" . esc_html( $tod ) . " - " . esc_html( $loopsec_time ) . ":00" . esc_html( $tod2 );
			$app_available = $this->is_app_available( $the_appointment, $appuserid, $app_year, $app_month, $app_day );

			if ( 0 !== $i && $app_available ) {
				$app[] = "<li class='app-" . esc_attr( $i ) . " single-app'><input value='" . (int) $appuserid . "' type='hidden' id='" . (int) $day . "' class='apps-" . (int) $i . "'><input type='button' id='" . (int) $i . "' class='app-time' value='" . esc_html( $the_appointment ) . "'></li>";
			}
		}

		$second_time = $start_time + $appointment_duration;

		if ( $second_time >= 12 ) {
			if ( $second_time > 12 ) {
				$second_time = $second_time - 12;
			}

			$tod4 = 'pm';
		}

		if ( $start_time >= 12 ) {
			if ( $start_time > 12 ) {
				$start_time = $start_time - 12;
			}

			$tod3 = 'pm';
		}

		$first_time = esc_html( $start_time ) . ":00" . esc_html( $tod3 ) . " - " . esc_html( $second_time ) . ":00" . esc_html( $tod4 );

		$is_app_available = $this->is_app_available( $first_time, $appuserid, $app_year, $app_month, $app_day );

		if ( array() !== $app && is_array( $app ) && $is_app_available ) {
			$app_start = "<li class='app-" . esc_attr( $i ) . " single-app'><input value='" . (int) $appuserid . "' type='hidden' id='" . (int) $day . "' class='apps-" . esc_attr( $i ) . "'><input type='button' id='" . (int) $i . "' class='app-time' value='" . esc_html( $first_time ) . "'></li>";
			array_unshift( $app, $app_start );
		}

		echo implode('', $app );

		wp_die();
	}
}

$od_appointments = new OD_Appointments();
