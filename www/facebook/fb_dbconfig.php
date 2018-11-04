<?php
/* define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'xa392_5');
define('DB_PASSWORD', 'h@SC_2015');
define('DB_DATABASE', 'xa392_db5');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die(mysqli_error()); */

$hostname="photorunner.mysql.domeneshop.no";
$username="photorunner"; //anaadit
$password="p6Fu8nS6TEADgoW"; // L@rryCP14
$database_name="photorunner";

/* database connect section */
$dbcon=mysql_connect($hostname, $username, $password) or die('Could not connect'.mysql_error());
$db_connection=mysql_select_db($database_name, $dbcon) or die('Could not connect'.mysql_error());
?>
