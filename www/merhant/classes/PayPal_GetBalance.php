<?php 
/**
 * PayPal NVP Transactions, GetBalance Method
 *
 * @package PayPal NVP Transactions
 * @author Jarvis Badgley
 * @copyright 2011 Jarvis Badgley
 * @link https://github.com/ChiperSoft/PayPal-NVP-Transactions
 *
 */

class PayPal_GetBalance extends PayPalNVP {
	protected $METHOD = 'GetBalance';
	public $Balance;
	public $Currency;
	
	protected function OnSuccess() {
		$this->Balance 		= $this->Response['L_AMT0'];
		$this->Currency 	= $this->Response['L_CURRENCYCODE0'];
	}
}

