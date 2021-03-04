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
<form method="post" action="index.php?page=wallet_info">
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
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
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
if(isset($_POST['Search'])){
	if($_POST['date'] != ''){
		$_SESSION['SESS_search_date'] = $date = date('Y-m-d', strtotime($_POST['date']));
		$qur_set_search = " WHERE t1.date = '$date' ";
	}
	
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " WHERE t1.id = '$search_id' ";
	}
}

if(isset($_POST['create_file']))
{
	$file_name = time()."Wallet Info".date('Y-m-d');
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM wallet t1 
	LEFT JOIN users t2 ON t1.id = t2.id_user
	$qur_set_search";	
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="Username \t Name \t Bonus Wallet \t Company Wallet \t Activation Wallet \t Date";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$username = $row['username'];
		$amount = $row['amount'];
		$companyw = $row['companyw'];
		$activationw = $row['amount'];
		$date = date('d/m/Y' , strtotime($row['date']));
		$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $amount.$sep;
		$insert .= $companyw.$sep;
		$insert .= $activationw.$sep;
		$insert .= $date.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	echo "<B style='color:#008000;'>Excel File Created Successfully !</B>";
	?>
	<p><a style="color:#333368; font-weight:600;" href="index.php?page=<?=$val?>">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a> <?php 
}
else
{
	$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM wallet t1 
	LEFT JOIN users t2 ON t1.id = t2.id_user
	$qur_set_search ORDER BY t1.amount DESC";	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT SUM(activationw) amt ,COUNT(id) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_amt = $ro['amt'];
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows != 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr><th colspan="6">Total Deposit Wallet : <?=$tot_amt; ?> &#36;</th></tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User Name</td>
				<th class="text-center">Name</td>
				<th class="text-center">Deposit Wallet</td>
				<th class="text-center">Date</td>
				<th class="text-center">Action</td>
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
				$member_id = $row['member_id'];
				$date = date('d/m/Y' , strtotime($row['date']));
				$amount = $row['activationw'];
				$username = $row['username'];
				$level = $row['level'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td>&#36;<?=$amount?></td>
					<td><?=$date?></td>
					<td>
						<form action="index.php?page=add_funds" method="post">
							<input type="hidden" name="username" value="<?=$username?>" />
							<input type="submit" name="edit" value="Add Balance" class="btn btn-success btn-xs" />
						</form><br />
						<form action="index.php?page=deduct_balance" method="post">
							<input type="hidden" name="username" value="<?=$username?>" />
							<input type="submit" name="edit" value="Deduct Balance" class="btn btn-danger btn-xs" />
						</form>
					</td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>