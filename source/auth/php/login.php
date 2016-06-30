<?php

$d = file_get_contents('./relay.php?action=login');

$d2 = json_decode($d,true);

 echo 'Key: ' . $d2['key'];
echo $d;


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
echo $actual_link;