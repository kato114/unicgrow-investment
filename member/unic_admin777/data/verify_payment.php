<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include('../function/setting.php');
include("../function/blockchain_trasaction.php");
include("../function/direct_income.php");
include("../function/pair_point_calc.php");


$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(isset($_SESSION['pmsg'])){
	echo $_SESSION['pmsg'];
	unset($_SESSION['pmsg']);
}
if(isset($_REQUEST['verify']) and $_REQUEST['verify'] == "Verify"){
	$pid = $_REQUEST['table_id'];
	$sql = "SELECT t1.*,t2.id_user,t2.ac_no,t2.phone_no FROM `request_crown_wallet` t1 
	INNER JOIN `users` t2 ON t1.`user_id` = t2.`id_user`		
	WHERE t1.`status` IN(65,0) AND t2.`type`='B' AND t1.id=$pid";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$chk_result[0] = 0;
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$r_id = $row['id'];
			$user_id = $row['user_id'];
			$s_addr = $row['ac_no'];
			$g_addr = $row['bitcoin_address'];
			$phone_no = $row['phone_no'];
			$full_name= $row['f_name']." ".$row['l_name'];
			$amount = $request_crowd = $row['request_crowd'];
			$pay_method = $pm_name[$row['ac_type']-1];
			$investment = round($row['investment'],3);// /$currency_exch_rate[$pay_method]
			$ac_type = $row['ac_type'];
			$ac_type = $pm_name[$ac_type-1];
			$time = $row['date'];
			//$result = chk_txs_with_hash($s_addr,$g_addr,$amount,$time,$hash);
			if(!isset($_SESSION['vppmsg'])){ // 1 for testing
				$tx_index = $result[5];
				$time = date("Y-m-d H:i:s",($result[6]));
				$sql = "UPDATE request_crown_wallet SET 
				`transaction_id`= '$transation_id' ,`status` = '1', `action_date` = '$systems_date_time' 
				WHERE `id`='$r_id' AND `status`=0";
				query_execute_sqli($sql);
				
				
				query_execute_sqli("update wallet set activationw = activationw+'$investment' where id='$user_id'");
				$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
								`date_time`) VALUES ('$user_id','$r_id','Credit Fund By $ac_type  Money','$investment','0',(SELECT activationw FROM wallet where id='$user_id'), '$systems_date_time')";
				query_execute_sqli($sqk);
				
				$_SESSION['pmsg'] = "<B class='text-success'>Request Is Complete !!</B>";
				if(strtoupper($soft_chk) == "LIVE"){
					$message = "Your Deposit Request is completed Thanks. https://www.unicgrow.com";
					send_sms($phone_no,$message);
				}
			}
			else{ ?> <script>window.location = "index.php?page=verify_payment";</script> <?php }
		}
	}
}
if(isset($_REQUEST['cancel']) and $_REQUEST['cancel'] == "Cancel"){
	$pid = $_REQUEST['table_id'];
	$sql = "SELECT t1.*,t2.id_user,t2.ac_no,t2.phone_no FROM `request_crown_wallet` t1 
	INNER JOIN `users` t2 ON t1.`user_id` = t2.`id_user`		
	WHERE t1.`status` IN(65,0) AND t2.`type`='B' AND t1.id=$pid";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$chk_result[0] = 0;
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$r_id = $row['id'];
			if(!isset($_SESSION['vppmsg'])){
				$sql = "UPDATE request_crown_wallet SET 
				`transaction_id`= 'cccccc' ,`status` = '3', `action_date` = '$systems_date_time' 
				WHERE `id`='$r_id' AND `status`=0";
				query_execute_sqli($sql);
				print $_SESSION['pmsg'] = "<B class='text-danger'>Request Of Cancel Is Complete !!</B>";
				if(strtoupper($soft_chk) == "LIVE"){
					$message = "Your Deposit Request is Cancel.  Thanks https://www.unicgrow.com";
					send_sms($phone_no,$message);
				}
			}
			else{ ?> <script>window.location = "index.php?page=verify_payment";</script> <?php }
		}
	}
}
unset($_SESSION['vppmsg']);


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>
<div class="row">
	<div class="col-md-4 col-md-offset-8">
	<form method="post" action="index.php?page=verify_payment">
	<table class="table table-bordered">
		<tr>
			<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
			<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
		</tr>
	</table>
	</form>	
	</div>
</div>

<?php
$sql = "SELECT t1.*,t2.username username1,t2.ac_no ac_no1 FROM request_crown_wallet t1
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.status IN(65,0) $qur_set_search
ORDER BY t1.status DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$queryS = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($queryS);

$sqlk = "SELECT COUNT(t1.id) num FROM request_crown_wallet t1 $qur_set_search";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows >0)
{ 
	while($ro = mysqli_fetch_array($queryS))
	{
		$total_amount += $ro['request_crowd'];
	} ?>
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="9">Total Amount : <?=$total_amount; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Sender Username</th>
			<th class="text-center">Sender Address</th>
			<th class="text-center">Receiver Address</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Payment By</th>
			<th class="text-center">Hash Code</th>
			<th class="text-center">Date</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $plimit*($newp-1);
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$sr_no++;
			$table_id = $row['id'];
			$username1 = $row['username1'];
			$ac_no1 = $row['ac_no1'];
			$ac_no2 = $row['bitcoin_address'];
			$amount = $row['request_crowd'];
			$transaction_hash = $row['transaction_hash'];
			$date1 = $row['date'];
			$date = $date1;
			$ac_type = $row['ac_type'];
			 ?>
			<tr>
				<td><?=$sr_no?></td>
				<td><?=$username1?></td>
				<td><?=$ac_no1?></td>
				<td><?=$ac_no2?></td>
				<td><?=$amount?></td>
				<td><?=$pm_name[$ac_type-1]?></td>
				<td>
					<?php
					if($ac_type !=1){ ?>
						<a href="payment_rec.php?payment_receipt=<?=$transaction_hash?>" target="_blank">
							Check Receipet
						</a><?php
					}
					else{ echo $transaction_hash; }
					?>
				</td>
				<td><?=$date?></td>
				<td>
					<form action="" method="post">
						<input type="hidden" name="table_id" value="<?=$table_id?>" />
						<input type="submit" name="verify" value="Verify" class="btn btn-info" />
						<input type="submit" name="cancel" value="Cancel" class="btn btn-danger" />
					</form>
				</td>
			</tr> <?php
			$sr_no++;
		}?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}
else{ echo "<B class='text-danger'>There is no information !</B>"; }  
?>	