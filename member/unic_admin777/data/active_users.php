<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_plan_id'] = $plan_id = $_POST['plan_id'];
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($plan_id !=''){
		$qur_set_search = " WHERE t1.invest_type = '$plan_id' ";
	}
	if($_POST['search_username'] !=''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<select name="plan_id" class="form-control">
				<option value="">Search By Package</option>
				<?php
				$qu = query_execute_sqli("SELECT * FROM plan_setting");
				while($rrr = mysqli_fetch_array($qu))
				{ 
					$plan_name = $rrr['plan_name'];
					$plan_id = $rrr['id'];
					$amount = $rrr['amount'];
					?> <option value="<?=$plan_id; ?>"><?=$plan_name?> (<?=$amount?> &#36;)</option> <?php	
				} ?>		
			</select>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name,COUNT(t1.user_id) cnt_num FROM reg_fees_structure t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
$qur_set_search 
GROUP BY t1.user_id
ORDER BY date DESC ";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COALESCE(SUM(t1.update_fees),0) amt,COUNT(t1.id) num FROM ($sql) t1 ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$tot_invest = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="11">Total Investment : <?=$tot_invest; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Date</th>
			<th class="text-center">Investment</th>
			<th class="text-center">Investment Count</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$user_query_lmt = query_execute_sqli("$sql LIMIT $start,$plimit");
		$total_investment = 0;
		while($r = mysqli_fetch_array($user_query_lmt))
		{
			$reg_fees_id = $r['id'];
			$date = $r['date'];
			$phone_no = $r['phone_no'];
			$email = $r['email'];
			$name = ucwords($r['f_name']." ".$r['f_name']);
			$username = $r['username'];
			$update_fees = $r['update_fees'];
			$cnt_num = $r['cnt_num'];
			$num_inv = "<span class='label label-danger'>$cnt_num</span>";
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$date?></td>
				<td><?=$update_fees?> &#36;</td>
				<td><?=$num_inv?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}
else{ echo "<B class='text-danger'>No Investment Found !</B>";  }	

?>
