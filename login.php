<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
//$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
$version = "1.0";

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['version'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if (false && $_POST['version'] != $version)
        $response["success"] = -1;
    else {
        $a = $MyBBI->getUser($MyBBI->getUserId($user));
        $response["recoplas"] = $MyBBI->getMoney($user);
        $response["avatar"] = "http://minekkit.com/foro/" . $a['avatar'];
        $response["success"] = $MyBBI->checkUserPass($user, $pass) ? 1 : 0;
    }
} else {
    $response["success"] = 0;
}

echo json_encode($response);


?>

