<form method="post" id="search-address">
<input name="search-address" type="text" />
    <input name="submit" type="submit" value="search" />
</form>
<?php

$search = isset($_POST['search-address']) ? $_POST['search-address'] : "";

if(isset($_POST['submit'])){
$search_results = $od_map::get_the_address($search);

echo $search_results;
}

?>