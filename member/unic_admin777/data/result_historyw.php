

<?php
include('../../security_web_validation.php');
//die("Please contact to customer care.");

include("../function/functions.php");

if(isset($_POST['rank'])){
	$_SESSION['SESS_win_rank'] = $_POST['rank'];
	$_SESSION['SESS_rdate'] = $_POST['rdate'];
}

$rank = $_SESSION['SESS_win_rank'];
$rdate = $_SESSION['SESS_rdate'];

if(isset($_POST['create_file'])){
	$file_name = "Lottery History".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['SQL_lottery_data'];
	$result = query_execute_sqli($SQL);              

	$insert_rows.="User ID \t Amount \t Lottery Date \t Lottery No.";
	$insert_rows.="\n";
	
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result)){
		$insert = "";
		$username = $row['username']; 
		$lottery_no = $row['lottery_no'];
		$ramount = round($row['ramount'],2); 
		$date = date('d/m/Y' , strtotime($row['date']));
		
		$insert .= $username.$sep;
		$insert .= $ramount.$sep;
		$insert .= $date.$sep;
		$insert .= $lottery_no.$sep;

		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['SQL_lottery_data']);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{ 
	$sql = "SELECT t1.*,t2.username FROM lottery_ticket t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.`rank` = '$rank' AND t1.`rdate` = '$rdate'";
	$_SESSION['SQL_lottery_data'] = $sql;
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	
	
	if($totalrows > 0){ ?>
		<div class="col-md-3">
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="submit" name="create_file" value="CreateExcel File" class="btn btn-warning btn-sm"/>
			</form>
		</div>
		<div class="col-sm-9 text-right">
			<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
				<i class="fa fa-reply"></i> Close Window
			</button>
		</div>
		<div class="col-sm-12">&nbsp;</div>
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Lottery Date</th>
				<th class="text-center">Lottery No.</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			
			$que = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($query)){
				$username = $row['username']; 
				$ticket_no = $row['ticket_no'];
				$lottery_no = $row['lottery_no'];
				$amount = round($row['amount'],2); 
				$date = date('d/m/Y' , strtotime($row['date']));
				$rdate = date('d/m/Y' , strtotime($row['rdate']));
				$ramount = $row['ramount']; 
				
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$username?></td>
					<td><?=$ramount?></td>
					<td><?=$date?></td>
					<td><?=$lottery_no?></td>
				</tr> <?php
				$sr_no++;
			}
			?>
		</table> <?php 
	}		
	else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
}
?>
