<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/setting.php");
//include("function/database.php");
//include("function/send_mail.php");

$id = $_SESSION['mlmproject_user_id'];
include("function/wallet_message.php");
?>
<h1 align="left">Request For Funds</h1>
<?php
if(count($_POST) == 0)unset($_SESSION['session_user_req_add_fund']);
if(isset($_POST['submit']))
{
	$user_pin = $_REQUEST['user_pin'];
	$current_amount = $_REQUEST['curr_amnt'];
	$request_amount = $_REQUEST['request'];
	$pay_mode = $_REQUEST['pay_mode'];
	$query = query_execute_sqli("select * from users where id_user = '$id' and password = '$user_pin' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		if(/*$request_amount <= $current_amount*/1)
		{ 
			
			
				if(!isset($_SESSION['session_user_req_add_fund'])){
				$_SESSION['session_user_req_add_fund'] = 0;
				
				$request_date= date('Y-m-d');
				$sql = "insert into paid_unpaid (user_id , amount , request_date , paid , paid_mode) values ('$id' , '$request_amount' , '$request_date' , 0 , '$pay_mode') ";
				query_execute_sqli($sql);
				
				/*$position = get_user_position($id);
				$wallet_message = wallet_transfer_message(2,$_SESSION['mlmproject_user_name'],$request_amount,$pay_mode);
				data_logs($id,$data_log[6][0],$wallet_message,$log_type[5]);
								
				$pay_request_username = get_user_name($id);
				$to = get_user_email($id);  //message for mail
				$title = "Payment Request Message";
				$db_msg = $payment_request_message;
				include("function/full_message.php");
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
				$SMTPChat = $SMTPMail->SendMail();
				
				$req_amount = request_amount;
				$from_user = $_SESSION['mlmproject_user_name'];
				$parent_phone = $_SESSION['mlmproject_user_phone'];
				include("function/sms_message.php");
				send_sms($url_sms,$req_fund_transfer,$parent_phone);  //send sms
				if($pay_mode == 'mode_2')
				{
					include("mode_2.php");
				}
				elseif($pay_mode == 'mode_3')
				{
					include("mode_3.php");
				}*/
				echo "<script type=\"text/javascript\">";
				echo "alert('Request Send Successfully!!');window.location = \"index.php?page=requested_add_funds\"";
				echo "</script>";
				}
				else{
					echo "<script type=\"text/javascript\">";
					echo "window.location = \"index.php?page=requested_add_funds\"";
					echo "</script>";
				}
			
		}	
		else { print "You request balance is not available in your wallet"; }	
	}
	else { print "Please enter correct user pin !"; }		
}
else
{
$query = query_execute_sqli("select amount from wallet where id = '$id' ");
while($row = mysqli_fetch_array($query))
{
	$curr_amnt = $row[0];
}	

?>


<?php $msg = $_REQUEST[mg]; echo $msg; ?> 
<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
<form name="money" action="index.php?page=requested_add_funds" method="post">
<input type="hidden" name="curr_amnt" value="<?php echo $curr_amnt; ?>"  />
  <tr>
    <td colspan="2" class="td_title"><strong>Your Wallet Information</strong></td>   
  </tr>
  <tr>
    <td colspan="2" class="td_title">Your Current Wallet is (USD) <?php echo $curr_amnt; ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>   
  </tr>
  <tr>
    <td class="td_title">Your Request Amount :</td>
    <td > <input type="text" name="request" size="8" class="form-control" /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>   
  </tr>
  <tr>
    <td class="td_title">Transaction Password :</td>
    <td ><input type="text" name="user_pin" size="15" class="form-control" /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>   
  </tr>
  <!--<tr>
  	<td><p>Payment Mode</p></td>
    <td><p>
		<select name="pay_mode" class=form-control>
			<option value="" >Select Mode</option>
			<?php $count = 3;//count($registration_mode);
			for($i = 1; $i < $count; $i++)
			{ ?>
				<option value="<?php echo $registration_mode[$i]; ?>" ><?php echo $registration_mode_value[$i]; ?></option>
			<?php  }  ?>
		</select></p>	
	</td>   
  </tr>-->
  <tr>
    <td colspan="2"><p align="right"><input type="submit" name="submit" value="Request" class="btn btn-info"  /></p></td>   
  </tr>

  </form>
</table>

<?php }  ?>
