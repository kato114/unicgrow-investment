<?php
include('../../security_web_validation.php');
//die("Please contact to customer care.");

include("condition.php");
include("../function/setting.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 30;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_ticket_no'],$_SESSION['SESS_username'],$_SESSION['SESS_chk_lottery']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['ticket_no'] = $_SESSION['SESS_ticket_no'];
	$_POST['username'] = $_SESSION['SESS_username'];
	$_POST['chk_lottery'] = $_SESSION['SESS_chk_lottery'];
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE DATE_FORMAT(t1.date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['ticket_no'] != ''){
		$ticket_no = $_POST['ticket_no'];
		$sql = "SELECT * FROM lottery_ticket WHERE ticket_no = '$ticket_no' and mode=0";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		if($num > 0){
			$qur_set_search .= " WHERE t1.ticket_no = '$ticket_no'";
			$_SESSION['SESS_ticket_no'] = $ticket_no;
		}
		else{
			echo "<B class='text-danger'>In-Correct Ticket No !!</B>";
		}
	}
	if($_POST['username'] !=''){
		$_SESSION['SESS_username'] = $search_userid = $_POST['username'];
		$search_id = get_new_user_id($search_userid);
		$qur_set_search = " WHERE t1.user_id = '$search_id'";
	}
	if($_POST['chk_lottery'] !=''){
		$_SESSION['SESS_chk_lottery'] = $chk_lottery = $_POST['chk_lottery'];
		$qur_set_search = " WHERE t1.lottery_no = '$chk_lottery'";
	}
}


if(isset($_POST['create_file'])){
	$file_name = "Lottery History".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['SQL_lottery_hist'];
	$result = query_execute_sqli($SQL);              

	$insert_rows.="Lottery No. \t Ticket ID \t User ID \t User Name \t Buy Date \t Result Date \t Win Amount \t Rank ";
	$insert_rows.="\n";
	
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result)){
		$insert = "";
		$username = $row['username']; 
		$ticket_no = $row['ticket_no'];
		$lottery_no = $row['lottery_no'];
		$amount = round($row['amount'],2); 
		$date = date('Y-m-d' , strtotime($row['date']));
		$rdate = date('Y-m-d' , strtotime($row['rdate']));
		$ramount = $row['ramount']; 
		$mode = $row['mode'];
		$rank = $row['rank'];
		$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
		
		$win_amt = $win_rank = "";
		if($mode == 1){
			$win_amt = $ramount > 0 ? $ramount : "";
			$win_rank = $rank > 0 ? $rank : "";
		}
		
		$insert .= $lottery_no.$sep;
		$insert .= $ticket_no.$sep;
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $date.$sep;
		$insert .= $rdate.$sep;
		$insert .= $win_amt.$sep;
		$insert .= $win_rank.$sep;

		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['SQL_lottery_hist']);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{ ?>
	<div class="col-md-1">
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="submit" name="create_file" value="Excel File" class="btn btn-danger btn-sm"/>
		</form>
	</div>
	<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-2">
		<select name="chk_lottery" class="form-control">
			<option value="">Select Lottery</option>
			<?php
			$sql = "SELECT * FROM lottery_ticket GROUP BY lottery_no";
			$query = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($query)){
				$lottery_no = $row['lottery_no'];
				$rdate = date('d/m/Y' , strtotime($row['rdate'])); ?>
				<option value="<?=$lottery_no?>"><?=$lottery_no?>&nbsp;&nbsp;-&nbsp;&nbsp; <?=$rdate?></option> 
				
				<?php
			}
			?>
		</select>
	</div>
	<div class="col-md-2">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="en_date" placeholder="End Date" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<input type="text" name="ticket_no" placeholder="Ticket No." class="form-control" />
	</div>
	<div class="col-md-2">
		<input type="text" name="username" placeholder="Username" class="form-control" />
	</div>
	<div class="col-md-1 text-right">
		<input type="submit" value="Search" name="Search" class="btn btn-warning btn-sm">
	</div>
	</form>	
	<div class="col-md-12">&nbsp;</div>
	<?php
	$sql = "SELECT t1.*,t2.username,t2.f_name, t2.l_name FROM lottery_ticket t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	$qur_set_search ORDER BY t1.id DESC";
	
	$_SESSION['SQL_lottery_hist'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT SUM(amount) amt , COUNT(id) num FROM ($sql) t1";
	$quer = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($quer)){
		$amount = $ro['amt'];
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	mysqli_free_result($quer);
	mysqli_free_result($query);
	if($totalrows > 0){ ?>
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th colspan="9">
					Total Ticket : <?=$tot_rec?>
					<div class="pull-right">Total Amount : &#36; <?=round($amount,2);?></div>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Lottery No.</th>
				<th class="text-center">Ticket ID</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Buy Date</th>
				<th class="text-center">Result Date</th>
				<th class="text-center">Result</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			
			if ($newp==''){ $newp='1'; }
		
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			
			$que = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($que)){
				$username = $row['username']; 
				$ticket_no = $row['ticket_no'];
				$lottery_no = $row['lottery_no'];
				$amount = round($row['amount'],2); 
				$date = date('d/m/Y' , strtotime($row['date']));
				$rdate = date('d/m/Y' , strtotime($row['rdate']));
				$ramount = $row['ramount']; 
				$mode = $row['mode'];
				$rank = $row['rank'];
				$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
				
				//$ramount = $mode == 0 ? "Wait" : ($ramount > 0 ? "&#36; $ramount" : "Lose");
				
				$win_amt = "<B class='text-warning'>Pending</B>";
				if($mode == 1){
					$win_amt = $ramount > 0 ? "Amount : &#36; ".$ramount : "-----------";
					$win_rank = $rank > 0 ? "Rank : "."<span class='label label-danger'>".$rank."</span>" : "";
				}
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$lottery_no?></td>
					<td><?=$ticket_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$date?></td>
					<td><?=$rdate?></td>
					<td><?=$win_amt?> <br /> <?=$win_rank?></td>
				</tr> <?php
				$sr_no++;
			}
			?>
		</table> <?php 
		mysqli_free_result($que);
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}		
	else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
}
?>
