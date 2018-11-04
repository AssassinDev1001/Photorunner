<?php  include('include/config.php');

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 10;
$startpoint = ($page * $limit) - $limit;					
$conditions = " WHERE buyer = '".$_SESSION['guast']['email']."' ORDER by id DESC ";			
$statement = "pr_payments" . $conditions;						
$conditions .= " LIMIT {$startpoint} , {$limit}";	
$purchase = $common->getpagirecords('pr_payments','*',$conditions);


?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<script>
		function reloadPage()
		{
			setInterval(function(){
				location.href = "<?php echo APP_URL; ?>success.php";
			},2000);
		}
	</script>

<?php
if(isset($_POST['printfile']))
{
	$common->printfile($_POST);
	$conditions = array('id'=>$_POST['print']);
	$print = $common->getrecord('pr_photos','*',$conditions);
	?>
	<div id="divToPrint" style="display:none;">
		<img src="<?php echo APP_URL; ?>uploads/photos/real/<?php echo $print->webfile; ?>" style="width:100%; height:auto;" />  
	</div>
	<?php if($_POST['size'] == 'nosize') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=800,height=800');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_URL; ?>success.php";
				},2000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A3') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1000,height=1000');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_URL; ?>success.php";
				},2000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A4') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1200,height=1200');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_URL; ?>success.php";
				},2000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'A5') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1400,height=1400');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_URL; ?>success.php";
				},2000);
		</script>
	<?php } ?>
	<?php if($_POST['size'] == 'othertitle') { ?>
		<script type="text/javascript">     
				var divToPrint = document.getElementById('divToPrint');
				var popupWin = window.open('', '_blank', 'width=1400,height=800');
				popupWin.document.open();
				popupWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</html>');
				popupWin.document.close();
			
				setInterval(function(){
					location.href = "<?php echo APP_URL; ?>success.php";
				},2000);
		</script>
	<?php } ?>
	<?php	
}
?>
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div style="width:100%; margin-left:20px;">
	<?php
		if(!empty($_SESSION['flash_messages']))
		{	
			echo $msgs->display();
		}	
	?>
</div>
	<div class="col-md-12 features features-right" style="margin:20px 0; padding:0 0 0 20px">
		<div style="margin-top:25px; margin-bottom:15px; float:left;"><a href="<?php echo $_SESSION['app']['url']; ?>" style="padding:10px 40px 10px 40px; background-color:#43ace5; color:#fff; text-decoration:none; font-weight:bold; border:0px; border-radius:3px; font-size:16px;" >Back To Gallery</a></div>
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="margin:20px;">
				<?php
				if(!empty($purchase))
				{
					foreach($purchase as $purchase)
					{
					$conditionsphoto = array('id'=>$purchase->photo);
					$photo = $common->getrecord('pr_photos','*',$conditionsphoto);

					$conditionsseller = array('id'=>$purchase->photographer);
					$seller = $common->getrecord('pr_seller','*',$conditionsseller);
					?>
					<div style="border:2px solid #33b5e5; width:100%; float:left">
						<div>
							<div class="col-md-3" style="padding:10px;"><img src="<?php echo APP_URL; ?>uploads/photos/bigwatermark/<?php echo $photo->webfile; ?>" style="width:100%; height:180px;" /></div>
							<div class="col-md-7" style="padding:10px;">
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Product Name</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $photo->name; ?></div>

								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">TXN Id</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->txnid; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Payment Status</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->payment; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Type</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->type; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">File Size</div>
								<div style="font-size:15px; padding:5px;">: <?php if($purchase->size =='othertitle') { ?><?php echo $photo->othertitle; }else{ ?><?php echo $purchase->size; } ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Price</div>
								<?php if($purchase->currency == 'EURO') { ?>
									<div style="font-size:15px; padding:5px;">: &euro; <?php echo $purchase->amount; ?> EURO</div>
								<?php }else{ ?>
									<div style="font-size:15px; padding:5px;">: $ <?php echo $purchase->amount; ?> USD</div>
								<?php } ?>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Photographer</div>
								<div style="font-size:13px; padding:5px;">: <?php echo $seller->username; ?></div>
<div style="clear:both"></div>
<?php
$str = $purchase->date;
$date = explode(" ",$str);
?>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Date</div>
								<div style="font-size:13px; padding:5px;">: <?php echo $date['0']; ?></div>
							</div>
							<div class="col-md-2" style="padding:10px;">
							<?php if($purchase->type == 'webfile') { ?>
								<?php if($purchase->download == 'NotDownload') { ?>
									<div style="margin-top:30px;">
										<form action="<?php echo APP_URL; ?>download1.php" target="_blank" method="post" >	
											<input type="hidden" name="download" value="<?php echo $purchase->photo; ?>" />
											<input type="hidden" name="id" value="<?php echo $purchase->id; ?>" />
											<button type="submit" name="downloadwebfile" style="margin-top:20px;" class="btn btn-primary" onclick="reloadPage();" >Download</button>
										</form>
									</div>
								<?php }else{ ?>
									<div style="margin-top:30px;">
										<button type="button" name="delete_review" style="margin-top:20px;" class="btn btn-primary" >Already Download</button>
									</div>
								<?php } ?>
							<?php } ?>
							<?php if($purchase->type == 'printfile') { ?>
								<?php if($purchase->download == 'NotDownload') { ?>
									<div style="margin-top:0px;">
										<form action="" method="post" >	
											<input type="hidden" name="print" value="<?php echo $purchase->photo; ?>" />
											<input type="hidden" name="id" value="<?php echo $purchase->id; ?>" />
											<input type="hidden" name="size" value="<?php echo $purchase->size; ?>" />
											<button type="submit" name="printfile" value="print" onclick="PrintDiv();" style="margin-top:20px;" class="btn btn-primary" >Print File</button>
										</form>
									</div>
								<?php }else{ ?>
									<div style="margin-top:0px;">
										<button type="submit" name="delete_review" style="margin-top:20px;" class="btn btn-primary" >Already Print</button>
									</div>
								<?php } ?>
							<?php } ?>
							</div>
						</div>
					</div>
					<div style="clear:both; height:20px;"></div>
					<?php
					}
				}
				else
				{
				?>
					<div style="margin:50px;">
					<div style="width:150px; margin:auto;"><img src="<?php echo APP_URL; ?>images/noproduct.png" style="width:100%; height:auto;" /></div>
					<div style="text-align:center;font-size: 18px;font-weight: bold;">No Any Photo Available in Your Purchase Photos List</div>
				</div>
				<?php
				}
				?>
			</div>
			<div style="float:right">
				<?php echo $common->pagination($statement,$limit,$page); ?>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php include(APP_ROOT.'include/footer.php') ?>
<?php include(APP_ROOT.'include/foot.php') ?>
</body>
</html>
<script src="<?php echo APP_URL; ?>js/jquery.validate.min.js"></script>
<script>
	$(document).ready(function(){
	$("#send_review").validate({

		rules: {
				review: {
					required: true,
				},
			},

			messages: {           
				review: {
					required: "Please Write Your Review First",
				},
			},
        
			submitHandler: function(form) {
				form.submit();
			}

	});

	});
</script>

<script type="text/javascript" src="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo APP_URL; ?>fancy-box/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<script type="text/javascript">
	$(document).ready(function() {
		$('.fancybox').fancybox();
	});
</script>
