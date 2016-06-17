<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_User_Search
 */
class OD_User_Search {

    public function __construct(){
        
        add_action( 'setup_theme',        array($this, 'live_search_results') );
        add_action( 'wp_enqueue_scripts', array($this, 'ajax_search_enqueues') );
    }
    
    public function ajax_search_enqueues() {

        wp_enqueue_script( 'od-main',  '/wp-content/plugins/od-user-search/js/od-main.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'typeahead', '/wp-content/plugins/od-user-search/js/typeahead.min.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_style( 'od-user-search', '/wp-content/plugins/od-user-search/css/od-user-search.css',false,'1.1','all');
    }
}
    
$od_user_search = new OD_User_Search();    