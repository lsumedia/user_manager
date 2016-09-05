<?php

if(0 || isset($_GET['debug'])){	//Error reporting - disable for production
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
}else{
    ini_set('display_errors', '0');     # don't show any errors...
    error_reporting(E_ALL | E_STRICT);  # ...but do log them
}

require('app/init.php');

$auth = new authenticator();

//Block users without manage_users permission
if(!$auth->server_check_permission('manage_users')){
    echo "You do not have permission to access this page.";
    die();
}

$page_loader = new page_loader();

if(isset($_GET['p'])){
    $page_id = $_GET['p'];
}else{
    $page_id = 'dashboard';
}
$page_loader->load_page_by_name($page_id);

?>
<!doctype html >
<html>
    <head>
        <title>LSU Media User Manager</title>
        
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="css/main.css" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>        
        <?php js_import(); $page_loader->load_header(); ?>
    </head>
    
    <body>
    
    <nav class="navbar navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href=".">User Manager</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href=".">Home</a></li>
            <li><a href=".?p=users">Users</a></li>            
            <li><a href=".?p=groups">Groups</a></li>
            <li><a href=".?p=sessions">Sessions</a></li>
          </ul>
        </div>
      </div>
    </nav>

        <main class="container">
            <?php if(strlen($page_loader->current_page_name()) > 0){ ?>
            <div class="page-header">
                <h3><?= $page_loader->current_page_name() ?></h3>
            </div>
            <?php } ?>
            <?php $page_loader->load_content(); ?>
        </main>
        
        <?php $auth->status_bug(); ?>
       
    </body>
</html>
