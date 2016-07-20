<?php

$key=$_GET['key'];

$config = array('host'=>'localhost', 'user'=>'root', 'pass'=>'root', 'db_name'=>'odwp2016');

$sql = new mysqli($config['host'], $config['user'], $config['pass'], $config['db_name']);

if (mysqli_connect_errno()) {
    
  printf("Connect failed: %s\n", mysqli_connect_error());
    
  exit;
}

$query = "SELECT * from od_users WHERE ID IN (SELECT user_id FROM od_usermeta WHERE meta_key = 'od_capabilities' AND meta_value LIKE '%business%' OR meta_value LIKE '%client%') AND ID IN (SELECT ID from od_users WHERE user_nicename LIKE '%{$key}%' OR display_name LIKE '%{$key}%' OR user_email LIKE '%{$key}%')";

$result = $sql->query($query);

if($result->num_rows !== 0){
    
    while($row = $result->fetch_row()) {
    
        if($row[0])
    $rows[]=$row[9];
    }
}else{ 
    
    $rows = array('no suggestions.');
}

echo json_encode($rows);
$result->close();

$sql->close();