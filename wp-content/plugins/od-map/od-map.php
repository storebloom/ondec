<?php
/**
 * Plugin Name: OD Map
 * Description: This adds the map for searching current businesses.
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

function od_map_display($address = ""){
    if(isset($_POST['search_val'])){  

        $address = $_POST['search_val'];

        require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/od-map-class.php');

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

add_shortcode('od_map_display', 'od_map_display');