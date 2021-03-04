<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/setting.php");

?>
<h1 align="left">Add Amount Panel</h1>
<?php
require_once("function/country_list.php");

$user_id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit')
	{
		$pay_mode = $_REQUEST['pay_mode'];
		$user_name = $_REQUEST['name'];
		$address = $_REQUEST['address'];
		$country = $_REQUEST['country'];
		$account_id = $_REQUEST['account_id'];
		$plan_setting_id = $_REQUEST['package_id'];
		$amount = $_REQUEST['amount'];
		$invest_type = $_REQUEST['invest_type'];
		if($account_id != '' and $pay_mode != '' and $user_name != '' and $address !='')
		{
			$inc_chk = validate_request_amount($amount); 
			if($inc_chk == 1)
			{
				$date = $systems_date; //date('Y-m-d');	
				query_execute_sqli("insert into investment_request (user_id , amount , date , mode , payment_mode , name , address , country , account_id , plan_setting_id , plan_type) values ('$user_id' , '$amount' , '$date' , 0 , '$pay_mode' , '$user_name' , '$address' , '$country' , '$account_id' , '$plan_setting_id' , '$invest_type') ");
				
				$quq = query_execute_sqli("select * from investment_request group by id desc limit 1 ");
				$nummm = mysqli_num_rows($quq);
				if($nummm == 1)
				{
					while($qrow = mysqli_fetch_array($quq))
					{
						$invst_tbl_id = $qrow['id'];
					}
				}	
									
				if($pay_mode == 'perfect_money')
				{ 
					$amount = $_REQUEST['amount'];
					
				?>
					<div class="content" style="padding-top:20px; font-size:16px; text-align:left; padding-left:30px;"><center>
					<table width="550">
					<form action="https://perfectmoney.is/api/step1.asp" method="POST">
					<input type="hidden" name="package_id" value="<?php print $package_id; ?>"  />
					<input type="hidden" name="pay_mode" value="ge_currency"  />
					<input type="hidden" name="invest_type" value="<?php print $plan_select_type; ?>"  />
					
					<input type="hidden" name="PAYEE_ACCOUNT" value="<?php print $perfect_money_payee_user_account; ?>">
					<input type="hidden" name="PAYEE_NAME" value="<?php print $perfect_money_payee_user_name; ?>">
					<input type="hidden" name="PAYMENT_UNITS" value="USD">
					<input type="hidden" name="STATUS_URL" value="<?php print $perfect_money_status_url; ?>">
					<input type="hidden" name="PAYMENT_URL" value="<?php print $perfect_money_payment_url; ?>">
					<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="NOPAYMENT_URL" value="<?php print $perfect_money_cancel_url; ?>">
					<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="SUGGESTED_MEMO" value="">
					<input type="hidden" name="Success" value="Success">
					<input type="hidden" name="Failed" value="Failed">
					<input type="hidden" name="BAGGAGE_FIELDS" value="Success Failed">
					<input type="hidden" name="PAYMENT_AMOUNT" value="<?php print $amount; ?>">

					<input type="hidden" name="customer_id" value="<?php print $user_id; ?>" />
					<input type="hidden" name="cust_investment_type" value="<?php print $invest_type; ?>" />
					<input type="hidden" name="plan_setting_id" value="<?php print $plan_setting_id; ?>" />
					<input type="hidden" name="invst_tbl_id" value="<?php print $invst_tbl_id; ?>" />
					<input type="hidden" name="BAGGAGE_FIELDS" value="customer_id cust_investment_type plan_setting_id invst_tbl_id">
				
					
					<tr>
						<td><strong>Your Name :</strong></td>
						<td><strong><?php print $user_name; ?></strong></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td><strong>Perfect Money Id :</strong></td>
						<td><strong><?php print $account_id; ?></strong></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td><strong>Amount :</strong></td>
						<td><strong>$ <?php print $amount; ?> USD</strong></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center; padding-right:150px;">
							<input type="submit" name="submit" value="Submit" class="normal-button" />
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					</form>
					</table></center>
					</div> 
<?php
				}
				elseif($pay_mode == 'liberty')
				{
					$amount = $_REQUEST['amount'];
					
				?>
					<table width="400" border="0">
					<form name="liberty_pay" action="https://sci.libertyreserve.com/en" method="POST">
					<input type="hidden" name="customer_id" value="<?php print $user_id; ?>"  />
					<input type="hidden" name="req_amount" value="<?php print $amount; ?>"  />
					
					<input type="hidden" name="lr_acc" value="<?php print $liberty_account; ?>">
					<input type="hidden" name="lr_store" value="Royal Trader Group">
					<input type="hidden" name="lr_currency" value="<?php print $lr_currency; ?>">
					<input type="hidden" name="lr_comments" value="Welcome to Our Company">
					<input type="hidden" name="lr_success_url" value="<?php print $liberty_success_url; ?>">
					<input type="hidden" name="lr_success_url_method" value="POST">
					<input type="hidden" name="lr_fail_url" value="<?php print $liberty_failed_url; ?>">
					<input type="hidden" name="lr_fail_url_method" value="POST">
	
					
					  <tr>
						<td colspan="2"><font color="#2B2B57" size="+2">Liberty Payment Information</font></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td width="230"><h1>Requested Amount</h1> </td>
						<td><strong><?php print $amount; ?></strong></td>
						</tr>
						<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Account Id</h1> </td>
						<td><?php print $account_id; ?></td>
						</tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Name</h1></td>
						<td><?php print $user_name; ?></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Address</h1></td>
						<td><?php print $address; ?></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Country</h1></td>
						<td><?php print $country; ?></td>
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
						<td colspan="2" align="center">
						<!--<input type="submit" name="submit" value="Liberty"  />-->
						<a href=https://sci.libertyreserve.com/en?lr_acc=<?php print $liberty_account; ?>&lr_currency=LRUSD><img src="http://images04.olx.in/ui/5/59/39/1272714090_91185039_1-Pictures-of--INDIANS-CAN-BUY-LIBERTY-RESERVE-DOLLARS-1272714090.jpg" height=50/></a></td>
					  </tr>
					</table>
		
			<?php  
				}
			}
			else
			{
				print "<font color=\"#FF0000\" size=\"+2\">Request of Add Funds can not be completed.<br> Please Enter An Integer Amount !!</font>";	
			}	
		}
		else { print "Please Enter All Information"; }	
		
	}
	
			
}

					
