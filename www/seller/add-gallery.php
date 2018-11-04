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
			$common->redirect(APP_URL."seller/galleries.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

if(isset($_POST['update']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->updategallery($_POST, $_FILES))
		{
			$common->redirect(APP_URL."seller/galleries.php");
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$conditions = array('id'=>base64_decode($_GET['id']),'seller'=>$_SESSION['seller']['id']);
	$gellery = $common->getrecord('pr_galleries','*',$conditions);
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<link rel="stylesheet" type="text/css" href="file/demo.css" />
	<link rel="stylesheet" type="text/css" href="file/component.css" />
	<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#gallery-form").submit(function() {
				$(".se-pre-con").css('display','block');
			});
		});
	</script>
	<style>
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	</style>
	<style>
	.se-pre-con {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		opacity: 0.5;
		background: url(<?php echo APP_URL; ?>images/loading6.gif) center no-repeat #fff;
		background-size: 90px 90px;
	}
	</style>
</head>
<body style="background-color:#EBEBEB">
<div class="se-pre-con"></div>
<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div class="col-md-3 no-pading" style="background-color:#fff; height:auto;margin:20px 0;">
		<?php include(APP_ROOT."include/seller-left.php") ?>
	</div>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
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
						<h2 style="text-align:left">Add Gallery</h2>
						<form  action=""  method="post" id="gallery-form" enctype='multipart/form-data'> 
							<div style="padding-left:10px; padding-bottom:2px;">Gallery Name</div>
								<input type="text" placeholder="Gallery Name" name="name" id="name" value="<?php if(isset($gellery->name) && !empty($gellery->name)) { echo $gellery->name; } ?>" style="width:50%;"/>
							<div style="clear:both"></div>
							<div style="padding-left:10px; padding-bottom:2px;">Create Password for Gallery <span stgyle="color:red; font-size:14px;">( optional )</span></div>
								<input type="password" placeholder="Password" name="password" id="password" value="<?php if(isset($gellery->password) && !empty($gellery->password)) { echo $gellery->password; } ?>" style="width:50%;"/>
							<div style="height:10px; clear:both"></div>
							<div>
								<input type="file" name="image" id="file-7" class="inputfile inputfile-6" />
								<label for="file-7" id="tabfile-7" style="float:left;"><span></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label> <?php if(isset($gellery->image) && !empty($gellery->image)) { ?><img src="<?php echo APP_URL; ?>uploads/galleries/<?php echo $gellery->image; ?>" style="float: left; height: 44px; margin-left: 10px;"><input type="hidden" name="oldimage" value="<?php echo $gellery->image; ?>" /> <?php } ?>
								<div style="clear:both">&nbsp;</div>
							</div>
							<div style="height:20px; clear:both"></div>
							<?php
							if(!isset($_GET['id']) && empty($_GET['id']))
							{
								?>
								<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
								<button type="submit" name="submit" id="btnSubmit" style="width:30%;"/>Add Gallery</button>
								<?php
							}
							else
							{
								?>
								<div style="clear:both; height:20px;"></div>
								<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
								<button id="btnSubmit" style="width:30%;" name="update" type="submit">Update Image</button>
								<?php
							}
							?>
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
        
		<?php
		if(!isset($_GET['id']) && empty($_GET['id']))
		{
		?>
		$('#gallery-form input[type="file"]').each(function() {
			var fileid = $(this).attr('id');
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $("#tab"+fileid).css({
                    "border": "1px solid red",
                });
            }
            else {
                $("#tab"+fileid).css({
					"border": "1px solid #3cb371",
                });
            }
        });
		<?php
		}
		?>
		
        if (isValid == false) 
            e.preventDefault();
        else 
            ;
    });
});
</script>
<script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
</script>
