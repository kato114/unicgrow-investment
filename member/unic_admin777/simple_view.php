<?php
ini_set('display_errors','on');
session_start();
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
.text-left{
	text-align:left;
}
</style>

</head>
<body>
<?php
$search_id = $_SESSION['net_mem_id'];

$sql = $_SESSION['sql_net_memb'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th colspan="9" class="text-left">
				Binary Member Of : <B class="text-danger"><?=get_user_name($search_id)?></B>
			</th>
		</tr>
		
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Activate Date</th>
			<th class="text-center">Sponser ID</th>
			<th class="text-center">Package</th>
			<!--<th class="text-center">Position</th>-->
			<th class="text-center">Booster Detail</th>
			<th class="text-center">Monthly ROI</th>
			<th class="text-center">A/C No.</th>
			<th class="text-center">Bank Name</th>
			<th class="text-center">Branch</th>
			<th class="text-center">IFSC Code</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$username = $row['username'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$position = $row['position'] == 0 ? "Left" : "Right";
			$sponser = $row['sponser'];
			$packag = $row['reg_amt'];
			$act_date = $row['act_date'];
			$boost_id = $row['boost_id'];
			$roi = $row['ROI'];
			$bank_ac = $row['bank_ac'];
			$bank = $row['bank'];
			$branch = $row['branch'];
			$ifsc = $row['ifsc'];
			
			
			$package = my_package($id)[0];
			
			if($package == ''){ $package = "*****"; }
			
			if($boost_id > 0) { $boost_status = "<span class='label label-success'>Booster</span>"; }
			else{ $boost_status = "<span class='label label-warning'>Non Booster</span>"; }
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$act_date?></td>
				<td><?=$sponser?></td>
				<td><?=$package?></td>
				<!--<td><?=$position?></td>-->
				<td><?=$boost_status?></th>
				<td><?=$roi?></td>
				<td class="text-left"><?=$bank_ac?></td>
				<td class="text-left"><?=$bank?></td>
				<td class="text-left"><?=$branch?></td>
				<td class="text-left"><?=$ifsc?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>