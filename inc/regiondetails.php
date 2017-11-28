<h1>Details<i class="glyphicon glyphicon-home pull-right"></i></h1>

<?php
if (isset($_GET['details']))
{
    $region_name  = htmlspecialchars($_GET["details"]);

    $sql = $db->prepare("
        SELECT * 
        FROM ".$tbname." 
        WHERE region_name = ?
    ");
    $sql->bindValue(1, $region_name, PDO::PARAM_STR);

    try {
        $sql->execute(array($region_name));

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
            echo '<p class="alert alert-warning">';
            echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            echo '<strong>0</strong> destination found ...</p>';
        }

        echo '<center>';

        $sql->execute(array($region_name));

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
            $object_name = $row->object_name;
            // $object_uuid = $row->object_uuid;
            $categorie_name = $row->categorie_name;
            $http_server_url = $row->http_server_url;
            $agents_online = $row->agents_online;
            // $locX = $row['locX'];
            // $locY = $row['locY'];
            // $sizeX = $row['sizeX'];
            // $sizeY = $row['sizeY'];
            // $aera = $sizeX * $sizeY;
            $local_position = $row->local_position;
            $region_aera = 65536;
            $last_update  = date("d/m/Y h:m:s", $row->date);

            echo '<div class="col-md-12">';
            echo '<div class="col-md-6">';
            echo '<div class="text-left rounded border boxer">';
            echo '<a href="secondlife://'.$region_name.'/'.$local_position.'" target="_self" style="text-decoration: none;">';
            echo '<img class="img-thumbnail img-responsive" src="'.getImageByName("img/", $region_name, 1).'" alt="'.$region_name.'" >';
            echo '</a>';
            echo '</div>';
            echo '</div>';

            echo '<div class="col-md-6">';
            echo '<div class="regiondetails rounded border">';
            echo '<p>';
            echo $region_name.' <span class="pull-right">'.$region_aera.' m²</span><br />';
            echo 'Owner name : <span class="pull-right">'.$owner_name.'</span><br />';
            echo 'Agents online : <span class="badge pull-right">'.$agents_online.'</span><br />';
            echo 'Categorie name : <span class="pull-right">'.$categorie_name.'</span><br />';
            echo 'Last Update : <span class="pull-right">'.$last_update.'</span><br />';
            echo 'Terminal Name : <span class="pull-right">'.$object_name.'</span><br />';
            echo 'Local Position : <span class="pull-right">'.$local_position.'</span><br />';
            echo '</p>';

            echo '<form class="form-horizontal" role="form" action="?refresh='.$region_name.'&url='.base64_encode($http_server_url).'" method="post">';
            echo '<a href="secondlife://'.$region_name.'/'.$local_position.'" target="_self" class="btn btn-primary"">';
            echo '<i class="glyphicon glyphicon-plane"></i> Teleport</a>';
            echo '<button type="submit" class="btn btn-success pull-right">';
            echo '<i class="glyphicon glyphicon-refresh"></i> Refresh</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="clearfix"></div>';
            echo "<hr>";
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
    $db = null;
}

else header('Location: ./');
?>
