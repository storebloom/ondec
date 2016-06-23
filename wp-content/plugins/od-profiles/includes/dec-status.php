<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Decstatus
 */
class Decstatus {
    
    public function __construct(){
        
        add_action( 'wp_ajax_add_decstatus',        array($this, 'prefix_ajax_add_decstatus') );
        add_action( 'wp_ajax_nopriv_add_decstatus', array($this, 'prefix_ajax_add_decstatus') );
    }

    public function prefix_ajax_add_decstatus() {
        
        global $current_user;
        
        $decstatus = $_POST['decstatus'];
        
        update_user_meta( $current_user->ID, 'decstatus', $decstatus ); 
        
        echo "Dec status=" . $decstatus;
    }
}

$decstatus = new Decstatus();