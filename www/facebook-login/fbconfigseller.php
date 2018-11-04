<?php
require 'dbconfig.php';
session_start();
// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication( '1004207326328522','cf3eccb57dd955d72a0fc77d80a798dc' );
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('http://anaadit.net/bazzar/demo/facebook_login/fbconfig.php' );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}

// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  //$request = new FacebookRequest( $session, 'GET', '/me' );
  $request = new FacebookRequest( $session, 'GET', '/me?fields=name,email,first_name,last_name' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
      $fbfirstname = $graphObject->getProperty('first_name'); // To Get Facebook full name
      $fblastname = $graphObject->getProperty('last_name');    // To Get Facebook email ID
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
      $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
      $_SESSION['FIRSTNAME'] = $fbfirstname;
      $_SESSION['LASTNAME'] =  $fblastname;
    /* ---- header location after session ----*/
   
    
    $unique_id = uniqid(); 
    $unique_ids = md5($unique_id);
    $code = md5($femail.rand().rand());
    $entered = @date('Y-m-d h:i:s');

    $sim1 = mysql_query("select * from bz_users where email = '".$femail."' and user_type = 'owner'");
    $sim2 = mysql_query("select * from bz_users where username = '".$fbfullname."' and user_type = 'owner'");
    $cnt1 = mysql_num_rows($sim1);
    $cnt2 = mysql_num_rows($sim2);
    if($cnt1 >= '1')
    {
    ?>

    <script src="jquery.min.js"></script>
    <script type="text/javascript">
$(function () {
   alert('Email already exist. Please try again.'); 
   parent.location.href = 'http://anaadit.net/bazzar/demo/index.php';
      
});
</script>

    <?php
    }
    else if($cnt2 >= '1')
    {
    ?>

    <script src="jquery.min.js"></script>
    <script type="text/javascript">
$(function () {
  alert('Username already exist. Please try again.');   
   parent.location.href = 'http://anaadit.net/bazzar/demo/index.php';
     
});
</script>

    <?php

    }
    else
    {
    $save_data = mysql_query("INSERT INTO bz_users set firstname = '".$fbfirstname."',lastname='".$fblastname."',email='".$femail."',username='".$fbfullname."',password='".$unique_ids."', code = '".$code."', user_type = 'seller', entered = '".$entered."'");
    
    
    //$user = $femail;
    $user = $femail;
    $subject = "HMbyme-Account verification";
    $message ="<html><body>
            <div style='100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your with Account</div>
            <div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>HMbyme</div>".
            "<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center'>Hi ".$fbfirstname."! To finish signing up, just confirm that we have got the right email.<br/><br/>".
            "<div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:40px; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> <a href='".APP_URL."index.php/?verifykey=".$code."'' style='color:#fff; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px;'>Confirm Account</a></div></div><br/><br/>".
            "<div style='font-size:16px; text-align:center; color:#00A2B5;'>Please follow the below details to login.</div><br/>".
            "<div style='font-size:16px; text-align:center;'><b>Email address:</b> ".$femail."</div><br/>".
            "<div style='font-size:16px; text-align:center;'><b>Password:</b> ".$unique_id."</div><br/><br/>".
            "<div style='font-size:14px; text-align:center;'>2016 Sell on HMbyme!. All Rights Reserved </div><br/>".
            "</div></body></html>";
  

    //Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    //More headers
    $headers .= 'From: <"info@HMBYME.com">' . "\r\n"; 
    //$headers .= 'Cc: myboss@example.com' . "\r\n";

    mail($user,$subject,$message,$headers);
?>

<script src="jquery.min.js"></script>
<script type="text/javascript">
$(function () {
  alert('Please Check Your Email For Log In  Details'); 
  parent.location.href = 'http://anaadit.net/bazzar/demo/index.php';
  //parent.$.fancybox.close();
       
});
</script>

<?php
}
}
else {
  $loginUrl = $helper->getLoginUrl();
 header("Location: ".$loginUrl);
}
?>
