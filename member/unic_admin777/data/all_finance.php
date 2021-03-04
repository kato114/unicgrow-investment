<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
require_once("../config.php");
include("../function/child_info.php");
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	if($_POST['Submit'] == 'Submit')
	{
		$mode = $_REQUEST['mode'];
		?>
			<table align="center" border="0" width=450>
				<form name="my_form" action="index.php?page=all_finance" method="post">
				<tr>
					<td colspan="2">&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="2">&nbsp;</td>
				  </tr>
				  <tr>
					<td colspan="2" class="td_title"><strong> Daily Binary List </strong></td>
				  </tr>
				  <tr>
					<td colspan="2">&nbsp;</td>
				  </tr> <?php
				  if($mode == 'date') 
				  {
				  ?>
				 <tr>
				 	<input type="hidden" name="search_mode" value="date_wise"  />
					<td class="td_title"><p>Enter Start Date</p></td>
					<td><p><input type="text" name="s_date" size=3 class="input-medium flexy_datepicker_input"/></p></td>
				  </tr>
				  <tr>
					<td class="td_title"><p>Enter End Date</p></td>
					<td><p><input type="text" name="end_d" size=3 class="input-medium flexy_datepicker_input" /></p></td>
				  </tr>
				  <?php } 
				  else
				  { ?>
				  
				  <tr>
				  <input type="hidden" name="search_mode" value="user_wise"  />
					<td class="td_title"><p>Enter User Id</p></td>
					<td><p><input type="text" name="user_info" size=3 class="form-control" /></p></td>
				  </tr>
				  
				<?php }?>
				  <tr>
					<td align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" value="Enter" class="btn btn-info" /></td>
					
				  </tr>
				  </form>
			</table>
			<?php
		
	}
	elseif($_POST['Submit'] == 'Enter')
	{
		$search_mode = $_REQUEST['search_mode'];
		if($search_mode == 'date_wise')
		{	
		
			$s_date = $_REQUEST['s_date'];
			$e_date = $_REQUEST['end_d'];
			$q_all = query_execute_sqli("select * from add_funds where date >= '$s_date' and date <= '$e_date' and mode = 1");
			$num_all = mysqli_num_rows($q_all);
			if($num_all != 0)
			{
				print "
							<table hspace = 0 cellspacing=0 cellpadding=0 border=0 width=700>
							
							<tr>
							<th width=200 class=\"message tip\"><strong>Date</strong></th>
							<th width=200 class=\"message tip\"><strong>User Id</strong></th>
							<th width=200 class=\"message tip\"><strong>Amount</strong></th>
							<th width=200 class=\"message tip\"><strong>Payment Mode</strong></th>
							<th width=200 class=\"message tip\"><strong>Received Date</strong></th>
							
							</tr>
							<tr>
							<td align=center colspan=4 width=400 class=\"td_title\"><strong>&nbsp;</strong></th>
							</tr>"; 
			
				while($row = mysqli_fetch_array($q_all))
				{
						
							$amount = $row['amount'];
							$date = $row['date'];
							$user_id = $row['user_id'];
							$username = get_user_name($user_id);
							$payment_mode = $row['payment_mode'];
							$request_amount = $row['amount'];
							$app_date = $row['app_date'];
							
							
								print "<tr>
									<td width=200 align=center class=input-small ><small>";echo $date; print"</small></th>
									<td width=200 align=center class=input-small ><small>";echo $username; print"</small></th>
									<td width=200 align=center class=input-small ><small>"; echo $amount; print"</small></th>
									<td width=200 align=center class=input-small><small>"; echo $payment_mode; print"</small></th>
									<td width=200 align=center class=input-small ><small>";echo $app_date; print"</small></th>
									
									</tr>";
							
				}
				print "</table>";
			}
			else { print "There is no information to show "; }
		}
		elseif($search_mode == 'user_wise')
		{
			$u_name = $_REQUEST['user_info'];
			$q = query_execute_sqli("select * from users where username = '$u_name' ");
			$num = mysqli_num_rows($q);
			if($num == 0)
			{
				echo "<h3>Please Enter right User Name!</h3>"; 
			}
			else
			{
				while($id_row = mysqli_fetch_array($q))
				{
					$id_user = $id_row['id_user'];
				}
				
				$q_all = query_execute_sqli("select * from add_funds where user_id = '$id_user' and mode = 1 ");
				$num_all = mysqli_num_rows($q_all);
				if($num_all != 0)
				{
					print "
								<table hspace = 0 cellspacing=0 cellpadding=0 border=0 width=700>
								
								<tr>
							<th width=200 class=\"message tip\"><strong>Date</strong></th>
							<th width=200 class=\"message tip\"><strong>Amount</strong></th>
							<th width=200 class=\"message tip\"><strong>Payment Mode</strong></th>
							<th width=200 class=\"message tip\"><strong>Received Date</strong></th>
							
							</tr>
							<tr>
							<td align=center colspan=4 width=400 class=\"td_title\"><strong>&nbsp;</strong></th>
							</tr>"; 
			
					while($row = mysqli_fetch_array($q_all))
					{
							
								$amount = $row['amount'];
								$date = $row['date'];
								$payment_mode = $row['payment_mode'];
								$request_amount = $row['amount'];
								$app_date = $row['app_date'];
								
								
									print "<tr>
										<td width=200 align=center class=input-small ><small>";echo $date; print"</small></th>
										<td width=200 align=center class=input-small ><small>"; echo $amount; print"</small></th>
										<td width=200 align=center class=input-small><small>"; echo $payment_mode; print"</small></th>
										<td width=200 align=center class=input-small ><small>";echo $app_date; print"</small></th>
										
										</tr>";
								
					}
					print "</table>";
				}
			}	
		}
	}		
}	
else
{ ?>
<table align="center" border="0" width=450>
<form name="my_form" action="index.php?page=all_finance" method="post">
<tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="td_title"><strong> Daily Binary List Panel</strong></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="td_title"><p>Select Search Mode</p></td>
    <td><p><input type="radio" name="mode" value="date" /> By Date
			<input type="radio" name="mode" value="user" /> By User</p></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" value="Submit" class="btn btn-info" /></td>
    
  </tr>
  </form>
</table>
<?php  }  

