<?php  include('../include/config.php'); include(APP_ROOT.'include/check-login.php'); include(APP_ROOT.'include/check-information.php');

if(isset($_POST['post_review']))
{
	if(isset($_POST['js_enabled']))
	{
		$msgs->add('i', 'Javascript not enable. Please enable javascript.');	
		$common->redirect(APP_URL);
	}
	else
	{
		 if($common->add_productreview($_POST))
		{
			$common->redirect(APP_URL.'buyer/purchase-list.php');
		}
		else
		{
			$common->redirect(APP_URL.'buyer/purchase-list.php');
		}
		
	}	
}


if(isset($_POST['delete_review']))
{
	if($common->deletereview($_POST))
	{
		$common->redirect(APP_URL.'buyer/purchase-list.php');
	}
	else
	{
		$common->redirect(APP_URL.'buyer/purchase-list.php');
	}	
}

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = 10;
$startpoint = ($page * $limit) - $limit;					
$conditions = " WHERE buyer = '".$_SESSION['account']['id']."' ORDER by id DESC ";			
$statement = "pr_payments" . $conditions;						
$conditions .= " LIMIT {$startpoint} , {$limit}";	
$purchase = $common->getpagirecords('pr_payments','*',$conditions);

?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
	<style>
		.star-rating {
		  font-size: 0;
		  white-space: nowrap;
		  display: inline-block;
		  width: 100px;
		  height: 20px;
		 
		  position: relative;
		  background: url('../images/1457455617_Low rating.png');
		  background-size: contain;
		}
		.star-rating i {
		  opacity: 0;
		  position: absolute;
		  left: 0;
		  top: 0;
		  height: 100%;
		  width: 20%;
		  z-index: 1;
		  background: url('../images/1457455623_Favourites.png');
		  background-size: contain;
		}
		.star-rating input {
		  -moz-appearance: none;
		  -webkit-appearance: none;
		  opacity: 0;
		  display: inline-block;
		  width: 20%;
		  height: 100%;
		  margin: 0;
		  padding: 0;
		  z-index: 2;
		  position: relative;
		}



		.star-rating input:hover + i,
		.star-rating input:checked + i {
		  opacity: 1;
		}


		.star-rating i ~ i {
		  width: 40%;
		}
		.star-rating i ~ i ~ i {
		  width: 60%;
		}
		.star-rating i ~ i ~ i ~ i {
		  width: 80%;
		}
		.star-rating i ~ i ~ i ~ i ~ i {
		  width: 100%;
		}

		.error
		{
			color:red;
		}
	</style>
	<script>
		function fun(vals)
		{
		if(vals=='1')
		{
		document.getElementById("show_rating").innerHTML="Poor";
		}
		if(vals=='2')
		{
		document.getElementById("show_rating").innerHTML="Average";
		}
		if(vals=='3')
		{
		document.getElementById("show_rating").innerHTML="Good";
		}
		if(vals=='4')
		{
		document.getElementById("show_rating").innerHTML="Very Good";
		}
		if(vals=='5')
		{
		document.getElementById("show_rating").innerHTML="Excellent";
		}
		}
		function funs(valss)
		{
		if(valss=='1')
		{
		document.getElementById("show_rating").innerHTML="";
		}
		if(valss=='2')
		{
		document.getElementById("show_rating").innerHTML="";
		}
		if(valss=='3')
		{
		document.getElementById("show_rating").innerHTML="";
		}
		if(valss=='4')
		{
		document.getElementById("show_rating").innerHTML="";
		}
		if(valss=='5')
		{
		document.getElementById("show_rating").innerHTML="";
		}
		}
		
		function reloadPage()
		{
			setInterval(function(){
				location.href = "<?php echo APP_URL; ?>buyer/purchase-list.php";
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
					location.href = "<?php echo APP_URL; ?>buyer/purchase-list.php";
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
					location.href = "<?php echo APP_URL; ?>buyer/purchase-list.php";
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
					location.href = "<?php echo APP_URL; ?>buyer/purchase-list.php";
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
					location.href = "<?php echo APP_URL; ?>buyer/purchase-list.php";
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
							<div class="col-md-3" style="padding:10px;">
							<?php if(!empty($photo->webfile)) { ?>
								<img src="<?php echo APP_URL; ?>uploads/photos/bigwatermark/<?php echo $photo->webfile; ?>" style="width:200px; height:180px;" />
							<?php }else{ ?>
								<img src="<?php echo APP_URL; ?>images/No-Images.png" style="width:200px; height:180px;" />
							<?php } ?>
							</div>
							<div class="col-md-7" style="padding:10px;">
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Product Name</div>
								<div style="font-size:15px; padding:5px;">: <?php if(!empty($photo->name)) { echo $photo->name; }else{ ?>No Name <?php } ?></div>

								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">TXN Id</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->txnid; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Payment Status</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->payment; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Type</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->type; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">File Size</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->size; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Price</div>
								<?php if($purchase->currency == 'EURO') { ?>
									<div style="font-size:15px; padding:5px;">: &euro; <?php echo $purchase->amount; ?> EURO</div>
								<?php }else{ ?>
									<div style="font-size:15px; padding:5px;">: $ <?php echo $purchase->amount; ?> USD</div>
								<?php } ?>
								<div style="font-size:15px; font-weight:bold; padding:5px;" class="buyerbuyer">Photographer</div>
								<div style="font-size:13px; padding:5px;">: <?php echo $seller->username; ?></div>
							</div>
							<div class="col-md-2" style="padding:10px;">
							<?php if($purchase->review == '0') { ?>
								<?php /*<a href="#<?php echo $purchase->id; ?>" class="fancybox" style="color:#ffffff;"><div style="color:#ffffff; text-align:center; margin-top:50px; font-size:15px; background-color:#00A2B5; padding:9px; border-radius:3px;">Send Review</div></a>*/ ?>
								<div id="<?php echo $purchase->id; ?>" style="width:100%; display: none; margin:auto; padding-top:15px;">
									<form action="" method="post" enctype='multipart/form-data' id="send_review" style="height: 240px;">	
										<h4>Send Review</h4>
										<input type="hidden" name="photo" id="photo" value="<?php echo $purchase->photo; ?>" />
										<input type="hidden" name="paymentid" id="paymentid" value="<?php echo $purchase->id; ?>" />
										<div style="padding-bottom:10px;">
											<span class="star-rating">
												  <input type="radio" name="rating" value="1" onmouseover="fun(this.value)" onmouseout="funs(this.value)"><i></i>
												  <input type="radio" name="rating" value="2" onmouseover="fun(this.value)" onmouseout="funs(this.value)"><i></i>
												  <input type="radio" name="rating" value="3" onmouseover="fun(this.value)" onmouseout="funs(this.value)"><i></i>
												  <input type="radio" name="rating" value="4" onmouseover="fun(this.value)" onmouseout="funs(this.value)"><i></i>
												  <input type="radio" name="rating" value="5" onmouseover="fun(this.value)" onmouseout="funs(this.value)"><i></i>
											</span>
											<span style="color:red" id="show_rating"></span>
										</div>
										<textarea name="review" id="review" rows="4" cols="50" style="border-radius:0px; border:1px solid #00A2B5; width:100%" required></textarea>
										<div style="clear:both"></div>
										<button type="submit" name="post_review" style="margin-top:20px;" class="btn btn-primary" >Post Review</button>
									</form>
								</div>
							<?php }else{ ?>
								<div style="margin-top:52px;">
									<form action="" method="post" >	
										<input type="hidden" name="delete" id="delete" value="<?php echo $purchase->id; ?>" />
										<button type="submit" name="delete_review" style="margin-top:20px;" class="btn btn-primary" >Delete Review</button>
									</form>
								</div>
							<?php } ?>
							<?php if($purchase->type == 'webfile') { ?>
								<?php if($purchase->download == 'NotDownload') { ?>
									<div style="margin-top:0px;">
										<form action="<?php echo APP_URL; ?>buyer/download.php" target="_blank" method="post" >	
											<input type="hidden" name="download" value="<?php echo $purchase->photo; ?>" />
											<input type="hidden" name="id" value="<?php echo $purchase->id; ?>" />
											<button type="submit" name="downloadwebfile" style="margin-top:20px;" class="btn btn-primary" onclick="reloadPage();" >Download</button>
										</form>
									</div>
								<?php }else{ ?>
									<div style="margin-top:0px;">
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
					<div style="text-align:center;font-size: 18px;font-weight: bold;">No Any Photo Available in Your Purchase List</div>
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
