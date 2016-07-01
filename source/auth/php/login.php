<?php

$d = file_get_contents('./relay.php?action=login');

$d2 = json_decode($d,true);

 echo 'Key: ' . $d2['key'];
echo $d;

$return = $_GET['redirect'];

/* Symbol to use for URL variable */
$u_v = (strpos($return, "?") == false)? '?' : '&';

echo "<a href=\"{$return}{$u_v}key=5\">$return</a>"; 


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";
echo $actual_link;