<?php
function debug($variable)
{
    echo '<pre>' . print_r($variable, true) . '</pre>';
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
function getDefaultDestinationCategories()
{
    // -1 => "All Categories",
    $categories = [
        0 => "All Categories",
        1 => "Official location",
        3 => "Arts and culture",
        4 => "Business",
        5 => "Educational",
        6 => "Gaming",
        7 => "Hangout",
        8 => "Newcomer friendly",
        9 => "Parks and Nature",
        10 => "Residential",
        11 => "Shopping",
        13 => "Other",
        14 => "Rental"
    ];
    return $categories;
}

function getCategorieByNumber($number)
{
    if ($number == -1 OR $number == 0) return "All Categories";
    else if ($number == 1) return "Official location";
    else if ($number == 3) return "Arts and culture";
    else if ($number == 4) return "Business";
    else if ($number == 5) return "Educational";
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
    else if ($name == "Educationnal") return 5;
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
                 // exit;
            }

            if (fwrite($handle, $file_content."\n") === FALSE)
            {
                if ($debug) echo "\nImpossible d'écrire dans le fichier ($file_name)";
                // exit;
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
?>
