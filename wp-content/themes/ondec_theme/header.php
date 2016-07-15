<?php

include_once('inc/autologin.php');

if(!is_page('my-profile')){ echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>';}
global $current_user;
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ondec_custom_theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>  
<?php if(is_page('my-profile') && is_user_logged_in()){ echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>';} ?>
<script type="text/javascript">

    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>    
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'ondec_theme' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
            <section>
                <?php
                if ( is_user_logged_in()) : ?>
                      <div class="site-branding">
                <?php else : ?>
                          <div class="site-branding loggedout">
                <?php endif; ?>
                <?php
                if ( !is_user_logged_in()) : ?>
                   <a href="/"><img class="site-logo" src="/wp-content/themes/ondec_theme/img/ondeclogow.png" /></a>
                <?php else : ?>
                <a href="/"><img class="site-logo-logged-in" src="/wp-content/themes/ondec_theme/img/ondeclogow.png" /></a>
                <?php od_user_search(); ?>
                <?php
                endif; ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation" role="navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'ondec_theme' ); ?></button>

                <?php if(is_user_logged_in()): ?>
                <div class="hello-user">Hello <a href="/my-profile"><?php echo $current_user->display_name; ?></a></div> <a class="login-button" href="<?php echo wp_logout_url(esc_url( home_url( '/' ) )); ?>">Logout</a>
                <?php else:
                $site_url = home_url( '/' );

                $page = get_page_by_path( 'my-profile' , OBJECT );

                if(isset($page)){
                    $location = $site_url . 'my-profile';
                } else {
                    $location = $site_url;
                }

                $args = array(
                    'echo'           => true,
                    'redirect'       => $location,
                );

                echo wp_login_form($args);
                ?>
                <?php endif; ?>
            </nav><!-- #site-navigation -->  
        </section>
	</header><!-- #masthead -->

	<div id="content" class="site-content">     