<?php
try {
    $db = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.'', $dbuser, $dbpass);
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