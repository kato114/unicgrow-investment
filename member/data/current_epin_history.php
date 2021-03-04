<?php
include('../security_web_validation.php');


$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d' , strtotime($_POST['search_date']));
	
	if($search_date != ''){
		$qur_set_search = " AND t1.date = '$search_date'";
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-4 col-md-offset-7">
		<input type="text" name="search_date" placeholder="Search By Date" class="form-control datepicker" />
	</div>
	<div class="col-md-1"><input type="submit" value="Submit" name="Search" class="btn btn-info"></div>
</form>
<div class="col-md-12">&nbsp;</div>

<?php
$sql = "SELECT t1.*,t2.generate_id,t2.transfer_to,t2.user_id eh_user_id FROM e_pin t1 
INNER JOIN epin_history t2 ON t1.id = t2.epin_id 
WHERE t1.user_id = '$user_id' $qur_set_search
ORDER BY t1.date ,t1.id DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1 ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){
?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th>
			<th class="text-center">Generate By</th>
			<th class="text-center">User Id</th>
			<th class="text-center">Transfer To</th>
			<th class="text-center">Used By</th>
			<th class="text-center">Used Date</th>
		</tr>
		</thead>
		<?php	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr = $plimit*($newp-1)+1;
		
		$sql = "$sql  LIMIT $start,$plimit";
		$query_in = query_execute_sqli($sql);
		
		while($row = mysqli_fetch_array($query_in))
		{
			$id = $row['id'];
			$generate_id = $row['generate_id'];
			$date = $row['date'];
			$epin = $row['epin'];
			$amount = $row['amount'];
			$owner = $row['transfer_to'];
			$transfer_id = $row['eh_user_id'];
			$used_id = get_user_name($row['used_id']);
			$used_date = $row['used_date'];
			
			if($generate_id == 0){
				$generate_id = 'Admin';	
			}
			else{
				$generate_id = get_user_name($generate_id);
			}
			
			
			if($transfer_id == $owner){
				$transfer_id = 'No Transfer';
				$owner = get_user_name($owner);
			}
			else{
				$transfer_id = get_user_name($transfer_id);
				$owner = get_user_name($owner);
			}
			
			if($used_id == ''){
				$used_id = "<span class='label label-danger'>Not Used</span>";
				$used_date = "<span class='label label-danger'>No Date</span>";
			}
			?>
			<tr class="text-center">
				<td><?=$sr?></td>
				<td><?=$epin?></td>
				<td><?=$amount?> &#36;</td>
				<td><?=$date?></td>
				<td><?=$generate_id?></td>
				<td><?=$owner?></td>
				<td><?=$transfer_id?></td>
				<td><?=$used_id?></td>
				<td><?=$used_date?></td>
			</tr> <?PHP
			$sr++;
		}
		?>
	</table>
	<?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }	
?>				
