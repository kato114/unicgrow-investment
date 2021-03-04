<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>

<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_search_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['date'] = $_SESSION['SESS_search_date'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['date'] != ''){
		$_SESSION['SESS_search_date'] = $date = date('Y-m-d', strtotime($_POST['date']));
		$qur_set_search = " AND t1.date = '$date' ";
	}
		
	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}


//$sql = "SELECT * FROM reg_fees_structure $qur_set_search ORDER BY id DESC";
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name,t3.roi,t4.bin,t5.wal_tr_amt,t6.withdraw_amt 
FROM reg_fees_structure t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
LEFT JOIN (SELECT COALESCE(SUM(amount),0) roi ,user_id FROM income WHERE type = 2 GROUP BY user_id) t3 ON t1.user_id = t3.user_id
LEFT JOIN (SELECT COALESCE(SUM(amount),0) bin ,user_id FROM income WHERE type = 4 GROUP BY user_id) t4 ON t1.user_id = t4.user_id
LEFT JOIN (SELECT COALESCE(SUM(dr),0) wal_tr_amt ,user_id FROM account WHERE type = 29 GROUP BY user_id) t5 ON t1.user_id = t5.user_id
LEFT JOIN (SELECT COALESCE(SUM(dr),0) withdraw_amt ,user_id FROM account WHERE type = 15 GROUP BY user_id) t6 ON t1.user_id = t6.user_id
WHERE t1.user_id IS NOT NULL AND (t3.roi > 0 OR t4.bin > 0 OR t5.wal_tr_amt > 0 OR t6.withdraw_amt > 0) $qur_set_search 
GROUP BY t1.user_id ORDER BY t1.id DESC";

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

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Growth</th>
			<th class="text-center">Binary</th>
			<th class="text-center">Total Income</th>
			<th class="text-center">Wallet Transfer</th>
			<th class="text-center">Withdrawal</th>
			<th class="text-center">Balance</th>
			<th class="text-center">Wallet Balance</th>
			<th class="text-center">View All</th>
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
			$id = $row['id'];
			$user_id = $row['user_id'];
			$username = $row['username'];
			$growth_bonus = round($row['roi'],2);
			$binary_bonus = round($row['bin'],2);
			$wal_tr_amt = round($row['wal_tr_amt'],2);
			$withdraw_amt = round($row['withdraw_amt'],2);
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			//$growth_bonus = round(get_user_which_type_bonus($user_id , 2),2);
			//$binary_bonus = round(get_user_which_type_bonus($user_id , 4),2);
			$tot_bonus = round($growth_bonus+$binary_bonus,2);
			
			//$wal_tr_amt = round(get_user_account_info($user_id , 29 , 'dr'),2);
			//$withdraw_amt = round(get_user_account_info($user_id , 15 , 'dr'),2);
			
			$balance = round($tot_bonus-($wal_tr_amt+$withdraw_amt),2);
			
			$wal_amt = round(get_user_allwallet($user_id,'amount'),2);
			
			$form_btn = "<input type='submit' name='view_all' value='View All' class='btn btn-success btn-xs' />";
			
			if($withdraw_amt > 0){
				//$withdraw_amt = "<button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#myModal5' data-id='$user_id' data-busins='$withdraw_amt'>$withdraw_amt</button>";
				
				$form_btn = "<form method='post' action='index.php?page=tot_business_report' target='_blank'>
				<input type='hidden' name='table_id' value='$id' />
				<input type='hidden' name='user_id' value='$user_id' />
				<input type='hidden' name='withdraw_amt' value='$withdraw_amt' />
				<input type='submit' name='view_all' value='View All' class='btn btn-success btn-xs' />
			</form>";
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$growth_bonus?></td>
				<td><?=$binary_bonus?></td>
				<td><?=$tot_bonus?></td>
				<td><?=$wal_tr_amt?></td>
				<td><?=$withdraw_amt?></td>
				<td><?=$balance?></td>
				<td><?=$wal_amt?></td>
				<td><?=$form_btn?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }	
//}
?>

<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var busins = $(e.relatedTarget).data('busins');
        $.ajax({
            type : 'post',
            url : 'my_withdrawal.php',
            data :  {'id': id, 'business': busins},
            success : function(data){
				$('.show_user').html(data);
			}
		});
	});
});
</script>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">MY Withdrawal</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<div class="modal-body">
				<div class="show_user"></div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>