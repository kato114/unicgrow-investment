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
				$bywallet = $row['bywallet'];
				$total_amount = $buy_share * $unit_amount;
			}
			$wfield = $by_wallet == 1 ? 'trade_gaming' : 'amount';
			$ac_type = $bywallet == 1 ? $acount_type[10] : $acount_type[40];
			$ac_type_desc = $bywallet == 1 ? $acount_type_desc[10] : $acount_type_desc[39];
			$wall_type = $by_wallet == 1 ? $wallet_type[4] : $wallet_type[1];
			$sql = "update wallet set $wfield = $wfield + $total_amount where id='$buy_user_id';";
			query_execute_sqli($sql);
			insert_wallet_account($buy_user_id , $buy_user_id , $total_amount , $systems_date_time ,$ac_type , $ac_type_desc, 1 , get_user_allwallet($buy_user_id,$wfield),$wall_type,$remarks = "Trade Cancel Credit $wall_type");
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
?>
<div class="panel blank-panel">
	<div class="panel-heading">
		<div class="panel-options">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab-1">Buy</a></li>
				<li class=""><a data-toggle="tab" href="#tab-2">Sale</a></li>
			</ul>
		</div>
	</div>

	<div class="panel-body">
		<div class="tab-content">
			<div id="tab-1" class="tab-pane active">
				<table class="table table-bordered table-hover">
					<thead>
						<th class="text-center">S.No.</th>
						<th class="text-center">Date</th>
						<th class="text-center">Share</th>
						<th class="text-center">Unit Amount</th>
						<th class="text-center">Total Amount</th>
						<th class="text-center">&nbsp;</th>
					</thead>
					<?php
					$s_no = 1;
					$sql = "SELECT * FROM trade_buy WHERE type IN (1) AND user_id='$login_id' $qur_set_search 
					ORDER BY date DESC";
					$que = query_execute_sqli($sql);
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
						<tr class="text-center">
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
			</div>

			<div id="tab-2" class="tab-pane">
				<table class="table table-bordered table-hover">
					<thead>
						<th class="text-center">S.No.</th>
						<th class="text-center">Date</th>
						<th class="text-center">Share</th>
						<th class="text-center">Unit Amount</th>
						<th class="text-center">Total Amount</th>
						<th class="text-center">&nbsp;</th>
					</thead>
					<?php
					$s_no = 1;
					$sql = "SELECT * FROM trade_buy WHERE type IN (2) AND user_id='$login_id' $qur_set_search 
					ORDER BY date DESC";
					$que = query_execute_sqli($sql);
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
						<tr class="text-center">
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
			</div>
		</div>
	</div>
</div>
					
					
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
<table class="table table-bordered">
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
	$sql = "select * from trade_buy where type in(1) and user_id='$login_id' $qur_set_search order by date desc";

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
	}
	?>
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
			/*$pnums = ceil ($totalrows/$plimit);
				
			if ($newp==''){ $newp='1'; }
		
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;*/
			$sql = "select * from trade_buy where type in(2) and user_id='$login_id' $qur_set_search order by date desc";
		
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
			}
			?>
		</table>
	</td>
</tr>
</table>--> <?php 
mysqli_free_result($que);
//pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);

mysqli_free_result($query);
?>



