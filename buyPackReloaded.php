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

function isPlayerOnline($p)
{
    try {
        $a = new MinecraftQuery();
        $a->Connect("localhost");
        foreach ($a->GetPlayers() as $player) {
            if (strtolower($player) == strtolower($p)) return true;
        }
    } catch (Exception $e) {
        return false;
    }
    return false;
}


//Se revisa si el usuario esta logeado con la clase MyBBI
/**
 * @param $id
 * @param $player
 * @return ItemsPack|RandomSet|RandomSword
 */
function getPack($id, $player)
{
    switch ($id) {
        case -1:
            $pack = new RandomSword($player);
            break;
        case -2:
            $pack = new RandomSet($player);
            break;
        case -3:
            $pack = new RandomSetCuero($player);
            break;
        case -4:
            $pack = new RandomBow($player);
            break;
        case 15000:
            $pack = new Donador15Dias($player);
            break;
        case 15001:
            $pack = new DonadorPlus15Dias($player);
            break;
        case 15002:
            $pack = new DonadorPlus1Dia($player);
            break;
        case 15003:
            $pack = new DonadorPlus3Dias($player);
            break;
        case 15004:
            $pack = new DonadorPlus7Dias($player);
            break;
        case 15005:
            $pack = new Donador1Dia($player);
            break;
        case 15006:
            $pack = new Donador3Dias($player);
            break;
        case 15007:
            $pack = new Donador7Dias($player);
            break;
        default:
            $pack = new ItemsPack($id, $player);
            break;
    }
    return $pack;
}

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