<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>
<form method="post" action="index.php?page=direct_member">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	

<?php
$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_username'] != '')
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	if($search_username !=''){
		$qur_set_search = " WHERE id_user = '$search_id' ";
	}
}

$sql = "SELECT * FROM users $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id_user) num FROM users $qur_set_search";
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
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Total Direct Members</th>
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
		while($row = mysqli_fetch_array($que))
		{ 	
			$user_id = $row['id_user'];
			$username = $row['username'];
			$real_parent = $row['real_parent'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			
			$sql = "SELECT * FROM users WHERE real_parent = '$user_id' ";	
			$quer = query_execute_sqli($sql);
			$num = mysqli_num_rows($quer);
		
			if($num == 0)
			{
				$num = "No child Found";
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$num?></td>
				<td>
					<form action="index.php?page=direct_members" method="post">
						<input type="hidden" name="real_parent" value="<?=$user_id?>" />
						<input type="submit" value="View All" name="view" class="btn btn-info">
					</form>
				</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>