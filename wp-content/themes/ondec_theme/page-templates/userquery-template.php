<?php
/**
 * Template Name: User Query Template
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */

global $wpdb;
// The Query
$user_query = new WP_User_Query( array(	'role'  =>	'professional' ) );

// User Loop
if ( ! empty( $user_query->results ) ) {
    
	foreach ( $user_query->results as $user ) {
		if($user !== ""){
            
            $current_user_query .= $user->display_name;          
        }
	}
    
    $stripped_array = "array(" . implode($current_user_query, ',') . ")";
    
    $new_data = (array)$stripped_array;
    
} else {
	echo 'No users found.';
}
