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
$search_id = 1;
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_nmsearch_username'];
	$_POST['position'] = $_SESSION['SESS_Position'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_Position']);
}

if(isset($_POST['Search']))
{
	if($_POST['search_username'] !='' and $_POST['position'] !=''){
		
		if($_POST['search_username'] != '')
		$_SESSION['SESS_nmsearch_username'] = $search_username = $_POST['search_username'];
		$_SESSION['SESS_Position'] = $position = $_POST['position'];
		
		$search_id = get_new_user_id($search_username);
		
		$member = network_members($search_id,$position);
		$qur_set_search = " WHERE t1.id_user IN($member)";
	}
}
else{
	$member = network_members($search_id,0);
	$qur_set_search = " WHERE t1.id_user IN($member)";
}

$_SESSION['net_mem_id'] = $search_id;

?>

<table class="table table-bordered">
	<tr>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<select name="position" class="form-control">
				<option value="0" <?php if($_POST['position'] == 0){?> selected="selected" <?php } ?>> Left</option>
				<option value="1" <?php if($_POST['position'] == 1){?> selected="selected" <?php } ?>>Right</option>
			</select>
		</td>
		<td><input type="text" name="search_username" value="<?=$_POST['search_username']?>" placeholder="Search By Username" class="form-control" /></td>
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
		</form>
		<td class="text-right">
			<form method="post" action="simple_view.php" target="_blank"> 
				<input type="hidden" name="sql" value="<?=$_SESSION['sql_net_memb']?>" />
				<input type=submit name="simple_view" value="Simple View" class="btn btn-danger" />
			</form>
		</td>
	</tr>
</table>
	

<?php
$sql = "SELECT t1.id_user,t1.username,t1.f_name,t1.l_name,t1.position,COALESCE(t2.update_fees,'*****') reg_amt, COALESCE(t2.profit,'*****') ROI, COALESCE(t2.date,'*****') act_date,t2.boost_id,t4.username sponser,t5.bank_ac,
t5.bank,t5.branch,t5.ifsc
FROM users t1  
LEFT JOIN (SELECT user_id,max(id) id FROM reg_fees_structure WHERE mode=1 GROUP BY user_id) t3 ON t1.id_user = t3.user_id
LEFT JOIN reg_fees_structure t2 ON t3.id = t2.id
LEFT JOIN users t4 ON t1.real_parent = t4.id_user
LEFT JOIN kyc t5 ON t1.id_user = t5.user_id
$qur_set_search
GROUP BY t1.username
ORDER BY t1.id_user ASC";
$_SESSION['sql_net_memb'] = $sql;
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id_user) num FROM ($sql) t1";
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
			<th colspan="9">
				Binary Member Of : <B class="text-danger"><?=get_user_name($search_id)?></B>
			</th>
		</tr>
		
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Activate Date</th>
			<th class="text-center">Sponser ID</th>
			<th class="text-center">Package</th>
			<!--<th class="text-center">Position</th>-->
			<th class="text-center">Booster Detail</th>
			<th class="text-center">Monthly ROI</th>
			<th class="text-center">Bank Details</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$username = $row['username'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$position = $row['position'] == 0 ? "Left" : "Right";
			$sponser = $row['sponser'];
			$packag = $row['reg_amt'];
			$act_date = $row['act_date'];
			$boost_id = $row['boost_id'];
			$roi = $row['ROI'];
			
			$bank_info = "A/C No. = ".$row['bank_ac']."<br>Bank Name = ".$row['bank']." <br>Branch = ".$row['branch'].'<br />IFSC Code = '.$row['ifsc'];
			
			$package = my_package($id)[0];
			
			if($package == ''){ $package = "*****"; }
			
			if($boost_id > 0) { $boost_status = "<span class='label label-success'>Booster</span>"; }
			else{ $boost_status = "<span class='label label-warning'>Non Booster</span>"; }
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$act_date?></td>
				<td><?=$sponser?></td>
				<td><?=$package?></td>
				<!--<td><?=$position?></td>-->
				<td><?=$boost_status?></th>
				<td><?=$roi?></td>
				<td class="text-left"><?=$bank_info?></td>
			</tr> <?php
			$sr_no++;
		}
		
		?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }


function network_members($search_id,$position){
	$result = "";
	$sqls = "SELECT id_user FROM users WHERE parent_id = '$search_id' AND position = $position";
	$quer = query_execute_sqli($sqls);	
	$ro = mysqli_fetch_array($quer);
	$id_total = $ro[0];
	if($id_total != ""){
		$result = $id_total.",".mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0];
	}
	return rtrim($result,",");
}
?>
