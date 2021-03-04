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
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search'])){

	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if(!empty($_POST['search_username'])){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND t1.id_user = '$search_id' AND t1.id_user > 0";
	}
}
?>
<table class="table table-bordered">
	<tr>
		<!--<td>
			<form action="index.php?page=excel_bonus" method="post">
				<input type="hidden" name="bonus_name" value="ROI Bonus" />
				<input type="hidden" name="inc_type" value="2" />
				<input type="hidden" name="url" value="<?=$val?>" />
				<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning" />
			</form>
		</td>-->
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
		</td>
		<!--<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="End Date" class="form-control" />
				</div>
			</div>
		</td>-->
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
		</form>
	</tr>
</table>

<?php
if(isset($_POST['block']) and $_POST['block'] == 'Confirm'){
	$paid_id = $_POST['user_id'];
	$remark = $_POST['remark'];
	//stop binary
	query_execute_sqli("update users set step = 3 where id_user = '$paid_id'");
	$sql = "insert into ledger set user_id='$paid_id',particular='$remark',	date_time='$systems_date_time', 
	balance='3'";
	query_execute_sqli($sql);
	//stop roi
	
	$sql = "UPDATE reg_fees_structure SET mode = 199 WHERE user_id='$paid_id' AND mode=1 ORDER BY id DESC LIMIT 1";
	query_execute_sqli($sql);
	$sql = "INSERT INTO ledger SET user_id='$paid_id',particular='Stop Roi By Mode 199',	
	date_time='$systems_date_time',balance='199'";
	query_execute_sqli($sql);
	?> 
	<script>
		alert('Binary And Roi Stop Successfully !!'); window.location = "index.php?page=<?=$val?>";
	</script> <?php
}
if(isset($_POST['Excel']))
{
	$file_name = "ROI-Bonus Report".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.*,t2.request_crowd tot_invst,t2.profit,t2.boost_id,t2.total_days,t2.mode reg_mode, 
	t2.update_fees 
	FROM users t1 
	LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
	WHERE t2.id IS NOT NULL AND t2.mode = 1 $qur_set_search GROUP BY t2.user_id ORDER BY t1.id_user DESC";
	
	$result = query_execute_sqli($SQL);              

	$insert_rows.="User ID \t Name \t Mobile No. \t Investment \t Package Name  \t Booster \t Total ROI \t Total Received \t Total Pending \t 10% Of ROI \t Month";
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id'];
		$user_id = $row['id_user'];
		$date = date('d/m/Y' , strtotime($row['date']));
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$phone_no = $row['phone_no'];
		
		$tot_invst = $row['tot_invst'];
		$total_days = $row['total_days'];
		$mode = $row['reg_mode'];
		$boost_id = $row['boost_id'];
		$profit = $row['profit'];
		$update_fees = $row['update_fees'];
		
		if($mode == 1){
			$tot_roi = $profit*$total_days;
		}
		
		$my_plan = my_package($user_id)[0];
		
		//$booster = "Pending";
		//if($mode == 66){ $booster = "<B class='text-success'>Achieved </B>"; }
		
		$booster = user_booster_is_activate_or_not_for_bonus_roi($user_id,$systems_date_time);
		
		$recvd_roi = get_user_roi_income($user_id);
		$pend_roi = $tot_roi-$recvd_roi;	
		
		$roi_per_10 = $update_fees*10/100;
		$month = round($pend_roi/$roi_per_10);
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $phone_no.$sep;
		$insert .= $update_fees.$sep;
		$insert .= $my_plan.$sep;
		$insert .= $booster.$sep;
		$insert .= $tot_roi.$sep;
		$insert .= $recvd_roi.$sep;
		$insert .= $pend_roi.$sep;
		$insert .= $roi_per_10.$sep;
		$insert .= $month.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{
	$sql = "SELECT t1.*,t2.request_crowd tot_invst,t2.profit,t2.boost_id,t2.total_days,t2.mode reg_mode, 
	t2.update_fees 
	FROM users t1 
	LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
	WHERE t2.id IS NOT NULL AND t2.mode = 1 $qur_set_search GROUP BY t2.user_id ORDER BY t1.id_user DESC";	
	
	$_SESSION['SQL_roi_new'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$amount = $ro['amt'];
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan="11">
					<form action="" method="post">
						<input type="submit" name="Excel" value="Download Excel" class="btn btn-primary btn-sm" />
					</form>
				</th>
				<th class="text-right" colspan="2">
					<form method="post" action="simple_view_rental_bonus.php" target="_blank"> 
						<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
					</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr.No.</th>
				<th class="text-center">User ID</td>
				<th class="text-center">Name</td>
				<th class="text-center">Mobile No.</td>
				<th class="text-center">Investment</th>
				<th class="text-center">Booster</th>
				<th class="text-center">Total ROI</td>
				<th class="text-center">Total Received</td>
				<th class="text-center">Total Pending</td>
				<th class="text-center">10% Of ROI</td>
				<th class="text-center">Month</td>
				<th class="text-center">Status</td>
				<th class="text-center">Block</td>
			</tr>
			</thead>
			<?php
			$pnums = ceil($totalrows/$plimit);
			if($newp == ''){ $newp = '1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$que = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($que))
			{ 	
				$id = $row['id'];
				$user_id = $row['id_user'];
				$date = date('d/m/Y' , strtotime($row['date']));
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$phone_no = $row['phone_no'];
				
				$tot_invst = $row['tot_invst'];
				$total_days = $row['total_days'];
				$mode = $row['reg_mode'];
				$boost_id = $row['boost_id'];
				$profit = $row['profit'];
				$update_fees = $row['update_fees'];
				

				if($mode == 1){
					$tot_roi = $profit*$total_days;
				}
				
				//$booster = "<B class='text-danger'>Pending </B>";
				//if($mode == 66){ $booster = "<B class='text-success'>Achieved </B>"; }
				
				$booster = user_booster_is_activate_or_not($user_id,$systems_date_time);
				
				$recvd_roi = get_user_roi_income($user_id);
				$pend_roi = $tot_roi-$recvd_roi;
				
				$form_btn = "";
				if($recvd_roi > 0){
					$form_btn = "<form method='post' action='index.php?page=bonus_roi_details' target='_blank'>
						<input type='hidden' name='table_id' value='$id' />
						<input type='hidden' name='user_id' value='$user_id' />
						<input type='submit' name='view_all' value='Status' class='btn btn-success btn-xs' />
					</form>";
				}
				
				$roi_per_10 = $update_fees*10/100;
				$month = round($pend_roi/$roi_per_10);
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username;?></td>
					<td><?=$name?></td>
					<td><?=$phone_no?></td>
					<td>&#36;<?=$update_fees?></td>
					<td><?=$booster?></td>
					<td>&#36;<?=$tot_roi?></td>
					<td>&#36;<?=$recvd_roi?></td>
					<td>&#36;<?=$pend_roi?></td>
					<td>&#36;<?=$roi_per_10?></td>
					<td><?=$month?></td>
					<td><?php //$form_btn?>
						<form method="post" action="index.php?page=bonus_details" target="_blank">
							<input type="hidden" name="user_id" value="<?=$user_id?>" />
							<input type="hidden" name="username" value="<?=$username?>" />
							<input type="hidden" name="type" value="2" />
							<input type="submit" name="status" value="More" class="btn btn-success btn-xs" />
						</form>
					</td>
					<td><?php //$form_btn?>
						<form method="post" action="" >
							<input type="hidden" name="user_id" value="<?=$user_id?>" />
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal5"
			data-id="<?=$user_id?>" >Block</button>
						</form>
					</td>
				</tr> <?php
				$sr_no++;
			}  ?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
} ?>
<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var uname = $(e.relatedTarget).data('username');
       	$("input[name=user_id]").val(id);
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
				<h4 class="modal-title">Block To Member</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<form action="" method="post">
			<div class="modal-body">
				<p>Are You Sure, You Want To Stop Roi And Stop Binary For This Member.</p>
				Remark : <textarea name="remark" class="form-control" placeholder='Give Your Valuable Remarks...' required></textarea>
			</div>
			
			<div class="modal-footer">
				
				<input type="hidden" name="user_id" value="" />
				<input type="submit" name="block" value="Confirm" class="btn btn-danger btn-xs" />
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
				
			</div>
			</form>
		</div>
	</div>
</div>