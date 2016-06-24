<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Decstatus
 */
class Decstatus {
    
    public function __construct(){
        
        add_action( 'wp_ajax_add_decstatus',        array($this, 'prefix_ajax_add_decstatus') );
        add_action( 'wp_ajax_nopriv_add_decstatus', array($this, 'prefix_ajax_add_decstatus') );
        add_action( 'wp_ajax_add_decmessage',        array($this, 'prefix_ajax_add_decmessage') );
        add_action( 'wp_ajax_nopriv_add_decmessage', array($this, 'prefix_ajax_add_decmessage') );
        add_action( 'wp_ajax_remove_decmember',        array($this, 'prefix_ajax_remove_decmember') );
        add_action( 'wp_ajax_nopriv_remove_decmember', array($this, 'prefix_ajax_remove_decmember') );
        add_action( 'wp_ajax_add_decmember',        array($this, 'prefix_ajax_add_decmember') );
        add_action( 'wp_ajax_nopriv_add_decmember', array($this, 'prefix_ajax_add_decmember') );
    }
    
    public function prefix_ajax_add_decmember() {
        
        global $current_user;
        
        $adddecid = isset($_POST['adddecid']) ? $_POST['adddecid'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mydec', false);
        
        $new_array = $current_dec_members;
        
        foreach( $current_dec_members as $dec_memeber){
            
            if($dec_member !== $decid){
                
                $new_array[] = $decmember;
            }
        }
        
        if(is_array($adddecid) && $adddecid !== array()){
        $new_array = array_merge($new_array, $adddecid);
        }else{
           $new_array = array($adddecid);
        }
        update_user_meta($current_user->ID, 'mydec', $new_array);
        
        print_r($adddecid, true);
    }

    public function prefix_ajax_add_decstatus() {
        
        global $current_user;
        
        $decstatus = isset($_POST['decstatus']) ? $_POST['decstatus'] : ""; 
        
        update_user_meta( $current_user->ID, 'decstatus', $decstatus );
    
        echo "Dec status=" . $decstatus;
    }
    
    public function prefix_ajax_add_decmessage() {
        
        global $current_user;
        
        $decmessage = isset($_POST['decmessage']) ? $_POST['decmessage'] : "";
        
        update_user_meta( $current_user->ID, 'decmessage', $decmessage ); 
        
        echo "success!";
    }
    
    public function prefix_ajax_remove_decmember() {
        
        global $current_user;
        
        $rmdecid = isset($_POST['rmdecid']) ? $_POST['rmdecid'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mydec', false);
        
        $new_array = array();
        
        if(isset($current_dec_members[0])){
            foreach( $current_dec_members[0] as $dec_member => $member){

                if($member !== $rmdecid){

                    $new_array[] = $member;
                }
            }
        }
      
        update_user_meta($current_user->ID, 'mydec', $new_array);
        print_r($rmdecid); 
    }
}

$decstatus = new Decstatus();