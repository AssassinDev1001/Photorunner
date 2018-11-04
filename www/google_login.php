<?php
session_start();
define('DB_SERVER', 'photorunner.mysql.domeneshop.no');
define('DB_USERNAME', 'photorunner');    // DB username
define('DB_PASSWORD', 'p6Fu8nS6TEADgoW');    // DB password
define('DB_DATABASE', 'photorunner');      // DB name
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die( "Unable to connect");
$database = mysql_select_db(DB_DATABASE) or die( "Unable to select database");
?>
<script src="jquery.min.js"></script>
<?php

if(!isset($_SESSION['google_data'])):header("Location:index.php");endif;

echo"<pre>";
print_r($_SESSION);
echo"</pre>";
exit;
$check=mysql_query("select * from bz_users where email='".$_SESSION['google_data']['email']."' and username = '".$_SESSION['google_data']['given_name']."'");
$fetch=mysql_fetch_assoc($check);
$get_num = mysql_num_rows($check);
if($get_num >= 1)
{
	?>
	<script type="text/javascript">
	$(function () {
	  alert('An Account With This Email or username Already Exists. Please Try Another Email.'); 
	  parent.location.href = 'http://anaadit.net/bazzar/demo/index.php';
	});
	</script>
	<?php
}
else
{
	$firstname = $_SESSION['google_data']['given_name'];     
	$lastname = $_SESSION['google_data']['family_name'];  
	$username = $_SESSION['google_data']['given_name'];  
	$email = $_SESSION['google_data']['email'];        



	$unique_id = uniqid(); 
	$unique_ids = md5($unique_id);
	$code = md5($email.rand().rand());
	$entered = @date('Y-m-d h:i:s');

	if($_SESSION['googleplustype'] = 'seller')
	{
		$type = 'seller';
	}
	else
	{
		$type = 'owner';
	}

   	 $save_data = mysql_query("INSERT INTO bz_users set firstname = '".$firstname."',lastname='".$lastname."',email='".$email."',username='".$username."',password='".$unique_ids."', code = '".$code."', user_type = '".$type."', entered = '".$entered."'");
    
    
    $user = $email;
    $subject = "HMbyme-Account verification";
    $message ="<html><body>
            <div style='100%; border:10px solid #00A2B5; font-family:arial; font-size:18px; border-radius:10px;'><div style='background-color:#F2F2F2; padding:20px; font-size:22px;'>Confirm your with Account</div>
            <div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px;'>HMbyme</div>".
            "<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center'>Hi ".$username."! To finish signing up, just confirm that we have got the right email.<br/><br/>".
            "<div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:40px; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> <a href='".APP_URL."index.php/?verifykey=".$code."'' style='color:#fff; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px;'>Confirm Account</a></div></div><br/><br/>".
            "<div style='font-size:16px; text-align:center; color:#00A2B5;'>Please follow the below details to login.</div><br/>".
            "<div style='font-size:16px; text-align:center;'><b>Email address:</b> ".$email."</div><br/>".
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
	<script type="text/javascript">
	$(function () {
	  alert('Please Check Your Email For Log In  Details'); 
	  parent.location.href = 'http://anaadit.net/bazzar/demo/index.php';
	  //parent.$.fancybox.close();
	       
	});
	</script>
	<?php
}
?>

                                            
