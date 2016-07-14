<form method="post" id="search-address">
<input name="search-address" type="text" />
    <input name="submit" type="submit" value="search" />
</form>
<?php

$search = isset($_POST['search-address']) ? $_POST['search-address'] : "";

if(isset($_POST['submit'])){
$search_results = $od_map::get_the_address($search);

$search_results = $search_results[0][0];
    
}

$geocode_info = json_decode($od_map::geocode_address($search_results));

foreach($geocode_info as $geoloc){
    
     if(isset($geoloc[0]->geometry)){
         
        $coord[] = $geoloc[0]->geometry->location->lat . ", " . $geoloc[0]->geometry->location->lng;
     }

}

echo $coord[0];
?>