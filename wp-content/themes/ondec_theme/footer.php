<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ondec_custom_theme
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
        <div class="footer-content-wrapper">
            <div class="footermenu">
            <?php wp_nav_menu( array( 'footer1' => 'footer-menu' ) ); ?>
            </div>
            <div class="site-info">
                Ondec © <?php echo date( 'Y' ); ?>
            </div><!-- .site-info -->
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
