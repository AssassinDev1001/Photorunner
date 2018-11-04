<?php 
include 'include/config.php'; 
if(!empty($_SESSION['account']['id']))
{
	$amounttt = $_POST['amount']/100;
	$amountttt = number_format($amounttt,2);
	$stirpeamount=$_REQUEST['amount'];

	try {
		require_once('Stripe/lib/Stripe.php');
		//Stripe::setApiKey("sk_test_xitA2poC7TfjnP1IGD0FT6rp");
		Stripe::setApiKey("sk_live_f0Zb65LFbH3jQV4zGbzb1AMV");

		 $charge = Stripe_Charge::create(array(
		  "amount" => $stirpeamount,
		  "currency" => "usd",
		  "card" => $_POST['stripeToken'],
		  "description" =>$amount['id']
		));
		if(!empty($_REQUEST['stripeToken']))
		{
			foreach($_SESSION['cart'] as $key=>$valuee)
			{

				$downloadid = $valuee['photo'];
				$conditions = array('id'=>$downloadid);
				$download = $common->getrecord('pr_photos','*',$conditions);


				$currencyCode="USD";
				$conditions = array('id'=>$_SESSION['account']['id']);
				$emaill = $common->getrecord('pr_members','*',$conditions);
				$email1 = $emaill->email;
				if($valuee['type'] == 'webfileprice') 
				{ 
					if($_SESSION['currency'] == 'USD') {
						$amount = urlencode($download->webfileprice);
					}
					if($_SESSION['currency'] == 'EURO') {
						$amount = urlencode($download->webfilepriceeuro);
					}
				}
				if($valuee['type'] == 'printfileprice') 
				{
					if($valuee['size'] == 'A3') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea3);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea3euro);
						}
					}
					if($valuee['size'] == 'A4') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea4);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea4euro);
						}
					}
					if($valuee['size'] == 'A5') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea5);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea5euro);
						}
					}
					if($valuee['size'] == 'othertitle') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->otherprice);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->otherpriceeuro);
						}
					}
				}
				$currencyCode="USD";

				if($valuee['type'] == 'webfileprice')
				{
					$_POST['photoname'] = $download->name;
					$_POST['photoid'] = $download->id;
					$_POST['photographer'] = $download->seller;
					$_POST['txnid'] = $_POST['stripeToken'];
					$_POST['amount'] = $amount;
					$_POST['phototype'] = 'webfile';
					$_POST['ack'] = $_POST['stripeTokenType'];
					$_POST['size'] = $valuee['size'];

					$common->addpayment($_POST, $email1);

				}
				if($valuee['type'] == 'printfileprice')
				{
					$_POST['photoid'] = $download->id;
					$_POST['photoname'] = $download->name;
					$_POST['photographer'] = $download->seller;
					$_POST['txnid'] = $_POST['stripeToken'];
					$_POST['amount'] = $amount;
					$_POST['phototype'] = 'printfile';
					$_POST['ack'] = $_POST['stripeTokenType'];
					$_POST['size'] = $valuee['size'];

					$common->addprintpayment($_POST, $email1);

				}
			}
			unset($_SESSION['cart']);
			$common->add('s', 'Your transaction has been completed please received your File after click on download button.');	
			$common->redirect(APP_URL."buyer/purchase-list.php");			
		}
		else
		{
			$msgs->add('e', 'Something went Wrong.');	
			$common->redirect(APP_URL."buyer/purchase-list.php");
		}
	  
	}
	catch(Stripe_CardError $e) {
	
	}

	//catch the errors in any way you like

	 catch (Stripe_InvalidRequestError $e) {
	  // Invalid parameters were supplied to Stripe's API

	} catch (Stripe_AuthenticationError $e) {
	  // Authentication with Stripe's API failed
	  // (maybe you changed API keys recently)

	} catch (Stripe_ApiConnectionError $e) {
	  // Network communication with Stripe failed
	} catch (Stripe_Error $e) {

	  // Display a very generic error to the user, and maybe send
	  // yourself an email
	} catch (Exception $e) {

	  // Something else happened, completely unrelated to Stripe
	}
}
else
{
	$amounttt = $_POST['amount']/100;
	$amountttt = number_format($amounttt,2);
	$stirpeamount=$_REQUEST['amount'];

	try {
		require_once('Stripe/lib/Stripe.php');
		//Stripe::setApiKey("sk_test_xitA2poC7TfjnP1IGD0FT6rp");
		Stripe::setApiKey("sk_live_f0Zb65LFbH3jQV4zGbzb1AMV");

		 $charge = Stripe_Charge::create(array(
		  "amount" => $stirpeamount,
		  "currency" => "usd",
		  "card" => $_POST['stripeToken'],
		  "description" =>$amount['id']
		));

		if(!empty($_REQUEST['stripeToken']))
		{
			foreach($_SESSION['cart'] as $key=>$valuee)
			{
				$downloadid = $valuee['photo'];
				$conditions = array('id'=>$downloadid);
				$download = $common->getrecord('pr_photos','*',$conditions);


				$currencyCode="USD";
				$email1 = $_SESSION['guast']['email'];
				if($valuee['type'] == 'webfileprice') 
				{ 
					if($_SESSION['currency'] == 'USD') {
						$amount = urlencode($download->webfileprice);
					}
					if($_SESSION['currency'] == 'EURO') {
						$amount = urlencode($download->webfilepriceeuro);
					}
				}
				if($valuee['type'] == 'printfileprice') 
				{
					if($valuee['size'] == 'A3') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea3);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea3euro);
						}
					}
					if($valuee['size'] == 'A4') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea4);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea4euro);
						}
					}
					if($valuee['size'] == 'A5') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->printfilepricea5);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->printfilepricea5euro);
						}
					}
					if($valuee['size'] == 'othertitle') 
					{
						if($_SESSION['currency'] == 'USD') {
							$amount = urlencode($download->otherprice);
						}
						if($_SESSION['currency'] == 'EURO') {
							$amount = urlencode($download->otherpriceeuro);
						}
					}
				}
				$currencyCode="USD";

				if($valuee['type'] == 'webfileprice')
				{
					$_POST['photoname'] = $download->name;
					$_POST['photoid'] = $download->id;
					$_POST['photographer'] = $download->seller;
					$_POST['txnid'] = $_POST['stripeToken'];
					$_POST['amount'] = $amount;
					$_POST['phototype'] = 'webfile';
					$_POST['ack'] = $_POST['stripeTokenType'];
					$_POST['size'] = $valuee['size'];

					$common->addpayment($_POST, $email1);

				}
				if($valuee['type'] == 'printfileprice')
				{
					$_POST['photoid'] = $download->id;
					$_POST['photoname'] = $download->name;
					$_POST['photographer'] = $download->seller;
					$_POST['txnid'] = $_POST['stripeToken'];
					$_POST['amount'] = $amount;
					$_POST['phototype'] = 'printfile';
					$_POST['ack'] = $_POST['stripeTokenType'];
					$_POST['size'] = $valuee['size'];

					$common->addprintpayment($_POST, $email1);

				}
			}
			unset($_SESSION['cart']);
			$common->add('s', 'Your transaction has been completed please received your File after click on download button.');	
			$common->redirect(APP_URL."success.php");		
		}
		else
		{
			$msgs->add('e', 'Something went Wrong.');	
			$common->redirect(APP_FULL_URL);
		}
	  
	}
	catch(Stripe_CardError $e) {
	
	}

	//catch the errors in any way you like

	 catch (Stripe_InvalidRequestError $e) {
	  // Invalid parameters were supplied to Stripe's API

	} catch (Stripe_AuthenticationError $e) {
	  // Authentication with Stripe's API failed
	  // (maybe you changed API keys recently)

	} catch (Stripe_ApiConnectionError $e) {
	  // Network communication with Stripe failed
	} catch (Stripe_Error $e) {

	  // Display a very generic error to the user, and maybe send
	  // yourself an email
	} catch (Exception $e) {

	  // Something else happened, completely unrelated to Stripe
	}
}
?>
