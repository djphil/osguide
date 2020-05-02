<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
$_SESSION['flash']['success'] = "You are disconnected succesfully ...";
unset($_SESSION["valid"]);
unset($_SESSION["username"]);
unset($_SESSION['useruuid']);
if (isset($_COOKIE[$cookie_name])) {setcookie ($cookie_name, '', time() - $cookie_time);}
header ('Location: ?page=home');
exit();
?>
