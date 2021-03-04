<?php
session_start();
include('../../security_web_validation.php');
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['create_file']))
{
	$file_name = "investment".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['SQL_INVEST'];	
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="Username \t Name \t Amount \t Date";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$amount = $row['update_fees'];
		$date = date('d/m/Y' , strtotime($row['date']));
		
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $amount.$sep;
		$insert .= $date.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	echo "<B class='text-danger'>Excel File Created Successfully !</B>";
	unset($_SESSION['SQL_INVEST']);
	?>
	<p><a style="color:#333368; font-weight:600;" href="index.php?page=<?=$val?>">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a> <?php
}


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['start_date'] = $_SESSION['SESS_strt_date'];
	$_POST['end_date'] = $_SESSION['SESS_end_date'];
}

if(isset($_POST['search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_strt_date'] = $st_date = $_POST['start_date'];
	$_SESSION['SESS_end_date'] = $en_date = $_POST['end_date'];
	
	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " WHERE t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
	if($search_username !='' and $st_date !='' and $en_date != '')
	{		
		$qur_set_search = " WHERE t1.user_id = '$search_id' AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	
		
 	$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name,t2.position FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	$qur_set_search";	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$_SESSION['SQL_INVEST'] = $SQL;
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(t1.id) num FROM reg_fees_structure t1 $qur_set_search";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows != 0)
	{ 
		while($r1 = mysqli_fetch_array($query))
		{
			$tot_invest += $r1['update_fees'];
		} ?>
		<div class="row">
			<div class="col-md-12">
				<form action="" method="post">
					To Create Excel File 
					<input type="submit" name="create_file" value="Create Excel File" class="btn btn-warning"/>
				</form>
			</div>
		</div>
		<div class="row">&nbsp;</div>
		<table class="table table-bordered">
			<thead><tr><th colspan="7">Total Investment : <?=$tot_invest; ?> &#36;</th></tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Name</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Date</th>
				<th class="text-center">Left Business</th>
				<th class="text-center">Right Business</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			$query = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($query))
			{ 	
				$member_id = $row['user_id'];
				$date = date('d/m/Y' , strtotime($row['date']));
				$amount = $row['update_fees'];
				$username = $row['username'];
				$level = $row['level'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
				$position = $row['position'];
				
				if($position == 0) { $pos = 'Left'; }
				else { $pos = 'Right'; }
				
				$left_business = user_network_left_right_business($member_id,0);
				$right_business = user_network_left_right_business($member_id,1);
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td>&#36; <?=$amount?></td>
					<td><?=$date?></td>
					<td><?=$left_business?></td>
					<td><?=$right_business?></td>
					<!--<td><?=$cur_rank?></td>
					<td><img src="../images/<?=$img;?>" title="<?=$title?>" /></td>-->
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
else
{ ?> 
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="start_date" placeholder="Enter Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="end_date" placeholder="Enter End Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" name="search" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form> <?php  
}  ?>