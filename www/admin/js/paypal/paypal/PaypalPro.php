<?php
class PaypalPro
{
    var $apiUsername = 'gamego_1353134285_biz_api1.gmail.com';
    var $apiPassword = '1353134312';
    var $apiSignature = 'AlVtORxlymlrQpGY-fnzOdluxvTlA2nTmy.LlXvPkOP5oUOVdZitgeEV';
    var $apiEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';
    var $subject = 'Sale';
    var $authToken = '';
    var $authSignature = '';
    var $authTimestamp = '';
    var $useProxy = FALSE;
    var $proxyHost = '127.0.0.1';
    var $proxyPort = 808;
    var $paypalURL = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
    var $version = '65.1';
    var $ackSuccess = 'SUCCESS';
    var $ackSuccessWarning = 'SUCCESSWITHWARNING';
    
    public function __construct($config = array()){ 
        ob_start();
        session_start();
        if (count($config) > 0){
            foreach ($config as $key => $val){
                if (isset($key) && $key == 'live' && $val == 1){
                    $this->paypalURL = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
                }else if (isset($this->$key)){
                    $this->$key = $val;
                }
            }
        }
    }
    public function nvpHeader(){
        $nvpHeaderStr = "";
    
        if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature)) && (!empty($subject))) {
            $authMode = "THIRDPARTY";
        }else if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature))) {
            $authMode = "3TOKEN";
        }elseif (!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp)) {
            $authMode = "PERMISSION";
        }elseif(!empty($subject)) {
            $authMode = "FIRSTPARTY";
        }
        
        switch($authMode) {
            case "3TOKEN" : 
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature);
                break;
            case "FIRSTPARTY" :
                $nvpHeaderStr = "&SUBJECT=".urlencode($this->subject);
                break;
            case "THIRDPARTY" :
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature)."&SUBJECT=".urlencode($subject);
                break;		
            case "PERMISSION" :
                $nvpHeaderStr = $this->formAutorization($this->authToken,$this->authSignature,$this->authTimestamp);
                break;
        }
        return $nvpHeaderStr;
    }
    
    public function hashCall($methodName,$nvpStr){
        $nvpheader = $this->nvpHeader();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->apiEndpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        if(!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp))
         {
            $headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        else 
        {
            $nvpStr = $nvpheader.$nvpStr;
        }
        if($this->useProxy)
            curl_setopt ($ch, CURLOPT_PROXY, $this->proxyHost.":".$this->proxyPort); 
    
        if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
            $nvpStr = "&VERSION=" . urlencode($this->version) . $nvpStr;	
        }
        
        $nvpreq="METHOD=".urlencode($methodName).$nvpStr;
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
    
   
        $response = curl_exec($ch);
        
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray']=$nvpReqArray;
    
        if (curl_errno($ch)) {
            die("CURL send a error during perform operation: ".curl_error($ch));
        } else {
            curl_close($ch);
        }
    
        return $nvpResArray;
    }
    
    public function deformatNVP($nvpstr){
        $intial=0;
        $nvpArray = array();
    
        while(strlen($nvpstr)){
            //postion of Key
            $keypos = strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
    
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr,$intial,$keypos);
            $valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
         }
        return $nvpArray;
    }
    
    public function formAutorization($auth_token,$auth_signature,$auth_timestamp){
        $authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
        return $authString;
    }
    
    public function paypalCall($params){
		$recurringStr = (array_key_exists("recurring",$params) && $params['recurring'] == 'Y')?'&RECURRING=Y':'';
        $nvpstr = "&PAYMENTACTION=".$params['paymentAction']."&AMT=".$params['amount']."&CREDITCARDTYPE=".$params['creditCardType']."&ACCT=".$params['creditCardNumber']."&EXPDATE=".$params['expMonth'].$params['expYear']."&CVV2=".$params['cvv']."&FIRSTNAME=".$params['firstName']."&LASTNAME=".$params['lastName']."&CITY=".$params['city']."&ZIP=".$params['zip']."&COUNTRYCODE=".$params['countryCode']."&CURRENCYCODE=".$params['currencyCode'].$recurringStr;

        $resArray = $this->hashCall("DoDirectPayment",$nvpstr);
    
        return $resArray;
    }
}
?>
