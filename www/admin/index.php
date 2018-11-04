<?php
include('include/config.php');
include(APP_ROOT."include/check-logged.php");
if(isset($_POST['signin']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->adminlogin($_POST))
		{
			$common->redirect(APP_URL.'control-panel.php');
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
	<link href="css/admin.css" rel='stylesheet' type='text/css' />
	<?php include('include/head.php') ?>
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
<div style="height:200px;">&nbsp;</div>
<div style="width:42%; margin:auto;">
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
		<h2 style="text-align:center"><img src="../images/logo.png" /></h2>
		<form  action=""  method="post" id="login-form"> 
			<input type="text" placeholder="Username" name="username" id="username"/>
			<input type="password" placeholder="Password" name="password" id="username"/>
			<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
			<button type="submit" name="signin" id="btnlogin" STYLE="font-size:16px;"/>Log in</button>
		</form>
	</div>
</div>		
</body>
</html>
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
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
