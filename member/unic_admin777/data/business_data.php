<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['submit'])){
	$user_id = $_POST['user_id'];
	$table_id = $_POST['table_id'];
	$tot_roi = $_POST['tot_roi'];
	$recd_roi = $_POST['recd_roi'];
	$recd_bin = $_POST['recd_bin'];
	$pend_roi = $_POST['pend_roi'];
	$remarks = $_POST['remarks'];
	
	$page_value = isset($_GET['p'])?"&p=".$_GET['p']:'';
	
	if($_POST['submit'] == 'Block'){
		$sql = "SELECT * FROM cancel_investment WHERE user_id = '$user_id'";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			$sql = "UPDATE cancel_investment SET mode = 1, remark = '$remarks', re_mode = 1, approve_date = NOW() 
			WHERE user_id = '$user_id'";
		}
		else{
			$sql = "INSERT INTO `cancel_investment`(`user_id`, `tot_roi`, `received_roi`, `received_bin`, `pending_roi`, `req_date`, `paid_date`, `mode`, `approve_date`, `re_mode`, `remark`) 
			VALUES ('$user_id', '$tot_roi', '$recd_roi','$recd_bin', '$pend_roi', NOW(), DATE_ADD(NOW(), INTERVAL 45 DAY),1,NOW(), 1, '$remarks')";
		}
		query_execute_sqli($sql);
		query_execute_sqli("UPDATE reg_fees_structure SET mode = 177 WHERE user_id = '$user_id'");
		
		$MSGS = "Blocked Your Investment Successfully !!";
		?> <script>alert("<?=$MSGS?>"); window.location="index.php?page=<?=$val.$page_value?>";</script> <?php
	}

	if($_POST['submit'] == 'Unblock'){
		$sql = "DELETE FROM cancel_investment WHERE user_id = '$user_id'";
		query_execute_sqli($sql);
		
		$sql = "UPDATE reg_fees_structure SET mode = 1 WHERE user_id = '$user_id' AND mode = 177 
		ORDER BY id DESC LIMIT 1";
		query_execute_sqli($sql);
		
		$MSGS = "Unblocked Your Investment Successfully !!";
		?> <script>alert("<?=$MSGS?>"); window.location="index.php?page=<?=$val.$page_value?>";</script> <?php
	}
}

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_serarch_mem']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['serarch_mem'] = $_SESSION['SESS_serarch_mem'];
}
if(isset($_POST['Search'])){
	
	if($_POST['serarch_mem'] != ''){
		$_SESSION['SESS_serarch_mem'] = $_POST['serarch_mem'];
		if($_SESSION['SESS_serarch_mem'] == 1){
			$can_search = " AND t4.mode = 0"; //" AND (t4.mode NOT IN(1) OR t4.mode IS NULL)";
			$sel1 = 'selected="selected"';
		}
		elseif($_SESSION['SESS_serarch_mem'] == 2){
			$can_search = " AND t4.mode = 1";
			$sel2 = 'selected="selected"';
		}
		elseif($_SESSION['SESS_serarch_mem'] == 4){
			$can_search = " AND t4.mode IS NULL";
			$sel3 = 'selected="selected"';
		}
		else{
			$can_search = " AND t4.mode = 2";
			$sel4 = 'selected="selected"';
		}
	}
		
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$inc_search = " AND t1.user_id = '$search_id' ";
	}
}

?>


	<div class="col-md-3">  
		<!--<form method="post" action="index.php?page=<?=$val?>">
			<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger" />
		</form>-->
		<form method="post" action="simple_view_business.php" target="_blank"> 
			<input type=submit name="simple_view" value="Simple View" class="btn btn-danger" />
		</form>
	</div> 
	<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-3">
		<select name="serarch_mem" class="form-control">
			<option value="">Search Members</option>
			<option value="1" <?=$sel1?>>Cancel Pending</option>
			<option value="2" <?=$sel2?>>Cancel Approved</option>
			<option value="3" <?=$sel3?>>Request Cancelled</option>
			<option value="4" <?=$sel4?>>Active</option>
		</select>
	</div>
	<div class="col-md-4">
		<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
	</div>
	<div class="col-md-2"><input type="submit" value="Search" name="Search" class="btn btn-info"></div>
	</form>	


<div class="col-md-12">&nbsp;</div>

<?php

if(isset($_POST['create_file'])){
	$file_name = 'Business_report'.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['sql_bus_data'];
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="User ID \t User Name \t Activation Date \t Package \t Rental Bonus Received \t Growth Bonus Received \t Pending Balance \t Status"; //\t Net Amount
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id'];
		$user_id = $row['user_id'];
		$username = $row['username'];
		$roi_bonus = round($row['roi'],2);
		$binary_bonus = round($row['bin'],2);
		$wal_tr_amt = round($row['wal_tr_amt'],2);
		$withdraw_amt = round($row['withdraw_amt'],2);
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$wal_amt = round(get_user_allwallet($user_id,'amount'),2);
		
		$investment = "";
		$package = '***';
		$my_plan = my_package($user_id);
		if(!empty($my_plan)){
			$investment = $my_plan[0];
			$package = $my_plan[5];
		}
		
		$top_up = get_paid_member($user_id);
		if($top_up == 0) { $status = "<span class='label label-danger'>Inactive</span>"; }
		else { $status = "<span class='label label-info'>Active</span>"; }
		if($row['user_type']== 'D'){ $status = "<span class='label label-danger'>Block</span>"; }
		
		$tot_roi = get_user_tot_roi_for_active_users($user_id);
		$act_date = get_user_active_investment_with_date($user_id)[1];
		$pending_bonus = $package - ($roi_bonus+$binary_bonus);
		$pend_roi = $my_plan[5]-$roi_bonus;
		
		switch(get_user_cancel_investment($user_id)){
		case 0 : $btn_status = "Block";	break;
		case 1 : $btn_status = "Blocked";	break;
	}
	
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $act_date.$sep;
		$insert .= $package.$sep;
		$insert .= $roi_bonus.$sep;
		$insert .= $binary_bonus.$sep;
		$insert .= $pending_bonus.$sep;
		$insert .= $btn_status.$sep;
		$insert = str_replace($sep."$", "", $insert);
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{

$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name,t2.type user_type from(
SELECT t1.*,COALESCE(SUM(t2.roi),0) roi, COALESCE(SUM(t3.bin),0) bin,COALESCE(t4.mode,5) can_mode, t4.remark, COALESCE(t4.re_mode,5) re_mode 
FROM
(select * from reg_fees_structure where id in(select max(id) from reg_fees_structure t1 where 1=1 $inc_search group by user_id) ) t1 
LEFT JOIN (SELECT COALESCE(SUM(amount),0) roi ,user_id FROM income t1 WHERE type = 2 $inc_search  GROUP BY user_id) t2 on t1.user_id = t2.user_id
LEFT JOIN (SELECT COALESCE(SUM(amount),0) bin ,user_id FROM income t1 WHERE type = 4 $inc_search  GROUP BY user_id) t3 on t1.user_id = t3.user_id 
LEFT JOIN cancel_investment t4 ON t1.user_id = t4.user_id
WHERE t1.user_id IS NOT NULL $can_search
GROUP BY t1.user_id,t2.user_id,t3.user_id
ORDER BY id DESC) t1
LEFT JOIN users t2 ON t1.user_id = t2.id_user
where t2.type='B' and t1.mode in (1,177)
HAVING t1.update_fees - (t1.roi+t1.bin) > 0 ";
$_SESSION['sql_business_data'] = $sql;
	//$b_data_report = get_business_data_report($sql);
	
	$_SESSION['sql_bus_data'] = $sql;
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num,COALESCE(sum(roi),0) total_rental,COALESCE(sum(bin),0) total_growth,
	COALESCE( ( sum(update_fees)-(sum(roi)+sum(bin)) ),0) total_pending FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$b_data_report[0] = round($ro['total_rental'],2);
		$b_data_report[1] = round($ro['total_growth'],2);
		$b_data_report[2] = round($ro['total_pending'],2);
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows != 0)
	{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="2">Total Members : <?=$tot_rec?></th>
			<th colspan="2">Total Rental Bonus: <?=$b_data_report[0]?></th>
			<th colspan="2">Total Growth Bonus: <?=$b_data_report[1]?></span></th>
			<th colspan="2">Total Pending Bonus: <?=$b_data_report[2]?></span></th>
		</tr>
		</thead>
	</table>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Activation Date</td>
				<th class="text-center">Package</th>
				<!--<th class="text-center">Total ROI</th>-->			
				<th class="text-center">Rental Bonus Received</th>
				<th class="text-center">Growth Bonus Received</th>
				<th class="text-center">Pending Balance</th>
				<!--<th class="text-center">Total Withdrawal</th>
				<th class="text-center">Total Wallet Transfer</th>-->
				<th class="text-center">Remarks</th>
				<th class="text-center">Status</th>
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
				$roi_bonus = round($row['roi'],2);
				$binary_bonus = round($row['bin'],2);
				$can_mode = $row['can_mode'];
				$remark = $row['remark'];
				$re_mode = $row['re_mode'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$investment = "";
				
				$package = '***';
				$my_plan = my_package($user_id);
				if(!empty($my_plan)){
					$investment = $my_plan[0];
					$package = $my_plan[5];
				}
				
				$top_up = get_paid_member($user_id);
				if($top_up == 0) { $status = "<span class='label label-danger'>Inactive</span>"; }
				else { $status = "<span class='label label-info'>Active</span>"; }
				if($row['user_type']== 'D'){ $status = "<span class='label label-danger'>Block</span>"; }
				
				$tot_roi = $my_plan[5];//get_user_tot_roi_for_active_users($user_id);
				$act_date = get_user_active_investment_with_date($user_id)[1];
				$pending_bonus = $package - ($roi_bonus+$binary_bonus);
				$pend_roi = $my_plan[5]-($roi_bonus+$binary_bonus);
				
				
				//$btn_status = "<B class='text-success'>Active</B>";
				$btn_status = "<input type='submit' name='submit' value='Block' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Block !! &quot;);' />";
				switch(/*get_user_cancel_investment($user_id)*/$can_mode){
					case 0 : $btn_status = "<input type='submit' name='submit' value='Block' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Block !! &quot;);' />";	break;
					case 1 : $btn_status = "<input type='submit' name='submit' value='Unblock' class='btn btn-success btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Unblock !! &quot;);' />";	break;
					//case 0 : $btn_status = "<B class='text-warning'>Cancel Pending</B>";	break;
					//case 1 : $btn_status = "<B class='text-primary'>Cancel Approved</B>";	break;
					//case 51 : $btn_status = "<input type='submit' name='submit' value='Block' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Block !! &quot;);' />";	break;
					case 2 : $btn_status = "<B class='text-danger'>Request Cancelled</B>";	break;
					//default : $btn_status = "<B class='text-success'>Active</B>";
				}
				//if($row['re_mode']== 1){ $btn_status = ""; }
				?>
				<form method="post" action="">
				<input type="hidden" name="table_id" value="<?=$id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<input type="hidden" name="tot_roi" value="<?=$tot_roi?>" />
				<input type="hidden" name="recd_roi" value="<?=$roi_bonus?>" />
				<input type="hidden" name="recd_bin" value="<?=$binary_bonus?>" />
				<input type="hidden" name="pend_roi" value="<?=$pend_roi?>" />
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td><?=$act_date?></td>
					<td><?=$package?></td>
					<!--<td><?=$tot_roi?></td>-->
					<td><?=$roi_bonus?></td>
					<td><?=$binary_bonus?></td>
					<td><?=$pending_bonus?></td>
					<!--<td><?=$withdraw_amt?></td>
					<td><?=$wal_tr_amt?></td>
					<td><?=$wal_amt?></td>-->
					<td><textarea name="remarks" class="form-control"><?=$remark?></textarea></td>
					<td><?=$btn_status?>
						<!--<form method='post' action='index.php?page=<?=$val?>'>
							<input type='hidden' name='table_id' value='<?=$id?>' />
							<input type='hidden' name='user_id' value='<?=$user_id?>' />
							<input type='hidden' name='tot_roi' value='<?=$tot_roi?>' />
							<input type='hidden' name='recd_roi' value='<?=$roi_bonus?>' />
							<input type='hidden' name='pend_roi' value='<?=$pend_roi?>' />
							<input type='submit' name='submit' value='Block' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Block !! &quot;);' />
						</form>-->
					</td>
				</tr>
				</form> <?php
				$sr_no++;
			} ?>
		</table> 
		<?php
		mysqli_free_result($que);
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }	
}
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