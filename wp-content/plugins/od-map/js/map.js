google.maps.event.addDomListener(window, 'load', gmaps_results_initialize);		
    /**		 * Renders a Google Maps centered on Atlanta, Georgia. This is done by using		 * the Latitude and Longitude for the city.		 *		 * Getting the coordinates of a city can easily be done using the tool availabled		 * at: http://www.latlong.net		 *		 * @since    1.0.0		 */		
    function gmaps_results_initialize() { 

	var infowindow, i;

    var markers_available = data.markers_available;
        
    var mapOptions = {
        center: new google.maps.LatLng(search_val.lat, search_val.long),
        zoom: 13
    };
        
    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

var markers=[];
var contents = [];
var infowindows = [];
var map_info = [];

google.maps.event.addListener(map, 'bounds_changed', function() {
for (var i in markers_available) { 
        
    markers[i] = new google.maps.Marker({
      position: new google.maps.LatLng(markers_available[i].lat, markers_available[i].long),
      title: 'samplemarker'
      
    });
    
    markers[i].setMap(null);
    
     markers[i].index = i;
    contents[i] = '<div class="popup_container">' + map_info[i] +
    '</div>';


    if(map.getBounds().contains(markers[i].getPosition()) == true){
         
             markers[i].setMap(map);
         
         }      

    infowindows[i] = new google.maps.InfoWindow({
    content: markers_available[i].info,
    maxWidth: 300
    });

    google.maps.event.addListener(markers[i], 'click', function() {
            console.log(this.index); // this will give correct index
            console.log(i); //this will always give 10 for you
            infowindows[this.index].open(map,markers[this.index]);
              
        
        
    });
}
    });

}

jQuery("#close_map").click(function(){
    
    jQuery('#map_wrapper').slideUp(300).delay(800).fadeOut(400);
});