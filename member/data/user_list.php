<?php
include('../security_web_validation.php');
?>
<?php
session_start();
$id = $_SESSION['mlmproject_user_id'];
?>
<style>
.td_title{
padding-bottom:15px;
}
</style>
<script type='text/javascript' src='edit_ validition.js'></script> 
<link rel="stylesheet" type="text/css" href="css/style.css" />

<h1>Search Members</h1>
<div id="content" class="narrowcolumn">
<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">

<table align="center" border="0" width=100%>
<form name="my_form" action="" method="post">
  <tr>
    <td colspan="5" class="td_title"><strong>Member Information</strong></td>
  </tr>
  <tr>
    <td class="td_title">Enter Member UserName</td>
    <td><input type="text" name="user_name" size=3 required="required"/></td>
	<td class="td_title">&nbsp;Position</td>
	<td><select name="position" required="required">
			<option value="0">Left</option>
			<option value="1">Right</option>
		</select>
	</td>
	<td class="td_title"><input type="submit" name="submit" value="Submit" class="normal-button"/></td>
  </tr>
  <tr>
    
    
  </tr>
  </form>
</table>
<?php
if(isset($_REQUEST['submit']) and $_REQUEST['submit']=='Submit')
{
	$user_name = $_REQUEST['user_name'];
	$user_Id = get_new_user_id($user_name);
	
	$sql = query_execute_sqli("select * from users where parent_id = '$user_Id' and position='$position' and id_user > '$id'");
	$num = mysqli_num_rows($sql);
	if($num < 1)
	{
		print "<font color=\"#FF0000\">Username Incorrect !!</font>";
	}
	else
	{
		while($row = mysqli_fetch_array($sql))
		{
			$user_Id = $row['id_user'];
		}
		$arr_usr = get_downline_network_by_position($user_Id,$position);
		?>
		<table id="data-table" class="display" width="850" hspace="0" cellspacing="0" cellpadding="0" border="0" align="center">
		  <tr>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>S.No.</strong></td>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>Username</strong></td>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>Name</strong></td>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>Phone</strong></td>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>E-Mail</strong></td>
			<td class="td_title message tip" align="center" style="padding-left:10px"><strong>City</strong></td>
		  </tr>	
		  <tr height="25"><td colspan="6"></td></tr>	
		<?php
		$s_no = 1;
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
						<td align=\"left\"><small>$s_no</small></td>
						<td align=\"center\"><small>$username</small></td>
						<td align=\"center\"><small>$name</small></td>
						<td align=\"center\"><small>$phone</small></td>
						<td align=\"center\"><small>$email</small></td>
						<td align=\"center\"><small>$city</small></td>
					</tr>";
			$s_no++;
		}
		print "</table>";
	}
}

?>
</div>
</div>
