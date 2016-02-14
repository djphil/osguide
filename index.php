<?php include_once("inc/config.php"); ?>
<?php include_once("inc/functions.php"); ?>
<?php include_once("inc/header.php"); ?>
<?php include_once("inc/navbar.php"); ?>

<?php
ini_set('magic_quotes_gpc', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<div class="github-fork-ribbon-wrapper left">
    <div class="github-fork-ribbon">
        <a href="https://github.com/djphil/osguide" target="_blank">Fork me on GitHub</a>
    </div>
</div>
    
<!-- Fash Message -->
<?php if(isset($_SESSION['flash'])): ?>
    <?php foreach($_SESSION['flash'] as $type => $message): ?>
        <div class="alert alert-<?php echo $type; ?> alert-anim">
            <?php echo $message; ?>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<?php
if ($useSQLite === TRUE)
{
    include_once("inc/PDO-sqlite.php");

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
            date            CURRENT_TIMESTAMP	
        );
    ");
}

else
{
    include_once("inc/PDO-mysql.php");
}

if (isset($_GET['home'])) $page = 1;
else if (isset($_GET['categorie'])) $page = 2;
else if (isset($_GET['search'])) $page = 3;
else if (isset($_GET['help'])) $page = 4;
else if (isset($_GET['details'])) $page = 5;
else if (isset($_GET['refresh'])) $page = 6;
else {$page = 1;} 

echo '<div class="content">';
if ($page == 1) require("inc/categories.php");
else if ($page == 2) require("inc/regionswall.php");
else if ($page == 3) require("inc/search.php");
else if ($page == 4) require("inc/help.php");
else if ($page == 5) require("inc/regiondetails.php");
else if ($page == 6) require("inc/refresh.php");
else require("inc/404.php");
echo '</div>';
?>

<?php include_once("inc/footer.php"); ?>
