<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");

$newp = $_GET['p'];
$plimit = "15";

if(isset($_POST['submit']))
{
	$table_id = $_POST['table_id'];
	$query = query_execute_sqli("select * from epin_request where id = '$table_id' ");			
	while($row = mysqli_fetch_array($query))
	{
		$new_user_id = $row['user_id'];
		$amount = $row['epin_amount']; 	
		$epin_type = $row['epin_type']; 
			
	}
		
	do
	{
		$unique_epin = mt_rand(1000000000, 9999999999);
		$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin' ");
		$num = mysqli_num_rows($query);
	}while($num > 0);
	
	$mode = 1;
	$date = date('Y-m-d');
	$t = date('h:i:s');
	query_execute_sqli("insert into e_pin (epin , epin_type , user_id , amount , mode , time , date) values ('$unique_epin' , '$epin_type' , '$new_user_id' ,'$amount' , '$mode' , '$t' , '$date')");
	
	query_execute_sqli("update epin_request set mode = 0 , app_date = '$date' where id = '$table_id' ");			
	
	$epin_generate_username = "rapidforx2";
	$epin_amount = $fees;
	$payee_epin_username = $mew_user;
	$epin .= $unique_epin."<br>";
	$title = "E-pin mail";
	$to = get_user_email($new_user_id);
	$from = 0;
	
	$db_msg = $epin_generate_message;
	include("../function/full_message.php");
		
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
	$SMTPChat = $SMTPMail->SendMail();
	print "E pin generated Successfully !";	
}


$query = query_execute_sqli("select * from epin_request ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=800>
			<tr>
				<th  height=30px width=200 class=\"message tip\"><strong>S.No</strong></th>
				<th  height=30px width=200 class=\"message tip\"><strong>User Id</strong></th>
				<th width=200 class=\"message tip\"><strong>Amount</strong></th>
				<th  height=30px width=150 class=\"message tip\"><strong>Date</strong></th>
				<th  height=30px width=150 class=\"message tip\"><strong>E-pin Type</strong></th>
				<th  height=30px width=150 class=\"message tip\"><strong>Action</strong></th>
			</tr>";
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
				
	$query = query_execute_sqli("select * from epin_request LIMIT $start,$plimit ");			
	while($row = mysqli_fetch_array($query))
	{
		$tbl_id = $row['id'];
		$user_id = get_user_name($row['user_id']);
		$amount = $row['epin_amount']; 	
		$date = $row['date'];
		$epin_type = $row['epin_type'];
		$n = 1;
		if($epin_type == 0){	$epin_types = "Register E-Pin"; }
		else{ $epin_types = "Topup E-Pin";}
		print  "<tr>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>$n</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>$user_id</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>$amount</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>$date</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>$epin_types</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:20px\"><small>"; ?>
					<form action="index.php?page=request" method="post">
					<input type="hidden" name="table_id" value="<?php print $tbl_id; ?>" />
					<input type="submit" name="submit" value="Accept" class="btn btn-info" />
					</form>
					<form action="index.php?page=request" method="post">
					<input type="hidden" name="table_id" value="<?php print $tbl_id; ?>" />
					<input type="submit" name="delete" value="Reject" class="btn btn-info" />
					</form>
				<?php	print  "</small></td></tr>";
				$n++;
	}
	print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=6 height=30px width=400 class=\"message tip\">&nbsp;&nbsp;<strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=request&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=request&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=request&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";

}
else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no Unused E-pin to show !</td></tr></table>"; }


?>
