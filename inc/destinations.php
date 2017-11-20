<?php
include_once("config.php");
include_once("functions.php");

if ($useSQLite === TRUE)
    include_once("PDO-sqlite.php");
else include_once("PDO-mysql.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/osguide-inworld.css">
    <title><?php echo $osguide; ?></title>
</head>

<body>

<?php
if (isset($_GET['categorie'])) 
{
	$categorie_number   = htmlspecialchars($_GET['categorie']);
    $categorie_name     = getCategorieByNumber($categorie_number);
    $categorie_number   = getCategorieByName($categorie_name);

    echo "<h1>";
    echo '<a href="destinations.php">'.$osguide.' : </a>'.$categorie_name;
    echo '<span><a href="destinations.php?categories">Back to Categories</a></span>';
    echo "</h1>";
    echo '<hr class="up">';
    echo '<hr class="down">';
    echo '<div id="regionguide_container">';
    echo '<div class="boxer radius">';

    if ($categorie_number == 0 OR $categorie_number == -1)
    {
        $query = $db->prepare("
            SELECT categorie_name, region_name, local_position, agents_online
            FROM ".$tbname." 
        ");
    }

    else
    {
        $query = $db->prepare("
            SELECT categorie_name, region_name, local_position, agents_online
            FROM ".$tbname." 
            WHERE categorie_name 
            LIKE '%{$categorie_name}%'
        ");
    }

    $query->execute();

    /* SQLITE */
    if ($useSQLite === TRUE) {
        $buffer = array();
        foreach($query as $row) {
            $buffer[] = $row;
        }
        $counter = count($buffer);
    }
    else $counter = $query->rowCount();

    if ($counter == 0)
    {
        echo '<p class="alert red">0 destination found ...</p>';
        exit;
    }

    $query->execute();

    while ($row = $query->fetch(PDO::FETCH_ASSOC))
    {
        $region_name    = $row['region_name'];
        $categorie_name = $row['categorie_name'];
        $local_position = $row['local_position'];
        $agents_online  = $row['agents_online'];

        echo '<div class="regionspics shadows radius">';
        echo '<div class="boxer radius">';
        echo '<hr class="up"><hr class="down">';
        echo '<a href="secondlife://'.$region_name.'/'.$local_position.'" target="_self" style="text-decoration: none;">';
        echo '<img class="" src="'.getImageByName("../img/", $region_name, 2).'" alt="'.$region_name.'" >';
        echo '<div class="regions">';
        echo '<hr class="up">';
        echo '<hr class="down">'.$region_name;
        echo '<span>'.$agents_online.'</span>';
        echo '<hr class="up">';
        echo '<hr class="down">';
        echo '<hr class="up">';
        echo '<hr class="down">';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    unset($categorie_name);
    unset($categorie_number);
}

else
{
    echo '<h1>';
    echo '<a href="destinations.php">'.$osguide.' :</a> Categories';
    echo '<span><a href="secondlife://Digital Concepts/128/128/25" target="_self" style="text-decoration: none;">Teleport to OpenSim</a></span>';
    echo '</h1>';
    echo '<hr class="up">';
    echo '<hr class="down">';
    echo '<div id="regionguide_container">';
    echo '<div class="boxer radius">';

    $categories = getDefaultDestinationCategories();

    foreach ($categories as $categorie_number => $categorie_name)
    {
        if ($categorie_number == 0 OR $categorie_number == -1)
        {
            $query = $db->prepare("
                SELECT categorie_name
                FROM ".$tbname." 
            ");
        }

        else
        {
            $query = $db->prepare("
                SELECT categorie_name 
                FROM ".$tbname." 
                WHERE categorie_name 
                LIKE '%{$categorie_name}%'
            ");
        }

        $query->execute();

        /* SQLITE */
        if ($useSQLite === TRUE) {
            $buffer = array();
            foreach($query as $row) {
                $buffer[] = $row;
            }
            $counter = count($buffer);
        }
        else $counter = $query->rowCount();

        echo '<div class="regionspics shadows radius">';
        echo '<div class="boxer radius">';
        echo '<hr class="up"><hr class="down">';
        echo '<a href="destinations.php?categorie='.$categorie_number.'" target="_self" style="text-decoration: none;">';
        echo '<img class="" src="'.getImageByName("../img/", $categorie_name, 2).'" alt="'.$categorie_name.'" >';
        echo '<div class="regions">';
        echo '<hr class="up">';
        echo '<hr class="down">'.$categorie_name;
        echo '<span>'.$counter.'</span>';
        echo '<hr class="up">';
        echo '<hr class="down">';
        echo '<hr class="up">';
        echo '<hr class="down">';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';

    unset($categorie_name);
    unset($categorie_number);
}
$query = null;
?>

</body>
</html>
