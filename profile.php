<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$MySQL = new SQL($host, $usernombre, $pass, $dbJobs);

$response["success"] = 0;
$response["recoplas"] = 0;
$response["jobs"] = Array();


if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if ($MyBBI->checkUserPass($user, $pass)) {
        $a = $MyBBI->getUser($MyBBI->getUserId($user));
        $response['email'] = $a['email'];
        $response['user'] = $user;
        $response['userId'] = $MyBBI->getUserId($user);
        $response["recoplas"] = $MyBBI->getMoney($user);
        $response["avatar"] = "http://minekkit.com/foro/" . $a['avatar'];
        $response["jobs"] = $MySQL->execute("SELECT * FROM jobsjobs WHERE username='" . $user . "'");
        $response["success"] = 1;
    } else {
        $a = $MyBBI->getUser($MyBBI->getUserId($user));
        $response['user'] = $user;
        $response['userId'] = $MyBBI->getUserId($user);
        $response["avatar"] = "http://minekkit.com/foro/" . $a['avatar'];
        $response["success"] = 1;
    }
}

echo json_encode($response);

?>