<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<h1>Details<i class="glyphicon glyphicon-map-marker pull-right"></i></h1>
<div class="clearfix"></div>

<?php
if (isset($_GET['refresh']))
{
    $region_name = htmlspecialchars($_GET["refresh"]);
    $http_server_url = base64_decode(htmlspecialchars($_GET["url"]));

    try {
        $sql = $db->prepare("
            SELECT http_server_url,
                   region_name 
            FROM ".TB_DESTINATIONS." 
            WHERE http_server_url = ?
        ");
        $sql->execute(array($http_server_url));

        while($row = $sql->fetch(PDO::FETCH_OBJ)) 
        {
            $http_server_url = $row->http_server_url;
            $region_name = $row->region_name;
        }

        $data = "PING";
        $url = $http_server_url;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        // curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_setopt($ch, CURLOPT_USERAGENT, "osquide");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // echo '[ACTION] '.$data.' '.$region_name;
        $result = curl_exec($ch);
        // debug($result);

        if ($result <> "OK")
        {
            $info = curl_getinfo($ch);
            echo '<p class="alert alert-danger">';
            echo 'Error in curl_exec : '.curl_error($ch).'<br />'; // exit();
            echo 'La requête a mis ' . $info['total_time'] . ' secondes à être envoyée à ' . $info['url'];
            echo '</p>';

            // DELETE
            try {
                $sql = $db->prepare("
                    DELETE 
                    FROM ".TB_DESTINATIONS." 
                    WHERE http_server_url = ?
                ");
                $sql->execute(array($http_server_url));
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

        curl_close($ch);
        $sql = null;
        $db = null;
        header('Location: ./?page=destinations-details&details='.$region_name.'');
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

if (isset($_GET['details']))
{
    $region_name  = htmlspecialchars($_GET["details"]);

    $sql = $db->prepare("
        SELECT * 
        FROM ".TB_DESTINATIONS." 
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

        $sql->execute(array($region_name));

        echo '<div class="row">';

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
            $object_name = trucate($row->object_name, 40);
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
            $last_update = date("d/m/Y h:i:s", $row->date);

            echo '<div class="col-md-6">';
            echo '<div class="panel panel-default">';
            echo '<div class="panel-heading"></div>';
            echo '<div class="card-body">';
            echo '<img class="img img-responsive" src="'.getImageByName("img/", $region_name, 1).'" alt="'.$region_name.' by '.$owner_name.'" >';
            echo '</div>';
            echo '<div class="panel-footer">';
            echo '<a href="secondlife://'.$region_name.'/'.$local_position.'" target="_self" class="btn btn-default btn-block">';
            echo '<i class="glyphicon glyphicon-plane"></i> Teleport</a>';
            echo'</div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="col-md-6">';
            echo '<div class="panel panel-default">';
            echo '<div class="panel-heading"></div>';

            echo '<ul class="list-group">';
            echo '<li class="list-group-item">Destination name <span class="pull-right">'.$region_name.'</span></li>';
            echo '<li class="list-group-item">Categorie name <span class="pull-right">'.$categorie_name.'</span></li>';
            echo '<li class="list-group-item">Terminal name <span class="pull-right">'.$object_name.'</span></li>';
            echo '<li class="list-group-item">Local position <span class="pull-right">'.$local_position.'</span></li>';
            echo '<li class="list-group-item">Owner name <span class="pull-right">'.$owner_name.'</span></li>';
            echo '<li class="list-group-item">Agents online <span class="badge pull-right">'.$agents_online.'</span></li>';
            echo '<li class="list-group-item">Last Update';
            echo '<form class="form-inline pull-right" role="form" action="./?page=destinations-details&refresh='.$region_name.'&url='.base64_encode($http_server_url).'" method="post">';
            echo '<button type="submit" class="btn btn-success btn-xs btn-refresh-details">';
            echo '<i class="glyphicon glyphicon-refresh"></i> </button>'; // Refresh
            echo '</form> ';
            echo '<span class="pull-right">'.$last_update.'</span>';
            echo '</li>';
            echo '</ul>';

            echo '<div class="panel-footer"></div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="clearfix"></div>';
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
}
?>
<div class="clearfix"></div>
