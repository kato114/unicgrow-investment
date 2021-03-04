<?php
include('../security_web_validation.php');
?>
<?php
$newp = $_GET['p'];
$plimit = "15";
$id = $_SESSION['mlmproject_user_login'];

$sql = "SELECT * FROM news";

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center" width="10%">Sr. No.</th>
				<th class="text-center">Title</th>
				<th class="text-center">Updates</th>
				<th class="text-center">Date</th>	
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
		$title = $row['title'];
		$news = $row['news'];
		$date = date('d/m/Y', strtotime($row['date']));
		?>
		 <tr>
			<td class="text-center"><?=$sr_no;?></td>
			<td class="text-center"><?=$title;?></td>
			<td><?=$news;?></td>
			<td class="text-center"><?=$date;?></td>
		</tr> <?php
		$sr_no++;
	} ?>
	</table> <?php 
	pagging_initation($newp,$pnums,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>

