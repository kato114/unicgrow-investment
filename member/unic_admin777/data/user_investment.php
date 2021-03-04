<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");

if(isset($_POST['submit']))
{
	$username = $_REQUEST['username'];
	$query = query_execute_sqli("SELECT * FROM users WHERE username = '$username'");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$_SESSION['real_par_admin_user_$id'] = $id;
		}
		$tamount = 0;
		$q = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$id' ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$update_fees = $row['update_fees'];
				$reg_fees = $row['reg_fees'];
				
				if($update_fees == 0) $tamount = $tamount+$reg_fees;
				else $tamount = $tamount+$update_fees;
			}	
			$q = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$id' ");
		 	?>
			<table class="table table-bordered">
				<thead>
				<tr><th colspan="6">Total Investment : <?=$tamount; ?> &#36;</th></tr>
				<tr>
					<th class="text-center">Sr. No</th>
					<th class="text-center">Date</th>
					<th class="text-center">Investment</th>
					<th class="text-center">Profit (%)</th>
					<th class="text-center">Total Month</th>
					<th class="text-center">Type</th>
				</tr>
				</thead>
			 	<?php
				$sr_no = 1;
				while($r = mysqli_fetch_array($q))
				{
					$date = $r['date'];
					$profit = $r['profit'];
					$total_days = $r['total_days'];
					$mode = $r['mode'];
					$reg_fees = $r['reg_fees'];
					$update_fees = $r['update_fees'];
					$boost = $r['boost'];
				
					if($update_fees == 0) $amount = $reg_fees;
					else $amount = $update_fees;
						
					if($boost == 0 and $mode == 1){ $type = "Investment"; }
					if($boost > 0 and $mode == 1){ $type = "Investment Boost"; }
					if($boost == 0 and $mode == 0){ $type = "Investment Close"; }
					?>
					<tr class="text-center">
						<td><?=$sr_no?></td>
						<td><?=$date?></td>
						<td><?=$amount?> &#36;</td>
						<td><?=$profit?></td>
						<td><?=$total_days?></td>
						<td><?=$type?></td>
					</tr> <?php
					$sr_no++;
				} ?>
			</table> <?php
		}
		else{ echo "<B class='text-danger'>Sorry Username ".$username." have no Investments !</B>"; }
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Username !</B>"; }	
}
else
{ ?> 
<form action="" method="post">
<table class="table table-bordered">
	<!--<tr><th colspan="2">Wallet Information</th></tr>-->
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="username" class="form-control"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
<?php 
}	
?>