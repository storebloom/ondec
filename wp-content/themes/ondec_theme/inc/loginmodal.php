<?php 

    $site_url = home_url( '/' );
            
    $page = get_page_by_path( 'my-profile' , OBJECT );
    
    if(isset($page)){
        $location = $site_url . 'my-profile';
    } else {
        $location = $site_url;
    }

    $args = array(
        'echo'           => true,
        'redirect'       => $location,
        'value_username' => (isset($_GET['user']) ? $_GET['user'] : ""),
    )
?>

<style type="text/css">
    .dumbBoxWrap { /* The div that shows/hides. */
        display:none; /* starts out hidden */
        z-index:40001; /* High z-index to ensure it appears above all content */
    }
    .dumbBoxOverlay { /* Shades out background when selector is active */
        position:fixed;
        width:100%;
        height:100%;
        background-color:black;
        opacity:.5; /* Sets opacity so it's partly transparent */
        -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)"; /* IE transparency */
        filter:alpha(opacity=50); /* More IE transparency */
        z-index:40001;
    }

    .vertical-offset { /* Fixed position to provide the vertical offset */
        position:fixed;
        top:30%;
        width:100%;
        z-index:40002; /* ensures box appears above overlay */
    }
    
    .dumbBox { /* The actual box, centered in the fixed-position div */
    width:405px; /* Whatever width you want the box to be */
    position:relative;
    margin:0 auto;
    /* Everything below is just visual styling */
    background-color:white;
    padding:10px;
    border:1px solid black;
}

</style>

<div class="dumbBoxWrap">
    <div class="dumbBoxOverlay">
        &nbsp;
    </div>
    <div class="vertical-offset">
        <div class="dumbBox">
            <?php echo wp_login_form($args); ?>
            <a id="closeModal">Close</a>
        </div>
    </div>
</div>    

<a id="openModal">Login</a>

<script type="text/javascript">
    jQuery(document).ready(function() {
        //Show modal box
        jQuery('#openModal').click(
            function() {$('.dumbBoxWrap').show();}
        );
        //Hide modal box
        jQuery('#closeModal').click(
            function() {$('.dumbBoxWrap').hide();}
        );
    });
</script>