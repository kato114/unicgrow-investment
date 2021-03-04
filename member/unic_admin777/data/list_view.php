<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;



$qur_set_search = $qur_status_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_search_username'],$_SESSION['SESS_status_check'],$_SESSION['SESS_search_pack']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
	$_POST['search_userid'] = $_SESSION['SESS_search_username'];
	$_POST['status_check'] = $_SESSION['SESS_status_check'];
	$_POST['search_pack'] = $_SESSION['SESS_search_pack'];
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<input type="text" name="search_userid" placeholder="Enter User ID" class="form-control" value="<?=$_POST['search_userid']?>" required />
		</td>
		<td>
			<select name="status_check" class="form-control" required>
				<option value="">Select Status</option>
				<option value="1" <?php if($_POST['status_check'] == 1){?> selected="selected" <?php } ?>>
					Activate
				</option>
				<option value="2" <?php if($_POST['status_check'] == 2){?> selected="selected" <?php } ?>>
					Inactive
				</option>
				<option value="3" <?php if($_POST['status_check'] == 3){?> selected="selected" <?php } ?>>
					Blocked
				</option>
			</select>
		</td>
		<td>
			<select name="search_pos" class="form-control" required>
				<option value="">Select Position</option>
				<option value="0" <?php if($_POST['search_pos'] != ''){?> selected="selected" <?php } ?>>
					Left
				</option>
				<option value="1" <?php if($_POST['search_pos'] == 1){?> selected="selected" <?php } ?>>
					Right
				</option>
			</select>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>
<?php
$sqlk = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id = '1'";
$result = mysqli_fetch_array(query_execute_sqli($sqlk))[0];

if(isset($_POST['Search'])){
	
	if($_POST['search_pos'] != '' and $_POST['search_userid'] != ''){
		$_SESSION['SESS_search_username'] = $search_userid = $_POST['search_userid'];
		$_SESSION['SESS_POS'] = $pos = $_POST['search_pos'];
		
		$search_id = get_new_user_id($search_userid);
		
		switch($pos){
			case 0: $field = 'left_network'; break;
			case 1:  $field = 'right_network'; break;
		}
		
		$sqlk = "SELECT $field FROM network_users WHERE user_id = '$search_id'";
		$result = mysqli_fetch_array(query_execute_sqli($sqlk))[0];
	}
	
	if($_POST['status_check'] != ''){
		$_SESSION['SESS_status_check'] = $status_check = $_POST['status_check'];
		switch($status_check){
			case 1 : $qur_set_search .= " AND t1.step > 0 AND t1.type = 'B'"; break;
			case 2 : $qur_set_search .= " AND t1.step = 0 AND t1.type = 'B'"; break;
			case 3 : $qur_set_search .= " AND t1.type = 'D' "; break;
		}
	}
}	


$sql = "SELECT t1.*,t2.username spons_id, t2.f_name s_f_name, t2.l_name s_l_name FROM users t1 
LEFT JOIN users t2 ON t1.real_parent = t2.id_user
WHERE t1.id_user IN ($result) $qur_set_search GROUP BY t1.id_user ORDER BY t1.id_user ASC";

$_SESSION['SQL_network_member'] = $sql;

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT count(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
	
if($totalrows != 0){ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="7">Total Members : <?=$tot_rec;?></th>
			<!--<th>
				<form method="post" action="simple_view_netwrk_mem.php" target="_blank"> 
					<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
				</form>
			</th>-->
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-left">User Info</th>
			<th class="text-left">Pnone/E-mail</th>
			<th class="text-left">Sponsor Info</th>
			<th class="text-center">Status</th>
			<!--<th class="text-center">Package</th>-->
			<th class="text-center">Lottery</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query)){
			$id = $row['id_user'];
			$password = $row['password'];
			$username = $row['username'];
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			
			$parent_id = $row['real_parent'];
			$name = $row['f_name']." ".$row['l_name'];
			$type = $row['type'];
			$user_pin = $row['user_pin'];
			
			$step = $row['step'];
			$spons_id = $row['spons_id'];
			$spons_name = ucwords($row['s_f_name']." ".$row['s_l_name']);
							
			<!--if($step == 1 and $type == 'B'){ $status = "<span class='label label-success'>Active</span>"; }
			elseif($step == 0 and $type=='B'){ $status = "<span class='label label-warning'>Registered</span>"; }
			elseif($type != 'B'){$status = "<span class='label label-danger'>Blocked</span>"; }-->
			
			$top_up = get_paid_member($id);
			if($top_up == 0) { $status = "Inactive"; }
			else { $status = "Active"; }
			if($row['type']== 'D'){ $status = "Block"; }
		
		
			$wallet = get_user_wallet($id);
			
			$package = '----------';
			$my_plan = my_package($id);
			if($my_plan > 0){
				$package = $my_plan[0];
			}
			$lottery_ticket = get_user_total_lottery_ticket($id);
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td class="text-left"><B>User ID -</B> <?=$username?><br /><B>Username - </B><?=$name?></td>
				<td class="text-left"><B>E-mail -</B> <?=$email?><br /><B>Phone -</b> <?=$phone_no?></td>
				<td class="text-left"><B>User ID -</B> <?=$spons_id?> <br /> <B>Name -</B> <?=$spons_name?></td>
				<td><?=$status?></td>
				<!--<td><?=$package;?></td>-->
				<td><?=$lottery_ticket?></td>
			</tr> <?php	
			$sr_no++;	
		} 
		?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }

?>

							