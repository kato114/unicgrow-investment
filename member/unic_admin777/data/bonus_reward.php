<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "50";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['rxd_id'] = $_SESSION['SESS_rxd_id'];
	$_POST['reward'] = $_SESSION['SESS_reward'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_rxd_id'],$_SESSION['SESS_reward']);
}

if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
		
	$_SESSION['SESS_rxd_id'] = $rxd_id = $_POST['rxd_id'];
	$_SESSION['SESS_reward'] = $reward = $_POST['reward'];

	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	else{
		$_POST['st_date'] = ''; $_POST['en_date'] = '';
	}
	if($search_username !=''){
		$qur_set_search .= " AND t1.user_id = '$search_id' ";
	}
	if($_POST['rxd_id'] !=''){
		$qur_set_search .= " AND t1.incomed_id = '$rxd_id' ";
	}
	
	if($_POST['reward'] !=''){
		$qur_set_search .= " AND t1.incomed_id = '$reward' ";
	}
}
?>
<table class="table table-bordered">
	<tr>
		<!--<td>
			<form action="index.php?page=excel_bonus" method="post">
				<input type="hidden" name="bonus_name" value="Reward Income" />
				<input type="hidden" name="inc_type" value="5" />
				<input type="hidden" name="url" value="<?=$val?>" />
				<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning" />
			</form>
		</td>-->
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_username" value="<?=$_POST['search_username']?>" placeholder="Search By Username" class="form-control" />
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="End Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group">
				<select name="reward" class="form-control">
					<option value="">Select Reward</option>
					<?php
						$sql = "select * from plan_reward";
						$query = query_execute_sqli($sql);
						while($rt = mysqli_fetch_array($query)){
						$sel = "";
						if($_POST['rxd_ids'] == $rt['id']){
							$sel = "selected='selected'";
						}
						?><option value="<?=$rt['id']?>" <?=$sel?>><?=$rt['incentive'];?></option><?php	
						}
					?>
				</select>
			</div>
		</td>
		<td>
			<div class="form-group">
				<select name="rxd_id" class="form-control">
					<option value="">Select Destination</option>
					<?php
						$sql = "select * from plan_reward";
						$query = query_execute_sqli($sql);
						while($rt = mysqli_fetch_array($query)){
						$sel = "";
						if($_POST['rxd_id'] == $rt['id']){
							$sel = "selected='selected'";
						}
						?><option value="<?=$rt['id']?>" <?=$sel?>><?=$rt['title'];?></option><?php	
						}
					?>
				</select>
			</div>
		</td>
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
		</form>
	</tr>
</table>

<?php
if(isset($_POST['Excel']))
{
	$file_name = "Reward-Designation Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.*,t2.username,t2.f_name,t2.l_name, t3.incentive reward ,t3.title designation
	FROM income t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	LEFT JOIN plan_reward t3 ON t1.incomed_id = t3.id
	WHERE t1.type = 5 $qur_set_search ORDER BY t1.date DESC";
	$result = query_execute_sqli($SQL);              
	
	
	$insert_rows.="User ID \t Name \t Designation \t Left Business \t Right Business \t Reward \t Remarks";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id'];
		$member_id = $row['user_id'];
		$date = date('d/m/Y' , strtotime($row['date']));
		$designation = $row['designation'];
		$reward = $row['reward'];
		$mode = $row['mode'];
		$username = $row['username'];
		$level = $row['level'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		
		
		$lr_business = get_network_lr_business($member_id);
		$left_bus = $lr_business[0];
		$right_bus = $lr_business[1];
		
		//$cur_rank = get_user_current_rank($member_id);
		$remarks = get_user_remarks($member_id, $id);
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $designation.$sep;
		$insert .= $left_bus.$sep;
		$insert .= $right_bus.$sep;
		$insert .= $reward.$sep;
		$insert .= $remarks.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{

	$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name, t3.incentive reward ,t3.title designation
	FROM income t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	LEFT JOIN plan_reward t3 ON t1.incomed_id = t3.id
	WHERE t1.type = 5 $qur_set_search ORDER BY t1.date DESC";	
	
	$_SESSION['search_result'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$sqlk = "SELECT COUNT(id) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$amount = $ro['amt'];
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<!--<tr><th colspan="6">Total Reward Bonus : <?=$amount; ?> &#36;</th></tr>-->
			<tr>
				<th class="text-right" colspan="9">
					<form action="" method="post">
					<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning btn-sm" />
					</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</td>
				<th class="text-center">Name</td>
				<th class="text-center">Designation</td>
				<th class="text-center">Left Business</td>
				<th class="text-center">Right Business</td>
				<th class="text-center">Reward</td>
				<th class="text-center">Date</td>
				<th class="text-center">Remarks</td>
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
				$id = $row['id'];
				$member_id = $row['user_id'];
				$date = date('d/m/Y' , strtotime($row['date']));
				$designation = $row['designation'];
				$reward = $row['reward'];
				$mode = $row['mode'];
				$username = $row['username'];
				$level = $row['level'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
				
				
				$lr_business = get_network_lr_business($member_id);
				$left_bus = $lr_business[0];
				$right_bus = $lr_business[1];
				
				//$cur_rank = get_user_current_rank($member_id);
				$remarks = get_user_remarks($member_id, $id);
					
				$img = 'close.png';
				$title = "Unconfirmed";
					
				if($remarks == ''){
					//$img = 'yes.png';
					//$title = "Confirmed";
					$remarks = "<form action='index.php?page=reward_remarks' method='post'>
							<input type='hidden' name='income_id' value='$id' />
							<input type='hidden' name='user_id' value='$member_id' />
							<input type='hidden' name='username' value='$username' />
							<input type='hidden' name='name' value='$name' />
							<input type='hidden' name='designation' value='$designation' />
							<input type='hidden' name='reward' value='$reward' />
							<input type='hidden' name='date' value='$date' />
							<input type='submit' name='submit' value='Add Remarks' class='btn btn-danger btn-xs' />
						</form>";
				} ?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td><?=$designation?></td>
					<td>&#36;<?=$left_bus?></td>
					<td>&#36;<?=$right_bus?></td>
					<td><?=$reward?></td>
					<td><?=$date?></td>
					<td><?=$remarks?></td>
					<!--<td><img src="../images/<?=$img;?>" title="<?=$title?>" /></td>-->
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}

function get_user_remarks($user_id, $income_id){
	$sql = "SELECT remarks FROM rewards_remarks WHERE user_id = '$user_id' AND income_id = '$income_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
?>