<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");


/*if($_REQUEST['page'] == 're_investment'){
	echo "Coming Soon";  // Code and condition for coming soon page remove if and else code for page run
}
else{*/

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_user_id'] = $_SESSION['SESS_search_user_id'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
else{
	unset($_SESSION['SESS_search_user_id'],$_SESSION['SESS_st_date'],$_SESSION['SESS_en_date']);
}
if(isset($_POST['Search'])){
	if($_POST['search_user_id'] !=''){
		$_SESSION['SESS_search_user_id'] = $search_user_id = $_POST['search_user_id'];
		$search_id = get_new_user_id($search_user_id);
		$qur_set_search = " AND t1.user_id = '$search_id' AND t1.user_id > 0";
	}
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		
		$qur_set_search = " AND t1.date >= '$st_date' AND t1.date <= '$en_date' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">	
<table class="table table-bordered">
	<tr>
		<td>
			<div id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="From Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="To Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="text" name="search_user_id" placeholder="Search By User ID" class="form-control" /></td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info btn-sm"></td>
	</tr>
</table>
</form>

<?php
if(isset($_POST['Excel']))
{
	$file_name = "Re-investment Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.*,t2.username , t2.f_name , t2.l_name FROM reg_fees_structure t1
	INNER JOIN (
	SELECT min(id) min_id,user_id,COUNT(*) FROM `reg_fees_structure` 
	WHERE `level` = 0 GROUP BY user_id HAVING COUNT(*) > 1) t2
	ON t1.user_id = t2.user_id AND t1.id > t2.min_id
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.level = 0 $qur_set_search
	ORDER BY t1.date DESC ";
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t Name \t Date \t Investment \t Profit(%)";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$date = $row['date'];
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$profit = $row['profit'];
		$update_fees = $row['request_crowd'];
		$mode = $row['mode'];
		
		if($mode == 0){ $status = "Pending"; }
		else{ $status = "Confirm"; }	
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $date.$sep;
		$insert .= $update_fees.$sep;
		$insert .= $profit.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click here for download file =</B> <a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a>
	
	 <?php
}
else
{
	/*$SQL = "SELECT T1.*, COUNT(T1.user_id) , T2.username , T2.email , T2.phone_no FROM reg_fees_structure T1
	LEFT JOIN users T2 ON T1.user_id = T2.id_user where T1.invest_type = 1
	GROUP BY T1.user_id
	HAVING COUNT(T1.user_id) > 1
	ORDER BY T1.date  ";*/
	$sql = "SELECT t1.*,t2.username , t2.f_name , t2.l_name FROM reg_fees_structure t1
	INNER JOIN (
	SELECT min(id) min_id,user_id,COUNT(*) FROM `reg_fees_structure` 
	WHERE `level` = 0 GROUP BY user_id HAVING COUNT(*) > 1) t2
	ON t1.user_id = t2.user_id AND t1.id > t2.min_id
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.level = 0 $qur_set_search
	ORDER BY t1.date DESC ";
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$querys = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($querys);
	
	$sqlk = "SELECT COALESCE(SUM(request_crowd),0) amt,COUNT(*) num FROM ($sql) t1 ";
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
			<tr>
				<th colspan="6">
					Total Investment : <?=$tot_invest; ?> &#36; 
					<div class="pull-right">
						<form action="" method="post">
						<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning btn-sm" />
						</form>
					</div>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Date</th>
				<th class="text-center">Investment</th>
				<th class="text-center">Profit (%)</th>
				<!--<th class="text-center">Total Days</th>
				<th class="text-center">Status</th>-->
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
			$total_investment = 0;
			while($r = mysqli_fetch_array($query))
			{
				$date = date('d/m/Y', strtotime($r['date']));
				$user_ids = $r['user_id'];
				$username = $r['username'];
				$name = ucwords($r['f_name']." ".$r['l_name']);
				$phone_no = $r['phone_no'];					
				$profit = $r['profit'];
				$total_days = $r['total_days'];
				$reg_fees = $r['reg_fees'];
				$update_fees = $r['request_crowd'];
				$hash_code = $r['hash_code'];
				$mode = $r['mode'];
				
				/*$reinv_num = reinvestment_is_or_not($user_ids);
				if($reinv_num > 1){ $inv_type = "<span style='color:#9C2004'>Reinvestment</span>"; }
				else{ $inv_type = "Investment"; }
				if($update_fees == 0) $amount = $reg_fees;
				else $amount = $update_fees;*/
				
				if($mode == 0){ $status = "<span class='label label-warning'>Pending</span>"; }
				else{ $status = "<span class='label label-primary'>Confirm</span>"; }	
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$date?></td>
					<td><?=$update_fees?> &#36; </td>
					<td><?=$profit?></td>
					<!--<td><?=$total_days?></td>
					<td><?=$status?></td>-->
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
	}
	else{ echo "<B class='text-danger'>There are no Investment to show!</B>";  }
}
//}
?>