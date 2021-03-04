<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/e_pin.php");
include("../function/functions.php");
include("../function/send_mail.php");

if(isset($_POST['submit']))
{
	if($_SESSION['generate_pin_for_user'] == 1)
		{
		
		$new_user = $_REQUEST['username'];
		$epin_type = $_POST['epin_type'];
		$epin_number = $_POST['epin_number'];
		
		if($epin_type == 0)
			$amount = $setting_registration_fees;
		else
		{
			$qu = query_execute_sqli("select * from plan_setting where id = '$epin_type' ");
			while($rrr = mysqli_fetch_array($qu))
			{ 
				$amount = $rrr['amount'];
			}
		}	
		
		$q = query_execute_sqli("select * from users where username = '$new_user' ");
		$num = mysqli_num_rows($q);
		if($num != 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$new_user_id = $row['id_user'];
			}
			$epin = "$epin_number E-pin ";
			for($ii = 0; $ii < $epin_number; $ii++)
			{
				do
				{
					$unique_epin = mt_rand(1000000000, 9999999999);
					$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin' ");
					$num = mysqli_num_rows($query);
				}while($num > 0);
				
				$mode = 1;
				$date = date('Y-m-d');
				$t = date('h:i:s');
				query_execute_sqli("insert into e_pin (epin, epin_type , user_id , amount , mode , time , date) values ('$unique_epin' , '$epin_type' , '$new_user_id' ,'$amount' , '$mode' , '$t' , '$date')");
				
				$epin .= $unique_epin."<br>";
			}
			$epin_generate_username = "canindia";
			$epin_amount = $fees;
			$payee_epin_username = $mew_user;
			$title = "E-pin mail";
			$to = get_user_email($new_user_id);
			$from = 0;
			
			$db_msg = $epin_generate_message;
			include("../function/full_message.php");
				
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			$SMTPChat = $SMTPMail->SendMail();
			print "E pin generated Successfully !";	
			$_SESSION['generate_pin_for_user'] = 0;	
		}
		else { print "Enter Correct Username !"; }	
	}
}
else
{ 
	$_SESSION['generate_pin_for_user'] = 1;
?>
	<table width="500" border="0">
	<form method="post" action="">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
    <td>User Id</td>
    <td><input style="width:200px;" type="text" name="username"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>No of E-pin</td>
    <td><input style="width:200px;" type="text" name="epin_number"  /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td>E-pin Type </td>
    <td><select name="epin_type" style="width:200px;">
		<option value="0">Registration Epin</option>
<?php
	$qu = query_execute_sqli("select * from plan_setting ");
	while($rrr = mysqli_fetch_array($qu))
	{ 
		$plan_name = $rrr['plan_name'];
		$plan_id = $rrr['id'];
		?>
		<option value="<?php print $plan_id; ?>"><?php print $plan_name; ?></option>
<?php	}	
?>		</select>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="submit" value="Generate" class="btn btn-info" /></td>
  </tr>
  </form>
</table>

<?php } ?>
