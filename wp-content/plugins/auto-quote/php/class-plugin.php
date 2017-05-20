<?php
/**
 * Bootstraps the Auto Quote plugin.
 *
 * @package AutoQuote
 */

namespace AutoQuote;

/**
 * Main plugin bootstrap file.
 */
class Plugin extends Plugin_Base {

	public function __construct() {
		parent::__construct();

		// Initiate classes.
		$classes = array(
			new Auto_Quote( $this ),
		);

		// Add classes doc hooks.
		foreach ( $classes as $instance ) {
			$this->add_doc_hooks( $instance );
		}
	}

	/**
	 * Register scripts/styles.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function register_assets() { var_dump($this->dir_url);
		wp_register_script( "{$this->assets_prefix}-quote", "{$this->dir_url}js/quote.js", array( 'jquery' ) );
		wp_register_style( "{$this->assets_prefix}-quote", "{$this->dir_url}css/quote.css", false );
	}
}