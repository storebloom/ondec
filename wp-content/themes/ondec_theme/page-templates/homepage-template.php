<?php
/**
 * Template Name: Homepage Template
 *
 * This is the template used to house the homepage content including any spealized additions solely used on the homepage.
 *
 *
 * @package ondec_custom_theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post();?>
            
                <?php the_content(); ?>
            
            <?php endwhile; endif; ?>
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
