<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(count($_GET) > 1){
	$_POST['submit'] = '1';
	$_POST['username'] = $_SESSION['save_username_TR'];
}
else{
	unset($_SESSION['save_username_TR']);
}

if(isset($_POST['submit']))
{
	if(count($_GET) == 1)
	{
		 $_SESSION['save_username_TR'] = $_REQUEST['username'];
	}
	$username = $_SESSION['save_username_TR'];
	
	$sql =  "SELECT * FROM users WHERE username = '$username' ";
	$id_query = query_execute_sqli($sql);
	$num = mysqli_num_rows($id_query);
	if($num == 0){ echo "<B style='color:#FF0000'>Please enter correct Username !</B>"; }
	else
	{
		while($row = mysqli_fetch_array($id_query))
		{
			$id = $row['id_user'];
		}
		
		$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($id)";
		$quer = query_execute_sqli($sql);
		$result = rtrim(mysqli_fetch_array($quer)[0],',');
		
		$sql = "SELECT t1.*,t2.position pos,t2.username FROM reg_fees_structure t1 
		LEFT JOIN users t2 ON t1.user_id = t2.id_user
		WHERE t1.user_id IN ($result) AND t1.request_crowd > 0 AND boost_id = 0 
		GROUP BY t1.user_id,t2.id_user";
		
		//$sql = "SELECT * FROM reg_fees_structure WHERE user_id IN ($result) AND update_fees > 0";
		$SQL = "$sql LIMIT $tstart,$tot_p ";
		$query = query_execute_sqli($SQL);
		$totalrows = mysqli_num_rows($query);
		
		$sqlk = "SELECT SUM(request_crowd) amt , COUNT(*) num FROM ($sql) t1";
		$query = query_execute_sqli($sqlk);
		while($ro = mysqli_fetch_array($query))
		{
			$tot_amt = $ro['amt'];
			$tot_rec = $ro['num'];
			$lpnums = ceil ($tot_rec/$plimit);
		}
		
		if($totalrows == ''){ echo "<B class='text-danger'>There are no information to show !!</B>"; }
		else
		{ 
			while($row1 = mysqli_fetch_array($query))
			{ $tatal_amt = $tatal_amt+$row1['update_fees']; } 
			?>
			<table class="table table-bordered">
				<thead>
				<tr><th colspan="5">Total Network Business : &#36; <?=round($tot_amt,2);?></th></tr>
				<tr>
					<th class="text-center">Sr. No.</th>
					<th class="text-center">User ID</th>
					<th class="text-center">Topup Amount</th>
					<th class="text-center">Date</th>
					<th class="text-center">Position</th>  
				</tr>
				</thead>
				<?php
				$pnums = ceil ($totalrows/$plimit);
				if ($newp==''){ $newp='1'; }
					
				$start = ($newp-1) * $plimit;
				$starting_no = $start + 1;
				
				$sr_no = $starting_no;
				
				$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
				while($row = mysqli_fetch_array($query))
				{
					$date = date('d/m/Y' , strtotime($row['date']));
					$amount = round($row['update_fees'],5);
					$user_id = $row['username'];
					$position = $row['position'];
					
					if($position == 0) { $pos = 'Left'; }
					else { $pos = 'Right'; }
					?>
					<tr class="text-center">
						<td><?=$sr_no?></td>
						<td><?=$user_id?></td>
						<td>&#36; <?=$amount?></td>
						<td><?=$date?></td>
						<td><?=$pos?></td>
					</tr> <?php
					$sr_no++;
				} ?>
			</table> <?PHP
			pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
		}
	}	
}
else
{ ?>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Enter Username :</th>
		<td><input type="text" name="username" class="form-control"  /></td>
		<td><input type="submit" name="submit" value="submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  
} ?>
