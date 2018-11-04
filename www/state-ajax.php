<?php
include('include/config.php');
if(!empty($_GET['country']))
{
	$conditions = array('country'=>$_GET['country']);
	$state12 = $common->getrecords('pr_state','*',$conditions);
}

?>
<select class="form-control" name="state" style="border-radius:0px; margin: 0 0 20px;" id="state" required="required" onchange="getcity(this.value);">
	<option value="">Select State</option>
	<?php
	if(!empty($state12))
	{
		foreach($state12 as $statea12)
		{
			?>
			sdsad
			<option value="<?php echo $statea12->id; ?>"><?php echo $statea12->state; ?></option>
			<?php
		}
	}
	?>
</select>
