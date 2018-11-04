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
if(isset($_POST['submit_queto'])) {
	 
		if($_POST['others'])
		{
			$exp_chk= explode(",",$_POST['others'][0]);
		}
		 
		if($_POST['ameri'])
		{
			$exp_chk= explode(",",$_POST['ameri'][0]); 
		}
	 
		if($_POST['m_amt']!="")
		{ 
			$amtship=$_POST['m_amt'];
		} else {
			$amtship=$_POST['amount'];
		}
		
		$neitem = array ('user_id' =>$_POST['item_number'] ,'wrhouse_id' => $_POST['link'],'shipping_charge' =>$amtship ,'weight' => $exp_chk[0],'service'=>$exp_chk[1]); 
		session_start();
		$_SESSION['invc_item'] = $neitem;
 
	}
		
		
		$get_id= base64_decode($_SESSION['invc_item']['wrhouse_id']);
		$xpl_shp_id= explode(",",$get_id);
		for($i=0;$i < count($xpl_shp_id); $i++){
		$data = mysql_fetch_assoc(mysql_query("select * from shopgt_shipping where id = '".$xpl_shp_id[$i]."'"));
		$tarck_implode[]=$data['track_id'];
		$track_id=implode(",",$tarck_implode);
	}
		
		
$user_detail= mysql_fetch_assoc(mysql_query("select * from shopgt_reg_user where user_id='".$_SESSION['user_id']."'"));		
			
	echo "<h3 style='margin-left:200px'>Your shipping details.</h3><br/>";		
	echo "<div style='width:303px;float:left;text-align:right'>Box ID</div>
	<div style='width:20px;float:left;'>:</div>
	<div style='float:left'>".$user_detail['box_id']."</div>
	 <div style='clear:both;height:3px'></div>";
	echo "<div style='width:303px;float:left;text-align:right'>Name</div>
	<div style='width:20px;float:left;'>:</div>
	<div style='float:left'>".$user_detail['first_name']." ".$user_detail['last_name']."</div>
	<div style='clear:both;height:3px'></div>";
	echo "<div style='width:303px;float:left;text-align:right'>Shipping Address</div>
	<div style='width:20px;float:left;'>:</div>
	<div>".$user_detail['address'].",".$user_detail['city'].",".$user_detail['postal_code'].",".$user_detail['country']."</div><br/>";
	
	echo "<h3 style='margin-left:200px'>Your items details.</h3><br/>";

	echo "<div style='width:690px;padding:10px;border-bottom:1px dotted #ccc;background:#F1F1F1;margin:5px 0;float:left;margin-left:200px'>";
			  echo"
			  <div style='width:350px;float:left;'><b>Shipped Through</b></div>
			  <div style='width:150px;float:left;text-align:center'><b>Amount</b></div>
			  
			  <div style='clear:both;height:10px'></div>";
			  
		
			  
			  
			  echo "
			  <div style='width:350px;float:left;'>".$_SESSION['invc_item']['service']."</div>
			  <div style='width:150px;float:left;text-align:center'>$".$_SESSION['invc_item']['shipping_charge']."</div>
			 
			  <div style='clear:both;height:10px'></div>";

			 
			  
			echo"<div style='clear:both;border-top:1px solid black;height:7px'></div>"; 
			echo"<div style='float:left;width:402px;text-align:right'>Sub Total = </div>";
			echo"<div style='float:left'> $".$_SESSION['invc_item']['shipping_charge']."</div>";
			echo"<div style='clear:both;'></div>";
		    echo "</div>";
			echo "<div style='clear:both'></div>";
			 
			 echo "<a href='account'><div style='background: none repeat scroll 0 0 #FDB713;
    border: 0 none;
    border-radius: 4px;
    color: #FFFFFF;
    cursor: pointer;
    float: left;
    font-size: 16px;
    padding: 7px 15px;width:100px;float:left;margin-left:202px;margin-top:19px;text-align:center;'>Back</div></a>";
	echo "<form name='credit_check' method='post' action='check.php'>
 <div style='width:170px;padding:10px;margin:5px 0;float:left'>
			  <div>
			  <div style='width:250px;float:left;'>".$vals['N']."</div>
			  <div style='width:300px;float:left;' style='background:yellow;color:red;'>".$vals['C'].
			  "</div>
			  <div style='width:150px;float:left;'></div>
			   
			  </div>";
					$cont = '
					<input type="hidden" value="_xclick" name="cmd">
					<input type="hidden" value="service@shopgt.com" name="business">
					<input type="hidden" value="Shopgt Order '.$_SESSION['user_id'].'" name="item_name">
					<input type="hidden" value="'.$_SESSION['user_id'].'" name="item_number">
					<input type="hidden" value="'.$_SESSION['invc_item']['shipping_charge'].'" name="amount">
					<input type="hidden" value="'.$track_id.'" name="ship_id">
					<input type="hidden" name="return" value="'.$_SESSION['invc_item']['service'].'">
					<input type="hidden" name="shipby" value="'.$_SESSION['invc_item']['service'].'">
					<input type="hidden" name="itemamount" value="'.$shp_amount.'">					
					<input type="hidden" value="USD" name="currency_code">
					<input type="hidden" value="1" name="no_note">
					<input type="submit" name="submit" value="Ship Now" style="margin-left:20px;margin-top:4px;background: none repeat scroll 0 0 #FDB713;
    border: 0 none;
    border-radius: 4px;
    color: #FFFFFF;
    cursor: pointer;
    float: left;
    font-size: 16px;
    padding: 7px 15px;width:140px;float:left"  >
					
					</form>';
					echo $cont;
				
			  echo"</div>";
	if($_SESSION['invc_item']){
	echo "<form name='credit_check' method='post' action='checkout.php'>
		<div style='width:300px;padding:10px;margin:5px 0;float:left'>
			  <div>
			  <div style='width:250px;float:left;'>".$vals['N']."</div>
			  <div style='width:300px;float:left;' style='background:yellow;color:red;'>".$vals['C'].
			  "</div>
			  <div style='width:150px;float:left;'></div>
			   
			  </div>";
					$cont = '
					<input type="hidden" value="_xclick" name="cmd">
					<input type="hidden" value="service@shopgt.com" name="business">
					<input type="hidden" value="Shopgt Order '.$_SESSION['user_id'].'" name="item_name">
					<input type="hidden" value="'.$_SESSION['user_id'].'" name="item_number">
					
					<input type="hidden" value="'.$_SESSION['invc_item']['shipping_charge'].'" name="amount">
					<input type="hidden" value="'.$track_id.'" name="ship_id">
					<input type="hidden" name="return" value="'.$_SESSION['invc_item']['service'].'">
					<input type="hidden" name="shipby" value="'.$_SESSION['invc_item']['service'].'">
					<input type="hidden" value="USD" name="currency_code">
					<input type="hidden" value="1" name="no_note">
					<input type="submit" name="submit" value="Freight Collect" style="margin-left:20px;margin-top:4px;background: none repeat scroll 0 0 #FDB713;
    border: 0 none;
    border-radius: 4px;
    color: #FFFFFF;
    cursor: pointer;
    float: left;
    font-size: 16px;
    padding: 7px 15px;width:140px;float:left"  >
					
					</form>';
					}
					echo $cont;
					echo"<div style='clear:both;height:10px'></div>";
					echo "</div>";
					
			
 

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