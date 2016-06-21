<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register_OD_CPT
 */
class Register_OD_CPT {
    
    public function __construct(){
        
        add_action( 'init',                  array($this, 'od_cpt_init' ));
        add_filter( 'post_updated_messages', array($this, 'od_professional_updated_messages' ));
        add_filter( 'post_updated_messages', array($this, 'od_business_updated_messages' ));
        add_action( 'contextual_help',       array($this, 'od_add_help_text'), 10, 3 );
        add_action('admin_head',             array($this, 'od_custom_help_tab'));
        register_activation_hook( __FILE__,  array($this, 'my_rewrite_flush' ));
    }
    
    /**
    * Register a professional post type.
    *
    * @link http://codex.wordpress.org/Function_Reference/register_post_type
    */
    public function od_cpt_init(){
                
        $pro_labels = array(
            'name'               => _x( 'Professionals', 'post type general name', '' ),
            'singular_name'      => _x( 'Professional', 'post type singular name', '' ),
            'menu_name'          => _x( 'Professionals', 'admin menu', '' ),
            'name_admin_bar'     => _x( 'Professional', 'add new on admin bar', '' ),
            'add_new'            => _x( 'Add New', 'professional', '' ),
            'add_new_item'       => __( 'Add New Professional', '' ),
            'new_item'           => __( 'New Professional', '' ),
            'edit_item'          => __( 'Edit Professional', '' ),
            'view_item'          => __( 'View Professional', '' ),
            'all_items'          => __( 'All Professionals', '' ),
            'search_items'       => __( 'Search Professionals', '' ),
            'parent_item_colon'  => __( 'Parent Professionals:', '' ),
            'not_found'          => __( 'No professionals found.', '' ),
            'not_found_in_trash' => __( 'No professionals found in Trash.', '' )
        );

        $pro_args = array(
            'labels'             => $pro_labels,
            'description'        => __( 'Description.', '' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'professional' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );
        
        $biz_labels = array(
            'name'               => _x( 'Businesses', 'post type general name', '' ),
            'singular_name'      => _x( 'Business', 'post type singular name', '' ),
            'menu_name'          => _x( 'Businesses', 'admin menu', '' ),
            'name_admin_bar'     => _x( 'Business', 'add new on admin bar', '' ),
            'add_new'            => _x( 'Add New', 'business', '' ),
            'add_new_item'       => __( 'Add New Business', '' ),
            'new_item'           => __( 'New Business', '' ),
            'edit_item'          => __( 'Edit Business', '' ),
            'view_item'          => __( 'View Business', '' ),
            'all_items'          => __( 'All Businesses', '' ),
            'search_items'       => __( 'Search Businesses', '' ),
            'parent_item_colon'  => __( 'Parent Businesses:', '' ),
            'not_found'          => __( 'No businesses found.', '' ),
            'not_found_in_trash' => __( 'No businesses found in Trash.', '' )
        );

        $biz_args = array(
            'labels'             => $biz_labels,
            'description'        => __( 'Description.', '' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'business' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'professional', $pro_args );
        register_post_type( 'business', $biz_args );
    }
    
        /**
     * Professional update messages.
     *
     * See /wp-admin/edit-form-advanced.php
     *
     * @param array $messages Existing post update messages.
     *
     * @return array Amended post update messages with new CPT update messages.
     */
    public function od_professional_updated_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['professional'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Professional updated.', '' ),
            2  => __( 'Custom field updated.', '' ),
            3  => __( 'Custom field deleted.', '' ),
            4  => __( 'Professional updated.', '' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Professional restored to revision from %s', '' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Professional published.', '' ),
            7  => __( 'Professional saved.', '' ),
            8  => __( 'Professional submitted.', '' ),
            9  => sprintf(
                __( 'Professional scheduled for: <strong>%1$s</strong>.', '' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', '' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Professional draft updated.', '' )
        );

        if ( $post_type_object->publicly_queryable ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View professional', '' ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview professional', '' ) );
            $messages[ $post_type ][8]  .= $preview_link;
            $messages[ $post_type ][10] .= $preview_link;
        }

        return $messages;
    }
    
    public function od_business_updated_messages( $messages ) {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['business'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Business updated.', '' ),
            2  => __( 'Custom field updated.', '' ),
            3  => __( 'Custom field deleted.', '' ),
            4  => __( 'Business updated.', '' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Business restored to revision from %s', '' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Business published.', '' ),
            7  => __( 'Business saved.', '' ),
            8  => __( 'Business submitted.', '' ),
            9  => sprintf(
                __( 'Business scheduled for: <strong>%1$s</strong>.', '' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', '' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Business draft updated.', '' )
        );

        if ( $post_type_object->publicly_queryable ) {
            $permalink = get_permalink( $post->ID );

            $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View professional', '' ) );
            $messages[ $post_type ][1] .= $view_link;
            $messages[ $post_type ][6] .= $view_link;
            $messages[ $post_type ][9] .= $view_link;

            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview professional', '' ) );
            $messages[ $post_type ][8]  .= $preview_link;
            $messages[ $post_type ][10] .= $preview_link;
        }

        return $messages;
    }
    
    //display contextual help for Professionals

    public function od_add_help_text( $contextual_help, $screen_id, $screen ) {
      //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
      if ( 'professional' == $screen->id ) {
        $contextual_help =
          '<p>' . __('Things to remember when adding or editing a professional:', '') . '</p>' .
          '<ul>' .
          '<li>' . __('Specify the correct genre such as Mystery, or Historic.', '') . '</li>' .
          '<li>' . __('Specify the correct writer of the professional.  Remember that the Author module refers to you, the author of this professional review.', '') . '</li>' .
          '</ul>' .
          '<p>' . __('If you want to schedule the professional review to be published in the future:', '') . '</p>' .
          '<ul>' .
          '<li>' . __('Under the Publish module, click on the Edit link next to Publish.', '') . '</li>' .
          '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', '') . '</li>' .
          '</ul>' .
          '<p><strong>' . __('For more information:', '') . '</strong></p>' .
          '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>', '') . '</p>' .
          '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', '') . '</p>' ;
      } elseif ( 'edit-professional' == $screen->id ) {
        $contextual_help =
          '<p>' . __('This is the help screen displaying the table of professionals blah blah blah.', '') . '</p>' ;
      }
      return $contextual_help;
    }
        
    public function od_custom_help_tab() {

      $screen = get_current_screen();

      // Return early if we're not on the professional post type.
      if ( 'professional' != $screen->post_type )
        return;

      // Setup help tab args.
      $args = array(
        'id'      => 'od_help_tab', //unique id for the tab
        'title'   => 'Custom Help', //unique visible title for the tab
        'content' => '<h3>Help Title</h3><p>Help content</p>',  //actual help text
      );

      // Add the help tab.
      $screen->add_help_tab( $args );

    }
    
    public function my_rewrite_flush() {
        // First, we "add" the custom post type via the above written function.
        // Note: "add" is written with quotes, as CPTs don't get added to the DB,
        // They are only referenced in the post_type column with a post entry, 
        // when you add a post of this CPT.
        my_cpt_init();

        // ATTENTION: This is *only* done during plugin activation hook in this example!
        // You should *NEVER EVER* do this on every page load!!
        flush_rewrite_rules();
    }
}

$register_od_cpt = new Register_OD_CPT();