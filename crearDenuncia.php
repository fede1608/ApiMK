<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['titulo']) && isset($_POST['fecha']) && isset($_POST['horario']) && isset($_POST['involucrados']) && isset($_POST['mundo']) && isset($_POST['ciudad']) && isset($_POST['tipo']) && isset($_POST['reglas']) && isset($_POST['explicacion']) && isset($_POST['solucion'])) {
    $user=$_POST['user'];
    $pass=$_POST['pass'];

    $thread['savedraft']=false;
    $thread['uid']=$MyBBI->getUserId($user);
    $thread['subject']="Denuncia Mobil";
    $thread['message']="Mensaje standard";
    $thread['fid']=26;
    $thread['username']=$user;
    //$thread['']=;
    //$thread['']=;
    //$thread['']=;
    $resp=$MyBBI->createThread($thread);
    print_r($resp);
    $response["success"] = 1;
}


echo json_encode($response);


?>

