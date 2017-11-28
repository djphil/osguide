<h1>Wall<i class="glyphicon glyphicon-home pull-right"></i></h1>

<?php
if ($useSQLite == TRUE) $random = "random()";
else $random = "RAND()";

if (isset($_GET['orderby']))
{
	$orderby = htmlspecialchars($_GET['orderby']);
    if ($orderby === "id")              $orderby = "id ASC";
    else if ($orderby === "region")     $orderby = "region_name ASC";
    else if ($orderby === "owner")      $orderby = "owner_name ASC";
    else if ($orderby === "categorie")  $orderby = "categorie_name ASC";
    else if ($orderby === "agents")     $orderby = "agents_online DESC";
    else if ($orderby === "date")       $orderby = "date DESC";
    else if ($orderby === "random")     $orderby = $random;
    else $orderby = $random;
}

else {$orderby = "id ASC";}

if (isset($_GET['categorie']))
{
	$categorie = htmlspecialchars($_GET['categorie']);
}

else {$categorie = "All Categories";}

if ($categorie === "All Categories") {
    $sql = $db->prepare("
        SELECT * 
        FROM ".$tbname." 
        ORDER BY ".$orderby."
    ");
}

else {
    $sql = $db->prepare("
        SELECT * 
        FROM ".$tbname." 
        WHERE categorie_name = ?
        ORDER BY ".$orderby."
    ");
}

try {
    if ($categorie === "All Categories")
        $sql->execute();
    else $sql->execute(array($categorie));

    include("orderby.php");

    /* SQLITE */
    if ($useSQLite === TRUE) {
        $buffer = array();
        foreach($sql as $row) {
            $buffer[] = $row;
        }
        $counter = count($buffer);
    }

    else $counter = $sql->rowCount();

    if ($counter == 0)
    {
        echo '<p class="alert alert-warning">0 destination found ...</p>';
    }

    echo '<center>';

    if ($categorie === "All Categories")
        $sql->execute();
    else $sql->execute(array($categorie));

    while($row = $sql->fetch(PDO::FETCH_OBJ)) 
    {
        $agents_online_counter = 0;

        if ($row->agents_online == 1)
        {
            ++$agents_online_counter;
        }
        
        $id = $row->id;
        $region_name = $row->region_name;
        $owner_name = $row->owner_name ;
        $owner_uuid = $row->owner_uuid;
        $categorie_name = $row->categorie_name;
        $agents_online = $row->agents_online;
        $local_position = $row->local_position;
        $region_aera = 65536;
        $last_update  = date("d/m/Y h:m:s", $row->date);

        echo '<div class="col-xs-12 col-sm-6 col-md-4">';
        echo '<div class="text-left rounded border boxer">';
        echo '<a href="?details='.$region_name.'" target="_self" style="text-decoration: none;">';
        echo '<img class="img-thumbnail img-responsive" src="'.getImageByName("img/", $region_name, 1).'" alt="'.$region_name.'" >';
        echo '<div class="regions ">';
        echo '<p>';
        echo $id.' : '.$region_name.' <span class="pull-right">'.$region_aera.' m²</span><br />';
        echo 'Owner name : <span class="pull-right">'.$owner_name.'</span><br />';
        echo 'Agents online : <span class="badge pull-right">'.$agents_online.'</span><br />';
        echo 'Categorie name : <span class="pull-right">'.$categorie_name.'</span><br />';
        echo 'Last Update : <span class="pull-right">'.$last_update.'</span><br />';
        echo '</p>';
        echo '</div>';
        echo '</a>';
        echo '</div>';
        echo '</div>';
    }

    $db = null;
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
$sql = null;
?>
