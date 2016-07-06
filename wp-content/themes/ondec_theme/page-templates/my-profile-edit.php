<?php
session_start();

global $current_user, $wp_roles, $profile_pages;
/**
 * Template Name: My Profile
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */

$user_role = isset($current_user->roles[0]) ? $current_user->roles[0] : "";
echo '<style type="text/css">

.messageWrap { /* The div that shows/hides. */
    display:none; /* starts out hidden */
    z-index:40001; /* High z-index to ensure it appears above all content */
}

.messageOverlay { /* Shades out background when selector is active */
    position:fixed;
    width:100%;
    height:100%;
    background-color:black;
    opacity:.5;
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; /* IE transparency */
    filter:alpha(opacity=50); /* More IE transparency */
    z-index:40001;
    top:0px;
    left:0px;
}

.vertical-offset { /* Fixed position to provide the vertical offset */
    position:fixed;
    top:30%;
    width:100%;
    z-index:40002; /* ensures box appears above overlay */  
}
    
.messageBox { /* The actual box, centered in the fixed-position div */
    width:405px; /* Whatever width you want the box to be */
    position:relative;
    margin:0 auto;
    /* Everything below is just visual styling */
    background-color:white;
    padding:10px;
    border:1px solid black;
}
</style>';

switch($user_role){
        
    case 'professional':
        
    require_once('user-edit-templates/professional-profile-edit.php');
    
    break;
    
    case 'business';
    
    require_once('user-edit-templates/business-profile-edit.php');
    
    break;
    
    case 'client';
    
    require_once('user-edit-templates/client-profile-edit.php');
    
    break;
        
    case 'administrator';
        
    require_once('user-edit-templates/professional-profile-edit.php');
    
    break;
        
    case '';
        
    require_once('user-edit-templates/professional-profile-edit.php');
        
    
}