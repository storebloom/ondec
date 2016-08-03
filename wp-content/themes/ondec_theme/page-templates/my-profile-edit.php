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