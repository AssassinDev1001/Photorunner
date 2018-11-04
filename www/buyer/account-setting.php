<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');

if(isset($_POST['profile_picture_submit']))
{
	if(!empty($_FILES['profilepicture']['name']))
	{
		if($common->updateprofilepicture($_FILES))
		{
			$common->redirect(APP_URL."buyer/account-setting.php");
		}
		else
		{
			$common->redirect(APP_URL."buyer/account-setting.php");
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
		if($common->changeemail($_POST))
		{
			$common->redirect(APP_URL."buyer/account-setting.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

if(isset($_POST['update']))
{
	if($common->photorunner($_POST))
	{
		$common->redirect(APP_URL."buyer/account-setting.php");
	}
	else
	{
		$common->redirect(APP_FULL_URL);
	}
}

$conditions = array('id'=>$_SESSION['account']['id']);
$members = $common->getrecord('pr_members','*',$conditions) ;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link href="<?php echo APP_URL; ?>js/checkbox/bootstrap-inputs.css" rel='stylesheet'>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
		});
	</script>
</head>
<body style="background-color:#EBEBEB">
<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<?php include(APP_ROOT.'include/buyer-left.php') ?>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<form  action=""  method="post" id="register-form" style="margin:30px;"> 
				<div style="width:100%; margin:10px;">
					<?php
						if(!empty($_SESSION['flash_messages']))
						{	
							echo $msgs->display();
						}	
					?>
				</div>
				<div class="col-md-12" style="padding:0px;">
					<h2 style="text-align:left">Account Settings</h2>
					<div id="tabs" style="border:none;">
						<ul style="background-color:#fff; border:none;">
							<li style="border-radius:0px;"><a href="#tabs-1">Change your Email</a></li>
							<li style="border-radius:0px;"><a href="#tabs-2">Email Notification</a></li>
						</ul>
						<div id="tabs-1">
							<div style="height:20px; clear:both;"></div>
							<h2 style="text-align:left">Email Registered</h2>
							<div style="font-size:18px; padding:0px 5px 0px 40px;">Username : <?php echo $members->username; ?></div>
							<div style="font-size:18px; padding:0px 5px 0px 40px;">Your email : <?php echo $members->email; ?></div>
							<div style="font-size:18px; padding:0px 5px 0px 40px;">Status : Confirmed</div>
							<div style="height:30px; clear:both;"></div>
							<h2 style="text-align:left">Change your Email</h2>
							<form  action=""  method="post" id="register-form" style="margin:30px;"> 
								<input type="email" placeholder="New Email" name="email" id="email" value="" style="width:80%; margin: 0px 0px 10px 40px;"/>
								<input type="email" placeholder="Confirm your New Email" name="cemail" id="cemail" value="" style="width:80%; margin: 0px 0px 10px 40px;"/>
								<input type="password" placeholder="Your Password" name="password" id="password" value="" style="width:80%; margin: 0px 0px 10px 40px;"/>
								<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
								<input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>" >
								<button type="submit" name="submit" id="btnSubmit" style="width:30%; margin-left:40px;"/>Change Email</button>
							</form>
						</div>
						<div id="tabs-2">
							<h2 style="text-align:left; padding-top:10px;">Email Notification</h2>
							<form  action=""  method="post" id="register-form" style="margin:30px;"> 
								<label for="">Email Notification</label>
								<div style="margin-top:20px;">
									<div style="float:left; width:35%;">Coupons & promotions.</div>
									<div style="padding-top:0px;">
										<div class='input'>
											<input type='radio' id='input1' name='coupons' value="yes" <?php if($members->coupons == 'yes') { ?>checked<?php } ?>>
											<label for="input1" style="padding-right:20px;">Yes</label>
											<input type='radio' id='input2' name='coupons' value="no" <?php if($members->coupons == 'no') { ?>checked<?php } ?>>
											<label for="input2">No</label>
										</div>
									</div>
									<div style="float:left; width:35%;">Send Newsletter</div>
									<div style="padding-top:0px;">
										<div class='input'>
											<input type='radio' id='input3' name='newsletter' value="yes" <?php if($members->newsletter == 'yes') { ?>checked<?php } ?>>
											<label for="input3" style="padding-right:20px;">Yes</label>
											<input type='radio' id='input4' name='newsletter' value="no" <?php if($members->newsletter == 'no') { ?>checked<?php } ?>>
											<label for="input4">No</label>
										</div>
									</div>
									<div style="float:left; width:35%;">What s new on Photo Runner?</div>
									<div style="padding-top:0px;">
										<div class='input'>
											<input type='radio' id='input5' name='photorunner' value="yes" <?php if($members->photorunner == 'yes') { ?>checked<?php } ?>>
											<label for="input5" style="padding-right:20px;">Yes</label>
											<input type='radio' id='input6' name='photorunner' value="no" <?php if($members->photorunner == 'no') { ?>checked<?php } ?>>
											<label for="input6">No</label>
										</div>
									</div>
									<input type="hidden" name="id" value="<?php echo $_SESSION['account']['id']; ?>" >
									<button type="submit" name="update" id="btnSubmit" style="width:30%; margin-top:40px;"/>Change Email</button>
								</div>
							</form>
						</div>
					</div>
					<div style="height:30px; clear:both"></div>
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

$(document).ready(function() {
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#register-form input[type="password"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else {
                $(this).css({
			"border": "1px solid #3cb371",
                });
            }
        });
        
        $('#register-form input[type="email"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else {
			var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
			if(!pattern.test($.trim($(this).val())))
			{
				isValid = false;
				$(this).css({
					"border": "1px solid red",
				});
			}
			else
			{
				if($(this).attr('name') == 'cemail')
				{
					if($.trim($(this).val()) != $.trim($('input[name="email"]').val()))
					{
						isValid = false;
						$(this).css({
							"border": "1px solid red",
						});
					}
					else {
						$(this).css({
							"border": "",
							"background": ""
						});
					}
				}
				
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
<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
