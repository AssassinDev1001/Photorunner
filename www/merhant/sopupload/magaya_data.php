<?php
include("../config.php");

	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$fp = fopen('php://input', 'rb');
		file_put_contents('test.xml', $fp);

		$url = 'http://anaadit.net/shopgt/magaya/test.xml';
		
		if(!empty($url)){	
		
		$sxml = simplexml_load_file($url);
		$decodexml = json_encode($sxml);
		$phpArrays = json_decode($decodexml, true);		
		
		$lp= count($phpArrays['WarehouseReceipt']);
		// echo "<pre>";
		// print_r($phpArrays);
		// echo "</pre>";exit;
		
		for($i=0;$i<$lp;$i++)
			{		
				$box= $phpArrays['WarehouseReceipt'][$i]['Items']['Item']['PartNumber'];
				$boxID= trim($box);
				if(!empty($boxID))
				{
				$userdata= mysql_fetch_assoc(mysql_query("select * from shopgt_reg_user where box_id='".$boxID."'"));
				if($userdata) {
					
				$sels=mysql_query("select * from shopgt_shipping where receipt='".$phpArrays['WarehouseReceipt'][$i]['Number']."'"); 
				if(mysql_num_rows($sels)==0){
					
				if($phpArrays['WarehouseReceipt'][$i]['Items']['Item']['TrackingNumber']!="")
				{
					$Trackid=$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['TrackingNumber'];
				}
				else
				{
					$Trackid=$phpArrays['WarehouseReceipt'][$i]['Number'];
				}
				$address=$userdata['address'].",".$userdata['city'].",".$userdata['postal_code'].",".$userdata['country'].",".$userdata['phone'];
				
				$ins=mysql_query("insert into shopgt_shipping set box_id = '".$userdata['box_id']."', user_id = '".$userdata['user_id']."',item_value = '".$_POST['item_value']."',carrier_track_id = '".$phpArrays['WarehouseReceipt'][$i]['CarrierTrackingNumber']."', service = '".$phpArrays['WarehouseReceipt'][$i]['ShipperName']."',receipt='".$phpArrays['WarehouseReceipt'][$i]['Number']."', shipper_address = '".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['Street']."', shipper_phone = '".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['ContactPhone']."',length = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Length']."',width = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Width']."',height = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Height']."',weight = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Weight']."',address = '".$address."',city='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['City']."',state='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['State']."',zip='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['ZipCode']."',country='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['Country']."',track_id = '".$Trackid."' ,carrier='".$phpArrays['WarehouseReceipt'][$i]['CarrierName']."',description='".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Description']."',create_date = now()");
					
					
				}
				else
				{
					
				if($phpArrays['WarehouseReceipt'][$i]['Items']['Item']['TrackingNumber']!="")
				{
					$Trackid=$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['TrackingNumber'];
				}
				else
				{
					$Trackid=$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['WarehouseReceiptNumber'];
				}
				$address=$userdata['address'].",".$userdata['city'].",".$userdata['postal_code'].",".$userdata['country'].",".$userdata['phone'];
				$ins=mysql_query("update shopgt_shipping set box_id = '".$userdata['box_id']."', user_id = '".$userdata['user_id']."',item_value = '".$_POST['item_value']."',carrier_track_id = '".$phpArrays['WarehouseReceipt'][$i]['CarrierTrackingNumber']."', service = '".$phpArrays['WarehouseReceipt'][$i]['ShipperName']."',receipt='".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['WarehouseReceiptNumber']."', shipper_address = '".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['Street']."', shipper_phone = '".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['ContactPhone']."',length = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Length']."',width = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Width']."',height = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Height']."',weight = '".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Weight']."',address = '".$address."',city='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['City']."',state='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['State']."',zip='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['ZipCode']."',country='".$phpArrays['WarehouseReceipt'][$i]['Shipper']['Address']['Country']."',track_id = '".$Trackid."' ,carrier='".$phpArrays['WarehouseReceipt'][$i]['CarrierName']."',description='".$phpArrays['WarehouseReceipt'][$i]['Items']['Item']['Description']."' where receipt='".$phpArrays['WarehouseReceipt'][$i]['Number']."' and box_id='".$userdata['box_id']."'");
					
				}
				}				
				}
			}
		}
	}
			
?>
