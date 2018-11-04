<?php
ob_start();
session_start();
ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');
            

if (!defined("SITE_URL")) define('SITE_URL',"http://".$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'))); 
if (!defined("SITE_NAME")) define('SITE_NAME',"Photo Runner");
if (!defined("APP_FOLDER")) define('APP_FOLDER', "photorunner/code/");
if (!defined("APP_ROOT")) define('APP_ROOT', $_SERVER["DOCUMENT_ROOT"].APP_FOLDER);

/*$hostname="localhost";
$username="anaadit"; //anaadit
$password="L@rryCP14"; // L@rryCP14
$database_name="db_photorunner";*/

$hostname="photorunner.mysql.domeneshop.no";
$username="photorunner"; //anaadit
$password="p6Fu8nS6TEADgoW"; // L@rryCP14
$database_name="photorunner";


$dbcon=mysql_connect($hostname, $username, $password) or die('Could not connect'.mysql_error());
$db_connection=mysql_select_db($database_name, $dbcon) or die('Could not connect'.mysql_error());


?>
