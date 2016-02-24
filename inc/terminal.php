<?php include_once("config.php"); ?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
// base64_decode($str);
// base64_encode($str);
if ($useSQLite === TRUE)
    include_once("PDO-sqlite.php");
else include_once("PDO-mysql.php");

if (isset($_POST['terminal']))
{
	$action = htmlspecialchars($_POST["terminal"]);

    if ($action === "register")
    {
        /* SL Headers
            HTTP_X_SECONDLIFE_SHARD === OpenSim
            HTTP_X_SECONDLIFE_OBJECT_NAME === OpenSim Destination Guide Terminal v0.1
            HTTP_X_SECONDLIFE_OBJECT_KEY === 43e6965f-3096-48cc-a231-54095b571c58
            HTTP_X_SECONDLIFE_REGION === COLLAB 3D ASBL (9924, 9947)
            HTTP_X_SECONDLIFE_LOCAL_POSITION === (151.5, 145.0, 20.5)
            HTTP_X_SECONDLIFE_LOCAL_VELOCITY === (0.0, 0.0, 0.0)
            HTTP_X_SECONDLIFE_LOCAL_ROTATION === (0.0, 0.0, 0.0, 1.0)
            HTTP_X_SECONDLIFE_OWNER_NAME === dj phil
            HTTP_X_SECONDLIFE_OWNER_KEY === 3d4e42e9-9307-2ac7-11b6-c35c46794fa8
            HTTP_USER_AGENT === OpenSim LSL (Mozilla Compatible)
            HTTP_HOST === collab3d.be
            HTTP_CONNECTION === Keep-Alive
        */

        foreach ($_SERVER as $key => $value)
        {
            if(substr($key, 0, 5) == 'HTTP_')
            {
                // echo $key. " === " .$value. "\n";
                // if ($key === "HTTP_HOST")
                //     $http_host = $value;
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

        $buffer = explode(" (", $region_name);
        $region_name = $buffer[0];
        $region_coord = "(".$buffer[1];

        $categorie_name = htmlspecialchars($_POST["categorie_name"]);
        // $categorie_number = htmlspecialchars($_POST["categorie_number"]);

        $agents_list = base64_decode(htmlspecialchars($_POST["agents_list"]));
        $agents_online = explode(',', $agents_list, 0);

        if ($agents_online[0] === "") $agents_online = 0;
        else $agents_online = count($agents_list);

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

        $lslURL = "secondlife://".$region_name."/".$local_position;
        $hopURL = "hop://".$region_name."/".$local_position;
        $appURL = "secondlife:///app/teleport/".$region_name."/".$local_position;

        $timestamp = date("Y-m-d, H-i-s");

        if ($debug)
        {
            echo "\n[ACTION] ".$action;
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
            FROM $tbname 
            WHERE (
                region_name = '".mysql_real_escape_string($region_name)."' 
                AND 
                object_uuid = '".mysql_real_escape_string($object_uuid)."' 
                AND 
                owner_uuid = '".mysql_real_escape_string($owner_uuid)."'
            )
        ");

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
                INSERT INTO $tbname (
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
                    '".mysql_real_escape_string($region_name)."', 
                    '".mysql_real_escape_string($owner_name)."', 
                    '".mysql_real_escape_string($owner_uuid)."',
                    '".mysql_real_escape_string($object_name)."', 
                    '".mysql_real_escape_string($object_uuid)."', 
                    '".mysql_real_escape_string($categorie_name)."',
                    '".mysql_real_escape_string($local_position)."',
                    '".mysql_real_escape_string($http_server_url)."',
                    '".mysql_real_escape_string($agents_online)."',
                    '".$timestamp."'
                )
            ");
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
                UPDATE $tbname
                SET local_position = '".mysql_real_escape_string($local_position)."', 
                    http_server_url = '".mysql_real_escape_string($http_server_url)."', 
                    categorie_name = '".mysql_real_escape_string($categorie_name)."', 
                    agents_online = '".mysql_real_escape_string($agents_online)."', 
                    date = '".$timestamp."'
                WHERE (
                    region_name = '".mysql_real_escape_string($region_name)."' 
                    AND 
                    object_uuid = '".mysql_real_escape_string($object_uuid)."' 
                    AND 
                    owner_uuid = '".mysql_real_escape_string($owner_uuid)."'
                )
            ");
            $query->execute();

            // READ
            $query = $db->prepare("
                SELECT * 
                FROM $tbname 
                WHERE (
                    region_name = '".mysql_real_escape_string($region_name)."' 
                    AND 
                    object_uuid = '".mysql_real_escape_string($object_uuid)."' 
                    AND 
                    owner_uuid = '".mysql_real_escape_string($owner_uuid)."'
                )
            ");
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
