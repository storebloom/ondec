<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Profile_Pages
 */
class Profile_Pages {
    
    public function __construct(){
        
        add_action('init', array($this, 'custom_rewrite_tag'), 10, 0);
        add_action('init', array($this, 'custom_rewrite_rule'), 10, 0);
    }
    
    public function custom_rewrite_tag() {
    
        add_rewrite_tag('%professional%', '([^&]+)');
        add_rewrite_tag('%business%', '([^&]+)');
        add_rewrite_tag('%client%', '([^&]+)');
    }

    public function custom_rewrite_rule() {    
        
        add_rewrite_rule('^professionals/([^/]*)/?','index.php?page_id=22&professional=$matches[1]','top');
        add_rewrite_rule('^businesses/([^/]*)/?','index.php?page_id=27&business=$matches[1]','top');
        add_rewrite_rule('^clients/([^/]*)/?','index.php?page_id=42&client=$matches[1]','top');
    }
    
    public function get_pro_type_readable($pro_id){
        
        $professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");
        
        $pro_types_str = str_replace("pro-types=", "", get_user_meta($pro_id, 'protype', true));

        foreach(explode("&", $pro_types_str ) as $single_type){

            $professional_new_types[]=$professional_types[$single_type];

        }

        return $professional_new_types;
    }
    
    public function is_current_location($id){
        global $current_user;
        
        $current_location = intval(get_user_meta($current_user->ID, 'current_location', true));

        if($id === $current_location){
            return true;
        } else {
            return false;
        }
        
    }
}

$profile_pages = new Profile_Pages();