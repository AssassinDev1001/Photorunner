<?php 
include('include/config.php');
if(empty($_SESSION['account']['id']) && !isset($_SESSION['account']['id']))
{		
	$common->redirect(APP_URL.'log-in.php');
}

if(isset($_GET['delete']))
{
	$remove = $_GET['delete'];
	$_SESSION['cart'][$remove];

	unset($_SESSION['cart'][$remove]);	
	header('location:payment.php');	
}

if(empty($_SESSION['cart']))
{
	$common->redirect(APP_URL."photos.php");
}
if(isset($_POST['paypal']))
{
	$environment = 'live';	// or 'beta-sandbox' or 'live'

	function PPHttpPost($methodName_, $nvpStr_) 
	{
		global $environment;
		$API_UserName = urlencode('fhefoto_api1.yahoo.no');
		$API_Password = urlencode('N6SEDXLJKPN6PPWY');
		$API_Signature = urlencode('AlVtORxlymlrQpGY-fnzOdIuxvT1A2nTmy.LlXvPkOP5oU0VdZitgeEV');
	
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if("sandbox" === $environment || "beta-sandbox" === $environment) {
			$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
		}
		$version = urlencode('51.0');

		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		$httpResponse = curl_exec($ch);

		if(!$httpResponse) {
			exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}

		$httpResponseAr = explode("&", $httpResponse);

		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}

		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}

		return $httpParsedResponseAr;
	}

	$conditionsbuyer = array('id'=>$_SESSION['account']['id']);
	$buyer = $common->getrecord('pr_members','*',$conditionsbuyer);

	
	$paymentType =	'Sale';
	$firstName =urlencode($_POST['cardholder']);
	$lastName = urlencode($_POST['cardholder']);
	$creditCardType = urlencode($_POST['cardtype']);
	$creditCardNumber = urlencode($_POST['card']);
	$expDateMonth = $_POST['expmonth'];
	$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

	$expDateYear = urlencode($_POST['expyear']);
	$cvv2Number = urlencode($_POST['cvv']);
	$country = 'US';
	$amount = urlencode($_POST['totalamount']);
	$currencyID = 'USD';

	$nvpStr ="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";


	$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		$conditions = array('id'=>$_SESSION['account']['id']);
		$emaill = $common->getrecord('pr_members','*',$conditions);
		$email1 = $emaill->email;

		foreach($_SESSION['cart'] as $key=>$valuee)
		{
			$downloadid = $valuee['photo'];
			$conditions = array('id'=>$downloadid);
			$download = $common->getrecord('pr_photos','*',$conditions);

			if($valuee['type'] == 'webfileprice') { 
				$amount = urlencode($download->webfileprice);
			}else{
				$amount = urlencode($download->printfileprice);
			}
			$currencyCode="USD";

			if($valuee['type'] == 'webfileprice')
			{
				$_POST['photoname'] = $download->name;
				$_POST['photoid'] = $download->id;
				$_POST['photographer'] = $download->seller;
				$_POST['txnid'] = strtoupper($resArray["TRANSACTIONID"]);
				$_POST['amount'] = $amount;
				$_POST['phototype'] = 'webfile';
				$_POST['ack'] = $ack;
				$_POST['size'] = $valuee['size'];

				$common->addpayment($_POST, $email1);

	 	
			}
			if($valuee['type'] == 'printfileprice')
			{
				$_POST['photoname'] = $download->name;
				$_POST['photoid'] = $download->id;
				$_POST['photographer'] = $download->seller;
				$_POST['txnid'] = strtoupper($resArray["TRANSACTIONID"]);
				$_POST['amount'] = $amount;
				$_POST['phototype'] = 'printfile';
				$_POST['ack'] = $ack;
				$_POST['size'] = $valuee['size'];

				$common->addprintpayment($_POST, $email1);
			}
		}
		unset($_SESSION['cart']);
		$succ = $httpParsedResponseAr['L_LONGMESSAGE0'];
		$msgs->add('s', urldecode($succ));		
		$common->redirect(APP_URL."buyer/purchase-list.php");	
	}  
	else
	{
		$err = $httpParsedResponseAr['L_LONGMESSAGE0'];
		$msg = urldecode($err);
		$msgs->add('e', $msg);	
		$common->redirect(APP_FULL_URL);

	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<style>
		.stripe-button-el{ border-radius:none; padding: 0 !important; width: auto !important; }
	</style>
</head>
<body style="background-color:#F3F3F3">
	<?php include(APP_ROOT.'include/header.php'); ?>
<div class="space_header_join">&nbsp;</div>
<div style="width:89%; margin:auto;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
<div class="container" style="background-color:#ffffff;">
	<div class="module form-module" style="max-width: 95%; border-top:0px; height:0px;">
		<div style="padding:15px;">
			<a href="<?php echo APP_URL; ?>photos.php" style="color:#fff; text-decoration:none;"><div style="font-size:18px; padding:10px; color:#00A2B5; float:left; background-color:#33b5e5; color:#fff; margin-bottom:10px; border-radius:2px;">Continue Purchasing</div></a>
			<div style="font-size:20px; padding:10px; color:#00A2B5; float:right; color:#33b5e5; margin-bottom:10px; border-radius:2px;">Photo Runner</div>
			<div style="clear:both"></div>
			<?php
			if(!empty($_SESSION['cart']))
			{
				$subtotal = 0;
				foreach($_SESSION['cart'] as $key=>$value)
				{
					
					$id = $value['photo'];
					$conditions = array('id'=>$id);
					$view = $common->getrecord('pr_photos','*',$conditions);
					?>
					<div class="col-md-12 payment_box_123" id="">
						<div>
							<div class="col-md-2" style="padding:10px;"><img src="<?php echo APP_URL; ?>uploads/photos/bigwatermark/<?php echo $view->webfile; ?>" style="width:140px; height:110px;" /></div>
							<div class="col-md-2" style="padding:10px;">
								<div style="font-size:15px; font-weight:bold; padding:5px;">Product Name</div>
								<div style="font-size:15px; font-weight:bold; padding:5px;">Price</div>
								<div style="font-size:15px; font-weight:bold; padding:5px;">File Type</div>
								<div style="font-size:15px; font-weight:bold; padding:5px;">File Size</div>
							</div>
							<div class="col-md-5" style="padding:10px;">
								<div style="font-size:15px; font-weight:bold; padding:5px;"><?php echo $view->name; ?></div>
								<?php if($value['type'] == 'webfileprice') { ?>
									<?php if($_SESSION['currency'] == 'USD') { ?>
										<?php $stripe  = $view->webfileprice; ?>
									<?php } ?>
									<?php if($_SESSION['currency'] == 'EURO') { ?>
										<?php $stripe  = $view->webfilepriceeuro; ?>
									<?php } ?>
									<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($_SESSION['currency'] == 'USD') { ?>$ <?php echo $view->webfileprice; ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>&euro; <?php echo $view->webfilepriceeuro; ?> EURO<?php } ?></div>
									<div style="font-size:15px; font-weight:bold; padding:5px;">Webfile</div>
								<?php } ?>


								<?php if($value['type'] == 'printfileprice') { ?>
									<?php if($value['size'] == 'nosize') { ?>
										<?php if($_SESSION['currency'] == 'USD') { ?>
											<?php $stripe  = $view->printfileprice; ?>
										<?php } ?>
										<?php if($_SESSION['currency'] == 'EURO') { ?>
											<?php $stripe  = $view->printfilepriceeuro; ?>
										<?php } ?>
										<div style="font-size:15px; font-weight:bold; padding:5px;">$ <?php echo $view->printfileprice; ?> USD</div>
										<div style="font-size:15px; font-weight:bold; padding:5px;">Printfile</div>
									<?php } ?>


									<?php if($value['size'] == 'A3') { ?>
										<?php if($_SESSION['currency'] == 'USD') { ?>
											<?php $stripe  = $view->printfilepricea3; ?>
										<?php } ?>
										<?php if($_SESSION['currency'] == 'EURO') { ?>
											<?php $stripe  = $view->printfilepricea3euro; ?>
										<?php } ?>
										<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($_SESSION['currency'] == 'USD') { ?>$ <?php echo $view->printfilepricea3; ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>&euro; <?php echo $view->printfilepricea3euro; ?> EURO<?php } ?></div>
										<div style="font-size:15px; font-weight:bold; padding:5px;">Printfile</div>
									<?php } ?>


									<?php if($value['size'] == 'A4') { ?>
										<?php if($_SESSION['currency'] == 'USD') { ?>
											<?php $stripe  = $view->printfilepricea4; ?>
										<?php } ?>
										<?php if($_SESSION['currency'] == 'EURO') { ?>
											<?php $stripe  = $view->printfilepricea4euro; ?>
										<?php } ?>
										<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($_SESSION['currency'] == 'USD') { ?>$ <?php echo $view->printfilepricea4; ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>&euro; <?php echo $view->printfilepricea4euro; ?> EURO<?php } ?></div>
										<div style="font-size:15px; font-weight:bold; padding:5px;">Printfile</div>
									<?php } ?>
									<?php if($value['size'] == 'A5') { ?>
										<?php if($_SESSION['currency'] == 'USD') { ?>
											<?php $stripe  = $view->printfilepricea5; ?>
										<?php } ?>
										<?php if($_SESSION['currency'] == 'EURO') { ?>
											<?php $stripe  = $view->printfilepricea5euro; ?>
										<?php } ?>
										<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($_SESSION['currency'] == 'USD') { ?>$ <?php echo $view->printfilepricea5; ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>&euro; <?php echo $view->printfilepricea5euro; ?> EURO<?php } ?></div>
										<div style="font-size:15px; font-weight:bold; padding:5px;">Printfile</div>
									<?php } ?>
									<?php if($value['size'] == 'othertitle') { ?>
										<?php if($_SESSION['currency'] == 'USD') { ?>
											<?php $stripe  = $view->otherprice; ?>
										<?php } ?>
										<?php if($_SESSION['currency'] == 'EURO') { ?>
											<?php $stripe  = $view->otherpriceeuro; ?>
										<?php } ?>
										<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($_SESSION['currency'] == 'USD') { ?>$ <?php echo $view->otherprice; ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>&euro; <?php echo $view->otherpriceeuro; ?> EURO<?php } ?></div>
										<div style="font-size:15px; font-weight:bold; padding:5px;"><?php echo $view->othertitle; ?></div>
									<?php } ?>
								<?php } ?>
								<div style="font-size:15px; font-weight:bold; padding:5px;"><?php if($value['size'] == 'nosize') { ?>Normal Size<?php } ?><?php if($value['size'] == 'A3') { ?>A3 Size<?php } ?><?php if($value['size'] == 'A4') { ?>A4 Size<?php } ?><?php if($value['size'] == 'A5') { ?>A5 Size<?php } ?><?php if($value['size'] == 'othertitle') { ?><?php echo $view->othertitle; ?><?php } ?></div>
							</div>
							<div class="col-md-3" style="padding:10px;">
								<div style="font-size:16px; padding-top:10px; color:#66AEDC; float:right"><a href="<?php echo APP_URL; ?>payment.php?delete=<?php echo $key; ?>" style="text-decoration:none"><img src="images/file_delete.png" height="24px" />&nbsp;&nbsp;Remove</a></div>
							</div>
						</div>
					</div>
					<div style="clear:both; height:5px;"></div>
					<?php
					$subtotal = $stripe+$subtotal;
				}
			}
			?>
			<div style="clear:both; height:0px;">&nbsp;</div>
			<div style="font-size:20px; padding:10px; text-align:right; color:#000000; margin-bottom:10px;"><?php if($_SESSION['currency'] == 'USD') { ?>Total Amount : $ <?php echo number_format($subtotal,2); ?> USD<?php } ?><?php if($_SESSION['currency'] == 'EURO') { ?>Total Amount : &euro; <?php echo number_format($subtotal,2); ?> EURO<?php } ?></div>
			<div class="col-md-12">
				<?php /*<div style="color: #00a2b5;font-size: 31px;font-weight: bold; float:left">Pay with Paypal</div>
<div style="margin:12px; float:left; width:150px;"><a class="fancybox" href="#inline" style="padding-top:8px;padding-bottom:8px;padding-left:25px;padding-right:30px; font-weight:bold; color:#fff; text-decoration:none; background-color:#00A2B5; border-radius:2px; ">Pay New</a></div>*/ ?>
			</div>
			<div class="col-md-12">
				<div class="col-md-12" id="inline" style="display: none;" >
					<form class="payment" id="paypal" action="" method="post">
						<label style="font-size:15px; font-weight:bold;">Card Type :</label>
						<select class="form-control" style="border-radius:0px; font-weight:bold; margin: 0 0 20px; width:100%;" name="cardtype" id="cardtype" required="required">
							<option elected="selected" value="Visa" style="font-weight:bold; padding:5px;">Visa</option>
							<option value="MasterCard" style="font-weight:bold; padding:5px;">MasterCard</option>
							<option value="Discover" style="font-weight:bold; padding:5px;">Discover</option>
							<option value="American Express" style="font-weight:bold; padding:5px;">American Express</option>
						</select>
						<label style="font-size:15px; font-weight:bold;">Card Number :</label>
						<input type="text" name="card" id="card" style="width:100%;margin: 0 0 0px; border-radius:0px; height:44px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"/>
						<div style="clear:both; height:20px;"></div>
						<div style="float:left; width:48%;">
							<label style="font-size:15px; font-weight:bold;">Expiration Date:</label>
							<select class="form-control" style="border-radius:0px; margin: 0 0 20px; width:100%; font-weight:bold;" name="expmonth" id="expmonth" required="required">
								<option style="font-weight:bold; padding:5px;" value="01">01</option>
								<option style="font-weight:bold; padding:5px;" value="02">02</option>
								<option style="font-weight:bold; padding:5px;" value="03">03</option>
								<option style="font-weight:bold; padding:5px;" value="04">04</option>
								<option style="font-weight:bold; padding:5px;" value="05">05</option>
								<option style="font-weight:bold; padding:5px;" value="06">06</option>
								<option style="font-weight:bold; padding:5px;" value="07">07</option>
								<option style="font-weight:bold; padding:5px;" value="08">08</option>
								<option style="font-weight:bold; padding:5px;" value="09">09</option>
								<option style="font-weight:bold; padding:5px;" value="10">10</option>
								<option style="font-weight:bold; padding:5px;" value="11">11</option>
								<option style="font-weight:bold; padding:5px;" value="12">12</option>
							</select>
						</div>
						<div style="float:right; width:48%;">
							<label style="font-size:16px;">&nbsp;</label>
							<select class="form-control" style="border-radius:0px; margin: 0 0 20px; width:100%; font-weight:bold;" name="expyear" id="expyear" required="required">
								<option style="font-weight:bold; padding:5px;" value="2015">2015</option>
								<option style="font-weight:bold; padding:5px;" value="2016">2016</option>
								<option style="font-weight:bold; padding:5px;" value="2017">2017</option>
								<option style="font-weight:bold; padding:5px;" value="2018">2018</option>
								<option style="font-weight:bold; padding:5px;" value="2019">2019</option>
								<option style="font-weight:bold; padding:5px;" value="2020">2020</option>
								<option style="font-weight:bold; padding:5px;" value="2021">2021</option>
								<option style="font-weight:bold; padding:5px;" value="2022">2022</option>
								<option style="font-weight:bold; padding:5px;" value="2023">2023</option>
								<option style="font-weight:bold; padding:5px;" value="2024">2024</option>
							</select>
						</div>
						<label style="font-size:15px; font-weight:bold;">CVV Code :</label>
						<input type="hidden" name="totalamount" value="<?php echo $subtotal; ?>"/>
						<input type="text" name="cvv" id="cvv" style="width:100%;margin: 0 0 0px; border-radius:0px; height:44px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"  />
						<div style="clear:both; height:20px;"></div>
						<label style="font-size:15px; font-weight:bold;">Cardholder Name :</label>
						<input type="text" name="cardholder" id="cardholder" style="width:100%;margin: 0 0 0px; border-radius:0px; height:44px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"  />
						<div style="clear:both; height:20px;"></div>
						<button type="submit" name="paypal" style="background-color: #00a2b5;border: 0 none;color: #fff;font-weight: bold;height: 40px;width: 34%;font-weight:bold;" id="btnSubmit" />Pay</button>
						<div style="clear:both; height:10px;">&nbsp;</div>
					</form>	
				</div>
			</div>
			<div style="clear:both;"> &nbsp; </div>
			<div class="col-md-12">
				<div style="color:#00a2b5;font-size: 31px;font-weight: bold; float:left">Pay with Stripe</div>
				<div style="margin-top:10px; margin-left:21px; float:left; width:150px;">
					<form action="charge.php" method="post">
						<?php $stripe1 = $subtotal*100; ?>
						<input type="hidden" name="amount" value="<?php echo $stripe1; ?>"/>
						<?php if($_SESSION['currency'] == 'EURO') { ?>
						<script
							src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							data-key="pk_live_obp9GmiNdWLl0DWA5wYW6rCv"
							data-image="http://www.photorunner.no/images/stripelogo.png"
							data-amount="<?php echo $stripe1; ?>"  data-currency="EUR">
						</script>
						<?php } ?>
						<?php if($_SESSION['currency'] == 'USD') { ?>
						<script
							src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							data-key="pk_live_obp9GmiNdWLl0DWA5wYW6rCv"
							data-image="http://www.photorunner.no/images/stripelogo.png"
							data-amount="<?php echo $stripe1; ?>">
						</script>
						<?php } ?>
						<?php /*<script
							src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							data-key="pk_test_inM99ehBADdrzRTf3wa3ggu2"
							data-image="http://anaadit.net/photorunner/code/images/logo.png"
							data-amount="<?php echo $stripe1; ?>">
						</script>*/ ?>
					</form>	
				</div>
			</div>
		</div>
		<div class="col-md-12"><div style="width:30%; float:left;"><img src="<?php echo APP_URL; ?>images/paypal11.jpg" style="padding-top:50px; width:100%; " ></div></div>
	</div>
	<div style="clear:both; height:20px; float:left">&nbsp;</div>
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
<script type='text/javascript'>
jQuery(function() {
	
	jQuery('input[type="radio"]').click(function(){
        if(jQuery(this).attr("value")=="paypal"){
            jQuery("#authorize").hide();
            jQuery("#paypal").show();
        }
        if(jQuery(this).attr("value")=="authorize"){
            jQuery("#paypal").hide();
            jQuery("#authorize").show();
        }
	});
	
    jQuery("#paypal").validate({
    
        rules: {
			cardtype:
			{
				required: true				
			},
			cardholder:
			{
				required: true				
			},
            card:
			{
				required: true,
                minlength: 16,
				accept: "[0-9]+"				
			},
            cvv: {
                required: true,
                minlength: 3,
				accept: "[0-9]+"
            }
        },
        
        messages: { 
			cardtype:
			{
				required:"Please select your Card Type"							
			},
			cardholder:
			{
				required:"Please enter Cardholder Name"							
			},
            card:
			{
				required:"Please enter your Card Number",
				minlength: "Card Number must be at least 16 characters long",
				accept:"Please enter only digits"							
			},							
            cvv: {
                required:"Please enter your CVV Number",
				minlength: "Card CVV Number must be at least 3 characters long",
				accept:"Please enter only digits"
            }	
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });
	
	jQuery("#authorize").validate({
    
        rules: {
			cardtype:
			{
				required: true				
			},
			cardholder:
			{
				required: true				
			},
            card:
			{
				required: true,
                minlength: 16,
				accept: "[0-9]+"				
			},
            cvv: {
                required: true,
                minlength: 3,
				accept: "[0-9]+"
            }
        },
        
        messages: { 
			cardtype:
			{
				required:"Please select your Card Type"							
			},
			cardholder:
			{
				required:"Please enter Cardholder Name"							
			},
            card:
			{
				required:"Please enter your Card Number",
				minlength: "Card Number must be at least 16 characters long",
				accept:"Please enter only digits"							
			},							
            cvv: {
                required:"Please enter your CVV Number",
				minlength: "Card CVV Number must be at least 3 characters long",
				accept:"Please enter only digits"
            }	
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });
	
	jQuery.validator.addMethod("accept", function(value, element, param) {
	  return value.match(new RegExp("." + param + "$"));
	});
	jQuery.validator.addMethod( 'ContainsAtLeastOneDigit', function (value) { 
		return /[0-9]/.test(value);  
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
