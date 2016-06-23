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
                    
                    echo "<li class='user-result'><a href='".$user_type[0]."/".$user_value[3]."'>"  . $user_value[9] . " " . $user_value[4] . "</a>
                    <div class='decaddbutton_wrapper'>
                    <span style='display: none;' id='successadd'>Thanks I'm on your dec list now!</span>
                    <form id='decaddmeform-".$user_value[0]."' name='decaddmeform'>
                        <input type='hidden' id='decaddmebutton' value='".$user_value[0]."'>
                        <input type='button' id='addtoyourdec' value='follow me'>
                    </form>
                    </div>
                    </li></br>
                    
                    ";
                    
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
    jQuery("#addtoyourdec").click(function(){
        var adddecid = jQuery("#decaddmebutton").val();
        jQuery.post( 
            ajaxurl,
                {   
                    \'action\': \'add_decmember\',
                    \'adddecid\': adddecid
                }, 
                function(response){
                alert(response);
                jQuery("#decaddmeform-" + adddecid).slideDown(800).fadeOut(400);    
                jQuery("#successadd").slideUp(800).fadeIn(400);
                jQuery("#successadd").slideDown(300).delay(800).fadeOut(400);    
               
            }
        );
    });
</script>';
}
?>