<?php

$key=$_GET['key'];

$input = preg_quote($key, '~'); // don't forget to quote input string!

$data = file('http://ondec.dev/querypage');

$result = preg_grep('~' . $input . '~', $data);

echo json_encode($result);