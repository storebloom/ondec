<?php
/**
 * Plugin Name: OD Registration
 * Description: Creates shortcodes for each registration form type.
 * Version: 0.1.0
 * Author: Scott Adrian
 *
 * @copyright 2016
 * @author Scott Adrian
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

private function __construct() {

    register_activation_hook( __FILE__, array( $this, 'activate' ) );

    add_action( 'init',                 array( $this, 'includes' ), 99 );
}

/**
 * A check to see if we are on PHP version greater than 5.4.
 *
 * @since  0.1.0
 */
public function activate() {

    // Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
    if ( 0 > check_version( PHP_VERSION, '5.5' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( sprintf( __( 'This plugin requires PHP Version 5.5. You are running <code>%s</code>. Sorry about that.', 'ftpwon' ), phpversion() ) );
    }
}

function include(){
    
    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'registration-forms/professional-registration.php');
}
        
        