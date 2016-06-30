<html>
    <head>
        
    </head>  
    <body>
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
            }
           
        ?>
    </body>
  
    
</html>




