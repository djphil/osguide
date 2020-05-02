<?php if (session_status() == PHP_SESSION_NONE) {session_start();} ?>
<?php include_once("inc/configcheck.php"); ?>
<?php include_once("inc/config.php"); ?>
<?php

if ($useSQLite === TRUE)
{
    include_once("inc/PDO-sqlite.php");
    $tbname = "osguide_destinations";

    $db->query("
        CREATE TABLE IF NOT EXISTS ".$tbname." ( 
            id              INTEGER PRIMARY KEY AUTOINCREMENT,
            region_name     VARCHAR(64),
            owner_name      VARCHAR(64),
            owner_uuid      VARCHAR(36),
            object_name     VARCHAR(64),
            object_uuid     VARCHAR(36),
            categorie_name  VARCHAR(32),
            local_position  VARCHAR(16),
            http_server_url VARCHAR(128),
            agents_online   VARCHAR(4),
            date            INT(11)
        );
    ");
}

else
{
    include_once("inc/PDO-mysql.php");
}
?>

<?php include_once("inc/functions.php"); ?>

<?php
if (isset($_GET['page'])) {$page = $_GET['page'];}
else {$page = 'home';}

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && $useGzip === TRUE) {ob_start("ob_gzhandler");} 
else ob_start();

if ($page === 'home') {require 'inc/home.php';}
else if ($page === 'destinations-wall') {require 'inc/destinations-wall.php';}
else if ($page === 'destinations-list') {require 'inc/destinations-list.php';}
else if ($page === 'destinations-details') {require 'inc/destinations-details.php';}
else if ($page === 'reorder') {require 'inc/reorder.php';}
else if ($page === 'help') {require 'inc/help.php';}
else if ($page === 'search') {require 'inc/search.php';}
else if ($page === 'login') {require 'inc/login.php';}
else if ($page === 'logout') {require 'inc/logout.php';}
else if ($page === 'admin') {require 'inc/admin.php';}
else if ($page === '404') {require 'inc/404.php';}
else require("inc/404.php");
$content = ob_get_clean();
require 'inc/template.php';
exit();
?>
