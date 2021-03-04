<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$newp = $_GET['p'];
$plimit = "25";

$SQL = "select *,SUM(request_crowd) amt from withdrawal_crown_wallet GROUP BY user_id  ";
$q = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($q);

if($totalrows > 0)
{ ?>	
	<table align="center" hspace=0 cellspacing=0 cellpadding=0 border=0 width=95%>
		<tr>
			<th class="text-center">User ID</th>
			<th class="text-center">Withdraw Amount</th>
			<th class="text-center">Hash Code</th>
			<th class="text-center">Date Time</td>
			<th class="text-center">Status</td>
		</tr>
	<?php		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
	while($row = mysqli_fetch_array($query))
	{
		$user_id = get_user_name($row['user_id']);
		$invst_amt = $row['amt'];
		$hash_code = $row['transaction_hash'];
		$mode = $row['status'];
		$date = date('d/m/Y H:i:s', strtotime($row['date']));
		
		if($mode == 0){ $status = "<B style='color:#FF0000;'>Unconfirmed</B>"; }
		else{ $status = "<B style='color:#008000;'>Confirmed</B>"; }
					
		?>
		<tr align="center">
			<td class="input-small"><small><?=$user_id?></small></td>
			<td><small><?=$invst_amt?> &#36;</small></td>
			<td><small><?=$hash_code?> </small></td>
			<td><small><?=$date?></small></td>
			<td><small><?=$status?></small></td>
		</tr> <?php
	} ?>
		<tr><td colspan=5>&nbsp;</td></tr>
		<tr>
			<td colspan=5 height=30 class="text-center">
			<B>
				<?php
				if ($newp > 1)
				{ ?> <a href="<?="index.php?page=withdraw_report&p=".($newp-1);?>">&laquo;</a> <?php }
				for ($i=1; $i<=$pnums; $i++) 
				{ 
					if ($i != $newp)
					{ ?> <a href="<?="index.php?page=withdraw_report&p=$i";?>"><?php print_r("$i");?></a><?php  }
					else
					{ print_r("$i"); }
				} 
				if ($newp < $pnums) 
				{ ?> <a href="<?="index.php?page=withdraw_report&p=".($newp+1);?>">&raquo;</a> <?php   } ?>
			</B>
			</td>
		</tr>
	</table>
<?php
}
else 
{  echo "<B style='color:#FF0000; font-size:16px;'>There are no information to show !!</B>"; }
?>