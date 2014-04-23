<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', null);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
//$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);

$response["success"] = 0;

if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['title']) &&
    isset($_POST['content']) && isset($_POST['to'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $to = $_POST['to'];
    $toid = $MyBBI->getUserId($to);
    if (!isset($toid)) {
        $response["success"] = -2;
    } else {
        if ($MyBBI->checkUserPass($user, $pass)) {
            $pm = array(
                'fromid' => $MyBBI->getUserId($user),
                'subject' => $title,
                'message' => $content,
                'icon' => 0,
                'to_username' => $to);
            $pm['options']['savecopy'] = true;
            $MyBBI->sendPrivateMessage($pm);
            $response["success"] = 1; //todo: usar el return del metodo anterior para saber si se envio el mensaje
        }
    }
}


echo json_encode($response);


?>
