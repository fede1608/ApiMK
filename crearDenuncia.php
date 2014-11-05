<?php
require 'inventorySQL/mysql.inc.php';
require 'inventorySQL/config.inc.php';
define('IN_MYBB', NULL);
require_once '../foro/global.php';
require 'MyBBIntegrator.php';


function random_str2($length="8")
{
    $set = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
    $str = '';

    for($i = 1; $i <= $length; ++$i)
    {
        $ch = my_rand(0, count($set)-1);
        $str .= $set[$ch];
    }

    return $str;
}

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);

$MySQL = new SQL($host, $usernombre, $pass, $dbRecom);

$response["success"] = 0;
if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['titulo']) && isset($_POST['fecha']) && isset($_POST['horario']) && isset($_POST['involucrados']) && isset($_POST['mundo']) && isset($_POST['ciudad']) && isset($_POST['tipo']) && isset($_POST['reglas']) && isset($_POST['explicacion']) && isset($_POST['solucion'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($MyBBI->checkUserPass($user, $pass)) {
        $involucrados = $_POST['involucrados'];
        $titulo = utf8_encode($_POST['titulo']);
        $fecha = $_POST['fecha'];
        $hora = $_POST['horario'];
        $mundo = utf8_encode($_POST['mundo']);
        $ciudad = utf8_encode($_POST['ciudad']);
        $tipo = utf8_encode($_POST['tipo']);
        $normas = utf8_encode($_POST['reglas']);
        $explicacion = utf8_encode($_POST['explicacion']);
        $solucion = utf8_encode($_POST['solucion']);

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
            'options' => Array('signature' => true),
            'posthash' => md5($MyBBI->getUserId($user).random_str2())

        );
        $resp = $MyBBI->createThreadWithAttach($thread,$_FILES);
        $response["success"] = isset($resp['pid']) ? 1 : -1;
        $archivo = fopen("photoTest.txt", "a");
        fputs($archivo, print_r($_FILES,true));
        fputs($archivo, print_r($resp,true));
        fputs($archivo, print_r("\n",true));
        fclose($archivo);
    }
}

echo json_encode($response);


?>

