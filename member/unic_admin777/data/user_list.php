<?php
include('../../security_web_validation.php');
?>
<?php
	session_start();
	include "../function/functions.php";
?>
<style>
.odd  td{
border-right:#999999 1px solid;
padding-left:5px;
}
.even  td{
border-right:#999999 1px solid;
padding-left:5px;
}
.even {
    background: #e5e5e5 none repeat scroll 0 0;
}
.odd {
   #fff none repeat scroll 0 0
}
</style>
<script type='text/javascript' src='edit_ validition.js'></script> 
<link rel="stylesheet" type="text/css" href="css/style.css" />


<table align="center" hspace = 0 cellspacing=0 cellpadding=0 border=0  width=65%>
<form name="my_form" action="" method="post">
  
  <tr>
    <td class="td_title">Enter Member UserName</td>
    <td><input type="text" name="user_name"  required="required"/></td>
	<td class="td_title">&nbsp;Position</td>
	<td><select name="position" required="required">
			<option value="0">Left</option>
			<option value="1">Right</option>
		</select>
	</td>
	<td class="td_title">&nbsp;<input type="submit" name="submit" value="Submit" class="btn btn-info"/></td>
  </tr>
  <tr>
    
    
  </tr>
  </form>
</table>
<?php
if(isset($_REQUEST['submit']) and $_REQUEST['submit']=='Submit')
{
	$user_name = $_REQUEST['user_name'];
	$position = $_REQUEST['position'];
	$user_Id = get_new_user_id($user_name);
	if($user_Id != 0)
	{
		$sql = query_execute_sqli("select * from users where parent_id = '$user_Id' and position='$position'");
		$num = mysqli_num_rows($sql);
		if($num < 1)
		{
			print "<p></p><font color=\"#FF0000\">There Have No Downline !!</font>";
		}
		else
		{
			
			?>
			<table id="data-table" class="display" width="850" hspace="0" cellspacing="0" cellpadding="0" border="0" align="center">
			<tr height="25"><td colspan="6"></td></tr>
			  <tr>
				<td class="text-center" align="center"><strong>S.No.</strong></td>
				<td class="text-center" align="center"><strong>Username</strong></td>
				<td class="text-center" align="center"><strong>Name</strong></td>
				<td class="text-center" align="center"><strong>Phone</strong></td>
				<td class="text-center" align="center"><strong>E-Mail</strong></td>
				<td class="text-center" align="center"><strong>City</strong></td>
			  </tr>	
			  <tr height="25"><td colspan="6"></td></tr>
			  	
			<?php
			while($row = mysqli_fetch_array($sql))
			{
				$user_Id = $row['id_user'];
				$username = $row['username'];
				$name = ucfirst($row['f_name']."&nbsp;".$row['l_name']);
				$phone = $row['phone_no'];
				$email = $row['email'];
				$city = $row['city'];
				print "<tr class=\"even\">
							<td class=\"\" align=\"center\"><small>1</small></td>
							<td class=\"\"><small>$username</small></td>
							<td class=\"\"><small>$name</small></td>
							<td class=\"\"><small>$phone</small></td>
							<td class=\"\"><small>$email</small></td>
							<td class=\"\"><small>$city</small></td>
						</tr>";
			}
			$arr_usr = get_downline_network_by_position($user_Id,$position);
			
			$s_no = 2;
			for($i = 0; $i < count($arr_usr); $i++)
			{
				$username = $arr_usr[$i]['username'];
				$name = ucfirst($arr_usr[$i]['f_name']."&nbsp;".$arr_usr[$i]['l_name']);
				$phone = $arr_usr[$i]['phone_no'];
				$email = $arr_usr[$i]['email'];
				$city = $arr_usr[$i]['city'];
				if($s_no%2==0)
				{
					$class = "odd";
				}
				else
				{
					$class = "even";
				}
				
				print "<tr class=\"$class\">
							<td class=\"\" align=\"center\"><small>$s_no</small></td>
							<td class=\"\"><small>$username</small></td>
							<td class=\"\"><small>$name</small></td>
							<td class=\"\"><small>$phone</small></td>
							<td class=\"\"><small>$email</small></td>
							<td class=\"\"><small>$city</small></td>
						</tr>";
				$s_no++;
			}
			print "</table>";
		}
	}
	else
	{
		print "<p></p><font color=\"#FF0000\">Username Incorrect !!</font>";
	}
}

?>
</div>
</div>
