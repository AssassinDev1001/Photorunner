<?php
include('include/config.php');

if(!empty($_GET['country']))
{
	$conditions = array('country'=>$_GET['country']);
	$state12 = $common->getrecords('pr_state','*',$conditions);
}

?>

<label>Select State</label>
<select class="form-control" name="state" id="state" required="required">
	<option value="">Select State</option>
	<?php
	if(!empty($state12))
	{
		foreach($state12 as $statea12)
		{
			?>
			<option value="<?php echo $statea12->id; ?>"><?php echo $statea12->state; ?></option>
			<?php
		}
	}
	?>
</select>
