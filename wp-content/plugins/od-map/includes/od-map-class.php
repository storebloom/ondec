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
    
    public static function geocode_address($address){
        
        $geocoded_address = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&address=' . urlencode($address));
        
        return $geocoded_address;
    }
    
    public static function get_the_address($key){
        
        $config = array('host'=>'localhost', 'user'=>'root', 'pass'=>'root', 'db_name'=>'odwp2016');

                $sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db_name']);

                if (mysqli_connect_errno()) {

                  printf("Connect failed: %s\n", mysqli_connect_error());

                  exit;
                }

                $query = "SELECT meta_value from od_usermeta WHERE user_id IN (SELECT user_id FROM od_usermeta WHERE meta_key = 'od_capabilities' AND meta_value LIKE '%business%') AND user_id IN (SELECT ID from od_users WHERE user_nicename LIKE '%{$key}%' OR display_name LIKE '%{$key}%' OR user_email LIKE '%{$key}%') AND meta_key = 'address'";

                $result = $sql->query($query);

                if($result->num_rows !== 0){

                    while($row = $result->fetch_row()) {

                        if($row[0]){
                    $rows[]=$row;}
                    }
                }else{ 

                    $rows = array('no suggestions.');
                }
    
                return $rows;
                $result->close();

                $sql->close();
    }
}
    
$od_map = new OD_Map();