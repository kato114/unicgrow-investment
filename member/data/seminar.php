<?php
include('../security_web_validation.php');

$newp = $_GET['p'];
$plimit = "15";
$id = $_SESSION['mlmproject_user_login'];

if(isset($_POST['more'])){
	$table_id = $_POST['table_id'];
	$query = query_execute_sqli("SELECT * FROM seminar WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$venue = $row['venue'];
		$organized_by = $row['organized_by'];
		$address = $row['address'];
		
		$date = date('d/m/Y', strtotime($row['date']));
	} ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-md-3"><h3>Date</h3></div>
			<div class="col-md-9"><h3><?=$date?></h3></div>
			
			<div class="col-md-3"><h3>Venue</h3></div>
			<div class="col-md-9"><h3><?=$venue?></h3></div>
			
			<div class="col-md-3"><h3>Organized By</h3></div>
			<div class="col-md-9"><h3><?=$organized_by?></h3></div>
			
			<div class="col-md-3"><h3>Address</h3></div>
			<div class="col-md-9"><h3><?=$address?></h3></div>
		</div>
	</div>
	<?php
}
else{
	$sql = "SELECT * FROM seminar";
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="text-center" width="10%">Sr. No.</th>
					<th class="text-center">Date</th>
					<th class="text-center">Venue</th>
					<th class="text-center">Action</th>	
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
				$venue = $row['venue'];
				$organized_by = $row['organized_by'];
				$address = $row['address'];
				$date = date('d/m/Y', strtotime($row['date']));
				?>
				<tr>
					<td class="text-center"><?=$sr_no;?></td>
					<td class="text-center"><?=$date;?></td>
					<td class="text-center"><?=$venue;?></td>
					<td class="text-center">
						<form action="" method="post">
							<input type="hidden" name="table_id" value="<?=$id?>" />
							<input type="submit" name="more" value="More" class="btn btn-warning" />
						</form>
					</td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php 
		pagging_initation($newp,$pnums,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
}
?>

