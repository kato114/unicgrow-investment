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
		$qur_set_search = "  and t1.request_date >= '$st_date' AND t1.request_date <= '$en_date'";
	}
	$_SESSION['SESS_search_pan'] = $search_pan = $_POST['search_pan'];
	
	//$search_id = get_new_user_id($search_username);
	
	if($search_pan !=''){
		$qur_set_search = " and pan_no = '$search_pan' ";
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
	
	$SQL = $_SESSION['pan_sql'];
	
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

	/*$sql = "SELECT t1.user_id,SUM(t1.request_crowd) request_crowd,t2.pan_no,t3.username,t2.name FROM withdrawal_crown_wallet t1 
	INNER JOIN (SELECT user_id,pan_no,name FROM kyc WHERE pan_no <> '' $qur_set_search GROUP BY pan_no,user_id 
	ORDER BY pan_no DESC) t2 ON t1.user_id = t2.user_id
	LEFT JOIN users t3 ON t3.id_user = t1.user_id
	GROUP BY t2.pan_no,t2.user_id ORDER BY t2.pan_no DESC";*/
	
	$sql = "SELECT GROUP_CONCAT(user_id) pan_id,count(t1.pan_no) cnt ,pan_no,SUM(update_fees) tot_invst,SUM(tot_with) tot_with,SUM(comm_wallet) tot_com,SUM(e_wallet) tot_e_wall,SUM(comp_wallet) tot_compny_wallet, SUM(tot_receive) tot_receive FROM (
	SELECT t1.*,t2.update_fees,COALESCE(SUM(t3.request_crowd),0) tot_with,t4.amount 'comm_wallet',t4.activationw 'e_wallet',t4.companyw 'comp_wallet',t5.amt tot_receive
	FROM (SELECT `user_id`,pan_no FROM `kyc` GROUP by pan_no,user_id) t1
	LEFT JOIN reg_fees_structure t2 ON t1.user_id = t2.user_id 
	LEFT JOIN withdrawal_crown_wallet t3 ON t1.user_id = t3.user_id
	LEFT JOIN wallet t4 ON t1.user_id =t4.id
	LEFT JOIN (SELECT user_id,SUM(amount) amt FROM income WHERE type = 2 GROUP by user_id) t5 ON t1.user_id = t5.user_id
	$qur_set_search
	WHERE t2.mode = 1 
	GROUP BY t1.user_id)
	t1 GROUP by t1.pan_no";

	$_SESSION['pan_sql'] = $sql;
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
				<th class="text-center">No. Of ID's</th>
				<th class="text-center">Total Investment</th>
				<th class="text-center">Total Received</th>
				<th class="text-center">Total Withdrawal</th>
				<th class="text-center">E-Wallet</th>
				<th class="text-center">Commission Wallet</th>
				<th class="text-center"></th>
			</tr>
			</thead>
			<?php
			$query = query_execute_sqli("$sql");
			while($row = mysqli_fetch_array($query))
			{
				$pan_id = $row['pan_id'];
				$pan_no = $row['pan_no'];
				$pan_cnt = $row['cnt'];
				$tot_invst = $row['tot_invst'];
				$tot_receive = $row['tot_receive'];
				$tot_with = $row['tot_with'];
				$tot_com = $row['tot_com'];
				$tot_e_wall = $row['tot_e_wall'];
				$tot_compny_wallet = $row['tot_compny_wallet'];

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
			
			?>	
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$pan_no?></td>
					<td><?=$pan_cnt?></td>
					<td>&#36;<?=round($tot_invst,2)?></td>
					<td>&#36;<?=round($tot_receive,2)?></td>
					<td>&#36;<?=round($tot_with,2)?></td>
					<td>&#36;<?=round($tot_e_wall,2)?></td>
					<td>&#36;<?=round($tot_com,2)?></td>
					<td>
						<form method="post" action="index.php?page=all_pan_reports" target="_blank">
							<input type="hidden" name="pan_id" value="<?=$pan_id?>" />
							<input type="submit" name="more" value="More" class="btn btn-warning btn-xs" />
						</form>
					
					</td>
				</tr>
				<?php
				$sr_no++;
			}	
			?>
		</table> <?PHP
		
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
?>

