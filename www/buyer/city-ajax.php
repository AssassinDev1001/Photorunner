<?php
include('../include/config.php');

if(!empty($_GET['state']))
{
	$conditions = array('state'=>$_GET['state']);
	$city12 = $common->getrecords('pr_city','*',$conditions);
}

?>

<select class="form-control" name="city" style="border-radius:0px; margin: 0 0 20px;" id="city" required="required">
	<option value="">Select City</option>
	<?php
	if(!empty($city12))
	{
		foreach($city12 as $city12)
		{
			?>
			<option value="<?php echo $city12->id; ?>"><?php echo $city12->city; ?></option>
			<?php
		}
	}
	?>
</select>
