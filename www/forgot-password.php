<?php 
include('include/config.php');
if(isset($_POST['forgot']))
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
			if($common->forgotpasswordbuyer($_POST))
			{
				$common->redirect(APP_FULL_URL);
			}
			else
			{
				$common->redirect(APP_FULL_URL);
			}
		}
		if($_POST['set'] == 'seller')
		{
			if($common->forgotpasswordseller($_POST))
			{
				$common->redirect(APP_FULL_URL);
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
<div style="height:210px;">&nbsp;</div>
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
		<h2 style="text-align:center">Forgot Your Password</h2>
		<form  action=""  method="post" id="forgot-form"> 
			<input type="email" placeholder="Email Address" name="email" id="email"/>
			<div id="affected">
				<input type="radio" name="set" title="Buyer" value="buyer" id="cvcv" checked="checked">
				<input type="radio" name="set" title="Seller" value="seller" >
			</div>
			<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
			<button type="submit" name="forgot" id="btnforgot"/>Forgot Password</button>
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
    $('#btnforgot').click(function(e) {
        var isValid = true;
        $('#forgot-form input[type="email"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "2px solid red",
                });
            }
            else {
				var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
				if(!pattern.test($.trim($(this).val())))
				{
					isValid = false;
					$(this).css({
						"border": "2px solid red",
					});
				}
				else
				{
					$(this).css({
						"border": "1px solid #3cb371",
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
