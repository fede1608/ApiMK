<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    define('IN_MYBB', NULL);
    require_once '../foro/global.php';
    require 'MyBBIntegrator.php';
    $dbMinekkit="minekkit";
    
    $MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config); 
    $MySQL = new SQL($host, $usernombre, $pass, $dbMinekkit);
	
    $response["success"]=0;
    $response["recoplas"]=0;
    $response["jobs"]=Array();
   
    
    
    if(isset($_POST['user'])&&isset($_POST['pass'])){
         $user=$_POST['user'];
         $pass=$_POST['pass'];
         
         if($MyBBI->checkUserPass($user,$pass)){
                $a=$MyBBI->getUser($MyBBI->getUserId($user));
                $response["recoplas"]=$MyBBI->getMoney($user);
                $response["jobs"]=$MySQL->execute("SELECT * FROM jobsjobs WHERE username='".$user."'");
                $response["avatar"]="http://minekkit.com/foro/".$a['avatar'];
                $response["success"]=1;//todo: usar el return del metodo anterior para saber si se envio el mensaje
               
          }
    }
                
    
    echo json_encode($response);
    

?>