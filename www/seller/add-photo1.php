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
		if($_POST['emailsend'] == 'gallery')
		{
			for($i=0; $i < count($_FILES['webfile']['name']); $i++)
			{
				$file['name'] = $_FILES['webfile']['name'][$i];
				$file['type'] = $_FILES['webfile']['type'][$i];
				$file['tmp_name'] = $_FILES['webfile']['tmp_name'][$i];
				$file['error'] = $_FILES['webfile']['error'][$i];
				$file['size'] = $_FILES['webfile']['size'][$i];
				$files['name'] = $_FILES['printfile']['name'][$i];
				$files['type'] = $_FILES['printfile']['type'][$i];
				$files['tmp_name'] = $_FILES['printfile']['tmp_name'][$i];
				$files['error'] = $_FILES['printfile']['error'][$i];
				$files['size'] = $_FILES['printfile']['size'][$i];
				$common->addphoto1($_POST, $file, $files);
			}
			
			for($i=0; $i < count($_POST['email']); $i++)
			{
				$email = $_POST['email'][$i];
				if(!empty($email))
				{
					$conditions = array('id'=>$_POST['gallery']);
					$checkgallery = $common->getrecord('pr_galleries','*',$conditions) ;
					if(!empty($checkgallery->password))
					{
						$common->sentgallery($_POST, $email, $checkgallery);
					}
					else
					{
						$common->sentgalleryunsecore($_POST, $email);
					}
				}
			}
			$common->add('s', 'Photo Info has been added successfully.');	
			$common->redirect(APP_URL."seller/photos.php");
		}
		if($_POST['emailsend'] == 'image')
		{
			$allids = array();
			for($i=0; $i < count($_FILES['webfile']['name']); $i++)
			{
				$file['name'] = $_FILES['webfile']['name'][$i];
				$file['type'] = $_FILES['webfile']['type'][$i];
				$file['tmp_name'] = $_FILES['webfile']['tmp_name'][$i];
				$file['error'] = $_FILES['webfile']['error'][$i];
				$file['size'] = $_FILES['webfile']['size'][$i];
				$files['name'] = $_FILES['printfile']['name'][$i];
				$files['type'] = $_FILES['printfile']['type'][$i];
				$files['tmp_name'] = $_FILES['printfile']['tmp_name'][$i];
				$files['error'] = $_FILES['printfile']['error'][$i];
				$files['size'] = $_FILES['printfile']['size'][$i];
				$lastid = $common->addphoto5($_POST, $file, $files);
				$allids[] = $lastid;
			}
			$gallery = $_POST['gallery'];
			$conditions = array('id'=>$gallery);
			$checkgallery = $common->getrecord('pr_galleries','*',$conditions) ;
			if(!empty($checkgallery->password))
			{
				for($i=0; $i < count($_POST['email']); $i++)
				{
					$email = $_POST['email'][$i];
					if(!empty($email))
					{
						$code = base64_encode($checkgallery->id);
						$password10 = $checkgallery->password;
						$subject = "Something New in Photorunner";
						$message ="<html><body>
						<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px; font-family:arial;'>PhotoRunner</div>".
						"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center; font-family:arial;'>To get in touch with new exciting things, just click on the button below<br/><br/>".


						"<a href='".APP_URL."photos.php?gallery=".$code."&lock=unlock&email=".$email."' style='color:#fff; font-family:arial; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px; padding-bottom:10px;'><div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:50px; font-family:arial; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> Click Me !</div></a></div><br/><br/>".
						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>Password = ".$password10."</div><br/>".

						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
						"<div style='font-size:14px; text-align:left;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";

						$common->sendemail($email,$subject,$message);
					}
				}
			}
			else
			{	
				for($i=0; $i < count($_POST['email']); $i++)
				{

					/* echo"<pre>";
					print_r($allids);
					echo"</pre>okk"; */
					$email = $_POST['email'][$i];
					if(!empty($email))
					{
						$subject = "Something New in Photorunner";
						$message ="<html><body>
						<div style='color:#00A2B5; font-size:46px; font-weight:bold; margin:20px; font-family:arial;'>PhotoRunner</div>".
						"<div style='color:#6B555A; border:1px solid #ccc; margin-top:30px; width:50%; margin-top:20px; margin-left:auto; margin-right:auto; padding:10px; padding-top:30px; font-size:16px; background-color:#F2F2F2; text-align:center; font-family:arial;'>To get in touch with new exciting things, just click on the button below<br/><br/>";
					
						if(!empty($allids))
						{
							$ct = 1;
							foreach($allids as $value)
							{
								$code = base64_encode($value);
								$message .= "<a href='".APP_URL."view-photo.php?view=".$code."&email=".$email."' style='color:#fff; font-family:arial; text-decoration:none; font-size:22px; font-weight:bold; margin-bottom:20px; padding-bottom:10px;'><div style='width:250px; margin-left:auto; margin-right:auto; background-color:#00A2B5; height:50px; font-family:arial; border-radius:5px; padding-top:15px; padding-bottom:15px; margin-bottom:30px;'> Picture ".$ct." Buy Now </div></a>"; 
							$ct = $ct+1;
							}
						}
					
						$message .="</div><br/><br/>".

						"<div style='font-size:16px; font-family:arial; text-align:center; color:#00A2B5;'>If you need any help, Please contact us at post@photorunner.no</div><br/>".
						"<div style='font-size:14px; text-align:left;'>Team<br/>Photo Runner</div>".
						"</div></body></html>";

						$common->sendemail($email,$subject,$message);
					}
				}
			}	
			
			$common->add('s', 'Photo Info has been added successfully.');	
			$common->redirect(APP_URL."seller/photos.php");
		}
	}
}

phpinfo();
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<link rel="stylesheet" href="image-field/css/jquery.simplefileinput.css">
	<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>

	<link href="css/jquery.filer.css" type="text/css" rel="stylesheet" />
	<link href="css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="js/jquery.filer.min.js?v=1.0.5"></script>
	<script type="text/javascript" src="js/custom.js?v=1.0.5"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#photo-form").submit(function() {
				//$(".form-module").attr('id','loaderdiv');
				$(".se-pre-con").css('display','block');
			});
		});
	</script>
	<style>
	#loaderdiv { background: #fff url("http://www.photorunner.no/images/Preloader_2.gif") no-repeat scroll center center; }
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	/* end only demo styles */

	.checkbox-custom, .radio-custom {
	    opacity: 0;
	    position: absolute;   
	}

	.checkbox-custom, .checkbox-custom-label, .radio-custom, .radio-custom-label {
	    display: inline-block;
	    vertical-align: middle;
	    margin: 5px;
	    cursor: pointer;
	}

	.checkbox-custom-label, .radio-custom-label {
	    position: relative;
	}

	.checkbox-custom + .checkbox-custom-label:before, .radio-custom + .radio-custom-label:before {
	    content: '';
	    background: #fff;
	    border: 2px solid #ddd;
	    display: inline-block;
	    vertical-align: middle;
	    width: 20px;
	    height: 20px;
	    padding: 2px;
	    margin-right: 10px;
	    text-align: center;
		
	}

	.checkbox-custom:checked + .checkbox-custom-label:before {
	    background: rebeccapurple;
	}

	.radio-custom + .radio-custom-label:before {
	    border-radius: 50%;
	}

	.radio-custom:checked + .radio-custom-label:before {
	    background: #90cd18;
	}


	.checkbox-custom:focus + .checkbox-custom-label, .radio-custom:focus + .radio-custom-label {
	  outline: 1px solid #ddd; /* focus style */
	}
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
		background: url(<?php echo APP_URL; ?>images/Preloader_2.gif) center no-repeat #fff;
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
			<div style="clear:both"></div>
			<div id="filerowsdiv">
				<label style="padding-left:8px;">Web File Photo</label> ( Best size 1200*800 )
				<div style="clear:both; height:10px;"></div>
				<input type="file" name="webfile[]" id="filer_input2" multiple="multiple">
				<div style="clear:both; height:10px;"></div>
				<label style="padding-left:8px;">Print File Photo</label> ( Best size 1200*800 )
				<div style="clear:both; height:10px;"></div>
				<input type="file" name="printfile[]" id="filer_input3" multiple="multiple">
			</div>
			<div style="clear:both; height:20px;"></div>
			<div style="float:left">
				<div>
				    <input id="radio-1" class="radio-custom" name="radio-group" type="radio" checked>
				    <label for="radio-1" class="radio-custom-label">Premium File</label>
				</div>
			</div>
			<div>
				<div>
				    <input id="radio-2" class="radio-custom" name="radio-group" type="radio">
				    <label for="radio-2" class="radio-custom-label">Free File</label>
				</div>
			</div>
			<div style="clear:both"></div>
			<div id="div">
				<label style="padding-left:8px;">Web File Price in USD</label>
				<input type="text" placeholder="Web File Price in $" name="webfileprice" value="" style="width:70%;" class="number_only" id="webfileprice" />				
				<label style="padding-left:8px;">Print File Price in USD</label>
				<input type="text" placeholder="Print File Price in $" name="printfileprice" value="" style="width:70%;" id="printfileprice" class="number_only"/>
				<label style="padding-left:8px;">A3 Print File Price in USD</label>
				<input type="" placeholder="A3 Print File Price in $" name="printfilepricea3" value="" style="width:70%;" id="printfilepricea3" class="number_only"/>
				<label style="padding-left:8px;">A4 Print File Price in USD</label>
				<input type="" placeholder="A4 Print File Price in $" name="printfilepricea4" value="" style="width:70%;" id="printfilepricea4" class="number_only"/>
				<label style="padding-left:8px;">A5 Print File Price in USD</label>
				<input type="" placeholder="A5 Print File Price in $" name="printfilepricea5" value="" style="width:70%;" id="printfilepricea5" class="number_only"/>
			</div>
			<div style="clear:both;"></div>

			<div style="float:left">
				<div>
				    <input id="radio-3" class="radio-custom" name="emailsend" type="radio" value="gallery" checked>
				    <label for="radio-3" class="radio-custom-label">Send Gallery Link</label>
				</div>
			</div>
			<div>
				<div>
				    <input id="radio-4" class="radio-custom" name="emailsend" value="image" type="radio">
				    <label for="radio-4" class="radio-custom-label">Send Image Link</label>
				</div>
			</div>
			<div style="clear:both;"></div>
			<label style="padding-left:8px;">Email Address</label> ( optional )
			<div id="emailrowsdiv">
				<div>
					<input type="email" placeholder="Email Address" name="email[]" style="width:70%;float:left;"/>
				</div>
			</div><div style="clear:both;"></div>
			<a href="javascript:void();" id="addEmailRow">+ Add More</a>
			<div style="clear:both; height:20px;"></div>
			<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
			<button id="btnSubmit" style="width:30%;" name="submit" type="submit">Upload Image</button>
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
			"border": "",
		});
	    }
	});	
	$('#photo-form input[type="file"]').each(function() {
	    if ($.trim($(this).val()) == '') {
		isValid = false;
		$(this).parent().css({
			 "border": "2px dashed red",
		});
	    }
	    else {
		$(this).parent().css({
			"border": "",
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
			"border": "",
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
<script>
$(document).ready(function(){	
	
	$("#radio-2").click(function(){
		if($("#radio-2").is(':checked')==true){
			
			$("#div").hide();
			$("#webfileprice").val('0.00');
			$("#printfileprice").val('0.00');
			$("#printfilepricea3").val('0.00');
			$("#printfilepricea4").val('0.00');
			$("#printfilepricea5").val('0.00');
			
		}
	});
	$("#radio-1").click(function(){
		if($("#radio-1").is(':checked')==true){
			
			$("#div").show();
			$("#webfileprice").val('');
			$("#printfileprice").val('');
			$("#printfilepricea3").val('');
			$("#printfilepricea4").val('');
			$("#printfilepricea5").val('');
			
		}
	});	
	
	$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        
});	
</script>
<link rel="stylesheet" href="<?php echo APP_URL; ?>js/dist/ladda.min.css">
<script src="<?php echo APP_URL; ?>js/dist/spin.min.js"></script>
<script src="<?php echo APP_URL; ?>js/dist/ladda.min.js"></script>
<script>

	// Bind normal buttons
	Ladda.bind( '.button-demo button', { timeout: 2000 } );

	// Bind progress buttons and simulate loading progress
	Ladda.bind( '.progress-demo button', {
		callback: function( instance ) {
			var progress = 0;
			var interval = setInterval( function() {
				progress = Math.min( progress + Math.random() * 0.1, 1 );
				instance.setProgress( progress );

				if( progress === 1 ) {
					instance.stop();
					clearInterval( interval );
				}
			}, 200 );
		}
	} );
</script>
<script>
	$(window).load(function() {
		$(".se-pre-con").fadeOut("slow");;
	});
</script>
