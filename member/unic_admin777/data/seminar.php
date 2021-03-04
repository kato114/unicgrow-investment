<?php
include('../../security_web_validation.php');
session_start();

$newp = $_GET['p'];
$plimit = "20";



if(isset($_SESSION['seminr_succ']))
{
	echo $_SESSION['seminr_succ'];
	unset($_SESSION['seminr_succ']);
}

if(isset($_POST['delete_seminar']))
{
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `seminar` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `seminar` DROP `id`");
	query_execute_sqli("ALTER TABLE `seminar` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
			
	echo "<B class='text-success'>Seminar Delete Successfully !!</B>";
}

if(isset($_POST['update']))
{
	$table_id = $_POST['table_id'];
	$venue = $_POST['venue'];
	$organizer = $_POST['organizer'];
	$address = $_POST['address'];
	$desc = $_POST['desc'];
	$date = date('Y-m-d');
	$time = date('H:i:s');
	
	if($venue != "" and $organizer != "" and $address != "" and $desc != "")
	{
		$sql = "UPDATE `seminar` SET `venue` = '$venue',`organized_by` = '$organizer' , `date` = '$date' , 
		`time` = '$time' , `address` = '$address' , `description` = '$desc' WHERE id = '$table_id'";
		query_execute_sqli($sql);
		
		$_SESSION['seminr_succ'] = "<B class='text-success'>Seminar Edit Successfully !!</B>";
		?> <script>window.location = "index.php?page=seminar"; </script> <?php
	}
	else{ echo "<B class='text-danger'>Please fill all field !!</B>"; }
}
elseif(isset($_POST['edit_seminar']))
{
	$table_id=$_POST['table_id'];
	$sql = "select * from seminar where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{  
		$id = $row['id'];
		$venue = $row['venue'];
		$organized_by = $row['organized_by'];
		$address = $row['address'];
		$desc = $row['description'];
	}
	?>
	<form action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="table_id" />
	<table class="table table-bordered">
		<tr>
			<th>Venue</th>
			<td><input type="text" name="venue" value="<?=$venue?>" class="form-control" /></td>
		</tr>
		<tr>
			<th>Organized By</th>
			<td><input type="text" name="organizer" value="<?=$organized_by?>" class="form-control" /></td>
		</tr>
		<tr>
			<th>Address</th>
			<td><textarea name="address" class="form-control"><?=$address?></textarea></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><textarea name="desc" class="form-control"><?=$desc?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="update" class="btn btn-info" value="Update" />
			</td>
		</tr>
	</table>
	</form>
<?php
}
else
{ 
	$sqli = "select * from seminar";
	$query = query_execute_sqli($sqli);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">Date</th>
				<th class="text-center">Venue</th>
				<th class="text-center">Organized By</th>
				<th class="text-center">Time</th>
				<th class="text-center">Address</th>
				<th class="text-center">Description</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			$query = query_execute_sqli("$sqli LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($query))
			{  
				$id = $row['id'];
				$venue = $row['venue'];
				$organize_by = $row['organized_by'];
				$date = date('d/m/Y' , strtotime($row['date']));
				$time = $row['time'];
				$address = $row['address'];
				$desc = $row['description'];
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$date;?></td>
					<td><?=$venue;?></td>
					<td><?=$organize_by;?></td>
					<td><?=$time;?></td>
					<td><?=$address;?></td>
					<td><?=$desc;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer" value="" name="edit_seminar" title="Edit This Seminar" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;   border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer" name="delete_seminar" title="Delete This Seminar" />
						</form>
					</td>
				</tr> <?php	
				 $sr_no++;
			} ?>
		</table> <?php
		pagging_admin_panel($newp,$pnums,$val); 
	}
	else{ echo "<B class='text-danger'>There are no Seminar Found !!</b>";}
} ?>

