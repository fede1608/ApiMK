<?php
const LIMIT = 50;
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', null);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($MyBBI->checkUserPass($user, $pass)) {
        $response["success"] = 1;
        if (isset($_POST['idpm'])) {
            $pm = $MyBBI->getPrivateMessage($_POST['idpm']);
            $arrpm[0]["title"] = $pm[0]['subject'];
            $arrpm[0]["from"] = $pm[0]['fromusername'];
            $arrpm[0]["pmid"] = $pm[0]['pmid'];
            $arrpm[0]["date"] = $pm[0]['dateline'];
            $arrpm[0]["content"] = $pm[0]['message'];
            $arrpm[0]["read"] = $pm[0]['status'] > 0 ? 1 : 0;
            $response["pms"] = $arrpm;
        } else {
            $pms = $MyBBI->getPrivateMessagesOfUser($MyBBI->getUserId($user));
            $response["success"] = 1;
            $c = 0;
            foreach ($pms['Bandeja de entrada'] as $pm) {
                if (isset($_GET['read']) && $_GET['read'] == 1 && $pm['status'] > 0)
                    break;
                $arrpm[$c]["title"] = $pm['subject'];
                $arrpm[$c]["from"] = $pm['fromusername'];
                $arrpm[$c]["pmid"] = $pm['pmid'];
                $arrpm[$c]["date"] = $pm['dateline'];
                $arrpm[$c]["read"] = $pm['status'] > 0 ? 1 : 0;
                $c++;
                if ($c >= LIMIT)
                    break;
            }
            $response["pms"] = $arrpm;
        }
    }
}
echo json_encode($response);


?>

