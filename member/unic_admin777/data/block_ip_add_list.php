<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

$newp = $_GET['p'];
$plimit = "20";

if(isset($_POST['submit']))
{
	$ip_address_id = $_REQUEST['ip_address'];
	query_execute_sqli("delete from block_ip_address where id = '$ip_address_id' ");

	echo "<B class='text-success'>IP Address Remove Successfully !</B>";
}

else{

	$q = query_execute_sqli("select * from block_ip_address ");
	$num = mysqli_num_rows($q);
	if($num == 0){
		echo "<B class='text-danger'>There Are No Blocked Ip Address !</B>";
	}
	else
	{ ?> 
		<table class="table table-bordered">
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">IP Address</th>
				<th class="text-center">&nbsp;</td>
			</tr>
			<?php
			$pnums = ceil ($num/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			$query = query_execute_sqli("$sqli LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($q))
			{ 
				$block_ip_address = $row['block_ip_address'];
				$id = $row['id']; ?> 
				<tr class="text-center">
					<form name="my_form" action="index.php?page=block_ip_add_list" method="post">
					<input type="hidden" name="ip_address" value="<?=$id?>"  />
					<td><?=$sr_no?></td>
					<td><?=$block_ip_address?></td>
					<td><input type="submit" name="submit" value="Delete" class="btn btn-info" /></td>
					</form>
				</tr> <?php 
			} ?>
		</table> <?php  
		$sr_no++;
		pagging_admin_panel($newp,$pnums,$val); 
	}
}  ?>

