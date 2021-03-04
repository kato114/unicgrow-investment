<?php
session_start();
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_trade_mode']);
}
else{
	$_POST['search'] = '1';
	$_POST['s_date'] = $_SESSION['SESS_strt_date'];
	$_POST['e_date'] = $_SESSION['SESS_end_date'];
	$_POST['trade_mode'] = $_SESSION['SESS_trade_mode'];
}
if(isset($_POST['search']))
{
	if($_POST['s_date'] != '' and $_POST['e_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['s_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['e_date']));
		$qur_set_search = " AND DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['trade_mode'] != ''){
		$trade_mode = $_SESSION['SESS_trade_mode'] = $_POST['trade_mode'];
		switch($trade_mode){
			case 1 : $qur_set_search .= " AND mode=1";break;
			case 2 : $qur_set_search .= " AND mode=0";break;
			case 3 : $qur_set_search .= " AND mode=2";break;
		}
	}
}

if(isset($_POST['cancel_trade']) and $_POST['cancel_trade'] == 'Cancel')
{
	$trade_id = $_POST['trade_id'];
	$sql = "select * from trade_buy where mode=0 and id='$trade_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$sql = "update trade_buy set mode = 2 where id='$trade_id';";
		query_execute_sqli($sql);
		$num = query_affected_rows();
		if($num > 0){
			while($row = mysqli_fetch_array($query)){
				$sale_user_id = $row['user_id'];
				$sale_share = $row['share'];
			}
			$sql = "update wallet set owner_share = owner_share + $sale_share where id='$sale_user_id';";
			query_execute_sqli($sql);
			insert_wallet_account($sale_user_id , $sale_user_id , $sale_share , $systems_date_time , $acount_type[11] , $acount_type_desc[11], 1 , get_user_allwallet($sale_user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Cancel Credit Owner Wallet");
			echo "<B class='text-success'>Success : Trade Cancelation Completed !!</B>";
		}
		else{
			echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
		}
	}
	else{
		header('Location:index.php?page=gtb_tsale_pending');
	}
	mysqli_free_result($query);
}
$sql = "select * from trade_buy where type=2 and user_id='$login_id' $qur_set_search order by date desc";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
$sqlk = "SELECT COUNT(id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
mysqli_free_result($query);
if($totalrows > 0){
	$sel = "selected";
	?>
	<form action="index.php?page=<?=$val?>" method="post">
		<div class="col-md-12">
			<div class="col-md-3">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="s_date" placeholder="From Date" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="e_date" placeholder="To Date" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<select name="trade_mode" class="form-control">
					<option value="">Select Mode</option>
					<option value="1" <?php if($_POST['trade_mode'] == 1)echo $sel;?>>Completed Sale</option>
					<option value="2" <?php if($_POST['trade_mode'] == 2)echo $sel;?>>Pending Sale</option>
					<option value="3" <?php if($_POST['trade_mode'] == 3)echo $sel;?>>Cancel Sale</option>
				</select>
			</div>
			<div class="col-md-3"><input type="submit" name="search" value="Search" class="btn btn-info" /></div>
		</div>
	</form>
	<div class="col-md-12">&nbsp;</div>
	<table class="table table-bordered table-hover">
		<thead>
			<th>S.No.</th>
			<th>Date</th>
			<th>Share</th>
			<th>Unit Amount</th>
			<th>Total Amount</th>
			<th>&nbsp;</th>
		</thead>
	<?php
	$s_no = 1;
	$pnums = ceil ($totalrows/$plimit);
		
	if ($newp==''){ $newp='1'; }

	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	$sr_no = $starting_no;
	$que = query_execute_sqli("$sql LIMIT $start,$plimit");
	while($row = mysqli_fetch_array($que)){
		switch($row['mode']){
		case 0 : $status = 
				"<form action='' method='post'>	
					<input type='submit' name='cancel_trade' value='Cancel' class='btn btn-danger btn-xs' />
					<input type='hidden' name='trade_id' value='".$row['id']."' />
				</form>";
				break;
		case 1 :  $status = "<B class='text-success'>Complete</B>";break;
		case 2 :  $status = "<B class='text-danger'>Canceled</B>";break;
		}
		?>
		<tr>
			<td><?=$s_no;?></td>
			<td><?=date("Y-m-d H:i:s",strtotime($row['date']));?></td>
			<td><?=$row["share"];?></td>
			<td>&#36;<?=$row["unit_amount"];?></td>
			<td>&#36;<?=$row["total_amount"];?></td>
			<td><?=$status?></td>
		</tr>
		<?php
		$s_no++;
	}?>
	</table> <?php 
	mysqli_free_result($que);
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{
	switch($trade_mode){
		case 1 : $msg = "There Have No Completed Buy Trade !!";break;
		case 2 : $msg = "There Have No Pending Buy Trade !!";break;
		case 3 : $msg = "There Have No Canceled Buy Trade !!";break;
	}
	echo "<B class='text-danger'>$msg</B>";
}
mysqli_free_result($query);
?>



