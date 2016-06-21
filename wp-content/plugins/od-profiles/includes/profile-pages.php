<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Profile_Pages
 */
class Profile_Pages {
    
    public function __construct(){
        
        add_action('init', array($this, 'custom_rewrite_tag'), 10, 0);
        add_action('init', array($this, 'custom_rewrite_rule'), 10, 0);
    }
    
    public function custom_rewrite_tag() {
    
        add_rewrite_tag('%professional%', '([^&]+)');
        add_rewrite_tag('%business%', '([^&]+)');
    }

    public function custom_rewrite_rule() {    
        
        add_rewrite_rule('^professionals/([^/]*)/?','index.php?page_id=22&professional=$matches[1]','top');
        add_rewrite_rule('^businesses/([^/]*)/?','index.php?page_id=27&business=$matches[1]','top');
    }
}

$profile_pages = new Profile_Pages();