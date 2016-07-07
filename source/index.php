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
        
        
        <?php js_import(); ?>
    </head>
    
    <body>
        
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href=".">LSU Media Admin</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href=".">Dashboard</a></li>
            <li><a href=".?p=users">Users</a></li>
            <li><a href=".?p=sessions">Sessions</a></li>
            <li><a href=".?p=groups">Groups</a></li>
          </ul>
        </div>
      </div>
    </nav>

        <main class="container">
            <div class="page-header">
                <h1><?= $page_loader->current_page_name() ?></h1>
            </div>
            
            <?php $page_loader->load_content(); ?>
        </main>
        
        <?php $auth->status_bug() ?>
       
    </body>
</html>
