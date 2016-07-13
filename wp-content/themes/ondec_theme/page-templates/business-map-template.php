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

<style type="text/css">				
    #map-canvas {						
        width: 100%;			
        height: 500px;					
    }				
</style>	

<div id="map-canvas"></div><!-- #map-canvas -->	

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD27PJsgKc4b4Jkm5swmUmeMOpbT8HcXtc&v=3.exp&libraries=places&signed_in=true"></script>

<script type="text/javascript">				
    google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );		
    /**		 * Renders a Google Maps centered on Atlanta, Georgia. This is done by using		 * the Latitude and Longitude for the city.		 *		 * Getting the coordinates of a city can easily be done using the tool availabled		 * at: http://www.latlong.net		 *		 * @since    1.0.0		 */		
    function gmaps_results_initialize() {					
        var map = new google.maps.Map( document.getElementById( 'map-canvas' ), {						
            zoom: 7,				
            center: new google.maps.LatLng( 33.748995, -84.387982 ),					
        });	
        
        // Place a marker in Atlanta			
        marker = new google.maps.Marker({		
		position: new google.maps.LatLng( 33.748995, -84.387982 ),				
            map:      map		
	});
        //
        marker = new google.maps.Marker({		
		position: new google.maps.LatLng( 34.075376, -84.294090 ),				
        map:      map		
	});
    }				
</script>
<?php
get_footer();
