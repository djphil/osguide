<h1>Home<i class="glyphicon glyphicon-home pull-right"></i></h1>

<?php include_once("nav.php"); ?>

<?php
$categories = getDefaultDestinationCategories();

foreach ($categories as $categorie)
{
    if ($categorie === "All Categories")
    {
        $sql = $db->prepare("
            SELECT categorie_name 
            FROM ".$tbname." 
        ");
    }

    else
    {
        $sql = $db->prepare("
            SELECT categorie_name 
            FROM ".$tbname." 
            WHERE categorie_name = ?
        ");

        $sql->bindValue(1, $categorie, PDO::PARAM_STR);
    }


    try {
        if ($categorie === "All Categories")
            $sql->execute();
        else $sql->execute(array($categorie));

        if ($useSQLite === TRUE) {
            $buffer = array();
            foreach($sql as $row) {
                $buffer[] = $row;
            }
            $counter = count($buffer);
        }
        else $counter = $sql->rowCount();

        echo '<div class="col-xs-12 col-sm-6 col-md-4">';
        echo '<div class="text-left rounded border boxer">';
        echo '<a href="./?categorie='.$categorie.'" style="text-decoration: none;">';
        echo '<img class="img-thumbnail" src="'.getImageByName("img/", $categorie, 1).'" alt="'.$categorie.'" >';
        echo '<div class="regions">';
        echo '<p>'.$categorie.' <span class="badge pull-right">'.$counter.'</span></p>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    catch(PDOException $e) {
        $message = '
            <pre>
                Unable to query database ...
                Error code: '.$e->getCode().'
                Error file: '.$e->getFile().'
                Error line: '.$e->getLine().'
                Error data: '.$e->getMessage().'
            </pre>
        ';
        die($message);
    }

    echo '</center>';
}

$sql = null;
$db = null;
?>
