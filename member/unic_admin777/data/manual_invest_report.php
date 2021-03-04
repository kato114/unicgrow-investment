<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_st_date'],$_SESSION['SESS_en_date']);
}
if(isset($_POST['Search'])){
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_userid'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
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
		<td><input type="text" name="search_username" placeholder="Search By User ID" class="form-control" /></td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info btn-sm"></td>
	</tr>
</table>
</form>

<?php
if(isset($_POST['excel']))
{
	$file_name = "Manual-Investment Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.*, t2.username,t2.f_name,t2.l_name FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.plan = 'x' $qur_set_search ORDER BY date DESC";
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t Name \t Date \t Investment \t Remarks";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$date = $row['date'];
		$remarks = $row['remarks'];
		$update_fees = $row['request_crowd'];
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $date.$sep;
		$insert .= $update_fees.$sep;
		$insert .= "Investment By Admin".$sep;
		
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
	$sql = "SELECT t1.*, t2.username,t2.f_name,t2.l_name FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.plan = 'x' $qur_set_search ORDER BY date DESC";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	
	
	$sqlk = "SELECT COALESCE(SUM(t1.request_crowd),0) amt,COUNT(*) num FROM ($sql) t1 ";
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
					Total Investment : &#36;<?=$tot_invest; ?>
					<div class="pull-right">
						<form action="" method="post">
						<input type="submit" name="excel" value="Download Excel" class="btn btn-warning btn-sm" />
						</form>
					</div>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Name</th>
				<th class="text-center">Date</th>
				<th class="text-center">Investment</th>
				<th class="text-center">Remarks</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$quer = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($r = mysqli_fetch_array($quer))
			{
				$reg_fees_id = $r['id'];
				$date = $r['date'];
				$user_ids = $r['user_id'];
				$remarks = $r['remarks'];
				$update_fees = $r['request_crowd'];
				$usernames = $r['username'];
				$name = ucwords($r['f_name']." ".$r['l_name']);	
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$usernames?></td>
					<td><?=$name?></td>
					<td><?=$date?></td>
					<td>&#36;<?=$update_fees?></td>
					<td>Investment By Admin</td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
	}
	else{ echo "<B class='text-danger'>No Investment Found !</B>";  }	
}
?>
