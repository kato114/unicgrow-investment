<?php
include("../config.php");
include("../function/functions.php");

$user_id = $_REQUEST['id'];
$username = $_REQUEST['username'];


$sql = "SELECT * FROM users WHERE real_parent = '$user_id'";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0){ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="4">
				Total Direct Members of <B class="text-danger"><?=$username?></B> <i class="fa fa-arrow-right"></i> 
				<span class='label label-info'><?=$totalrows?></span>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		$que = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($que)){ 	
			$user_id = $row['id_user'];
			$username = $row['username'];
			$type = $row['type'];
			$step = $row['step'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			$top_up = get_paid_member($user_id);
			if($top_up == 0) { $status = "Inactive"; }
			else { $status = "Active"; }
			if($row['type']== 'D'){ $status = "Block"; }
			
			/*if($step == 1 and $type == 'B'){ $status = "<span class='label label-success'>Active</span>"; }
			elseif($step == 0 and $type == 'B') { $status = "<span class='label label-warning'>Registered</span>"; }
			elseif($type != 'B'){$status = "<span class='label label-danger'>Blocked</span>"; }*/
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
}
else{ echo "<B class='text-danger'>No Child Found!</B>";  }
?>
