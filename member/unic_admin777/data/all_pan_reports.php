<?php
include('../../security_web_validation.php');
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");
$pan_id  = $_POST['pan_id'];
?>

<?php
 	$sql = "SELECT t1.*,t2.update_fees,SUM(t3.request_crowd) tot_with,t4.amount comm_wallet,t4.activationw 	e_wallet,t4.companyw comp_wallet,t5.amt tot_receive
	FROM (SELECT `user_id`,pan_no FROM `kyc` where user_id in($pan_id) GROUP by pan_no,user_id) t1
	LEFT JOIN reg_fees_structure t2 ON t1.user_id = t2.user_id 
	LEFT JOIN withdrawal_crown_wallet t3 ON t1.user_id = t3.user_id
	LEFT JOIN wallet t4 ON t1.user_id =t4.id
	LEFT JOIN (SELECT user_id,SUM(amount) amt FROM income WHERE type = 2 GROUP by user_id) t5 ON t1.user_id = t5.user_id
	WHERE t2.mode = 1 
	GROUP BY t1.user_id";
 ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Name</th>
				<th class="text-center">Kyc Status</th>
				<th class="text-center">Investment</th>
				<th class="text-center">Total Received</th>
				<th class="text-center">Total Withdrawal</th>
				<th class="text-center">E-Wallet </th>
				<th class="text-center">Total Commission</th>
			</tr>
			</thead>
			<?php
			$sr_no = 1;
			$query = query_execute_sqli("$sql");
			while($row = mysqli_fetch_array($query)){
				$user_id = $row['user_id'];
				$pan_cnt = $row['cnt'];
				$tot_invst = $row['update_fees'];
				$tot_receive = $row['tot_receive'];
				$tot_with = $row['tot_with'];
				$tot_com = $row['comm_wallet'];
				$tot_e_wall = $row['e_wallet'];
				$tot_compny_wallet = $row['comp_wallet'];
				$username = get_user_name($user_id);
				$name = get_full_name($user_id);
				
				$kyc_sts = get_user_kyc_status_new($user_id);
				switch($kyc_sts){
					case 'Cancelled' : 	$kyc_status = "<B class='text-danger'>Cancelled </B>"; break;
					case 'Pending' : 	$kyc_status = "<B class='text-warning'>Pending </B>"; break;
					case 'Approved' : 	$kyc_status = "<B class='text-success'>Approved </B>"; break;
				}
				?>	
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$kyc_status?></td>
					<td>&#36;<?=$tot_invst?></td>
					<td>&#36;<?=round($tot_receive,2)?></td>
					<td>&#36;<?=round($tot_with,2)?></td>
					<td>&#36;<?=round($tot_e_wall,2)?></td>
					<td>&#36;<?=round($tot_com,2)?></td>
				</tr>
				<?php
				$sr_no++;
			}	
			?>
		</table> <?PHP
		
?>

