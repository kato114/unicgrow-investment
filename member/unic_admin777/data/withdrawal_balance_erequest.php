<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");

$newp = $_GET['p'];
$plimit = 100;
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

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " and T1.user_id = '$search_id' ";
	}
}
?>
<div class="row">
	<div class="col-md-4 col-md-offset-8">
	<form method="post" action="index.php?page=withdrawal_balance_erequest">
	<table class="table table-bordered">
		<tr>
			<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
			<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
		</tr>
	</table>
	</form>	
	</div>
</div>

<?php

if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Accept All')
	{
		$sql = $_SESSION['SQL_withdraw'];
		$query = query_execute_sqli($sql);
		if($query){
			while($row = mysqli_fetch_array($query)){
				$req_id = $row['id'];
				$u_id = $row['user_id'];
				$req_amount = $row['request_crowd'];
				$req_amount_btc = $row['request_crowd'];
				$information = $_REQUEST['information'];
				$wall_type = $row['ac_type'];
				
				$wal_field = 'amount';
				$total_amount = get_user_wallet($u_id);
				
				
				$accept_date= $systems_date;
				$sql = "UPDATE withdrawal_crown_wallet SET accept_date = '$systems_date_time' , status = 2 , 
				sys_comment = '$information' WHERE id = '$req_id' ";
				query_execute_sqli($sql);
				if(strtoupper($soft_chk) == "LIVE"){
					$username_log = get_user_name($u_id);
					include("../function/logs_messages.php");
					data_logs($u_id,$data_log[20][0],$data_log[20][1],$log_type[9]);
					
					$income_log = $req_amount;
					$date = $accept_date;
					$for = "Withdrawal his Balance";
					include("../function/logs_messages.php");
					data_logs($u_id,$data_log[8][0],$data_log[8][1],$log_type[4]);
					
					$from_user = $u_id;
					$to_user = $u_id;
					
					//$phone_to = get_user_phone($u_id);
					//include("../function/sms_message.php");
					//send_sms($url_sms,$request_yourself,$phone_to);  //send sms
					$log_username = get_user_name($u_id);
					$income_log = $req_amount;
					$date = $accept_date;
					$income_type_log = "Requested Fund from ADMIN";
					include("../function/logs_messages.php");
					data_logs($u_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
					
					$pay_request_username = get_user_name($u_id);
					$request_amount = $req_amount;
					$to = get_user_email($u_id);
					$title = "Payment Transfer Message";
					$db_msg = $payment_transfer_message;
					include("../function/full_message.php");
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
					//$SMTPChat = $SMTPMail->SendMail();
					
					$phone = get_user_phone($u_id);
							
					$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Bankwire will be done in 10 working days. https://www.unicgrow.com";
					send_sms($phone,$message);
				}
			}
		}
		
		print "<B class='text-danger'>Request Accepted!</B>";
	}
	elseif($_POST['submit'] == 'Cancel All')
	{
		$sql = $_SESSION['SQL_withdraw'];
		$query = query_execute_sqli($sql);
		if($query){
			while($row = mysqli_fetch_array($query)){
				$req_id = $row['id'];
				$u_id = $row['user_id'];
				$req_amount = $row['request_crowd'];
				$information = $_REQUEST['information'];
				$wall_type = $row['ac_type'];
				$tot_amt_cancel = $row['request_crowd']+$row['tax']+$row['cur_bitcoin_value'];
				$wal_field = 'amount';
				$total_amount = get_user_wallet($u_id);
				
				
				$accept_date= $systems_date_time;
				
				$sql = "update withdrawal_crown_wallet set accept_date = '$systems_date_time' , status = 3, description = '$information',sys_comment='$information' where id = '$req_id' ";
				query_execute_sqli($sql);
				$wal_field = 'amount';
				$total_amount = get_user_wallet($u_id);
				
				
				$tax_amount = 0;//($req_amount*100)/(100-$withdrwal_money_tax)-$req_amount;
				$bal_amount = $total_amount+$tot_amt_cancel;		
				
				$SQL = "UPDATE wallet SET $wal_field = '$bal_amount' , date = '$accept_date' WHERE id = '$u_id' ";
				query_execute_sqli($SQL);
				if(strtoupper($soft_chk) == "LIVE"){
					$pay_request_username = get_user_name($u_id);
					$phone = get_user_phone($u_id);
					$message = "Dear Member, Working Wallet withdrawal for username $pay_request_username is cancelled. Thanks, https://www.unicgrow.com";
					send_sms($phone,$message);
					
					$username_log = get_user_name($u_id);
					include("../function/logs_messages.php");
					data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);
				}
			}
			?>
			<script>
				alert("Request of Withwrawal Amount has been Canceled Successfully !"); 
				window.location = "index.php?page=withdrawal_balance_erequest";
			</script> <?php
		}
		
	}
	elseif($_POST['submit'] == 'Cancel')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$information = $_REQUEST['information'];
		$wall_type = $_REQUEST['wall_type'];
		
		$accept_date= $systems_date_time;
		$sql = "select * from withdrawal_crown_wallet where id = '$req_id' and status=0";
		$query = query_execute_sqli($sql);
		if($query){
			while($row = mysqli_fetch_array($query)){
				$tot_amt_cancel = $row['request_crowd']+$row['tax']+$row['cur_bitcoin_value'];
			}
			$sql = "update withdrawal_crown_wallet set accept_date = '$systems_date_time' , status = 3, description = '$information',sys_comment='$information' where id = '$req_id' ";
			query_execute_sqli($sql);
			$wal_field = 'amount';
			$total_amount = get_user_wallet($u_id);
			
			
			$tax_amount = 0;//($req_amount*100)/(100-$withdrwal_money_tax)-$req_amount;
			$bal_amount = $total_amount+$tot_amt_cancel;		
			
			$SQL = "UPDATE wallet SET $wal_field = '$bal_amount' , date = '$accept_date' WHERE id = '$u_id' ";
			query_execute_sqli($SQL);
			if(strtoupper($soft_chk) == "LIVE"){
				$pay_request_username = get_user_name($u_id);
				$phone = get_user_phone($u_id);
				$message = "Dear Member, Working Wallet withdrawal for username $pay_request_username is cancelled. Thanks, https://www.unicgrow.com";
				send_sms($phone,$message);
				
				$username_log = get_user_name($u_id);
				include("../function/logs_messages.php");
				data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);
			}
			?>
			<script>
				alert("Request of Withwrawal Amount has been Canceled Successfully !"); 
				window.location = "index.php?page=withdrawal_balance_erequest";
			</script> <?php
		}
		
	}
	elseif($_POST['submit'] == 'Pay Bank')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$req_amount_btc = $_REQUEST['req_amount_btc'];
		$information = $_REQUEST['information'];
		$wall_type = $_REQUEST['wall_type'];
		
		$total_amount =  get_user_roi_wallet($u_id);
		$wal_field = 'roi';
		if($wall_type == 1)
		{ 
			$wal_field = 'amount';
			$total_amount = get_user_wallet($u_id);
		}
		
		$accept_date= date('Y-m-d');
		$sql = "UPDATE withdrawal_crown_wallet SET action_date = '$systems_date_time' , accept_date = 
		'$systems_date_time' , status = 2 , description = 'Withdrawal Payout Manual' , api_description='xxxxxxxxxx', 
		transaction_hash='xxxxxxxxxx',sys_comment='$information' WHERE id = '$req_id' ";
		query_execute_sqli($sql);
		if(strtoupper($soft_chk) == "LIVE"){
			$username_log = get_user_name($u_id);
			include("../function/logs_messages.php");
			data_logs($u_id,$data_log[20][0],$data_log[20][1],$log_type[9]);
			
			$income_log = $req_amount;
			$date = $accept_date;
			$for = "Withdrawal his Balance";
			include("../function/logs_messages.php");
			data_logs($u_id,$data_log[8][0],$data_log[8][1],$log_type[4]);
			
			
			$from_user = $u_id;
			$to_user = $u_id;
			$log_username = get_user_name($u_id);
			$income_log = $req_amount;
			$date = $accept_date;
			$income_type_log = "Requested Fund from ADMIN";
			include("../function/logs_messages.php");
			data_logs($u_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
			
			$pay_request_username = get_user_name($u_id);
			$request_amount = $req_amount;
			$to = get_user_email($u_id);
			$title = "Payment Transfer Message";
			$db_msg = $payment_transfer_message;
			include("../function/full_message.php");
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			//$SMTPChat = $SMTPMail->SendMail();
			
			$phone = get_user_phone($u_id);
					
			$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Withdrawal will be done in 10 working days. www.atsolutions.com";
			send_sms($phone,$message);
		} ?>
		<script>alert("Request Accepted!"); window.location = "index.php?page=withdrawal_balance_erequest";</script> 
		<?php
	}
	else { echo "<B class='text-danger'>There Are Some Conflicts !</B>"; }	
}

elseif(isset($_POST['create_file']))
{
	$file_name = "withdrawal".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['SQL_withdraw'];
	$result = query_execute_sqli($SQL);              
	$insert_rows.="Withdrawal Request Of E-wallet " ;
	$insert_rows.="\n";
	$insert_rows.=" Username \t Name \t Phone(%) \t Total Withdrawal(USD) \t  Date \t Beneficiery Name \t Bank Account No. \t Bank Name \t Branch \t IFSC Code" ;
	$insert_rows.="\n";
	
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$username = $row['username'];
		$phone_no = $row['phone_no'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$wallet_type = $row['ac_type'];
		$wal_amt = $row['wal_amt'];
		$ex_tds = $row['tax'];
		$ex_adm_tax = $row['cur_bitcoin_value'];
		$amount = $row['request_crowd']+$ex_tds+$ex_adm_tax;
		$request_crowd = $row['request_crowd'];
		$bit_ac_no = $row['bank_ac'];
		
		$benf_name = $row['beneficiery_name'];
		$bank_ac = $row['bank_ac'];
		$bank_name = $row['bank_name'];
		$ifsc_code = $row['ifsc_code'];
		$branch = $row['branch'];

		$date = date('d/m/Y' , strtotime($row['date']));
		$payment_mode = $pm_name[$wallet_type-1];
		
		$benf = $ac_no = $bank = $bank_code = "";
		$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
		$query1 = query_execute_sqli($sql);
		while($rows = mysqli_fetch_array($query1))
		{
			$benf = $rows['name'];
			$ac_no = $rows['bank_ac'];
			$bank = $rows['bank'];
			$ifsc = $rows['ifsc'];
			$pan_no = $rows['pan_no'];
			$branch = $rows['branch'];
		}
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $phone_no.$sep;
		//$insert .= $wal_amt.$sep;
		$insert .= $amount.$sep;
		/*$insert .= $ex_tds.$sep;
		$insert .= $ex_adm_tax.$sep;
		$insert .= $request_crowd.$sep;*/
		
		$insert .= $date.$sep;
		$insert .= $benf.$sep;
		$insert .= sprintf("A/c - ".$ac_no).$sep;
		$insert .= $bank.$sep;
		$insert .= $branch.$sep;
		$insert .= $ifsc.$sep;
		
		
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
	<B>Click here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else
{
 	$sql = "SELECT T1.*,T2.username,T2.phone_no FROM withdrawal_crown_wallet T1 
	INNER JOIN users T2 ON T1.user_id = T2.id_user
	WHERE T1.status in(65,0) AND T1.request_crowd > 0 and ac_type=1
	$qur_set_search ORDER BY T1.date DESC";
	
	$_SESSION['SQL_withdraw'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COALESCE(SUM(T1.request_crowd),0) amt,COUNT(*) num FROM ($sql) T1 ";
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
			<form method=post>
			<tr>
				<td colspan="7">
					<textarea name="information" class="form-control" required="required" placeholder="Action To All"></textarea>
				</td>
				<td colspan="3">
					<input type="submit" name="submit" value="Accept All" class="btn btn-info" />
					<input type="submit" name="submit" value="Cancel All" class="btn btn-danger" />
				</td>
			</tr>
			</form>
			<tr>
				<th colspan="7">Total Payble Amount : <?=$tot_amt; ?> &#36;</th>
				<td colspan="2">
				<form method="post" action="">
				<input type="submit" name="create_file" value="Create Excel File" class="btn btn-warning btn-sm"/>
				</form>
				</td>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Phone</th>
				<th class="text-center">Total Withdrawal</th>
				<!--<th class="text-center">TDS</th>
				<th class="text-center">Admin Tax</th>
				<th class="text-center">Net Payble Amount</th>-->
				<th class="text-center">Bank Info</th>
				<th class="text-center">Date Time</td>
				<th class="text-center">Information</th>
				<th class="text-center">Action</td>
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
				$username = $row['username'];
				$phone = $row['phone_no'];
				$name = ucwords($row['f_name']." ".$row['l_name']);

				$request_amount = $row['request_crowd'];
				$tax = $row['tax'];
				$net_amt = $request_amount;
				$request_amount = $request_amount + $row['tax']+$row['cur_bitcoin_value'];
				$request_date = date('d/m/Y', strtotime($row['date']));
				$wallet_type = $row['ac_type'];
				$bit_ac_no = $row['ac_no'];
				$user_comment = $row['user_comment'];
				$pay_mode = $row['paid_mode'];
				
				$payment_mode = $pm_name[$wallet_type-1];
				
				$benf = $ac_no = $bank = $bank_code = "";
				$sql = "SELECT * FROM kyc WHERE user_id = '$u_id'";
				$query1 = query_execute_sqli($sql);
				while($rows = mysqli_fetch_array($query1))
				{
					$benf = $rows['name'];
					$ac_no = $rows['bank_ac'];
					$bank = $rows['bank'];
					$ifsc = $rows['ifsc'];
					$pan_no = $rows['pan_no'];
				}	

				$bank_info = "Benf. Name = ".$benf."<br>A/c No. = ".$ac_no." <br>Bank Name = ".$bank.'<br />IFSC No. = '.$ifsc;	
				?>
							
				<form action="" method="post">
				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="u_id" value="<?=$u_id?>" />
				<input type="hidden" name="req_amount" value="<?=$request_amount?>" />
				<input type="hidden" name="req_amount_btc" value="<?=$request_amount?>" />
				<input type="hidden" name="wall_type" value="<?=$wallet_type?>" />
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$phone?></td>
					<td><?=$request_amount?> &#36; </td>
					<!--<td><?=$tax?> &#36; </td>
					<td><?=$row['cur_bitcoin_value']?> &#36; </td>
					<td><?=$net_amt?> &#36;</td>-->
					<td class="text-left"><?=$bank_info?></td>
					<td><?=$request_date?></td>
					<td><textarea name="information" class="form-control"></textarea></td>
					<td>
						<input type="submit" name="submit" class="btn btn-info btn-xs" value="Pay Bank" />
						<br /><br />
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
?>