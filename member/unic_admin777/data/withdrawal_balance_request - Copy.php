<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");
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
		$information = $_REQUEST['information'];
		$query = query_execute_sqli("select * from wallet where id = '$u_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$total_amount = $row['amount'];
		}
			$accept_date= date('Y-m-d');
			query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 1 , paid_inform = '$information' where id = '$req_id' ");
			
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
	
			//$bal_amount = $total_amount+$req_amount;		
			query_execute_sqli("update wallet set amount = amount+'$bal_amount' , date = '$accept_date' where id = '$u_id' ");
			
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
			
			$phone = get_user_phone($id);
					
			$message = "Dear Member, Working Wallet withdrawal for username  $pay_request_username is accepted. Bankwire will be done in 3 working days. www.canindia.co.in.";
			send_sms($phone,$message);
			
			print "Request Accepted!";
	}
	elseif($_POST['submit'] == 'Cencel')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$information = $_REQUEST['information'];
		$accept_date= date('Y-m-d');
		query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 2 , paid_inform = '$information' where id = '$req_id' ");
		
		$username_log = get_user_name($u_id);
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);
		print "Request of Withwrawal Amount has been Canceled Successfully .";
	}
	else { print "There Are Some Conflicts."; }	
}

elseif(isset($_POST['create_file']))
{
	$sql = "select t2.f_name as First_Name, t2.l_name as Last_name, t2.phone_no as Phone, t2.username as Username , t3.amount as Wallet , t1.amount as RequestAmount,t2.ac_no as AcountNo , t2.branch as Branch , t2.bank as Bank, t2.bank_code as BankCode  , t2.beneficiery_name as BeneficieryName , t1.request_date as Date from paid_unpaid as t1 inner join users as t2 on t1.user_id = t2.id_user inner join wallet as t3 on t2.id_user = t3.id and t1.paid = 0 and t1.amount > 0";
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
		<p><a style="color:#333368; font-weight:600;" href="index.php?page=show_daily_income">Back</a></p>
	click here for download file = <a href="mlm_user excel files/<?php print $file_name;?>.xls"><?php print $file_name; ?></a>	
<?php 

}
else
{
	
	$newp = $_GET['p'];
	$plimit = "5";
	
	$mg = $_REQUEST[mg]; echo $mg;
	$query = query_execute_sqli("select * from paid_unpaid where paid = 0 and amount > 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{
		print " 
					
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=950>
			
			<tr>
				<th colspan=8 height=40><strong style=\"font-size:20px\">
				<form method=post>
					To Create Excel File <input type=submit name=create_file value=Create Excel File class=\"button1\" style=\"vertical-align: text-bottom\"/></strong></th>
				</form>
			</tr>
			
			<tr>
			<td class=\"message tip\" align=\"center\"><INPUT type=\"checkbox\" onchange=\"checkAll(this)\" name=\"content[]\" /> Check All</td>
			<td class=\"message tip\" align=\"center\"><strong>User Name</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Wallet Amount</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Request Amount</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Payment Mode</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Account</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Date</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Action</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Information</strong></td></tr>";
		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	
		$query = query_execute_sqli("select * from paid_unpaid as pu inner join users as us on pu.user_id = us.id_user and pu.paid = 0 and pu.amount > 0 LIMIT $start,$plimit ");

		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = $row['username'];
			$request_amount = $row['amount'];
			$request_date = $row['request_date'];
			
			$bank_info = "RegName = ".$row['f_name']." ".$row['l_name']."<br>Beneficiary = ".$row['beneficiery_name']."<br>A/c Number = ".$row['ac_no']." <br>Bank Name = ".$row['bank'].' <br /> Branch = '.$row['branch'].'<br />Bank Code = '.$row['bank_code'];
			$ac_no = $row['ac_no'];
			
			$walletquery = query_execute_sqli("select amount from wallet where id = '$u_id' ");
			while($walletrow = mysqli_fetch_array($walletquery))
			{
				$wallet_amount = $walletrow[0];
			}	
			$pay_mode = $row['paid_mode'];
			if($pay_mode == 'liberty')
			{
				$payment_mode = 'Liberty Reserve';
				$accinfo = get_account_information($u_id);
				$account_number = $accinfo[0];
			}	
			if($pay_mode == 'ge_currency')
			{
				$payment_mode = 'Ge Currency';
				$accinfo = get_account_information($u_id);
				$account_number = $accinfo[1];
			}	
			
						
			print "<tr>
			<td class=\"input-medium\" align=\"center\"><input type=\"checkbox\" name=\"content[]\" value=\"$id\" /></td>
			<td class=\"input-medium\" align=\"center\">$username</td>
			<td class=\"input-medium\" align=\"center\"><small>$wallet_amount RC</small></td>
			<td class=\"input-medium\" align=\"center\"><small> $request_amount RC</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$payment_mode</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$bank_info</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$request_date</small></td>
				
				<td  class=\"input-medium\"><center><form name=\"inact\" action=\"index.php?page=withdrawal_balance_request\" method=\"post\">
					<textarea name=\"information\" class=\"input-medium\" style=\"height:30px; width:100px;\" > </textarea></td>
					<td class=\"input-medium\" style=\"width:100px;\">
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"u_id\" value=\"$u_id\" />
					<input type=\"hidden\" name=\"req_amount\" value=\"$request_amount\" />
					<input type=\"submit\" name=\"submit\" class=\"btn btn-info\" style=\"width:80px;\" value=\"Accept\" />
					<input type=\"submit\" name=\"submit\" class=\"btn btn-info\" style=\"width:80px;\" value=\"Cencel\" />	</center>				
				</td></form></tr>";
		}
		print "<tr><td colspan=9 >&nbsp;</td></tr><td colspan=98 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=withdrawal_balance_request&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=withdrawal_balance_request&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=withdrawal_balance_request&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print "</table>";	
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