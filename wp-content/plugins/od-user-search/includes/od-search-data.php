<?php
    
    $key=$_GET['key'];

    $input = preg_quote($key, '~'); // don't forget to quote input string!
    $data = array('orange', 'blue', 'green', 'red', 'pink', 'brown', 'black');

    $result = preg_grep('~' . $input . '~', $data);

    echo json_encode($result);
        ?>