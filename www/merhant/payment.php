<?php include('../include/config.php'); 
  
		$amount=urlencode('Sale');
		$CardFirstName=urlencode('Amandeep');
		$Card_Type=urlencode('MasterCard');
		$CardNumber=urlencode('5404000000000001'); 
		$CCVCode=urlencode('100');
		$CardExpiration= urlencode('06/2018');
		$_REQUEST['Add'] ="Added Payment";
		include 'classes/PayPalNVP.php';
		include 'classes/PayPalCodes.php';
		include 'classes/PayPal_DoDirectPayment.php';
		include 'classes/PayPal_GetBalance.php';
		$pp = new PayPal_DoDirectPayment(); 
		$pp->Amount 		= $amount; 
		$pp->CardType 		= $Card_Type; 
		$pp->CardNumber 	= $CardNumber;
		$pp->CardExpiration = $CardExpiration;
		$pp->CardCVV2 		= $CCVCode; 
		$pp->FirstName 		= $CardFirstName;	
		 $pp->send();					
		$arr=$pp->Response;
echo"<pre>";
print_r($arr);
echo"</pre>";

		
	 


?>


    
