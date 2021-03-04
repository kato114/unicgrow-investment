<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME'],$_SESSION['SESS_member_roi']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['member_roi'] = $_SESSION['SESS_member_roi'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
		$u_id = get_new_user_id($search_username);
		$qur_set_search = " and user_id = '$u_id' ";
	}
	if($_POST['member_roi'] !=''){
		$_SESSION['SESS_member_roi'] = $_POST['member_roi'];
		if($_POST['member_roi'] == 0)
			$qur_set_search .= " and mode = '189' ";
		else
			$qur_set_search .= " and mode = '1' ";
	}
	
}

elseif(isset($_POST['stop']))
{
	$paid_id = $_POST['paid_id'];
	$date = date('Y-m-d', strtotime($systems_date.'-1 day'));
	query_execute_sqli("update reg_fees_structure set mode = 189 where user_id = '$paid_id' and mode=1 order by id desc limit 1");
	$sql = "insert into ledger set user_id='$paid_id',particular='Stop Roi By Mode 189',	
	date_time='$systems_date_time',balance='189'";
	query_execute_sqli($sql);
	?> <script>alert('ROI Stop Successfully !!'); window.location = "index.php?page=<?=$val?>";</script> <?php
}
elseif(isset($_POST['start']))
{
	$paid_id = $_POST['paid_id'];
	$date = date('Y-m-d', strtotime($systems_date.'-1 day'));
	query_execute_sqli("update reg_fees_structure set mode = 1 where user_id = '$paid_id' and mode in(189,199) order by id desc limit 1");
	$sql = "insert into ledger set user_id='$paid_id',particular='Start Roi By Mode 1',	
	date_time='$systems_date_time',balance='1'";
	query_execute_sqli($sql);
	?> <script>alert('ROI Start Successfully !!'); window.location = "index.php?page=<?=$val?>";</script> <?php
}
$sel = "selected=selected";
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" value="<?=$_POST['search_username'];?>" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<select name="member_roi" class="form-control">
				<option value="">Select Member</option>
				<option value="0" <?=($_POST['member_roi'] == 0 && $_POST['member_roi'] != "" && isset($_POST['member_roi'])) ? $sel : "";?>>Member For START</option>
				<option value="1" <?=($_POST['member_roi'] == 1 && $_POST['member_roi'] != "" && isset($_POST['member_roi'])) ? $sel : "";?>>Member For STOP</option>
			</select>
		</td>
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php	
if(isset($_POST['excel']))
{
	$file_name = "Stop-ROI Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$sql = "SELECT user_id FROM reg_fees_structure 
	WHERE id IN (SELECT max(id) FROM reg_fees_structure WHERE id > 0 $qur_set_search GROUP BY user_id)";
	$SQL = "SELECT * FROM users WHERE id_user IN($sql)";
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t Name \t E-mail \t Phone No.";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id_user'];
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$email = $row['email'];
		$phone = $row['phone_no'];
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $email.$sep;
		$insert .= $phone.$sep;
		
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
else{

	$id = $_SESSION['id'];
	$date = $systems_date;
		
	$sql = "SELECT user_id FROM reg_fees_structure 
	WHERE id IN (SELECT max(id) FROM reg_fees_structure WHERE id > 0 $qur_set_search GROUP BY user_id)";
	$sql = "SELECT * FROM users WHERE id_user IN($sql)";
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
	
	if($totalrows > 0)
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
				<th class="text-center">Username</th>
				<th class="text-center">Name</th>
				<th class="text-center">E-mail</th>
				<th class="text-center">Phone</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			
			$query = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($query)){
				$id = $row['id_user'];
				$username = $row['username'];
				$name = $row['f_name']." ".$row['l_name'];
				$bank_code = $row['bank_code'];
				$email = $row['email'];
				$phone = $row['phone_no'];
				$bank = $row['bank'];
				$ac_no = $row['ac_no'];
				$branch = $row['branch'];
				$beneficiery_name = $row['beneficiery_name'];
				$btn = "<input type=\"submit\" name=\"stop\" value=\"Stop\" class=\"btn btn-danger\">";
				if(chek_roi_status($id)){
					$btn = "<input type=\"submit\" name=\"start\" value=\"Start\" class=\"btn btn-info\">";
				}
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$email?></td>
					<td><?=$phone?></td>
					<td>
						<form method="post" action="">
							<input type="hidden" name="paid_id" value="<?=$id?>">
							<input type="hidden" name="inc_date" value="<?=$inc_date?>">
							<?=$btn;?>
						</form>
					</td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There is No User to Show !</B>";  }
}	
function chek_roi_status($user_id){
	$sql = "SELECT * from reg_fees_structure where user_id=$user_id order by id desc limit 1 ";
	$query = query_execute_sqli("$sql");
	while($row = mysqli_fetch_array($query)){
		if($row['mode']== 1)
			return false;
		else
			return true;
	}
}
?>
