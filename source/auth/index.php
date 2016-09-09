<?php
require_once('../app/config.php');
?>
<html>
    <head>
        <title>LSU Media SSO</title>
        <link rel="stylesheet" href="css/materialize.min.css" />
        <link rel="stylesheet" href="css/style.css" />
        <script src="js/materialize.min.js" ></script>
        
        <meta name="viewport" content="width=device-width, user-scalable=no" />
    </head>  
    <body>
        <?php
        $page = $_GET['p'];
        if($page != 'login'){
        ?>
        <nav>
            <div class="nav-wrapper container white-text" >
                <div class="brand-logo">
                    <img src="res/media_reverse.png" />
                    <p>Single Sign-On</p>
                </div>
            </div>
        </nav>
        <?php } ?>
        <main class="container">
        <?php
            
            switch($page){
                case 'login':
                    include('php/login.php');
                    break;
                case 'profile':
                    include('php/profile.php');
                    break;
                case 'register':
                    include('php/register.php');
                    break;  
                case 'logout':
                    include('php/logout.php');
                    break;
                case 'goodbye':
                    include('php/goodbye.php');
                    break;
                case 'nopath':
                    include('php/nopath.php');
                    break;
            }
           
        ?>
        </main>
    </body>
  
    
</html>




