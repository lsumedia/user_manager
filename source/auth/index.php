<html>
    <head>
        <title>LSU Media SSO</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>  
    <body>
        <nav>
            <img src="res/media_logo.png" alt="Media Logo" />
            <p>Single-Sign-On</p>
        </nav>
        <main>
        <?php
            $page = $_GET['p'];
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




