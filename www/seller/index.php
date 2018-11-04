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
		if($common->updateseller($_POST, $_FILES))
		{
			$common->redirect(APP_URL."seller/index.php");
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
	<script>
	function getstate(country){
		$.ajax({
			url:"<?php echo APP_URL; ?>buyer/state-ajax.php",
			type:"GET",
			data:"&country="+country,
			success:function(res)
			{
			document.getElementById('show_state').innerHTML=res;
			}
		
		})
	}
	</script>
	<script>
	function getcity(state){
		$.ajax({
			url:"<?php echo APP_URL; ?>buyer/city-ajax.php",
			type:"GET",
			data:"&state="+state,
			success:function(res)
			{
			document.getElementById('show_city').innerHTML=res;
			}
		
		})
	}
	</script>
	<style>
	.line1{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5; border-bottom:1px solid #00A2B5;}
	.line2{margin:0px 0px 0px 15px; padding:10px; color:#00A2B5;}
	</style>
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
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
	<div class="col-md-3 no-pading " style="background-color:#fff; height:auto;margin:20px 0;">
		<?php include(APP_ROOT."include/seller-left.php") ?>
	</div>
	<div class="col-md-9 features features-right padding_account" style="margin:20px 0;">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<form  action=""  method="post" id="register-form" style="margin:30px;" enctype='multipart/form-data'> 
				<h2 style="text-align:center">Update Your Profile</h2>
				<div class="col-md-6">
					<lable style="padding:5px;">Category</lable>
					<select class="form-control" style="border-radius:0px; margin: 0 0 20px;" name="category" id="category" required="required">
						<option value="">Select Category</option>
						<?php
						$conditions = array();
						$categories = $common->getrecords('pr_categories','*',$conditions);
						if(!empty($categories))
						{
							$k=1;
							foreach($categories as $categories)
							{
								?>
								<option value="<?php echo $categories->id; ?>"<?php if($seller->category == $categories->id) { echo"selected='selected'"; }  ?>><?php echo $categories->category; ?></option>
								<?php
							}
						}
						?>
					</select>
					<lable style="padding:5px;">Email Address</lable>
					<input type="email" placeholder="Email Address" name="email" id="email" value="<?php echo $seller->email; ?>" disabled />
					<lable style="padding:5px;">Username</lable>
					<input type="text" placeholder="Username" name="username" id="username" value="<?php echo $seller->username; ?>" disabled />
					<lable style="padding:5px;">First Name</lable>
					<input type="text" placeholder="First Name" name="firstname" id="firstname" value="<?php echo $seller->firstname; ?>" />
					<lable style="padding:5px;">Last Name</lable>
					<input type="text" placeholder="Last Name" name="lastname" id="lastname" value="<?php echo $seller->lastname; ?>" />
					<lable style="padding:5px;">Zip Code</lable>
					<input type="text" placeholder="Zip Code"  id="zip_code" name="zip_code" value="<?php echo $seller->zip_code; ?>" />

					<lable style="padding:5px;">Bank Name</lable>
					<input type="text" placeholder="Bank Name"  id="bankname" name="bankname" value="<?php echo $seller->bankname; ?>" />
					<lable style="padding:5px;">Owner Name</lable>
					<input type="text" placeholder="Owner Name"  id="owner_name" name="owner_name" value="<?php echo $seller->owner_name; ?>" />

				</div>
				<div class="col-md-6">
					<lable style="padding:5px;">Business Name</lable>
					<input type="text" placeholder="Business Name" name="business_name" id="business_name" value="<?php echo $seller->business_name; ?>"/>
					<lable style="padding:5px;">Mobile Number</lable>
					<input type="text" placeholder="Business Phone number" name="phone_number" id="phone_number" class="numeric" value="<?php echo $seller->phone_number; ?>"/>


					<lable style="padding:5px;">Country</lable>
					<input type="text" placeholder="Country" name="country" id="country" value="<?php echo $seller->country; ?>"/>
					<lable style="padding:5px;">State</lable>
					<input type="text" placeholder="State" name="state" id="state" value="<?php echo $seller->state; ?>"/>

					<lable style="padding:5px;">City</lable>
					<input type="text" placeholder="City" name="city" id="city" value="<?php echo $seller->city; ?>"/>

					<lable style="padding:5px;">Bank Number</lable>
					<input type="text" placeholder="Bank Number" name="banknumber" id="banknumber" value="<?php echo $seller->banknumber; ?>"/>
				</div>
				<div class="col-md-12">
					<lable style="padding:5px;">About Us</lable>
					<textarea name="about" id="about" type="text" style="width:100%; border-radius:0px;border: 1px solid #d9d9d9; padding:10px 15px;"><?php echo $seller->about; ?></textarea>
				</div>
				<div style="height:10px; clear:both"></div>
				<div class="col-md-12">
					<lable style="padding:5px;">Geographical Area</lable>
					<textarea name="area" id="area" type="text" style="width:100%; border-radius:0px;border: 1px solid #d9d9d9; padding:10px 15px;"><?php echo $seller->area; ?></textarea>
				</div>
				<div style="height:10px; clear:both"></div>
				<div class="col-md-6">
					<lable style="padding:5px;">Collection</lable>
					<input type="file" name="banner1" id="banner1" style="border:0px;"/>
					<?php if(!empty($seller->banner1)) { ?><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner1; ?>" style="max-width:100px;min-width:100px;max-height:70px;min-height:70px; padding-left:15px; padding-bottom:10px;"><?php } ?>
					<div style="clear:both"></div>
				</div>
				<div class="col-md-6">
					<lable style="padding:5px;">Collection</lable>
					<input type="file" name="banner2" id="banner2" style="border:0px;"/>
					<?php if(!empty($seller->banner2)) { ?><img src="<?php echo APP_URL; ?>uploads/seller/<?php echo $seller->banner2; ?>" style="max-width:100px;min-width:100px;max-height:70px;min-height:70px; padding-left:15px; padding-bottom:10px;"><?php } ?>
					<div style="clear:both"></div>
				</div>
				<div style="height:10px; clear:both"></div>
				<div class="col-md-12">
					<div><lable style="padding:5px;">Set your price in USD and EURO</lable></div>
					<div style="width:15%; float:left"><input type="text" placeholder="$ USD"  id="price" name="price" class="numeric" value="<?php echo $seller->price; ?>"/></div>

					<div style="width:15%; float:left"><input type="text" placeholder="&#8364; EURO"  id="priceeuro" name="priceeuro" class="numeric" value="<?php echo $seller->priceeuro; ?>"/></div>
					<div style="width:70%;"><input type="text" placeholder="Enter text your price is per hour or per day"  id="pricetext" name="pricetext" value="<?php echo $seller->pricetext; ?>"/></div>
				</div>
				<div style="height:10px; clear:both"></div>
				<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
				<input type="hidden" name="id" value="<?php echo $_SESSION['seller']['id']; ?>" >
				<button type="submit" name="submit" id="btnSubmit"/>Update Profile</button>
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
        $('#register-form input[type="text"]').each(function() {
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
        $('#photo-form textarea[type="text"]').each(function() {
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
        
       $('#register-form select').each(function() {
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
<script type="text/javascript">
	var specialKeys = new Array();
	specialKeys.push(8);
	$(function () {
		$(".numeric").bind("keypress", function (e) {
			var keyCode = e.which ? e.which : e.keyCode
			var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
			$(".error").css("display", ret ? "none" : "inline");
			return ret;
		});
	});
</script>

