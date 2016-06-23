<?php

require('app/init.php');

$page_loader = new page_loader();


if(isset($_GET['p'])){
    $page_id = $_GET['p'];
}else{
    $page_id = 'dashboard';
}
$page_loader->load_page_by_name($page_id);

$page_loader->load_content();
