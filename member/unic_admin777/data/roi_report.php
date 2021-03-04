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
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	

	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
	
	<div class="col-md-3">		
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger" />
		</form>
	</div>
	<div class="col-md-3 col-md-offset-4">
		<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
	</div>
	<!--<div class="col-md-3">		
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="en_date" placeholder="End Date" class="form-control" />
			</div>
		</div>
	</div>-->
	<div class="col-md-2 text-right"><input type="submit" value="Search" name="Search" class="btn btn-info"></div>
</form>
<div class="col-md-12">&nbsp;</div>
<?php
if(isset($_POST['create_file']))
{
	$file_name = 'ROI_Report'.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM reg_fees_structure t1
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.mode = 1 AND t1.profit > 0 $qur_set_search";
	$result = query_execute_sqli($sql);              
	
	$insert_rows.="User ID \t Username \t Total Confirm ROI \t Total Future ROI ";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$profit = $row['profit'];
		$total_days = $row['total_days'];
		$username = $row['username'];
		$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);

		$confirm_roi = get_confirm_roi($user_id);
		$future_roi = $profit*$total_days-$confirm_roi;
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $confirm_roi.$sep;
		$insert .= $future_roi.$sep;
		
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
	$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM reg_fees_structure t1
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.mode = 1 AND t1.profit > 0 $qur_set_search";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$amount = $ro['amt'];
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	if($totalrows > 0)
	{ ?>	
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Total Confirm ROI</th>
				<th class="text-center">Total Future ROI</th>
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
				$profit = $row['profit'];
				$total_days = $row['total_days'];
				$username = $row['username'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
		
				$confirm_roi = get_confirm_roi($user_id);
				$future_roi = $profit*$total_days-$confirm_roi;
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td>&#36;<?=$confirm_roi?></td>
					<td>&#36;<?=$future_roi?></td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>