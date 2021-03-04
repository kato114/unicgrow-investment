<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_srch_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['srch_date'] = $_SESSION['SESS_srch_date'];
}
if(isset($_POST['Search'])){

	if($_POST['date'] != ''){
		$_SESSION['SESS_srch_date'] = $srch_date = date('Y-m-d', strtotime($_POST['srch_date']));
		$qur_set_search = " AND date = '$srch_date' ";	
	}
	
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " AND id_user = '$search_id' AND id_user > 0";
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="srch_date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php
if(isset($_POST['excel']))
{
	$file_name = "Binary Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT * FROM users WHERE pair_lapse = 0 AND 
	((l_lps > 1 and r_lps >= 1) or (l_lps >= 1 and r_lps > 1)) AND step = 1
	$qur_set_search ORDER BY id_user DESC";	
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t Name \t E-mail \t Phone No. \t Date";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$date = $row['binary_qdate'];
		$email = $row['email'];
		$username = $row['username'];
		$phone_no = $row['phone_no'];
		$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $email.$sep;
		$insert .= $phone_no.$sep;
		$insert .= $date.$sep;
		
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

	//$sql = "SELECT * FROM users WHERE pair_lapse > 0 $qur_set_search ORDER BY id_user DESC";
	$sql = "SELECT * FROM users WHERE pair_lapse = 0 AND 
	((l_lps > 1 and r_lps >= 1) or (l_lps >= 1 and r_lps > 1)) AND step = 1
	$qur_set_search ORDER BY id_user DESC";		
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
				<th colspan="6"  class="text-right">
					<form action="" method="post">
						<input type="submit" name="excel" value="Download Excel" class="btn btn-warning btn-sm" />
					</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Name</th>
				<th class="text-center">E-mail</th>
				<th class="text-center">Phone No.</th>
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
				$date = date('d/m/Y' , strtotime($row['binary_qdate']));
				$email = $row['email'];
				$username = $row['username'];
				$phone_no = $row['phone_no'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td><?=$email?></td>
					<td><?=$phone_no?></td>
					<td><?=$date?></td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }	
}
?>