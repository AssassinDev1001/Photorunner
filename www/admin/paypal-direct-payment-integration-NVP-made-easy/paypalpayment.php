<?php

$environment = 'sandbox';

function PPHttpPost($methodName_, $nvpStr_) {
	global $environment;

	$API_UserName = urlencode('sdk-three_api1.sdk.com');
	$API_Password = urlencode('QFZCWN5HZM8VBG7Q');
	$API_Signature = urlencode('A-IzJhZZjhg29XQ2qnhapuwxIDzyAZQ92FRP5dqBzVesOkzbdUONzmOU');
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

$paymentType =	'Sale';
$firstName =urlencode($_POST['John']);
$lastName = urlencode($_POST['Doe']);
$creditCardType = urlencode($_POST['Visa']);
$creditCardNumber = urlencode($_POST['4066901366000455']);
$expDateMonth = $_POST['01'];
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

$expDateYear = urlencode($_POST['2012']);
$cvv2Number = urlencode($_POST['962']);
$address1 = urlencode($_POST['1 Main St']);
$address2 = '';
$city = urlencode($_POST['San Jose']);
$state = urlencode($_POST['CA']);
$zip = urlencode($_POST['95131']);
$country = 'US';
$amount = '1.00';
$currencyID = 'USD';

$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);

echo '<pre>';
print_r($_POST);
if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	exit('Direct Payment Completed Successfully: '.print_r($httpParsedResponseAr, true));
} else  {
	exit('DoDirectPayment failed: ' . print_r($httpParsedResponseAr, true));
}

?>
