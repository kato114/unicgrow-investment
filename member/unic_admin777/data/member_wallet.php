<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
$h = isset($_GET['h']) ? $_GET['h'] : 1;
$qur_set_search = '';
if(count($_GET) == 1 or count($_GET) == 2){
	unset($_SESSION['SESS_search_username']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	if($search_username !=''){
		$qur_set_search .= " AND t1.id = '$search_id' ";
	}
}
/*switch($h){
	case 1 : $hd1 = 'disabled';break; 
	case 2 : $hd2 = 'disabled';break; 
	case 3 : $hd3 = 'disabled';break;
	case 4 : $hd4 = 'disabled';break; 
}*/
?>
<!--<div class="col-lg-12 text-right">
	<a href="index.php?page=<?=$val?>&h=1" class="btn btn-success btn-xs <?=$hd1?>">Deposit Wallet</a>
	<a href="index.php?page=<?=$val?>&h=2" class="btn btn-success btn-xs" <?=$hd2?>>SMG Wallet</a>
	<a href="index.php?page=<?=$val?>&h=3" class="btn btn-success btn-xs" <?=$hd3?>>SMG Share</a>
	<a href="index.php?page=<?=$val?>&h=4" class="btn btn-success btn-xs" <?=$hd4?>>Tora Share</a>
</div>
<div class="col-lg-12">&nbsp;</div>-->
<?php
//$val = $val."&h=$h";
?>
<table class="table table-bordered">
	<tr>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_username" value="<?=$_POST['search_username']?>" placeholder="Search By Username" class="form-control" />
		</td>
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
		</form>
	</tr>
</table>

<?php
$sign="*";
switch($h){
	case 1 : $wall_type = 'amount';$sign="&#36;";break; 
	case 2 : $wall_type = 'activationw';$sign="&#36;";break; 
}

$sql = "SELECT t1.amount wallet_balance1,t1.activationw wallet_balance2,t2.username,t2.f_name,t2.l_name FROM wallet t1 
INNER JOIN users t2 ON t1.id = t2.id_user
WHERE 1=1 $qur_set_search ORDER BY t1.$wall_type DESC";	
//t1.type IN(13,19) AND

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COALESCE(SUM(wallet_balance1),0) total_w1, COALESCE(SUM(wallet_balance2),0) total_w2 ,COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$total_w1 = $ro['total_w1'];
	$total_w2 = $ro['total_w2'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="7">
				<div class="pull-left col-md-6">
					Total Commission Balance : <span class="btn btn-success btn-sm"><?=$sign?><?=$total_w1?></span>
				</div>&nbsp;&nbsp;
				<div class="pull-left">
					Total Deposit Balance : <span class="btn btn-success btn-sm"><?=$sign?><?=$total_w2?></span>
				</div>
			</th>
		</tr>
		<tr>
			<th class="text-center" width="10%">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Commission Balance</th>
			<th class="text-center">Deposit Balance</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){ 	
			$username = $row['username'];
			$wal_bal1 = $row['wallet_balance1'];
			$wal_bal2 = $row['wallet_balance2'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			
			$wr = $cr > 0 ? $cr : $dr;
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$sign?><?=$wal_bal1?></td>
				<td><?=$sign?><?=$wal_bal2?></td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>