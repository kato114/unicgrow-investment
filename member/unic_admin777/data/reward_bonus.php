<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$sql = "select * from plan_reward";
$query = query_execute_sqli($sql);
$i = 0;
while($rt = mysqli_fetch_array($query)){
	$preward[$i] = $rt['incentive'];
	$ptitle[$i] = $rt['title'];
	$prb[$i] = $rt['business'];
	$pid[$i] = $rt['id'];
	$i++;
}
mysqli_free_result($query);

if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_rwd_id']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['rwd_id'] = $_SESSION['SESS_rwd_id'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_rwd_id'] = $_POST['rwd_id'];
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	

	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search .= " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search .= " AND t1.user_id = '$search_id' ";
	}
	if($_POST['rwd_id'] != ""){
		$qur_set_search .= " AND t1.incomed_id = '".$_POST['rwd_id']."' ";
	}
}
if(isset($_POST['Excel']) and $_POST['Excel'] == "Download Excel"){
	$file_name = $_REQUEST['bonus_name'];
	$inc_type = $_REQUEST['inc_type'];
	$url = $_REQUEST['url'];
	
	$file_name = $file_name.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	/*$SQL = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM income t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.type = '$inc_type' ORDER BY t1.date DESC";*/	
	$SQL = $_SESSION['search_result'];
	$result = query_execute_sqli($SQL);              
	$insert_rows.=" \t Reward \t \t Reward Business \t Reward Business \t Current Business \t Current Business \t Date";
	$insert_rows.="\n";
	$insert_rows.="Username \t Destination \t Reward \t Left \t Right  \t Left \t Right\t Date";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$username = $row['username'];
		$incomed_id = $row['incomed_id'];
		$cb = get_cb($user_id);
		$cb_l = $cb[0]; 
		$cb_r = $cb[1];
		$rew = $preward[$incomed_id-1];
		$rb_l = $rb_r = $prb[$incomed_id-1];
		$date = date('d/m/Y' , strtotime($row['date']));
		
		
		$insert .= $username.$sep;
		$insert .= $ptitle[$incomed_id-1].$sep;
		$insert .= $rew.$sep;
		$insert .= $rb_l.$sep;
		$insert .= $rb_r.$sep;
		$insert .= $cb_l.$sep;
		$insert .= $cb_r.$sep;
		$insert .= $date.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['search_result']);
	?>
	<p><a class="btn btn-danger" href="index.php?page=<?=$url?>"><i class="fa fa-reply"></i> Back</a></p>
	<div class="alert alert-success"><B>Excel File Created Successfully !</B></div>
	
	Click here for download file <i class="fa fa-hand-o-right"></i>  <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>
	<?php
}



$sql= "select t1.user_id,max(t1.incomed_id) incomed_id,max(t1.date) date,t2.username  from income t1
		left join users t2 on t1.user_id = t2.id_user
		where t1.type=5 $qur_set_search group by user_id";
$_SESSION['search_result'] = $sql;
$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0){
	$pnums = ceil($totalrows/$plimit);
	if($newp == ''){ $newp = '1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	$sr_no = $starting_no;

	?>
	<table class="table table-bordered">
		<tr>
			<td>
				<form action="index.php?page=<?=$val?>" method="post">
					<input type="hidden" name="bonus_name" value="Reward Bonus" />
					<input type="hidden" name="inc_type" value="5" />
					<input type="hidden" name="url" value="<?=$val?>" />
					<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning" />
				</form>
			</td>
			<form method="post" action="index.php?page=<?=$val?>">
			<td>
				<input type="text" name="search_username" value="<?=$_POST['search_username']?>" placeholder="Search By Username" class="form-control" />
			</td>
			<td>
			<div class="form-group">
				<select name="rwd_id" class="form-control">
					<option value="">Select Destination</option>
					<?php
						for($i = 0; $i < count($pid); $i++){
						$sel = "";
						if($_POST['rwd_id'] == $pid[$i]){
							$sel = "selected='selected'";
						}
						?><option value="<?=$pid[$i]?>" <?=$sel?>><?=$ptitle[$i];?></option><?php	
						}
					?>
				</select>
			</div>
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
			<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
			</form>
		</tr>
	</table>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center" colspan="2">&nbsp;</th>
			<th class="text-center" colspan="2">Reward</th>
			<th class="text-center" colspan="2">Reward Business</th>
			<th class="text-center" colspan="2">Current Business</th>
			<th class="text-center">Status</th> 
		</tr>
		<tr>
			<th class="text-center">Sr.No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Destination </th>
			<th class="text-center">Reward</th>
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			<th>&nbsp;</th>
		</tr>
		</thead>
	<?php
	$que = query_execute_sqli("$sql LIMIT $start,$plimit");
	while($rt = mysqli_fetch_array($que)){
		$cb = array();
		$user_id = $rt['user_id'];
		$username = $rt['username'];
		$incomed_id = $rt['incomed_id'];
		$date = $rt['date'];
		$cb = get_cb($user_id);
		$cb_l = $cb[0]; 
		$cb_r = $cb[1];
		$rew = $preward[$incomed_id-1];
		$rb_l = $rb_r =0;
		for($k = 0; $k < $incomed_id; $k++){
			$rb_l = $rb_r += $prb[$k];
		}
		?>
		<tr class="text-center">
			<td><?=$sr_no;?></td>
			<td><?=$username?></td>
			<td><?=$ptitle[$incomed_id-1]?></td>
			<td><?=$rew?></td>
			<td><?=$rb_l?></td>
			<td><?=$rb_r?></td>
			<td><?=$cb_l?></td>
			<td><?=$cb_r?></td>
			<td><?=$date?></td>
		</tr>
		 <?php
		$sr_no++;
	}
	?>
	</table>
	<?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{
	echo "<p class=text-danger>There Have No Rewards Achiver !!</p>";
}
mysqli_free_result($query);

function get_cb($user_id){
	
	$sql = "select user_id,COALESCE(sum(`cf_left`),0) left_point,COALESCE(sum(`cf_right`),0) right_point
			from pair_point where user_id='$user_id'
			group by user_id";
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0){
		$row = mysqli_fetch_array($query);
		return array($row['left_point'],$row['right_point']);
	}
	else{
		return array(0,0);
	}
	return false;
}


	