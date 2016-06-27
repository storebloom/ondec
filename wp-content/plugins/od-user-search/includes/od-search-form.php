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
            <ul style='overflow:scroll; max-height: 400px;' class="modal_results">
            <?php if(isset($_POST['typeahead'])){
                
                global $current_user;
    
                $user_role = $current_user->roles[0];
    
                $add_msg = array('professional' => 'request business', 'business' => 'add pro', 'client' => 'follow me');
            
                $rows = $od_user_search->get_user_modal_results($_POST['typeahead']);
                
                
                foreach($rows as $user_value){
                    
                    if($od_user_search->in_my_dec_already($user_value[0])){
                        
                        
                        $user_information = get_userdata($user_value[0]);

                        $user_type = $user_information->roles;

                        $decstatus = get_user_meta($user_value[0], 'decstatus', true);    

                        $mypromsg = "";

                        if(is_page('my-profile')){
                            $mypromsg =  "Refresh to see me.";
                        }
                        
                        if($user_role !== 'client' ){
                            
                        echo "<li class='user-result'><a href='/".$user_type[0]."es/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        <div class='decrequestbutton_wrapper'>
                        <span style='display: none;' id='successrequest-".$user_value[0]."'>Request submitted! ". $mypromsg."</span>
                        <form id='decrequestmeform-".$user_value[0]."' name='decrequestmeform'>
                            <input type='hidden' id='decrequestmebutton-".$user_value[0]."' value='".$user_value[0]."'>
                            <input type='button' id='requesttoyourdec-".$user_value[0]."' value='".$add_msg[$user_role]."'>
                        </form>
                        </div>
                        </li></br>
                    "; } elseif($user_role === 'client' && $user_type[0] === 'professional'){
       
                            echo "<li class='user-result'><a href='/".$user_type[0]."s/".$user_value[3]."'>". get_wp_user_avatar($user_value[0], 96) . "</br>" .$decstatus. "</br>". $user_value[9] . " " . $user_value[4] . "</a>
                        <div class='decaddbutton_wrapper'>
                        <span style='display: none;' id='successadd-".$user_value[0]."'>I'm on your dec list now! ". $mypromsg."</span>
                        <form id='decaddmeform-".$user_value[0]."' name='decaddmeform'>
                            <input type='hidden' id='decaddmebutton-".$user_value[0]."' value='".$user_value[0]."'>
                            <input type='button' id='addtoyourdec-".$user_value[0]."' value='".$add_msg[$user_role]."'>
                        </form>
                        </div>
                        </li></br>
                    ";
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
                
                if($user_role === 'client'){
                    foreach($rows as $user_value){

                       echo 
                           "jQuery('#addtoyourdec-".$user_value[0]."').click(function(){
                            var adddecid = jQuery('#decaddmebutton-".$user_value[0]."').val();
                            jQuery.post( 
                                ajaxurl,
                                    {   
                                        'action': 'add_decmember',
                                        'adddecid': adddecid
                                    }, 
                                    function(response){
                                    jQuery('#decaddmeform-".$user_value[0]."').slideDown(800).fadeOut(400);    
                                    jQuery('#successadd-".$user_value[0]."').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);   
                                }
                            );
                        });  
                    ";
                    }
                } else {
                    foreach($rows as $user_value){

                   echo 
                       "jQuery('#requesttoyourdec-".$user_value[0]."').click(function(){
                        var requestdecid = jQuery('#decrequestmebutton-".$user_value[0]."').val();
                        jQuery.post( 
                            ajaxurl,
                                {   
                                    'action': 'request_decmember',
                                    'requestdecid': requestdecid
                                }, 
                                function(response){
                                jQuery('#decrequestmeform-".$user_value[0]."').slideDown(800).fadeOut(400);    
                                jQuery('#successrequest-".$user_value[0]."').slideUp(800).fadeIn(400).slideDown(300).delay(800).fadeOut(400);
                            }
                        );
                    });  
                ";
                }    
                }
echo '
    jQuery( document ).ready(function(){$(\'.od-searchBoxWrap\').show();});   
</script>';
}
?>