<?php include("config.php"); ?>   
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<?php
include("includes/top_script.php"); 
include("includes/use_fancy_box.php");  
?>
</head>
<!-- END HEAD -->

<!-- BEGIN BODY -->
<body>
<?php include("includes/head_bar_menu.php");?>
<div class="wrapper">
	<div class="content">
		

<?php	
   	$warehouse_id= base64_decode($_GET['shp']);
	$xplode_warehouse_id= explode(",",$warehouse_id);
	
	$weight =0;
	$Lbs=0;
	for($i=0;$i<count($xplode_warehouse_id);$i++)
	{
		$getweight= mysql_fetch_assoc(mysql_query("select id,weight,length,width,height from shopgt_shipping where id='".$xplode_warehouse_id[$i]."'"));		
		$weight=$weight+$getweight['weight'];
		$Lbs = $Lbs+$getweight['length']*$getweight['width']*$getweight['height']/166;
	}
		
	$user_deta= mysql_fetch_assoc(mysql_query("select * from shopgt_reg_user where user_id='".$_SESSION['user_id']."'"));
	$country = $user_deta['country'];
	$postal = $user_deta['postal_code'];
	$num_foramt= $Lbs;

    $ship= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $weight between range_from and range_to"));

	if($weight > $Lbs)
    { 
		if($weight<= 5)
		{
		$ship_rate2= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $weight between range_from and range_to"));
		$ship_rate= $ship_rate2['rate'];
		}
		else if($weight >=100)
		{			
			$weights=99;
			$ship_rate1= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $weights between range_from and range_to"));
			$rt= $ship_rate1['rate'];
			$ship_rates=  $weight*$rt;
			$ship_rate= number_format($ship_rates, 2); 
		}
		 else {
			 
		$ship_rate1= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $weight between range_from and range_to"));
		$rt= $ship_rate1['rate'];
		$ship_rates=  $weight*$rt;
		$ship_rate= number_format($ship_rates, 2);
		}
	}
    else
    {
		if($num_foramt<= 5)
		{
			$ship_rate2= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $num_foramt between range_from and range_to"));
			$ship_rate= $ship_rate2['rate'];
		}
		else if($num_foramt>=100)
		{
			$weights=99;
			$ship_rate1= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $weights between range_from and range_to"));
			$rt= $ship_rate1['rate'];
			$ship_rates=  $Lbs*$rt;
			$ship_rate= number_format($ship_rates, 2); 
		}
		else
			{

				$ship_rate1= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping_rates where value='".$country."' and $num_foramt between range_from and range_to"));

				$rt= $ship_rate1['rate'];
				$ship_rates=  $Lbs*$rt;
				$ship_rate= number_format($ship_rates, 2);

			}
    }

		
		


	if(!empty($_REQUEST['option']))
	{
		$filename=$_REQUEST['option'].".php";
		if (file_exists($filename)) {
			include($_REQUEST['option'].".php");
		} else {
		   include("page_not_found.php");
		}
	}


   $API_AccountId = ''; 
   $API_AccountId = '1af6818d0c31282f7d24d2bd7128e5c5';   
   $shipAPIClass = 'shiprateapi/ShipRateAPI.inc';  
   
   if (! file_exists($shipAPIClass)) die("Unable to locate ShipAPI class file [$shipAPIClass]");
   include($shipAPIClass);

   $shipAPI = new ShipRateAPI($API_AccountId);
   $shipAPI->setSecureComm(false);
   $shipAPI->useCurl(false);
   
   $detailLevel = 2;
   $shipAPI->setDetailLevel($detailLevel);
   $showErrors = true;
   
   $destCountryCode = $country;
   $destPostalCode  = $postal;
   $residential     = true;
   $shipAPI->setDestinationAddress($destCountryCode, $destPostalCode, '', $residential);

   $items = array();  
   $item = array();
   $item["CalcMethod"] = "C";
   $item["refCode"] = "test_item_1";
   $item["quantity"] = 1;
   $item["packMethod"] = 'T';   
   $item["weight"] = $weight;  
   $item["weightUOM"] = "LBS"; 
   $item["length"] = $length;
   $item["width"] =  $width;
   $item["height"] = $height;
   $item["dimUOM"] = "IN";    
   $item["value"] =  $amount;  
   
   $item["odServices"] = "USPMM, USPEXP";    
   $items[] = $item;
   
   $item = array();
   $item["refCode"] = "test_item_2";
   $item["CalcMethod"] = "C";
   $item["quantity"] = 1;
   $item["packMethod"] = 'T'; 
   
 
   $item["weight"] = $weight;
   $item["weightUOM"] = "LBS";
   $item["length"] = $length;
   $item["width"] =  $width;
   $item["height"] = $height;
   $item["dimUOM"] = "IN";    
   $item["value"] =  $amount; 
   $item["odServices"] = "USPMM";  
   
   $item = array();
   $item["CalcMethod"] = "F";
   $item["refCode"] = "test_item_3";
   $item["FeeType"] = "F"; 
   $item["quantity"] = 2;
   $item["fixedAmt_1"] = 3.00;  
   $item["fixedAmt_2"] = 2.00; 
   $item["fixedFeeCode"] = "";  
  
   $item = array();
   $item["CalcMethod"] = "N"; 
   $item["refCode"] = "test_item_3";
   $item["quantity"] = 1;
  
   foreach ($items AS $val){
         
     if ($val["CalcMethod"] == "C"){
           $shipAPI->addItemCalc($val["refCode"], $val["quantity"], $val["weight"], $val['weightUOM'], $val["length"], $val["width"], $val["height"], $val["dimUOM"],  $val["value"], $val["packMethod"]);
           
           if (isset($val["originCode"]))          $shipAPI->addItemOriginCode($val["originCode"]);
           if (isset($val["odServices"]))          $shipAPI->addItemOnDemandServices($val["odServices"]);
           if (isset($val["suppHandlingCode"]))    $shipAPI->addItemSuppHandlingCode($val["suppHandlingCode"]);
           if (isset($val["suppHandlingFee"]))     $shipAPI->addItemHandlingFee($val["suppHandlingFee"]);
           if (isset($val["specCarrierSvcs"]))     $shipAPI->addItemSpecialCarrierServices($val["specCarrierSvcs"]);
   
     } elseif ($val["CalcMethod"] == "F"){
           $shipAPI->addItemFixed($val["refCode"], $val["quantity"], $val["FeeType"], $val["fixedAmt_1"], $val["fixedAmt_2"], $val["fixedFeeCode"]);    

     } elseif ($val["CalcMethod"] == "N"){
           $shipAPI->addItemFree($val["refCode"], $val["quantity"]);    
     }
   }
 
   
   $ok = $shipAPI->GetItemShipRateSS( $shipRates );
   if ($ok) {
    
      displayRates($shipRates, $detailLevel);
   } else {
      echo 'Sorry, but we were unable to determine shipping rates.';
      
      if ($showErrors == true){
         $str =  "<P><table border=1><tr><th>Code</th><th>Message</th><th>Severity</th>";
         foreach ($shipRates["ErrorList"] AS $k => $v){
            $code     = $v["Code"];      
            $message  = $v["Message"];      
            $severity = $v["Severity"];  
            $str .= "<tr><td>$code</td><td>$message</td><td>$severity</td></tr>";    
         } 
         $str .=  "</table>";
         echo $str;
      } 
   }
   
   
   
   function displayRates($shipRates, $detailLevel) {
      
      $str = "";           
         echo "<p><form>" . genSelectHTML($shipRates) . '</form>';
    }
           
   
   function genSelectHTML($shipRates, $fieldName='shiprate', $valueFormat='C,R', $displayFormat='N - F', $size=4, $class='', $noRatesVal='0', $noRatesMsg='Unable to determine') {
   
    $warehouse_id = base64_decode($_GET['shp']);	
	$xplode_warehouse_id= explode(",",$warehouse_id);
	
	$weight =0;
	for($i=0;$i<count($xplode_warehouse_id);$i++)
	{
		$getweight= mysql_fetch_assoc(mysql_query("select id,weight from shopgt_shipping where id='".$xplode_warehouse_id[$i]."'"));
		
		$weight=$weight+$getweight['weight'];
	}
	

   
         echo "<h3>Please choose from the following options to calculate shipping:</h3><br/>";
      
      $c=sizeof($shipRates['ShipRate']);

      $n=0;    // the number of valid rates
	 
	
      for($i=0; $i < $c; ++$i) {
         $valid = $shipRates['ShipRate'][$i]['Valid'];
         if (strcmp($valid, 'true') !== 0) continue;
         ++$n;
         
         $vals['R'] = $shipRates['ShipRate'][$i]['Rate'];
         $vals['F'] = $shipRates['ShipRate'][$i]['Rate'] > 0 ? ('$' . number_format($shipRates['ShipRate'][$i]['Rate'],2) ) : 'Free';
         $vals['S'] = $shipRates['ShipRate'][$i]['ServiceCode'];
         $vals['C'] = $shipRates['ShipRate'][$i]['CarrierCode'];
         $vals['N'] = $shipRates['ShipRate'][$i]['ServiceName'];

         // Iterate over the format strings and substitute the tags with the appropriate values
         $value = '';
         for($s=0, $l=strlen($valueFormat); $s < $l; ++$s) {
            $char = $valueFormat{$s};
            $value .= urlencode( isset($vals[$char]) ? $vals[$char] : $char );
         }
		 
         $display = '';
         for($s=0, $l=strlen($displayFormat); $s < $l; ++$s) {
            $char = $displayFormat{$s};
            $display .= isset($vals[$char]) ? $vals[$char] : $char;
         }
         
         $selected = $n==1 ? ' SELECTED' : '';
       
		 $exp = explode("-",$value);	
		
		 echo "<form name='paypal_form' method='post' action='shipall_buy.php'>
		       <div style='width:800px;padding:10px;border-bottom:1px dotted #ccc;background:#F1F1F1;margin:5px 0;'>
			  <div>
			 
			  <div style='width:250px;float:left;'>".$vals['N']."</div>
			  <div style='width:150px;float:left;' style='background:yellow;color:red;'>".$vals['C'].
			  "</div>
			  <div style='width:70px;float:left;'>$".$vals['R'].
			  "</div>	
				<div style='float:left;width:100px;'></div>			  
			  </div>";
					$cont = '
					<input type="hidden" value="_xclick" name="cmd">
					<input type="hidden" value="service@shopgt.com" name="business">
					<input type="hidden" value="Shopgt Order '.$_SESSION['user_id'].'" name="item_name">
					<input type="hidden" value="'.$_SESSION['user_id'].'" name="item_number">
					<input type="hidden" value="'.$vals['R'].'" name="amount">
					<input type="hidden" value="'.base64_encode($warehouse_id).'" name="link">
					<input type="hidden" value="'.$price.'" name="price">
					<input type="hidden" value="0" name="no_shipping">
					<input type="hidden" value="USD" name="currency_code">
					<input type="hidden" value="1" name="no_note">
					<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
					<input type="radio" name="others[]" value="'.$weight.",".$vals['N'].'" style="margin-left:113px;"> 
					<input type="submit" name="submit_queto" value="Continue" class="cont_btn"/>
					</form>';
					echo $cont;
			  
			  echo "<div style='clear:both;'></div>
			   
			  </div>";
		 
      }
	   
   }
   if($ship['country']!="")
	{
	$ab="Amerijet";
	echo "<form method='post' action='shipall_buy.php'><div style='width:800px;padding:10px;border-bottom:1px dotted #ccc;background:#F1F1F1;margin:5px 0;'>
			  <div>
			  <!--div style='width:50px;float:left;'><input type='radio' id='service' name='service' value='".$vals['N']."-".$vals['C']."-".$vals['R']."-".$_GET['id']."'></div-->
			  <div style='width:250px;float:left;'>Amerijet</div>
			  <div style='width:150px;float:left;' style='background:yellow;color:red;'>Amerijet</div>
			  <div style='width:70px;float:left;'>$".$ship_rate.
			  "</div>
			  <div style='float:left;width:100px;'></div>
			  </div>";
					$cont = '
					<input type="hidden" value="_xclick" name="cmd">
					<input type="hidden" value="service@shopgt.com" name="business">
					<input type="hidden" value="Shopgt Order '.$_SESSION['user_id'].'" name="item_name">
					<input type="hidden" value="'.$_SESSION['user_id'].'" name="item_number">
					<input type="hidden" value="'.$ship_rate.'" name="amount">
					<input type="hidden" value="'.$impl.'" name="custom">
					<input type="hidden" value="'.base64_encode($warehouse_id).'" name="link">
					<input type="hidden" value="'.$price.'" name="price">
					
					<input type="hidden" value="0" name="no_shipping">
					<input type="hidden" name="return" value="http://anaadit.net/shopgt/suceess_queto&tid='.$_GET['id'].'&service='.$vals['N'].'">
					<input type="hidden" name="cancel_return" value="http://anaadit.net/shopgt/suceess_queto&tid='.$_GET['id'].'&service='.$vals['N'].'">
					<input type="hidden" name="notify_url" value="http://anaadit.net/shopgt/suceess_queto&tid='.$_GET['id'].'&service='.$vals['N'].'">
					<input type="hidden" value="USD" name="currency_code">
					<input type="hidden" value="1" name="no_note">
					<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
					<input type="radio" name="ameri[]" value="'.$weight.",".$ab.'" style="margin-left:113px;"> 
					<input type="submit" name="submit_queto" value="Continue" class="cont_btn">
					</form>';
					echo $cont;
					echo "<div style='clear:both'></div>";
					}
					
			
 

?>

								
			  

   
		  <div class="clear"></div> 

    </div>
		<div class="clear"></div> 
        <!-- END CONTAINER -->
</div>
<div class="clear"></div>
	
	
<?php
include("includes/footer.php");
?>	 

</body>
<!-- END BODY -->
</html>