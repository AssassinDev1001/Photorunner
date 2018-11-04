<?php    
include("include/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("include/menu_bar_fancy_box.php");?>
<?php include('include/head_default.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/select2/select2_metro.css"/>
<link rel="stylesheet" href="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/data-tables/DT_bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<!--start ckedito script-->
<script src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/ckeditor/ckeditor.js"></script>
<!--end ckedito script-->
<title>Manage Order</title>

</head>

<body>

<?php
include("include/header_admin.php");
?>

<div  style="margin:auto;margin-top:5px;background-color:white;"></div>

			<div style="clear:left; height:30px"></div>                
			<?php include('include/main_msg.php'); ?>			
              <div class="row" style="margin-top:25px;">			  
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-edit"></i>Manage Order
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
								<a href="#portlet-config" data-toggle="modal" class="config"></a>
								<a href="javascript:;" class="reload"></a>
								<a href="javascript:;" class="remove"></a>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-toolbar">
								<div class="btn-group">
									<button id="sample_editable_1_new" class="btn green">
									Add New <i class="fa fa-plus"></i>
									</button>
								</div>
								<div class="btn-group pull-right">
									<button class="btn dropdown-toggle" data-toggle="dropdown">Tools 
										<i class="fa fa-angle-down"></i>
									</button>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="#">Print</a>
										</li>
										<li>
											<a href="#">Save as PDF</a>
										</li>
										<li>
											<a href="#">Export to Excel</a>
										</li>
									</ul>
								</div>
							</div>
							
							<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
							<thead>
								<tr>
									<th>Serial</th>
									<th>Email</th>
									<th>Name</th>
									<th>Amount</th>
									<th>Order ID</th>
									<th>Action</th>									
								</tr>
							</thead>
							<tbody>
						<?php 
						$i=1;
						$query = "select * from ship_order where status=0 group by order_id order by id desc";
						$sql =mysql_query($query);					
						while($res = mysql_fetch_assoc($sql))
						{	
						$query1 = "select * from shopgt_reg_user where user_id='".$res['user_id']."'";	
						$sql1 =mysql_query($query1);	
						$userdet= mysql_fetch_assoc($sql1);
						?>
							<tr>
								<td>
									<?php echo $i++; ?>
								</td>
								<td>
									<?php echo $userdet['user_email']; ?>
								</td>
								<td> 
									<?php echo $userdet['first_name']." ".$userdet['last_name']; ?>
								</td>
								<td>
									$ <?php echo $res['amount']; ?>
								</td>
								<td><?php echo $res['order_id']; ?></td>
								<td> 
									<a  href="add_localcharge.php?iid=<?php echo $res['order_id']; ?>&uid=<?php echo $res['user_id']; ?>" >Add Local Charge</a>
								</td>
								
							</tr>
							<?php
						} ?>
							</tbody>
							</table>
							
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
			</div>
			<!-- END PAGE CONTENT -->
	<?php
	include("include/footer.php");
	?>
	<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/data-tables/DT_bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/scripts/app.js"></script>
<script src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/scripts/table-editable.js"></script>
<script>
jQuery(document).ready(function() {       
   App.init();
   TableEditable.init();
});
</script>
<script>

			// This call can be placed at any point after the
			// <textarea>, or inside a <head><script> in a
			// window.onload event handler.

			// Replace the <textarea id="editor"> with an CKEditor
			// instance, using default configurations.

			CKEDITOR.replace( 'editor1' );

		</script>
</body>
</html>
