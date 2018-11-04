<?php 
include('include/config.php');
if(isset($_POST['submit']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->registration($_POST))
		{
			$common->redirect(APP_FULL_URL);
		}
		else
		{
			$common->redirect(APP_FULL_URL);
		}
	}
}
if(isset($_POST['register']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		if($common->sellerregistration($_POST, $_FILES))
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
	<link rel="stylesheet" href="efacts/tabs/style.css" />
	<script src="efacts/tabs/jquery-1.9.1.min.js"></script>
	<script src="efacts/tabs/tabs.js"></script>
	<link rel="stylesheet" href="image-field/css/jquery.simplefileinput.css">
	<script>
	function getstate(country){
		$.ajax({
			url:"<?php echo APP_URL; ?>state-ajax.php",
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
			url:"<?php echo APP_URL; ?>city-ajax.php",
			type:"GET",
			data:"&state="+state,
			success:function(res)
			{
			document.getElementById('show_city').innerHTML=res;
			}
		
		})
	}
	</script>
</head>
<body style="background-color:#F3F3F3">
	<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_header_join">&nbsp;</div>
<div style="width:48%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
<div id="wrapper">
	<div id="main">
		<div class="container">
			<ul id="tabs">
				<li class="active">Photographer</li>
				<li>Buyer</li>
			</ul>
			<ul id="tab">
				<li class="active">
					<div class="module form-module" style="max-width: 820px;">
						<div class="form"></div>
						<div class="form">
							<h2 style="text-align:center">Registration with PhotoRunner</h2>
							<form  action=""  method="post" id="photo-form" enctype='multipart/form-data'> 
								<input type="email" placeholder="Email Address" name="email" id="email"/>
								<input type="text" placeholder="User Name" name="username" id="username"/>
								<div style="float:left; width:47%;"><input type="password" placeholder="Password" name="passwordd" id="passwordd"/></div><div style="width:4%; float:left">&nbsp;</div><div style="width:47%; float:right"><input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword"/></div>
								<div style="clear:both;"></div>
								<div style="float:left; width:47%;"><input type="text" placeholder="First Name" name="firstname" id="firstname"/></div><div style="width:4%; float:left">&nbsp;</div><div style="width:47%; float:right"><input type="text" placeholder="Last Name" name="lastname" id="lastname"/></div>
								<div style="clear:both;"></div>
								<input type="text" placeholder="Business Name"  id="business_name" name="business_name" />
								<input type="text" placeholder="Business Phone number"  id="phone_number" name="phone_number" class="numeric"/>


								<input type="text" placeholder="Bank name"  id="bankname" name="bankname" />
								<div style="float:left; width:47%;"><input type="text" placeholder="Account owner's name"  id="owner_name" name="owner_name" /></div>
								<div style="float:right; width:47%;"><input type="text" placeholder="Bank Account Number"  id="banknumber" name="banknumber" class="numeric"/></div>
								<div style="clear:both;"></div>
								<div style="float:left; width:47%;"><input type="text" placeholder="Country"  id="country" name="country"/></div>
								<div style="float:right; width:47%;"><input type="text" placeholder="State"  id="state" name="state"/></div>
								<div style="clear:both;"></div>
								<div style="float:left; width:47%;"><input type="text" placeholder="City"  id="city" name="city"/></div>
								<div style="float:right; width:47%;"><input type="text" placeholder="Zip Code"  id="zip_code" name="zip_code"/></div>
								<div style="clear:both;"></div>
								<textarea name="about" id="about" type="text" style="width:100%; border-radius:0px;border: 1px solid #d9d9d9; padding:10px 15px;"  placeholder="Write about your self"></textarea>
								<div style="clear:both; height:10px;"></div>
								<div style="font-weight:bold; margin-bottom:10px;">Brows your best collation</div>
								<div style="float:left; width:47%;"><input type="file" id="banner1" name="banner1"/></div>
								<div style="float:right; width:47%;"><input type="file" id="banner2" name="banner2"/></div>
								<div style="clear:both;"></div>
								<textarea name="area" id="area" type="text" style="width:100%; border-radius:0px;border: 1px solid #d9d9d9; padding:10px 15px;"  placeholder="Geographical area for work"></textarea>
								<div style="clear:both; height:10px;"></div>

								<div style="font-weight:bold; margin-bottom:10px;">Set your price in USD and EURO</div>
								<div style="width:15%; float:left"><input type="text" placeholder="$ USD"  id="price" name="price" class="numeric"/></div>
								<div style="width:15%; float:left"><input type="text" placeholder="&#8364; EURO"  id="priceeuro" name="priceeuro" class="numeric"/></div>
								<div style="width:70%;"><input type="text" placeholder="Enter text your price is per hour or per day"  id="pricetext" name="pricetext"/></div>
								<div class="form_riwidth" style="float:left">
									<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br></a>	
									<span style="float:left; color:grey;">Can't read the image? click <a href='javascript: refreshCaptcha();' style="font-weight:bold; color:#00A2B5;">&nbsp;&nbsp;here&nbsp;&nbsp;</a>to refresh</span>
									<input type="text" id="6_letters_code" name="6_letters_code" class="input_div_div" placeholder="Enter The Code Above here" required="required"/>
									</br>
								</div>
								<script language='JavaScript' type='text/javascript'>
								function refreshCaptcha()
								{
									var img = document.images['captchaimg'];
									img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
								}
								</script>
								<div style="clear:both"></div>
								<div style="float:left; width:30px;"><input type="checkbox" placeholder="User Name" name="checkbox" id="checkbox" required="required"/></div><div>&nbsp;&nbsp;&nbsp;I agree to accept the <b>terms</b> & <b>conditions</b>.</div>
								<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
								<input type="hidden" name="type" value="buyer"/>
								<button type="submit" name="register" id="register"/>Register</button>
							</form>
						</div>
					</div>
				</li>
				<li>
					<div class="module form-module">
						<div class="form"></div>
						<div class="form">
							<h2 style="text-align:center">Registration with PhotoRunner</h2>
							<form  action=""  method="post" id="buyer-form"> 
								<div style="float:left; width:48%;"><input type="text" placeholder="First Name" name="firstname" id="firstname"/></div><div style="width:4%; float:left">&nbsp;</div><div style="width:48%; float:right;"><input type="text" placeholder="Last Name" name="lastname" id="lastname"/></div>
								<div style="clear:both;"></div>
								<input type="email" placeholder="Email Address" name="email" id="email"/>
								<input type="text" placeholder="Phone Number"  id="mobile" name="mobile" class="numeric"/>
								<input type="text" placeholder="User Name" name="username" id="username"/>
								<input type="password" placeholder="Password" name="password" id="password"/>
								<div style="float:left; width:30px;">
									<input type="checkbox" name="checkbox" required="required"/>
								</div>
								<div>&nbsp;&nbsp;&nbsp;I agree to accept the <b>terms</b> & <b>conditions</b>.</div>
								<div style="clear:both"></div>
								<noscript><input name="js_enabled" type="hidden" value="1"></noscript>
								<input type="hidden" name="type" value="buyer"/>
								<button type="submit" name="submit" id="btnSubmit"/>Join Us</button>
							</form>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</div>
<div style="height:100px;">&nbsp;</div>
	<?php include(APP_ROOT.'include/footer.php') ?>
	<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>

$(document).ready(function() {
    $('#btnSubmit').click(function(e) {
        var isValid = true;
        $('#buyer-form input[type="text"]').each(function() {
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

        $('#buyer-form input[type="email"]').each(function() {
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
						"border": "",
					});
				}
                
            }
        });
        $('#buyer-form input[type="password"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
					"border": "1px solid red",
                });
            }
            else {
				if($.trim($(this).val()).length < 6 || $.trim($(this).val()).length > 18)
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
        });
       
        if (isValid == false) 
            e.preventDefault();
        else 
            ;
    });
});
</script>
<script>

$(document).ready(function() {
    $('#register').click(function(e) {
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

        $('#photo-form textarea[type="text"]').each(function() {
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

        $('#photo-form input[type="file"]').each(function() {
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
					"border": "",
				});
			}
                
            }
        });
        $('#photo-form input[type="password"]').each(function() {
            if ($.trim($(this).val()) == '') {
                isValid = false;
                $(this).css({
                    "border": "1px solid red",
                });
            }
            else 
	    {
		if($.trim($(this).val()).length < 6 || $.trim($(this).val()).length > 18)
		{
			isValid = false;
			$(this).css({
				"border": "1px solid red",
			});
		}
		else 
		{
			if($(this).attr('name') == 'cpassword')
			{
				if($.trim($(this).val()) != $.trim($('input[name="passwordd"]').val()))
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
			else
			{
				$(this).css({
					"border": "",
					"background": ""
				});
			}					
		}
            }
        });  

	$('#photo-form select[name="country"]').each(function() {
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
        
        $('#photo-form select[name="state"]').each(function() {
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

	$('#photo-form select[name="city"]').each(function() {
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

	$('#photo-form select[name="category"]').each(function() {
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
