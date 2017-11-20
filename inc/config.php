<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
ini_set('magic_quotes_gpc', 0);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$osguide = "OpenSim Destination Guide";
$version = "0.3";
$debug = FALSE;

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "<DB PASS>";
$dbname = "<DB NAME>";
$tbname = "osguide_destinations";

/* SQLite 3 */
$useSQLite = FALSE;
$SQLitePath = "D:/xampp/htdocs/osguide/db/";

/* Security */
$create_log = TRUE;
$access_log = "../logs/access_log.txt";

// This limit region registre 
// to Destination Guide by host's
$limit_hosts = TRUE;
$white_hosts = [
    "<ALLOWED_HOST>",
    "domain.com",
    ""
];

// This limit region resistration 
// to "Official Location" category by uuid's
$limit_uuids = TRUE;
$white_uuids = [
    "<ALLOWED_UUID>",
    "00000000-0000-0000-0000-000000000000",
    ""
];

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
