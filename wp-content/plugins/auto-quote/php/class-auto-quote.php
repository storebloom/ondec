<?php
/**
 * Auto Quote.
 *
 * @package AutoQuote
 */
namespace AutoQuote;
/**
 * Auto Quote Class.
 *
 * Holds the logic for he auto quote shortcode.
 *
 * @package AutoQuote
 */
class Auto_Quote {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	public $plugin;
	/**
	 * Class constructor.
	 *
	 * @param object $plugin Plugin class.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		add_shortcode( 'auto-quote', array( $this, 'display_auto_quote' ) );
	}

	/**
	 * Display function for the shortcode.
	 *
	 * @access public
	 */
	public function display_auto_quote() {
		// Enqueue the scripts and styles only if shortcode is used.
		wp_enqueue_style( "{$this->plugin->assets_prefix}-quote" );
		wp_enqueue_script( "{$this->plugin->assets_prefix}-quote" );
		wp_add_inline_script( "{$this->plugin->assets_prefix}-quote", sprintf( 'AutoQuote.boot( %s );',
			wp_json_encode( array(
				'nonce'             => wp_create_nonce( $this->plugin->meta_prefix ),
			) )
		) );

		// Get options from settings page.
		$color = get_option( 'auto-quote-color', '#CCCCCC' );
		$size  = get_option( 'auto-quote-size', 'medium' );
		$font  = get_option( 'auto-quote-font', 'Arial, sans-serif' );

		include_once( "{$this->plugin->dir_path}/templates/quote-wrapper.php" );
	}
}