<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = "";
//$qur_kyc = "AND `url` LIKE '%kyc%'";
//$qur_profile = "AND `url` LIKE '%edit-profile%'";
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['search_page'] = $_SESSION['SESS_search_page'];
	$_POST['search_status'] = $_SESSION['SESS_search_status'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_page'],$_SESSION['SESS_search_status']);
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND DATE(t1.date_time) BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND t1.member_id = '$search_id' ";
	}
	if($_POST['search_ip'] !=''){
		$_SESSION['SESS_search_ip'] = $search_ip = $_POST['search_ip'];
		$qur_set_search = " AND t1.ip_add = '$search_ip' ";
	}
	
	if($_POST['search_page'] !=''){
		$_SESSION['SESS_search_page'] = $search_page = $_POST['search_page'];
		$qur_set_search = " AND `url` LIKE '%$search_page%'";
	}
}

?>
<table class="table table-bordered">
	<tr>
		<td>
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="Search" value="Search" />
				<select name="search_page" class="form-control" onchange="this.form.submit();">
					<option value="">Search Page</option>
					<?php
					$arr = array();
					$sql = "SELECT * FROM panel_work_history WHERE panel_id = 1 GROUP BY url ";
					$que = query_execute_sqli($sql);
					while($row = mysqli_fetch_array($que)){ 
						$url = explode('page=',$row['url']);
						$pages = explode('&',implode('=',array_slice($url,1)))[0];
						
						$sqlk = "SELECT * FROM menu WHERE menu_file = '$pages' GROUP BY menu_file LIMIT 1";
						$quer = query_execute_sqli($sqlk);
						while($rows = mysqli_fetch_array($quer)){ 
							$menu_file = $rows['menu_file'];
							$menu = $rows['menu'];
							
							if(!in_array($menu,$arr)){
								$arr[] = $menu;
								?>
								<option value="<?=$menu_file?>" <?php if($_POST['search_page'] == $menu_file){ ?> selected="selected" <?php } ?>><?=$menu?></option> <?php
							}
						} 
					}
					unset($arr); ?>
					
					<!--<option value="1">KYC</option>
					<option value="2">Edit Profile</option>-->
				</select>
			</form>
		</td>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_ip" placeholder="Search By IP Address" value="<?=$_POST['search_ip']?>" class="form-control" />
		</td>
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" value="<?=$_POST['search_username']?>" class="form-control" />
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
/*$sql = "SELECT t1.*,t2.username,t2.email FROM panel_work_history t1 
LEFT JOIN admin t2 ON t1.member_id = t2.id_user
WHERE t1.panel_id = 2 $qur_kyc $qur_set_search
UNION
SELECT t1.*,t2.username,t2.email FROM panel_work_history t1 
LEFT JOIN admin t2 ON t1.member_id = t2.id_user
WHERE t1.panel_id = 2 $qur_profile $qur_set_search";*/	


$sql = "SELECT t1.*,t2.username,t2.email, t3.username user_panel_id FROM panel_work_history t1 
LEFT JOIN admin t2 ON t1.member_id = t2.id_user AND t1.panel_id > 1
LEFT JOIN users t3 ON t1.member_id = t3.id_user AND t1.panel_id = 1
WHERE panel_id = 1 $qur_set_search";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<!--<th class="text-center">Admin User ID</td>-->
			<th class="text-center">User ID</td>
			<th class="text-center">Work On Page</td>
			<!--<th class="text-center">Remarks</td>-->
			<th class="text-center">Date</td>
			<th class="text-center">IP Address</td>
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
			$username = $row['username'];
			$email = $row['email'];
			$date = $row['date_time'];
			$ip_add = $row['ip_add'];
		 	$url = explode('page=',$row['url']);
			$post_data = $row['post_data'];
			$user_panel_id = $row['user_panel_id'];
			$panel_id = $row['panel_id'];
			
			parse_str($post_data, $output);
			$user_id = get_user_name($output['user_id']);
			
			
			$pages = explode('&',implode('=',array_slice($url,1)))[0];
			
			if($panel_id > 1){
				$sql = "SELECT menu FROM admin_menu WHERE menu_file = '$pages'";
			}
			else{
				$sql = "SELECT menu FROM menu WHERE menu_file = '$pages'";
			}
			$menu_name = mysqli_fetch_array(query_execute_sqli($sql))[0];
			
			if($pages == 'all_kyc'){
				$menu_name = "KYC $coment";
			}
			
			
			/*if(strpos($post_data, "approve_kyc") !== false or strpos($post_data, "approve_allkyc") !== false) {
				$remarks = 'Approved KYC';
			}
			elseif(strpos($post_data, "cancel_kyc") !== false or strpos($post_data, "cancel_allkyc") !== false) {
				$remarks = 'Cancelled KYC';
			}
			
			elseif(strpos($post_data, "edit_profile") !== false or strpos($post_data, "submit=Update") !== false) {
				$remarks = 'Update Profile';
			}
			elseif(strpos($post_data, "edit_profile") !== false or strpos($post_data, "submit=Submit") !== false) {
				$remarks = 'View Profile';
			}
			else{
				$remarks = 'View KYC';
			}*/
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<!--<td><?=$username;?></td>-->
				<td><?=$user_panel_id?></td>
				<td><?=$menu_name?></td>
				<!--<td><?=$remarks?></td>-->
				<td><?=$date?></td>
				<td><?=$ip_add?></td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>