<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Decstatus
 */
class Decstatus {
    
    public function __construct(){
        
        add_action( 'wp_ajax_add_decstatus',            array($this, 'prefix_ajax_add_decstatus') );
        add_action( 'wp_ajax_nopriv_add_decstatus',     array($this, 'prefix_ajax_add_decstatus') );
        add_action( 'wp_ajax_add_decmessage',           array($this, 'prefix_ajax_add_decmessage') );
        add_action( 'wp_ajax_nopriv_add_decmessage',    array($this, 'prefix_ajax_add_decmessage') );
        add_action( 'wp_ajax_remove_decmember',         array($this, 'prefix_ajax_remove_decmember') );
        add_action( 'wp_ajax_nopriv_remove_decmember',  array($this, 'prefix_ajax_remove_decmember') );
        add_action( 'wp_ajax_add_decmember',            array($this, 'prefix_ajax_add_decmember') );
        add_action( 'wp_ajax_nopriv_add_decmember',     array($this, 'prefix_ajax_add_decmember') );
        add_action( 'wp_ajax_request_decmember',        array($this, 'prefix_ajax_request_decmember') );
        add_action( 'wp_ajax_nopriv_request_decmember', array($this, 'prefix_ajax_request_decmember') );
        add_action( 'wp_ajax_approve_pro',              array($this, 'prefix_ajax_approve_pro') );
        add_action( 'wp_ajax_nopriv_approve_pro',       array($this, 'prefix_ajax_appove_pro') );
        add_action( 'wp_ajax_remove_pro',               array($this, 'prefix_ajax_remove_pro') );
        add_action( 'wp_ajax_nopriv_remove_pro',        array($this, 'prefix_ajax_remove_pro') );
        add_action( 'wp_ajax_remove_biz',               array($this, 'prefix_ajax_remove_biz') );
        add_action( 'wp_ajax_nopriv_remove_biz',        array($this, 'prefix_ajax_remove_biz') );
        add_action( 'wp_ajax_add_pro_type',             array($this, 'prefix_ajax_add_pro_type') );
        add_action( 'wp_ajax_nopriv_add_pro_type',      array($this, 'prefix_ajax_add_pro_type') );
    }
    
    public function prefix_ajax_add_decmember() {
        
        global $current_user;
        
        $user_role = $current_user->roles[0];
        
        $adddecid = isset($_POST['adddecid']) ? $_POST['adddecid'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mydec', false);
        
        $current_followers = get_user_meta($adddecid, 'mydec', false);
        
        $current_dec_members = array() !== $current_dec_members ? $current_dec_members : array(0 => array());
        
        $current_followers = array() !== $current_followers ? $current_followers : array(0 => array());
        
        $new_array = array_merge($current_dec_members[0], array($adddecid));
        
        $new_followers = array_merge($current_followers[0], array($current_user->ID));
       
        update_user_meta($current_user->ID, 'mydec', $new_array);
        
        if($user_role === 'client'){
            
            update_user_meta($adddecid, 'mydec', $new_followers);
        }
        
        print_r($new_array, true);
    }
    
    public function prefix_ajax_request_decmember() {
        
        global $current_user;
        
        $requestdecid = isset($_POST['requestdecid']) ? $_POST['requestdecid'] : "";
        
        $business_pros = get_user_meta($requestdecid, 'pro_requests', false);
        
        $business_pros = array() !== $business_pros ? $business_pros : array(0 => array());
        
        $new_pros = array_merge($business_pros[0], array($current_user->ID));

        update_user_meta($requestdecid, 'pro_requests', $new_pros);
        
        print_r($new_array, true);
    }
    
    public function prefix_ajax_approve_pro() {
        
        global $current_user;
        
        $requestdecid = isset($_POST['approvepro']) ? $_POST['approvepro'] : "";
        
        $business_pros = get_user_meta($current_user->ID, 'pro_requests', false);
        
        $pro_businesses = get_user_meta($requestdecid, 'mybusinesses', false);
         
        $current_pros = get_user_meta($current_user->ID, 'mydec', false);
        
        $business_pros = array() !== $business_pros ? $business_pros : array(0 => array());
        $current_pros = array() !== $current_pros ? $current_pros : array(0 => array());
        $pro_businesses = array() !== $pro_businesses ? $pro_businesses : array(0 => array());
         
        $new_appros = array();
        
        if(isset($business_pros[0])){
            foreach( $business_pros[0] as $pros => $pro){

                if(intval($pro) !== intval($requestdecid)){

                    $new_appros[] = $pro;
                }
            }
        } 
        
        $new_biz = array_merge($pro_businesses[0], array($current_user->ID));
        $new_pros = array_merge($current_pros[0], array($requestdecid));

        update_user_meta($current_user->ID, 'pro_requests', $new_appros);
        update_user_meta($current_user->ID, 'mydec', $new_pros); 
        update_user_meta($requestdecid, 'mybusinesses', $new_biz);
        
        print_r($new_array, true);
    }
        
        public function prefix_ajax_remove_pro() {
        
        global $current_user;
        
        $requestdecid = isset($_POST['removepro']) ? $_POST['removepro'] : "";
        
        $business_requests = get_user_meta($current_user->ID, 'pro_requests', false);
        
        $business_requests = array() !== $business_requests ? $business_requests : array(0 => array());
       
        $new_rmpros = array();
        
        if(isset($business_requests[0])){
            foreach( $business_requests[0] as $pros => $pro){

                if($pro !== $requestdecid){

                    $new_rmpros[] = $pro;
                }
            }
        } 
        
        update_user_meta($current_user->ID, 'pro_requests', $new_rmpros);
      
        print_r($new_rmpros, true);
    }

    public function prefix_ajax_add_decstatus() {
        
        global $current_user;
        
        $decstatus = isset($_POST['decstatus']) ? $_POST['decstatus'] : ""; 
        
        update_user_meta( $current_user->ID, 'decstatus', $decstatus );
    
        echo "Dec status=" . $decstatus;
    }
    
     public function prefix_ajax_add_pro_type() {
        
        global $current_user;
        
        $pro_types = isset($_POST['typeselected']) ? $_POST['typeselected'] : ""; 
        
        update_user_meta( $current_user->ID, 'protype', $pro_types );
    
        print_r($protypes);
    }
    
    public function prefix_ajax_add_decmessage() {
        
        global $current_user;
        
        $decmessage = isset($_POST['decmessage']) ? $_POST['decmessage'] : "";
        
        update_user_meta( $current_user->ID, 'decmessage', $decmessage ); 
        
        echo "success!";
    }
    
    public function prefix_ajax_remove_decmember() {
        
        global $current_user;
        
        $user_role = $current_user->roles[0];
        
        $rmdecid = isset($_POST['rmdecid']) ? $_POST['rmdecid'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mydec', false);
        $current_biz = get_user_meta($rmdecid, 'mybusinesses', false);
        $client_pro = get_user_meta($rmdecid, 'mydec', false);
        
        $new_array = array();
        
        if(isset($current_dec_members[0])){
            foreach( $current_dec_members[0] as $dec_member => $member){

                if($member !== $rmdecid){

                    $new_array[] = $member;
                }
            }
        }
      
        update_user_meta($current_user->ID, 'mydec', $new_array);
        
        if($user_role === 'business'){
            $new_biz = array();

            if(isset($current_biz[0])){
                foreach( $current_biz[0] as $c_bizs => $c_biz){

                    if(intval($c_biz) !== intval($current_user->ID)){

                        $new_biz[] = $c_biz;
                    }
                }

                update_user_meta($rmdecid, 'mybusinesses', $new_biz);
            }
        }
        
        if($user_role === 'client'){
            $new_follower = array();

            if(isset($client_pro[0])){
                foreach( $client_follower[0] as $c_followers => $c_follower){

                    if(intval($c_follower) !== intval($current_user->ID)){

                        $new_follower[] = $c_pro;
                    }
                }

                update_user_meta($rmdecid, 'mydec', $new_follower);
            }
        }
        //print_r($rmdecid); 
    }
    
    public function prefix_ajax_remove_biz() {
        
        global $current_user;
        
        $rmbizid = isset($_POST['rmbizid']) ? $_POST['rmbizid'] : "";
        $current_businesses = get_user_meta($current_user->ID, 'mybusinesses', false);
        $current_pros = get_user_meta($rmbizid, 'mydec', false);
        
        $new_array = array();
       
        if(isset($current_businesses[0])){
            foreach( $current_businesses[0] as $businesses => $business){

                if($business !== intval($rmbizid)){

                    $new_array[] = $business;
                }
            }
            update_user_meta($current_user->ID, 'mybusinesses', $new_array); 
        }
            
        $new_pro = array();
        
        if(isset($current_pros[0])){
            foreach( $current_pros[0] as $c_pros => $c_pro){

                if(intval($c_pro) !== $current_user->ID){

                    $new_pro[] = $c_pro;
                }
            }
            
            update_user_meta($rmbizid, 'mydec', $new_pro); 
        }
    }
}

$decstatus = new Decstatus();