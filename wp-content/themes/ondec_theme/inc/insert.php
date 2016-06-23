<?php
global $current_user;

update_user_meta( $current_user->ID, 'decstatus', $_POST['decstatus'] ); 

?>