<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>

<div class="col-sm-12">&nbsp;</div>
<?php
$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['user_id'])){
	unset($_SESSION['user_id'],$_SESSION['username'],$_SESSION['position'],$_SESSION['member'],$_SESSION['date'],$_SESSION['tot_bus']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['position'] = $_POST['position'];
	$_SESSION['member'] = $_POST['member'];
	$_SESSION['date'] = $_POST['date'];
	$_SESSION['tot_bus'] = $_POST['tot_bus'];
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$position = $_SESSION['position'];
$member = $_SESSION['member'];
$date = $_SESSION['date'];
//$tot_bus = $_SESSION['tot_bus'];




$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM reg_fees_structure t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user 
WHERE t1.user_id IN ($member) AND t1.date = '$date' GROUP BY t1.user_id
ORDER BY t1.id DESC ";
//AND t1.mode IN (1,189,190) AND DATE_ADD(t1.start_date,INTERVAL -1 MONTH) = '$date'
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="4">
				<?=ucwords($position)?> Downline Business of <B class="text-danger"><?=$username?></B> 
				on <i class="fa fa-clock-o"></i> <?=date('d/m/Y', strtotime($date))?>
				<div class="pull-right" >Total Business : <span id="cb_total">45</span>&#36;</div>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Topup Amount</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$tot_amt = 0;
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$user_id = $row['user_id'];
			$username = $row['username'];
			$type = $row['type'];
			$amount = $row['request_crowd'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			$class = '';
			$pre_topup = get_user_previous_topup($user_id,$date);
			if($pre_topup > 0){
				$amount = $amount-$pre_topup;
				$class = 'text-danger';
			}
			$tot_amt += $amount;
			?>
			<tr class="text-center">
				<td class="<?=$class?>"><?=$sr_no?></td>
				<td class="<?=$class?>"><?=$username;?></td>
				<td class="<?=$class?>"><?=$name?></td>
				<td class="<?=$class?>"><?=$amount?>&#36;</td>
			</tr> <?php
			$sr_no++;
		} 
		?>
	</table> 
	<?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>No information to show!</B>";  }

function get_user_previous_topup($user_id,$date){
	$sql = "SELECT request_crowd FROM reg_fees_structure WHERE user_id = '$user_id' AND mode IN (98,99) 
	AND date < '$date' ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($num > 0){
		return $result;
	}
	else{ return 0; }
}
?>
<script>
	$(document).ready(function(){
		$('#cb_total').html(<?=$tot_amt?>);
	});
</script>