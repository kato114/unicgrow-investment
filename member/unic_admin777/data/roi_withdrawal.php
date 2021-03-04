<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	

	$sql = "SELECT SUM(t1.amount) tot_roi, SUM(t1.tax) tax, SUM(t1.tds_tax) tds_tax,t1.date, t2.username, t2.f_name, 
	t2.l_name,t3.name,t3.bank_ac,t3.bank,t3.ifsc ,t3.branch
	FROM income t1
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	LEFT JOIN kyc t3 ON t1.user_id = t3.user_id
	WHERE t1.type = 2 AND t3.mode_pan = 1 AND t3.mode_id = 1 AND t3.mode_photo = 1 AND t3.mode_chq = 1 
	$qur_set_search GROUP BY t1.user_id";
	
	$_SESSION['SQL_roi_withdraw'] = $sql;
	
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
	
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan="9" class="text-right">
				<form method="post" action="simple_view_roi.php" target="_blank"> 
					<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
				</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">KYC Name</th>
				<th class="text-center">Bank Details</th>
				<th class="text-center">ROI Amount</th>
				<th class="text-center">TDS</th>
				<th class="text-center">Admin Tax</th>
				<th class="text-center">Date</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil($totalrows/$plimit);
			if($newp == ''){ $newp = '1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$que = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($que))
			{
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$tot_roi = $row['tot_roi'];
				$tax = $row['tax'];
				$tds_tax = $row['tds_tax'];
				
				$date = date('d/m/Y', strtotime($row['date']));
				
				$benf_name = $row['name'];
				$ac_no = $row['bank_ac'];
				$bank = $row['bank'];
				$bank_code = $row['ifsc'];
				$branch = $row['branch'];
	
				
	
				$ac_info = "<B>Bank :</B> ".$bank."<br><B>Bank Ac :</B> ".$ac_no."<br><B>IFSC :</B> ".$bank_code."<br><B>Branch :</B> ".$branch;	
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$benf_name?></td>
					<td class="text-left"><?=$ac_info?></td>
					<td>&#36;<?=$tot_roi?></td>
					<td>&#36;<?=$tax?></td>
					<td>&#36;<?=$tds_tax?></td>
					<td><?=$date?></td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else { echo "<B class='text-danger'>There are no information to show !</B>"; }	
}
else{ ?>
	<form method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered">
		<tr>
			<td>
				<div id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" />
					</div>
				</div>
			</td>
			<td>
				<div id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" />
					</div>
				</div>
			</td>
			<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
		</tr>
	</table>
	</form>
	<?php
}
?>