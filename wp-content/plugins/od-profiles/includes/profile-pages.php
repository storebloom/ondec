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
    
    
 
    public function new_mail_from($old) {
     return 'notification@ondec.info';
    }
    
    public function new_mail_from_name($old) {
     return 'ondec user';
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
    
    public function is_not_on_list($id, $type = 'mydec'){
        
        global $current_user;
        
        $current_dec = get_user_meta($current_user->ID, $type, true);
        $request_dec = get_user_meta($id, $type, true);

        if(isset($current_dec) && $current_dec !== "" && $current_dec !== false || "" !== $request_dec){

            if($type === "myfriends" ){

                if(isset($request_dec[0][0]) && null !== $request_dec[0][0]){
                  
                    foreach($request_dec[0] as $dec_members){

                        if(isset($dec_members['user']) && intval($dec_members['user']) === intval($current_user->ID)){

                            return false;
                        }
                    }
                } elseif(isset($request_dec[0]) && intval($request_dec[0]['user']) === intval($current_user->ID)){

                        return false;
                }
            
            } else {
            
                foreach($current_dec as $dec_members){

                    if(intval($dec_members) === intval($id)){

                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    public function get_user_profile_url($id){
        
        $user_data = get_userdata($id);
        
        $display_name = $user_data->user_login;

        $user_role = $user_data->roles[0];
                
        $user_url = "";
        
        if($user_role === 'professional' || $user_role === 'administrator'){
            
            $user_url = "/professionals/" . $display_name;
        }elseif($user_role === 'business'){
            
            $user_url = "/businesses/" . $display_name;
        }elseif($user_role === 'client'){
            
            $user_url = "/clients/" . $display_name;
        }
        
        return $user_url;
    }
}

$profile_pages = new Profile_Pages();