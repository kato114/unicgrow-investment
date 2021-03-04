<?php
include('../security_web_validation.php');
include("condition.php");

$login_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_SESSION['MSG_CSN_INV'])){
	echo $_SESSION['MSG_CSN_INV'];
	unset($_SESSION['MSG_CSN_INV']);	
}

$sql = "SELECT * FROM cancel_investment WHERE user_id = '$login_id'";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Request Date</th>
			<th class="text-center">Total Investment</th>
			<th class="text-center">Total ROI</th>
			<th class="text-center">Total Received</th>
			<th class="text-center">Total Pending</th>
			<th class="text-center">Paid Date</th>
			<th class="text-center">Status</th>
			<th class="text-center">Invoice</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$s_no = $starting_no;

		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){
			$id = $row['id']; 
			$user_id = $row['user_id']; 
			$tot_roi = $row['tot_roi'];
			$recvd_roi = $row['received_roi'];
			$pend_roi = $row['pending_roi'];
			$req_date = $row['req_date'];
			$mode = $row['mode'];
			$req_date = date('d/m/Y' , strtotime($row['req_date']));
			$paid_date = date('d/m/Y' , strtotime($row['paid_date']));
			
			$status = "<B class='text-danger'>Owner</B>";
			
			
			$my_plan = my_package($login_id);
			$plan_amt = $my_plan[5];
			
			switch($mode){
				case 0 : $status = "<B class='text-warning'>Pending</B>";	
						$btn = "";
				break;
				case 1 : $status = "<B class='text-success'>Approved</B>";	
						$btn = "<button style='background:none; border:none; cursor:pointer;' class='text-info'>
							<i class='fa fa-info-circle'></i>
						</button>";
				break;
				case 2 : $status = "<B class='text-danger'>Cancelled</B>";	
						$btn = "";
				break;
			}
			?>
			<tr class="text-center">
				<td><?=$s_no?></td>
				<td><?=$req_date?></td>
				<td>&#36;<?=$plan_amt?></td>
				<td>&#36;<?=$tot_roi?></td>
				<td>&#36;<?=$recvd_roi?></td>
				<td>&#36;<?=$pend_roi?></td>
				<td><?=$paid_date?></td>
				<td><?=$status?></td>
				<td>
					<form method="post" action="invoice_req_invst.php" target="_blank" id="invoice_u">
						<input type="hidden" name="table_id" value="<?=$sale_id?>" />
						<input type="hidden" name="user_id" value="<?=$login_id?>" />
						<input type="hidden" name="date" value="<?=$req_date?>" />
						<input type="hidden" name="paid_date" value="<?=$paid_date?>" />
						<!--<button style="background:none; border:none; cursor:pointer;" class="text-info">
							<i class="fa fa-info-circle"></i>
						</button>-->
						<?=$btn?>
					</form>
				</td>
			</tr> <?php
			$s_no++;
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>