<?php
include('../../security_web_validation.php');

include("../function/functions.php");
include("../function/setting.php");
$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_business_amount'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['business_amount'] = $_SESSION['SESS_business_amount'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	$_SESSION['SESS_business_amount'] = $business_amount = $_POST['business_amount'];
	
	
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
		$st_date = date('m/d/Y', strtotime($st_date));
		$en_date = date('m/d/Y', strtotime($en_date));
	}
	if($business_amount !=''){
		$qur_set_search .= " group by user_id having total_business >= '$business_amount' ";
	}
}

?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="business_amount" placeholder="Business" class="form-control" value="<?=$business_amount?>" required /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" value="<?=$st_date?>" required />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" value="<?=$en_date?>" required />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	

<?php
$date =  date("Y-m-d",strtotime($systems_date." -1 DAY"));

$sql = "SELECT user_id,sum(total_business*$set_binary_percent[0]) total_business FROM pair_point 
			  WHERE id > 0 $qur_set_search ORDER BY user_id asc";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(user_id) num FROM ($sql) t";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0 and isset($_POST['Search']))
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Total Business</th>
		</tr>
		</thead>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
		while($r = mysqli_fetch_array($query))
		{
			$date = date('d/m/Y', strtotime($r['date']));
			$user_id = get_user_name($r['user_id']);
			$total_business = $r['total_business'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td>&#36;<?=$total_business?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else { echo "<B class='text-danger'>There are no information to show !!</B>"; }	
?>