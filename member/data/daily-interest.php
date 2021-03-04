<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include("function/setting.php");

$user_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="End Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	

<?php
$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND by_wallet = 1 AND mode = 1 $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(update_fees) amt , COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tamount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="10">Total Investment:- &nbsp; &#36; <?=$tamount;?></th></tr></thead>
		<tr>
			<th class="text-center" width="10%">Sr. No</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Return Profit</th>
			<th class="text-center">Top-Up To</th>
			<th class="text-center">Start Date</th>
			<th class="text-center">Number Of Month</th>
			<th class="text-center">Growth</th>
		</tr>
		<?php 
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$i = 1;
		$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($r = mysqli_fetch_array($q))
		{
			$id = $r['id'];
			$date = date('d/m/Y', strtotime($r['date']));
			$type = $r['by_wallet'];
			$reg_fees = $r['dr'];
			$update_fees = $r['update_fees'];
			$rcw_id = $r['rcw_id'];
			$profit = $r['profit'];
			$user_id = $r['user_id'];
			$total_days = $r['total_days'];
			$st_date = $r['date'];
			$start_date = date('Y-m-d', strtotime($st_date."+1 Month"));
			$en_date = date('Y-m-d', strtotime($st_date."+".$total_days." Month"));
			$end_date = date('d/m/Y', strtotime($en_date));
			
			$amount = $update_fees;
			$topupto = get_user_name($user_id);
			$update_fees*$profit/100;
			
			/*$s_date = date('D', strtotime($st_date."+15 Days"));
			if($s_date == 'Sat'){ $start_date = date('Y-m-d', strtotime($st_date."+17 Days")); }
			if($s_date == 'Sun'){ $start_date = date('Y-m-d', strtotime($st_date."+16 Days")); }*/
						
			if($type == 0){ $status = "Activation Wallet"; }
			else{ $status = "Grow Well Wallet"; }
			
			$strt_date = date('d/m/Y', strtotime($start_date));
			?>
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$date;?></td>
				<td>&#36; <?=$amount;?></td>
				<td>&#36;<?=$profit;?></td>
				<td><?=$topupto;?></td>
				<td><?=$strt_date;?></td>
				<td><?=$total_days;?></td>
				<td>
					<!--<form action="index.php?page=box_growth" method="post">
						<input type="hidden" name="url" value="<?=$val;?>" />
						<input type="hidden" name="table_id" value="<?=$id;?>" />
						<input name="growth" value="Growth" class="btn btn-info" type="submit" />
					</form>-->
					<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal5"
					data-table_id="<?=$id?>" data-url="<?=$val?>">Growth</button>
				</td>
			</tr>
			<?php
			$i++;
		}
		?>
	</table>
	<?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }



$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = '$income_type[2]'";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{
	while($row1 = mysqli_fetch_array($query))
	{ $tatal_amt = $tatal_amt+$row1['amount']; } 
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="2">Total ROI Bonus : &#36; <?=round($tatal_amt,2);?></th></tr>
		</thead>
		<tr>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th> 
		</tr>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = round($row['amount'],5); ?>
			<tr>
				<td class="text-center">&#36; <?=$amount?></td>
				<td class="text-center"><?=$date?></td>
			</tr> <?php
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
//else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

<script>
$(document).ready(function(){
	$('#myModal5').on('show.bs.modal', function (e) {
		var url = $(e.relatedTarget).data('url');
		var table_id = $(e.relatedTarget).data('table_id');
        $.ajax({
            type : 'post',
            url : 'data/box_growth.php',
            data :  {'url': url, 'table_id': table_id},
            success : function(data){
				$('.show_user').html(data);
			}
		});
	});
});
</script>
<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title">Growth</h4>
				<!--<small class="font-bold">Member Direct</small>-->
			</div>
			<div class="modal-body">
				<div class="show_user"></div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>
