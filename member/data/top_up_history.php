<?php
include('../security_web_validation.php');
?>
<?php
session_start();

/*$user_id = $_SESSION['mlmproject_user_id'];
$sql = "select * from reg_fees_structure where user_id = '$user_id' ";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>		
	<table class="table table-bordered table-hovet">
		<tr>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">By User</th>
		</tr>
		<?php
		while($row = mysqli_fetch_array($query))
		{	
			$date = $row['date'];
			$amount = $row['update_fees'];
			$by_user = $row['by_user'];

			if($by_user == 0)
				$by_user = "No Information";				
			else
				$by_user = get_user_name($by_user);
			?>
				<tr>
					<td class="text-center"><?=$date?></td>
					<td class="text-center"><?=$amount?></td>
					<td class="text-center"><?=$by_user?></td>
				</tr>
			<?php
		}
	echo "</table>";
}
else 
{  echo "<B class='text-danger'>There are no information to show !!</B>"; }*/
?>
<h2 class="text-danger">Coming Soon......</h2>
