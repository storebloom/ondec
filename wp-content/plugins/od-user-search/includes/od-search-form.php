<form action="" name="od-user-search" method="post" id="od-user-search">
    <input class="typeahead" type="text" name="typeahead"/>
    <input id="openSearchModal" type="submit"/>
</form>

<div class="od-searchBoxWrap">
    <div class="od-searchBoxOverlay">
        &nbsp;
    </div>
    <div class="vertical-offset">
        <div class="od-searchBox">
            <ul class="modal_results">
            <?php if(isset($_POST['typeahead'])){
            
                $rows = $od_user_search->get_user_modal_results($_POST['typeahead']);
                
                
                foreach($rows as $user_value){
                    
                    $user_information = get_userdata($user_value[0]);
                    $user_type = $user_information->roles;
                    
                    echo "<li class='user-result'><a href='".$user_type[0]."/".$user_value[3]."'>"  . $user_value[9] . " " . $user_value[4] . "</a></li></br>";
                }
            } ?>
                
            </ul>
            <a id="closeSearchModal">Close</a>     
        </div>
    </div>
</div>

<?php if(isset($_POST['typeahead'])){
echo '<script>
    jQuery( document ).ready(function(){$(\'.od-searchBoxWrap\').show();});
</script>';
}
?>