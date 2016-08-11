<form action="" name="od-user-search" method="post" id="od-user-search">
    <input class="typeahead" type="text" name="typeahead"/>
    <input id="openSearchModal" type="submit" value="search"/>
</form>

<div class="od-searchBoxWrap">
    <div class="od-searchBoxOverlay">
        &nbsp;
    </div>
    <div class="vertical-offset">
        <div class="od-searchBox">
            <ul style='max-height: 400px;' class="modal_results">
            <?php if(isset($_POST['typeahead'])){
                
                global $current_user;
                
                if(isset($current_user->roles[0])){
                    $user_role = $current_user->roles[0];
                }
    
                $add_msg = array('' => '', 'professional' => 'request business', 'business' => 'add pro', 'client' => 'follow me');
            
                $rows = $od_user_search->get_user_modal_results($_POST['typeahead']);
                
                
                foreach($rows as $user_value){
                    
                    $user_information = get_userdata($user_value[0]);

                    $user_type = $user_information->roles;

                    $decstatus = get_user_meta($user_value[0], 'decstatus', true);         
                    
                    if(count($rows) < 2){
                        
                        $stype = "s/";
                    
                        if($user_role === 'professional' && $user_type[0] === "business" ){
                            
                            $stype = "es/";
                        }
                        
                        echo '<script type="text/javascript"> window.location = "/'.$user_type[0].$stype.$user_value[3].'" </script>';
                    }
                    
                    if($od_user_search->in_my_dec_already($user_value[0])){           
                        
                        if($user_role === 'professional' && $user_type[0] === "business" ){
                            
                        echo "<li class='user-result'><a href='/".$user_type[0]."es/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        </li></br>
                    "; } elseif($user_role === 'client' && $user_type[0] === 'professional'){
       
                            echo "<li class='user-result'><a href='/".$user_type[0]."s/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        </li></br>
                    ";
                        }  elseif($user_role === 'business' && $user_type[0] === "professional" ){
                            
                        echo "<li class='user-result'><a href='/".$user_type[0]."s/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        </li></br>
                    "; } elseif ($user_type[0] !== "business") {
       
                            echo "<li class='user-result'><a href='/".$user_type[0]."s/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        </li></br>
                    "; } elseif( $user_type[0] === 'business'){
                            
                             echo "<li class='user-result'><a href='/".$user_type[0]."es/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        </li></br>";
                        }
                }}} ?>
                
            </ul>
            <a id="closeSearchModal">Close</a>     
        </div>
    </div>
</div>

<?php if(isset($_POST['typeahead'])){
            echo '<script>';
    
                $rows = $od_user_search->get_user_modal_results($_POST['typeahead']);
                
echo '
    jQuery( document ).ready(function(){$(\'.od-searchBoxWrap\').show();});   
</script>';
}
?>