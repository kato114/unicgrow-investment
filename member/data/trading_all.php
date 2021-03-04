<?php
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
$login_id = $user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$user_id' ");
while($row = mysqli_fetch_array($query)){
	$amount = $row['trade_gaming'];
	$owner_share = $row['owner_share'];
}

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
if(isset($_POST['cancel_trade_buy']) and $_POST['cancel_trade_buy'] == 'Cancel')
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
				$buy_user_id = $row['user_id'];
				$buy_share = $row['share'];
				$unit_amount = $row['unit_amount'];
				$total_amount = $buy_share * $unit_amount;
			}
			$sql = "update wallet set trade_gaming = trade_gaming + $total_amount where id='$buy_user_id';";
			query_execute_sqli($sql);
			insert_wallet_account($buy_user_id , $buy_user_id , $total_amount , $systems_date_time , $acount_type[10] , $acount_type_desc[10], 1 , get_user_allwallet($buy_user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Cancel Credit Trade Wallet");
			echo "<B class='text-success'>Success : Trade Cancelation Completed !!</B>";
		}
		else{
			echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
		}
	}
	else{
		header('Location:index.php?page=$val');
	}
	mysqli_free_result($query);
}
if(isset($_POST['cancel_trade_sale']) and $_POST['cancel_trade_sale'] == 'Cancel')
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
		header('Location:index.php?page=$val');
	}
	mysqli_free_result($query);
}

$sel = "selected";

$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$login_id'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num == 0){ ?>
	<div class="alert alert-danger">
		Activate SMG Trading by buying package !! 
		<a href="index.php?page=activation_company_wallet" class="btn btn-primary btn-sm">Buy</a>
	</div> <?php
}
else{
include "gtb_trade_start.php";
?>
<!--<form action="index.php?page=<?=$val?>" method="post">
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
<div class="col-md-6">
	<div class="alert alert-success"><B>USD Wallet Balance : &#36;<?=$amount?></B></div>
</div>
<div class="col-md-6">
	<div class="alert alert-info"><B>SMG Wallet : <?=$owner_share?></B></div>
</div>-->

<?php
$sql = "SELECT * FROM trade_trasaction ORDER BY date DESC LIMIT 1";
$que = query_execute_sqli($sql);
?>
<table class="table table-bordered table-hover">
	<thead><tr><th colspan="5">Last Trade</th></tr>
	<tr>
		<th class="text-center">Sr.No.</th>
		<th class="text-center">Date</th>
		<th class="text-center">Time</th>
		<th class="text-center">Rate</th>
		<th class="text-center">Volume</th>
	</thead>
	<?php
	$s_no = 1;
	
	while($row = mysqli_fetch_array($que)){
		$unit_amount = $row['tx_unit'];
		$share = $row['share'];
		$date = $row['date'];
		$date_l = date("Y-m-d",strtotime($row['date']));
		$time_l = date("H:i:s",strtotime($row['date']));
		?>
		<tr class="text-center">
			<td><?=$s_no;?></td>
			<td><?=$date_l;?></td>
			<td><?=$time_l;?></td>
			<td>&#36;<?=$unit_amount;?></td>
			<td><?=$share?></td>
		</tr> <?php
		$s_no++;
	} ?>
</table>
<!--<table class="table table-bordered">
<thead><th class="text-center">Buy</th><th class="text-center">Sale</th></thead>
<tr>
	<td>
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
			/*$pnums = ceil ($totalrows/$plimit);
				
			if ($newp==''){ $newp='1'; }
		
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;*/
			$sql = "select * from trade_buy where type in(1) and user_id='$login_id' $qur_set_search 
			order by date desc LIMIT 10";
		
			$que = query_execute_sqli("$sql/* LIMIT $start,$plimit*/");
			while($row = mysqli_fetch_array($que)){
				switch($row['mode']){
				case 0 : $status = 
						"<form action='index.php?page=$val' method='post'>	
						<input type='submit' name='cancel_trade_buy' value='Cancel' class='btn btn-danger btn-xs' />
						<input type='hidden' name='trade_id' value='".$row['id']."' />
						</form>";
						break;
				case 1 :  $status = "<B class='text-success'>Complete</B>";break;
				case 2 :  $status = "<B class='text-danger'>Canceled</B>";break;
				} ?>
				<tr>
					<td><?=$s_no;?></td>
					<td><?=date("Y-m-d H:i:s",strtotime($row['date']));?></td>
					<td><?=$row["share"];?></td>
					<td>&#36;<?=$row["unit_amount"];?></td>
					<td>&#36;<?=$row["total_amount"];?></td>
					<td><?=$status?></td>
				</tr> <?php
				$s_no++;
			} ?>
		</table>
	</td>
	<td>
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
			$sql = "select * from trade_buy where type in(2) and user_id='$login_id' $qur_set_search 
			order by date desc LIMIT 10";
			$que = query_execute_sqli("$sql/* LIMIT $start,$plimit*/");
			while($row = mysqli_fetch_array($que)){
				switch($row['mode']){
					case 0 : $status = 
							"<form action='index.php?page=$val' method='post'>	
								<input type='submit' name='cancel_trade_sale' value='Cancel' class='btn btn-danger btn-xs' />
								<input type='hidden' name='trade_id' value='".$row['id']."' />
							</form>";
							break;
					case 1 :  $status = "<B class='text-success'>Complete</B>";break;
					case 2 :  $status = "<B class='text-danger'>Canceled</B>";break;
				} ?>
				<tr>
					<td><?=$s_no;?></td>
					<td><?=date("Y-m-d H:i:s",strtotime($row['date']));?></td>
					<td><?=$row["share"];?></td>
					<td>&#36;<?=$row["unit_amount"];?></td>
					<td>&#36;<?=$row["total_amount"];?></td>
					<td><?=$status?></td>
				</tr> <?php
				$s_no++;
			} ?>
		</table>
	</td>
</tr>
</table>--> <?php 
mysqli_free_result($que);
//pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);

mysqli_free_result($query);

get_live_trade();

include "trading_chart_new.php";
}
?>
