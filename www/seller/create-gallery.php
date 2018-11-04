<?php  include('../include/config.php'); include(APP_ROOT.'include/check-seller.php');

if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->addgallery($_POST, $_FILES))
		{
			$common->redirect(APP_URL."seller/create-gallery.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}
$conditions = array('id'=>$_SESSION['seller']['id']);
$seller = $common->getrecord('pr_seller','*',$conditions);	
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<link rel="stylesheet" type="text/css" href="file/demo.css" />
	<link rel="stylesheet" type="text/css" href="file/component.css" />
	<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
	<style>
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	</style>
</head>
<body style="background-color:#EBEBEB">
<?php include(APP_ROOT.'include/header.php'); ?>
<div style="height:124px;"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div class="col-md-3 no-pading" style="background-color:#fff; height:auto;margin:20px 0;">
		<div style="margin:15px;">
			<img src="<?php echo APP_URL; ?>images/banner10.jpg" style="width:100%; min-height:200px; max-height:200px;"/>
		</div>
		<a href="<?php echo APP_URL; ?>seller/create-gallery.php"><div class="line1">Create Gallery</div></a>
		<a href="<?php echo APP_URL; ?>buyer/favorite-list.php"><div  class="line1">My Favorite List</div></a>
		<a href="<?php echo APP_URL; ?>seller/change-password.php"><div  class="line1">Change Password</div></a>
		<a href="<?php echo APP_URL; ?>seller/logout.php"><div class="line2">Sign Out</div></a>
	</div>
	<div class="col-md-9 features features-right" style="margin:20px 0; padding:0 0 0 20px">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="width:90%; margin:10px;">
				<?php
					if(!empty($_SESSION['flash_messages']))
					{	
						echo $msgs->display();
					}	
				?>
			</div>
			<div class="col-md-12" style="padding:0px;">
				<div id="tabs" style="border:none;">
					<div id="tabs-1">
						<div style="height:20px; clear:both;"></div>
						<h2 style="text-align:left">Create a Gallery</h2>
						<form  action=""  method="post" id="gallery-form" style="margin:30px;"  enctype='multipart/form-data'> 
							<input type="text" placeholder="Gallery Name" name="name" id="name" value="" style="width:50%; margin: 0px 0px 10px 40px;"/>
							<div style="height:10px; clear:both"></div>
							<div style="padding-left:40px;">
								<input type="file" name="image" id="file-7" class="inputfile inputfile-6" />
								<label for="file-7"><span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label>
							</div>
							<div style="height:20px; clear:both"></div>
							<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
							<input type="hidden" name="username" value="<?php echo $seller->username; ?>" >
							<input type="hidden" name="seller" value="<?php echo $_SESSION['seller']['id']; ?>" >
							<button type="submit" name="submit" id="btnSubmit" style="width:30%; margin-left:40px;"/>Save Gallery</button>
						</form>
					</div>
					
				</div>
				<div style="height:30px; clear:both"></div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="file/custom-file-input.js"></script>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#gallery-form input[type="text"]').each(function() {
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
        
		$('#gallery-form input[type="file"]').each(function() {
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
        if (isValid == false) 
            e.preventDefault();
        else 
            ;
    });
});
</script>
