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
        add_action( 'wp_ajax_add_current_location',             array($this, 'prefix_ajax_add_current_location') );
        add_action( 'wp_ajax_nopriv_add_current_location',      array($this, 'prefix_ajax_add_current_location') );
        add_action( 'wp_ajax_remove_current_location',             array($this, 'prefix_ajax_remove_current_location') );
        add_action( 'wp_ajax_nopriv_remove_current_location',      array($this, 'prefix_ajax_remove_current_location') );
        add_action( 'wp_ajax_approve_biz_request',             array($this, 'prefix_ajax_approve_biz_request') );
        add_action( 'wp_ajax_nopriv_approve_biz_request',      array($this, 'prefix_ajax_approve_biz_request') );
        add_action( 'wp_ajax_remove_biz_request',             array($this, 'prefix_ajax_remove_biz_request') );
        add_action( 'wp_ajax_nopriv_remove_biz_request',      array($this, 'prefix_ajax_remove_biz_request') );
        add_action( 'wp_ajax_add_usermessage',             array($this, 'prefix_ajax_add_usermessage') );
        add_action( 'wp_ajax_nopriv_add_usermessage',      array($this, 'prefix_ajax_add_usermessage') );
        add_action( 'wp_ajax_add_read_status',             array($this, 'prefix_ajax_add_read_status') );
        add_action( 'wp_ajax_nopriv_add_read_status',      array($this, 'prefix_ajax_add_read_status') );
        add_action( 'wp_ajax_like_decmember',            array($this, 'prefix_ajax_like_decmember') );
        add_action( 'wp_ajax_nopriv_like_decmember',     array($this, 'prefix_ajax_like_decmember') );
        add_action( 'wp_ajax_add_userendorse',             array($this, 'prefix_ajax_add_userendorse') );
        add_action( 'wp_ajax_nopriv_add_userendorse',      array($this, 'prefix_ajax_add_userendorse') );
        add_action( 'wp_ajax_remove_end',             array($this, 'prefix_ajax_remove_end') );
        add_action( 'wp_ajax_nopriv_remove_end',      array($this, 'prefix_ajax_remove_end') );
        add_action( 'wp_ajax_approve_endorsement',             array($this, 'prefix_ajax_approve_endorsement') );
        add_action( 'wp_ajax_nopriv_approve_endorsement',      array($this, 'prefix_ajax_approve_endorsement') );
        add_action( 'wp_ajax_approve_friend',             array($this, 'prefix_ajax_approve_friend') );
        add_action( 'wp_ajax_nopriv_approve_friend',      array($this, 'prefix_ajax_approve_friend') );
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
    
    public function prefix_ajax_like_decmember() {
        
        global $current_user;
        
        $user_role = $current_user->roles[0];
        
        $likedecid = isset($_POST['likedecid']) ? $_POST['likedecid'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mylikes', false);
        
        $current_followers = get_user_meta($likedecid, 'mylikers', false);
        
        $current_dec_members = array() !== $current_dec_members ? $current_dec_members : array(0 => array());
        
        $current_followers = array() !== $current_followers ? $current_followers : array(0 => array());
        
        $new_array = array_merge($current_dec_members[0], array($likedecid));
        
        $new_followers = array_merge($current_followers[0], array($current_user->ID));
       
        update_user_meta($current_user->ID, 'mylikes', $new_array);
        
        if($user_role === 'client'){
            
            update_user_meta($likedecid, 'mylikers', $new_followers);
        }
        
        print_r($new_array, true);
    }
    
    public function prefix_ajax_request_decmember() {
        
        global $current_user;
        
        $user_role = $current_user->roles[0];
        
        $requestdecid = isset($_POST['requestdecid']) ? $_POST['requestdecid'] : "";
        
        if($user_role === 'professional'){
            
        $business_pros = get_user_meta($requestdecid, 'pro_requests', false);
        
        $business_pros = array() !== $business_pros ? $business_pros : array(0 => array());
        
        $new_pros = array_merge($business_pros[0], array($current_user->ID));

        update_user_meta($requestdecid, 'pro_requests', $new_pros);
        
        } elseif($user_role === 'business') {
            
        $business_pros = get_user_meta($requestdecid, 'business_requests', false);
        
        $business_pros = array() !== $business_pros ? $business_pros : array(0 => array());
        
        $new_pros = array_merge($business_pros[0], array($current_user->ID));

        update_user_meta($requestdecid, 'business_requests', $new_pros);
        } elseif($user_role === 'client'){
            
            $current_friends = get_user_meta($requestdecid, 'myfriends', false);

            $my_friends = array() !== $current_friends ? $current_friends : array(0 => array());

            if(!is_array($my_friends[0][0])){

                $new_friends = array_merge($my_friends[0], array('user' => $current_user->ID, 'approval_status' => 'pending'));

                update_user_meta($requestdecid, 'myfriends', array($new_friends));
            } else {
                $new_friends = array_merge($my_friends[0], array(array('user' => $current_user->ID, 'approval_status' => 'pending')));

                update_user_meta($requestdecid, 'myfriends', $new_friends);
            }
        }
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
    
    public function prefix_ajax_approve_biz_request() {
        
        global $current_user;
        
        $requestdecid = isset($_POST['approvebizrequest']) ? $_POST['approvebizrequest'] : "";
        
        $business_requests = get_user_meta($current_user->ID, 'business_requests', false);
        
        $current_pros = get_user_meta($requestdecid, 'mydec', false);
         
        $current_biz = get_user_meta($current_user->ID, 'mybusinesses', false);
        
        $business_requests = array() !== $business_requests ? $business_requests : array(0 => array());
        $current_pros = array() !== $current_pros ? $current_pros : array(0 => array());
        $current_biz = array() !== $current_biz ? $current_biz : array(0 => array());
         
        $new_apbiz = array();
        
        if(isset($business_requests[0])){
            foreach( $business_requests[0] as $bizs => $biz){

                if(intval($biz) !== intval($requestdecid)){

                    $new_apbiz[] = $biz;
                }
            }
        } 
        
        $new_pros = array_merge($current_pros[0], array($current_user->ID));
        $new_bizs = array_merge($current_biz[0], array($requestdecid));

        update_user_meta($current_user->ID, 'business_requests', $new_apbiz);
        update_user_meta($requestdecid, 'mydec', $new_pros); 
        update_user_meta($current_user->ID, 'mybusinesses', $new_bizs);
        
        print_r($new_array, true);
    }
        
        public function prefix_ajax_remove_biz_request() {
        
        global $current_user;
        
        $requestdecid = isset($_POST['removebizrequest']) ? $_POST['removebizrequest'] : "";
        
        $business_requests = get_user_meta($current_user->ID, 'business_requests', false);
        
        $business_requests = array() !== $business_requests ? $business_requests : array(0 => array());
       
        $new_rmbizs = array();
        
        if(isset($business_requests[0])){
            foreach( $business_requests[0] as $bizs => $biz){

                if($biz !== $requestdecid){

                    $new_rmbizs[] = $biz;
                }
            }
        } 
        
        update_user_meta($current_user->ID, 'business_requests', $new_rmbizs);
      
        print_r($new_rmpros, true);
    }

    public function prefix_ajax_add_decstatus() {
        
        global $current_user;
        
        $decstatus = isset($_POST['decstatus']) ? $_POST['decstatus'] : ""; 
        
        update_user_meta( $current_user->ID, 'decstatus', $decstatus );
    
        echo "Dec status=" . $decstatus;
    }
    
    public function prefix_ajax_add_current_location() {
        
        global $current_user;
        
        $current_location = isset($_POST['currentloc']) ? $_POST['currentloc'] : ""; 
        
        update_user_meta( $current_user->ID, 'current_location', $current_location );
    
        echo "location=" . $current_location;
    }
    
    public function prefix_ajax_remove_current_location() {
        
        global $current_user;
      
        update_user_meta($current_user->ID, 'current_location', '');

    }
    
     public function prefix_ajax_add_pro_type() {
        
        global $current_user;
        
        $pro_types = isset($_POST['typeselected']) ? $_POST['typeselected'] : ""; 
        print_r($pro_types);
        update_user_meta( $current_user->ID, 'protype', $pro_types );
    
        
    }
    
    public function prefix_ajax_add_decmessage() {
        
        global $current_user;
        
        $decmessage = isset($_POST['decmessage']) ? $_POST['decmessage'] : "";
        
        update_user_meta( $current_user->ID, 'decmessage', $decmessage ); 
        
        echo "success!";
    }
    
    public function prefix_ajax_add_usermessage() {
        
        global $current_user;
            
        date_default_timezone_set('America/Los_Angeles');
        
        $usermessage = isset($_POST['usermessage']) ? $_POST['usermessage'] : "";
        $msgid = isset($_POST['msgid']) ? $_POST['msgid'] : "";
        $messageid = isset($_POST['messageid']) ? $_POST['messageid'] : "";
        $c_date = time();
        
        $usermessage_id = array('messageid' => $messageid, 'message_date' => $c_date, 'user' => $current_user->ID, 'message' => $usermessage, 'read_status' => 'unread'); 
        
        $current_message_array = get_user_meta($msgid, 'my_messages', false);
        
        $current_messages = isset($current_message_array) ? $current_message_array : "";
       
        if(NULL === $current_messages[0]){
    
        update_user_meta( $msgid, 'my_messages', $usermessage_id );
            
        } elseif( 1 === count($current_messages) && 5 === count($current_messages[0]) && NULL === $current_messages[0][0])  {
            
        $new_message_array = array_merge($current_messages, array($usermessage_id));
            
        update_user_meta( $msgid, 'my_messages', $new_message_array );
        } elseif( 2 <= count($current_messages[0]) ){
            
        $new_message_array = array_merge($current_messages[0], array($usermessage_id));
            
        update_user_meta( $msgid, 'my_messages', $new_message_array ); 
        }
        
        
        echo "success!";
    }
    
    public function prefix_ajax_approve_friend() {
        
        global $current_user;
        
        $friendid = isset($_POST['friendid']) ? $_POST['friendid'] : "";
        
        $current_friends = get_user_meta($current_user->ID, 'myfriends');
        $request_friends = get_user_meta($friendid, 'myfriends', false);

        if(is_array($current_friends[0][0])){
            foreach($current_friends[0] as $message_key => $message){
                foreach($message as $messages_key => $messages){
                    if($message['user'] === intVal($friendid)){

                        $current_friends[0][$message_key]['approval_status'] = 'approved';
                    }               
                }       
             }
        } else {
    
         
                    if($current_friends[0]['user'] === intVal($friendid)){

                        $current_friends[0]['approval_status'] = 'approved';      
            }         
        }

        update_user_meta( $current_user->ID, 'myfriends', $current_friends[0] );
        
        $approved_friends = get_user_meta($friendid, 'myfriends', false);

        $approver_friends = array() !== $approved_friends ? $approved_friends : array(0 => array());

        if(!is_array($approver_friends[0])){

            $new_friends = array_merge($approver_friends[0], array('user' => $current_user->ID, 'approval_status' => 'approved'));

            update_user_meta($friendid, 'myfriends', array($new_friends));
        } else {
            $new_friends = array_merge($approver_friends[0], array(array('user' => $current_user->ID, 'approval_status' => 'approved')));

            update_user_meta($friendid, 'myfriends', $new_friends);
        }
        
    }
    
    public function prefix_ajax_approve_endorsement() {
        
        global $current_user;
        
        $endorseid = isset($_POST['endorseid']) ? $_POST['endorseid'] : "";
        
        $current_endorsements = get_user_meta($current_user->ID, 'my_endorsements');
        
        if(is_array($current_endorsements[0][0])){
            foreach($current_endorsements[0] as $message_key => $message){
                foreach($message as $messages_key => $messages){
                    if($message['endorseid'] === $messageid){

                        $current_endorsements[0][$message_key]['approval_status'] = 'approved';
                    }               
                }       
             }
        } else {
    
         
                    if($current_endorsements[0]['endorseid'] === $messageid){

                        $current_endorsements[0]['approval_status'] = 'approved';
                        
                                  
            }         
        }

        update_user_meta( $current_user->ID, 'my_endorsements', $current_endorsements[0] ); 
        
    }
    
    public function prefix_ajax_add_userendorse() {
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        
        $usermessage = isset($_POST['userendorse']) ? $_POST['userendorse'] : "";
        $msgid = isset($_POST['endorseusrid']) ? $_POST['endorseusrid'] : "";
        $messageid = isset($_POST['endorseid']) ? $_POST['endorseid'] : "";
        $c_date = time();
        
        $usermessage_id = array('endorsementid' => $messageid, 'endorsement_date' => $c_date, 'user' => $current_user->ID, 'endorsement' => $usermessage, 'approval_status' => 'pending'); 
        
        $current_message_array = get_user_meta($msgid, 'my_endorsements', false);
        
        $current_messages = isset($current_message_array) ? $current_message_array : "";
       
        if(NULL === $current_messages[0]){
    
        update_user_meta( $msgid, 'my_endorsements', $usermessage_id );
            
        } elseif( 1 === count($current_messages) && 5 === count($current_messages[0]) && NULL === $current_messages[0][0])  {
            
        $new_message_array = array_merge($current_messages, array($usermessage_id));
            
        update_user_meta( $msgid, 'my_endorsements', $new_message_array );
        } elseif( 2 <= count($current_messages[0]) ){
            
        $new_message_array = array_merge($current_messages[0], array($usermessage_id));
            
        update_user_meta( $msgid, 'my_endorsements', $new_message_array ); 
        }
        
        
        echo "success!";
    }
    
    public function prefix_ajax_remove_end() {
        
        global $current_user;
        
        $rmendid = isset($_POST['rmendid']) ? $_POST['rmendid'] : "";
        
        $current_message_array = get_user_meta($current_user->ID, 'my_endorsements', false);
        
        $current_messages = isset($current_message_array) ? $current_message_array : "";

        if(NULL === $current_messages[0][0]){
                
                delete_user_meta( $current_user->ID, 'my_endorsements' );     
            
        } elseif( 2 === count($current_messages[0]) ){
  
        foreach($current_messages[0] as $endorsement){
            
            if($endorsement['endorsementid'] !== $rmendid ){
                
                $new_message_array[] = $endorsement;
            }
        } 
            
        update_user_meta( $current_user->ID, 'my_endorsements', $new_message_array[0] );
        } elseif( 2 < count($current_messages[0]) ){

        foreach($current_messages[0] as $endorsement){
            
            if($endorsement['endorsementid'] !== $rmendid ){
                
                $new_message_array[] = $endorsement;
            }
        } 
            
        update_user_meta( $current_user->ID, 'my_endorsements', $new_message_array );
        }
        
    }
    
    public function prefix_ajax_add_read_status(){
        
        global $current_user;
        
        $messageid = isset($_POST['message_id']) ? $_POST['message_id'] : "";

        $current_messages = get_user_meta($current_user->ID, 'my_messages', false);
        
        if(is_array($current_messages[0][0])){
            foreach($current_messages[0] as $message_key => $message){
                foreach($message as $messages_key => $messages){
                    if($message['messageid'] === $messageid){

                        $current_messages[0][$message_key]['read_status'] = 'read';
                    }               
                }       
             }
        } else {
    
         
                    if($current_messages[0]['messageid'] === $messageid){

                        $current_messages[0]['read_status'] = 'read';
                        
                                  
            }         
        }

        update_user_meta( $current_user->ID, 'my_messages', $current_messages[0] ); 
    }
    
    public function prefix_ajax_remove_decmember() {
        
        global $current_user;
        
        $user_role = $current_user->roles[0];
        
        $rmdecid = isset($_POST['rmdecid']) ? $_POST['rmdecid'] : "";
        $rmtype = isset($_POST['rmtype']) ? $_POST['rmtype'] : "";
        
        $current_dec_members = get_user_meta($current_user->ID, 'mydec', false);
        $current_biz = get_user_meta($rmdecid, 'mybusinesses', false);
        $client_pro = get_user_meta($rmdecid, 'mydec', false);
        $client_likes = get_user_meta($current_user->ID, 'mylikes', false);
        $client_friends = get_user_meta($current_user->ID, 'myfriends', false);
        $client_frienders = get_user_meta($rmdecid, 'myfriends', false);
        $biz_likers = get_user_meta($rmdecid, 'mylikers', false);
        
        $new_array = array();

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
        
        if($user_role === 'client' && $rmtype === "follow"){
            
            $new_follower = array();

            if(isset($client_pro[0])){
                foreach( $client_follower[0] as $c_followers => $c_follower){

                    if(intval($c_follower) !== intval($current_user->ID)){

                        $new_follower[] = $c_pro;
                    }
                }

                update_user_meta($rmdecid, 'mydec', $new_follower);
            }
        }elseif($user_role === 'client' && $rmtype === "like"){
            
            $new_like = array();

            if(isset($client_likes[0])){
                foreach( $client_likes[0] as $c_likes => $c_like){

                    if(intval($c_like) !== intval($rmdecid)){

                        $new_like[] = $c_like;
                    }
                }

                update_user_meta($current_user->ID, 'mylikes', $new_like);
            }
            
            
            $new_liker = array();

            if(isset($biz_likers[0])){
                foreach( $biz_likers[0] as $b_likers => $b_liker){

                    if(intval($b_liker) !== intval($current_user->ID)){

                        $new_liker[] = $b_liker;
                    }
                }

                update_user_meta($rmdecid, 'mylikers', $new_liker);
            }
        }elseif($user_role === 'client' && $rmtype === "friend"){
            
            $new_friend = array();

            if(isset($client_friends[0])){
                foreach( $client_friends[0] as $c_friends){

                    if(intval($c_friends['user']) !== intval($rmdecid)){

                        $new_friend[] = $c_friends;
                    }
                }

                update_user_meta($current_user->ID, 'myfriends', $new_friend);
            }
            
            
            $new_friender = array();

            if(isset($client_frienders[0])){
                foreach( $client_frienders[0] as $client_friender){

                    if(intval($client_friender['user']) !== intval($current_user->ID)){

                        $new_friender[] = $client_friender;
                    }
                }

                update_user_meta($rmdecid, 'myfriends', $new_friender);
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
       
            $new_pro_biz = array();

            if(isset($current_businesses[0])){
                foreach( $current_businesses[0] as $c_bizs => $c_biz){

                    if(intval($c_biz) !== intval($rmdecid)){

                        $new_pro_biz[] = $c_biz;
                    }
                }

                update_user_meta($current_user->ID, 'mybusinesses', $new_pro_biz);
            }
            var_dump($new_pro_biz);
            $new_biz_pro = array();

            if(isset($current_pros[0])){
                foreach( $current_pros[0] as $c_bizs => $c_biz){

                    if(intval($c_biz) !== intval($current_user->ID)){

                        $new_biz_pro[] = $c_biz;
                    }
                }

                update_user_meta($current_user->ID, 'mybusinesses', $new_biz_pro);
        }
    }
}

$decstatus = new Decstatus();