<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/daily_income.php");



if(isset($_POST['submit']))
{
	$turn_process = $_REQUEST['turn_process'];
	query_execute_sqli("update income_process set mode = '$turn_process' ");
}

$qu = query_execute_sqli("select * from income_process where id = 1 ");
while($r = mysqli_fetch_array($qu))
{
	$process_mode = $r['mode'];
}
if($process_mode == 1) 
{
	$system_turn = "On";
	$system_process ="Off";
	$turn_process = 0;
}
else
{
	$system_process ="On";
	$system_turn = "Off";
	$turn_process = 1;
}
?>
<form name="pay_form" action="index.php?page=system_on_off" method="post">
<input type="hidden" name="turn_process" value="<?=$turn_process; ?>" >
<table class="table table-bordered">
	<thead><tr><th>System On/Off Panel</th></tr></thead>
	<tr><th class="text-success">System Is Currently <?=$system_process;?></th></tr>
	<tr>
		<td>
			<input type="submit" name="submit" class="btn btn-warning" value="Turn <?=$system_turn;?>" />
		</td>
	</tr>
</table>
</form>

