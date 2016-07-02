<?php

$d = file_get_contents('./relay.php?action=login');

$d2 = json_decode($d,true);


$return = $_GET['redirect'];

/* Symbol to use for URL variable */
$u_v = (strpos($return, "?") == false)? '?' : '&';

$return_full = $return . $u_v . "key=" ;

//echo "<a href=\"{$return}{$u_v}key=5\">$return</a>"; 


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_NAME]";

?>
<form action="../request_login.php?action=login" method="POST">
    <div class="form_row">
        <p>Username</p>
        <input type="text" name="username" />
    </div>
    <div class="form_row">
        <p>Password</p>
        <input type="password" name="password" />
    </div>
    <div class="form_row">
        <input type="submit" value="Sign in"/>
    </div>
    <input type="hidden" value="<?= $_GET['redirect'] ?>" name="redirect" />
</form>
<?php
if(isset($_GET['error'])){
    echo 'Error - username or password incorrect';
}