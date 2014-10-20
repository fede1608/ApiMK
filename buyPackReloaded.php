<?php

error_reporting(-1);
ini_set('display_errors', 'On');
define('IN_MYBB', null);
define('ROOT_PATH',"/var/zpanel/hostdata/zadmin/public_html/minekkit_com/");

require_once ROOT_PATH.'recompensas/libs/config.inc.php';
require_once Config::$Root_Path.'foro/global.php';
require_once Config::$Root_Path.'recompensas/libs/items.php';
require_once Config::$Root_Path.'recompensas/libs/meekrodb.2.2.class.php';
require_once Config::$Root_Path.'recompensas/class/ItemsPack.php';
require_once Config::$Root_Path.'recompensas/class/Item.php';
require_once Config::$Root_Path.'recompensas/class/RandomSet.php';
require_once Config::$Root_Path.'recompensas/class/RandomSword.php';
require_once Config::$Root_Path.'recompensas/class/RandomSetCuero.php';
require_once Config::$Root_Path.'recompensas/class/RandomBow.php';
require_once Config::$Root_Path.'recompensas/class/Donador15Dias.php';
require_once Config::$Root_Path.'recompensas/class/DonadorPlus15Dias.php';
require_once Config::$Root_Path.'recompensas/class/DonadorPacks.php';
require_once Config::$Root_Path.'recompensas/libs/MinecraftQuery.class.php';
date_default_timezone_set("America/Argentina/Buenos_Aires");

DB::$user = Config::$userDB;
DB::$password = Config::$passDB;
DB::$dbName = Config::$DBinvsql;

require_once 'MyBBIntegrator.php';
$archivo = fopen("../Logs/MobileShopLogs.txt", "a");
date_default_timezone_set("America/Argentina/Buenos_Aires");

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$price = 0;
$response["success"] = 0;
$response["recoplas"] = 0;
$response["descuento"] = 0;
$response["mensaje"]="";

$msgExit = '';

require_once '../recompensas/functions.php';

if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['id'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $id = $_POST['id'];

    if ($MyBBI->checkUserPass($user, $pass)) {

            if (!isPlayerOnline($user)) {
                $logger = new Logger("../Logs/WebShopLogs.txt");
                $money = $MyBBI->getMoney($user);

                $pack = getPack($id, $user);

                $discount = $pack->getDiscount();
                $response["descuento"] = (1 - $discount) * 100;
                //show discounts if not donador or donadorplus 15dias


                $estado = "Sin Dinero";
                if ($pack->hasEnoughMoney($money)) {
                    $MyBBI->updateMoney($MyBBI->getUserId($user), ($money - $pack->getPrice()), true);
                    $estado = "Dinero suficiente";
                    $response["recoplas"] = ($money - $pack->getPrice());
                    $pack->process();

                    $msgExit .= "Has adquirido: " . $pack->name . ' por: ' . $pack->getPrice() . ' Recoplas. Ya se ha cargado tu pedido, reloggea en Minekkit para recibir tu compra. Gracias por jugar en Minekkit! Tu nuevo saldo es de: ' . ($money - $pack->getPrice());
                    if ($discount < 1) {
                        $msgExit .= "Tuviste un Descuento del " . (100 - ($discount * 100)) . "% ";
                    }
                    $response['success'] = 1;
                } else {
                    $msgExit = "No tienes Suficiente Dinero.";
                };
                $logger->info("[MOBILE] -Id venta: " . $_GET['id'] .
                    " -PackName: " . $pack->name . " -User: " . $mybb->user['username'] .
                    " -Estado: " . $estado . " -DineroPrevio:" . $money . " -Precio: " . $pack->getPrice());
            } else
                $msgExit = "No se ha acreditado el Pack! Debes desloggear tu cuenta del juego para poder comprar.";

        }

}

$response['mensaje']=$msgExit;
echo json_encode($response);


?>