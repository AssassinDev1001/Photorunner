<?php  include('../include/config.php'); include(APP_ROOT.'include/check-seller.php');

if(isset($_POST['update']))
{	
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		list($width, $height, $type, $attr) = getimagesize($_FILES['webfile']['tmp_name'][$i]); 
		$_POST['imagewidth'] = $width;
		$_POST['imageheight'] = $height;

		if($common->updatephoto($_POST, $_FILES))
		{
			$common->redirect(APP_URL."seller/photos.php");
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
	$photo = $common->getrecord('pr_photos','*',$conditions);
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
	<style>
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	</style>
	<link href="radio-button/css/bootstrap-toggle.css" rel="stylesheet">
</head>
<body style="background-color:#EBEBEB">
<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_account"></div>
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
	<div class="col-md-3 no-pading" style="background-color:#fff; height:auto;margin:20px 0;">
		<?php include(APP_ROOT."include/seller-left.php") ?>
	</div>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div>&nbsp;</div>
			<form  action=""  method="post" id="photo-form" enctype='multipart/form-data'> 
			<h2>Upload Photo</h2>
			<label style="padding-left:8px;">Photo Title</label>
			<input type="text" placeholder="Photo Title" name="name" id="name" value="<?php if(isset($photo->name) && !empty($photo->name)) { echo $photo->name; } ?>" style="width:70%;"/>
			<label style="padding-left:8px;">Select Category</label>
			<select class="form-control" style="border-radius:0px; width:70%; margin-bottom:20px;" name="category" id="category" required="required">
				<option value="">Select Category</option>
				<?php
				$conditions = array('status'=>'1');
				$categories = $common->getrecords('pr_categories','*',$conditions);
				if(!empty($categories))
				{
					foreach($categories as $categories)
					{
						?>
						<option value="<?php echo $categories->id; ?>" <?php if(isset($photo->category) && $photo->category == $categories->id) { echo"selected"; } ?>><?php echo $categories->category; ?></option>
						<?php
					}
				}
				?>
			</select>
			<label style="padding-left:8px;">Select Gallery</label>
			<select class="form-control" style="border-radius:0px; width:70%; margin-bottom:20px;" name="gallery" id="gallery" required="required">
				<option value="">Select Gallery</option>
				<?php
				$conditions = array('seller'=>$_SESSION['seller']['id'],'status'=>'1');
				$galleries = $common->getrecords('pr_galleries','*',$conditions);
				if(!empty($galleries))
				{
					foreach($galleries as $galleries)
					{
						?>
						<option value="<?php echo $galleries->id; ?>" <?php if(isset($photo->gallery) && $photo->gallery == $galleries->id) { echo"selected"; } ?>><?php echo $galleries->name; ?></option>
						<?php
					}
				}
				?>
			</select>
			<label style="padding-left:8px;">Web File Photo</label><br/>
			<input type="file" name="webfile" id="file-7" class="inputfile inputfile-6" style="width:70%;" />
			<label for="file-7" id="tabfile-7" style="float:left;"><span><?php ?></span> <strong><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Choose a file&hellip;</strong></label> <?php if(isset($photo->webfile) && !empty($photo->webfile)) { ?><img src="<?php echo APP_URL; ?>uploads/photos/real/<?php echo $photo->webfile; ?>" style="float: left; height: 44px; margin-left: 10px;"><input type="hidden" name="oldwebfile" value="<?php echo $photo->webfile; ?>" /> <?php } ?>
			<div style="clear:both">&nbsp;</div>
				<h2>Set Price</h2>
				<div id="div">
					<label style="padding-left:8px;">Web File Price in USD and EURO</label>

					<div style="clear:both"></div>
					<div style="width:40%; float:left"><input type="text" placeholder="Price in $" name="webfileprice" style="width:100%;" class="number_only" id="webfileprice" value="<?php if(isset($photo->webfileprice) && !empty($photo->webfileprice)) { echo $photo->webfileprice; } ?>"/></div>
					<div style="width:40%;float:left"><input type="text" placeholder="Price in &#8364;" name="webfilepriceeuro" style="width:100%;" class="number_only" id="webfilepriceeuro" value="<?php if(isset($photo->webfilepriceeuro) && !empty($photo->webfilepriceeuro)) { echo $photo->webfilepriceeuro; } ?>"/></div>
					<div style="clear:both"></div>
					<label style="padding-left:8px;">Web File sell in public</label>
					<div style="margin:5px;">
						<input type="checkbox" data-toggle="toggle" name="sellwebpublik" <?php if(!empty($photo->sellwebpublik)) { ?>checked<?php } ?>>
					</div>
					<div style="clear:both;height:15px;"></div>
					<label style="padding-left:8px;">A3 Print File Price in USD and EURO</label>
					<div style="clear:both"></div>
					<div style="width:40%; float:left"><input type="" placeholder="Price in $" name="printfilepricea3" value="<?php if(isset($photo->printfilepricea3) && !empty($photo->printfilepricea3)) { echo $photo->printfilepricea3; } ?>" style="width:100%;" id="printfilepricea3" class="number_only"/></div>
					<div style="width:40%;float:left"><input type="" placeholder="Price in &#8364;" name="printfilepricea3euro"  value="<?php if(isset($photo->printfilepricea3euro) && !empty($photo->printfilepricea3euro)) { echo $photo->printfilepricea3euro; } ?>" style="width:100%;" id="printfilepricea3euro" class="number_only"/></div>
					<div style="clear:both"></div>



					<label style="padding-left:8px;">A4 Print File Price in USD and EURO</label>
					<div style="clear:both"></div>
					<div style="width:40%; float:left"><input type="" placeholder="Price in $" name="printfilepricea4" value="<?php if(isset($photo->printfilepricea4) && !empty($photo->printfilepricea4)) { echo $photo->printfilepricea4; } ?>" style="width:100%;" id="printfilepricea4" class="number_only"/></div>
					<div style="width:40%;float:left"><input type="" placeholder="Price in &#8364;" name="printfilepricea4euro" value="<?php if(isset($photo->printfilepricea4euro) && !empty($photo->printfilepricea4euro)) { echo $photo->printfilepricea4euro; } ?>" style="width:100%;" id="printfilepricea4euro" class="number_only"/></div>
					<div style="clear:both"></div>

					<label style="padding-left:8px;">A5 Print File Price in USD and EURO</label>
					<div style="clear:both"></div>
					<div style="width:40%;float:left"><input type="" placeholder="Price in $" name="printfilepricea5" value="<?php if(isset($photo->printfilepricea5) && !empty($photo->printfilepricea5)) { echo $photo->printfilepricea5; } ?>" style="width:100%;" id="printfilepricea5" class="number_only"/></div>
					<div style="width:40%;float:left"><input type="" placeholder="Price in &#8364;" name="printfilepricea5euro" value="<?php if(isset($photo->printfilepricea5euro) && !empty($photo->printfilepricea5euro)) { echo $photo->printfilepricea5euro; } ?>" style="width:100%;" id="printfilepricea5euro" class="number_only"/></div>
					<div style="clear:both"></div>
					<label style="padding-left:8px;">Print File sell in public</label>
					<div style="margin:5px;">
						<input type="checkbox" data-toggle="toggle" name="sellprintpublik" <?php if(!empty($photo->sellprintpublik)) { ?>checked<?php } ?> >
					</div>
				</div>
				<div style="height:15px;"></div>
				<?php if($photo->othertitle != '0.00' && $photo->otherprice != '0.00' && $photo->otherpriceeuro != '0.00') {?>
				<div id="div1">
					<div style="clear:both;"></div>
					<label style="padding-left:8px;">Set the size</label>
					<input type="text" placeholder="Set the size" name="othertitle" value="<?php if(isset($photo->othertitle) && !empty($photo->othertitle)) { echo $photo->othertitle; } ?>" style="width:70%;" id="othertitle"/>
					<label style="padding-left:8px;">Set the price in USD and EURO</label>
					<div style="clear:both"></div>
					<div style="width:40%;float:left"><input type="text" placeholder="Price in $" name="otherprice" value="<?php if(isset($photo->otherprice) && !empty($photo->otherprice)) { echo $photo->otherprice; } ?>" style="width:100%;" id="otherprice" class="number_only"/></div>
					<div style="width:40%;float:left"><input type="text" placeholder="Price in &#8364;" name="otherpriceeuro" value="<?php if(isset($photo->otherpriceeuro) && !empty($photo->otherpriceeuro)) { echo $photo->otherpriceeuro; } ?>" style="width:100%;" id="otherpriceeuro" class="number_only"/></div>
					<div style="clear:both"></div>
				</div>
				<?php } ?>
				<div style="clear:both; height:20px;"></div>
				<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
				<button id="btnSubmit" style="width:30%;" name="update" type="submit">Upload Image</button>
			</form>
			<div>&nbsp;</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="<?php echo APP_URL; ?>seller/file/custom-file-input.js"></script>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>
  $(document).ready(function() {
            $('.number_only').keypress(function (event) {
                return isNumber(event, this)
            });        

        });
        function isNumber(evt, element) {
            var charCode = (evt.which) ? evt.which : event.keyCode
                    
            if ((charCode != 45 || $(element).val().indexOf('-') != -1) && (charCode != 46 || $(element).val().indexOf('.') != -1) && ((charCode < 48 && charCode != 8) || charCode > 57)){
                 return false;
                    
            }
            else {
                return true;
            }
            
        } 
</script>
<script>
 $(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
</script>
<script>

$(document).ready(function() {
	
	$('#addEmailRow').click(function(e) {
		var emailRow = '<div><input type="email" placeholder="Email Address" name="email[]" style="width:70%;float:left;"/> <img src="<?php echo APP_URL; ?>images/remove.png" title="Remove" alt="Remove" onclick="$(this).parent().remove();" style="float:left;margin-top:5px;"></div>';
		$('#emailrowsdiv').append(emailRow); 		
	});
	
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#photo-form input[type="text"]').each(function() {
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
        
       $('#photo-form select').each(function() {
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
		$('#photo-form input[type="file"]').each(function() {
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

        $('#photo-form input[type="email"]').each(function() {
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
<script src="radio-button/css/bootstrap-toggle.js"></script>
