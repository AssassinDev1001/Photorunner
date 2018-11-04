<?php
ob_start();
session_start();

/*define( 'DB_HOST', 'localhost' );
define( 'DB_USERNAME', 'anaadit');
define( 'DB_PASSWORD', 'L@rryCP14');
define( 'DB_NAME', 'db_photorunner');*/


define( 'DB_HOST', 'photorunner.mysql.domeneshop.no' );
define( 'DB_USERNAME', 'photorunner');
define( 'DB_PASSWORD', 'p6Fu8nS6TEADgoW');
define( 'DB_NAME', 'photorunner');


if (!defined("PAGILIMIT")) define("PAGILIMIT", 20);

if (!defined("APP_NAME")) define("APP_NAME", "Photo Runner");
if (!defined("ADMIN_EMAIL")) define("ADMIN_EMAIL", "info@photorunner.no");

if (!defined("APP_FOLDER")) define("APP_FOLDER", "");
if (!defined("APP_ROOT")) define("APP_ROOT", $_SERVER["DOCUMENT_ROOT"]."/".APP_FOLDER);
if (!defined("APP_URL")) define("APP_URL", "http://".$_SERVER["HTTP_HOST"]."/".APP_FOLDER);
if (!defined("APP_FULL_URL")) define("APP_FULL_URL", "http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);

function __autoload($class)
{
	$parts = end(explode('_', $class));
	require_once APP_ROOT.'include/' . $parts . '.php';
}

$msgs  = new Cl_Messages();
$common  = new Cl_Common();
?>
