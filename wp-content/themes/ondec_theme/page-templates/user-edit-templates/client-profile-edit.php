<?php
$decstatus = get_user_meta($current_user->ID, 'decstatus', true);
$decmessage = get_user_meta($current_user->ID, 'decmessage', true);
$mydec = null !== get_user_meta($current_user->ID, 'mydec', false) ? get_user_meta($current_user->ID, 'mydec', false) : array( 0 => array());
$decstatus = isset($decstatus) && $decstatus !== "" ? $decstatus : "no dec status";
$current_decmessage = isset($decmessage) && $decmessage !== "" ? $decmessage : "";
$biz_title = array("client" => "dec", "professional" => "Followers", "business" => "Current Professoinal"); 
$professional_types = array("tattoo" => "Tattoo Artist", "makeup" => "Makeup Artist", "hair" => "Hair Stylist", "bar" => "Bartender", "other" => "Other");

$my_dec_info = array();

if(isset($mydec[0])){
    foreach($mydec[0] as $single_member){
        $my_dec_info[] = get_userdata($single_member);
    }
}

if($decstatus === "ondec" ){
    $negdecstatus = "offdec";
}else{
    $negdecstatus = "ondec";
}
get_header(); 
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php echo "<h1>" . $current_user->display_name . "'s Profile</h2>"; ?>
            <?php echo get_wp_user_avatar($current_user->ID, 96); ?>
            
            <?php if($user_role !== "business" ) : ?>
            <h3>current dec status:</h3>
                        
            <form id="decform" name="decform">
                <input type="hidden"  name="decstatus" id="decstatus" value="<?php echo $negdecstatus; ?>">
                <select id="decmood">
                    <option disabled selected value="">Choose mood</option>
                    <option value="Happiness">Happiness</option>
                    <option value="Sadness">Sadness</option>
                    <option value="Excitement">Excitement</option>
                    <option value="Coolness">Coolness</option>
                </select>
                <input id="submit" type="button" value="<?php echo "Currently " . $decstatus; ?>">
            </form>
            
            <a href="/clients/<?php echo $current_user->user_login; ?>">view my profile</a>
            
            <span>
            <div style="display:none;" id="msgsuccess">success!</div>
            </span>
            <form id="decmsgform" name="decmsgform">
                <input type="text" placeholder="what's up?" name="decmessage" id="decmessage" value="<?php echo $current_decmessage; ?>">
                <input id="msgsubmit" type="button" value="update">
            </form>
            <?php endif; ?> 
        
            <h3>My <?php echo $biz_title[$user_role]; ?></h3>  
            
            <div class="od-my-list">
                <span>
                                
                                <div style="display:none;" id="rmsuccess">successfully removed member!</div>
                                
                            </span>
                <ul>
                <?php foreach($my_dec_info as $single_dec_member) :
                    
                    $user_information = get_userdata($single_dec_member->ID);

                    $user_type = $user_information->roles;
                ?>
                    <li class="decmember-<?php echo $single_dec_member->ID; ?>">
                        <a href='/<?php echo $user_type[0].'s/'.$user_information->user_login; ?>'>
                        <div class="dec-name">
                            
                            <?php echo $single_dec_member->display_name; ?>
                            
                        </div>
                        
                        <div class="dec-image">
                            
                            <?php echo get_wp_user_avatar($single_dec_member->ID, 96); ?>
                            
                        </div>
                            
                        </a>
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decstatus', true); ?>
                            
                        </div>
                        
                        <div class="dec-status">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decstatus', true); ?>
                            
                        </div>
                        
                        <div class="dec-message">
                            
                            <?php echo get_user_meta($single_dec_member->ID, 'decmessage', true); ?>
                            
                        </div>
                        
                        <div>
                            
                            <form id="decmsgform-<?php echo $single_dec_member->ID; ?>" name="decmsgform-<?php echo $single_dec_member->ID; ?>">
                                
                                <input id="<?php echo $single_dec_member->ID; ?>" class="decremove" type="button" value="remove from list">
                                
                            </form>
                            
                        </div>
                        
                    </li>
                    
                <?php endforeach; ?>
                    
                </ul>
                
            </div>
            
            <?php if($user_role === "professional"):
            
                $mybusinesses = null !== get_user_meta($current_user->ID, 'mybusinesses', false) ? get_user_meta($current_user->ID, 'mybusinesses', false) : array( 0 => array());
                $my_business_info = array();    
            
                if(isset($mybusinesses[0])){
                    foreach($mybusinesses[0] as $single_business){
                        $my_business_info[] = get_userdata($single_business);
                    }
                }
            ?>  
                <h3>My Business Locations</h3>
                <div class="od-my-businesses">
                    <span>

                        <div style="display:none;" id="rmsuccess">successfully removed business!</div>

                    </span>
                    
                    <ul id="business-list">
                    <?php foreach($my_business_info as $single_dec_business) :
                        
                     $user_information = get_userdata($single_dec_business->ID);
                    ?>
                        <li class="decmember-<?php echo $single_dec_business->ID; ?>">
                            <a href='/businesses/<?php echo $single_dec_business->user_login; ?>'>
                            <div class="dec-name">

                                <?php echo $single_dec_business->display_name; ?>

                            </div>

                            <div class="dec-image">

                                <?php echo get_wp_user_avatar($single_dec_business->ID, 96); ?>

                            </div>

                            </a>

                            <div class="dec-status">

                                <?php echo get_user_meta($single_dec_business->ID, 'decstatus', true); ?>

                            </div>

                            <div class="dec-message">

                                <?php echo get_user_meta($single_dec_business->ID, 'decmessage', true); ?>

                            </div>

                            <div>

                                <form id="decmsgform-<?php echo $single_dec_business->ID; ?>" name="decmsgform-<?php echo $single_dec_business->ID; ?>">

                                    <input id="<?php echo $single_dec_business->ID; ?>" class="decremovebiz" type="button" value="remove from list">

                                </form>

                            </div>

                        </li>

                    <?php endforeach; ?>

                    </ul>

                </div>
            <?php endif; ?>

            <p>
        <a href="edit-profile-info">Edit Profile</a>
            </p>
<?php
get_footer();

?>
<script>
    jQuery(document).ready(function() {
        
        jQuery('.decremove').click(function(){
            var rmdecid = jQuery(this).attr('id');
            var rmclass = ".decmember-" + rmdecid; 

            if (window.confirm("Do you really want to remove them from your list?")) {

                jQuery.post(

                    ajaxurl,
                        {   
                            'action': 'remove_decmember',
                            'rmdecid': rmdecid
                        }, 
                        function(response){

                        jQuery(rmclass).slideDown(800).fadeOut(400);    
                        jQuery("#rmsuccess").slideUp(800).fadeIn(400).delay(800).fadeOut(400);
                    }
                );
            }
        });
    
        jQuery("#submit").click(function() {
        
            if(jQuery("#decstatus").val() === 'ondec'){ 

                jQuery('#submit').removeClass('currently-offdec').addClass('currently-ondec');

                jQuery( "#decstatus").val('offdec');
                
                var decmood = jQuery('#decmood option:selected').val() + " ";

                var decstatus = decmood + 'ondec';

                jQuery( "#submit" ).val('Currently ondec');
            } else {

                jQuery('#submit').removeClass('currently-ondec').addClass('currently-offdec');

                jQuery( "#decstatus").val('ondec');

                var decstatus = decmood + ' ' + 'offdec';

                jQuery( "#submit").val('Currently offdec');
            }    

            jQuery.post( 
                ajaxurl,
                    {   
                        'action': 'add_decstatus',
                        'decstatus': decstatus
                    }, 
                    function(response){

                    //alert('The server responded: ' + response);
                }
            );
    });
        
    jQuery("#msgsubmit").click(function(){    
            
        var decmessage = jQuery('#decmessage').val();
            
            jQuery.post( 
            ajaxurl,
                {   
                    'action': 'add_decmessage',
                    'decmessage': decmessage
                }, 
                function(response){

                jQuery("#msgsuccess").slideUp(800).fadeIn(400);
                jQuery("#msgsuccess").slideDown(300).delay(800).fadeOut(400);    
               
            }
        );
    }); 
});
 
</script>