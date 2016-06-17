<?php

if(isset($_SESSION['user']) && $_SESSION['user'] !== ""){
    $username = $_SESSION['user'];
    $password = $_SESSION['pass'];
    
    $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = true;
 
            wp_signon( $creds, false );
    
   if(is_page('my-profile')){ echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>'; }
    
    $string = '<script type="text/javascript">';
            $string .= 'jQuery( document ).ready(function() {if(jQuery(\'.logged-in\').is(\':visible\')){ return;} window.location.reload();});';
            $string .= '</script>';
    echo $string;
    
    session_destroy();
}