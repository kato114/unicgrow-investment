<?php
include("function/setting.php");
?>
<h2 align="left">Add Amount Panel</h2>
<?php
require_once("function/country_list.php");

$user_id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit')
	{
		
		$pay_mode = $_REQUEST['pay_mode'];
		$amount = $_REQUEST['amount'];
		if($amount != 0)
		{
			if($pay_mode == 'admin')
			{
				$date = date('Y-m-d');
				$mode = "Admin";
				query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$user_id' , '$amount' , '$date' , 0 , '$pay_mode') ");
				
				$username_log = get_user_name($user_id);
				$amount = $amount;
				$add_by = "ADMIN(self)";
				include("function/logs_messages.php");
				data_logs($id,$data_log[13][0],$data_log[13][1],$log_type[6]);
			
				print "Request For Add amount $ ".$amount." to ADMIN has been Completed Successfully"; 
			}
			elseif($pay_mode == 'alert')
			{ 
				$amount = $_REQUEST['amount'];
				$_SESSION['dccan_alert_pay_customer_id'] = $user_id;

				$date = date('Y-m-d');
				$mode = "Admin";
				query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$user_id' , '$amount' , '$date' , 0 , '$pay_mode') ");
			?>
				<table width="600" border="0">
				<form method="post" name="alert_pay_payment" action="https://www.alertpay.com/PayProcess.aspx" >
				<input type="hidden" name="ap_purchasetype" value="service"/>
				<input type="hidden" name="ap_merchant" value="<?php print $recipient_acc;?>"/>
				<input type="hidden" name="ap_itemname" value="<?php print $item_name; ?>"/>
				<input type="hidden" name="ap_currency" value="<?php print $currency; ?>"/>
				<input type="hidden" name="ap_quantity" value="1"/>
				<input type="hidden" name="ap_description" value="<?php print $item; ?>"/>
				<input type="hidden" name="ap_amount" value="<?php print $amount; ?>"/>
				<input type="hidden" name="ap_returnurl" value="<?php print $return_url; ?>"/>
				<input type="hidden" name="ap_cancelurl" value="<?php print $cancel_url; ?>"/>
				<input type="hidden" name="ap_test" value="<?php print $ap_test; ?>"/>
				

				<input type="hidden" name="customer_id" value="<?php print $user_id; ?>"  />
				  <tr>
					<td colspan="2"><font color="#2B2B57" size="+2">Alert Pay Information</font></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Requested Amount</h1> </td>
					<td style="padding-left:15px; font-weight:700;"><font color="#2B2B57" size="+2"><?php print $amount; ?></font></td
				  ></tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Name</h1></td>
					<td><input type="text" name="name" size="26"  class="input-medium" /></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Address</h1></td>
					<td><textarea name="address" style="height:40px;" class="input-medium"></textarea></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Country</h1></td>
					<td>
						<select name=country id="country" class="input-medium" style="width:250px">
							<option value="India">India</option>
						<?php
							$list = count($country_list);
							for($cl = 0; $cl < $list; $cl++)
							{ ?>
								<option value="<?php print $country_list[$cl]; ?>"><?php print $country_list[$cl]; ?></option>
						<?php } ?>
						</select>
					</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Currency</h1></td>
					<td>USD</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="2" align="center"><input type="image" name="ap_image" src="https://www.alertpay.com/Images/BuyNow/big_pay_01.gif" alt="Subscribe via Alertpay" /></td>
				  </tr>
				</table>
	
		<?php	}
			elseif($pay_mode == 'liberty')
			{
				$amount = $_REQUEST['amount'];
				$date = date('Y-m-d');
				$mode = "Admin";
				query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$user_id' , '$amount' , '$date' , 0 , '$pay_mode') ");
			?>
				<table width="600" border="0">
				<form name="liberty_pay" action="https://sci.libertyreserve.com/en" method="POST">
				<input type="hidden" name="customer_id" value="<?php print $user_id; ?>"  />
				<input type="hidden" name="req_amount" value="<?php print $amount; ?>"  />
				
				<input type="hidden" name="lr_acc" value="<?php print $liberty_account; ?>">
				<input type="hidden" name="lr_store" value="Royal Trader Group">
				<input type="hidden" name="lr_currency" value="LRUSD">
				<input type="hidden" name="lr_comments" value="Welcome to Our Company">
				<input type="hidden" name="lr_success_url" value="http://royalforexgroup.biz/business/index.php?page=liberty_payment">
				<input type="hidden" name="lr_success_url_method" value="POST">
				<input type="hidden" name="lr_fail_url" value="http://royalforexgroup.biz/business/index.php?page=transfer_failed">
				<input type="hidden" name="lr_fail_url_method" value="POST">

				
				  <tr>
					<td colspan="2"><font color="#2B2B57" size="+2">Liberty Payment Information</font></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Requested Amount : </h1> </td>
					<td style="padding-left:15px; font-weight:700;"><font color="#2B2B57" size="+2"><?php print $amount; ?></font></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Name</h1></td>
					<td><input type="text" name="name" class="input-medium" /></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Address</h1></td>
					<td><textarea name="address" class="input-medium" style="height:40px;" ></textarea></td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Country</h1></td>
					<td>
						<select name=country id="country" class="input-medium" style="width:250px" />
							<option value="India">India</option>
						<?php
							$list = count($country_list);
							for($cl = 0; $cl < $list; $cl++)
							{ ?>
								<option value="<?php print $country_list[$cl]; ?>"><?php print $country_list[$cl]; ?></option>
						<?php } ?>
						</select>
					</td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td><h1>Currency</h1></td>
					<td>&#36; </td>
				  </tr>
				  <tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="2" align="center"><input type="submit" name="submit" value="Liberty"  /></td>
				  </tr>
				</table>
	
		<?php
			}
		}
		else
		{
			print "Please Enter Amount To Add !";	
		}	
	}
	
			
}
else
{

?>
	<table width="900" border="0">
	
  <tr>
    <td colspan="3">&nbsp;</td>
	</tr>
  <tr>
    <td align="center" height="30px" class="message tip"><strong>Mode</strong></td>
	<td align="center" height=30px class="message tip"><strong>Amount</strong></td>
	<td align="center" height=30px class="message tip"><strong>A/C Id</strong></td>
	<td align="center" height=30px class="message tip"><strong>&nbsp;</strong></td>
  </tr>
   <tr>
   <form name="add" method="post" action="index.php?page=add_funds">
    <td height="30px" class="input-small" style="padding-left:50px;"><strong>Liberty Reserve</strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="text" name="amount" size="15" class="input-medium" /></strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="text" name="amount" size="15" class="input-medium" /></strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="submit" name="submit" value="Submit" /></strong>
	<input type="hidden" name="pay_mode" value="liberty"  /></td>
	</form>
  </tr>
  <tr>
  <form name="add" method="post" action="index.php?page=add_funds">
    <td height="30px" class="input-small" style="padding-left:50px;"><strong>Alert Pay</strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="text" name="amount" size="15" class="input-medium" /></strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="text" name="amount" size="15" class="input-medium" /></strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="submit" name="submit" value="Submit" /></strong>
	<input type="hidden" name="pay_mode" value="alert"  /></td>
	</form>
  </tr>
 <!-- <tr>
  <form name="add" method="post" action="index.php?page=add_funds">
    <td  height="30px" class="input-small" style="padding-left:50px;"><strong>Admin Request</strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="text" name="amount" size="15" class="input-medium" /></strong></td>
	<td align="center" height=30px class="input-small"><strong><input type="submit" name="submit" value="Submit" /></strong>
	<input type="hidden" name="pay_mode" value="admin"  /></td>-->
	</form>
  </tr>
  </table>

<?php } ?>

