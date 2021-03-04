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
$plimit = $page_limit;
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
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Accept')
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
		
		$accept_date= $systems_date;
		$sql = "UPDATE withdrawal_crown_wallet SET accept_date = '$systems_date_time' , status = 0,sys_comment='$information' WHERE id = '$req_id' ";
		query_execute_sqli($sql);
		
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
		$SMTPChat = $SMTPMail->SendMail();
		
		$phone = get_user_phone($u_id);
				
		$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Bankwire will be done in 10 working days. https://www.unicgrow.com";
		send_sms($phone,$message);
		
		print "Request Accepted!";
	}
	elseif($_POST['submit'] == 'Paid Manual')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$request_amount_usd = $_REQUEST['request_amount_usd'];
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
		$sql = "UPDATE withdrawal_crown_wallet SET action_date = '$systems_date_time',accept_date='$systems_date_time' , status = 2 ,description='Withdrawal Payout Manual',api_description='xxxxxxxxxx',transaction_hash='xxxxxxxxxx',sys_comment='$information'
		WHERE id = '$req_id' ";
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
					
			$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Withdrawal will be done in 10 working days. https://www.unicgrow.com";
			send_sms($phone,$message);
		}
		echo '<script language="javascript">';
		echo 'alert("Request Accepted!")';
		echo '</script>';
		
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=withdrawal_balance_request_inr\"";
		echo "</script>";
	}
	elseif($_POST['submit'] == 'Cancel')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$req_amount_usd = $_REQUEST['req_amount_usd'];
		$information = $_REQUEST['information'];
		$wall_type = $_REQUEST['wall_type'];
		
		$accept_date= date('Y-m-d');
		$sql = "update withdrawal_crown_wallet set accept_date = '$systems_date_time' , status = 3, description = '$information',sys_comment='$information' where id = '$req_id' ";
		query_execute_sqli($sql);
		
		if($wall_type == 5)
		{ 
			$wal_field = 'amount';
			$total_amount = get_user_wallet($u_id);
		}
		
	 	$tax_amount = 0;//($req_amount*100)/(100-$withdrwal_money_tax)-$req_amount;
		$bal_amount = $total_amount+$req_amount_usd*100/(100-$withdrwal_money_tax);		
		
		$SQL = "UPDATE wallet SET $wal_field = '$bal_amount' , date = '$accept_date' WHERE id = '$u_id' ";
		query_execute_sqli($SQL);
		
		$username_log = get_user_name($u_id);
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);

		echo '<script language="javascript">';
		echo 'alert("Request of Withwrawal Amount has been Canceled Successfully !")';
		echo '</script>';
		
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=withdrawal_balance_request_inr\"";
		echo "</script>";
	}
	/*elseif($_POST['submit'] == 'Accept All')
	{
		$information = $_REQUEST['information'];
		$sql = "select * from paid_unpaid where paid = 0 and amount > 0";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$request_amount = $row['amount'];
			$query1 = query_execute_sqli("select * from wallet where id = '$u_id' ");
			while($row1 = mysqli_fetch_array($query1))
			{
				$total_amount = $row1['amount'];
			}
			$accept_date= date('Y-m-d');
			
			$sql = "UPDATE paid_unpaid SET paid_date = '$accept_date' , paid = 1 , paid_inform = '$information' 
			WHERE id = '$id' ";
			query_execute_sqli($sql);
						
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
			$req_amount = get_USD_TO_BITCOIN($currency = "USD",$req_amount);
			
			$sql = "INSERT INTO withdrawal_crown_wallet(user_id , request_crowd , ac_type , date , description)
			VALUES('$u_id','$req_amount','1','$systems_date_time','Withdrawal Payout')";
			query_execute_sqli($sql);
			
			
			//$phone_to = get_user_phone($u_id);
			//include("../function/sms_message.php");
			//send_sms($url_sms,$request_yourself,$phone_to);  //send sms
	
			//$bal_amount = $total_amount+$req_amount;		
			$SQL = "UPDATE wallet SET amount = amount+'$bal_amount' , date = '$accept_date' WHERE id = '$u_id' ";
			//query_execute_sqli($SQL);
			
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
			$SMTPChat = $SMTPMail->SendMail();
			
			$phone = get_user_phone($u_id);
					
			$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Bankwire will be done in 10 working days. www.bitexnetwork.com.";
			send_sms($phone,$message);
			
		}print "Request Accepted!";
	}
	elseif($_POST['submit'] == 'Cancel All')
	{
		$sql = "select * from paid_unpaid where paid = 0 and amount > 0";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query))
		{
			$req_id = $row['id'];
			$u_id = $row['user_id'];
			$request_amount = $row['amount'];
			$information = $_REQUEST['information'];
			$accept_date= date('Y-m-d');
			
			query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 2 , paid_inform = '$information' where id = '$req_id' ");
		
			$username_log = get_user_name($u_id);
			include("../function/logs_messages.php");
			data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);
		}
		print "Request of Withwrawal Amount has been Canceled Successfully .";
	}*/
	else { print "There Are Some Conflicts."; }	
}


elseif(isset($_POST['create_file']))
{
	$file_name = "withdrawal USD".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT t1.* , t2.username,t2.f_name,t2.l_name,t2.beneficiery_name,t2.bank_ac,t2.bank_name,t2.ifsc_code, 
	t3.amount wal_amt 
	FROM withdrawal_crown_wallet t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	LEFT JOIN wallet t3 ON t1.user_id = t3.id
	WHERE t1.status =65 AND t1.amount > 0 AND t1.ac_type = 5 
	ORDER BY t1.date DESC";
	$result = query_execute_sqli($SQL);              

	$insert_rows.=" Username \t Name \t Wallet Amount \t Request Amount(USD) \t Admin Tax(%) \t USD Amount \t Payment Mode \t Withdrawal Type \t Date \t Bank Info";
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

		$bank_info = "Beneficiary = ".$row['beneficiery_name'].", A/c Number = ".$row['bank_ac'].", Bank Name = ".$row['bank_name'].', IFSC Code = '.$row['ifsc_code'];
			
		$date = date('d/m/Y' , strtotime($row['date']));
		$payment_mode = $pm_name[$wallet_type-1];
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $wal_amt.$sep;
		$insert .= $amount.$sep;
		$insert .= $withdrwal_money_tax."%".$sep;
		$insert .= $request_crowd.$sep;
		$insert .= $payment_mode.$sep;
		$insert .= "Working Wallet".$sep;
		$insert .= $date.$sep;
		$insert .= $bank_info.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	echo "<B style='color:#008000;'>Excel File Created Successfully !</B>";
	?>
	<p><a style="color:#333368; font-weight:600;" href="index.php?page=<?=$val?>">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a> <?php
}

/*elseif(isset($_POST['create_file']))
{
	$sql = "select t2.f_name as First_Name, t2.l_name as Last_name, t2.phone_no as Phone, t2.username as Username , t3.amount as Wallet , t1.amount as RequestAmount,t2.ac_no as AcountNo , t2.branch as Branch , t2.bank as Bank, t2.bank_code as BankCode  , t2.beneficiery_name as BeneficieryName , t1.request_date as Date from paid_unpaid as t1 inner join users as t2 on t1.user_id = t2.id_user inner join wallet as t3 on t2.id_user = t3.id and t1.paid = 0 and t1.amount > 0 0 AND T1.ac_type = 5";
	$result = query_execute_sqli($sql);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		$file_name = time()."Withdrawal Request".date('Y-m-d');
		//define separator (defines columns in excel & tabs in word)
		$sep = "\t"; //tabbed character
		$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
		$schema_insert = "";
		$schema_insert_rows = "";
		//start of printing column names as names of MySQL fields
		
		
		//start of adding column names as names of MySQL fields
		for ($i = 0; $i < mysqli_num_fields($result); $i++)
		{
		$schema_insert_rows.=strtoupper(str_replace("_"," ",mysqli_field_name($result,$i))) . "\t";
		}
		$schema_insert_rows.="\n";
		//echo $schema_insert_rows;
		fwrite($fp, $schema_insert_rows);
		//end of adding column names
		
		
		//start while loop to get data
		while($row = mysqli_fetch_row($result))
		{
		//set_time_limit(60); //
			
			$schema_insert = "";
			for($j=0; $j<mysqli_num_fields($result);$j++)
			{
				if(!isset($row[$j]))
				$schema_insert .= "NULL".$sep;
				elseif ($row[$j] != "")
				{
					if($j == 6)
					{
						$schema_insert .= strtoupper("AC - ".strip_tags("$row[$j]").$sep);
					}
					else
					{
						$schema_insert .= strtoupper(strip_tags("$row[$j]").$sep);
					}	
				}
				else
				$schema_insert .= "".$sep;

			}
			$schema_insert = str_replace($sep."$", "", $schema_insert);
			
			//this corrects output in excel when table fields contain \n or \r
			//these two characters are now replaced with a space
			
			$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
			$schema_insert .= "\n";
			//$schema_insert = (trim($schema_insert));
			//print $schema_insert .= "\n";
			//print "\n";
			
		
			fwrite($fp, $schema_insert);
		}
		fclose($fp);
		
		print "Excel File Created Successfully !!<br><br><br>";
		
	}	
	else
	{
		print "There is No users to write !<br><br><br>";
	}
	
	?>
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=<?=$val?>">Back</a></p>
		click here for download file = <a href="mlm_user excel files/<?=$file_name;?>.xls"><?=$file_name; ?></a>	
<?php 

}*/
else
{
	$sql = "SELECT T1.*,T1.date as wt_date,T2.* FROM withdrawal_crown_wallet as T1 
	INNER JOIN users as T2 ON T1.user_id = T2.id_user AND T1.status =65 AND T1.amount > 0 AND T1.ac_type = 5 
	ORDER BY T1.date DESC";
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{ ?>
		<table align="center" hspace = 0 cellspacing=0 cellpadding=0 border=0 height="40" width=950>
			<tr>
				<th colspan=11 height=40 style="font-size:20px" align="left">
					<form method=post>
						To Create Excel File 
						<input type="submit" name="create_file" value="Create Excel File" class="button1"/>
					</form>
				</th>
				<!--<th colspan=5 height=40>
					<form method=post>
						<textarea name="information" class="input-small" style="height:20px; width:100px;" required="required" placeholder="Action To All"></textarea>
						<input type=submit name=submit value='Accept All' class="button1" style="vertical-align: text-bottom"/>
						<input type=submit name=submit value='Cancel All' class="button1" style="vertical-align: text-bottom"/>
					</form>
				</th>-->
			</tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr>
				<th class="text-center" align="center">User Name</th>
				<th class="text-center" align="center">Bonus Wallet</th>
				<!--<th class="text-center" align="center">Bonus Wallet</th>-->
				<th class="text-center" align="center">Request Amount(USD)</th>
				<th class="text-center" align="center">Admin Tax</th>
				<th class="text-center" align="center">Net Amount(USD)</th>
				<th class="text-center" align="center">Net Amount(USD)</th>
				<th class="text-center" align="center">Withdrawal</th>
				<th class="text-center" align="center">Date</th>
				<th class="text-center" align="center">Comment</th>
				<th class="text-center" align="center" width="15%">Bank Info</th>
				<th class="text-center" align="center">Information</th>
				<th class="text-center" align="center">Action</th>
			</tr>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
		
			$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		
			while($row = mysqli_fetch_array($query))
			{
				$id = $row['id'];
				$u_id = $row['user_id'];
				$username = $row['username'];
				$request_amount = $row['amount'];
				//$request_amount_usd = $row['request_crowd'];
				$request_date = $row['wt_date'];
				$wallet_type = $row['ac_type'];
				$bit_ac_no = $row['ac_no'];
				$ac_tax = $row['tax'];
				$user_comment = $row['user_comment'];
				
				$bank_info = "Name = ".$row['f_name']." ".$row['l_name']."<br>Beneficiary = ".$row['beneficiery_name']."<br>A/c Number = ".$row['bank_ac']." <br>Bank Name = ".$row['bank_name'].'<br />IFSC Code = '.$row['ifsc_code'];
				
				$wallet_amount =  get_user_allwallet($u_id,'amount')/$currency_exch_rate['USD'];
				$roi_amount =  get_user_allwallet($u_id,'amount');
				$level_bonus =  get_user_allwallet($u_id,'companyw');
				
				$pay_mode = $row['paid_mode'];
				
				$payment_mode = $pm_name[$wallet_type-1];
				
				$wal_type = "Working Wallet";
				
				$request_amount_inr = round($request_amount*$currency_exch_rate['USD'],3);
				
				$net_val = $request_amount-($request_amount*$ac_tax/100);
				$net_val_inr = $request_amount_inr-($request_amount_inr*$ac_tax/100);
				?>
							
				<form name="inact" action="" method="post">
				<input type="hidden" name="id" value="<?=$id?>" />
				<input type="hidden" name="u_id" value="<?=$u_id?>" />
				<input type="hidden" name="req_amount" value="<?=$request_amount?>" />
				<input type="hidden" name="req_amount_usd" value="<?=$request_amount_inr?>" />
				<input type="hidden" name="wall_type" value="<?=$wallet_type?>" />
				<tr align="center">
					<td class="input-small"><?=$username?></td>
					<td class="input-small"><small><?=$wallet_amount?> &#36; </small></td>
					<!--<td class="input-small"><small><?=$roi_amount?> &#36; </small></td>
					<td class="input-small"><small><?=$level_bonus?> &#36; </small></td>-->
					<td class="input-small"><small><?=$request_amount?> &#36; </small></td>
					<td class="input-small"><small><?=$ac_tax?> % </small></td>
					<td class="input-small"><small><?=$net_val_inr?> &#36; </small></td>
					<td class="input-small"><small><?=$net_val?> &#36;</small></td>
					<td class="input-small"><small><?=$wal_type?></small></td>
					<td class="input-small"><small><?=$request_date?></small></td>
					<td  class="input-small"><?=$user_comment;?></td>
					<td  class="input-small" align="left"><?=$bank_info;?></td>
					<td  class="input-small">
						<textarea name="information" class="input-small" style="height:30px; width:100px;" > </textarea>
					</td>
					<td class="input-small" style="width:100px;">
						<?php
						if($wallet_type == 1){?>
						<input type="submit" name="submit" class="btn btn-info" value="Accept" />
						<?php
						}?>
						<input type="submit" name="submit" class="btn btn-info" value="Paid Manual" />
						<input type="submit" name="submit" class="btn btn-info" value="Cancel" />	
					</td>
				</tr>
				</form> <?PHP
			}
			pagging_admin_panel($newp,$pnums,13,$val); ?>
		</table> <?php
	}
	else{ print "There is no request !"; }
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