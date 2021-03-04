<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>

<form method="post" action="index.php?page=booster_income">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_search_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['date'] = $_SESSION['SESS_search_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['date'] != '')
	$_SESSION['SESS_search_date'] = $date = date('Y-m-d', strtotime($_POST['date']));
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];	
	
	$search_id = get_new_user_id($search_username);
	
	if($date !=''){
		$qur_set_search = " AND t1.date = '$date' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND t1.member_id = '$search_id' ";
	}
}

$sql = "SELECT t1.*,COUNT(t1.investment_id) inv_boost ,t2.username,t2.f_name,t2.l_name FROM daily_interest t1 
LEFT JOIN users t2 ON t1.member_id = t2.id_user
WHERE t1.level = 0 $qur_set_search 
GROUP BY t1.investment_id 
HAVING inv_boost > 1";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM daily_interest t1 WHERE t1.level = 0 $qur_set_search ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">User Name</td>
			<th class="text-center">Amount</td>
			<th class="text-center">Date</td>
			<th class="text-center">Status</td>
		</tr>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$member_id = $row['member_id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = $row['amount'];
			$mode = $row['mode'];
			$username = $row['username'];
			$level = $row['level'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			
			if($mode == 0)
			{
				$img = 'yes.png';
				$title = "Confirmed";
			}
			else
			{
				$img = 'close.png';
				$title = "Unconfirmed";
			} ?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$amount?></td>
				<td><?=$date?></td>
				<td><img src="../images/<?=$img;?>" title="<?=$title?>" /></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }	
?>