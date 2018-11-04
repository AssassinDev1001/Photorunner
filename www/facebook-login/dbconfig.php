<?php
define('DB_SERVER', 'photorunner.mysql.domeneshop.no');
define('DB_USERNAME', 'photorunner');    // DB username
define('DB_PASSWORD', 'p6Fu8nS6TEADgoW');    // DB password
define('DB_DATABASE', 'photorunner');      // DB name
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
?>
