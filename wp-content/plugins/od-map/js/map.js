google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );		
    /**		 * Renders a Google Maps centered on Atlanta, Georgia. This is done by using		 * the Latitude and Longitude for the city.		 *		 * Getting the coordinates of a city can easily be done using the tool availabled		 * at: http://www.latlong.net		 *		 * @since    1.0.0		 */		
    function gmaps_results_initialize() {

	if ( null === document.getElementById( 'map-canvas' ) ) {
		return;
	}

	var map, marker, infowindow, i;

	map = new google.maps.Map( document.getElementById( 'map-canvas' ), {

		zoom:           7,
		center:         new google.maps.LatLng( 33.748995, -84.387982 ),

	});
    
    google.maps.event.addListener(map, 'idle', showMarkers);
    
        function showMarkers() {
    var bounds = map.getBounds();
        alert(bounds);
    // Call you server with ajax passing it the bounds

    // In the ajax callback delete the current markers and add new markers
    }

	// Place a marker in Atlanta
	marker = new google.maps.Marker({

		position: new google.maps.LatLng( 33.748995, -84.387982 ),
		map:      map,
        animation: google.maps.Animation.DROP,
		content:  "Atlanta, Georgia"

	});

	// Add an InfoWindow for Atlanta
	infowindow = new google.maps.InfoWindow();
    
	google.maps.event.addListener( marker, 'click', ( function( marker ) {

		return function() {
			
			infowindow.setContent( marker.content );
			infowindow.open( map, marker );
			
		}

	})( marker ));

}