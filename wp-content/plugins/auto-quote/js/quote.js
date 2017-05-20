/**
 * Auto Quote.
 */

// Make sure the wp object exists.
window.wp = window.wp || {};

/* exported AutoQuote */
var AutoQuote = ( function( $, wp ) {
	'use strict';
	
	return {
		/**
		 * Holds data.
		 */
		data: {},
		
		/**
		 * Boot plugin.
		 *
		 * @param data
		 */
		boot: function( data ) {
			this.data = data;
			
			$( document ).ready( function() {
				this.init();
			}.bind( this ) );
		},
		
		/**
		 * Initialize plugin.
		 *
		 * @param data
		 */
		init: function( data ) {
			this.$container = $( '.quote-wrapper' );
			this.getQuotes();
			this.listen();
		},
		
		/**
		 * Initiate listeners.
		 */
		listen: function() {
			var self = this;
			
		},
		
		/**
		 * Api call to grab quotes
		 */
		getQuotes: function() {
			$.ajax( { url: "http://api.forismatic.com/api/1.0/?method=getQuote&format=json&lang=en",
					success: function( result ) {
						console.log( result );
					}
			} );
		},
	};
} )( window.jQuery, window.wp );
