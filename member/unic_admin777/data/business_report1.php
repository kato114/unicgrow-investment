<?php
include('../../security_web_validation.php');

include("../function/functions.php");
include("../function/setting.php");
$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_business_amount'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['business_amount'] = $_SESSION['SESS_business_amount'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	$_SESSION['SESS_business_amount'] = $business_amount = $_POST['business_amount'];
	
	
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
		$st_date = date('m/d/Y', strtotime($st_date));
		$en_date = date('m/d/Y', strtotime($en_date));
	}
	if($business_amount !=''){
		//$qur_set_search .= "having total_business >= '$business_amount' ";
	}
}

?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="business_amount" placeholder="Business" class="form-control" value="<?=$business_amount?>" required /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" value="<?=$st_date?>" required />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" value="<?=$en_date?>" required />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	

<?php
$sr_no = 1;
$sql = "Select t1.* from network_users t1";
$query = query_execute_sqli($sql);
$cnt = mysqli_num_rows($query);
while($row = mysqli_fetch_array($query)){
	$user_id = $row['user_id'];
	$left_n = $row['left_network'];
	$right_n = $row['right_network'];
	$left_busi = get_business($left_n,$qur_set_search);
	$right_busi = get_business($right_n,$qur_set_search);
	if($left_busi >= $business_amount and $right_busi >= $business_amount){
		if($sr_no == 1){?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Total Left Business</th>
				<th class="text-center">Total Right Business</th>
				<th class="text-center">Business Matching</th>
			</tr>
			</thead>
			<?php		
		}
			$date = date('d/m/Y', strtotime($r['date']));
			$user_id = get_user_name($user_id);
			$total_business = $r['total_business'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td>&#36;<?=$left_busi?></td>
				<td>&#36;<?=$right_busi?></td>
				<td>&#36;<?=min($left_busi,$right_busi)?></td>
			</tr> <?php
			$sr_no++;
		 ?>
	<?php
	
	}
	if($cnt == $sr_no){
		print "</table> ";
	}
	//pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
mysqli_free_result($query);
function get_business($network,$search){
	$sql = "select coalesce(sum(update_fees),0) total_business from reg_fees_structure where user_id in($network) and boost_id = 0 and mode=1  $search";//and plan not in('x','y','Z')
	$query = query_execute_sqli($sql);
	$row =  mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $row;
}
?>