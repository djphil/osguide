<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<h1>Home<i class="glyphicon glyphicon-map-marker pull-right"></i></h1>
<div class="clearfix"></div>

<?php include_once("navbar-nav.php"); ?>

<?php
$counter = 0;
// SET NUMBER OF COLUMNS
$columns = !empty($cats_columns) ? $cats_columns : 3;
if ($columns <= 3) $number = 4;
else if ($columns >= 4) $number = 3;
else $number = 3;

echo '<div class="row">';
foreach ($destination_category_names as $categorie)
{
    if ($categorie === "All Categories")
    {
        $sql = $db->prepare("
            SELECT categorie_name 
            FROM ".TB_DESTINATIONS." 
        ");
    }

    else
    {
        $sql = $db->prepare("
            SELECT categorie_name 
            FROM ".TB_DESTINATIONS." 
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
            $count = count($buffer);
        }
        else $count = $sql->rowCount();

        echo '<div class="col-xs-12 col-sm-6 col-md-'.$number.'">';
        echo '<a href="./?page=destinations-wall&categorie='.$categorie.'" style="text-decoration: none;">';
        echo '<div class="panel panel-default shadows">';
        echo '<div class="panel-heading">';
        echo '<h3 class="panel-title">';
        echo '<strong>'.$categorie.'</strong> <span class="badge pull-right">'.$count.'</span>';
        echo '</h3>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<img class="img img-responsive" src="'.getImageByName("img/", $categorie, 1).'" alt="'.$categorie.'" >';
        echo '</div>';
        echo '<div class="panel-footer"></div>';
        echo '</div>';
        echo '</a>';
        echo '</div>';

        ++$counter;
        if ($counter % $columns == 0) echo '<div class="clearfix"></div>';
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
}
echo '</div>';

$sql = null;
$db = null;
?>
<div class="clearfix"></div>