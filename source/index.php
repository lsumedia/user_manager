<?php

require('app/init.php');

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
        
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="css/main.css" />
         
        
        
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

        <main class="container-fluid">
        <?php $page_loader->load_content(); ?>
        </main
        
        <script src="js/jquery-3.0.0.min.js" ></script>
        <script src="js/bootstrap.min.js"></script>
        
    </body>
</html>
