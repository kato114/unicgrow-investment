<?php
include('../../security_web_validation.php');
?>
<?php
include("../config.php");
include("condition.php");

if(isset($_POST['submit']))
{
	$username = $_REQUEST['username'];
	$query = query_execute_sqli("select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$user_type = $row['type'];
		}
		if($user_type != 'D')
		{
			query_execute_sqli("update users set type = 'D' where id_user = '$id_user' ");
			print "User Converted into Franchisee !";
		}
		else
		{
			print "You are already a Franchisee !";
		}
	}
	else
	{
		print "Please enter correct usernsme !";
	}
}
else
{?>

<table width="600" border="0">
<form name="franchisee" action="" method="post">
  <tr>
    <td colspan="2"><strong>Convert User Into Franchisee </strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Username :</td>
    <td><input type="text" name="username" class="form-control" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="Convert" class="btn btn-info" /></p></td>
  </tr>
  </form>
</table>
<?php } ?>


