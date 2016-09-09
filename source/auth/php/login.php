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
<style>
   body{
        background-image:url('<?= $config['login_background'] ?>');
        background-size:cover;
    }
</style>
<div class="row">
    <div class="login-card card z-depth-3 login-form col s12 l8 offset-l2">
        <form action="../request_login.php?action=login" method="POST" id="login_form">
            <div class="card-content">
                <p class="center-align"><img class="main-logo" src="res/media_logo.png" /></p>
                <h4 class="center">Single Sign-On</h4>
                <br />
                <label for="username_field">Username or email address</p>
                <input type="text" id="username_field" name="username" />
                <label for="password_field">Password</label>
                <input type="password" id="password_field" name="password" />
                <?php
                    if(isset($_GET['error'])){
                        echo '<p class="red-text">Error - username or password incorrect</p>';
                    }
                ?>
            </div>
            <div class="card-action">
                <!-- <input type="submit" class="btn-flat right" value="Sign in"/> -->
                <a>&nbsp;</a><!-- lord forgive me-->
                <a class="right blue-text" href="javascript:void(0);" onclick="document.getElementById('login_form').submit()">Sign In</a>
            </div>
            <input type="hidden" value="<?= $_GET['redirect'] ?>" name="redirect" />
        </form>
    </div>
</div>
