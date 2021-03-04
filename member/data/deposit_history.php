<?php
include('../security_web_validation.php');

session_start();
//include("function/account_maintain.php");
include("function/setting.php");
$user_id = $_SESSION['mlmproject_user_id'];
	
$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
}
$sqli = "SELECT * FROM request_crown_wallet WHERE user_id = '$user_id' $qur_set_search ORDER BY id DESC";
$SQL = "$sqli LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$num = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sqli) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($num > 0){ ?>
<form method="post" action="index.php?page=<?=$val?>">
    <div class="col-md-12">
    	<div class="form-group col-md-4" id="data_1">
    		<div class="input-group date">
    			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    			<input type="date" name="st_date" placeholder="Start Date" class="form-control" id="date"/>
    		</div>
    	</div>
    	<div class="form-group col-md-4" id="data_1" class="form-group" id="data_1">
    		<div class="input-group date">
    			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    			<input type="date" name="en_date" placeholder="End Date" class="form-control" id="date"/>
    		</div>
    	</div>
        <div class="form-group col-md-4" id="data_1"><input type="submit" value="Search" name="Search" class="btn btn-info"></div>
    </div
</form>
	<table class="table table-bordered table-hover table-responsive">
		<thead>
			<tr>
				<th class="text-center">Sr No.</th>
				<th class="text-center">Deposit Date</th>
				<th class="text-center">Deposit Amount</th>
				<th class="text-center">Deposit Address</th>
				<th class="text-center">Reference No.</th>
				<th class="text-center">Confirm Date</th>
				<th class="text-center">Payment Mode</th>
				<th class="text-center">Status</th>
			</tr>
		</thead>
		<?php
		$pnums = ceil ($num/$plimit);
			
		if($newp == ''){ $newp = '1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$sqli = "$sqli LIMIT $start,$plimit";
		$que = query_execute_sqli($sqli);
		while($row = mysqli_fetch_array($que)){
			$id = $row['id'];
			$amount = $row['investment'];
			$tax = $row['tax'];
			$paid = $row['status'];
			$action_date = $row['action_date'] == "0000-00-00 00:00:00" ? '0000-00-00 00:00:00': date('d/m/Y H:i:s' , strtotime($row['action_date']));
			$req_date = date('d/m/Y H:i:s' , strtotime($row['date']));
			$pay_mode = $row['payment_mode'];
			$refr_no = $row['transaction_hash'];
			$chq_no = $row['chq_no'];
			$remarks = $row['admin_remarks'];
			$bank_name = $row['bank_name'];
			$ac_type = $row['ac_type'];
		    $bitcoin_address = $row['bitcoin_address'];
			$payment_mode = $add_fund_mode_value[$pay_mode];
			
			if($action_date == '0000-00-00 00:00:00'){ $paid_date = ''; }
			else{ $paid_date = $action_date; }
			
			switch($paid){
				case 0 : $status = "<B class='text-warning'>In-Progress</B>";	break;
				case 1 : $status = "<B class='text-info'>Approved</B>";	break;
				case 3 : $status = "<B class='text-danger'>Cancel</B>";	break;
			}
			
			switch($ac_type){
				case 1 : $pay_status = "<B class='text-warning'>BTC</B>";	break;
				case 3 : $pay_status = "<B class='text-info'>ETH</B>";	break;
				case 9 : $pay_status = "<B class='text-success'>TRX</B>";	break;
			}
			
			if($pay_mode == 4){
				$refr_no = $chq_no;
			}
			
			$tax_amt = 	$amount*$tax/100;
			$net_amt = $amount-$tax_amt;
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$req_date?></td>
				<td><?=$amount?> TRX</td>
				<td><?=$bitcoin_address?></td>
				<td><?=md5($refr_no)?></td>
				<td><?=$paid_date?></td>
				<td><?=$pay_status?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
		</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

