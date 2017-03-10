<?php
/**
 * OD_User_Search
 */

class OD_Map {

    public function __construct(){
        
        add_action( 'wp_enqueue_scripts',           array($this, 'google_map_enqueue') );
        add_action( 'get_the_address',              array($this, 'get_the_address') );
        add_shortcode('homepage-map',               array($this, 'show_homepage_map'));
        add_shortcode('od_map_display',             array($this, 'od_map_display'));
    }
    
    public function od_map_display($address = ""){
        if(isset($_POST['search_val'])){  

            $address = $_POST['search_val'];

            $od_map->google_map_enqueue();
            $od_map->add_map_canvas();
            $od_map->geocode_address($address);
        }

        $display_search_form = '<form method="post" id="search-address">
        <input name="search_val" type="text" placeholder="enter your address here" >
        <input name="submit" type="submit" value="search" >
        </form>';

        return $display_search_form;
    }
    
    public function show_homepage_map(){
        
        require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'ip2locationlite.class.php');
		 if($_SERVER['REMOTE_ADDR'] !== '127.0.0.1'){
				//Load the class
				$ipLite = new ip2location_lite;
				$ipLite->setKey('37a3692ac27db659e807dc8ca72c062979edb44b366ef001342b521a8bf6c50c');

				//Get errors and locations
				$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
				$errors = $ipLite->getError();

				if($locations['statusCode'] === 'OK'){

					$address = $locations['cityName']. ' '.$locations['regionName']. ', '.$locations['countryCode']. ' '.$locations['zipCode'];
					self::google_map_enqueue();
					self::geocode_address($address);
					return 'ftp deploy works<div id="map_wrapper"><div id="map-canvas"></div></div>';
				}
		 }
    }
    
    public static function google_map_enqueue(){
        
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
    
    public static function add_map_canvas(){
        
         echo '<div id="map_wrapper"><div id="close_map">X</div><div id="map-canvas"></div></div>';
    }
    
    public static function geocode_address($address){
        
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

        $config = array('host'=>DB_HOST, 'user'=>DB_USER, 'pass'=>DB_PASSWORD, 'db_name'=>DB_NAME);

        $sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db_name']);

        if (mysqli_connect_errno()) {

          printf("Connect failed: %s\n", mysqli_connect_error());

          exit;
        }

        $query = "SELECT user_id,  meta_value from od_usermeta WHERE user_id IN (SELECT user_id FROM od_usermeta WHERE meta_key = 'od_capabilities' AND meta_value LIKE '%business%') AND meta_key = 'address'";

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

            $geocode_info = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&address=' . urlencode($results[1])));
        
            foreach($geocode_info as $geoloc){
                
                $type = !empty(get_user_meta($results[0], 'business_type', true)) ? get_user_meta($results[0], 'business_type', true) : "default";

                 if(isset($geoloc[0]->geometry) && $geoloc[0]->geometry->location->lat !== NULL && $geoloc[0]->geometry->location->lng !== NULL){

                    $coord[] = array('lat' => $geoloc[0]->geometry->location->lat, 'lng' => $geoloc[0]->geometry->location->lng, 'info' => $results[0], 'address' => $geoloc[0]->formatted_address, 'business_type' => $type);
                 }

            }
        }

        return $coord;
    }
    
   public static function get_info_card($id, $address = ""){
        
        $user_info = get_userdata(intVal($id));
        
        $user_avatar = intVal(get_user_meta($user_info->ID, 'od_user_avatar', true));
        
        $open_close =  !empty(get_user_meta($user_info->ID, 'decstatus', true)) ? get_user_meta($user_info->ID, 'decstatus', true) : "Closed";
        
        $pro_url = get_the_guid($user_avatar);
        
        $info_card = '<div class=\"map_name\">' . $user_info->display_name . '<\/div>';
        $info_card .= '<div class=\"map_avatar\"><img width=\"60px\" src=\"'.  $pro_url . '\" ><\/div>';
        $info_card .= '<div class=\"map_open\">We Are '.  $open_close . '<\/div>';
        $info_card .= '<div class=\"map_address\"><a target=\"_blank\" href=\"https://www.google.com/maps/dir/'.$address.'\">'.$address.'<\/a><\/div>';
        $info_card .= '<div class=\"map_link\"><a href=\"/businesses/' . $user_info->user_login . '/\">view profile<\/a><\/div>';
            
        
        return $info_card;

    }
        
    public static function define_global_var($coordlat, $coordlng){
        
        $coordinates = self::get_current_addresses();

        echo '<script> var search_val = { "lat" : '.$coordlat.' , "long" : '. $coordlng . ' }; var data = {
        "markers_available":[ ';
        
        foreach($coordinates as $single_coor){
            
            $info = self::get_info_card($single_coor['info'], $single_coor['address']);
            
        echo '
            {"lat":"'.$single_coor['lat'].'","long":"'.$single_coor['lng'].'", "info":"'.$info.'","business_type":"' . $single_coor['business_type'] .'"},';
            
        }
        
       echo ' ]}; </script>';

    }
}

$od_map = new OD_Map();