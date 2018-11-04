<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');

if(isset($_POST['profile_picture_submit']))
{
	if(!empty($_FILES['profilepicture']['name']))
	{
		if($common->updateprofilepicture($_FILES))
		{
			$common->redirect(APP_URL."buyer/change-password.php");
		}
		else
		{
			$common->redirect(APP_URL."buyer/change-password.php");
		}
	}	
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}

if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->changepassword($_POST))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<?php include(APP_ROOT.'include/buyer-left.php') ?>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="heigh:10px;">&nbsp;</div>
				<?php
					if(!empty($_SESSION['flash_messages']))
					{	
						echo $msgs->display();
					}	
				?>
			<form  action=""  method="post" id="change_password" style="margin:30px;"> 
				<div class="col-md-9">
					<h2 style="text-align:left">Change Password</h2>
					<lable style="padding:5px;">Old Password</lable>
					<input type="password" placeholder="Old Password" name="oldpassword" id="oldpassword" style="margin: 0 0 0px;"/>
					<div style="height:20px; clear:both;">&nbsp;</div>
					<lable style="padding:5px;">New Password</lable>
					<input type="password" placeholder="New Password" name="password" id="password" style="margin: 0 0 0px;"/>
					<div style="height:20px; clear:both;">&nbsp;</div>
					<lable style="padding:5px;">Confirm Password</lable>
					<input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword" style="margin: 0 0 0px;"/>
					<div style="height:20px; clear:both;">&nbsp;</div>
					<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
					<input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>" >
					<button type="submit" name="submit" id="btnSubmit"/>Change Password</button>
					<div style="height:30px;">&nbsp;</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>
$(document).ready(function(){	
	$("#change_password").validate({
		rules: {
			oldpassword: {
				required: true,
				minlength: 8
			},
			password: {
				required: true,
				minlength: 8
			},
			cpassword: {
				required: true,
				minlength: 8,
				equalTo: "#password"
			}
		},

		messages: {           
			oldpassword: {
				required: "Please enter your old Password"
			},
			password: {
				required: "Please enter your new password",
				minlength: "Password must be at least 8 characters long"
			},
			cpassword: {
				required: "Please enter confirm password",
				minlength: "Confirm password must be at least 8 characters long",
				equalTo: "Confirm password does not match"
			}	
		},
	
		submitHandler: function(form) {
			form.submit();
		}
	
	});		
});	
</script>
<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
