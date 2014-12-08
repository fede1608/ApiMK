<?php
/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 08/12/2014
 * Time: 02:47 PM
 */
require_once 'MyBBIntegrator.php';
require_once '../recompensas/header.php';
require_once '../recompensas/functions.php';
ini_set('display_errors', 'On');
$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$price = 0;
$response["success"] = 0;
$response["message"]="";

if (isset($_POST['user']) && isset($_POST['code'])) {
    $user = $_POST['user'];
    $code = $_POST['code'];
    $cupon = Cupon::load($code);
    $redeem=$cupon->redeem($user);
    $response["success"] = $redeem['error']?0:1;
    $response["message"]= $redeem['message'];

}


echo json_encode($response);


?>
