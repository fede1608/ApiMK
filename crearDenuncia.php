<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    
    
    
    $MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
    
	 $response["success"]=0;
    if(isset($_POST['user'])&&isset($_POST['pass'])&&isset($_POST['titulo'])&&isset($_POST['fecha'])&&isset($_POST['horario'])&&isset($_POST['involucrados'])&&isset($_POST['mundo'])&&isset($_POST['ciudad'])&&isset($_POST['tipo'])&&isset($_POST['reglas'])&&isset($_POST['explicacion'])&&isset($_POST['solucion'])){
         $response["success"]=1;
    }
    
    
   
    echo json_encode($response);
    
    
?>

