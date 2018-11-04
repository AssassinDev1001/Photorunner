<?php  include('include/config.php'); include(APP_ROOT.'include/check-logged.php');

if(!empty($_SESSION['fb_username'] && $_SESSION['fb_email']))
{
	if($common->facebookregistration($_SESSION))
	{
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}

if(isset($_GET['verifykey']) && !empty($_GET['verifykey']))
{	
	if($common->verifyaccount($_GET['verifykey']))
	{
		$common->redirect(APP_URL."log-in.php");
	}
	else
	{
		$common->redirect(APP_URL."log-in.php");
	}
}
if(isset($_GET['verifykeyy']) && !empty($_GET['verifykeyy']))
{	
	if($common->verifyaccountt($_GET['verifykeyy']))
	{
		$common->redirect(APP_URL."log-in.php");
	}
	else
	{
		$common->redirect(APP_URL."log-in.php");
	}
}

if(isset($_POST['signin']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_FULL_URL);
	}
	else
	{
		if($_POST['set'] == 'buyer')
		{
			if($common->login($_POST))
			{
				if(isset($_GET['redirecturl']) && !empty($_GET['redirecturl']))
				{
					$common->redirect(APP_URL.$_GET['redirecturl']);
				}
				elseif(isset($_GET['redirecturlcart']) && !empty($_GET['redirecturlcart']))
				{
					$common->redirect(APP_URL.$_GET['redirecturlcart']);
				}
				else
				{
					$common->redirect(APP_URL."buyer/personal-information.php");
				}
				
			}
			else
			{
				$common->redirect(APP_FULL_URL);
			}
		}
		if($_POST['set'] == 'seller')
		{
			if($common->sellerlogin($_POST))
			{
				$common->redirect(APP_URL."seller/index.php");
			}
			else
			{
				$common->redirect(APP_FULL_URL);
			}
		}
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<link id="stylesheet" rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>js/checkbox/zInput_default_stylesheet.css">
	<script src="<?php echo APP_URL; ?>js/checkbox/zInput.js"></script>
</head>
<body style="background-color:#F3F3F3">
	<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_header_join">&nbsp;</div>
<div style="width:48%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
<div class="module form-module">
	<div class="form"></div>
	<div class="form">
		<a href="<?php echo APP_URL; ?>facebook/check-facebook.php"><img src="images/facebbok-login.png" style="width:49%; float:left;" /></a>
		<img src="images/gmail-login.png" style="width:49%; float:right;" />
		<div style="clear:both; height:15px"></div>
		<h2 style="text-align:center">Log in with PhotoRunner</h2>
		<form  action=""  method="post" id="login-form"> 
			<input type="text" placeholder="Username & Email" name="username" id="username"/>
			<input type="password" placeholder="Password" name="password" id="username" style="margin: 0 0 5px;" />
			<div id="affected">
				<input type="radio" name="set" title="Buyer" value="buyer" id="cvcv" checked="checked">
				<input type="radio" name="set" title="Seller" value="seller" >
			</div>
			<div style="margin: 0 0 20px; text-align:right;"><a href="forgot-password.php" style="color:#33b5e5;" >Forgot Password ?</a></div>
			<button type="submit" name="signin" id="btnlogin"/>Log in</button>
		</form>
	</div>
</div>
<div style="height:100px;">&nbsp;</div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    $('#btnlogin').click(function(e) {
        var isValid = true;
        $('#login-form input[type="text"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "2px solid red",
                });
            }
            else {
                $(this).css({
					"border": "1px solid #3cb371",
                });
            }
        });
        $('#login-form input[type="password"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
					"border": "2px solid red",
                });
            }
            else {
				if($.trim($(this).val()).length < 6 || $.trim($(this).val()).length > 18)
				{
					isValid = false;
					$(this).css({
						"border": "2px solid red",
					});
				}
				else {
					$(this).css({
						"border": "1px solid #3cb371",
						"background": ""
					});
				}
            }
        });
       
        if (isValid == false) 
            e.preventDefault();
        else 
            ;
    });
});
</script>
<script>
$("#affected").zInput();
$(document).ready(function() {
	$("#select").addClass("zSelected");
});
</script>
