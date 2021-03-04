<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");

$_SESSION['newp'] = $newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>
<script type="text/javascript"> 
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
</script>
<?php
$r_uri = explode("/",$_SERVER['REQUEST_URI']);
$r_uri = $r_uri[count($r_uri)-1];


$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['search_bank'] = $_SESSION['SESS_search_bank'];
	$_POST['search_paymode'] = $_SESSION['SESS_search_paymode'];
}
else{
	unset($_SESSION['SESS_USERNAME'],$_SESSION['SESS_search_bank'],$_SESSION['SESS_search_paymode']);
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_search_bank'] = $search_bank = $_POST['search_bank'];
	$_SESSION['SESS_search_paymode'] = $search_paymode = $_POST['search_paymode'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND T1.user_id = '$search_id' ";
	}
	if($_POST['search_bank'] !=''){
		$qur_set_search = " AND T1.bank_name = '$search_bank' ";
		
		if($_POST['search_bank'] == 'none'){
			$qur_set_search = " AND T1.`bank_name` = '' ";
		}
	}
	if($_POST['search_paymode'] !=''){
		$qur_set_search = " AND T1.payment_mode = '$search_paymode' ";
		
		if($_POST['search_paymode'] == 'none'){
			$qur_set_search = " AND T1.`payment_mode` = '' ";
		}
	}
}

?>
	<div class="col-md-2">
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="hidden" name="Search" value="Search" />
			<select name="search_bank" class="form-control" onchange="this.form.submit();">
				<option value="">Search Bank</option>
				<?php
				for($i = 0; $i < count($bank_name_for_fund); $i++){ ?>
					<option value="<?=$bank_name_for_fund[$i]?>" <?php if($_POST['search_bank'] == $bank_name_for_fund[$i]){ ?> selected="selected" <?php } ?>><?=$bank_name_for_fund[$i]?></option> <?php
				} ?>
				<option value="none">None</option>
			</select>
		</form>
	</div>
	<div class="col-md-3">
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="hidden" name="Search" value="Search" />
			<select name="search_paymode" class="form-control" onchange="this.form.submit();">
				<option value="">Search By Payment Mode</option>
				<?php
				for($i = 0; $i < count($add_fund_mode_value); $i++){ ?>
					<option value="<?=$i?>" <?php if($_POST['search_bank'] == $add_fund_mode_value[$i]){ ?> selected="selected" <?php } ?>><?=$add_fund_mode_value[$i]?></option> <?php
				} ?>
			</select>
		</form>
	</div>
	<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-3 col-md-offset-2">
		<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
	</div>
	<div class="col-md-2 text-right">
		<input type="submit" value="Submit" name="Search" class="btn btn-info">
	</div>
	</form>	

<div class="col-md-12">&nbsp;</div>

<?php
$time= date('H:i:s');
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Accept')
	{
		$req_id = $u_id = $req_amount = $information = $sql = $SQL = NULL;
		$req_id = $_POST['id'];
		$u_id = $_POST['u_id'];
		$req_amount = $_POST['req_amount'];
		$information = $_POST['information'];
		
		$accept_date= $systems_date;
		$sql = "UPDATE fund_request SET app_date = '$systems_date' , paid = 1 ,admin_remarks = '$information' 
		WHERE id = '$req_id' AND paid = 0 ";
		if(query_execute_sqli($sql)){
			$SQL = "UPDATE wallet SET amount = amount + '$req_amount' , date = '$accept_date' 
			WHERE id = '$u_id' ";
			query_execute_sqli($SQL);
			insert_wallet_account($u_id , $u_id , $req_amount , $systems_date_time , $acount_type[6] ,$acount_type_desc[6], $mode=1 , get_user_allwallet($u_id,'amount'),$wallet_type[2],$remarks = "Add Fund From Admin");
		}
		?> <script> 
			alert('Request Accepted !'); window.location ="index.php?page=add_fund_request&p=<?=$_SESSION['newp']?>"; 
		</script> <?php
	}
	
	elseif($_POST['submit'] == 'Cancel')
	{
		$req_id = $_REQUEST['id'];
		$accept_date= date('Y-m-d');
		$time= date('H:i:s');
		$information = $_REQUEST['information'];
		$sql = "UPDATE fund_request SET app_date = '$systems_date' , paid = 3, admin_remarks = '$information' 
		WHERE id = '$req_id' ";
		query_execute_sqli($sql);
		
		?>
		<script>
			alert("Request of Add Fund has been Canceled Successfully !"); 
			window.location = "index.php?page=add_fund_request&p=<?=$_SESSION['newp']?>";
		</script> <?php
	}
	else { echo "<B class='text-danger'>There Are Some Conflicts !</B>"; }	
}

elseif(isset($_POST['create_file']))
{
	die('Comming Soon');
	$file_name = "withdrawal".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	/*$SQL = "SELECT T1.*,T2.ac_no,T2.beneficiery_name,T2.bank_ac,T2.bank_name,T2.ifsc_code,T2.username,
	T2.f_name,T2.l_name,T3.amount wal_amt  
	FROM withdrawal_crown_wallet as T1 
	INNER JOIN users as T2 ON T1.user_id = T2.id_user AND T1.status =65 AND T1.amount > 0 AND T1.ac_type = 1
	LEFT JOIN wallet T3 on T1.user_id = T3.id 
	$qur_set_search
	ORDER BY T1.date DESC";*/
	
	$SQL = $_SESSION['SQL_withdraw'];
	$result = query_execute_sqli($SQL);              

	$insert_rows.=" Username \t Name \t Wallet Amount \t Request Amount \t Date";
	$insert_rows.="\n";
	
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$username = $row['username'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$wallet_type = $row['ac_type'];
		$wal_amt = $row['wal_amt'];
		$amount = $row['amount'];
		$request_crowd = $row['request_crowd'];
		$bit_ac_no = $row['ac_no'];
		
		$benf_name = $row['name'];
		$bank_ac = $row['bank_ac'];
		$bank_name = $row['bank'];
		$ifsc_code = $row['ifsc'];

		$date = date('d/m/Y' , strtotime($row['request_date']));
		$payment_mode = $pm_name[$wallet_type-1];
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $wal_amt.$sep;
		$insert .= $amount.$sep;
		$insert .= $withdrwal_money_tax."%".$sep;
		//$insert .= $request_crowd.$sep;
		$insert .= $payment_mode.$sep;
		$insert .= "Working Wallet".$sep;
		//$insert .= $bit_ac_no.$sep;
		$insert .= $date.$sep;
		$insert .= $benf_name.$sep;
		$insert .= sprintf("A/c - ".$bank_ac).$sep;
		$insert .= $bank_name.$sep;
		$insert .= $ifsc_code.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['SQL_withdraw']);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click here for download file =</B> <a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else
{
	$sql = "SELECT T1.*,T2.username,T2.f_name,T2.l_name,T3.amount wal_amt FROM fund_request as T1 
	INNER JOIN users as T2 ON T1.user_id = T2.id_user
	LEFT JOIN wallet T3 on T1.user_id = T3.id 
	WHERE T1.paid = 0 AND T1.amount > 0 $qur_set_search
	ORDER BY T1.id DESC";
	
	$_SESSION['SQL_withdraw'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COALESCE(SUM(T1.amount),0) amt,COUNT(T1.id) num FROM ($sql) T1 ";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$tot_amt = $ro['amt'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<!--<tr>
				<th colspan=5 height=40>
					<form method=post>
						<textarea name="information" class="input-small" style="height:20px; width:100px;" required="required" placeholder="Action To All"></textarea>
						<input type=submit name=submit value='Accept All' class="button1" style="vertical-align: text-bottom"/>
						<input type=submit name=submit value='Cancel All' class="button1" style="vertical-align: text-bottom"/>
					</form>
				</th>
			</tr>-->
			<tr>
				<th colspan="12">Total Amount : <?=$tot_amt; ?> &#36;
					<div class="pull-right">
					<form method="post" action="">
						<input type="submit" name="create_file" value="Create Excel File" class="btn btn-warning btn-sm"/>
					</form>
					</div>
				</td>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Request Amount(&#36;)</th>
				<th class="text-center">Date</th>
				<th class="text-center">Mode</th>
				<th class="text-center">Tr.No./Chq.No.</th>
				<th class="text-center">Bank</th>
				<th class="text-center">Receipt</th>
				<th class="text-center" width="30%">Member Remark</th>
				<th class="text-center" width="30%">Information</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$sr_no = $start + 1;
		
			$sql = "$sql LIMIT $start,$plimit ";
			$query = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($query))
			{
				$id = $row['id'];
				$u_id = $row['user_id'];
				$tax = $row['tax'];
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$wal_amt = $row['wal_amt'];
				$request_amount = $row['amount'];
				$request_date = date('d/m/Y', strtotime($row['date']));
				$payment_mode = $row['payment_mode'];
				$bit_ac_no = $row['ac_no'];
				$receipt = $row['receipt'];
				$user_comment = $row['remarks'];
				$tr_no = $row['transaction_no'];
				$chq_no = $row['chq_no'];
				$bank_name = $row['bank_name'];
				
				
				$trans_no = $chq_no;
				if($chq_no == ''){
					$trans_no = $tr_no;
				}
				
				$payment_mode = $add_fund_mode_value[$payment_mode];
				
				$recpt = "";
				if($receipt != ''){
					$recpt = "<img src='../images/mlm_epin_receipt/$receipt' width='50' />";
				}
				
				?>
							
				<form name="inact" action="<?=$r_uri?>" method="post">
				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="u_id" value="<?=$u_id?>" />
				<input type="hidden" name="req_amount" value="<?=$request_amount?>" />
				
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					
					<td><?=$request_amount?> &#36; </td>
					<td><?=$request_date?></td>
					<td><?=$payment_mode?></td>
					<td><?=$trans_no?></td>
					<td><?=$bank_name?></td>
					<td><?=$recpt?></td>
					<td><?=$user_comment?></td>
					<td><textarea name="information" class="form-control"></textarea></td>
					<td>
						<input type="submit" name="submit" class="btn btn-info btn-xs" value="Accept" /><br /><br />
						<input type="submit" name="submit" class="btn btn-danger btn-xs" value="Cancel" />	
					</td>
				</tr>
				</form> <?PHP
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There is no request !</B>"; }
 }  
 
 
function get_account_information($id)
{
 	$qur = query_execute_sqli("select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($qur))
	{
 		$acc_info[0] = $row['liberty_email'];
 		$acc_info[1] = $row['ge_currency'];	 	 	
 	}
	return $acc_info;	
} 
?>