<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p']; 
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_st_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_pan']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pan'] = $_SESSION['SESS_search_pan'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = "  AND t1.request_date >= '$st_date' AND t1.request_date <= '$en_date'";
	}
	$_SESSION['SESS_search_pan'] = $search_pan = $_POST['search_pan'];
	
	//$search_id = get_new_user_id($search_username);
	
	if($search_pan !=''){
		$qur_set_search = " AND pan_no = '$search_pan' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger" />
			</form>
		</td>
		
		<!--<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1" style="margin:0px">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" />
				</div>
			</div>
		</td>-->
		<td><input type="text" name="search_pan" placeholder="Search By PAN No." class="form-control" /></td>
		<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
		
	</tr>
</table>
</form>
<?php
if(isset($_POST['create_file']))
{
	$file_name = 'TDS_report'.date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.user_id,SUM(t1.request_crowd) request_crowd,t2.pan_no,t2.name,t3.username FROM withdrawal_crown_wallet t1 
	INNER JOIN 
	(SELECT user_id,pan_no,name FROM kyc WHERE pan_no <> '' $qur_set_search GROUP BY pan_no,user_id ORDER BY pan_no DESC)
	 t2 ON t1.user_id = t2.user_id
	LEFT JOIN users t3 ON t3.id_user = t1.user_id
	GROUP BY t2.pan_no,t2.user_id ORDER BY t2.pan_no DESC";
	$result = query_execute_sqli($SQL);              
	
	$insert_rows.="PAN No. \t Name \t User ID \t Total Paid \t TDS "; //\t Net Amount
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$username = $row['username'];
		$name = $row['name'];
		$tot_amt = $row['request_crowd'];
		$pan_no = $row['pan_no'];
		
		$tds = $tot_amt*$setting_transfer_tds/100;
		$net_amt = $tot_amt-$tds;
		
		$insert .= $pan_no.$sep;
		$insert .= $name.$sep;
		$insert .= $username.$sep;
		$insert .= $tot_amt.$sep;
		$insert .= $tds.$sep;
		//$insert .= $net_amt.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{

	$sql = "SELECT t1.user_id,SUM(t1.request_crowd) request_crowd,t2.pan_no,t3.username,t2.name FROM withdrawal_crown_wallet t1 
	INNER JOIN (SELECT user_id,pan_no,name FROM kyc WHERE pan_no <> '' $qur_set_search GROUP BY pan_no,user_id 
	ORDER BY pan_no DESC) t2 ON t1.user_id = t2.user_id
	LEFT JOIN users t3 ON t3.id_user = t1.user_id
	GROUP BY t2.pan_no,t2.user_id ORDER BY t2.pan_no DESC";
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	
	$sqlk = "SELECT COUNT(*) num FROM (SELECT * FROM ($sql) t1 GROUP BY t1.pan_no) t2";
	//$sqlk = "SELECT COUNT(*) num FROM ($sql) t1 GROUP BY t1.pan_no";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$totalrows = $tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">PAN No.</th>
				<th class="text-center">Name</th>
				<th class="text-center" width="30%">Associated ID's</th>
				<th class="text-center">Total Paid</th>
				<th class="text-center">TDS</th>
				<!--<th class="text-center">Net Amount</th>-->
			</tr>
			</thead>
			<?php
			//$pnums = ceil ($totalrows/$plimit);
			//if ($newp==''){ $newp='1'; }
				
			//$start = ($newp-1) * $plimit;
			//$starting_no = $start + 1;
			
			//$sr_no = $starting_no;
			
			$amt_arr = $pan_arr = $user_arr = $name_arr = array();
			
			$query = query_execute_sqli("$sql");
			while($row = mysqli_fetch_array($query))
			{
				$user_id = $row['user_id'];
				$username = $row['username'];
				$name = $row['name'];
				$request_crowd = $row['request_crowd'];
				$pan_no = $row['pan_no'];
				
				$tot_amt = $amount*100/(100-($withdrwal_money_tax+$admin_tax));
				$tds = $tot_amt*$withdrwal_money_tax/100;
				$adm_tax = $tot_amt*$admin_tax/100;
				
				if(!in_array($pan_no,$pan_arr)){
					$pan_arr[] = $pan_no;
					$name_arr[] = $name;
				}
				$pan_key = array_search($pan_no,$pan_arr);
				
				$user_arr[$pan_key][] = $username;
				
				$amt_arr[$pan_key][] = $request_crowd;
			}
			
			
			$totalrows = count($pan_arr);
			$lpnums = ceil ($tot_rec/$plimit);
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			$s_num = ($plimit*$newp);
			if($qur_set_search != ""){
				$s_num = 1;
			}
			
			for($i = $start; $i < $s_num ; $i++){
				$tot_amt = array_sum($amt_arr[$i]);
				//$tot_amt = $net_amt*10000/(95*95);
				$tds = $tot_amt*$setting_transfer_tds/100;
				$net_amt = $tot_amt-$tds;
				?>	
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$pan_arr[$i]?></td>
					<td><?=$name_arr[$i]?></td>
					<td><?=implode(" , ",$user_arr[$i])?></td>
					<td>&#36;<?=round($tot_amt,2)?></td>
					<td>&#36;<?=round($tds,2)?></td>
					<!--<td>&#36;<?=round($net_amt,2)?></td>-->
				</tr> <?php
				$sr_no++;
				
			} ?>
		</table> <?PHP
		
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>

