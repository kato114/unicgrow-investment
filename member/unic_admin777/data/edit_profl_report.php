<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['updated_by'] = $_SESSION['SESS_updated_by'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_updated_by']);
}

if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	

	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND t1.member_id = '$search_id' ";
	}
	
	if($_POST['updated_by'] != ''){
		$_SESSION['SESS_updated_by'] = $updated_by = $_POST['updated_by'];
		switch($updated_by) {
			case 1 : $quer = " AND t1.admin_id < 1000";	$sel1 = 'selected="selected"'; break;
			case 2 : $quer = " AND t1.admin_id = 1000";	$sel2 = 'selected="selected"'; break;
		}
		$qur_set_search = $quer;
	}
}
?>

<div class="col-md-2">
	<form method="post" action="index.php?page=<?=$val?>">
		<input type="hidden" name="Search" value="Search" />
		<select name="updated_by" class="form-control" onchange="this.form.submit();">
			<option value="">Select Updated By</option>
			<option value="1" <?=$sel1?>>Admin</option>
			<option value="2" <?=$sel2?>>User</option>
		</select>
	</form>
</div>
<form method="post" action="index.php?page=<?=$val?>">
<div class="col-md-2">
	<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
</div>
<div class="col-md-3">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="en_date" placeholder="End Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-2">
	<input type="submit" value="Search" name="Search" class="btn btn-info">
</div>
</form>

<?PHP
$sql = "SELECT t1.*,t2.username FROM profile_record t1
LEFT JOIN users t2 ON t1.member_id = t2.id_user 
WHERE t1.member_id > 0 $qur_set_search";
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

if($totalrows > 0){ ?>

	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Updated By</th>
			<th class="text-center">Date</th>
			<th class="text-center">Reffered By</th>
			<th class="text-center">Before Update</th>
			<th class="text-center">After Update</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
	
		$sr_no = $start+1;
	
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$member_id = $row['member_id'];
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$date = $row['date'];
			$admin_id = $row['admin_id'];
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			
			$before_update = $row['before_update'];
			$after_update = $row['after_update'];
			$remarks = $row['remarks'];
			
			$chng_by = "User"; 
			if($admin_id == 1){ $chng_by = "Admin"; }
			elseif($admin_id > 1 and $admin_id < 1000) { $chng_by = "Sub-Admin"; }
			
			if($date > 0)
			$date1 = date('d/m/Y', strtotime($date));
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$chng_by?></td>
				<td><?=$date1?></td>
				<td><?=$remarks?></th>
				<td><?=$before_update?></td>
				<td><?=$after_update?></td>
				
				<!--<td>
					<form action="index.php?page=current_profile" method="post" target="_blank">
						<input type="hidden" name="user_id" value="<?=$member_id?>" />
						<input type="submit" name="cr_prfl" value="Current Profile" class="btn btn-danger btn-sm" />
					</form> 
				</td>-->
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php  
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}
else{ echo "<B style='color:#FF0000;'>There are no information to show!</B>";  }
