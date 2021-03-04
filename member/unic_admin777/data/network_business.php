<?php
include('../../security_web_validation.php');
session_start();
include("condition.php");

$newp = $_GET['p'];
$plimit = "15";
if(isset($_POST['Excel'])){
	$file_name = 'network_business';
	$url = $_REQUEST['url'];
	$poss = $_REQUEST['pos'];
	$power = $_REQUEST['power'];
	if($poss != ""){
		if($poss == 0)
			$file_name = "left_".$file_name;
		else
			$file_name = "right_".$file_name;
		
	}
	if($power != ""){
		if($poss == 0)
			$file_name = "wp_".$file_name;
		else{
			$file_name = "pi_".$file_name;
		}
	}
	$file_name = $file_name.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 

/*$SQL = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM income t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.type = '$inc_type' ORDER BY t1.date DESC";*/	
$SQL = $_SESSION['dnb_excel'];
$result = query_execute_sqli($SQL);              

$insert_rows.="User ID \t Topup Amount \t Date ";
$insert_rows.="\n";
fwrite($fp, $insert_rows);
while($row = mysqli_fetch_array($result))
{
	$insert = "";
	$date = date('d/m/Y' , strtotime($row['date']));
	$amount = round($row['update_fees'],5);
	$username = $row['username'];
	
	
	$insert .= $username.$sep;
	$insert .= $amount.$sep;
	$insert .= $date.$sep;
	
	$insert = str_replace($sep."$", "", $insert);
	
	$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
	$insert .= "\n";
	fwrite($fp, $insert);
}
fclose($fp);
echo "<B style='color:#008000;'>Excel File Created Successfully !</B>";
unset($_SESSION['search_result']);
?>
<p><a style="color:#333368; font-weight:600;" href="index.php?page=<?=$url?>">Back</a></p>
click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>
<?php
}
if(isset($_POST['username'])){
	$username = $_REQUEST['username'];
	$query = query_execute_sqli("SELECT * FROM users WHERE username = '$username'");
	$num = mysqli_num_rows($query);
	if($num != 0){
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row['id_user'];
		}
		$qur_set_search = '';
		if(count($_GET) == 1){
			unset($_SESSION['SESS_POS'],$_SESSION['SESS_st_date'],$_SESSION['SESS_en_date']);
		}
		else{
			$_POST['Search'] = '1';
			$_POST['search_pos'] = $_SESSION['SESS_POS'];
			$_POST['search_power'] = $_SESSION['SESS_POWER'];
		}
		if(isset($_POST['search_power']) and $_POST['search_power'] != ""){
			$_SESSION['SESS_POWER'] = $_POST['search_power'];
			if($_POST['search_power'] == 0)
				$qur_set_search = "  AND t1.plan NOT IN ('z')";
			if($_POST['search_power'] == 1)
			$qur_set_search = "  AND t1.plan IN ('z')";
		}
		if(isset($_POST['search_pos'])){
			$_SESSION['SESS_POS'] = $pos = $_POST['search_pos'];
			$sqls = "SELECT id_user FROM users WHERE parent_id = '$user_id'";
			if($_POST['search_pos'] != ''){
				$sqls = "SELECT id_user FROM users WHERE parent_id = '$user_id' AND position = $pos";
			}
			
			if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
				$_SESSION['SESS_st_date'] = $st_date = $_POST['st_date'];
				$_SESSION['SESS_en_date'] = $en_date = $_POST['en_date'];
				$qur_set_search .= "  AND t1.date >= '$st_date' AND t1.date <= '$en_date'";
			}
			
			$quer = query_execute_sqli($sqls);	
			$ro = mysqli_fetch_array($quer);
			$id_total = $ro[0];
			$seprt = "";
			if($id_total != "")$seprt = ",";
			$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0].$seprt.$id_total;
		}
		else{
			$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($user_id)"))[0];
		}
		?>
		
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
				<form method="post" action="index.php?page=network_business">
				<input type="hidden" name="username" value="<?=$username?>" />
					<!--<div class="col-md-2">
						<button name="search_pos" class="btn btn-info" value="0">My Left Group</button>
						<button name="search_pos" class="btn btn-info" value="1">My Right Group</button>
					</div>-->
					<div class="col-md-4">
						<select name="search_pos" class="form-control" onchange="this.form.submit()">
							<option value="">Select Position</option>
							<option value="0" <?php if($_POST['search_pos'] != ''){?> selected="selected" <?php } ?>>Left</option>
							<option value="1" <?php if($_POST['search_pos'] == 1){?> selected="selected" <?php } ?>>Right</option>
						</select>
					</div>
					<div class="col-md-4">
						<select name="search_power" class="form-control" onchange="this.form.submit()">
							<option value="">Select Power</option>
							<option value="0" <?php if($_POST['search_power'] != ''){?> selected="selected" <?php } ?>>Without Power</option>
							<option value="1" <?php if($_POST['search_power'] == 1){?> selected="selected" <?php } ?>>Power Investment</option>
						</select>
					</div>
				</form>
					<div class="col-md-4">
					<form action="index.php?page=network_business" method="post">
						<input type="hidden" name="url" value="network_business" />
						<input type="hidden" name="username" value="<?=$username?>" />
						<input type="hidden" name="pos" value="<?=$_POST['search_pos']?>" />
						<input type="hidden" name="power" value="<?=$_POST['search_power']?>" />
						<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning" />
					</form>	
					</div>
					
					<!--<div class="col-md-3">
						<input type="submit" value="Search" name="Search" class="btn btn-primary">
					</div>-->
				
				</div>
			</div>
		</div>
		
		<?php
		unset($_SESSION['dnb_excel']);
		$result = rtrim($result,",");
		$_SESSION['dnb_excel'] = $SQL = "SELECT t1.*,t2.position,t2.username FROM reg_fees_structure t1 
		INNER JOIN users t2 ON t1.user_id = t2.id_user
		WHERE t1.user_id IN ($result) AND t1.update_fees > 0 and t1.mode=1 $qur_set_search";
		$query = query_execute_sqli($SQL);
		$totalrows = mysqli_num_rows($query);
		if($totalrows != 0)
		{
			while($row1 = mysqli_fetch_array($query))
			{ $tatal_amt = $tatal_amt+$row1['update_fees']; } 
			?>
			<table class="table table-bordered table-hover">
				<thead>
					<tr><th colspan="4">Total Network Business : &#36; <?=round($tatal_amt,2);?></th></tr>
				</thead>
				<tr>
					<th class="text-center">S.No.</th>
					<th class="text-center">User ID</th>
					<th class="text-center">Topup Amount</th>
					<th class="text-center">Date</th>
					<!--<th class="text-center">Position</th>-->  
				</tr>
				<?php
				$pnums = ceil ($totalrows/$plimit);
				if ($newp==''){ $newp='1'; }
				$start = ($newp-1) * $plimit;
				$starting_no = $start + 1;
				
				$que = query_execute_sqli("$SQL");//LIMIT $start,$plimit
				$ss= 1;
				while($row = mysqli_fetch_array($que))
				{
					$date = date('d/m/Y' , strtotime($row['date']));
					$amount = round($row['update_fees'],5);
					$user_id = $row['username'];
					$position = $row['position'];
					
					if($position == 0) { $pos = 'Left'; }
					else { $pos = 'Right'; }
					?>
					<tr class="text-center">
						<td><?=$ss?></td>
						<td><?=$user_id?></td>
						<td>&#36; <?=$amount?></td>
						<td><?=$date?></td>
						<!--<td><?=$pos?></td>-->
					</tr> <?php
					$ss++;
				}
				?>
			</table> <?php 
			//pagging_initation($newp,$pnums,$val);
		}		
		else{ echo "<B style='color:#FF0000;'>There are no information to show !!</B>";  }
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Username !</B>"; }	
}
else{
?> 
<form action="" method="post">
<table class="table table-bordered">
	<!--<tr><th colspan="2">Wallet Information</th></tr>-->
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="username" class="form-control"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
<?php 
}
?>
