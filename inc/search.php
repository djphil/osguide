<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<h1>Search<i class="glyphicon glyphicon-search pull-right"></i></h1>
<div class="clearfix"></div>

<?php
if (isset($_POST['search']))
{
    if (!empty($_POST['searchword']))
    {
        $search_word  = htmlspecialchars($_POST['searchword']);
        $query = ('
            SELECT * 
            FROM '.TB_DESTINATIONS.' 
            WHERE region_name LIKE ?
            OR owner_name LIKE ?
            OR owner_uuid LIKE ? 
            OR object_name LIKE ? 
            OR object_uuid LIKE ? 
            OR categorie_name LIKE ? 
        ');
        $query = $db->prepare($query);

        $value = "%{$search_word}%";
        $query->bindValue(1, $value, PDO::PARAM_STR);
        $query->bindValue(2, $value, PDO::PARAM_STR);
        $query->bindValue(3, $value, PDO::PARAM_STR);
        $query->bindValue(4, $value, PDO::PARAM_STR);
        $query->bindValue(5, $value, PDO::PARAM_STR);
        $query->bindValue(6, $value, PDO::PARAM_STR);

        $query->execute();

        if ($useSQLite == TRUE) $count = $query->rowCount() >= 0;
        else $count = $query->rowCount() != 0;

        if ($count)
        {
            $counter = null;

            echo '<table class="table table-striped table-hover">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>#</th>';
            echo '<th>Destination name</th>';
            echo '<th>Category name</th>';
            echo '<th>Terminal name</th>';
            echo '<th>Owner name</th>';
            echo '<th class="text-right">Details</th>';
            echo '</tr>';
            echo '</thead>';

            echo '<tbody>';
            while ($result = $query->fetch()) 
            {
                $region_name    = $result['region_name'];
                // $categorie_name = $result['categorie_name'];
                // $object_name    = $result['object_name'];
                // $owner_name     = $result['owner_name'];
                // $owner_uuid  = $result['owner_uuid'];
                // $object_uuid = $result['object_uuid'];
                echo '<tr>';
                // echo '<td>'.$counter.'</td>';
                // echo '<td>'.$region_name.'</td>';
                // echo '<td>'.$owner_name.'</td>';
                // echo '<td>'.$object_name.'</td>';
                // echo '<td>'.$categorie_name.'</td>';
                echo '<td>'.++$counter.'</td>';
                echo '<td>'.$region_name.'</td>';
                echo '<td>'.$result['categorie_name'].'</td>';
                echo '<td>'.$result['object_name'].'</td>';
                echo '<td>'.$result['owner_name'].'</td>';
                echo '<td class="text-right">';
                echo '<a class="btn btn-primary btn-xs" href="?page=destinations-details&details='.$region_name.'">';
                echo '<i class="glyphicon glyphicon-info-sign "></i> More infos</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo "</table>";
        }

        else echo '<p>Result found: <span class="badge">0</span></p>';
    }

    else 
    {
        $_SESSION['flash']['warning'] = "Please enter a search word ...";
        echo '<p>Result found: <span class="badge">0</span></p>';
    }
}

else
{
    $_SESSION['flash']['danger'] = "Please enter a search query ...";
    echo '<p>Result found: <span class="badge">0</span></p>';
}

unset($region_name);
unset($owner_name);
// unset($owner_uuid);
unset($object_name);
// unset($object_uuid);
unset($categorie_name);
$query = null;
?>
