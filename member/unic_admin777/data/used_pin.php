<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>
<form method="post" action="index.php?page=used_pin">
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
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php
$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d', strtotime($_POST['search_date']));
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " AND user_id = '$search_id' ";
	}
	if($search_date != ''){
		$qur_set_search = "AND used_date = '$search_date' ";
	}
}
	

$sql = "SELECT * FROM e_pin WHERE mode = 0 $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM e_pin WHERE mode = 0 $qur_set_search";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">E-pin Type</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th>
			<th class="text-center">Used ID</th>
			<th class="text-center">Used Name</th>
			<th class="text-center">Used Date</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit");			
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row['user_id'];
			$epin = $row['epin'];
			$amount = $row['amount']; 	
			$date = date('d/m/Y', strtotime($row['date']));
			$used_id = $row['used_id'];	
			$epin_type = $row['epin_type'];
			$used_date = date('d/m/Y', strtotime($row['used_date']));
			
			
			if($epin_type == 0)
				$epin_type_status = "Registration E-pin";
			else
			{
				$qu = query_execute_sqli("select * from plan_setting where id = '$epin_type' ");
				while($rrr = mysqli_fetch_array($qu))
				{ 
					$epin_type_status = $rrr['plan_name'];
				}
			}
			
			$q1 = query_execute_sqli("select * from users where id_user = '$user_id' ");
			while($rrrrr = mysqli_fetch_array($q1))
			{
				$f_name = $rrrrr['f_name'];
				$l_name = $rrrrr['l_name'];
				$name = $f_name." ".$l_name;
				$user_name = $rrrrr['username'];
			}
			
			$qu2 = query_execute_sqli("select * from users where id_user = '$used_id' ");
			while($rrrrr = mysqli_fetch_array($qu2))
			{
				$f_name = $rrrrr['f_name'];
				$l_name = $rrrrr['l_name'];
				$used_name = $f_name." ".$l_name;
				$used_user_name = $rrrrr['username'];
			}	
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_name;?></td>
				<td><?=$name?></td>
				<td><?=$epin?></td>
				<td><?=$epin_type_status?></td>
				<td><?=$amount?> USD</td>
				<td><?=$date?></td>
				<td><?=$used_user_name?></td>
				<td><?=$used_name?></td>
				<td><?=$used_date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There is no Used E-pin to show !</B>";  }	
?>
