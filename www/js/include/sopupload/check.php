<?php             
include("config.php");
include("includes/top_script.php"); 
include("includes/use_fancy_box.php");  
include("includes/head_bar_menu.php");

 $order_date=date("Y-m-d");
 $end=date('d');
 $sel= mysql_fetch_assoc(mysql_query("select * from shopgt_credit where user_id='".$_SESSION['user_id']."' order by  id desc"));
 
 $sel_user_detail= mysql_fetch_assoc(mysql_query("select * from shopgt_reg_user where user_id='".$_SESSION['user_id']."'"));
 $address= $sel_user_detail['address'].",".$sel_user_detail['city'].",".   $sel_user_detail['postal_code'].",".$sel_user_detail['country'];
 if(isset($_POST['amount'])) {
 if($_POST['amount']!="")
 {
	
 $order=rand(100,999999);
 $order_id='Shop'."".$order;
 
 if($_SESSION['affilate_user']!="") {
	 
	$date_end=date("Y-m-d", strtotime(date('m', strtotime('+1 month')).'/'.$end.'/'.date('Y').' 00:00:00'));
	$today=date('m-Y');
	mysql_query("insert into shopgt_affiliate_purchase set affiliate_usrid='".$_SESSION['affilate_user']."',amount='".$_REQUEST['amount']."',invoice='".$order_id."',date='".$order_date."',end_date='".$date_end."',status='1',comission_month='".$today."'");
	} 
	else
	{ 
	if($sel_user_detail['af_coupan']!="")
	{
		$date_end=date("Y-m-d", strtotime(date('m', strtotime('+1 month')).'/'.$end.'/'.date('Y').' 00:00:00'));
		$today=date('m-Y');
		 
		$get_coupan_afusrid= mysql_fetch_assoc(mysql_query("select * from shopgt_affiliate_user where coupan_id='".$sel_user_detail['af_coupan']."'"));
		if($get_coupan_afusrid){
		mysql_query("insert into shopgt_affiliate_purchase set affiliate_usrid='".$get_coupan_afusrid['affiliate_id']."',amount='".$_REQUEST['amount']."',invoice='".$order_id."',date='".$order_date."',end_date='".$date_end."',status='1',comission_month='".$today."'");
		}
	} 
	}
 
	//$update= mysql_query("insert into shopgt_credit set user_id='".$_REQUEST['item_number']."',first_name='".$sel_user_detail['first_name']."',last_name='".$sel_user_detail['last_name']."',email='".$sel_user_detail['user_email']."',amount='".$_REQUEST['amount']."',total_amount='".$t_amount."',credit_date='".$order_date."',type='Sale',box_id='".$sel_user_detail['box_id']."',txn_id='".$order_id."',order_id='".$order_id."',track_id='".$_REQUEST['ship_id']."',courier='".$_POST['return']."',shipped_by='".$_REQUEST['shipby']."',ordr_status='1'");
 
	$trac_id= $_REQUEST['ship_id'];
	$courir=$_POST['return'];
	$amt=$_REQUEST['amount'];
	$itemaount=$_REQUEST['itemamount'];
	$itemaount_exp= explode(",",$itemaount);
	$amout_exp= explode(",",$amt);
	$couri_exp= explode(",",$courir);
	$xplode= explode(",",$trac_id);
	$xplode_shpby= explode(",",$_REQUEST['shipby']);
	$xplode_carier= explode(",",$_POST['return']);
	

	for($i=0;$i<count($xplode);$i++)
	{
	mysql_query("update shopgt_shipping set shipping_status ='Pending for shipment',shipped_through='".$xplode_carier[$i]."' where track_id= '".$xplode[$i]."'");
	
	mysql_query("insert into ship_order set user_id='".$_REQUEST['item_number']."',amount='".$_REQUEST['amount']."',item_amount='".$itemaount_exp[$i]."',order_id='".$order_id."',track_id='".$xplode[$i]."',shipped_by='".$xplode_carier[$i]."',credit_date='".$order_date."'");
	}
	$msg="Your ship order has been successfully submitted.";

		
	$trac_id= $_REQUEST['ship_id'];
	$courir=$_POST['return'];
	$amt=$_REQUEST['amount'];
	$amout_exp= explode(",",$amt);
	$couri_exp= explode(",",$courir);
	$xplode1= explode(",",$trac_id);
		
		$to=$sel_user_detail['user_email'];
		$from="service@shopgt.com";
		$subject="Shipping Detail";
	$mailcontent="<div style='width:600px;text-align:justify'>
	<p><img src='http://www.shopgt.com/images/logo2.png' alt='logo'></p>
	<p style='color:#000;'><b>Welcome To Shopgt.com</b></p>
	<p style='margin-bottom: 0in;color:#000'>
	Dear user,</p>
	
	<p style='margin-bottom: 0in;color:#000'>
	Thanks for placing an order with us</p><p style='margin-bottom: 0in;color:#000'>
	Your order details are given below:</p>
	<p style='margin-bottom: 0in'>
	<b>Order ID </b>: {$order_id}<br/>
	<b>Box ID</b>: {$sel_user_detail['box_id']}<br/>
	<b>Credit Used</b>:$ {$_REQUEST['amount']}<br/>
	<b>Shipping Address</b> : {$address}<br/>
	<b>Order Date</b> : {$order_date}<br/>
	</p>	
	<p style='margin-bottom: 0in;color:#000'>
				<b>Item Detail.</b></p>
			<p style='margin-bottom: 0in'>
			<table border='1' width='auto'>
					<tr style='text-align:center'>
						<th>S No.</th>
						<th>Track ID</th>
						<th>Warehouse receipt number</th>
						<th>Shipped through</th>
					</tr>";
					for($i=0;$i<count($xplode1);$i++)
					{						
						$m= $k+1;
						$count1 = $count + $exp_amt[$k];
						$count=number_format($count1,2,'.','');
						
						$sel_data = mysql_fetch_assoc(mysql_query("select * from shopgt_shipping where track_id = '".$xplode1[$i]."'"));
						
						if($sel_data['receipt']=="")
						{
						$trk_id=$xplode1[$i];
						}
						else
						{
						$trk_id=$sel_data['receipt'];
						}
						
					  $mailcontent.="<tr style='text-align:center'>
					    <td style='font-family: Arial,Helvetica,sans-serif;font-size:13px'>{$m}</td>
					    <td style='font-family: Arial,Helvetica,sans-serif;font-size:13px'>{$trk_id}</td>
						<td style='font-family: Arial,Helvetica,sans-serif;font-size:13px;'>{$trk_id}</td>
						<td style='font-family: Arial,Helvetica,sans-serif;font-size:13px;'>{$couri_exp[$i]}</td>
						</tr>";
					}
					
				$mailcontent.="</table>
				</p>

	<p style='margin-bottom: 0in;color:#000'>
	&nbsp;</p>
	<p style='margin-bottom: 0in;color:#000'>
	Best Regards,</p>
	<p style='margin-bottom: 0in;color:#000'>
	Giovanni Fernandez</p>
	<p style='margin-bottom: 0in;color:#000'>
	Vice President Customer Service</p>
	<p style='margin-bottom: 0in;color:#000'>
	ShopGT.com</p>
	</div>";
					$Headers .= "MIME-Version: 1.0\n";
					$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
					$Headers .= "from:$from";

					mail($to,$subject,$mailcontent,$Headers);
					
					
		$to1="rajeev@anaad.net";
		$from1="service@shopgt.com";
		$subject1="New Order Recieved";
 $mailcontent1="<div style='width:600px;text-align:justify'>
<p><img src='http://www.shopgt.com/images/logo2.png' alt='logo'></p>
<p style='color:#000;'><b>Welcome To Shopgt.com</b></p>
<p style='margin-bottom: 0in;color:#000'>
	Dear Admin,</p>
<p style='margin-bottom: 0in;color:#000'>
	Follwing user placing an order with us.</p><p style='margin-bottom: 0in;color:#000'>
	 Order details are given below:</p>
	<p style='margin-bottom: 0in'>
	<b>Order ID </b>: {$order_id}<br/>
	<b>Box ID</b>: {$sel_user_detail['box_id']}<br/>
	<b>Credit Used</b>:$ {$_REQUEST['amount']}<br/>
	<b>Shipping Address</b> : {$address}<br/>
	<b>Order Date</b> : {$order_date}<br/>
	<b>Customer Name</b> : {$sel_user_detail['first_name']} {$sel_user_detail['last_name']}<br/>
	</p>
	
	<p style='margin-bottom: 0in;color:#000'>
			<b>Item Detail.</b></p>
			<p style='margin-bottom: 0in'>
			<table border='1' width='auto'>
					<tr style='text-align:center'>
						<th>S No.</th>
						<th>Track ID</th>
						<th>Warehouse receipt number</th>
						<th>Shipped through</th>
					</tr>";
					for($i=0;$i<count($xplode1);$i++)
					{						
						$m= $k+1;
						$count1 = $count + $exp_amt[$k];
						$count=number_format($count1,2,'.','');
						
						$sel_data = mysql_fetch_assoc(mysql_query("select * from shopgt_shipping where track_id = '".$xplode1[$i]."'"));
						
						if($sel_data['receipt']=="")
						{
						$trk_id=$xplode1[$i];
						}
						else
						{
						$trk_id=$sel_data['receipt'];
						}
						
					  $mailcontent1.="<tr style='text-align:center'>
					    <td style='font-family: Arial,Helvetica,sans-serif;font-size:13px'>{$m}</td>
					    <td style='font-family: Arial,Helvetica,sans-serif;font-size:13px'>{$trk_id}</td>
							<td style='font-family: Arial,Helvetica,sans-serif;font-size:13px;'>{$trk_id}</td>
							<td style='font-family: Arial,Helvetica,sans-serif;font-size:13px;'>{$couri_exp[$i]}</td>
						</tr>";
					}
					
				$mailcontent1.="</table>
				</p>

	<p style='margin-bottom: 0in;color:#000'>
	&nbsp;</p>
<p style='margin-bottom: 0in;color:#000'>
	Best Regards,</p>
<p style='margin-bottom: 0in;color:#000'>
	Giovanni Fernandez</p>
<p style='margin-bottom: 0in;color:#000'>
	Vice President Customer Service</p>
<p style='margin-bottom: 0in;color:#000'>
	ShopGT.com</p>
	</div>";
					$Headers .= "MIME-Version: 1.0\n";
					$Headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
					$Headers .= "from:$from";

					mail($to1,$subject1,$mailcontent1,$Headers);
					
					$getdata= mysql_fetch_assoc(mysql_query("select * from gcm_users where email='".$sel_user_detail['user_email']."'"));	
					$registatoin_ids= $getdata['gcm_regid'];
					include("includes/notification.php");
				
unset($_SESSION['item']);
unset($_SESSION['amjet']);

}
}
?> 
<div style="float:left;padding-left:30px;">
<div class="register">
			<div class="clear"></div>
			<div class="box">
			<div>Thanks <?php echo $sel_user_detail['first_name']." ".$sel_user_detail['last_name']; ?>, Your order has been submitted successfully.<br/></div>	
			<div style="clear:both;height:20px"></div>
		
			<div style="float:left;text-align:right;width:200px">Order ID:</div>
			<div style="float:left;width:200px;padding-left:10px"><?php echo $order_id; ?></div>
			<div style="clear:both;height:5px"></div>
			<div style="float:left;text-align:right;width:200px">Your Email:</div>
			<div style="float:left;width:200px;padding-left:10px"><?php echo $sel_user_detail['user_email']; ?></div>
			<div style="clear:both;height:5px"></div>
			<div style="float:left;text-align:right;width:200px">Amount:</div>
			<div style="float:left;width:200px;padding-left:10px">$<?php echo $_REQUEST['amount']; ?></div>
			<div style="clear:both;height:5px"></div>
			
			<div style="clear:both;height:5px"></div>
			<div style="float:left;text-align:right;width:200px"> Date:</div>
			<div style="float:left;width:200px;padding-left:10px"><?php echo $order_date; ?></div>
				<div style="clear:both;height:10px"></div>
			
			</div>
			</div>
</div>
<div style="clear:both;height:200px;"></div>
<?php
include("includes/footer.php");
?>