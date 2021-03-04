<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/setting.php");

include("function/direct_income.php");
include("function/check_income_condition.php");
include("function/pair_point_calculation.php");
require_once("function/send_mail.php");

?>
<h1 align="left">Make Order Panel</h1>
<?php
$id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['Submit']))
{
	if($_POST['Submit'] == 'Book')
	{
		$product_id = $_REQUEST['product_id'];
		$p_costs = $_REQUEST['p_cost'];
		$q = query_execute_sqli("select * from wallet where id = '$id' ");
		while($r = mysqli_fetch_array($q))
		{
			$wallet_amount = $r['amount'];
		}	
		 ?>
			<table width="400" border="0">
			<?php
			if($wallet_amount > $p_costs)
			{ ?> 
				<form name="order_form" action="index.php?page=make_shopping" method="post"> 
				<input type="hidden" name="prd_id" value="<?php print $product_id; ?>"  />
				
		<?php  } ?>
			<tr>
			<td colspan="2">&nbsp;</td>
		 	</tr>
			<tr>
			<tr>
					<td><strong style="color:#FF0000;">Your Wallet Amount </strong></td>
					<td><strong style="color:#FF0000;">$ <?php print $wallet_amount; ?></strong></td>
			</tr>
			<tr>
			<tr>
					<td colspan="2">&nbsp;</td>
				  </tr>
			<tr>
				<td><strong>Product Coast</strong></td>
				<td>$ <?php print $p_costs; ?></td>
				 </tr>
				<?php
				if($wallet_amount > $p_costs)
				{ ?> 
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  
					  <tr>
						<td>Transaction Password</td>
						<td><input type="text" name="user_pin"  /></td>
					  </tr>
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="Submit" value="Order"  class="normal-button" /></td>
					  </tr>
					  </form>
				  <?php }
				  	else 
					{ ?>
						<tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td colspan="2"> <font color="#FF0000" size="+2">Sorry You have no Sufficient <br />Balance in Your Account !!</font></td>
					  </tr>
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
				  <?php } ?>
				  </table>
	<?php
	}
	elseif($_POST['Submit'] == 'Order')
	{
		$user_pin = $_REQUEST['user_pin'];
		$product_id = $_REQUEST['prd_id'];
		
		$q = query_execute_sqli("select * from users where id_user = '$id' and password = '$user_pin' ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{
			$to = get_user_email($id);
			$title = "Security Pin For Make Shopping";
			$unique_epin = mt_rand(1000000000, 9999999999);
			$date = date('Y-m-d');
			query_execute_sqli("insert into security_password (user_id , security_password , date , mode) values ('$id' , '$unique_epin' , '$date' , 1) ");
				
			$full_message = "Hello user ".$_SESSION['ednet_user_name']." , Your Change Password SECURITY PIN is : ".$unique_epin;
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			$SMTPChat = $SMTPMail->SendMail();
			
			 ?>
			<table width="400" border="0">
				<form name="change_pass" action="index.php?page=make_shopping" method="post" id="commentform">
				<input type="hidden" name="prd_id" value="<?php print $product_id; ?>"  />
				<tr>
				<td colspan="2">Please check security pin to your email address.</td>   
			  </tr>
			   <tr>
				<td colspan="2">&nbsp;</td>   
			  </tr>
				<tr>
				<td>Enter Security Password </td><td><input type=text size=25 name=security_pass /></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td colspan="2"><input type="submit" name="Submit" value="Check"  class="normal-button"/>
				</td>
				
				</form>
				
				</tr>
			</table>
		<?php
		}
		else { print "Please Enter Correct Transaction Pin !"; }
	}
	elseif($_POST['Submit'] == 'Check')	
	{
		$product_id = $_REQUEST['prd_id'];
		$security_pass = $_REQUEST['security_pass'];
		$q = query_execute_sqli("select * from security_password where user_id = '$id' and security_password = '$security_pass' and mode = 1 ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{
			$q = query_execute_sqli("select * from shopping where product_id = '$product_id' ");
			$num = mysqli_num_rows($q);
			if($num > 0)
			{
				while($r = mysqli_fetch_array($q))
				{
					$product_name = $r['product_name'];
					$product_cost = $r['product_cost'];
					$title = $r['title'];
					$discription = $r['discription'];
					$product_id = $r['product_id'];
				}	
			?>
				<table width="400" border="0">
				<form name="change_pass" action="index.php?page=make_shopping" method="post" id="commentform">
				<input type="hidden" name="prd_id" value="<?php print $product_id; ?>"  />
				<input type="hidden" name="security_pass" value="<?php print $security_pass; ?>"  />
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td colspan="2"  style="font-size:20px; color:#323265;"><strong>Product Information</strong> </td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td><h2 style="border-bottom:none">Product Name</h2> </td><td><h2 style="border-bottom:none"><?php print $product_name; ?></h2></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td><h2 style="border-bottom:none">Product Cost </h2></td><td><h2 style="border-bottom:none"><?php print $product_cost; ?></h2></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td><h2 style="border-bottom:none">Product Title</h2> </td><td><h2 style="border-bottom:none"><?php print $title; ?></h2></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td><h2 style="border-bottom:none">Product Discription</h2></td><td><h2 style="border-bottom:none"><?php print $discription; ?></h2></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td><h2 style="border-bottom:none">Shopping Adderss</h2> </td>
				<td><textarea name="shopping_add" style="height:50px;" > </textarea></td>
				</tr>
				<tr>
				<td colspan="2">&nbsp;
				</td>
				<tr>
				<td colspan="2"><input type="Submit" name="Submit" value="Process" class="normal-button" />
				</td>
				</form>
				</tr>
				</table>
		<?php	}
			else { print "Please Select Package !"; }
		}else { print "Please Enter Correct Transaction Pin !"; }
	}
	elseif($_POST['Submit'] == 'Process')	
	{
		$security_pass = $_REQUEST['security_pass'];
		$product_id = $_REQUEST['prd_id'];
		$shopping_add = $_REQUEST['shopping_add'];
		$q = query_execute_sqli("select * from shopping where product_id = '$product_id' ");
		while($r = mysqli_fetch_array($q))
		{
			$product_name = $r['product_name'];
			$product_cost = $r['product_cost'];
			$title = $r['title'];
			$discription = $r['discription'];
			$product_id = $r['product_id'];
		}
		$date = date('Y-m-d');	
		query_execute_sqli("insert into shopping_order (user_id , product_id , product_cost , date , shopping_address , order_confirm) values ('$id' , '$product_id' , '$product_cost' , '$date' , '$shopping_add' , 0) ");
		
		$q = query_execute_sqli("select * from wallet where id = '$id' ");
		while($r = mysqli_fetch_array($q))
		{
			$wallet_amount = $r['amount'];
		}	
		$left_amount = $wallet_amount-$product_cost;
		query_execute_sqli("UPDATE wallet set amount = '$left_amount' where id = '$id' "); 
		query_execute_sqli("UPDATE security_password SET mode = 0 WHERE security_password = '$security_pass' and user_id = '$id' ");
		
		$username_log = get_user_name($id);
		$income_log = $product_cost;
		$for = "Making Shopping";
		include("function/logs_messages.php");
		data_logs($id,$data_log[8][0],$data_log[8][1],$log_type[4]);

		$pay_request_username = get_user_name($id);
		include("function/logs_messages.php");
		data_logs($id,$data_log[18][0],$data_log[18][1],$log_type[8]);
		
		$pay_request_username = get_user_name($id);
		$to = get_user_email($id);  //message foe mail
		$title = "Make Shopping Email";
		$db_msg = $make_shopping_email;
		include("function/full_message.php");
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
		$SMTPChat = $SMTPMail->SendMail();
		
		print "Youe Order Accepted Successfully !";
	}
	else { print "Please Select Correct Package For Investment !"; }
}
else
{
	
	
?>
	<table width="700" border="0">
	 <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><img src="product_images/shop-ad.jpg" width="700" /></td>
  </tr>
	
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td width="650px" height=30px class="message tip"><strong>Product Name</strong></td>
	<td height=30px width="650px" class="message tip" align="center"><strong>Product Cost</strong></td>
	<td height=30px width="650px" class="message tip" align="center"><strong>Product Title</strong></td>
	<td height=30px width="650px" class="message tip" align="center"><strong>Product Discription</strong></td>
	<td height=30px width="650px" class="message tip" align="center"><strong>Order Product</strong></td>
  </tr>
 
  <?php 
  	$q = query_execute_sqli("select * from shopping ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		while($r = mysqli_fetch_array($q))
		{
			$product_name = $r['product_name'];
			$product_cost = $r['product_cost'];
			$title = $r['title'];
			$discription = $r['discription'];
			$product_id = $r['product_id'];
			
			
		  ?>
		  
		   <tr>
		   
			<form name="invest" method="post" action="index.php?page=make_shopping">
			<td height="20px" class="input-small" align="center"><img src="product_images/<?php print $product_id; ?>.jpg" width="100px" /><br /><?php print $product_name; ?></td>
			<td height="20px" class="input-small" align="center">$<?php print $product_cost; ?></td>
			<td height="20px" class="input-small" align="center"><?php print $title; ?></td>
			<td height="20px" class="input-small" align="center"><?php print $discription; ?></td>
			<td height="20px" class="input-small" align="center">
			<input type="hidden" name="p_cost" value="<?php print $product_cost; ?>"  />
			<input type="hidden" name="product_id" value="<?php print $product_id; ?>"  />
			<input type="submit" name="Submit" value="Book" class="normal-button"  /></form></td>
			</tr>
	<?php }
	}?>
	
  </table>
	
	
	
	
<?php } ?>

