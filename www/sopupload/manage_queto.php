<?php  
include("include/config.php");
include("include/security_admin.php");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include("include/menu_bar_fancy_box.php");?>
<?php include("include/use_fancy_box.php");?>
<?php include('include/head_default.php'); ?>
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/select2/select2_metro.css"/>
<link rel="stylesheet" href="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/data-tables/DT_bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<!--start ckedito script-->
<script src="bootstrap/metronic_1.5.5/bootstrap3.0.3/admin/template_content/assets/plugins/ckeditor/ckeditor.js"></script>

<!--end ckedito script-->
<title>Manage Quote</title>

</head>

<body>

<?php
include("include/header_admin.php");
?>						
																	
              <div class="row" style="margin-top:25px;">
			  
				<div class="col-md-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-edit"></i>Manage Invoice
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
									<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i>
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
								<th>
									Serial
								</th>
								<th>
									User Name
								</th>
								<th>
									Box ID
								</th>
								<th>
									Payment Status
								</th>
								<th>
									Email
								</th>
								<th>
									Amount
								</th>
								<th>
									Create Date
								</th>
								<th>
									View
								</th>
							</tr>
							</thead>
							<tbody>
							<?php
						$i=1;
						$query ="select * from shopgt_queto order by ID DESC";
						$sql =mysql_query($query);
					
                    while($res = mysql_fetch_assoc($sql))
					{ 
					$usrr = mysql_fetch_assoc(mysql_query("select first_name,last_name from shopgt_reg_user where user_id = '".$res['user_id']."'")); 
					$veiwdtal= mysql_query("select qoteid,payment_status,status from shopgt_invoice where qoteid='".$res['id']."'");
					$chkodetail= mysql_fetch_assoc($veiwdtal);
					$count= mysql_num_rows($veiwdtal);
					
					?>
							<tr>
								<td>
									<?php echo $i++; ?>
								</td>
								<td>
									<?php echo ucwords($res['user_name']); ?>
								</td>
								<td> 
									<?php echo $res['box_id']; ?>
								</td>
								<td>
									<?php 									
									if($chkodetail['payment_status']=="partial") {
										echo "Partial Payment";	
									} else{
										if($chkodetail['status']==1){
										echo "Paid"; } else {
										echo "Pending"; }
									}
									?> 
								</td>
								<td>
									<?php echo $res['email']; ?>
								</td>
								<td>
									$<?php echo $res['amount']; ?>
								</td>
								<td> 
									<?php echo $res['date']; ?>
								</td>
								
								<td><a class="fancybox fancybox.ajax" href="quto_detail.php?views_id=<?php echo $res['id']; ?>" data-fancybox-group="gallery" title="">View</a> / <a href="add_queto.php?editid=<?php echo $res['id']; ?>">Edit</a> /<?php if($count==1){ ?> <a href="manage_invoice.php?qt=<?php echo $res['id']; ?>">view Invoice</a> <?php } else { ?><a href="final_invoice.php?qote=<?php echo $res['id']; ?>">Create Invoice</a><?php } ?>
			
								</td>
							</tr>
							 <?php } ?>
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
