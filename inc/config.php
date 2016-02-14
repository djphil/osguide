<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "<DB PASS>";
$dbname = "<DB NAME>";
$tbname = "osguide_destination";

$osguide = "OpenSim Destination Guide";
$debug = FALSE;

/* SQLite 3 */
$useSQLite = FALSE;
$SQLitePath = "E:/xampp/htdocs/osguide/db/";

$useTheme = TRUE;
/* Navbar Style */
// navbar
// navbar-btn
// navbar-form
// navbar-left
// navbar-right
// navbar-default
// navbar-inverse
// navbar-collapse
// navbar-fixed-top
// navbar-fixed-bottom
$CLASS_NAVBAR = "navbar navbar-default";
$CLASS_ORDERBY_NAVBAR = "navbar navbar-default";

/* Nav Style */
// nav
// nav-tabs
// nav-pills
// navbar-nav
// nav-stacked
// nav-justified
$CLASS_NAV = "nav navbar-nav";
$CLASS_ORDERBY_NAV = "nav navbar-nav";        
?>
