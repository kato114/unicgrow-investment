<?php
include('../security_web_validation.php');
session_start();
include("condition.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "25";


$tamount = 0;
$sql = "SELECT t1.*,t2.profit,t2.id reg_id,t2.user_id red_userid FROM ledger t1 
LEFT JOIN reg_fees_structure t2 ON t1.by_id = t2.id
WHERE t1.user_id = '$login_id' AND t2.user_id != '$login_id' AND t1.particular ='Debit Fund For TOPUP FROM Company Wallet'";
$q = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($q);
if($totalrows == 0)
{
	echo "<B class='text-danger'>There are no information to show !!</B>"; 
}
else
{
	while($row = mysqli_fetch_array($q))
	{
		$tot_amt += $row['dr'];
	}		
	?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="11">Total Investment:- &nbsp; &#36; <?=$tot_amt;?></th></tr></thead>
		<tr>
			<th class="text-center" width="10%">Sr. No</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Profit(%)</th>
		</tr>
		<?php 
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$i = 1;
		$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($r = mysqli_fetch_array($q))
		{
			$id = $r['id'];
			$date = date('d/m/Y', strtotime($r['date_time']));
			$by_id = $r['red_userid'];
			$amount = $r['dr'];
			$profit = $r['profit'];
			$reg_id = $r['reg_id'];
			$topupto = get_user_name($by_id);
						
			if($type == 0){ $status = "Activation Wallet"; }
			else{ $status = "Grow Well Wallet"; }
			?>
			
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$topupto;?></td>
				<td><?=$date;?></td>
				<td>&#36; <?=$amount;?></td>
				<td>
					<?=$profit;?>%
					<!--<form action="index.php?page=box_growth" method="post">
						<input type="hidden" name="url" value="<?=$val;?>" />
						<input type="hidden" name="table_id" value="<?=$reg_id;?>" />
						<input name="growth" value="Growth" class="btn btn-info" type="submit" />
					</form>-->
				</td>
			</tr>
			<?php
			$i++;
		}
		?>
	</table> <?php 
	pagging_initation($newp,$pnums,$val);
}
?>	
