<?php
/**
 * Created by PhpStorm.
 * User: Fede
 * Date: 25/12/2014
 * Time: 10:52 PM
 */

error_reporting(-1);
ini_set('display_errors', 'On');
define('IN_MYBB', NULL);
require 'MyBBIntegrator.php';
require_once '../foro/global.php';

$MyBBI = new MyBBIntegrator($mybb, $db, $cache, $plugins, $lang, $config);
$response["success"] = 0;
try {
    if ($_GET['tid']) {
        $response["success"] = 1;
        $news = $MyBBI->getPosts(array(
            'fields' => 'tid,subject,username,uid,message,dateline',
            'order_by' => 'dateline',
            'order_dir' => 'DESC',
            'limit_start' => 0,
            'limit' => 1,
            'where' => "fid=2 AND replyto=0 AND tid=" . $_GET['tid']));
        $a = $MyBBI->getUser($news[0]['uid']);
        $response['news'] = $news[0];
        $response['news']['avatar'] = "http://minekkit.com/foro/" . $a['avatar'];
    } else {
        $response["success"] = 1;
        $response['posts'] = $MyBBI->getPosts(array(
            'fields' => 'tid,subject,username,message,dateline',
            'order_by' => 'dateline',
            'order_dir' => 'DESC',
            'limit_start' => 0,
            'limit' => $_POST['limit'] ? $_POST['limit'] : 10,
            'where' => "fid=2 AND replyto=0"));
    }
} catch (Exception $e) {
    $response["success"] = 0;
    $response["error"] = $e->getMessage();
}

echo json_encode($response);