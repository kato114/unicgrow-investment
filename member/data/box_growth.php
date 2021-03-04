<?php
session_start();
include("../config.php");
include('../function/setting.php');
$user_id = $_SESSION['mlmproject_user_id'];

$table_id = $_POST['table_id'];
$url = $_POST['url'];

$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND id ='$table_id'";
$que = query_execute_sqli($sql);
$query = query_execute_sqli($sql);
$rrr = mysqli_fetch_array($que);
$update_fees = $rrr['update_fees'];
?>

<table class="table table-bordered table-hover">
	<thead><tr><th colspan="4">Principal Amount : <?=$update_fees?></th></tr></thead>
	<tr>
		<th class="text-center">Day</th>
		<th class="text-center">Date</th>
		<th class="text-center">Growth</th>
		<th class="text-center">Status</th>
	</tr>
	<?php
	
	while($row = mysqli_fetch_array($query))
	{
		$amount1 = $row['update_fees'];
		$date = $row['date'];
		$percent = $row['profit'];
		$total_days = $row['total_days'];
	}
	$start_date = date('Y-m-d', strtotime($date."+1 Month"));
	$date = date('Y-m-d' , strtotime($start_date."- 1 month"));

	$per = $percent;
	
	for($i = 1; $i <= $total_days;)
	{
		$date = date('Y-m-d' , strtotime($date."+ 1 Month"));
		$sat_sun = date('D' , strtotime($date));
		
		/*if($sat_sun != "Sat" and $sat_sun != "Sun")
		{*/ 
			$amount = $per*$i; //$amount1 +$per*$i;  
			$date1 = date('d/m/Y' , strtotime($date));
		
			if($date > $systems_date){ $status = "<span class='label label-danger'>Unconfirmed</span>";}
			else{ $status = "<span class='label label-success'>Confirmed</span>";}
			?>
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$date1;?></td>
				<td><?=round($amount,2);?>&#36; </td>
				<td><?=$status;?></td>
			</tr> <?php
			$i++;
		//}
	} ?>
</table>
