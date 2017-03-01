google.maps.event.addDomListener(window, 'load', gmaps_results_initialize);		
	
function gmaps_results_initialize() { 

    var infowindow, i;

    var markers_available = data.markers_available;

    var pinColor = "FE7569";
    var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0,0),
        new google.maps.Point(10, 34));
    var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
        new google.maps.Size(40, 37),
        new google.maps.Point(0, 0),
        new google.maps.Point(12, 35));    

    var mapOptions = {
        center: new google.maps.LatLng(search_val.lat, search_val.long),
        zoom: 9
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
              title: 'samplemarker',
              icon: pinImage,
              shadow: pinShadow

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