<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['titulo']) && isset($_POST['fecha']) && isset($_POST['horario']) && isset($_POST['involucrados']) && isset($_POST['mundo']) && isset($_POST['ciudad']) && isset($_POST['tipo']) && isset($_POST['reglas']) && isset($_POST['explicacion']) && isset($_POST['solucion'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($MyBBI->checkUserPass($user, $pass)) {
        $involucrados = $_POST['involucrados'];
        $titulo = $_POST['titulo'];
        $fecha = $_POST['fecha'];
        $hora = $_POST['horario'];
        $mundo = $_POST['mundo'];
        $ciudad = $_POST['ciudad'];
        $tipo = $_POST['tipo'];
        $normas = $_POST['normas'];
        $explicacion = $_POST['explicacion'];
        $solucion = $_POST['solucion'];

        $thread = Array(
            'savedraft' => false,
            'uid' => $MyBBI->getUserId($user),
            'subject' => "[{$involucrados}] " . $titulo,
            'message' => "Fecha: {$fecha} \n
                            Hora: {$hora} \n
                            Mundo: {$mundo} \n
                            Ciudad: {$ciudad} \n
                            Tipo de Denuncia: {$tipo} \n
                            Usuarios Involucrados: {$involucrados} \n
                            Normas no cumplidas: {$normas} \n
                            Explicación del hecho: {$explicacion} \n
                            Solución propuesta: {$solucion} \n\n
                            Posteado por Minekkit Mobile App",
            'fid' => 26,
            'username' => $user,
            'options' => Array('signature' => true)

        );
        $resp = $MyBBI->createThread($thread);
        $response["success"] = isset($resp['pid'])?1:-1;
    }
}

echo json_encode($response);


?>

