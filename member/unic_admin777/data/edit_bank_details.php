<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
//include("../condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");

if(isset($_POST['Update']))
{
	$user_id = $_POST['user_id'];
	$bank = $_POST['bank'];
	$bank_code =$_POST['bank_code'];
	$beneficiery_name = $_POST['beneficiery_name'];
	$ac_no =$_POST['ac_no'];
	$branch = $_POST['branch'];
	$pan_no = $_POST['pan_no'];
	
	query_execute_sqli("UPDATE users SET bank = '$bank' , bank_code = '$bank_code' , beneficiery_name = '$beneficiery_name' , ac_no = '$ac_no' , branch = '$branch' , pan_no = '$pan_no' WHERE id_user = '$user_id' ");
	
	print "<font color=\"green\" size=+0>Successfully Update</font>";
}

elseif(isset($_POST['search']))
{ 
	$username = $_POST['username'];
	$user_id = get_new_user_id($username);
	if($user_id != 0)
	{
		$query = query_execute_sqli("select * from users where id_user = '$user_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$ac_no = $row['ac_no'];
			$bank = $row['bank'];
			$branch = $row['branch'];
			$bank_code = $row['bank_code'];
			$beneficiery_name = $row['beneficiery_name'];
			$pan_no = $row['pan_no'];
			$phone_no = $row['phone_no'];
		}	
	
	?>
		<table width="650" border="0" align="center" height="200" class="table">
			<form method="post">
			<input type="hidden" name="user_id" value="<?php print $user_id;?>">
			<tr>
				<td colspan="3" style="color:#00274F; font-size:18px; font-weight:bold; text-align:left;">A/C Details : </td>
			</tr>
	
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="alerts">Bank Name</label></td>
			<td> <input type=text  style="width:170px;" id="bank" name="bank" class="form-control" value="<?php print $bank; ?>" required/>
			</td>
			<td><?php print $error_bank; ?></td>
			</tr>
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="alerts">Branch </label></td>
			<td> <input type=text  style="width:170px;" id="bank" name="branch" class="form-control" value="<?php print $branch; ?>" required/>
			</td>
			<td><?php print $error_bank; ?></td>
			</tr>
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="liberty">IFSC/MICR Code</label></td>
			<td> <input type=text id="bank_code"  style="width:170px;" name=bank_code class="form-control" value="<?php print $bank_code; ?>" required/>
			</td>
			<td><?php print $error_bank_code; ?></td>
			</tr>
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="alerts">Beneficiery Name</label></td>
			<td> <input type=text  style="width:170px;" id="beneficiery_name" name="beneficiery_name" class="form-control" value="<?php print $beneficiery_name; ?>" required/>
			</td>
			<td><?php print $error_beneficiery_name; ?></td>
			</tr>
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="liberty">Account No.</label></td>
			<td> <input type=text id="ac_no"  style="width:170px;" name="ac_no" class="form-control" value="<?php print $ac_no; ?>" required/>
			</td>
			<td><?php print $error_ac_no; ?></td>
			</tr>
			<tr>
			<td style="padding-left:20px; width:180px; text-align:left;"><label for="liberty">PAN No.</label></td>
			<td> <input type=text id="ac_no"  style="width:170px;" name="pan_no" class="form-control" value="<?php print $pan_no; ?>" required/>
			</td>
			<td><?php print $error_pan_no; ?></td>
			</tr>
			<tr>
			<td colspan="3" style="padding-left:150px"><br />
					<input style="width:80px; " id="send" type="submit" name="Update" value="Update" class="btn btn-info" /> </td></tr>
			</form>		
		</table>
<?php 
	}
	else
	{
		print "Please Enter Correct Username";
	}
} else{?>

<table align="center" border="0" width=450>
<form name="my_form" action="index.php?page=edit_bank_details" method="post">
  <tr>
    <td colspan="2" class="td_title" style="font-size:16px; color:#CC0000;"><strong></strong></td>
  </tr>
  <tr>
    <td class="td_title"><p>Enter UserName</p></td>
    <td><p><input type="text" name="username" size=3 class="form-control"/></p></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="search" value="Search" class="btn btn-info" /></td>
    
  </tr>
  </form>
</table>
<?php
}
?>