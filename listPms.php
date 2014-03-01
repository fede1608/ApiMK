<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    define('IN_MYBB', NULL);
    require_once '../foro/global.php';
    require 'MyBBIntegrator.php';
    
    $MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 
    //$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
	
    $response["success"]=0;
     if(isset($_GET['user'])&&isset($_GET['pass'])){
         $user=$_GET['user'];
         $pass=$_GET['pass'];
        if( $MyBBI->checkUserPass($user,$pass)){
            $pms=$MyBBI->getPrivateMessagesOfUser($MyBBI->getUserId($user));
            $response["success"]=1;
            //print_r($pms);
        }
        
         
    }
    
    $response["pms"]=$pms['Bandeja de entrada'];
    echo json_encode($response);
    

?>

