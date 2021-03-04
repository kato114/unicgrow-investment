<?php
include('../../security_web_validation.php');

include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$ac_type = "ac_type = 1";
$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
else{
	unset($_SESSION['SESS_st_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_date_giv'],$_SESSION['SESS_ac_type']);
}

if(isset($_POST['search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND date BETWEEN '$st_date%' AND '$en_date%' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<!--<td><input type="text" name="search_username" placeholder="  By Username" class="form-control" /></td>-->
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
/*for($i = 0; $i < count($closing_roi_payout); $i++){
	$date_format[] = "DATE_FORMAT(date, '%d') = '$closing_roi_payout[$i]'";
}
$date_format1 = implode(" OR ",$date_format);
$date_format = $date_format1;*/


$closing_roi_payout = array();
$closing_roi_payout = get_withdrawal_day();
for($i = 0; $i < count($closing_roi_payout); $i++){
	$date_format[] = "DATE_FORMAT(date, '%d') = '$closing_roi_payout[$i]'";
}
$date_format1 = implode(" OR ",$date_format);
$date_format = $date_format1;

$sql = "SELECT t1.*,COALESCE(SUM(t2.amount),0) pend_amt, 
net_amt+tot_tds+tot_admtax tot_amt FROM 
(
	SELECT COUNT(user_id) tot_id,SUM(amount) net_amt, SUM(0) tot_tds,SUM(0) tot_admtax,
	DATE_FORMAT(date,'%Y-%m-%d') `date`
	FROM withdrawal_crown_wallet 
	WHERE ($date_format) 
	$qur_set_search GROUP BY DATE_FORMAT(date,'%Y-%m-%d')
) t1 
LEFT JOIN withdrawal_crown_wallet t2 ON t1.date = DATE_FORMAT(t2.date,'%Y-%m-%d') AND t2.status = 65
GROUP BY DATE_FORMAT(t1.date,'%Y-%m-%d') , DATE_FORMAT(t2.date,'%Y-%m-%d')
order by t1.date desc";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Closing Date</th>
			<th class="text-center">Total Amount</th>
			<!--<th class="text-center">TDS</th>
			<th class="text-center">Admin Tax</th>
			<th class="text-center">Net Payble Amount</th>-->
			<th class="text-center">Total ID's</th>
			<th class="text-center">Pending Amount</td>
			<th class="text-center">Action</td>
		</tr>
		</thead>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
		while($row = mysqli_fetch_array($query)){
			$user_id = get_user_name($row['user_id']);
			$tot_amt = $row['tot_amt'];

			$tot_tds = $row['tot_tds'];
			$adm_tax = $row['tot_admtax'];
			$net_amt = $row['net_amt'];
			$tds = $row['tax'];
			$adm_tax = $row['tot_admtax'];
			$tot_id = $row['tot_id'];
			$pend_amt = $row['pend_amt'];
			$date = date('Y-m-d', strtotime($row['date']));
			$date1 = date('d/m/Y', strtotime($row['date']));
						
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$date1?></td>
				<td><?=round($tot_amt,4)?>&#36;</td>
				<!--<td><?=round($tot_tds,4)?>&#36;</td>
				<td><?=round($adm_tax,4)?>&#36;</td>
				<td><?=round($net_amt,4)?>&#36;</td>--> 
				<td><?=$tot_id?></td>
				<td><?=round($pend_amt,4)?>&#36;</td>
				<td>
					<form method="post" action="index.php?page=withdrawal_history" target="_blank">
						<input type="hidden" name="date_giv" value="<?=$date?>" />
						<input type="hidden" name="ac_type" value="1" />
						<input type="submit" name="more" value="More" class="btn btn-danger btn-sm" />
					</form>
				</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>