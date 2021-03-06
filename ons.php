<?php

include('../recompensas/libs/status.class.php');
include('libs/MinecraftQuery.class.php');

$competencia = array();
$competencia['Minespazio']['host'] = "142.4.217.160";
$competencia['Minespazio']['port'] = "25565";

$competencia['Baragcraft']['host'] = "198.27.89.12";
$competencia['Baragcraft']['port'] = "25565";

/*$competencia['Minehard']['host']="";
$competencia['Minehard']['port']="";*/


$servers = array();
$server['Total'] = 25565;
$server['Principal'] = 25566;
$server2['Creativo'] = 25567;
$server2['KitPvP'] = 25568;
$server['SurvivalHardcore'] = 25569;
$server2['HungerGames'] = 25570;
$server['Skywars'] = 25571;
$server['Hub'] = 25572;
$server['EDLB'] = 25573;
$server2['Skyblock'] = 25575;
$status = new MinecraftServerStatus(); // call the class
$response["success"] = 1;

$response1 = $status->getStatus('minekkit.com'); // call the function
$response["playersOnline"] = ($response1['players']) ? $response1['players'] : 0;
foreach ($server as $sv => $port) {
    try {
        $Query = new MinecraftQuery();
        $Query->Connect("localhost", $port);
        $info = $Query->GetInfo();
        $players[$sv] = $info['Players'];
    } catch (Exception $ex) {
        $players[$sv] = 0;
    }
}
foreach ($server2 as $sv => $port) {
    try {
        $Query = new MinecraftQuery();
        $Query->Connect("h5.linekkit.com", $port);
        $info = $Query->GetInfo();
        $players[$sv] = $info['Players'];
    } catch (Exception $ex) {
        $players[$sv] = 0;
    }
}

$response["servers"] = $players;

/*foreach($competencia as $name => $sv){
    try {
        $response1 = $status->getStatus($sv['host'],"1.7.*","".$sv['port']); // call the function
        $svs[$name]=($response1['players']) ? $response1['players'] : 0 ;
//        $Query = new MinecraftQuery();
//        $Query->Connect($sv['host'], $sv['port']);
//        $info = $Query->GetInfo();
//        $svs[$name] = $info['Players'];
    }catch (Exception $ex){
        $svs[$name] = 0;
    }

}
$response["competencia"] = $svs;*/


echo json_encode($response);