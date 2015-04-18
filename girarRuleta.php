<?php
/**
 * Created by PhpStorm.
 * User: Fede1608
 * Date: 30/04/14
 * Time: 15:06
 */
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);
//$MySQL = new SQL($host, $usernombre, $pass, "recompensas");


$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['action'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $action = $_POST['action'];
    if ($MyBBI->checkUserPass($user, $pass)) {
        if ($action == "getIntents") {
            $response['intentos'] = 0;
            $cupon = $MySQL->execute("SELECT count(*) as cantidad FROM roulette WHERE player='{$user}' AND mobileUsed=0");
            $response['intentos'] = $cupon[0]['cantidad'];
            $response["success"] = 1;
        } elseif ($action == "getPrize") {
            $archivo = fopen("../Logs/Roulette.txt", "a");
            /*
                Example of server-side file that returns the number of the item in the prize array that the user is to win.
                Up to you how you determine this on your site, for example randomly, or not so randomly.

                In this quick example I always return 2 so the user will always win prize 3.
                You will need an apache server with PHP installed to run this file. I use XAMPP on my local machine.
            */
            $prizes[0] = 1000;
            $prizes[1] = 2500;
            $prizes[2] = 100000;
            $prizes[3] = 25000;
            $prizes[4] = 10000;
            $prizes[5] = 5000;
            $prizes[6] = 500;
            $prizes[7] = 50000;


            $p = mysql_real_escape_string($user);
            $cupon = $MySQL->execute("SELECT * FROM roulette WHERE player='{$p}' AND mobileUsed=0");
            if (isset($cupon[0])) {
                $num = rand(1, 100);
                if (($num >= 0) && ($num < 3)) $response["prize"] = 0;
                if (($num >= 3) && ($num < 10)) $response["prize"] = 6;
                if (($num >= 10) && ($num < 40)) $response["prize"] = 5;
                if (($num >= 40) && ($num < 55)) $response["prize"] = 3;
                if (($num >= 55) && ($num < 80)) $response["prize"] = 4;
                if (($num >= 80) && ($num < 96)) $response["prize"] = 1;
                if (($num >= 96) && ($num < 98)) $response["prize"] = 7;
                if (($num >= 99) && ($num <= 100)) $response["prize"] = 2;
                fputs($archivo, date(DATE_RFC822) . " MINEKKIT MOBILE APP Player: {$p} Prize: {$response["prize"]} Plas: {$prizes[$response["prize"]]} idPaygol: {$cupon[0]["idPaygol"]} \n");
                $MySQL->execute("UPDATE roulette SET mobileUsed=1,prizeMobile={$response["prize"]} WHERE idPaygol='{$cupon[0]["idPaygol"]}'");
                //$MySQL->execute("UPDATE minekkit_iconomy.iconomy SET balance=balance+{$prizes[$resp]} WHERE username='{$p}'");
                $MySQL->execute("UPDATE minekkit_principal18.economy_balance SET balance=balance + {$prizes[$resp]} WHERE username_id= (SELECT a.id FROM minekkit_principal18.economy_account a WHERE name='{$p}') ");

                $response["success"] = 1;
            } else $response["success"] = -1;
            fclose($archivo);
        }
    }

} else {
    $response["success"] = 0;
}

echo json_encode($response);


?>

