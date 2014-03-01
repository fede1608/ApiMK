<?php
    require 'inventorySQL/mysql.inc.php';
    require 'inventorySQL/config.inc.php';
    
    
    
    $MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
	
    if(isset($_GET['id'])){
        $id=make_safe($_GET['id']);
         $items = $MySQL->execute("SELECT id as Id, packName as Nombre,imgPath as Logo,cost as Costo, descripcion as Descripcion FROM infoPack WHERE id=".$id);
    }else{
        $items = $MySQL->execute("SELECT id as Id, packName as Nombre,imgPath as Logo,cost as Costo, descripcion as Descripcion FROM infoPack WHERE type=1");
    }
    
    
    $response["success"]=($items!=false)?1:0;
    $response["items"]=$items;
    echo json_encode($response);
    

?>

