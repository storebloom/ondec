<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * OD_Appointments
 */


class OD_Appointments {

    public function __construct(){
    
        add_shortcode('od-appointment-options', array($this, 'od_appointment_options') );
        add_shortcode('od-appointments',        array($this, 'define_profile_calendar') );
        add_shortcode('od-app-front',           array($this, 'od_app_front') );
        add_action( 'wp_ajax_add_app',          array($this, 'prefix_ajax_add_app') );
        add_action( 'wp_ajax_nopriv_add_app',   array($this, 'prefix_ajax_add_app') );
        add_action( 'wp_ajax_define_calendar',          array($this, 'define_calendar') );
        add_action( 'wp_ajax_nopriv_define_calendar',   array($this, 'define_calendar') );
        add_action( 'wp_footer',               array($this, 'enqueue_app_script'));
    }
    
    public function enqueue_app_script(){
        
        wp_enqueue_script( 'app-main', '/wp-content/plugins/od-appointments/js/app-main.js', array( 'jquery' ), '1.0.0', true );
    }
    
    public function prefix_ajax_add_app(){
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        
        $appid = isset($_POST['appid']) ? $_POST['appid'] : "";
        $app_time = isset($_POST['app_day']) ? $_POST['app_day'] : "";
        
        $app_year = date('Y', $app_time);
        $app_month = date('m', $app_time);
        $app_day = date('d', $app_time);
        
        $appentry = isset($_POST['appentry']) ? $_POST['appentry'] : "";
        
        $current_apps = get_user_meta($appid, 'my_appointments', true);
        
        $current_user_apps = get_user_meta($current_user->ID, 'my_appointments', true);
        
        $new_app = 
            array(
                array(
                    $app_year => array(
                        $app_month => array(
                            $app_day => array(
                                array(
                                    'userid' => $current_user->ID, 
                                    'appentry' => $appentry
                                )
                            )
                        )
                    )
                )
            );
        
        $new_user_app = 
            array(
                array(
                    $app_year => array(
                        $app_month => array(
                            $app_day => array(
                                array(
                                    'userid' => $appid, 
                                    'appentry' => $appentry
                                )
                            )
                        )
                    )
                )
            );
        
        if($current_apps !== "" && self::app_not_in_list($appid, $app_year, $app_month, $app_day, $app_entry)){
              
            foreach($current_apps[0] as $apps => $app_value){

                if($apps === intVal($app_year)){

                    foreach($app_value as $month => $month_value){
               
                        if(intVal($month) === intVal($app_month)){
                       
                            foreach($month_value as $day => $day_value){
                                
                                if(intVal($day) === intVal($app_day)){

                                    $new_app[0][$app_year][$app_month][$app_day] = array_merge($new_app[0][$app_year][$app_month][$app_day], $current_apps[0][$app_year][$app_month][$app_day]);
                                    
                                } else {
                                    
                                     $new_app[0][$app_year][$app_month] = $new_app[0][$app_year][$app_month] + $current_apps[0][$app_year][$app_month];
                                }
                            }
                        } else {
                            $new_app[0][$app_year] =  $new_app[0][$app_year] + $current_apps[0][$app_year];
                        }
                    }
                } else {
                    
                    $new_app[0] = $new_app[0] + $current_apps[0];
                }
            }
        }
        
        if($current_user_apps !== "" && self::app_not_in_list($current_user->ID, $app_year, $app_month, $app_day, $app_entry)){
              
            foreach($current_user_apps[0] as $user_apps => $app_user_value){

                if($user_apps === intVal($app_year)){

                    foreach($app_user_value as $month => $month_user_value){
               
                        if(intVal($month) === intVal($app_month)){
                       
                            foreach($month_user_value as $day => $day_user_value){
                                
                                if(intVal($day) === intVal($app_day)){

                                    $new_user_app[0][$app_year][$app_month][$app_day] = array_merge($new_user_app[0][$app_year][$app_month][$app_day], $current_user_apps[0][$app_year][$app_month][$app_day]);
                                    
                                } else {
                                    
                                     $new_user_app[0][$app_year][$app_month] = $new_user_app[0][$app_year][$app_month] + $current_user_apps[0][$app_year][$app_month];
                                }
                            }
                        } else {
                            $new_user_app[0][$app_year] =  $new_user_app[0][$app_year] + $current_user_apps[0][$app_year];
                        }
                    }
                } else {
                    
                    $new_user_app[0] = $new_user_app[0] + $current_user_apps[0];
                }
            }
        }
        
        update_user_meta($appid, 'my_appointments', $new_app);
        update_user_meta($current_user->ID, 'my_appointments', $new_user_app);
    }
    
    public function od_appointment_options(){
        
       include_once( '\wp-content\plugins\od-appointments\includes\appointment-controller.php');
    }
    
    public function od_appointments(){
        
       global $current_user;
        
       $available_apps = get_user_meta($current_user->ID, 'current_appointments');
        
       include_once( '\wp-content\plugins\od-appointments\includes\my-appointments.php');
    }
    
    public function od_app_front(){
         
       include_once( '\wp-content\plugins\od-appointments\includes\my-app-front.php');
    }
    
    public static function is_app_available($app, $userid, $year, $month, $day){
        
        $current_appointments = get_user_meta($userid, 'my_appointments', true);

        if( null !== $current_appointments && "" !== $current_appointments && is_array($current_appointments)){
            
            foreach($current_appointments as $current_apps){
                if(isset($current_apps[$year][$month][$day])) {
                    foreach($current_apps[$year][$month][$day] as $current_pro_apps){

                        if($app === $current_pro_apps['appentry']){

                            return false;
                        } 
                    }
                }
            }   
        } 
        return true;
    }
    
    public static function app_not_in_list($userid, $app_year, $app_month, $app_day, $app_entry){
        
        global $current_user;
        
        $current_user_apps = get_user_meta($userid, 'my_appointments', true);

        foreach($current_user_apps as $current_apps){
         
            foreach($current_apps[$app_year][$app_month][$app_day] as $current_app){
                   
                if($current_app['appentry'] === $app_entry){
                    
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public static function define_profile_calendar($selected_day = "now"){
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
       
        $day = strtotime($selected_day);
     
        $current_appointments = "" !== get_user_meta($current_user->ID, 'my_appointments', true) ? get_user_meta($current_user->ID, 'my_appointments', true) : array();
        
        $app_year = date('Y', $day);
        $app_month = date('m', $day);
        
        $this_year = date('Y');
        $this_month = date('m');
        
        $month_check = array(1, 3, 5, 7, 9, 11);
        
        $year_check = array(2016, 2020, 2024, 2028, 2032, 2036, 2040);
        
        if(array_search($app_month, $month_check)){
            
            $day_count = '30';
        } elseif($app_month === '02' ){
            
            $day_count = '28';
        } elseif ($app_month === '02' && array_search($app_year, $year_check)) {
            
            $day_count = '29';
        }else{
            
            $day_count = '31';
        }
        
        $app_year_minus = intval($app_year) - 1;
        $app_year_plus = intval($app_year) + 1;
        $app_month_minus = intval($app_month) - 1;
        $app_month_plus = intval($app_month) + 1;
        $no_past_y = "";
        $no_past_m = "";
        
        if($app_year === $this_year ){
            
            $no_past_y = "disabled=disabled";
            
            if($app_month === $this_month ){
            
                $no_past_m = "disabled=disabled";
            }
        }
            
        $calendar = '
        <div class="year-picker">
            <input id="'.$app_year_minus.'" type="button" class="minus-year" value="-" '.$no_past_y.' > 
                '.$app_year.' 
            <input id="'.$app_year_plus.'" type="button" class="plus-year" value="+" />
            <input id="'.$app_month_minus.'" type="button" class="minus-month" value="-" '.$no_past_m.'>
            '.$app_month.'
            <input id="'.$app_month_plus.'" type="button" class="plus-month" value="+" />
        </div>
        
        <div class="calendar-wrapper year-'.$app_year.'">
                        <div class="calendar-month month-'.$app_month.'">';

                            for( $i= 1 ; $i <= $day_count ; $i++ ) {

                                $calendar .= '
                                <div class="calendar-day day-'.$i.'"><p>'.$i.'</p>';
                                
                                if(isset($current_appointments[0][$app_year][$app_month]) && $current_appointments !== array() && is_array($current_appointments)):
                             
                                    foreach($current_appointments[0][$app_year][$app_month] as $app_day => $apps) : 
                                    
                                        if(intVal($app_day) === $i) :

                                            foreach($apps as $app){

                                                $app_user_info = get_userdata($app['userid']);

                                                $calendar .= '<li class="app_time">

                                                <a href="/clients/'.$app_user_info->user_login.'">'. $app_user_info->display_name . ': ' . $app['appentry'].'</a>
                                            </li>';
                                            }
                                        endif;
                                    endforeach;     
                                endif;
                                $calendar .= '</div>';  
                            }

        $calendar .= '        
                        </div>
                    </div>';    
       
        return $calendar;        
    }
        

    
    public static function define_calendar($app_user_id){
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        
        $app_count = 8;
        $appointment_duration = 2;
        $start_time = 9;
        $appuserid = isset($_POST['appid']) ? $_POST['appid'] : $app_user_id;
        $day = isset($_POST['app_day']) ? $_POST['app_day'] : "now";
        $day = strtotime($day);
     
        $current_appointments = get_user_meta($appuserid, 'my_appointments', true);
        
        $app_year = date('Y', $day);
        $app_month = date('m', $day);
        $app_day = date('d', $day);
        
        $tod = "am";
        $tod2 = "am";
        $tod3 = "am";
        $tod4 = "am";

        for( $i= 0 ; $i < $app_count ; $i++ ) {
            
            $adder = $i;
            
            switch ($appointment_duration){
                    
                case 2 :
                    $adder = $i + $i;
                break;
                case 3 :
                    $adder = $i + $i + $i;
                break;
                case 4 :
                    $adder = $i + $i + $i + $i;
                break;
                case 5 :
                    $adder = $i + $i + $i + $i + $i;
                break;
                case 6 :
                    $adder = $i + $i + $i + $i + $i + $i;
                break;
                case 7 :
                    $adder = $i + $i + $i + $i + $i + $i + $i;
                break;
                case 8 :
                    $adder = $i + $i + $i + $i + $i + $i + $i + $i;
                break;
                case 9 :
                    $adder = $i + $i + $i + $i + $i + $i + $i + $i + $i;
                break;
                case 10 :
                    $adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
                break;
                case 11 :
                    $adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
                break;
                case 12 :
                    $adder = $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i + $i;
                break;
            }
            
            $loopfirst_time = $start_time + $adder;
            
            $loopsec_time = $loopfirst_time + $appointment_duration;  
            
            if($loopfirst_time >= 12){
                
                if($loopfirst_time > 12){
                    
                    $loopfirst_time = $loopfirst_time - 12;
                }

                $tod = "pm";

                if($loopfirst_time >= 12){

                    if($loopfirst_time > 12){
                    
                        $loopfirst_time = $loopfirst_time - 12;
                    }

                    $tod = "am";

                    if($loopfirst_time >= 12){

                       if($loopfirst_time > 12){
                    
                            $loopfirst_time = $loopfirst_time - 12;
                        }

                        $tod = "pm";
                        
                        if($loopfirst_time >= 12){

                           if($loopfirst_time > 12){

                                $loopfirst_time = $loopfirst_time - 12;
                            }

                            $tod = "am";
                        } 
                    } 
                }
            }
            
            if($loopsec_time >= 12){
                
                if($loopsec_time > 12){
                    
                    $loopsec_time = $loopsec_time - 12;
                }
               
                $tod2 = "pm";
             
                if($loopsec_time >= 12){
                    
                    if($loopsec_time > 12){
                    
                        $loopsec_time = $loopsec_time - 12;
                    }
                    
                    $tod2 = "am";
              
                    if($loopsec_time >= 12){

                        if($loopsec_time > 12){

                            $loopsec_time = $loopsec_time - 12;
                        }  

                        $tod2 = "pm";
                        
                        if($loopsec_time >= 12){

                            if($loopsec_time > 12){

                                $loopsec_time = $loopsec_time - 12;
                            }  

                            $tod2 = "am";
            
                    }
            
                    }
                }
            }

            $the_appointment = $loopfirst_time . ":00".$tod." - " . $loopsec_time . ":00".$tod2;
            
            $app_available = self::is_app_available($the_appointment, $appuserid, $app_year, $app_month, $app_day);

            if($i !== 0 && $app_available){
                
                $app[] = "<li class='app-".$i." single-app'><input value='".$appuserid."' type='hidden' id='".$day."' class='apps-".$i."'><input type='button' id='".$i."' class='app-time' value='".$the_appointment."'></li>";
                
            }
        }
        
        $second_time = $start_time + $appointment_duration;
        
        if($second_time >= 12){
            
            if($second_time > 12){
            
                $second_time = $second_time - 12;    
            }
            
            $tod4 = "pm";
        }
        
        if($start_time >= 12){
                
            if($start_time > 12){
            
                $start_time = $start_time - 12;
            }
            
            $tod3 = "pm";
        }
            
        $first_time = $start_time . ":00".$tod3." - " . $second_time . ":00".$tod4;
        
        $is_app_available = self::is_app_available($first_time, $appuserid, $app_year, $app_month, $app_day);
            
        if($is_app_available){
        $app_start = array("<li class='app-".$i." single-app'><input value='".$appuserid."' type='hidden' id='".$day."' class='apps-".$i."'><input type='button' id='".$i."' class='app-time' value='".$first_time."'></li>");
            
        } else {
            
            $app_start = array();
        }
        
        if(isset($app)){
        
        $app_merged = array_merge($app_start, $app);
                
        echo implode($app_merged);
        } else{
            
            print_r(array());
        }
                
        }
            
    }

$od_appointments = new OD_Appointments();