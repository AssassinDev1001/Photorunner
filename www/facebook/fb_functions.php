<?php
require 'fb_dbconfig.php';
class User {

    function checkUser($uid, $oauth_provider, $username,$email,$twitter_otoken,$twitter_otoken_secret) 
	{
        $sql = "SELECT * FROM `users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'";
        $query = mysql_query($sql) or die(mysql_error());
       
        $result = mysql_fetch_array($query,MYSQL_ASSOC);
        if (!empty($result)) {
            
            # User is already present
        } else {
            #user not present. Insert a new Record
           $query = mysql_query("INSERT INTO `users` (oauth_provider, oauth_uid, username,email) VALUES ('$oauth_provider', $uid, '$username','$email')");
            $query = mysql_query("SELECT * FROM `users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
            $result = mysql_fetch_array($query,MYSQLI_ASSOC);
            return $result;
        }
        return $result;
    }

}
?>
