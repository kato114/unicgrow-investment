<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/daily_income.php");

$time = $systems_date_time;
if(isset($_POST['payout']))
{
	
		query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
											values('$date' , CURTIME(), CURTIME(), '1' , '2')");
		query_execute_sqli("update income_process set mode = 1 ");
		$percent = 100;
		if(isset($_REQUEST['percent']) and $_REQUEST['percent'] != 0 and is_numeric($_REQUEST['percent']))
		{
			$percent = $_REQUEST['percent'];
		}
		get_fif_min_income($time,$percent);
		
		//get_monthly_only_income($systems_date);
		query_execute_sqli("update income_process set mode = 0 ");
		
		query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
										values('$date' , CURTIME(), CURTIME(), '2' , '2')");
}

{?>
	<p></p><table width="400" border="0">
	<form name="pay_form" action="index.php?page=payout_income" method="post">
  <tr>
    <td colspan="2" style="font-size:16px; color:#666666;"><b>Generate Income</b></td>
  </tr>
  <!--<tr>
    <td  style="font-size:16px; color:#666666;"><b>Set Income Percent</b></td>
	<td><input type="text" name="percent" value="" /></td>
  </tr>-->
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="payout" value="Payout" class="btn btn-info"  /></td>
  </tr>
  </form>
</table>
<?php }?>
