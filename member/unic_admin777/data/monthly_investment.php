<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
?>
<h1>User Monthly Investment</h1>
<?php
$query = query_execute_sqli("select MIN(date) from reg_fees_structure");
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>
	<table align="center" hspace ="0" cellspacing="0" cellpadding="0" border="0" width="500">
	<tr><td colspan="2">&nbsp;</td></tr>		
	<tr>
		<td class="text-center" colspan="2" align="center"><B>Investment Month</B></td>
		<td class="text-center" colspan="2" align="center"><B>Total Investment</B></td>
	</tr>
	<?php	
	while($row = mysqli_fetch_array($query))
	{
		$min_date = $row[0];
	}
	$current_month = date('Y-m-01');
	$start_date = $min_date;
	while($current_month >= $start_date)
	{ 	  
		$end_date = date('Y-m-01', strtotime($start_date . ' +1 month'));
		$quer = query_execute_sqli("select * from reg_fees_structure where date >= '$start_date' and date < '$end_date' ");
		$num = mysqli_num_rows($quer);
		if($num > 0)
		{
			$tamount = 0;
			while($row = mysqli_fetch_array($quer))
			{
				$update_fees = $row['update_fees'];
				$reg_fees = $row['reg_fees'];
				if($update_fees == 0)
					$tamount = $tamount+$reg_fees;
				else
					$tamount = $tamount+$update_fees;
			}
			$full_start_date = date('M Y', strtotime($end_date . ' -1 month'));	
		 ?>
			<tr>
				<td class="text-center" colspan="2" align="center"><B><?=$full_start_date;?></B></td>
				<td class="text-center" colspan="2" align="center"><B><?=$tamount; ?> &#36;</B></td>
			</tr>
		 <?php  
		}
		$all_date = date('Y-m-01', strtotime($start_date . ' +1 month'));
		$start_date = $all_date;
	}
	print "</table>";
}
		