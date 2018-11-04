<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');

if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->editpersonal($_POST))
		{
			$common->redirect(APP_URL."buyer/account.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

$conditions = array('id'=>$_SESSION['account']['id']);
$members = $common->getrecord('pr_members','*',$conditions) ;

$conditions = array('member'=>$_SESSION['account']['id']);
$personal = $common->getrecord('personal_information','*',$conditions);

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
<div style="height:114px;"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div style="width:100%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
	<div class="col-md-12 features features-right" style="margin:20px 0; padding:0 0 0 20px">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="margin:20px;">
				<div class="col-md-12" style="border:2px solid #33b5e5; float:left">
					<div>
						<div class="col-md-3" style="padding:10px;"><img src="<?php echo APP_URL; ?>images/image1.jpg" style="width:230px; height:180px;" /></div>
						<div class="col-md-2" style="padding:10px;">
							<div style="font-size:15px; font-weight:bold; padding:5px;">Product Name</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Qty</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Price</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Description</div>
						</div>
						<div class="col-md-5" style="padding:10px;">
							<div style="font-size:15px; padding:5px;">: Lorem Ipsum is simply dummy</div>
							<div style="font-size:15px; padding:5px;">: 4</div>
							<div style="font-size:15px; padding:5px;">: $ 22.00 USD</div>
							<div style="font-size:13px; padding:5px;">: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
						</div>
						<div class="col-md-2" style="padding:10px;">
							<div style="color:#00A2B5; text-align:center; padding-top:70px;">View More Detail</div>
						</div>
					</div>
				</div>
				<div style="clear:both; height:20px;"></div>
				<div class="col-md-12" style="border:2px solid #33b5e5; float:left">
					<div>
						<div class="col-md-3" style="padding:10px;"><img src="<?php echo APP_URL; ?>images/image1.jpg" style="width:230px; height:180px;" /></div>
						<div class="col-md-2" style="padding:10px;">
							<div style="font-size:15px; font-weight:bold; padding:5px;">Product Name</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Qty</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Price</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Description</div>
						</div>
						<div class="col-md-5" style="padding:10px;">
							<div style="font-size:15px; padding:5px;">: Lorem Ipsum is simply dummy</div>
							<div style="font-size:15px; padding:5px;">: 4</div>
							<div style="font-size:15px; padding:5px;">: $ 22.00 USD</div>
							<div style="font-size:13px; padding:5px;">: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
						</div>
						<div class="col-md-2" style="padding:10px;">
							<div style="color:#00A2B5; text-align:center; padding-top:70px;">View More Detail</div>
						</div>
					</div>
				</div>
				<div style="clear:both; height:20px;"></div>
				<div class="col-md-12" style="border:2px solid #33b5e5; float:left">
					<div>
						<div class="col-md-3" style="padding:10px;"><img src="<?php echo APP_URL; ?>images/image1.jpg" style="width:230px; height:180px;" /></div>
						<div class="col-md-2" style="padding:10px;">
							<div style="font-size:15px; font-weight:bold; padding:5px;">Product Name</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Qty</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Price</div>
							<div style="font-size:15px; font-weight:bold; padding:5px;">Description</div>
						</div>
						<div class="col-md-5" style="padding:10px;">
							<div style="font-size:15px; padding:5px;">: Lorem Ipsum is simply dummy</div>
							<div style="font-size:15px; padding:5px;">: 4</div>
							<div style="font-size:15px; padding:5px;">: $ 22.00 USD</div>
							<div style="font-size:13px; padding:5px;">: Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
						</div>
						<div class="col-md-2" style="padding:10px;">
							<div style="color:#00A2B5; text-align:center; padding-top:70px;">View More Detail</div>
						</div>
					</div>
				</div>
				<div style="clear:both; height:20px;"></div>
			</div>
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
        $('#register-form input[type="text"]').each(function() {
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
        
       $('#register-form select').each(function() {
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

        $('#register-form input[type="email"]').each(function() {
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
