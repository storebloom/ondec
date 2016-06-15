<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'Professional Registration' ) )
	return;

/**
 * Professional Registration
 */
class Professional Registration {
    
    public function add_professional_registration_form(){
        
        include_once('forms/professional-reg-view.php');
        
    }
    
    
    add_shortcode('professional-registration-form', 'add_professional_registration_form')
    
}

$professional-registration = new Professional Registration();