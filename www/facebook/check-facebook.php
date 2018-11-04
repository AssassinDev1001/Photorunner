<?php
require '../include/config.php';
require 'facebook/facebook.php';
require 'fbconfig.php';
require 'fb_functions.php';
$facebook = new Facebook(array(
            'appId' => APP_ID,
            'secret' => APP_SECRET,
            ));
$user = $facebook->getUser();
if ($user) 
{
	try
	{
		$user_profile = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
	} 
	catch (FacebookApiException $e) 
	{
		error_log($e);
		$user = null;
	}
	if (!empty($user_profile ))
	{
		$username = $user_profile['first_name'].' '.$user_profile['last_name'];
		$uid = $user_profile['id'];
		$email = $user_profile['email'];
		$gender = $user_profile['gender'];
		$locale = $user_profile['locale'];
		$picture = $user_profile['picture'];
		$user = new User();
		$userdata = $user->checkUser($uid, 'facebook', $username,$email,$twitter_otoken,$twitter_otoken_secret);

		if(!empty($userdata))
		{
			session_start();
			$_SESSION['fb_id'] = $userdata['id'];
			$_SESSION['fb_username'] = $username;
			$_SESSION['fb_email'] = $email;
			header("Location: ../log-in.php");
		}
	} 
	else 
	{
		# For testing purposes, if there was an error, let's kill the script
		die("There was an error.");
	}
}
else
{
	# There's no active session, let's generate one
	$login_url = $facebook->getLoginUrl(array( 'scope' => 'email'));
	header("Location: " . $login_url);
}
?>
