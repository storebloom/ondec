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
        add_shortcode('od-app-settings',        array($this, 'od_app_settings'));
        add_action( 'wp_ajax_add_app',          array($this, 'prefix_ajax_add_app') );
        add_action( 'wp_ajax_nopriv_add_app',   array($this, 'prefix_ajax_add_app') );
        add_action( 'wp_ajax_define_calendar',          array($this, 'define_calendar') );
        add_action( 'wp_ajax_nopriv_define_calendar',   array($this, 'define_calendar') );
        add_action( 'wp_ajax_cancel_app',          array($this, 'cancel_app') );
        add_action( 'wp_ajax_nopriv_cancel_app',   array($this, 'cancel_app') );
        add_action( 'wp_ajax_approve_app',          array($this, 'approve_app') );
        add_action( 'wp_ajax_nopriv_approve_app',   array($this, 'approve_app') );
        add_action( 'wp_ajax_define_profile_calendar',          array($this, 'define_profile_calendar') );
        add_action( 'wp_ajax_nopriv_define_profile_calendar',   array($this, 'define_profile_calendar') );
        add_action( 'wp_ajax_appointment_settings',          array($this, 'appointment_settings') );
        add_action( 'wp_ajax_nopriv_appointment_settings',   array($this, 'appointment_settings') );
    }
    
    public function od_app_settings(){
        
        global $current_user;
        
        $current_app_settings = null !== get_user_meta($current_user->ID, 'app_settings', true) ? get_user_meta($current_user->ID, 'app_settings', true) : "";
        $html = "";
        $enabled = isset($current_app_settings['enabled']) ? $current_app_settings['enabled'] : 'false';
        $count = isset($current_app_settings['count']) ? $current_app_settings['count'] : "";
        $duration = isset($current_app_settings['duration']) ? $current_app_settings['duration'] : "";
        $start = isset($current_app_settings['start']) ? $current_app_settings['start'] : "";
      
        if($enabled === 'true'){
            
            $enabled = 'checked="checked"';
        } elseif($enabled === 'false'){
            
            $enabled = '';
        }

        $html .= '<div style="display:none;" class="setting-success">Update successful.</div><div class="app-setting-wrapper">';
        $html .= '<input type="checkbox" id="enable-appointments" '.$enabled.'><label for="enable-appointments">Check to enable customer appointments on your profile.</label>';
        $html .= '<input id="app-settings-count" value="'.$count.'" class="settings-input" type="number"><label for="app-settings-count">Insert the amount of appointments you would like to display per day.</label>';
        $html .= '<input id="app-settings-duration" value="'.$duration.'" class="settings-input" type="number" max="24"><label for="app-settings-duration">Insert the length of each appointment. Max 24 Ex:  2 = 2hours, 1 = 1hour, .5 = 30min</label>';
        $html .= self::get_time_array(intval($start));
        $html .= '<label for="app-settings-start">Insert the time your first appointment will begin.  Ex: 9 = 9AM, 17 = 9PM</label>';
        $html .= '<button id="submit-appointment-settings">Submit</button></div>';
        
        return $html;
    }
    
    public static function get_time_array($start){
        
        $time_array = array(
            1 => '1:00AM', 
            2 => '2:00AM', 
            3 => '3:00AM',
            4 => '4:00AM',
            5 => '5:00AM',
            6 => '6:00AM',
            7 => '7:00AM',
            8 => '8:00AM',
            9 => '9:00AM',
            10 => '10:00AM',
            11 => '11:00AM',
            12 => '12:00PM',
            13 => '1:00PM', 
            14 => '2:00PM', 
            15 => '3:00PM',
            16 => '4:00PM',
            17 => '5:00PM',
            18 => '6:00PM',
            19 => '7:00PM',
            20 => '8:00PM',
            21 => '9:00PM',
            22 => '10:00PM',
            23 => '11:00PM',
            24 => '12:00AM'  
        );
        
        $html = '<select id="app-settings-start" class="settings-input">';
        
        foreach($time_array as $num => $time){
            
            if($num === $start){
                
                $html .= '<option value="'.$num.'" selected="selected">'.$time.'</option>';
            }else{
                $html .= '<option value="'.$num.'">'.$time.'</option>';
            }
        }
        
        $html .= '</select>';
        
        return $html;
    }
    
    public function approve_app(){
        
        global $current_user;
        
        $app_day = isset($_POST['app_day']) ? $_POST['app_day'] : "";
        $app_month = isset($_POST['app_month']) ? $_POST['app_month'] : "";
        $app_year = isset($_POST['app_year']) ? $_POST['app_year'] : "";
        $app_time = isset($_POST['app_time']) ? $_POST['app_time'] : "";
        $app_user = isset($_POST['app_user']) ? $_POST['app_user'] : ""; 
        $current_apps = get_user_meta($current_user->ID, 'my_appointments', true);
        $current_user_apps = get_user_meta($app_user, 'my_appointments', true);
        
        if($current_apps !== ""){
              
            foreach($current_apps[0] as $apps => $app_value){
var_dump($app_year);
                if(intval($apps) === intval($app_year)){
var_dump($apps);
                    foreach($app_value as $month => $month_value){
               
                        if(intval($month) === intval($app_month)){
                     var_dump($month);
                            foreach($month_value as $day => $day_value){
                                
                                if(intval($day) === intVal($app_day)){
var_dump($day);
                                    foreach($day_value as $user => $apps){
                                
                                        if($apps['appentry'] === $app_time){
                                          
                                            $current_apps[0][$app_year][$app_month][$app_day][$user]['status'] = 'approved';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if($current_user_apps !== ""){
              
            foreach($current_user_apps[0] as $user_apps => $app_user_value){

                if($user_apps === intVal($app_year)){

                    foreach($app_user_value as $month => $month_user_value){
               
                        if(intVal($month) === intVal($app_month)){
                       
                            foreach($month_user_value as $day => $day_user_value){
                                
                                if(intVal($day) === intVal($app_day)){
                                    
                                    foreach($day_value as $user => $apps){
                                  
                                        if($apps['appentry'] === $app_time){
                                            
                                            $current_user_apps[0][$app_year][$app_month][$app_day][$user]['status'] = 'approved';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        var_dump($current_apps);
        update_user_meta($current_user->ID, 'my_appointments', $current_apps);
        update_user_meta($app_user, 'my_appointments', $current_user_apps);
        
    }
    
    public function cancel_app(){
        
        global $current_user;
        
        $app_day = isset($_POST['app_day']) ? $_POST['app_day'] : "";
        $app_month = isset($_POST['app_month']) ? $_POST['app_month'] : "";
        $app_year = isset($_POST['app_year']) ? $_POST['app_year'] : "";
        $app_time = isset($_POST['app_time']) ? $_POST['app_time'] : "";
        $app_user = isset($_POST['app_user']) ? $_POST['app_user'] : "";        
        $current_apps = get_user_meta($current_user->ID, 'my_appointments', true);
        $current_user_apps = get_user_meta($app_user, 'my_appointments', true);
        
        if($current_apps !== ""){
              
            foreach($current_apps[0] as $apps => $app_value){

                if($apps === intval($app_year)){

                    foreach($app_value as $month => $month_value){
               
                        if(intval($month) === intval($app_month)){
                     
                            foreach($month_value as $day => $day_value){
                                
                                if(intval($day) === intVal($app_day)){

                                    foreach($day_value as $user => $apps){
                                
                                        if($apps['appentry'] === $app_time){
                                          
                                            unset($current_apps[0][$app_year][$app_month][$app_day][$user]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if($current_user_apps !== ""){
              
            foreach($current_user_apps[0] as $user_apps => $app_user_value){

                if($user_apps === intVal($app_year)){

                    foreach($app_user_value as $month => $month_user_value){
               
                        if(intVal($month) === intVal($app_month)){
                       
                            foreach($month_user_value as $day => $day_user_value){
                                
                                if(intVal($day) === intVal($app_day)){
                                    
                                    foreach($day_value as $user => $apps){
                                  
                                        if($apps['appentry'] === $app_time){
                                            
                                            unset($current_user_apps[0][$app_year][$app_month][$app_day][$user]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        update_user_meta($current_user->ID, 'my_appointments', $current_apps);
        update_user_meta($app_user, 'my_appointments', $current_user_apps);
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
                                    'appentry' => $appentry,
                                    'status' => 'pending'
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
                                    'appentry' => $appentry,
                                    'status' => 'pending'
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
        
        wp_enqueue_script( 'app-main', '/wp-content/plugins/od-appointments/js/app-main.js', array( 'jquery' ), '1.0.0', true );
        
        global $current_user;
        
        $selected_month = isset($_POST['app_month']) ? $_POST['app_month'] : "";
        $selected_year = isset($_POST['app_year']) ? $_POST['app_year'] : "";
        
        date_default_timezone_set('America/Los_Angeles');
       
        $day = strtotime($selected_day);
     
        $current_appointments = "" !== get_user_meta($current_user->ID, 'my_appointments', true) ? get_user_meta($current_user->ID, 'my_appointments', true) : array();
        
        $app_year = "" !== $selected_year ? $selected_year : date('Y', $day);
        $app_month = "" !== $selected_month ? $selected_month : date('m', $day);
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
        <div class="year-'.$app_year.'">
                        <div class="calendar-month month-'.$app_month.'">';
        
                            for( $i= 1 ; $i <= $day_count ; $i++ ) {

                                $calendar .= '
                                <div class="calendar-day day-'.$i.'"><p>'.$i.'</p>
                                
                                    <div class="app-wrappers">';
                                
                                if(isset($current_appointments[0][$app_year][$app_month]) && $current_appointments !== array() && is_array($current_appointments)):
                             
                                    foreach($current_appointments[0][$app_year][$app_month] as $app_day => $apps) : 
                                    
                                        if(intVal($app_day) === $i) :

                                            foreach($apps as $app){

                                                $app_user_info = get_userdata($app['userid']);
                                                
                                                $pending = "";
                                                
                                                if(isset($app['status']) && $app['status'] === 'pending'){
                                                    
                                                    $pending = '<button app-year="'.$app_year.'" app-month="'.$app_month.'" app-time="'.$app['appentry'].'" app-day="'.$app_day.'" app-user="'.$app_user_info->ID.'" num="'.$i.'" class="approve-app">pending</button>';
                                                }

                                                $calendar .= '<li id="client-app-'.$app_day.'-'.$i.'" class="app_time"><span app-year="'.$app_year.'" app-month="'.$app_month.'" app-time="'.$app['appentry'].'" app-day="'.$app_day.'" app-user="'.$app_user_info->ID.'" num="'.$i.'" class="cancel-app">X</span>'.$pending.'

                                                <a href="/clients/'.$app_user_info->user_login.'">'. $app_user_info->display_name . ': ' . $app['appentry'].'</a>
                                            </li>';
                                            }
                                        endif;
                                    endforeach;     
                                endif;
                                $calendar .= '</div></div>';  
                            }

        $calendar .= '         
                        </div>
                    </div>';    
       
        echo $calendar;        
    }
    
    public static function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
    }
    
    public static function are_there_future_apps($apps){
        
        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');
        
        if(is_array($apps)){
            
            foreach($apps as $app1 => $month_array){
                
                if(is_array($month_array) && $app1 >= $current_year){
                    
                    foreach($month_array as $app2 => $day_array){
                        
                       if(is_array($day_array) && $app2 >= $current_month){
                           
                           foreach($day_array as $day => $appointment){
                               
                               if(is_array($appointment) && array() !== $appointment && $day >= $current_day){
                                   return true;
                               }
                           }
                       } 
                    }
                }
            }
        }
            
        return false;
    }
    
    public static function get_all_appointments(){
        
        wp_enqueue_script( 'app-main', '/wp-content/plugins/od-appointments/js/app-main.js', array( 'jquery' ), '1.0.0', true );
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        
        $current_appointments = get_user_meta($current_user->ID, 'my_appointments', true);
        
        $current_appointments = isset($current_appointments[0]) ? $current_appointments[0] : "";
        
        $current_year = date('Y');
        $current_month = date('m');
        $current_day = date('d');
        
        if($current_appointments !== ""){
        $future_apps = self::are_there_future_apps($current_appointments);

        if(isset($current_appointments) && $current_appointments !== array() && is_array($current_appointments) && $future_apps){
            
            $html = '<div class="client-appointments"><ul class="app-year">';
                             
            foreach($current_appointments as $year => $month_array){
                
                if($year >= $current_year){
            
                $i = 0;

                $html .= '<li>'.$year.': <ul class="app-month">';

                    foreach($month_array as $month => $day_array){
                        
                        if($month >= $current_month){
                            
                            $monthNum  = $month;
                            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                            $month = $dateObj->format('F');

                            $html .= '<li>'.$month.': <ul class="app-day">';

                            foreach($day_array as $day => $apps){

                                if($day >= $current_day && $apps !== array()){
                    
                                    $html .= '<li>'.self::ordinal($day).': <ul>';

                                    foreach($apps as $app){
                                        
                                        $app_user_info = get_userdata($app['userid']);
                                        
                                        $approved = true;
                                        
                                        if(isset($app['status']) && $app['status'] === 'pending'){
                                            
                                            $approved = false; 
                                        }
                                        
                                        if($approved){
                                            $html .= '<li id="client-app-'.$day.'-'.$i.'" class="app_time"><span app-year="'.$year.'" app-month="'.$monthNum.'" app-time="'.$app['appentry'].'" app-day="'.$day.'" app-user="'.$app_user_info->ID.'" num="'.$i.'" class="cancel-app">X</span>

                                                <a href="/professionals/'.$app_user_info->user_login.'">'. $app_user_info->display_name . ': ' . $app['appentry'].'</a>
                                            </li>';
                                        }
                                    }

                                    $html .= '</ul></li>';
                                }
                            }

                            $html .= '</ul></li>';
                        }

                        $html .= '</ul></li>';
                    }

                 $i++;
                }
            }
            
            $html .= '</ul></div>';

        }else{
            $html = '<p>No appointments scheduled.</p>';
        }
        
        return $html; 
            
        }
    }
    
    public static function get_todays_appointments(){
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        
        $current_appointments = get_user_meta($current_user->ID, 'my_appointments', true);
        
        $app_year = date('Y');
        $app_month = date('m');
        $app_day = date('d');
        
        if(isset($current_appointments[0][$app_year][$app_month][$app_day]) && $current_appointments !== array() && is_array($current_appointments)){
            
            $html = '<ul>';
                             
            foreach($current_appointments[0][$app_year][$app_month] as $apps => $app_val) : 
            
                $i = 0;
      
                if(intval($apps) === intval($app_day)){

                    foreach($app_val as $app){

                        $app_user_info = get_userdata($app['userid']);
                        
                        $pending = "";
                                                
                        if(isset($app['status']) && $app['status'] === 'pending'){

                            $pending = '<button app-year="'.$app_year.'" app-month="'.$app_month.'" app-time="'.$app['appentry'].'" app-day="'.$app_day.'" app-user="'.$app_user_info->ID.'" num="'.$i.'" class="approve-app">pending</button>';
                        }

                        $html .= '<li id="client-app-'.$app_day.'-'.$i.'" class="app_time"><span app-time="'.$app['appentry'].'" app-day="'.$app_day.'" app-user="'.$app_user_info->ID.'" num="'.$i.'" class="cancel-app">X</span>'.$pending.'

                        <a href="/clients/'.$app_user_info->user_login.'">'. $app_user_info->display_name . ': ' . $app['appentry'].'</a>
                    </li>';

                    $i++;
                    }
                }
            
            endforeach;  
            
            $html .= '</ul>';
        }else{
            $html = '<p>No appointments for today.</p>';
        }
        
        return $html;
    }
    
    public function appointment_settings(){
        
        global $current_user;

        $app_count = isset($_POST['count']) ? $_POST['count'] : "";
        $appointment_duration = isset($_POST['duration']) ? $_POST['duration'] : "";
        $start_time = isset($_POST['start']) ? $_POST['start'] : "";
        $enabled = isset($_POST['enabled']) ? $_POST['enabled'] : "";
        $new_app_settings = array('enabled' => $enabled, 'count' => $app_count, 'duration' => $appointment_duration, 'start' => $start_time);
   
        update_user_meta($current_user->ID, 'app_settings', $new_app_settings);
    }
        
    public static function define_calendar($app_user_id){
        
        wp_enqueue_script( 'app-main', '/wp-content/plugins/od-appointments/js/app-main.js', array( 'jquery' ), '1.0.0', true );
        
        global $current_user;
        
        date_default_timezone_set('America/Los_Angeles');
        $appuserid = isset($_POST['appid']) ? $_POST['appid'] : $app_user_id;
        $day = isset($_POST['app_day']) ? $_POST['app_day'] : "now";
        $day = strtotime($day);
        $user_app_settings = get_user_meta($appuserid, 'app_settings', true);
        $app_count = isset($user_app_settings['count']) ? $user_app_settings['count'] : "";
        $appointment_duration = isset($user_app_settings['duration']) ? $user_app_settings['duration'] : "";
        $start_time = isset($user_app_settings['start']) ? $user_app_settings['start'] : "";
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