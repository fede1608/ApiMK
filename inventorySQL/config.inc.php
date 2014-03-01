<?php
/* configuration */
$host = 'localhost:3306';
$usernombre = 'root';
$pass = '';
$database = 'inventorysql';
$dbRecom= 'recompensas';
$table = 'inventorysql_users';
$tableusers = 'inventorysql_users';
$tablependings = 'inventorysql_pendings';
$tableenchantments = 'inventorysql_enchantments';
$tablebackups = 'inventorysql_backups';
$tableinventories = 'inventorysql_inventories';

function make_safe($variable) {
    $variable = mysql_real_escape_string(trim($variable)); 
    return $variable;  
}
?>
