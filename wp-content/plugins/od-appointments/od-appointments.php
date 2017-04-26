<?php
/**
 * Plugin Name: Ondec Appointments
 * Description: Adds an appointment system to professional and business accounts.
 * Version: 0.1.0
 *
 * Author: Scott Adrian
 *
 * @copyright 2016
 *
 * @author Scott Adrian
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/class-od-appointments.php' );
