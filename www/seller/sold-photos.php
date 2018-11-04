<?php  include('../include/config.php'); include(APP_ROOT.'include/check-seller.php');

?>
<!DOCTYPE html>
<html>
<head>
	<?php include(APP_ROOT.'include/head-other.php'); ?>
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/login.css">
</head>
<body style="background-color:#EBEBEB">
	<?php include(APP_ROOT.'include/header.php'); ?>
<!-- our facilities -->
<div class="space_account"></div>
<div style="height:2px; background-color:#ebebeb;"></div>
<div style="height:20px;"></div>
<div class="container">
	<div class="col-md-12 features features-right" style="margin:20px 0; padding:0 0 0 20px">
		<div class="col-md-12 form-module" style="max-width: 100%;">
			<div style="margin:20px;">
				<?php
				/*$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
				$limit = 2;
				$startpoint = ($page * $limit) - $limit;
				$conditions = "WHERE photographer = '".$_SESSION['seller']['id']."'";						
				$statement = "pr_payments" . $conditions;						
				$conditions .= " LIMIT {$startpoint} , {$limit}";	
				$purchase = $common->getpagirecords('pr_payments','*',$conditions);*/

				$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
				$limit = 10;
				$startpoint = ($page * $limit) - $limit;					
				$conditions = " WHERE photographer = '".$_SESSION['seller']['id']."' ORDER by id DESC ";			
				$statement = "pr_payments" . $conditions;						
				$conditions .= " LIMIT {$startpoint} , {$limit}";	
				$purchase = $common->getpagirecords('pr_payments','*',$conditions);


				if(!empty($purchase))
				{
					foreach($purchase as $purchase)
					{
					$conditionsphoto = array('id'=>$purchase->photo);
					$photo = $common->getrecord('pr_photos','*',$conditionsphoto);

					$conditionsseller = array('id'=>$purchase->photographer);
					$seller = $common->getrecord('pr_seller','*',$conditionsseller);
					?>
					<div class="" style="border:2px solid #33b5e5; float:left; width:100%;">
						<div>
							<div class="col-md-4" style="padding:10px;">
							<?php if(!empty($photo->webfile)) { ?>
								<img src="<?php echo APP_URL; ?>uploads/photos/watermark/<?php echo $photo->webfile; ?>" style="width:100%; height:180px;" />
							<?php }else{ ?>
								<img src="<?php echo APP_URL; ?>images/No-Images.png" style="width:100%; height:180px;" />
							<?php } ?>
							</div>
							<div class="col-md-8" style="padding:10px;">
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Photo Name</div>
								<div style="font-size:15px; padding:5px;">: <?php if(!empty($photo->name)) { echo $photo->name; }else{ ?>No Name <?php } ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">TXN Id</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->txnid; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Payment Status</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->payment; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Type</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->type; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">File Size</div>
								<div style="font-size:15px; padding:5px;">: <?php echo $purchase->size; ?></div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Amount</div>
								<?php
									$payment1 = $purchase->amount;
									$payment2 = $payment1/100;
									$payment3 = $payment2*90;
								?>


								<div style="font-size:15px; padding:5px;">: $ <?php echo $payment3; ?> USD</div>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Photographer Name</div>
								<div style="font-size:13px; padding:5px;">: <?php echo $seller->username; ?></div>
								<div style="clear:both"></div>
<?php
$str = $purchase->date;
$date = explode(" ",$str);
?>
								<div style="font-size:15px; font-weight:bold; padding:5px;"class="buyerbuyer">Date</div>
								<div style="font-size:13px; padding:5px;">: <?php echo $date['0']; ?></div>
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
					<div style="text-align:center;font-size: 18px;font-weight: bold;">No Any Photo Available in Your Sold Photos List</div>
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
