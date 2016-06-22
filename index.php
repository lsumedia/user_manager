<?php

require('app/init.php');


$page_loader = new page_loader();
$page_loader->load_current_page();

$page_loader->load_content();
