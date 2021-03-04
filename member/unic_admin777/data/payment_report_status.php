<?php
include('../../security_web_validation.php');
?>
<?php
//include("../config.php");
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$cnt_d = date('M');
$cnt_yr = date('Y');

$newp = $_GET['p'];
$plimit = "20";

if(isset($_POST['Submit']))
{
	$tbl_id = $_POST['tbl_id'];
	$pay_mode = $_POST['pay_mode'];
	$pay_info = $_POST['pay_info'];
	$date = date('Y-m-d');
			
	query_execute_sqli("update payment_information set mode = 1 , pay_mode = '$pay_mode' , pay_information = '$pay_info' , paid_date = '$date' where id = '$tbl_id' ");
			
	print "<p>Payment has been Transfered Successfully !!</p>"; 
}
else
{

	$quer = query_execute_sqli("select * from payment_information where mode = 0 ");
	$totalrows = mysqli_num_rows($quer);
	if($totalrows != 0)
	{
		while($ro = mysqli_fetch_array($quer))
		{
			$totalamount = $totalamount+$ro['income'];
			$totaltax = $totaltax+$ro['tax'];
			$totaltds = $totaltds+$ro['tds'];
			$total_net_amount = $total_net_amount+$ro['amount'];
		}?>
		<table align="center" cellspacing=0 cellpadding=0 width=100%> 
			<tr><th >&nbsp;</th></tr>
			<tr>
				<th class="text-center">Total Amount</th>
				<th class="text-center">Admin Tax </th>
				<th class="text-center">TDS Tax </th>
				<th class="text-center">Net Amount</th>
			</tr>
			<tr>
				<th class="form-control"><?=$totalamount;?> RC</th>
				<th class="form-control"><?=$totaltax; ?> RC</th>
				<th class="form-control"><?=$totaltds; ?> RC</th>
				<th class="form-control"><?=$total_net_amount;?> RC</th>
			</tr>
			<tr><td colspan="4">&nbsp;</th></tr>
		</table>
		
		<table align="center" cellspacing=0 cellpadding=0 width=100%>
		<?php	
		print " 
			<table class=table width=100%>
			<tr>
				<th colspan=6>&nbsp;</th>
			</tr>
			<tr><th height=30 class=\"message tip\"><strong>User Name</strong></th>
			<th class=\"message tip\"><strong>Bank Details</strong></th>
			<th class=\"message tip\"><strong>Net Amount</strong></th>
			<th class=\"message tip\"><strong>Payment Mode</strong></th>
			<th class=\"message tip\"><strong>Payment Information</strong></th>
			<th class=\"message tip\"><strong>Date</strong></th>
			<th class=\"message tip\"><strong>Status</strong></th></tr>";
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
		
		$query = query_execute_sqli("select * from payment_information where mode = 0  LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{	
			$left_amnt = 0;
			$tbl_id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['income'];
			$admin_tax_anount = $row['tax'];
			$date = $row['date'];
			$admin_tds = $row['tds'];
			$amount = $row['amount'];
			$mode = $row['mode'];
			
			$qq= query_execute_sqli("select * from users where id_user = '$u_id' ");
			while($ru = mysqli_fetch_array($qq))
			{	
				$ac_no = $ru['ac_no'];
				$bank = $ru['bank'];
				$branch = $ru['branch'];
				$bank_code = $ru['bank_code'];
				$branch = $ru['branch'];
			}	
?>							
			<tr>
			<form name="pay_f" action="" method="post">
			<input type="hidden" name="tbl_id" value="<?=$tbl_id; ?>" />
			<td><?=$username; ?></td>
			<td style="width:350px;">
				<small>
					Bank Name : <?=$bank; ?><br />
					IFSC Code : <?=$bank_code; ?><br />
					Branch : <?=$branch; ?><br />
					Account No. : <?=$ac_no; ?><br />
				</small>
			</td>
			<td style="width:120px;"><small><?=$amount; ?> RC</small></td>
			<td style="width:100px;">
				<textarea name="pay_mode" style="width:90px; height:30px;"></textarea>
			</td>
			<td style="width:100px;">
				<textarea name="pay_info" style="width:90px; height:30px;"></textarea>
			</td>
			<td style="width:60px;"><small><?=$date; ?></small></td>
			<td style="width:60px;">
				<input type="submit" name="Submit" value="Pay" class="btn btn-info" />
			</td>
			</form>
			</tr>
<?php			
		}
		print "<tr><td colspan=7>&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?><a href="<?php echo "index.php?page=payment_report_status&p=".($newp-1);?>">&laquo;</a><?php }
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?><a href="<?php echo "index.php?page=payment_report_status&p=$i";?>"><?php print_r("$i");?></a><?php }
			else
			{ print_r("$i");}
		} 
		if ($newp<$pnums) 
		{ ?><a href="<?php echo "index.php?page=payment_report_status&p=".($newp+1);?>">&raquo;</a><?php } 
		print "</table>";	
	}
	else{ print "<p>There is No Member for Payment on $cnt_d $cnt_yr !</p>"; }
	
}	
?>

