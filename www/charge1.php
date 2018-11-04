<?php 
include 'include/config.php'; 
$amounttt = $_POST['amount']/100;
$amountttt = number_format($amounttt,2);
$stirpeamount=$_REQUEST['amount'];

try
{

	require_once('Stripe/lib/Stripe.php');
	Stripe::setApiKey("sk_test_xitA2poC7TfjnP1IGD0FT6rp");

	 $charge = Stripe_Charge::create(array(
	  "amount" => $stirpeamount,
	  "currency" => "usd",
	  "card" => $_POST['stripeToken'],
	  "description" =>$amount['id']
	));

	if(!empty($_REQUEST['stripeToken']))
	{
		$downloadid = $_POST['photoid'];
		$conditions = array('id'=>$downloadid);
		$download = $common->getrecord('pr_photos','*',$conditions);


		$email1 = $_SESSION['guast']['email'];
		if($_POST['phototype'] == 'webfileprice') 
		{ 
			$amount = urlencode($download->webfileprice);
		}else{
			$amount = urlencode($download->printfileprice);
		}
		$currencyCode="USD";

		if($_POST['phototype'] == 'webfileprice')
		{
echo 111; exit;
			$_POST['photoname'] = $download->name;
			$_POST['photoid'] = $download->id;
			$_POST['photographer'] = $download->seller;
			$_POST['txnid'] = $_POST['stripeToken'];
			$_POST['amount'] = $amount;
			$_POST['phototype'] = 'webfile';
			$_POST['ack'] = $_POST['stripeTokenType'];

			if($common->addpayment($_POST, $email1))
			{
				$common->add('s', 'Your transaction has been completed please received your File after click on download button.');	
				$common->redirect(APP_URL."success.php");

			}

		}
		if($_POST['phototype'] == 'printfileprice')
		{
echo 222; exit;
			$_POST['photoid'] = $download->id;
			$_POST['photoname'] = $download->name;
			$_POST['photographer'] = $download->seller;
			$_POST['txnid'] = $_POST['stripeToken'];
			$_POST['amount'] = $amount;
			$_POST['phototype'] = 'printfile';
			$_POST['ack'] = $_POST['stripeTokenType'];

			if($common->addprintpayment($_POST, $email1))
			{
				$common->add('s', 'Your transaction has been completed please received your File after click on print button.');	
				$common->redirect(APP_URL."success.php");

			}

		}			
	}
	else
	{
		$msgs->add('e', 'Something went Wrong.');	
		$common->redirect(APP_FULL_URL);
	}
  
}
catch(Stripe_CardError $e) {
	
}
 catch (Stripe_InvalidRequestError $e) {

} catch (Stripe_AuthenticationError $e) {

} catch (Stripe_ApiConnectionError $e) {
} catch (Stripe_Error $e) {

} catch (Exception $e) {

}


?>
