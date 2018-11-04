<?php
require '../include/config.php';
session_start();

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
	FacebookSession::setDefaultApplication( '1758055857740600','95dcd76b49832960dc7867d85cc8cda4' );
	$helper = new FacebookRedirectLoginHelper(APP_URL.'facebook-login/fbconfig.php' );
try 
{
	$session = $helper->getSessionFromRedirect();
} 
catch( FacebookRequestException $ex )
{}catch( Exception $ex ) {}

if ( isset( $session ) ) 
{
	$request = new FacebookRequest( $session, 'GET', '/me?fields=name,email,first_name,last_name' );
	$response = $request->execute();

	$graphObject = $response->getGraphObject();
	$fbid = $graphObject->getProperty('id');
	$fbfullname = $graphObject->getProperty('name');
	$femail = $graphObject->getProperty('email');
	$fbfirstname = $graphObject->getProperty('first_name');
	$fblastname = $graphObject->getProperty('last_name');

	echo $_SESSION['FBID'] = $fbid;           
	echo $_SESSION['FULLNAME'] = $fbfullname;
	echo $_SESSION['EMAIL'] =  $femail;
	echo $_SESSION['FIRSTNAME'] = $fbfirstname;
	echo $_SESSION['LASTNAME'] =  $fblastname;
	exit;



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
			parent.location.href = APP_URL.'index.php';
			});
		</script>
	<?php
	}
	else if($cnt2 >= '1'){
	?>
		<script src="jquery.min.js"></script>
		<script type="text/javascript">
			$(function () {
			alert('Username already exist. Please try again.');   
			parent.location.href = APP_URL.'index.php';

			});
		</script>
   	 <?php
	}
	else
	{
		$save_data = mysql_query("INSERT INTO bz_users set firstname = '".$fbfirstname."',lastname='".$fblastname."',email='".$femail."',username='".$fbfullname."',password='".$unique_ids."', code = '".$code."', user_type = 'owner', entered = '".$entered."'");


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
			parent.location.href = 'http://www.photorunner.no/index.php';
			//parent.$.fancybox.close();
			});
		</script>
		<?php
	}
}
else
{
	$loginUrl = $helper->getLoginUrl();
	header("Location: ".$loginUrl);
}
?>
