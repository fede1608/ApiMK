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


include('libs/MinecraftQuery.class.php');
$Query = new MinecraftQuery();
$Query->Connect("minekkit.com", "25565");
$players = $Query->GetPlayers();

$response["players"]=$players;

echo json_encode($response);


?>