<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_User_Search
 */
class OD_Map {

    public function __construct(){
        
        add_action( 'wp_enqueue_scripts',           array($this, 'google_map_enqueue') );
        add_action( 'init',                         array($this, 'add_map_canvas') );
    }
    
    public function google_map_enqueue(){
        
        wp_enqueue_script(
            'od-maps',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&v=3.exp&libraries=places&signed_in=true',
            array(),
            '1.0.0',
            true
	   );
        
        wp_enqueue_script(
            'od-custom-maps',
            '/wp-content/plugins/od-map/js/map.js',
            array( 'od-maps' ),
            '1.0.0',
            true
	   );
        
        wp_enqueue_style(
            'od-map-style',
            '/wp-content/plugins/od-map/css/custom-map.css',
            array(),
            '1.0.0',
            'all'
        );
        
    }
    
    public function add_map_canvas(){
        
        echo '<div id="map-canvas"></div>';
    }
}
    
$od_map = new OD_Map();