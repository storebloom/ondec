<?php
/**
 * OD_User_Search
 */

class OD_Map {

    public function __construct(){
        
        add_action( 'wp_enqueue_scripts',           array($this, 'google_map_enqueue') );
        add_action( 'get_the_address',              array($this, 'get_the_address') );
        add_action( 'init',                         array($this, 'add_map_canvas') );
    }
    
    public function google_map_enqueue(){
        
        wp_register_script(
            'od-maps',
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&v=3.exp&libraries=places&signed_in=true',
            array(), null, true);
        wp_enqueue_script('od-maps');
        
        wp_register_script(
            'od-custom-maps',
            '/wp-content/plugins/od-map/js/map.js',
            array( 'od-maps' ), null, true);
        
        wp_enqueue_script('od-custom-maps');
        
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
    
    public function geocode_address($address){
        
        $geocoded_address = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&address=' . urlencode($address));
        
        $geocode_info = json_decode($geocoded_address);
        
        foreach($geocode_info as $geoloc){

             if(isset($geoloc[0]->geometry)){

                $coordlat[] = $geoloc[0]->geometry->location->lat;
                $coordlng[] = $geoloc[0]->geometry->location->lng; 
             }

        }

        self::define_global_var($coordlat[0], $coordlng[0]);
    }
    
    public static function get_current_addresses(){

        $config = array('host'=>'localhost', 'user'=>'root', 'pass'=>'root', 'db_name'=>'odwp2016');

        $sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db_name']);

        if (mysqli_connect_errno()) {

          printf("Connect failed: %s\n", mysqli_connect_error());

          exit;
        }

        $query = "SELECT meta_value from od_usermeta WHERE user_id IN (SELECT user_id FROM od_usermeta WHERE meta_key = 'od_capabilities' AND meta_value LIKE '%business%') AND meta_key = 'address'";

        $result = $sql->query($query);

        if($result->num_rows !== 0){

            while($row = $result->fetch_row()) {

                if($row[0]){
            $rows[]=$row;}
            }
        }else{ 

            $rows = array('no suggestions.');
        }

        $result->close();

        $sql->close();

        $search_results = $rows;

        foreach($search_results as $results){
            
            $geocode_info = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&address=' . urlencode($results[0])));
        
        foreach($geocode_info as $geoloc){
            
             if(isset($geoloc[0]->geometry) && $geoloc[0]->geometry->location->lat !== NULL && $geoloc[0]->geometry->location->lng !== NULL){

                $coord[] = array('lat' => $geoloc[0]->geometry->location->lat, 'lng' => $geoloc[0]->geometry->location->lng) ;
             }

        }
        }

        return $coord;
    }
        
    public static function define_global_var($coordlat, $coordlng){
        
        $coordinates = self::get_current_addresses();
   
        echo '<script> var search_val = { "lat" : '.$coordlat.' , "long" : '. $coordlng . ' }; var data = {
        "markers_available":[ ';
        
        foreach($coordinates as $single_coor){
            
        echo '
            {"lat":"'.$single_coor['lat'].'","long":"'.$single_coor['lng'].'"},';
            
        }
        
       echo ' ]}; </script>';

    }
}

$od_map = new OD_Map();