<?php 

if(isset($_GET['user']) && $_GET['user'] !== ""){
    $username = $_GET['user'];
    $password = $_GET['pass'];
    
    $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = true;
 
            wp_signon( $creds, false );
    $string = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript">';
            $string .= 'jQuery( document ).ready(function() {if(jQuery(\'.logged-in\').is(\':visible\')){ return;} window.location.reload();});';
            $string .= '</script>';
    echo $string;
}