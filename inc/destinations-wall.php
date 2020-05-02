<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<h1>Wall<i class="glyphicon glyphicon-map-marker pull-right"></i></h1>
<div class="clearfix"></div>

<?php
$counter = 0;
// SET NUMBER OF COLUMNS
$columns = !empty($wall_columns) ? $wall_columns : 3;
if ($columns <= 3) $number = 4;
else if ($columns >= 4) $number = 3;
else $number = 3;

if (isset($_GET['orderby']))
{
    $random = "RAND()";
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

else {$orderby = "region_name ASC";}

if (isset($_GET['categorie']))
{
	$categorie = htmlspecialchars($_GET['categorie']);
}

else {$categorie = "All Categories";}

if ($categorie === "All Categories") {
    $sql = $db->prepare("
        SELECT * 
        FROM ".TB_DESTINATIONS." 
        ORDER BY ".$orderby."
    ");
}

else {
    $sql = $db->prepare("
        SELECT * 
        FROM ".TB_DESTINATIONS." 
        WHERE categorie_name = ?
        ORDER BY ".$orderby."
    ");
}

try {
    if ($categorie === "All Categories")
        $sql->execute();
    else $sql->execute(array($categorie));

    include_once("navbar-sub.php");

    /* SQLITE */
    if ($useSQLite === TRUE) {
        $buffer = array();
        foreach($sql as $row) {
            $buffer[] = $row;
        }
        $count = count($buffer);
    }
    else $count = $sql->rowCount();

    if ($count <= 0)
    {
        echo '<p class="alert alert-warning">0 destination found ...</p>';
    }

    if ($categorie === "All Categories")
        $sql->execute();
    else $sql->execute(array($categorie));

    echo '<div class="row text-center">';
    
    while($row = $sql->fetch(PDO::FETCH_OBJ)) 
    {
        $agents_online_counter = 0;

        if ($row->agents_online == 1)
        {
            ++$agents_online_counter;
        }

        // $id = $row->id;
        $region_name = $row->region_name;
        $owner_name = $row->owner_name ;
        $owner_uuid = $row->owner_uuid;
        // $object_name = $row['object_name'];
        // $object_uuid = $row['region_uuid'];
        $categorie_name = $row->categorie_name;
        // $local_position = $row->local_position;
        $agents_online = $row->agents_online;
        $last_update = date("d/m/Y h:i:s", $row->date);

        echo '<div class="col-xs-12 col-sm-6 col-md-'.$number.'">';
        echo '<a href="./?page=destinations-details&details='.$region_name.'" target="_self" class="btn-link">';
        echo '<div class="panel panel-default shadows">';
        echo '<div class="panel-heading"></div>';

        echo '<img class="img img-responsive text-center" src="'.getImageByName("img/", $region_name, 1).'" alt="'.$region_name.'" >';

        echo '<div class="regionname">'.$region_name.'</div>';
        echo '<div class="wall-card-body text-left">'; /*panel-body*/
        echo 'Update: <span class="pull-right">'.$last_update.'</span><br />';
        echo 'Agents online: <span class="badge pull-right">'.$agents_online.'</span><br />';
        echo '<i class="glyphicon glyphicon-folder-close"></i> '.$categorie_name.' ';
        echo '<i class="glyphicon glyphicon-user"></i> '.$owner_name.' ';
        echo '</div>';
        echo '<div class="panel-footer"></div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';

        ++$counter;
        if ($counter % $columns == 0) echo '<div class="clearfix"></div>';
    }
    echo '</div>';
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
$sql = null;
?>
<div class="clearfix"></div>
