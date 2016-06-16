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
    )
?>

<dialog>
  <p>This is da dialog!</p>
  <?php echo wp_login_form($args); ?>
  <button id="close">Enter</button>
</dialog>
<button id="show">Login</button>

<script type="text/javascript">
    var dialog = document.querySelector('dialog');
    document.querySelector('#show').onclick = function() {
      dialog.show();
    };
    document.querySelector('#close').onclick = function() {
      dialog.close();
    };
</script>