<?php     
include("include/config.php");
include("include/security_admin.php");

 $user= mysql_fetch_assoc(mysql_query("select * from shopgt_reg_user where user_id='". $_GET['uid']."'")); 
 $u_adrd= $user['address'].",".$user['city'].",".$user['postal_code'].",".$user['country']; 
 $addisplay=ucwords($user['first_name']." ".$user['last_name'])." <br>".$user['box_id']."<br>".$user['address'].",".$user['city'].",".$user['postal_code'].",".$user['country'];
 
	$query= mysql_query("select * from ship_order where user_id='". $_REQUEST['uid']."' and order_id='".$_GET['iid']."'");
	
	$amtship=0;
	$weight=0;

	while($getres= mysql_fetch_assoc($query))
	{		
		$shipppinga= mysql_fetch_assoc(mysql_query("select * from shopgt_shipping where user_id='". $_REQUEST['uid']."' and track_id='".$getres['track_id']."'"));
		$amtship= $getres['amount'];
		$shippping[]= $shipppinga['id'];
		$service[]= $getres['shipped_by'];
		$weight=$weight+$shipppinga['weight'];
	}
	
	$shipingid= implode(",",$shippping);
	$servce= implode(",",$service);
	$neitem = array ('user_id' =>$_REQUEST['uid'] ,'wrhouse_id' => $shipingid,'shipping_charge' =>$amtship ,'weight' => $weight,'service'=>$servce); 
	session_start();
	$_SESSION['invc_item'] = $neitem;
 
	// echo "<pre>";
	// print_r($_SESSION['invc_item']);
	// echo "</pre>";exit;
 
	if(isset($_POST['submit']))
    {
	
	  $checkcountry= mysql_fetch_assoc(mysql_query("select user_id,country from shopgt_reg_user where user_id='".$_REQUEST['uid']."'")); 	
	  $courty=$checkcountry['country'];
	  $category= $_POST['category'];
	  if($_POST['locharge']!="")
	  {
		 $pric_total= $_POST['locharge'];
	  }
	  else {
	  $repacking= $_POST['repacking'];
	  $tax= $_POST['tax'];
	  $price= $_POST['price'];
	  $insurance= $_POST['insurance'];
	  $country= $_POST['country'];
	  $minpirce=7.50;
	  if($category!="" && $price!="" && $country!="")
	    {
			$msgs="1";
			if($category=='clothing')
			{
			  $total_val= $price*10/100;
			  $pric_total=$total_val;
			  if($pric_total<$minpirce)
				{
					$pric_total= $minpirce; 
				} else {
					$pric_total=$total_val;
			    }
			  
			}
			if($category=='electronics')
			{			  
			  $total_val= $price*15/100; 
			  $pric_total=$total_val;
			  if($pric_total<$minpirce)
			  {
				$pric_total= $minpirce; 
			  } else {
				  $pric_total=$total_val;
			    }
			}
		  if($category=='mechanical')
			{
			 $total_val= $price*15/100;
			 $pric_total=$total_val;
			 if($pric_total<$minpirce)
			  {
				$pric_total= $minpirce; 
			  } else {
				  $pric_total=$total_val;
			    }
			}
			if($category=='general cargo')
			{
				$total_val= $price*10/100;
				$pric_total=$total_val;
				if($pric_total<$minpirce)
				{	$pric_total= $minpirce;	} else {
					$pric_total=$total_val;
			    }
			}
	    } 
	    else
		{
			$msg="All fields are required !";
		}
	  }

    }
	
	if(isset($_POST['submit_invoice']))
	{
		
		$shp_amount= $_POST['item_price'];
		$count=$_POST['amount'];
		$shp_shipping= $_SESSION['invc_item']['shipping_charge'];
		$shp_tax=$_POST['tax'];
		$shp_repack=$_POST['repacking_fee'];
		$localcharge=$_POST['local_chrg'];
		$categories=$_POST['categories'];
		$weight= $_SESSION['invc_item']['weight'];
		$service= $_SESSION['invc_item']['service'];
		$rec_id= $_SESSION['invc_item']['wrhouse_id'];
		$invoce_n=$_POST['inv_no'];
		if($_GET['qote']!="")
		{
			$users=$_SESSION['wr_id'];
		} else {
			$users=$_SESSION['invc_item']['user_id'];
		}
	if($_SESSION['invc_item']){
		$inv = "INV"."".$invoce_n;
		 mysql_query("insert into shopgt_invoice set user_id='".$users."',invoice='".$inv."',amount='".$shp_amount."',item='".$categories."',total_amount='".$count."',shipping='".$shp_shipping."',tax='".$shp_tax."',repacking='".$shp_repack."',weight='".$weight."',record_id='".$rec_id."',local_charge='".$localcharge."',insurance_fee='".$_POST['insurancefee']."',service='".$service."',qoteid='".$_GET['qote']."',ship_orderid='".$_REQUEST['iid']."', order_date=now()");
		$check_rec= mysql_insert_id();
		if($check_rec)
		{
			if($_GET['qote']!="")
			{
			
			}
			else {
			foreach($xpld_id as $wrid)
			{
				if($wrid!="")
				{
					mysql_query("update shopgt_shipping set shipping_status='Shipped' where id='".$wrid."'");
				}
			}
			}
			mysql_query("update shopgt_box set b_id='".$invoce_n."' where id='3'");
			
		$sel_invc= mysql_fetch_assoc(mysql_query("select * from shopgt_invoice where id='".$check_rec."'"));
		$invc_id= $sel_invc['id'];
		
		$userem= mysql_fetch_assoc(mysql_query("select user_id,user_email from shopgt_reg_user where user_id='".$sel_invc['user_id']."'"));
		
		$to=$userem['user_email'];
		$from="service@shopgt.com";
		$subject="Invoice Detail";
		$mailcontent="<div style='width:600px;text-align:justify'>
			<p><img src='http://www.shopgt.com/images/logo2.png' alt='logo'></p>
			<p style='color:#000;'><b>Welcome To Shopgt.com</b></p>
			<p style='margin-bottom: 0in;color:#000'>
				Dear,user123</p>
				<p style='margin-bottom: 0in;color:#000'>
				Thank you for placing an order with us. Your Invoice has been created successfully.</p>
			<p style='margin-bottom: 0in'>
				Your Invoice details are given below.:-</p>
			<p style='margin-bottom: 0in'>
				<b>Invoice ID: </b>: {$inv}<br/>
				<b>Amount</b>:${$invc_id['total_amount']}<br/>
				<b>Your Address</b>: {$u_adrd}<br/>
				</p>
				<p style='margin-bottom: 0in;color:#000'>
				Click on the link for payment <a href='http://anaadit.net/shopgt/checkoutt.php?uid=".base64_encode($invc_id)."'>Pay Now</a></p>
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
				$getdata= mysql_fetch_assoc(mysql_query("select * from gcm_users where email='".$userem['user_email']."'"));	
				$registatoin_ids= $getdata['gcm_regid'];
				define( 'API_ACCESS_KEY', 'AIzaSyCMY3hRVT2CY8NUFSOOZH_CbLWAsK-D06U' );
					
				$registrationIds = array( $registatoin_ids );
				$message = array("price" => $mailcontent);
				$fields = array('registration_ids'=> $registrationIds, 'data'=> $message);
 
				$headers = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);	
					
				unset($_SESSION['invc_item']);
				echo"<script>alert('Invoice Created Successfully.');</script>";			
				header('Refresh:0; url=manage_invoice.php');
			
		}   else {
			
			echo"<script>alert('Error occurred. Please try again !');</script>";
		}
	} 
	     else { echo"<script>alert('Error occurred. Please try again !');</script>"; }
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("include/menu_bar_fancy_box.php");?>
<?php include('include/head_default.php'); ?>

<!--Add more button-->
<!--End more button-->
<title>Final Invoices</title>
</head>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
</style>
<script lang='javascript'> 
 $(document).ready(function(){
	    $('#printPage').click(function(){     

		 var printContents = document.getElementById("wrdiv").innerHTML;
		 var originalContents = document.body.innerHTML;
		 document.body.innerHTML = printContents;
		 window.print();
		 document.body.innerHTML = originalContents;
		});
 });								
</script>
<script>
function maualadd()
{
	document.getElementById("notmaula").style.display='none';
	document.getElementById("maula").style.display='block';
}
</script> 
<body class="page-header-fixed">  	
<?php include('include/header_admin.php'); ?>
        
        <div class="row" style="margin-top:25px">
			
				<div class="col-md-12">
					<!-- BEGIN VALIDATION STATES-->
					<div class="portlet box green">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-reorder"></i>Final Invoice
							</div>
							
						</div>
						<div class="portlet-body form">									
							<?php 
					if(isset($_POST['submit']))
					{
						$num_qote= mysql_fetch_assoc(mysql_query("select b_id from shopgt_box where id='3'"));	
						$num1_qote= $num_qote['b_id'];
						$num2_qote= $num1_qote+1;
						
					echo "<span id='wrdiv'>";					
					echo "<div style='clear:both;height:26px;'></div>
	<div class='main-quote-div' style='margin-left:13px;'>";
    echo"<div id='heading-div'></div>
	<div class='quote-div-left'>
			<div class='quote-div-left-inner'><a>ShopGT.com</a></div>			
		</div>
		<div style='float:left;width:88px;'>&nbsp;</div>
		<div class='quote-div-left'>
			<div class='quote-div-right-inner'>Invoice<br/><br/>
			<p style='font-size:14px;'>Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".date("F d, Y")."<br/>
			<span style='width:272px;float:left;'>Invoice No.</span><span style='width:126px;float:left;'>INV".$num2_qote."</span>
			</p>
			</div>
		</div>
		<div style='clear:both;height:10px;'></div>";
					echo"<div class='quote-div-left-customer-main'>
			<div class='quote-div-left-customer-inner'>
			 <b>Customer</b>
			</div>
			<div id='customrname'><p>".$addisplay."</p></div>			
		</div>
		<div style='float:left;width:126px;'>&nbsp;</div>
		<div class='quote-div-left-customer-main'>
		    <div class='quote-div-left-customer-inner'>
			 <b>Deposit/ Payment</b>
			</div>
		</div>
		<div style='clear:both;height:10px;'></div>			  
		<div id='descption12'>
			<b>Item Description</b>
		</div>
		<div id='ltotal'>
			<b>Category</b>
		</div>
		<div style='clear:both;height:10px'></div>";
			  
			 $rcp_wr= $_SESSION['invc_item']['wrhouse_id'];
			 $xpd= explode(",",$rcp_wr);
 for($i=0;$i<count($xpd);$i++)
	{
		$grcpt= mysql_fetch_assoc(mysql_query("select id,track_id,receipt from shopgt_shipping where id='".$xpd[$i]."'"));	
			                            if($grcpt['receipt']=="")
										{ $tracck_id=$grcpt['track_id'];	}
										 else				{
										$tracck_id=$grcpt['receipt'];
										}
	
		 echo "<div class='itlink1'><p> Warehouse# ".$tracck_id."</p></div>
			   <div class='ipric'>".ucwords($category)."</div>
			   <div style='clear:both;'></div>";

			  }
			  
			  
			 
					$procurement_fee=$sum2+$sum3+$sum5;
					$fee1= $procurement_fee/10;
					if($fee1<10 && $fee1>0)
					{
					 $fee=10;	
					} else { $fee=$fee1; }
					if($repacking==""){
						$repacking=0;
					}
					if($tax==""){
						$tax=0;
					}
					$fees=number_format($fee,2,'.','');
					$grand_tota=$pric_total+$_SESSION['invc_item']['shipping_charge']+$repacking+$tax+$insurance;
					$grand_total=number_format($grand_tota,2,'.','');
			   echo"<div style='clear:both;border-top:1px dotted #E2DEDE;height:10px'></div>
			   <div class='quote-div-left-inst-main'>
			<div class='quote-div-left-customer-inner'>
			 <b>Special Notes and Instructions</b>
			</div>
			<div id='customrname'>
			 <p>Once signed, please Fax, mail or e-mail it to the provided address.</p>
			</div>			
		</div>
		<div style='float:left;width:26px;'>&nbsp;</div>
		<div class='quote-div-left-ra'>			
			<div class='ratheading'>Local charge</div>
			 <div class='ratprice'>$".number_format($pric_total,2,'.','')."</div>
			 
			  <div class='ratheading'>Shipping Cost(".$_SESSION['invc_item']['weight']."lbs)</div>
			 <div class='ratprice'>$".number_format($_SESSION['invc_item']['shipping_charge'],2,'.','')." </div>
			 
			 <div class='ratheading'>Repacking Fee</div>
			 <div class='ratprice'>$".number_format($repacking,2,'.','')."</div>
			 
			 <div class='ratheading'>Insurance Fee</div>
			 <div class='ratprice'>$".number_format($insurance,2,'.','')."</div>
			 
			 <div class='ratheading'>Handling charge</div>
			 <div class='ratprice'>$".number_format($tax,2,'.','')."</div>
			 
			 <div style='clear:both;border-top:1px solid black;'></div>
			 <div class='ratheading'><b>Grand Total in USD</b></div>
			 <div class='ratprice'><b>$".$grand_total."</b></div>";
			 if($courty=='GUY'){
				 $guyana=210*$grand_total;
			 echo"<div style='clear:both;border-top:1px solid black;'></div>
			 <div class='ratheading'><b>Grand Total in GYD</b></div>
			 <div class='ratprice'><b>$".number_format($guyana,2,'.','')."</b></div>";
			 }
			 
		echo"</div>
		<div style='clear:both;border-bottom:1px dotted #E2DEDE;height:15px;'></div>
		<div>
		 <p>Above information is not an invoice and only an estimate of services/goods described above.									
Payment will be collected in prior to provision of services/goods described in this quote.</p><br>
		 <p>Please confirm your acceptance of this quote by signing this document</div></p>
		 <p>
		 <span style='float:left;width:415px;text-align:right;'>Signature</span>
		 <span style='float:left;width:200px;'>--------------------------</span>
		 <br/>
		 <span style='float:left;width:415px;text-align:right;'>Print Name</span>
		 <span style='float:left;width:200px;'>--------------------------</span>
		  <br/>
		 <span style='float:left;width:415px;text-align:right;'>Date</span>
		 <span style='float:left;width:200px;'>--------------------------</span>
		 </p><br>
		 <p style='text-align:center;'>If you have any questions concerning this quote, contact [Name, Phone]									</p><br>
		 <p style='text-align:center;font-size:20px;font-weight:bold;'>Thank you for your business!</p><br>
		 <p style='text-align:center;font-size:20px;'>8557 NW 68th St &nbsp; 
		 Miami,FL 33166M<br/><br/>1-786-693-9049<br>info@shopgt.com
</p>
		 
        </div>
		</span>
		";
		
		if(!empty($_SESSION['invc_item']))
		{
			
echo "<form  method='post' action='' >";
					$cont = '
					<input type="hidden" value="Shopgt Order '.$_SESSION['u_id'].'" name="item_name">
					<input type="hidden" value="'.$_REQUEST['item_number'].'" name="item_number">
					<input type="hidden" value="'.$grand_total.'" name="amount">
					<input type="hidden" name="tax" value="'.$tax.'">
					<input type="hidden" name="item_price" value="'.$price.'">
					<input type="hidden" name="insurancefee" value="'.$insurance.'">					
					<input type="hidden" name="repacking_fee" value="'.$repacking.'">	
					<input type="hidden" name="local_chrg" value="'.$pric_total.'">
					<input type="hidden" name="categories" value="'.$category.'">
					<input type="hidden" name="inv_no" value="'.$num2_qote.'">
					<input type="submit" name="submit_invoice" value="Save Invoice" class="btn green" style="margin-left:400px;margin-top:5px;"> <!--a href="javascript:void(0);" id="printPage" class="btn green" style="margin-left:20px;">Print</a-->			
					</form>';
           echo $cont;							
		}
		
					}
					else {
					?>					
					<form method="post" name="myForm" id="myForm" action="">
                    	<table width="100%" border="0" cellspacing="0" cellpadding="10">
						  </div>
						  <div style="padding:5px;">
						 <div class="invoice-right">
						 <?php echo ucwords($user['first_name']." ".$user['last_name']);?></div>
						 <div style="clear:both"></div>
						 <div class="nvoice-right"><?php echo ucwords($user['user_email']); ?></div>
						 <div style="clear:both"></div>
						 <div class="nvoice-right"><?php echo ucwords($user['box_id']); ?></div>
						 </div>
						 <div style="clear:both;height:10px"></div>
						 <div style="float:left;margin-left:151px;font-family:Arial,Helvetica,sans-serif;font-size:14px;text-align:left"><b>Shipping Charges</b> : $<?php echo $_SESSION['invc_item']['shipping_charge']; ?> </div>
						<div style="clear:both;height:10px"></div>
						<div style=		"float:left;margin-left:151px;font-family:Arial,Helvetica,sans-serif;font-size:14px;text-align:left">
							<b>Select Category</b>
							<div style="clear:both;"></div>
								<select name="category" style="width:300px;" required>
									<option value="">--Select One--</option>
									<option value="electronics">Electronics</option>
									<option value="clothing">Clothing</option>
									<option value="mechanical">Mechanical</option>
									<option value="general cargo">General Cargo</option>
								</select> &nbsp;&nbsp;<a onclick="maualadd();" style="cursor:pointer;">Manualy add local charges Click Here</a>
						</div>
				<div style="clear:both;height:10px;"></div>
				<div style="float:left;margin-left:151px;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;text-align:left;display:none;" id="maula">
				<b>Enter Local Charge</b>
				<div style="clear:both;"></div>
				<input type="text" class="postal_box" id="locharge" name="locharge" style="width:300px;">
				</div>
				<span id="notmaula" style="display:block;">
				<div style="float:left;margin-left:151px;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;text-align:left">
				<b>Enter Value For Items</b>
				<div style="clear:both;"></div>
				<input type="text" class="postal_box" id="txtPostal" name="price" style="width:300px;" value="<?php echo $item_val['item_value']; ?>">
				</div>
				<div style="clear:both;height:10px;"></div>
				<div style="float:left;margin-left:151px;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;text-align:left">
				<b>Repacking Fee</b>
				<div style="clear:both;"></div>
				<input type="text" class="postal_box" id="repacking" name="repacking" style="width:300px;">
				</div>
				<div style="clear:both;height:10px;"></div>
				<div style="float:left;margin-left:151px;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;text-align:left">
				<b>Handling charge</b>
				<div style="clear:both;"></div>
				<input type="text" class="postal_box" id="tax" name="tax" style="width:300px;">
				</div>
				<div style="clear:both;height:10px;"></div>
				<div style="float:left;margin-left:151px;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;text-align:left">
				<b>Insurance Fee</b>
				<div style="clear:both;"></div>
				<input type="text" class="postal_box" id="insurance" name="insurance" style="width:300px;">
				</div>
				</span>
				<div style="clear:both;"></div>
				
				<div style="float:left;font-family: Arial,Helvetica,sans-serif;
 font-size:14px;margin-left:153px;margin-top:14px">
				
				<input type="hidden" name="country" value="<?php echo $user['country']; ?>">
				<input type="submit" name="submit" value="Submit" class="btn green">
				</div>
				
				
						
                        </table>
					<?php } ?>
					
					<div style='clear:both;height:10px'></div>					
			<!-- END PAGE CONTENT-->
			<div style="clear:both;height:25px"></div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
	</div>
	<div style="clear:both"></div>
</div>				
<?php include("include/footer.php");?>			
		
    
</body>
</html>
