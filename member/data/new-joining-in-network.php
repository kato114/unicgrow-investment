<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

require_once("config.php");
$id = $_SESSION['mlmproject_user_id'];

$title = 'Display';
$message = 'Display New Joinings';
data_logs($id,$title,$message,0);
 
$date = date('Y-m-d');
$query = query_execute_sqli("SELECT * FROM users WHERE real_parent = '$id' and date = '$date' ");
$num = mysqli_num_rows($query);
if($num == 0)
{
	echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</B>";
}
else 
{
?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="3" class="align-left">Topup History</th></tr></thead>
		<tr>
			<th class="span1 text-center">Name</th>
			<th class="span1 text-center">Username</th>
			<th class="span1 text-center">Status</th>
		</tr>
		
	<tr>
<?php
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		$name= $row['f_name']." ".$row['l_name'];
		$type = $row['type'];
		if($type == 'B') { $status = "Active"; }
		else {  $status = "Deactive"; }
	?>	
		<tr>
			<td class="span1 text-center"><?=$name;?></th>
			<td class="span1 text-center"><small><?=$username;?></small></th>
			<td class="span1 text-center"><small><?=$status;?></small></th>
		</tr>
	<?php
		
	}
	echo "</table>";
}
?>

