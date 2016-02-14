<?php
try {
    $db = new PDO('sqlite:'.$SQLitePath.$tbname.'.db');
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 

catch(PDOException $e) {
    $message = '
        <pre>
            Unable to connect to mysql ...
            Error code: '.$e->getCode().'
            Error file: '.$e->getFile().'
            Error line: '.$e->getLine().'
            Error data: '.$e->getMessage().'
        </pre>
    ';
    die($message);
}
?>