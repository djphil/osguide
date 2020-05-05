<?php
include_once("config.php");
include_once("functions.php");

if ($useSQLite === TRUE) include_once("PDO-sqlite.php");
else include_once("PDO-mysql.php");

if (isset($_POST['terminal']))
{
    $action = htmlspecialchars($_POST["terminal"]);

    if ($action == "register")
    {
        foreach ($_SERVER as $key => $value)
        {
            if(substr($key, 0, 5) == 'HTTP_')
            {
                if ($key === "HTTP_HOST")
                    $http_host = $value;
                if ($key === "HTTP_X_SECONDLIFE_REGION")
                    $region_name = $value;
                if ($key === "HTTP_X_SECONDLIFE_OWNER_NAME")
                    $owner_name = $value;
                if ($key === "HTTP_X_SECONDLIFE_OWNER_KEY")
                    $owner_uuid = $value;            
                if ($key === "HTTP_X_SECONDLIFE_OBJECT_NAME")
                    $object_name = $value;
                if ($key === "HTTP_X_SECONDLIFE_OBJECT_KEY")
                    $object_uuid = $value;
                if ($key === "HTTP_X_SECONDLIFE_LOCAL_POSITION")
                    $local_position = $value;
            }
        }

        if ($limit_hosts === TRUE && !in_array($http_host, $white_hosts))
        {
            $file_content = "HOST_RESTRICTION";
            echo $file_content;
            $file_content .= " " .date("d/m/Y h:i:s A")." *** ".$http_host." ***\n";
            if ($create_log === TRUE) {create_logs_file($access_log, $file_content, $debug);}
            return;
        }

        // $categorie_number = htmlspecialchars($_POST["categorie_number"]);
        $categorie_name = htmlspecialchars($_POST["categorie_name"]);

        if (strtolower($categorie_name) == "official location")
        {
            if ($limit_uuids === TRUE && !in_array($owner_uuid, $white_uuids))
            {
                $file_content = "UUID_RESTRICTION";
                echo $file_content;
                $file_content .= " " .date("d/m/Y h:i:s A")." *** ".$owner_uuid." ***\n";
                if ($create_log === TRUE) {create_logs_file($access_log, $file_content, $debug);}
                return;
            }
        }

        $buffer = explode(" (", $region_name);
        $region_name = $buffer[0];
        $region_coord = "(".$buffer[1];

        // $agents_online = [];
        $agents_list = base64_decode(htmlspecialchars($_POST["agents_list"]));
        $agents_online = explode(',', $agents_list);

        if ($agents_online[0] == "") $agents_online = 0;
        else $agents_online = count($agents_online);

        $http_server_url = base64_decode(htmlspecialchars($_POST["http_server_url"]));
        $buffer = explode("/lslhttp/", $http_server_url);
        $httpRequestID = rtrim($buffer[1], "/");
        $buffer = explode(":", $buffer[0]);
        $httpRequestHOST = $buffer[0].':'.$buffer[1];
        $httpRequestPORT = $buffer[2];
        $http_server_url = $httpRequestHOST.":".$httpRequestPORT."/lslhttp/".$httpRequestID."/";

        $local_position = substr($local_position, 1, -1);
        $buffer = explode(", ", $local_position);
        $local_positionX = round($buffer[0]);
        $local_positionY = round($buffer[1]);
        $local_positionZ = round($buffer[2]);
        $local_position = $local_positionX."/".$local_positionY."/".$local_positionZ;

        $appURL = "secondlife:///app/teleport/".$region_name."/".$local_position;
        $lslURL = "secondlife://".$region_name."/".$local_position;
        $hopURL = "hop://".$region_name."/".$local_position;
        $timestamp  = time();

        if ($debug)
        {
            echo "\n[ACTION] ".$action;
            echo "\n[HTTP_HOST] ".$http_host;
            echo "\n[HTTP REQUEST ID] ".$httpRequestID;
            echo "\n[HTTP REQUEST HOST] ".$httpRequestHOST;
            echo "\n[HTTP REQUEST PORT] ".$httpRequestPORT;
            echo "\n[HTTP SERVER URL] ".$http_server_url;
            echo "\n[LOCAL POSITION] ".$local_position;
            echo "\n[LSL URL] ".$lslURL;
            echo "\n[HOP URL] ".$hopURL;
            echo "\n[APP URL] ".$appURL;
            echo "\n[REGION COORD] ".$region_coord;
            echo "\n[OBJECT UUID] ".$object_uuid;
            echo "\n[OBJECT NAME] ".$object_name;
            echo "\n[CATEGORIE NAME] ".$categorie_name;
            // echo "\n[CATEGORIE NUMBER] ".$categorie_number;
            echo "\n[AGENTS ONLINE] ".$agents_online;
            echo "\n[AGENTS LIST] ".$agents_list;
            echo "\n[REGION NAME] ".$region_name;
            // echo "\n[REGION UUID] ".$region_uuid;
            echo "\n[OWNER NAME] ".$owner_name;
            echo "\n[OWNER UUID] ".$owner_uuid;
            echo "\n[TIMESTAMP] ".$timestamp;
        }

        $query = $db->prepare("
            SELECT * 
            FROM ".TB_DESTINATIONS." 
            WHERE (
                region_name = ?
                AND 
                owner_uuid = ?
                AND 
                object_uuid = ?
            )
        ");

        $query->bindValue(1, $region_name, PDO::PARAM_STR);
        $query->bindValue(2, $owner_uuid, PDO::PARAM_STR);
        $query->bindValue(3, $object_uuid, PDO::PARAM_STR);

        $query->execute();

        /* SQLite */
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
            echo "\n[TERMINAL INFOS] ".$counter." terminal ✘";

            $query = $db->prepare("
                INSERT INTO ".TB_DESTINATIONS." (
                    region_name, 
                    owner_name, 
                    owner_uuid, 
                    object_name, 
                    object_uuid, 
                    categorie_name, 
                    local_position,
                    http_server_url,
                    agents_online, 
                    date
                )
                VALUES (
                    :region_name, 
                    :owner_name, 
                    :owner_uuid,
                    :object_name, 
                    :object_uuid, 
                    :categorie_name,
                    :local_position,
                    :http_server_url,
                    :agents_online,
                    :date
                )
            ");

            $query->bindValue(':region_name', $region_name, PDO::PARAM_STR);
            $query->bindValue(':owner_name', $owner_name, PDO::PARAM_STR);
            $query->bindValue(':owner_uuid', $owner_uuid, PDO::PARAM_STR);
            $query->bindValue(':object_name', $object_name, PDO::PARAM_STR);
            $query->bindValue(':object_uuid', $object_uuid, PDO::PARAM_STR);
            $query->bindValue(':categorie_name', $categorie_name, PDO::PARAM_STR);
            $query->bindValue(':local_position', $local_position, PDO::PARAM_STR);
            $query->bindValue(':http_server_url', $http_server_url, PDO::PARAM_STR);
            $query->bindValue(':agents_online', $agents_online, PDO::PARAM_INT);
            $query->bindValue(':date', $timestamp, PDO::PARAM_INT);

            $query->execute();
        }

        else if ($counter == 1)
        {
            $query->execute();
            $row = $query->fetch(PDO::FETCH_NUM);

            echo "\n[TERMINAL INFO] ".$counter." terminal ✔";
            echo "\n[TERMINAL NAME] ".$row[4];
            echo "\n[TERMINAL DATE] ".$row[10];

            // UPDATE
            $query = $db->prepare("
                UPDATE ".TB_DESTINATIONS." 
                SET local_position = :local_position, 
                    http_server_url = :http_server_url, 
                    categorie_name = :categorie_name, 
                    agents_online = :agents_online, 
                    date = :timestamp
                WHERE (
                    region_name = :region_name 
                    AND 
                    object_uuid = :object_uuid 
                    AND 
                    owner_uuid = :owner_uuid
                )
            ");

            $query->bindValue(':local_position', $local_position, PDO::PARAM_STR);
            $query->bindValue(':http_server_url', $http_server_url, PDO::PARAM_STR);
            $query->bindValue(':categorie_name', $categorie_name, PDO::PARAM_STR);
            $query->bindValue(':agents_online', $agents_online, PDO::PARAM_INT);
            $query->bindValue(':timestamp', $timestamp, PDO::PARAM_INT);
            $query->bindValue(':region_name', $region_name, PDO::PARAM_STR);
            $query->bindValue(':object_uuid', $object_uuid, PDO::PARAM_STR);
            $query->bindValue(':owner_uuid', $owner_uuid, PDO::PARAM_STR);

            $query->execute();

            // READ
            $query = $db->prepare("
                SELECT * 
                FROM ".TB_DESTINATIONS." 
                WHERE (
                    region_name = ?
                    AND 
                    object_uuid = ?
                    AND 
                    owner_uuid = ?
                )
            ");

            $query->bindValue(1, $region_name, PDO::PARAM_STR);
            $query->bindValue(2, $object_uuid, PDO::PARAM_STR);
            $query->bindValue(3, $owner_uuid, PDO::PARAM_STR);

            $query->execute();

            $row = $query->fetch(PDO::FETCH_NUM);
            echo "\n[TERMINAL UPDATE] ".$row[10];
        }

        else if ($counter > 1)
        {
            $query->execute();

            while ($row = $query->fetch(PDO::FETCH_ASSOC))
            {
                $region_name = $row['region_name'];
                echo "\n[REGION FOUND IN DATABASE] ".$region_name;
            }
        }
    }
}
else exit("No direct access ...");
$query = null;
?>
