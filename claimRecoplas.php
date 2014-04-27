<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
//$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($MyBBI->checkUserPass($user, $pass)) {
        $response["success"] = $MyBBI->claimCoin($user);
    }
}


echo json_encode($response);


?>
