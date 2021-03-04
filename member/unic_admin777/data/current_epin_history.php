<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d', strtotime($_POST['search_date']));
	
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($search_date != ''){
		$qur_set_search = "WHERE t1.date = '$search_date' ";
	}
	if($_POST['search_username'] !=''){
		$qur_set_search = " WHERE t2.user_id = '$search_id' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="search_date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	
<?php	

$sql = "SELECT t1.*,t2.user_id hist_is,t2.transfer_to FROM e_pin t1 
INNER JOIN epin_history t2 ON t1.id = t2.epin_id 
$qur_set_search
GROUP BY t1.epin ORDER BY t1.date, t1.id DESC";

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

?>
<table class="table table-bordered">
	<thead>	
	<tr>
		<th class="text-center">Sr. No.</th>
		<th class="text-center">E-pin</th>
		<th class="text-center">Amount</th>
		<th class="text-center">Date</th>
		<!--<th class="text-center">Generate By</th>-->
		<th class="text-center">User Id</th>
		<th class="text-center">Transfer To</th>
		<th class="text-center">Used By</th>
		<th class="text-center">Used Name</th>
		<th class="text-center">Used Date</th>
	</tr>
	</thead>
	<?php	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	$sr = $plimit*($newp-1)+1;

	$query = query_execute_sqli("$sql LIMIT $start,$plimit");
	while($row = mysqli_fetch_array($query))
	{
		
		$id = $row['id'];
		$generate_id = $row['generate_id'];
		$date = date('d/m/Y', strtotime($row['date']));
		$epin = $row['epin'];
		$amount = $row['amount'];
		$owner = $row['transfer_to'];
		$transfer_id = $row['hist_is'];
		$used_id = $row['used_id'];
		$used_date = $row['used_date'];
		
		if($generate_id == 0){ $generate_id = 'Admin';	}
		else{ $generate_id = get_user_name($generate_id); }
		
		if($transfer_id == $owner){
			$transfer_id = 'No Transfer';
			$owner = get_user_name($owner);
		}
		else{
			$transfer_id = get_user_name($transfer_id);
			$owner = get_user_name($owner);
		}
		
		if($used_id == 0 or $used_id == ''){
			$used_ID = "<span class='label label-warning'>Unused</span>";
			$used_date = "<span class='label label-danger'>No Date</span>";
		}
		else{ 
			$used_ID = get_user_name($used_id);
			$used_NAME = get_full_name($used_id);
		}
		
		?>
		<tr class="text-center">
			<td><?=$sr?></td>
			<td><a href="index.php?page=current_epin_historys&epin=<?=$id?>" title="View"><?=$epin?></a></td>
			<td><?=$amount?></td>
			<td><?=$date?></td>
			<!--<td><?=$generate_id?></td>-->
			<td><?=$owner?></td>
			<td><?=$transfer_id?></td>
			<td><?=$used_ID?></td>
			<td><?=$used_NAME?></td>
			<td><?=$used_date?></td>
		</tr> <?php
		$sr++;
	} ?>
</table> <?php
pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);

?>