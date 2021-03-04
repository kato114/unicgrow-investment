<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");

$user_id = $_SESSION['mlmproject_user_id'];
$newp = $_GET['p'];
$plimit = "20";

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['search']))
{
	$_SESSION['SESS_strt_date'] = $st_date = $_POST['st_date'];
	$_SESSION['SESS_end_date'] = $en_date = $_POST['en_date'];
	
	if($st_date !='' and $en_date != '')
	{
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
}

//$SQL = "SELECT * FROM income WHERE user_id = '$user_id' AND type IN (3)";
$SQL = "SELECT t1.*,t3.username FROM income t1
LEFT JOIN reg_fees_structure t2 ON t1.incomed_id = t2.id
LEFT JOIN users t3 ON t2.user_id = t3.id_user
WHERE t1.user_id = '$user_id' AND t1.type IN ('$income_type[3]') $qur_set_search";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	while($row1 = mysqli_fetch_array($query))
	{ $tatal_amt = $tatal_amt+$row1['amount']; } 
	?>
	<!--<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
			<form method="post" action="index.php?page=direct-income">
				<div class="col-md-3 col-md-offset-5">
					<input type="text" name="st_date" placeholder="Start Date" class="form-control datepicker" />
				</div>
				<div class="col-md-3">
					<input type="text" name="en_date" placeholder="End Date" class="form-control datepicker" />
				</div>
				<div class="col-md-1"><input name="search" value="Search" class="btn btn-info" type="submit" /></div>
			</form>
			</div>
		</div>
	</div>-->
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="2">Total Referral Bonus : &#36; <?=round($tatal_amt,2);?></th></tr>
		</thead>
		<tr>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th> 
			<!--<th class="text-center">Income By </th>-->
		</tr>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$que = query_execute_sqli("$SQL LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = round($row['amount'],5); 
			$incomed_id = $row['username'];
			?>
			<tr>
				<td class="text-center">&#36; <?=$amount?></td>
				<td class="text-center"><?=$date?></td>
				<!--<td class="text-center"><?=$incomed_id?></td>-->
			</tr> <?php
		}
		?>
	</table> 
	<?php pagging_initation($newp,$pnums,$val);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
