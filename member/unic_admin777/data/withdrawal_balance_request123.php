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
$plimit = "5";
	
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
		query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 1 , 
		paid_inform = '$information' where id = '$req_id' ");
		
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
		$phone_to = get_user_phone($u_id);
		include("../function/sms_message.php");
		send_sms($url_sms,$request_yourself,$phone_to);  //send sms

		/*$bal_amount = $total_amount+$req_amount;		
		query_execute_sqli("update wallet set amount = '$bal_amount' , date = '$accept_date' where id = '$u_id' ");
		
		$log_username = get_user_name($u_id);
		$income_log = $req_amount;
		$date = $accept_date;
		$income_type_log = "Requested Fund from ADMIN";
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[4][0],$data_log[4][1],$log_type[4]);*/
		
		$pay_request_username = get_user_name($u_id);
		$request_amount = $req_amount;
		$to = get_user_email($u_id);
		$title = "Payment Transfer Message";
		$db_msg = $payment_transfer_message;
		include("../function/full_message.php");
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
		$SMTPChat = $SMTPMail->SendMail();
		
		print "Request Accepted!";
	}
	elseif($_POST['submit'] == 'Cencel')
	{
		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$req_amount = $_REQUEST['req_amount'];
		$information = $_REQUEST['information'];
		$accept_date= date('Y-m-d');
		query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 2 , 
		paid_inform = '$information' where id = '$req_id' ");
		
		$username_log = get_user_name($u_id);
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[21][0],$data_log[21][1],$log_type[9]);
		print "Request of Withwrawal Amount has been Canceled Successfully .";
	}
	else { print "<B style=\"color:#ff0000;\">There Are Some Conflicts !!</B>"; }	
}
else
{
	$mg = $_REQUEST['mg']; echo $mg;
	$query = query_execute_sqli("select * from paid_unpaid where paid = 0 and amount > 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered"> 
			<thead>
			<tr>
				<th>User Name</th>		<th>Wallet Amount</th>
				<th>Request Amount</th>	<th>Payment Mode</th>
				<th>Account No</th>		<th>Date</th>
				<th>Action</th>			<th>Information</th>
			</tr>
			</thead>
		<?php
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$query = query_execute_sqli("select * from paid_unpaid where paid = 0 and amount > 0 LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$u_id = $row['user_id'];
		$username = get_user_name($u_id);
		$request_amount = $row['amount'];
		$request_date = $row['request_date'];
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
		} ?>
			<form name="inact" action="index.php?page=withdrawal_balance_request" method="post">
			<input type="hidden" name="id" value="<?=$id;?>" />
			<input type="hidden" name="u_id" value="<?=$u_id;?>" />
			<input type="hidden" name="req_amount" value="<?=$request_amount;?>" />
			<tr>
				<td class="text-center"><?=$username;?></td>
				<td class="text-center"><?=$wallet_amount;?> &nbsp; <img width="15" height="15" src="images/bbc_black.png"> &nbsp; </td>
				<td class="text-center"><?=$request_amount;?> &nbsp; <img width="15" height="15" src="images/bbc_black.png"> &nbsp; </td>
				<td class="text-center"><?=$payment_mode;?></td>
				<td class="text-center"><?=$account_number;?></td>
				<td class="text-center"><?=$request_date;?></td>
				<td class="text-center">
					<textarea name="information" style="height:30px; width:100px;" > </textarea>
				</td>
				<td>
					<input type="submit" name="submit" class="btn btn-info" value="Accept" />
					<input type="submit" name="submit" class="btn btn-info" value="Cencel" />				
				</td>
			</tr>
			</form>
	<?php
	}
	?>
	</table>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
	if ($newp>1)
	{ ?>
		<li id="DataTables_Table_0_previous" class="paginate_button previous">
			<a href="<?="index.php?page=withdrawal_balance_request&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<li class="paginate_button ">
			<a href="<?="index.php?page=withdrawal_balance_request&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
	} 
	if ($newp<$pnums) 
	{ ?>
	   <li id="DataTables_Table_0_next" class="paginate_button next">
			<a href="<?="index.php?page=withdrawal_balance_request&p=".($newp+1);?>">Next</a>
	   </li>
	<?php 
	} 
	?>
	</ul></div>
<?php
	}
	else{ echo "<B style=\"color:#ff0000;\">There is no request !!</B>"; }
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
 