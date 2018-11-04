<?php
include('../include/config.php');
if(isset($_POST['downloadwebfile']))
{
	if($common->downloadwebfilee($_POST))
	{
		$condition = array('id'=>$_POST['download']);
		$downloadfile = $common->getrecord('pr_photos','*',$condition);

		$downloadf = $downloadfile->webfile;
		$file = APP_ROOT."uploads/photos/real/$downloadf";
		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    readfile($file);
		    exit;
		}
	}
	else
	{
		$common->redirect(APP_URL.'buyer/purchase-list.php');
	}

}
?>
