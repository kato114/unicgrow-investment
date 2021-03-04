<?php
ini_set('display_errors','on');
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../config.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNICGROW - Admin Panel</title>
<link rel="shortcut icon" href="images/logo.png" />
<style>
.text-center{
	text-align:center;
}
</style>

</head>
<body>
<?php
$search_id = $_SESSION['net_mem_id'];
$sql = "SELECT * FROM users where id_user= '$search_id'";

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ 
	
	$left_member = network_members($search_id,$position=0);
	$right_member = network_members($search_id,$position=1);
?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class="text-left" colspan="14">Binary Member Of <?=get_user_name($search_id)?></th>
		</tr>
		<tr>
			<th class="text-center" colspan="7">Left</th>
			<th class="text-center" colspan="7">Right</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Sponser ID</th>
			<th class="text-center">Position</th>
			<th class="text-center">Package</th>
			<th class="text-center">Date</th>
			
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Sponser ID</th>
			<th class="text-center">Position</th>
			<th class="text-center">Package</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php
		$sql = "select t1.username,t1.f_name,t1.l_name,t1.position,
				COALESCE(t2.update_fees,'*****') request_crowd,
				COALESCE(t2.date,'*****') date,t4.username sponser
				from users t1 
				left join (select user_id,max(id) id from reg_fees_structure where mode=1 group by user_id) t3 on t1.id_user = t3.user_id
				left join reg_fees_structure t2 on t3.id = t2.id
				left join users t4 on t1.real_parent = t4.id_user
				where t1.id_user in($left_member)
				group by t1.username
				order by t1.id_user asc";
		$que1 = query_execute_sqli($sql);
		$num1 = mysqli_num_rows($que1);
		$sql = "select t1.username,t1.f_name,t1.l_name,t1.position,
				COALESCE(t2.update_fees,'*****') request_crowd,
				COALESCE(t2.date,'*****') date,t4.username sponser
				from users t1  
				left join (select user_id,max(id) id from reg_fees_structure where mode=1 group by user_id) t3 on t1.id_user = t3.user_id
				left join reg_fees_structure t2 on t3.id = t2.id
				left join users t4 on t1.real_parent = t4.id_user
				where t1.id_user in($right_member)
				group by t1.username
				order by t1.id_user asc";
		$que2 = query_execute_sqli($sql);
		$num2 = mysqli_num_rows($que2);
		$i = 0;
		while($row = mysqli_fetch_array($que1))
		{ 	
			$usernames[$i] = $row['username'];
			$f_names[$i] =  ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$sponsers[$i] = $row['sponser'];
			$positions[$i] = $row['position'] == 0 ? "Left" : "Right";
			$package[$i] = $row['request_crowd'];
			$pdate[$i] = $row['date'];
			$i++;
		}
		$i = 0;
		while($row2 = mysqli_fetch_array($que2))
		{
			$username2[$i] = $row2['username'];
			$f_names2[$i] =  ucfirst($row2['f_name'])." ".ucfirst($row2['l_name']);
			$sponsers2[$i] = $row2['sponser'];
			$positions2[$i] = $row2['position'] == 0 ? "Left" : "Right";
			$package2[$i] = $row2['request_crowd'];
			$pdate2[$i] = $row2['date'];
			$i++;
		} 
		$max_cnt = max($num1,$num2);
		for($i = 1; $i <= $max_cnt; $i++){
		?>
			<tr class="text-center">
			<?php
				if($usernames[$i-1] != ""){ ?>
				<td><?=$i?></td>
				<td><?=$usernames[$i-1];?></td>
				<td><?=$f_names[$i-1];?></td>
				<td><?=$sponsers[$i-1];?></td>
				<td><?=$positions[$i-1];?></td>
				<td><?=$package[$i-1]?></td>
				<td><?=$pdate[$i-1]?></td>
				<?php
				}
				else{
					echo "<td colspan=7>&nbsp;</td>";
				}
				if($username2[$i-1] != ""){ ?>
				<td><?=$i?></td>
				<td><?=$username2[$i-1];?></td>
				<td><?=$f_names2[$i-1];?></td>
				<td><?=$sponsers2[$i-1];?></td>
				<td><?=$positions2[$i-1];?></td>
				<td><?=$package2[$i-1]?></td>
				<td><?=$pdate2[$i-1]?></td>
				<?php
				}
				else{
					echo "<td colspan=7>&nbsp;</td>";
				}
			?>
			
			</tr>
		<?php
		}
		 ?>
	</table>
	<?PHP
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
function network_members($search_id,$position){
	$result = "";
	$sqls = "SELECT id_user FROM users WHERE parent_id = '$search_id' AND position = $position";
	$quer = query_execute_sqli($sqls);	
	$ro = mysqli_fetch_array($quer);
	$id_total = $ro[0];
	if($id_total != ""){
		$result = $id_total.",".mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0];
	}
	return rtrim($result,",");
}
?>

</body>
</html>