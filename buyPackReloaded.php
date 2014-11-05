<?php


require_once 'MyBBIntegrator.php';
require_once '../recompensas/header.php';
require_once '../recompensas/functions.php';
ini_set('display_errors', 'Off');
$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$price = 0;
$response["success"] = 0;
$response["recoplas"] = 0;
$response["descuento"] = 0;
$response["mensaje"]="";

$msgExit = '';



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
                $logger->info("[MOBILE] -Id venta: " . $id .
                    " -PackName: " . $pack->name . " -User: " . $user  .
                    " -Estado: " . $estado . " -DineroPrevio:" . $money . " -Precio: " . $pack->getPrice());
            } else
                $msgExit = "No se ha acreditado el Pack! Debes desloggear tu cuenta del juego para poder comprar.";

        }

}

$response['mensaje']=$msgExit;
echo json_encode($response);


?>