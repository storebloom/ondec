<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_User_Search
 */
class OD_User_Search {

    public function __construct(){
        
        add_action( 'setup_theme',                  array($this, 'live_search_results') );
        add_action( 'wp_enqueue_scripts',           array($this, 'ajax_search_enqueues') );
    }
    
    
    public function ajax_search_enqueues() {
        
        global $current_user;
        
        if(is_user_logged_in()){
            
        $user_role = $current_user->roles[0];
        
            if($user_role === 'professional'){
                wp_enqueue_script( 'od-main',  '/wp-content/plugins/od-user-search/js/od-main-pro.js', array( 'jquery' ), '1.0.0', true );
            } elseif ($user_role === 'business') {
                wp_enqueue_script( 'od-main',  '/wp-content/plugins/od-user-search/js/od-main-biz.js', array( 'jquery' ), '1.0.0', true );
            } elseif ($user_role === 'client' || $user_role === 'administrator') {
                wp_enqueue_script( 'od-main',  '/wp-content/plugins/od-user-search/js/od-main.js', array( 'jquery' ), '1.0.0', true );
            }
        } else {
            wp_enqueue_script( 'od-main',  '/wp-content/plugins/od-user-search/js/od-main.js', array( 'jquery' ), '1.0.0', true );
        }
        wp_enqueue_script( 'typeahead', '/wp-content/plugins/od-user-search/js/typeahead.min.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_style( 'od-user-search', '/wp-content/plugins/od-user-search/css/od-user-search.css',false,'1.1','all');
    }
    
    public function in_my_dec_already($check_id){
        
        global $current_user;
        
        if(is_user_logged_in()){

            $current_dec = get_user_meta($current_user->ID, 'mydec', false);
            $current_dec = array() !== $current_dec ? $current_dec : array(0 => array());

            if($current_dec[0] !== array()){

                foreach($current_dec[0] as $currentid ){

                    if($currentid == $check_id){     

                        return false;
                    }
                }
            }
        }
        
        return true;
    }
    
    public function get_user_modal_results($key){

                $config = array('host'=>'localhost', 'user'=>'root', 'pass'=>'root', 'db_name'=>'odwp2016');

                $sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db_name']);

                if (mysqli_connect_errno()) {

                  printf("Connect failed: %s\n", mysqli_connect_error());

                  exit;
                }

                $query = "SELECT * from od_users WHERE ID IN (SELECT user_id FROM od_usermeta WHERE meta_key = 'od_capabilities' AND meta_value LIKE '%professional%' OR meta_value LIKE '%business%' OR meta_value LIKE '%client%') AND ID IN (SELECT ID from od_users WHERE user_nicename LIKE '%{$key}%' OR display_name LIKE '%{$key}%' OR user_email LIKE '%{$key}%')";

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
    
$od_user_search = new OD_User_Search();    