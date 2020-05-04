<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
function debug($variable)
{
    echo '<pre>'.print_r($variable, true).'</pre>';
}

function trucate($str, $max)
{
    if (strlen($str) < $max) return $str;
    return substr($str, 0, $max)." ...";
}

function generate_uuid()
{
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
    mt_rand(0, 0x0fff) | 0x4000,
    mt_rand(0, 0x3fff) | 0x8000,
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
}

function one_random_image_file()
{
    $imageDIR = "../img/";
    $files = glob($imageDIR.'*.jpg');
    shuffle($files);
    return realpath($files[0]);
}

function getImageByName($dir, $name, $offset)
{
    foreach (glob($dir."*.jpg") as $filename)
    {
        $filename = explode('/', $filename);
        $filename = $filename[$offset];
        $filename = explode('.', $filename);
        $filename = $filename[0];

        if ($filename === $name)
            return $dir.$filename.".jpg"; 
    }
    return $dir."default.jpg"; 
}

// -1 or 0 All Categories
// 1 Official location
// 3 Arts and culture
// 4 Business
// 5 Educational
// 6 Gaming
// 7 Hangout
// 8 Newcomer friendly
// 9 Parks and Nature
// 10 Residential
// 11 Shopping
// 13 Other
// 14 Rental

$destination_category_names = [
    0 => "All Categories",
    1 => "Official location",
    3 => "Arts and culture",
    4 => "Business",
    5 => "Educational",
    6 => "Gaming",
    7 => "Hangout",
    8 => "Newcomer friendly",
    9 => "Parks and nature",
    10 => "Residential",
    11 => "Shopping",
    13 => "Other",
    14 => "Rental"
];

function getCategorieByNumber($number)
{
    if ($number == -1 OR $number == 0) return "All Categories";
    else if ($number == 1) return "Official location";
    else if ($number == 3) return "Arts and culture";
    else if ($number == 4) return "Business";
    else if ($number == 5) return "Educationnal";
    else if ($number == 6) return "Gaming";
    else if ($number == 7) return "Hangout";
    else if ($number == 8) return "Newcomer friendly";
    else if ($number == 9) return "Parks and Nature";
    else if ($number == 10) return "Residential";
    else if ($number == 11) return "Shopping";
    else if ($number == 13) return "Other";
    else if ($number == 14) return "Rental";
    return "All Categories";
}

function getCategorieByName($name)
{
    if ($name == "All Categories") return 0;
    else if ($name == "Official location") return 1;
    else if ($name == "Arts and culture") return 3;
    else if ($name == "Business") return 4;
    else if ($name == "Educational") return 5;
    else if ($name == "Gaming") return 6;
    else if ($name == "Hangout") return 7;
    else if ($name == "Newcomer friendly") return 8;
    else if ($name == "Parks and Nature") return 9;
    else if ($name == "Residential") return 10;
    else if ($name == "Shopping") return 11;
    else if ($name == "Other") return 13;
    else if ($name == "Rental") return 14;
    return -1;
}

function create_logs_file($file_name, $file_content, $debug)
{
    $file_content = trim($file_content);

    if (file_exists($file_name))
    {
        if (is_writable($file_name))
        {
            if (!$handle = fopen($file_name, 'a'))
            {
                 if ($debug) echo "\nImpossible d'ouvrir le fichier ($file_name)";
            }

            if (fwrite($handle, $file_content."\n") === FALSE)
            {
                if ($debug) echo "\nImpossible d'écrire dans le fichier ($file_name)";
            }

            if ($debug) echo "\nL'écriture de ($file_content) dans le fichier ($file_name) a réussi";
            fclose($handle);
        }

        else
        {
            if ($debug) echo "\nLe fichier ($file_name) n'est pas accessible en écriture ...";
        }
    }

    else
    {
        if ($debug) echo "\nLe fichier ($file_name) n'existe pas ...";
        file_put_contents($file_name, $file_content, FILE_APPEND);
        create_log_files($file_name, $file_content);
    }
}

function curl_get_contents($url)
{
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/*GET_CURRENT_VERSION*/
function get_current_version($version_url, $my_version)
{
    $current_version = curl_get_contents($version_url);
    $current_version = trim($current_version);
    $version_infos = "";

    if (!$current_version)
    {
        // return $_SESSION['flash']['danger'] = "Version infos currently not available ...";
        $version_infos  = '<div class="alert alert-info">Version infos currently not available ...';
        $version_infos .= '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
        $version_infos .= '</div>';
        return $version_infos;
    }

    if ($current_version <> $my_version)
    {
        if ($current_version > $my_version)
        {
            $version_infos = '<span class="text-danger">Update available</span>';
        }

        else
        {
            $version_infos = '<span class="text-warning">Pre-release ?</span>';
        }
    }

    else $version_infos = '<span class="text-success">Up to date</span>';

    $result = '<div class="row version">';
    $result .= '<div class="col-sm-4"><h3><small>Current Version</small><br />'.$current_version.'</h3></div>';
    $result .= '<div class="col-sm-4"><h3><small>My Version</small><br />'.$my_version.'</h3></div>';
    $result .= '<div class="col-sm-4"><h3><small>Version infos</small><br />'.$version_infos.'</h3></div>';
    $result .= '</div>';
    return $result;
}

/* OSGUIDE_REORDER */
function osguide_reorder($db, $tb)
{
    $sql = $db->prepare("
        ALTER TABLE ".$tb."  
        DROP id
    ");
    $sql->execute();

    $sql = $db->prepare("
        ALTER TABLE ".$tb."  
        AUTO_INCREMENT = 1
    ");
    $sql->execute();

    $sql = $db->prepare("
        ALTER TABLE ".$tb."  
        ADD id int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST
    ");
    $sql->execute();

    if ($sql)
    {
        $_SESSION['flash']['success'] = "<i class='glyphicon glyphicon-ok'></i> Table field <strong>id</strong> re-ordered successfully ...";
    }

    else
    {
        $_SESSION['flash']['danger'] = "<i class='glyphicon glyphicon-remove'></i> Table <strong>id</strong> re-ordered failed ...";
    }
    $sql->closeCursor();
    $db = NULL;
}

/* OSGUIDE_UPDATE */
// TODO PING TERMINAL'S
// SEE refresh.php
function osguide_update($db, $tb)
{
    $sql = $db->prepare("
        SELECT http_server_url
        FROM ".$tb."
    ");
    $sql->execute();
    // $count = $sql->rowCount();

    if ($sql->rowCount() > 0)
    {
        // $buffer = [];

        while ($row = $sql->fetch(PDO::FETCH_ASSOC))
        {
            $http_server_url = $row['http_server_url'];
            
            // TODO PING TERMINAL'S
            $ping = "OK";
            if ($ping == "OK")
            {
                // TODO
                $_SESSION['flash']['success'] = "<i class='glyphicon glyphicon-ok'></i> Table updated successfully ...";
            }

            else
            {
                // TODO
                $_SESSION['flash']['danger'] = "<i class='glyphicon glyphicon-ok'></i> Table updated failed ...";
            }
        }

        $query = null;
        $sql = null;
    }

    else
    {
        // TODO
        $_SESSION['flash']['success'] = "<i class='glyphicon glyphicon-ok'></i> Table is allready up to date ...";
    }
}


/* OSGUIDE_TRUNCATE */
function osguide_truncate($db, $tb)
{
    $sql = $db->prepare("
        TRUNCATE TABLE ".$tb."
    ");
    // DISABLE IN DEMO MODE
    // $sql->execute();

    if ($sql)
    {
        $_SESSION['flash']['success'] = "<i class='glyphicon glyphicon-ok'></i> Table truncated successfully ...";
    }

    else
    {
        $_SESSION['flash']['danger'] = "<i class='glyphicon glyphicon-remove'></i> Table truncated failed ...";
    }
    $sql->closeCursor();
    $db = NULL;
}

/* GET_TOTAL_DESTINATIONS */
function get_total_destinations($db)
{
    $sql = $db->prepare("
        SELECT COUNT(*) 
        FROM ".TB_DESTINATIONS." 
    ");
    $sql->execute();
    $result = $sql->fetchColumn();
    $sql = null;
    $db = null;
    return $result;
}

/* GET_TOTAL_UNIQUE_OWNERS */
function get_total_unique_owners($db)
{
    //  AS counter
    $sql = $db->prepare("
        SELECT  COUNT(DISTINCT `owner_uuid`)
        FROM ".TB_DESTINATIONS." 
    ");
    $sql->execute();
    $result = $sql->fetchColumn();
    // $result = $sql->fetchAssoc();
    // $result = $sql->fetch(PDO::FETCH_ASSOC);
    $sql = null;
    $db = null;
    return $result;
}

/* GET_TOTAL_AGENTS_ONLINE */
function get_total_agents_online($db)
{
    $sql = $db->prepare("
        SELECT COUNT(*) 
        FROM ".TB_DESTINATIONS." 
        WHERE agents_online > 0
    ");
    $sql->execute();
    $result = $sql->fetchColumn();
    $sql = null;
    $db = null;
    return $result;
}

?>
