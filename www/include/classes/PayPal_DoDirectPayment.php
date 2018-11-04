<?php 
/**
 * PayPal NVP Transactions, DoDirectPayment Method
 * 
 * @package PayPal NVP Transactions
 * @author Jarvis Badgley
 * @copyright 2011 Jarvis Badgley
 * @link https://github.com/ChiperSoft/PayPal-NVP-Transactions
 *
 */

class PayPal_DoDirectPayment extends PayPalNVP {
	protected $METHOD = 'DoDirectPayment';
	
	public $AuthorizeOnly=false; //boolean
	public $Amount;
	public $CardType;
	public $CardNumber;
	public $CardCVV2;
	public $CardExpiration; //time integer or date string
	public $FirstName;
	public $LastName;
	public $Address;
	public $City;
	public $State;
	public $Zip;
	public $Country='IN';
	public $Currency = 'USD';
	
	public $TransactionID;
	public $CVV2Response;
	public $AVSResponse;
	
	private $REQUIRED = array(
		'Amount',
		'CardType',
		'CardNumber',
		'CardCVV2',
		'CardExpiration',
		'FirstName'

	);
	
	function Send() {
		if ($AuthorizeOnly)	$this['PAYMENTACTION'] = 'Authorization';
		else				$this['PAYMENTACTION'] = 'Sale';
		
		foreach ($this->REQUIRED as $field) {
			if (!$this->$field) throw new PayPalInvalidValueException("No value supplied for $field");
		}
		
		//Charge Amount
		$this['AMT'] = number_format($this->Amount, 2, '.', '');
		
		//Credit Card Type
		$ccType = PayPalCodes::CardType($this->CardType);
		if ($ccType) $this['CREDITCARDTYPE'] = $ccType;
		else throw new PayPalInvalidValueException("Invalid Credit Card Type: {$this->CardType}");
		
		//Credit Card Number
		$this['ACCT'] = $this->CardNumber;

		//Credit Card Expiration Date
		if (!is_numeric($this->CardExpiration)) $ccExp = strtotime($this->CardExpiration);
		else $ccExp = $this->CardExpiration;
		if ($ccExp < time()) throw new PayPalInvalidValueException("Invalid Credit Card Expiration Date: {$this->CardExpiration}");
		$this['EXPDATE'] = date('mY', $ccExp);
		
		//CVV Code
		$this['CVV2'] = $this->CardCVV2;
		
		$this['FIRSTNAME'] = $this->FirstName;
		$this['LASTNAME'] = $this->LastName;
		
		if (!isset(PayPalCodes::$countries[$this->Country])) throw new PayPalInvalidValueException("Country Code is not one of the allowed values: {$this->Country}");
		$this['COUNTRYCODE'] = $this->Country;

		if ($this->Address && $this->City && $this->State && $this->Zip) {
			if (!isset(PayPalCodes::$states[$this->Country][$this->State])) throw new PayPalInvalidValueException("State Code is not one of the allowed values: {$this->State}");

			$this['STREET'] = $this->Address;
			$this['CITY'] = $this->City;
			$this['STATE'] = $this->State;
			$this['ZIP'] = $this->Zip;
		}
		
		$this['CURRENCYCODE'] = $this->Currency;
		
		return parent::Send();
	}
	
	protected function OnSuccess() {
		$this->TransactionID 	= $this->Response['TRANSACTIONID'];
		$this->CVV2Response		= PayPalCodes::$CvvResponse[ $this->Response['CVV2MATCH'] ];
		$this->AVSResponse 		= PayPalCodes::$AvsResponse[ $this->Response['AVSCODE'] ];
	}
}

?>