<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    define('IN_MYBB', NULL);
    require_once '../foro/global.php';
    require 'MyBBIntegrator.php';
    
   	$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 
    //$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
	$version="0.9Beta";
    
     $response["success"]=0;
     if(isset($_POST['user'])&&isset($_POST['pass'])&&isset($_POST['version'])){
             $user=$_POST['user'];
             $pass=$_POST['pass'];
             if($_POST['version']!=$version)
                $response["success"]=-1;
             else
                $response["success"]=$MyBBI->checkUserPass($user,$pass)?1:0;
     }else{
             $response["success"]=0;
     }
     
     echo json_encode($response);
    
    
?>

