<?php include('include/config.php'); 
$conditions = array('status'=>'1');
$photo = $common->getrecords('pr_photos','*',$conditions) ;
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('include/head-other.php'); ?>
	<link rel="stylesheet" type="text/css" href="efacts/style.css" media="all" />
	<link rel="stylesheet" type="text/css" href="efacts/demo.css" media="all" />
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,600' rel='stylesheet' type='text/css'>
	<script src="efacts/custom.js" type="text/javascript"></script>
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/style.css" />
	<link href="http://www.jqueryscript.net/css/top.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="pagination/css/jPages.css">
	<script src="pagination/js/jPages.js"></script>
	<script>
	$(function(){
		$("div.holder").jPages({
		containerID  : "itemContainer",
		perPage      : 16,
		startPage    : 1,
		startRange   : 1,
		midRange     : 2,
		endRange     : 1
		});

		});
	</script>
</head>
<body>
<?php include('include/header.php'); ?>
<div style="background-color:#f2f2f2">
	<div class="banner-bottom" style="background-color:#f2f2f2">
		<div style="height:500px;"></div>
		<div class="container">
			<div style="height:20px;"></div>
			<div class="blog-section">
			<div class="blog-posts">
			<div class="blog-top" id="itemContainer">
			<?php
			if(!empty($photo))
			{
				foreach($photo as $photo)
				{
				?>
				<div class="photo_width_photos1">
					<div class="photo_width_photos">
						<div class="bottom-grids" style="margin-top:0px;">
							<div class="demo-3" style="margin-top:12px;">
								<div class="freshdesignweb"> 
									<article class="border c-two" style="background-image:url(https://s3-eu-west-1.amazonaws.com/photorunner.view/<?php echo $photo->webfile; ?>); background-size: 100% 260px; background-repeat: no-repeat; padding: 0px;">
										<div style="opacity: 0;" class="fdw-background">
											<h4 style="width:90%; margin:auto;"><a href="view-photo.php?view=<?php echo base64_encode($photo->id); ?>" style="color:#fff;"><?php echo $photo->name; ?> ::: <?php echo $photo->id; ?></a></h4>
											<a href="view-photo.php?view=<?php echo base64_encode($photo->id); ?>" ><h4 class="log_bg" style="color:#fff; width:50%; background-color:#ed4e6e; margin-left:auto; margin-right:auto; margin-top:15px; border-radius:0px;"><center>Click</center></h4></a>


										</div>
									</article>
								</div>
							</div>
							<div style="clear:both"></div>

						</div>	
					</div>
				</div>
				<div><?php echo $photo->id; ?></div>
				<?php
				}
				
			}
			?>
			</div></div</div>
			<div style="clear:both"></div>
			<div style="text-align:center; margin-top:30px;">
				<div class="blog-pagimation">
					<div class="holder"></div>
				</div>
			</div>
			</div>
		</div>
		<div style="clear:both; height:40px;"></div>
	</div>
</div>
<?php include('include/footer.php') ?>
<?php include('include/foot.php') ?>
</body>
</html>
<script type="text/javascript">
$(function() {
	$(".love").click(function()
	{
		var id = $(this).attr("id");	
		var dataString = 'id='+ id ;
		var parent = $(this);
		$(this).fadeOut(300);
		$.ajax({
			type: "GET",
			url: "favourite_product_ajax.php",
			data: dataString,
			success: function(html)
			{
				var divs = html.split('@=@');
				parent.html(divs[0]);
				parent.fadeIn(300);
				 $( '#show_stared132' ).html( divs[1] );
				$(this).removeClass('love');
			}
		});
		return false;
	});
});
</script>

