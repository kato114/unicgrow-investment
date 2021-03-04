<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
$user_id = $_SESSION['mlmproject_user_id'];
$title = 'Display';
$message = 'Display Un-Used Pin';
data_logs($user_id,$title,$message,0);

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$sql = "SELECT * FROM e_pin 
WHERE user_id = '$user_id' AND mode = 1";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM e_pin WHERE user_id = '$user_id' AND mode = 1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Plan</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['epin'];
			$date = $row['date'];
			$product_id = $row['product_id'];
			$amount = $row['amount'];
			$epin_type = $row['epin_type']; 
			$plan = $row['plan'];
			
			$date = date('d-m-Y' , strtotime($date));
			
			if($epin_type == 0)
			{
				$epin_type_status = "Registration E-pin";
				$pin_status = "<form method=post action='register.php' target='blank'>
								<input type='hidden' name='unused_reg_pin' value='$epin'>
								<input type='submit' name='unused_epin' value='Register' class='btn btn-primary'>
								</form>";
			}	
			else
			{
				$epin_type_status = "Top E-pin";
				$pin_status = "<form method=post action='index.php?page=top_up_epin'>
								<input type='hidden' name='invest_epin' value='$epin'>
								<input type='submit' name='epin_submit' value='Transfer' class='btn btn-success'>
								</form>";
				
				$pin_status = "<div class='pull-left'>
								<form method=post action='index.php?page=activation_company_wallet'>
								<input type='hidden' name='invest_epin' value='$epin'>
								<input type='submit' name='epin_submit' value='Top-Up Self' class='btn btn-info'>
								</form>
								</div>
								
								<div class='pull-right'>
								<form method=post action='index.php?page=topup_id'>
								<input type='hidden' name='invest_epin' value='$epin'>
								<input type='submit' name='epin_submit' value='Top-Up Other' class='btn btn-warning'>
								</form>
								</div>";				
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$epin;?></td>
				<td><?=$date;?></td>
				<td><?=$amount;?></td>
				<td><?=$plan;?></td>
				<td width="25%"><?=$pin_status;?></td>
			</tr> <?php	
			$sr_no++;
		} ?> 
	</table> <?PHP
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</b>"; }
?>
		