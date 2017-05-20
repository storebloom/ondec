<?php
/**
 * Auto Quote Wrapper Template
 *
 * The template wrapper the auto quote shortcode.
 *
 * @package AutoQuote
 */
?>

<div style="color: <?php echo esc_attr( $color ); ?>; font-family: <?php echo esc_attr( $font ); ?>;" class="quote-<?php echo esc_attr( $size ); ?> quote-wrapper">
	<div class="quote-quote"></div>
	<p class="quote-author"></p>
</div>
