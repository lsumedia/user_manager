<?php

require('../source/app/components/authenticator.php');

$auth = new authenticator();

?>
<html>
    <head>
        
    </head>
    <body>
        <p>Congratulations, you are logged in!</p>
        <p>Key: <?= $auth->key ?></p>
        <?php $auth->status_bug(); ?>
    </body>
</html>