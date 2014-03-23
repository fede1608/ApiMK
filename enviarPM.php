<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    define('IN_MYBB', NULL);
    require_once '../foro/global.php';
    require 'MyBBIntegrator.php';
    
    $MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 
    //$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
	
    $response["success"]=0;
    
    
    echo json_encode($response);
    

?>
