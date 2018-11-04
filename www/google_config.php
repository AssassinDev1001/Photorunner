<?php
//session_start();
//include('config/config.php');
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########


$clientId = '641164823839-ea1iv56jibr455cdjjiukflq98m5h3a8.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = 'gsMdb3kkNAqCSrCPgt4YniFZ'; //Google CLIENT SECRET
$redirectUrl = 'http://www.photorunner.no/google_index.php';  //return url (url to script)
$homeUrl = 'http://www.photorunner.no/log-in.php';  //return to home


##################################

$gClient = new Google_Client();
$gClient->setApplicationName('Login to codexworld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
