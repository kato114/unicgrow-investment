<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 10;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
$search_clause = array(" where "," And ");
$can_search  = $inc_search = "";
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_serarch_mem']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['serarch_mem'] = $_SESSION['SESS_serarch_mem'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['serarch_mem'] != ''){
		$_SESSION['SESS_serarch_mem'] = $_POST['serarch_mem'];
		if($_SESSION['SESS_serarch_mem'] == 1){
			$can_search = " and (t1.mode IN(1)) ";
		}
		elseif($_SESSION['SESS_serarch_mem'] == 2){
			$can_search = " and (t1.mode IN(2)) ";
		}else{
			$can_search = " and t1.mode = '0' ";
		}
		
	}
		
	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$inc_search = " and t1.user_id = '$search_id' ";
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-3">
		<select name="serarch_mem" class="form-control">
			<option value="">Search Members</option>
			<option value="1">Approved Member</option>
			<option value="2">Cancelled Member</option>
			<option value="3">Pending Member</option>
		</select>
	</div>
	<div class="col-md-3">
		<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
	</div>
	<div class="col-md-3">
		<input type="submit" value="Search" name="Search" class="btn btn-info">
	</div>
	<div class="col-md-3">
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger" />
		</form>
	</div>
</form>
<div class="col-md-12">&nbsp;</div>

<?php
if(isset($_POST['create_file']))
{
	$file_name = 'Business_report'.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['sql_bus_data'];
	$result = query_execute_sqli($SQL);  
	$insert_rows.="User ID \t Name \t Package \t Rental Bonus Received \t Growth Bonus Received \t Pending Balance \t Request Date \t Paid Date \t Activation Date \t Beneficiery Name \t Account No \t Bank Name \t IFSC Code \t Status"; //\t Net Amount
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id = $row['id'];
		$user_id = $row['user_id'];
		$name = $row['name'];
		$req_date = $row['req_date'];
		$mode = $row['mode'];
		$req_date = date('d/m/Y' , strtotime($row['req_date']));
		$paid_date = date('d/m/Y' , strtotime($row['paid_date']));
		
		$roi_bonus = round($row['roi'],2);
		$binary_bonus = round($row['bin'],2);
		
		
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		
		switch($mode){
			case 0 : $status = "Pending";	break;
			case 1 : $status = "Approved";	break;
			case 2 : $status = "Cancelled";	break;
		}
		
		$my_plan = my_package($user_id);
		$plan_amt = $my_plan[5];
		$act_date = get_user_active_investment_with_date($user_id)[1];
		$pending_bonus = $plan_amt - ($roi_bonus+$binary_bonus);
		
		$benf = $row['name'];
		$ac_no = $row['bank_ac'];
		$bank = $row['bank'];
		$bank_code = $row['ifsc'];
	
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $plan_amt.$sep;
		$insert .= $roi_bonus.$sep;
		$insert .= $binary_bonus.$sep;
		$insert .= $pending_bonus.$sep;
		$insert .= $req_date.$sep;
		$insert .= $paid_date.$sep;
		$insert .= $act_date.$sep;
		$insert .= $benf.$sep;
		$insert .= $ac_no.$sep;
		$insert .= $bank.$sep;
		$insert .= $bank_code.$sep;
		$insert .= $status.$sep;
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

if(isset($_POST['submit'])){
	$user_id = $_POST['user_id'];
	$table_id = $_POST['table_id'];
	
	if($_POST['submit'] == 'Approve'){
		$sql = "UPDATE cancel_investment SET mode = 1, approve_date = NOW() 
		WHERE id = '$table_id' AND user_id = '$user_id'";//paid_date = DATE_ADD(NOW(), INTERVAL 2 MONTH), 
		
		$MSGS = "Approved Investment Successfully !!";
	}
	if($_POST['submit'] == 'Cancel'){
		$sql = "SELECT * FROM income WHERE user_id='$user_id' and type=2 ORDER BY id DESC LIMIT 1";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query); 
		if($num > 0){
			while($ro = mysqli_fetch_array($query)){
				$incomed_id = $ro['incomed_id'];
			}
			$sql = "UPDATE reg_fees_structure SET mode = 1 WHERE id = '$incomed_id' AND user_id = '$user_id'";
		}
		else{
			$sql = "UPDATE reg_fees_structure SET mode = 1 WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
		}
		query_execute_sqli($sql);
		$sql = "UPDATE cancel_investment SET mode = 2, approve_date = NOW() 
				WHERE id = '$table_id' AND user_id = '$user_id'";
		
		$MSGS = "Cancelled Investment Successfully !!";
	}
	query_execute_sqli($sql);
	$page_value = isset($_GET['p'])?"&p=".$_GET['p']:'';
	?> <script>alert("<?=$MSGS?>"); window.location="index.php?page=<?=$val.$page_value?>";</script> <?php
}

$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name,t3.name,t3.bank_ac,t3.bank,t3.branch,t3.ifsc,COALESCE(t4.roi,0) roi,COALESCE(t5.bin,0) bin,t6.update_fees
FROM cancel_investment t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
LEFT JOIN kyc t3 ON t1.user_id = t3.user_id
LEFT JOIN (SELECT COALESCE(SUM(amount),0) roi ,user_id FROM income t1 WHERE type = 2 $inc_search  GROUP BY user_id) t4 ON t1.user_id = t4.user_id
LEFT JOIN (SELECT COALESCE(SUM(amount),0) bin ,user_id FROM income t1 WHERE type = 4 $inc_search GROUP BY user_id) t5 ON t1.user_id = t5.user_id
LEFT JOIN (select * from reg_fees_structure where id in(select max(id) from reg_fees_structure t1 where 1=1 $inc_search group by user_id)) t6 ON t1.user_id = t6.user_id
 where 1=1 AND t1.user_id > 0 $inc_search $can_search
GROUP BY t1.user_id
ORDER BY id DESC";
	

//$b_data_report = get_business_data_report($sql);
$_SESSION['sql_bus_data'] = $sql;
$SQL = "$sql LIMIT $tstart,$tot_p ";

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
mysqli_free_result($query);
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0){ ?>

	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="3">Total Members : <?=$tot_rec?></th>
			<th colspan="3">Total Rental Bonus: <?=$b_data_report[0]?></th>
			<th colspan="3">Total Growth Bonus: <?=$b_data_report[1]?></span></th>
			<th colspan="3">Total Pending Bonus: <?=$b_data_report[2]?></span></th>
		</tr>
		</thead>
	</table>
		
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr.No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Package</td>
			<th class="text-center">Rental Bonus Received</th>
			<th class="text-center">Growth Bonus Received</th>
			<th class="text-center">Pending Balance</th>
			<!--<th class="text-center">Total ROI</td>
			<th class="text-center">Total Received</td>
			<th class="text-center">Total Pending</td>-->
			<th class="text-center">Request Date</td>
			<th class="text-center">Paid Date</td>
			<th class="text-center">Activation Date</td>
			<th class="text-center">Account Info</td>
			<th class="text-center">Status</td>
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
			$name = $row['name'];
			$req_date = $row['req_date'];
			$mode = $row['mode'];
			$req_date = date('d/m/Y' , strtotime($row['req_date']));
			$paid_date = date('d/m/Y' , strtotime($row['paid_date']));
			
			$roi_bonus = round($row['roi'],2);
			$binary_bonus = round($row['bin'],2);
			
			
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			switch($mode){
				case 0 : $status = "<form method='post' action=''>
						<input type='hidden' name='table_id' value='$id' />
						<input type='hidden' name='user_id' value='$user_id' />
						<input type='submit' name='submit' value='Approve' class='btn btn-success btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Approve !! &quot;);' />
						<br /><br />
						<input type='submit' name='submit' value='Cancel' class='btn btn-danger btn-xs' onclick='javascript:return confirm(&quot; Are You Sure? You want to Cancel !! &quot;);' />
						
					</form>";	break;
				case 1 : $status = "<B class='text-success'>Approved</B>";	break;
				case 2 : $status = "<B class='text-danger'>Cancelled</B>";	break;
			}
			
			$my_plan = my_package($user_id);
			$plan_amt = $my_plan[5];
			$act_date = get_user_active_investment_with_date($user_id)[1];
			$pending_bonus = $plan_amt - ($roi_bonus+$binary_bonus);
			
			$benf = $row['name'];
			$ac_no = $row['bank_ac'];
			$bank = $row['bank'];
			$bank_code = $row['ifsc'];
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td>&#36;<?=$plan_amt?></td>
				<td><?=$roi_bonus?></td>
				<td><?=$binary_bonus?></td>
				<td><?=$pending_bonus?></td>
				<td><?=$req_date?></td>
				<td><?=$paid_date?></td>
				<td><?=$act_date?></td>
				<td class="text-left">
					<b>Beneficiery Name :</b> <?=$benf?><br>
					<b>Account No. :</b> <?=$ac_no?><br>
					<b>Bank Name :</b> <?=$bank?><br>
					<b>IFSC Code :</b> <?= $bank_code?>
				</td>
				<td><?=$status?>
					<!--<form method="post" action="index.php?page=<?=$val?>" target="_blank">
						<input type="hidden" name="table_id" value="<?=$id?>" />
						<input type="hidden" name="user_id" value="<?=$user_id?>" />
						<input type="submit" name="approve" value="Approve" class="btn btn-success btn-xs" />
						<br /><br />
						<input type="submit" name="cancel" value="Cancel" class="btn btn-danger btn-xs" />
					</form>-->
				</td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	mysqli_free_result($que);
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>


