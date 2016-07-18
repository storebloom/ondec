<?php
session_start();
/**
 * Template Name: Business Map Template
 *
 * This is the template used to house default pages with no sidebar or title.
 *
 *
 * @package ondec_custom_theme
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
<?php if(isset($_POST['search_val'])){
    
    od_map_display($_POST['search_val']);
}
?>
            <?php if (have_posts()) : while (have_posts()) : the_post();?>
            Find Businesses Near You:
                <form method="post" id="search-address">
<input name="search_val" type="text" placeholder="enter your address here" >
    <input name="submit" type="submit" value="search" >
</form>
                <?php the_content(); ?>
            
            <?php endwhile; endif; ?>
            
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
   