<?php
include('../../security_web_validation.php');
?>
<?php

include("condition.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = "15";

if((isset($_POST['submit'])) or $newp != '')
{
	if($newp == '')
	{
		$_SESSION['save_username_ednet'] = $_REQUEST['username'];
	}
	$username = $_SESSION['save_username_ednet'];
	
	$id_query = query_execute_sqli("SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0){ echo "<B style='color:#FF0000'>Please enter correct Username !</B>"; }
	else
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$id = $row['id_user'];
		}
		
		$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent($id)"))[0];
		$SQL = "SELECT t1.*,t2.position,t2.username FROM reg_fees_structure t1 
		LEFT JOIN users t2 ON t1.user_id = t2.id_user
		WHERE t1.user_id IN ('$result') AND t1.update_fees > 0";
		$query = query_execute_sqli($SQL);
		$totalrows = mysqli_num_rows($query);
		if($totalrows == ''){ echo "<B style='color:#FF0000;'>There are no information to show !!</B>"; }
		else
		{ 
			while($row1 = mysqli_fetch_array($query))
			{ $tatal_amt = $tatal_amt+$row1['update_fees']; } 
		?>
			<table align="center" hspace = 0 cellspacing=0 cellpadding=0 border=0 height="40" width=700>
				<thead>
					<tr><th colspan="4" class="text-center">Total Network Business : &#36; <?=round($tatal_amt,2);?></th></tr>
				</thead>
				<tr>
					<th class="text-center">User ID</th>
					<th class="text-center">Topup Amount</th>
					<th class="text-center">Date</th>
					<th class="text-center">Position</th>  
				</tr>
				<?php
				$pnums = ceil ($totalrows/$plimit);
				if ($newp==''){ $newp='1'; }
					
				$start = ($newp-1) * $plimit;
				$starting_no = $start + 1;
				
				$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
				while($row = mysqli_fetch_array($query))
				{
					$date = date('d/m/Y' , strtotime($row['date']));
					$amount = round($row['update_fees'],5);
					$user_id = $row['username'];
					$position = $row['position'];
					
					if($position == 0) { $pos = 'Left'; }
					else { $pos = 'Right'; }
					?>
					<tr>
						<td><?=$user_id?></td>
						<td>&#36; <?=$amount?></td>
						<td><?=$date?></td>
						<td><?=$pos?></td>
					</tr> <?php
				}
				pagging_admin_panel($newp,$pnums,6,$val); ?>
			</table> <?PHP
		}
	}	
}
else
{ ?>
	<!--<form action="" method="post">
	<table width="60%" border="0">
		<tr>
			<td>Enter Username :</td>
			<td><input type="text" name="username" class="form-control"  /></td>
			<td><input type="submit" name="submit" value="submit" class="btn btn-info" /></td>
		</tr>
	</table>
	</form>-->
	
	<form action="" method="post">
	<table width="85%" border="0">
		<tr>
			<td>
				<label>Username</label>
				<input type="text" name="search_username" placeholder="Search By Username" class="form-control"></td>
			<td>
				<label>Start Date</label>
				<input type="text" name="start_date" placeholder="Enter Start Date Here" class="input-medium flexy_datepicker_input">
			</td>
			<td>
				End Date
				<input type="text" name="end_date" placeholder="Enter End Date Here" class="input-medium flexy_datepicker_input">
			</td>
			<td><label>&nbsp;</label><input type="submit" name="search" value="Submit" class="btn btn-info" /></td>
		</tr>
	</table>
	</form>
<?php  
} ?>
