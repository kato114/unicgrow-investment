<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$newp = $_GET['p'];
$plimit = "25";

$SQL = "select * from request_crown_wallet ";
$q = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($q);

if($totalrows > 0)
{ ?>	
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">USD Amount</th>
			<th class="text-center">BTC Amount</th>
			<th class="text-center">Address</th>
			<th class="text-center">Hash Code</th>
			<th class="text-center">Date Time</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
	<?php		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	$sr_no = $starting_no;
	$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
	while($row = mysqli_fetch_array($query))
	{
		$user_id = get_user_name($row['user_id']);
		$investment = $row['investment'];
		$amount = $row['request_crowd'];
		$hash_code = $row['transaction_hash'];
		$bitcoin_address = $row['bitcoin_address'];
		$mode = $row['status'];
		$date = date('d/m/Y H:i:s', strtotime($row['date']));
		
		if($mode == 0){ $status = "<B style='color:#FF0000;'>Pending</B>"; }
		else{ $status = "<B style='color:#008000;'>Confirmed</B>"; }
					
		?>
		<tr align="center">
			<td><?=$sr_no?></td>
			<td><?=$user_id?></td>
			<td><?=$investment?> &#36;</td>
			<td><?=$amount?> &#3647;</td>
			<td><?=$bitcoin_address?></td>
			<td><?=$hash_code?></td>
			<td><?=$date?></td>
			<td><?=$status?></td>
		</tr> <?php
		$sr_no++;
	} ?>
		<tr><td colspan=8>&nbsp;</td></tr>
		<tr>
			<td colspan=8 height=30 class="text-left">
				<?php
				if($newp > 1)
				{ ?> <a href="<?="index.php?page=btc_investment&p=".($newp-1);?>">&laquo;</a> <?php }
				for($i = 1; $i <= $pnums; $i++) 
				{ 
					if ($i != $newp)
					{ ?> <a href="<?="index.php?page=btc_investment&p=$i";?>"><?php print_r("$i");?></a><?php }
					else
					{ print_r("$i"); }
				} 
				if ($newp < $pnums) 
				{ ?> <a href="<?="index.php?page=btc_investment&p=".($newp+1);?>">&raquo;</a> <?php } ?>
			</td>
		</tr>
	</table>
<?php
}
else 
{  echo "<B style='color:#FF0000; font-size:16px;'>There are no information to show !!</B>"; }
?>