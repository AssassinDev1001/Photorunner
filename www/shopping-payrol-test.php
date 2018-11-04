<!DOCTYPE html>
<!-- By Designscrazed.com , just a structure for easy usage. -->
<html lang='en'>
<head>
<meta charset="UTF-8" /> 
	<title>Sample Page by Designscrazed.com</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="headline"><img src="img/logo1.png" /></div>
<!--  Start here -->
<div style="background-color:#000;">
	<div id="wrap">
		<div id="grid">
			<div class="column1 column2">
				<div class="step" id="step3">
					<div class="title">
						<h1>Choose a way to pay</h1>
					</div>
					<div class="modify">
						<i class="fa fa-plus-circle"></i>
					</div>
				</div>
				<div class="content" id="shipping">
					<div style="color:#6683b7; font-size:16px;">&nbsp;&nbsp;&nbsp;Pay with PayPal - <span style="font-size:13px;">The faster, safer way to pay</span></div>
				</div>
				<div style="clear:both; height:40px;">&nbsp;</div>
				<div>
					<div style="text-align:center"><img src="img/button123.png" style="width:230px;" /></div>
					<div style="text-align:center; font-size:16px;">or</div>
					<div style="text-align:center; padding-top:8px;"><img src="img/ppcredit-logo-medium.png" style="width:220px; height:45px;" /></div>
				</div>
				<div style="clear:both; height:20px;">&nbsp;</div>
				<div style="height:2px; background-color:#f1f2f3"></div>
				<div style="clear:both; height:20px;">&nbsp;</div>
				<div class="content" id="">
					<div style="color:#6683b7; font-size:16px;">&nbsp;&nbsp;&nbsp;Pay with credit or debit card</span></div>
				</div>
				<div style="clear:both; height:40px;">&nbsp;</div>
				<div style="width:400px; margin:auto;">
					<div class="card-payment">
						<div class="col-md-12" >
							<form class="payment" id="paypal" action="" method="post">
								<div style="padding:5px; font-size:14px;">Card Type :</div>
								<select class="form-control" style="border-radius:0px; font-weight:bold; margin: 0 0 20px; width:100%; padding-left:10px; border: 1px solid #ccc; height:45px;" name="cardtype" id="cardtype" required="required">
									<option elected="selected" value="Visa" style="font-weight:bold; padding:5px;">Visa</option>
									<option value="MasterCard" style="font-weight:bold; padding:5px;">MasterCard</option>
									<option value="Discover" style="font-weight:bold; padding:5px;">Discover</option>
									<option value="American Express" style="font-weight:bold; padding:5px;">American Express</option>
								</select>
								<input type="text" name="card" id="card" placeholder="Card Number" style="width:100%;margin: 0 0 0px; border-radius:0px; height:45px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"/>
								<div style="clear:both; height:20px;"></div>
								<div style="padding:5px; font-size:14px;">Expiration Date:</div>
								<div style="float:left; width:48%;">
									<select class="form-control" style="border-radius:0px; margin: 0 0 20px; width:100%; font-weight:bold; height:45px; border: 1px solid #ccc; padding-left:10px;" name="expmonth" id="expmonth" required="required">
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
									<select class="form-control" style="border-radius:0px; margin: 0 0 20px; width:100%; font-weight:bold; height:45px; border: 1px solid #ccc; padding-left:10px;" name="expyear" id="expyear" required="required">
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
								<input type="hidden" name="totalamount" value="<?php echo $subtotal; ?>"/>
								<input type="text" name="cvv" id="cvv" placeholder="CVV" style="width:100%;margin: 0 0 0px; border-radius:0px; height:44px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"  />
								<div style="clear:both; height:20px;"></div>
								<input type="text" name="cardholder" placeholder="Cardholder Name" id="cardholder" style="width:100%;margin: 0 0 0px; border-radius:0px; height:44px; border: 1px solid #ccc;  font-weight:bold; padding-left:10px;"  />
								<div style="clear:both; height:20px;"></div>
								<button type="submit" name="paypal" style="background-color: #00a2b5;border: 0 none;color: #fff;font-weight: bold;height: 40px;width: 34%;font-weight:bold;" id="btnSubmit" />Pay</button>
								<div style="clear:both; height:10px;">&nbsp;</div>
							</form>	
						</div>
					</div>
					<div class="right">
						<div class="secured">
							<img class="lock" src="img/lock.png">
							<p class="security info">Secure payments by PayPal</p>
						</div>
					</div>
				</div>
			</div>
			<div class="column column3">
				<div class="step" id="step5">
					<div class="title">
						<h1></h1>
					</div>
				</div>
				<div class="content" id="final_products">
					<div class="left" id="ordered">
						<div class="products">
							<div class="product_details">
								<span class="product_name">Order Summary</span>
							</div>
						</div>
					</div>	
					<div class="right" id="reviewed">
						<div class="billing">
							<span class="title">Billing:</span>
							<div class="address_reviewed">
								<span class="name">John Smith</span>
								<span class="address">123 Main Street</span>
								<span class="location">Everytown, USA, 12345</span>
								<span class="phone">(123)867-5309</span>
							</div>
						</div>
						<div class="shipping">
							<span class="title">Shipping:</span>
							<div class="address_reviewed">
								<span class="name">John Smith</span>
								<span class="address">123 Main Street</span>
								<span class="location">Everytown, USA, 12345</span>
								<span class="phone">(123)867-5309</span>
							</div>
						</div>
						<div class="payment">
							<span class="title">Payment:</span>
							<div class="payment_reviewed">
								<span class="method">Visa</span>
								<span class="number_hidden">xxxx-xxxx-xxxx-1111</span>
							</div>
						</div>
						<div id="complete">
							<a class="big_button" id="complete" href="#">Complete Order</a>
							<span class="sub">By selecting this button you agree to the purchase and subsequent payment for this order.</span> 
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
