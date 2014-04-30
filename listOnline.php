<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
//define('IN_MYBB', NULL);
//require_once '../foro/global.php';
//require 'MyBBIntegrator.php';

//$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
//$MySQL = new SQL($host, $usernombre, $pass, $dbMinekkit);

$response["success"] = 1;
$response["players"] = Array();


$json = file_get_contents('http://api.iamphoenix.me/list/?server_ip=minekkit.com');
$obj = json_decode($json);
$response["players"] = explode(",", $obj->players);


echo json_encode($response);


?>