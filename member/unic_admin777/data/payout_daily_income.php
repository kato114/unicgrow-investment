<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/daily_income.php");



if(isset($_POST['submit']))
{
	query_execute_sqli("update income_process set mode = 1 ");
	
	get_daily_income();
	
	query_execute_sqli("update income_process set mode = 0 ");
}
else
{?>
	<table width="400" border="0">
	<form name="pay_form" action="index.php?page=payout_daily_income" method="post">
  <tr>
    <td colspan="2" style="font-size:16px; color:#666666;"><b>Generate Daily Income</b></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="submit" value="Pay" class="btn btn-info"  /></td>
  </tr>
  </form>
</table>

<?php }?>