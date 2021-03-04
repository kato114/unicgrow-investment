<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
$login_id = $_SESSION['mlmproject_user_id'];

$SQLK = "SELECT * FROM request_crown_wallet WHERE user_id = '$login_id' and status = 0";
$query = query_execute_sqli($SQLK);
$tot_row = mysqli_num_rows($query);
if($tot_row > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<?php 
		while($ro = mysqli_fetch_array($query))
		{
			$investment = $ro['investment'];
		?>
			<tr>
				<td><?=$sr_no;?></td> 		
				<td><?=$request_crowd;?> <?=$icon_black;?></td>
				<td><?=$tax_amount;?></td>
				<td><?=$cur_bitcoin_value;?></td>
				<td><?=$date;?></td> 		
				<td><?=$description;?></td>
				<td><?=$ac_type;?></td>
				<td><?=$status;?></td>
			</tr>
		<?php
		}
		print "</table>";
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>