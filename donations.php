<?php

require 'inventorySQL/config.inc.php';
require 'libs/meekrodb.2.2.class.php';
define('IN_MYBB', NULL);
require 'MyBBIntegrator.php';
require_once '../foro/global.php';
$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
DB::$user = $usernombre;
DB::$password = $pass;
DB::$dbName = "economia";

$totalExpected = 1200;

$result = DB::query(' SELECT SUM( CASE  WHEN `currency` = 0 THEN `valor` WHEN `currency`=1 THEN valor*10  END ) AS suma FROM donaciones WHERE (descripcion like "%Donador%" OR descripcion like "%Recoplas%")AND `fecha` >= UNIX_TIMESTAMP((LAST_DAY(NOW())+INTERVAL 1 DAY)-INTERVAL 1 MONTH)
  AND `fecha` <  UNIX_TIMESTAMP(LAST_DAY(NOW())+INTERVAL 1 DAY) ');

$dbTooth = "t00thtransactions";
$sms = DB::query('SELECT player,count(*) as sms FROM %l.`toothtransaction` WHERE ipn like "%fm" AND Unix_timestamp(`timestamp`) >= Unix_timestamp(( Last_day(Now()) + interval 1 day ) -
                                       interval 1 month)
       AND Unix_timestamp(`timestamp`) < Unix_timestamp(Last_day(Now()) + interval 1 day) GROUP BY player ORDER BY 2 DESC Limit 5', $dbTooth);

$topDonador = DB::query('SELECT player, SUM( amount ) AS donaciones
FROM  %l.`toothtransaction`
WHERE ipn NOT LIKE  "%fm"
AND UNIX_TIMESTAMP(  `timestamp` ) >= UNIX_TIMESTAMP( (
LAST_DAY( NOW( ) ) + INTERVAL 1
DAY ) - INTERVAL 1
MONTH
)
AND UNIX_TIMESTAMP(  `timestamp` ) < UNIX_TIMESTAMP( LAST_DAY( NOW( ) ) + INTERVAL 1
DAY )
GROUP BY player
ORDER BY 2 DESC
LIMIT 1', $dbTooth);
$exit['donated'] = $result[0]['suma'] / $totalExpected * 100;
$exit['donated'] = sprintf('%0.2f', $exit['donated']) * 1;

$a = $MyBBI->getUser($MyBBI->getUserId($topDonador[0]['player']));

$exit['topDonador'] = $topDonador[0];
$exit['topDonador']['userId'] = $MyBBI->getUserId($topDonador[0]['player']);
$exit['topDonador']["avatar"] = strlen($a['avatar']) != 0 ? "http://minekkit.com/foro/" . $a['avatar'] : "https://minotar.net/avatar/{$exit['topDonador']['userId']}/100.png";

$sms[0]['userId'] = $MyBBI->getUserId($sms[0]['player']);
$exit['topSms'] = $sms;
echo json_encode($exit);
