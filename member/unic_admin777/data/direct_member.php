<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "25";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$default_data[0] = "LEFT JOIN users t3 ON t3.id_user = t2.id_user AND t3.step = 1 AND t3.type = 'B'";
$default_data[1] = "LEFT JOIN users t4 ON t4.id_user = t2.id_user AND t4.step = 0 AND t4.type = 'B'";
$default_data[2] = "LEFT JOIN users t5 ON t5.id_user = t2.id_user AND t5.type != 'B' ";

$default_field[0] = " COUNT(t3.id_user) act_dirct"; 
$default_field[1] = " COUNT(t4.id_user) dct_dirct"; 
$default_field[2] = " COUNT(t5.id_user) blk_dirct"; 

$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_userid'] = $_SESSION['SESS_search_userid'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
	$_POST['mem_status'] = $_SESSION['SESS_mem_status'];
}
else{
	unset($_SESSION['search_userid'],$_SESSION['SESS_st_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_mem_status']);
}
if(isset($_POST['Search'])){
	if($_POST['search_userid'] !=''){
		$_SESSION['SESS_search_userid'] = $search_userid = $_POST['search_userid'];
		$search_id = get_new_user_id($search_userid);
		$qur_set_search = " WHERE t1.id_user = '$search_id'";
	}
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE t1.date >= '$st_date' AND t1.date <= '$en_date' ";
	}
	if($_POST['mem_status'] != ''){
		$_SESSION['SESS_mem_status'] = $mem_status = $_POST['mem_status'];
		switch($mem_status) {
			case 1 : 
					$default_data[0] = "LEFT JOIN users t3 ON t3.id_user = t2.id_user";
					$default_field[0] = " COUNT(t3.id_user) act_dirct, '0' dct_dirct, '0' blk_dirct"; 
					$qur_set_search = " WHERE t3.step = 1 AND t3.type = 'B'";
					$selmem1='selected="selected"';  
			break;
			case 2 : 
					$default_data[1] = "LEFT JOIN users t4 ON t4.id_user = t2.id_user";				
					$default_field[1] = " COUNT(t4.id_user) dct_dirct,'0 'act_dirct, '0' blk_dirct"; 
					$qur_set_search = " WHERE t4.step = 0 AND t4.type = 'B'";
					$selmem2='selected="selected"';  
			break;
			case 3 : 
					$default_data[2] = "LEFT JOIN users t5 ON t5.id_user = t2.id_user";
					$default_field[2] = " COUNT(t5.id_user) blk_dirct, '0' dct_dirct,'0 'act_dirct"; 
					$qur_set_search = " WHERE t5.type != 'B'";
					$selmem3='selected="selected"';	
			break;
		}
	}
}
?>


<div class="col-md-2">
	<form method="post" action="index.php?page=<?=$val?>">
		<input type="hidden" name="Search" value="Search" />
		<select name="mem_status" class="form-control" onchange="this.form.submit();">
			<option value="">Select Status</option>
			<option value="1" <?=$selmem1?>>Active Member</option>
			<option value="2" <?=$selmem2?>>Registered Member</option>
			<option value="3" <?=$selmem3?>>Block Member</option>
		</select>
	</form>
</div>
<form method="post" action="index.php?page=<?=$val?>">	
	<div class="col-md-3">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="st_date" placeholder="From Date" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="en_date" placeholder="To Date" class="form-control" />
			</div>
		</div>
	</div>
	
	<div class="col-md-3">
		<input type="text" name="search_userid" placeholder="Search By User ID" class="form-control" value="<?=$_POST['search_userid']?>" />
	</div>
	<div class="col-md-1 text-right">
		<input type="submit" value="Search" name="Search" class="btn btn-info btn-sm">
	</div>
</form>
<div class="col-md-12">&nbsp;</div>

<?php
//$sql = "SELECT * FROM users $qur_set_search";

$imp_join = implode(' ',$default_data);
$imp_field = implode(',',$default_field);
$sql = "SELECT t1.username,t1.id_user,t1.f_name,t1.l_name, COUNT(t2.id_user) tot_dirct, $imp_field FROM users t1
LEFT JOIN users t2 ON t1.id_user = t2.real_parent
$imp_join $qur_set_search
GROUP BY t1.id_user";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id_user) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
	
if($totalrows != 0){ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Total Direct</th>
			<th class="text-center">Active Direct</th>
			<th class="text-center">Registered</th>
			<th class="text-center">Blocked</th>
			<th class="text-center">More</th>
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
			$user_id = $row['id_user'];
			$username = $row['username'];
			$real_parent = $row['real_parent'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$tot_dirct = $row['tot_dirct'];
			$act_user = $row['act_dirct'];
			$dct_user = $row['dct_dirct'];
			$blk_user = $row['blk_dirct'];
			
			$btn = '';
			if($tot_dirct > 0){
				$btn = '<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal5" data-id="'.$user_id.'" data-username="'.$username.'">View All</button>';
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$tot_dirct?></td>
				<td><?=$act_user?></td>
				<td><?=$dct_user?></td>
				<td><?=$blk_user?></td>
				<td><?=$btn?>
					<!--<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal5"
					data-id="<?=$user_id?>" data-username="<?=$username?>">View All</button>
					<form action="index.php?page=direct_members" method="post">
						<input type="hidden" name="real_parent" value="<?=$user_id?>" />
						<input type="submit" value="View All" name="view" class="btn btn-info">
					</form>-->
				</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }

/*function  get_active_deactive_users($user_id, $type){
	switch($type){
		case 'act' : $ques = " AND step = 1 AND type = 'B'";	break;
		case 'dct' : $ques = " AND step = 0 AND type = 'B'";	break;
		case 'blk' : $ques = " AND type NOT IN ('B')";	break;
	}
	$sql = "SELECT id_user FROM users WHERE real_parent = $user_id $ques";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}*/
?>

<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var uname = $(e.relatedTarget).data('username');
        $.ajax({
            type : 'post',
            url : 'direct_users.php',
            data :  {'id': id, 'username': uname},
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
				<h4 class="modal-title">Member Direct</h4>
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

							