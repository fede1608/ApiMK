<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', null);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';
$dbMinekkit = "minekkit";
require 'inventorySQL/items1.3.2.php';
$archivo = fopen("Logs/MobileShopLogs.txt", "a");
date_default_timezone_set("America/Argentina/Buenos_Aires");
$price = 0;

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$MySQL = new SQL($host, $usernombre, $pass, $dbMinekkit);

function esDonador($unString)
{
    if (preg_match("/(Donador\-15Dias)/", $unString, $datareco))
        return 0.9;
    if (preg_match("/(DonadorPlus\-15Dias)/", $unString, $datareco))
        return 0.8;
    if (preg_match("/(Donador\-)([0-9a-zA-Z]+)/", $unString, $datareco))
        return 0.8;
    if (preg_match("/(DonadorPlus\-)([0-9a-zA-Z]+)/", $unString, $datareco))
        return 0.6;
    return 1;
}

$response["success"] = 0;
$response["recoplas"] = 0;
$response["descuento"] = 0;

if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['id'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $id = $_POST['id'];

    if ($MyBBI->checkUserPass($user, $pass)) {
        //$user = $MyBBI->getUser($MyBBI->getUserId($user));
        $money = $MyBBI->getMoney($user);
        $discount = 1;

        $paquetes = $MySQL->execute("SELECT * FROM t00thtransactions.`toothpackages` WHERE `player`='" . $user .
            "' AND (`status`=1 OR `status`=2) ORDER BY `id`");
        if (isset($paquetes[0])) {
            $discount = esDonador($paquetes[0]['package']);
            $response["descuento"] = (1-$discount)*100;
        }

        $users = $MySQL->execute("SELECT `id`, `name` FROM inventorysql.`" . $tableusers . "`");

        $pack = $MySQL->execute("SELECT * FROM recompensas.infoPack WHERE id=" . intval($id));

        $price = intval($discount * $pack[0]['cost']);
        if ($money >= $price) {
            $MyBBI->updateMoney($MyBBI->getUserId($user), ($money - $price), true);
            $response["recoplas"]= ($money - $price);
            $items = $MySQL->execute("SELECT * FROM recompensas.infoItem WHERE id=" . intval($id));
            foreach ($items as $item) {
                $correcto = 1;
                while ($correcto) {
                    $uuid = gen_uuid();
                    $consulta = $MySQL->execute("SELECT * FROM inventorysql.`inventorysql_inventories` WHERE id=\"" .
                        $uuid . "\"");
                    if (!isset($consulta[0]))
                        $correcto = 0;
                }

                switch ($item['funcion']) {
                    case 1:
                        addItem($uuid, $user, $item['itemid'], $item['itemData'], $item['cantidad']);
                        break;
                    case 2:
                        $itemdata = isset($_POST['eid']) ? $_POST['eid'] : $item['itemData'];
                        addEgg($uuid, $mybb->user['username'], $item['itemid'], $itemdata, $item['cantidad']);
                        break;
                    case 3:
                        $eid = isset($_POST['eid']) ? $_POST['eid'] : $item['itemData'];
                        addPotion($uuid, $user, 373, $eid, $item['cantidad']);
                        break;
                }
                $hechizos = $MySQL->execute("SELECT * FROM recompensas.infoEnchant WHERE itemid=" .
                    $item['orden']);

                foreach ($hechizos as $hech) {
                    addEnchantment($uuid, $hech['tipo'], $hech['level']);
                }
                $metas = $MySQL->execute("SELECT * FROM recompensas.infoMeta WHERE itemid=" . $item['orden']);
                $con = 0;
                foreach ($metas as $meta) {
                    $con++;
                    addmeta($uuid, $meta['tipo'], $meta['contenido']);
                }
                addmeta($uuid, "Lore_" . $con, "Propiedad de: " . $user);

            }
            $estado = "Dinero suficiente Uuid:" . $uuid;
            $response['success']=1;
        } else {
            $estado = "Sin Dinero";
            //"No tienes Suficiente Dinero";
            $response['success']=-1;
        }
        ;
        fputs($archivo, date(DATE_RFC822) . ": -Id venta: " . $id .
            " -PackName: " . $pack[0]['packName'] . " -User: " . $user .
            " -Estado: " . $estado . " -DineroPrevio:" . $money . " -Precio: " . $price . "\n");
        fclose($archivo);
        
    }
}


echo json_encode($response);


?>