<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../config.php");
include("../function/functions.php");
include("../function/setting.php");
include("../function/pair_point_calculation.php");



print $ioi = $_SERVER['HTTP_HOST'];



if(isset($_POST['submit']))
{
print $ioi = $_SERVER['PHP_SELF'];
	$d =query_execute_sqli("SELECT * FROM users WHERE type == 'C' ");
	$num = mysqli_num_rows($d);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($d))
		{
			$id_user = $row['id_user'];
			//query_execute_sqli("update wallet set amount = 0 where id = '$id_user' ");
			//query_execute_sqli("update reg_fees_structure set reg_fees = 0 , update_fees = 0 , total_days = 0 where user_id = '$id_user' ");
			//pair_point_calculation($id_user);
		}
	}	
		
		
}

?>

	<table width="200" border="1">
	<form name="zero_investment" method="post" action="index.php?page=block_member_investment_zero">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><h2><strong>Block Member Zero Investment Panel</strong></h2></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="Submit" name="submit" class="btn btn-info" /> </td>
	</tr>
	</form>
  </tr>
	
