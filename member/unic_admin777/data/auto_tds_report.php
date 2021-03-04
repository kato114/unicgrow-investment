<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_st_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_pan']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pan'] = $_SESSION['SESS_search_pan'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = "  WHERE DATE(t1.date) >= '$st_date' AND DATE(t1.date) <= '$en_date'";
	}
	
	if($_POST['search_username'] != ''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger" />
			</form>
		</td>
		
		<!--<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" />
				</div>
			</div>
		</td>-->
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
		
	</tr>
</form>
</table>
<?php
if(isset($_POST['create_file']))
{
	$file_name = 'Auto-TDS_Report'.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.user_id, SUM(t1.request_crowd) request_crowd,t2.username,t2.f_name,t2.l_name 
	FROM withdrawal_crown_wallet t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user $qur_set_search
	GROUP BY t1.user_id ORDER BY t1.date DESC";
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t Name \t Withdrawal Amount \t ROI Received \t Rest Amount \t TDS"; 
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$amount = $row['request_crowd'];
		$pan_no = $row['pan_no'];
		
		$tot_amt = $amount*100/(100-($withdrwal_money_tax+$admin_tax));
		
		$receive_roi = get_confirm_roi($user_id);
		$rest_amt = $amount - $receive_roi;
		
		$tds = 0;
		if($rest_amt > 0){
			$tds = $rest_amt*5/100;
		}
		
		$adm_tax = $rest_amt*$admin_tax/100;
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $amount.$sep;
		$insert .= $receive_roi.$sep;
		$insert .= $rest_amt.$sep;
		$insert .= $tds.$sep;
		//$insert .= $net_amt.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{

	$sql = "SELECT t1.user_id, SUM(t1.request_crowd) request_crowd,t2.username,t2.f_name,t2.l_name 
	FROM withdrawal_crown_wallet t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user $qur_set_search
	GROUP BY t1.user_id ORDER BY t1.date DESC";
	$_SESSION['SQL_auto_tds'] = $sql;
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$totalrows = $tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan="7" class="text-right">
					<form method="post" action="simple_view_auto_tds.php" target="_blank"> 
						<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
					</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Name</th>
				<th class="text-center">Withdrawal Amount</th>
				<th class="text-center">ROI Received</th>
				<th class="text-center">Rest Amount</th>
				<th class="text-center">TDS</th>
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
				$user_id = $row['user_id'];
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$amount = $row['request_crowd'];
				$pan_no = $row['pan_no'];
				
				$tot_amt = $amount*100/(100-($withdrwal_money_tax+$admin_tax));
				
				$receive_roi = get_confirm_roi($user_id);
				$rest_amt = $amount - $receive_roi;
				
				$tds = 0;
				if($rest_amt > 0){
					$tds = $rest_amt*5/100;
				}
				$adm_tax = $rest_amt*$admin_tax/100;
				?>	
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td>&#36;<?=round($amount,2)?></td>
					<td>&#36;<?=round($receive_roi,2)?></td>
					<td>&#36;<?=round($rest_amt,2)?></td>
					<td>&#36;<?=round($tds,2)?></td>
					<!--<td>&#36;<?=round($net_amt,2)?></td>-->
				</tr> <?php
				$sr_no++;
				
			} ?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>

