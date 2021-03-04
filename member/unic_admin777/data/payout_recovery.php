<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");
include("../function/binary_recovery_income.php");
if(isset($_SESSION['success']) and $_SESSION['success'] == 0)
{
	unset($_SESSION['success']);
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=payout_recovery\"";
	echo "</script>";
}

if($_REQUEST['success'] == 1 and $_SESSION['success']== 1)
{
	$_SESSION['success'] = 0;
	print "<font size=5 color=\"#004080\">Binary Income Successfully Distributed on today To All Members! </font><br>";
}



?>
	<table width="60%" border="0">
	<form name="pay_form" action="index.php?page=payout_recovery" method="post">
  <tr>
    <td colspan="4" style="font-size:16px; color:#666666;"><b>Re-Generate Income</b></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
  <td>
  <select name="year" style="width:70px;" required>
					<option value="">YYYY</option>
			<?php
					$yr = date('Y');
				 	for($i = 2013; $i <= $yr; $i++) 
				 	{ ?>
						<option value="<?=$i;?>"><?=$i; ?></option>
			<?php 	} ?> 
	</select>
	</td>
	<td>
	<select name="month" style="width:52px;" required>
		<option value="">MM</option>
		<option value="1">Jan</option>
		<option value="2">Feb</option>
		<option value="3">Mar</option>
		<option value="4">Apr</option>
		<option value="5">May</option>
		<option value="6">Jun</option>
		<option value="7">Jul</option>
		<option value="8">Aug</option>
		<option value="9">Sep</option>
		<option value="10">Oct</option>
		<option value="11">Nov</option>
		<option value="12">Dec</option>
	</select>
	</td>
	<td>Select Date&nbsp;
				
				<select name="day" style="width:152px;" required>
					<option value="">Select Date</option>
					<?php
					$yr = 31;
				 	for($i = 1; $i <= $yr; $i++) 
				 	{ ?>
						<option	value="<?=$i;?>"><?=$i;?></option>
			<?php 	} ?> 
				</select>
	</td>
    <td><input type="submit" name="pair" value="Search" class="btn btn-info"  /></td>
  </tr>
  </form>
</table>
<p></p>

<?php 

if(isset($_POST['pair']))
{
	query_execute_sqli("update income_process set mode = 1 ");
	$date = $_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];
	pair_point_income($date);
	query_execute_sqli("update income_process set mode = 0 ");
	
}
if(isset($_REQUEST['paid_continue']) and $_REQUEST['paid_continue'] =='Continue')
{
	$arr = $_POST;
	$date = $arr["date"];
	$id_arr = $arr["reco_id"];
	$amt_arr = $arr["reco_aot"];
	for($i = 0; $i < count($id_arr); $i++)
	{
		$id = $id_arr[$i];
		$income = $amt_arr[$i];
		
		query_execute_sqli("insert into income (user_id , amount , date , type ) values ('$id' , '$income' , '$date' , '$income_type[3]') ");
										
		query_execute_sqli("update wallet set amount = amount+'$income' , date = '$date'  where id = '$id' ");
		$income_type_log = "Binary Income";
		$log_username = get_user_name($id);
		$income_log = $income;
		include("../function/logs_messages.php");
		data_logs($id,$data_log[23][0],$data_log[23][1],$log_type[20]);
	}
	$_SESSION['success'] = 1;
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=payout_recovery&success=1\"";
	echo "</script>";
	
}
?>
