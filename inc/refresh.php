<?php
if (isset($_GET['refresh']))
{
    $region_name = htmlspecialchars($_GET["refresh"]);
    $http_server_url = base64_decode(htmlspecialchars($_GET["url"]));

    try {
        $sql = $db->prepare("
            SELECT http_server_url,
                   region_name 
            FROM ".$tbname." 
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
                    FROM ".$tbname." 
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
        echo '<script>document.location.href="?details='.$region_name.'"</script>';
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
    $db = null;
}
else header('Location: ./');
?>
