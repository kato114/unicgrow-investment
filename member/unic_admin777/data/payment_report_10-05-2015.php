<?php
include('../../security_web_validation.php');
?>
<?php
//include("../config.php");
ini_set("display_errors","off");
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");
?>
<script type="text/javascript"> 
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
</script>
<?php
$mnth_end_day = date('t',strtotime($system_date));

$sql_income_join = "inner join income as incm on last_tab.user_id = incm.user_id group by incm.user_id";
$query_Search = "";

if(isset($_POST['search']))
{
	$query_Search = '';
	$y = $_POST['year'];
	$m = $_POST['month'];
	
	$start_date = date($y.'-'.$m.'-01');
	$end_date = date($y.'-'.$m.'-t');
	$query_Search .= "where t1.mode = 0 and t1.date >= '$start_date' and t1.date <= '$end_date' ";
	$select_date = "";
	$sql_income_join = "";
}
?>
<table align="center" cellspacing=0 cellpadding=0 width=100%> 
<form name="myformtt" method="post">
<tr>
	<th colspan=7 align="center">Select Month&nbsp;
		<select name="year" style="width:70px;">
			<option value="">YYYY</option>
	<?php
			$yr = date('Y');
			for($i = 2014; $i <= $yr; $i++) 
			{ ?>
				<option <?php if($year == $i) { ?> selected="selected" <?php } ?> 
				value="<?=$i; ?>"><?=$i; ?></option>
	<?php 	} ?> 
		</select>
		<select name="month" style="width:52px;">
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
		Select Date&nbsp;
		
		<select name="day" style="width:152px;">
			<option value="">Select Date</option>
			<option value="1">1-<?=$mnth_end_day?></option>
		</select>
		
		<input type="submit" name="Search" value="Search" class="btn btn-info" />	
	</th>
</tr>
</form>
</table>
<table align="center" cellspacing=0 cellpadding=0 width=90%> 
	<tr><th >&nbsp;</th></tr>
	<tr>
		<th class="text-center">Total Amount</th>
		<th class="text-center">Admin Tax </th>
		<th class="text-center">TDS Tax </th>
		<th class="text-center">Net Amount</th>
	</tr>
	<tr>
		<th class="form-control"><?=$_SESSION['tot_bonus'];?> RC</th>
		<th class="form-control"><?=$_SESSION['admin_tax']; ?> RC</th>
		<th class="form-control"><?=$_SESSION['admin_tds']; ?> RC</th>
		<th class="form-control"><?=$_SESSION['left_amnt'];?> RC</th>
	</tr>
	<tr><th colspan="4">&nbsp;</th></tr>
</table>
	
<form name="myformtt" method="post">
<table align="center" cellspacing=0 cellpadding=0 width=90%>
<tr>
	<th colspan=12  valign=top align=center >
		<p>
			For Payment Select and Click <img src="images/click_here.png" /> &nbsp;
			<input type="submit" name="payout" value="Payout" class="btn btn-info" />	
		</p>
	</th>
</tr>
<!--<tr><th colspan=12>&nbsp;</th></tr>
<tr>
	<th colspan=12>
		<strong style="font-size:20px;">
			To Create Excel File <a href="index.php?page=payment_report_excel">Click Here</a>
		</strong>
	</th>
</tr>-->
<tr><th colspan="12">&nbsp;</th></tr>
<tr align="center" style=" color:#000000;">
	<th height=30 class="text-center">
		 <INPUT type="checkbox" onchange="checkAll(this)" name="content[]" /> Check All  
	</th>
	<th height=30 class="text-center"><strong>SR NO.</th>
	<th class="text-center"><b>User Id</b></th>
	<th class="text-center"><b>Name</b></th>
	<th class="text-center"><b>ROI Income</b></th>
	<th class="text-center"><b>Binary Income</b></th>
	<th class="text-center"><b>Total Income</b></th>
	<th class="text-center"><b>Admin Tax</b></th>
	<th class="text-center"><b>TDS Tax</b></th>
	<th class="text-center"><b>Net Payble Amount</b></th>
	<!--<th class="text-center"><b>Date</b></th>-->
</tr>
<?php	
$pd = 1;	
$sql = "select last_tab.*, (roi_b + binary_b) as tot_b from ( 
		select user_id, coalesce(roi_b, 0) as roi_b ,
		coalesce(binary_b, 0) as binary_b
		from(
				SELECT t1.user_id,
				sum(case when t1.type=2 then t1.amount end) as roi_b,
				sum(case when t1.type=3 then t1.amount end) as binary_b
				FROM `income` as t1
				group by t1.user_id
		) as tab
	) as last_tab
	having tot_b > $setting_min_withdrawal";
	$query = query_execute_sqli($sql);

	while($row = mysqli_fetch_array($query))
	{	
		$left_amnt = 0;
		$u_id = $row['user_id'];
		$username = get_user_name($u_id);
		$name = get_full_name($u_id);
		//$wall_bal = wallet_balance($u_id);
		
		$roi_bonus = $row['roi_b'];
		$binary_bonus = $row['binary_b'];
		//$real_id = real_child($u_id);
		
		$tot_bonus = $roi_bonus+$binary_bonus;
		$request_amount = $tot_bonus;
		
		if($row['date1'] != '' and $row['date2'] != '')
		{
			$start_date = $row['date1'];
			$end_date = $row['date2'];
			$date_blank = 1;
			$start_date = date('d-m-Y' , strtotime($start_date));
			$end_date = date('d-m-Y' , strtotime($end_date));
		}			
		
		$roi_admin_tax = $roi_bonus*$admin_tax_roi/100;
		$binary_admin_tax = $binary_bonus*$admin_tax_binary/100;
		
		$admin_tds_anount = $request_amount*($setting_withdrawal_tax/100);
		$admin_tax_anount = $roi_admin_tax+$binary_admin_tax;
		$left_amnt = $request_amount-($admin_tds_anount+$admin_tax_anount);
		
		$total_bonus += $tot_bonus;
		$admin_taxes += $admin_tax_anount;
		$admin_tdss += $admin_tds_anount;
		$left_amount += $left_amnt;
		
		$_SESSION['tot_bonus'] = $total_bonus;
		$_SESSION['admin_tax'] = $admin_taxes;
		$_SESSION['admin_tds'] = $admin_tdss;
		$_SESSION['left_amnt'] = $left_amount;
		
		$paid_date = $row['app_date'];
		$date = $row['date'];
		$payment_mode = $row['payment_mode'];
		$information = $row['information'];
		$mode = $row['mode'];
	
	?>
		<tr align="center">
		<td align="left" class="form-control" style="padding-left:5px;">
			<input type="checkbox" name="content[]" value="<?=$u_id; ?>" />
		</td>
		<td><small><?=$pd; ?></small></td>
		<td><small><?=$username; ?></small></td>
		<td><small><?=$name; ?></small></td>
		<td><small><?=$roi_bonus; ?></small></td>
		<td><small><?=$binary_bonus; ?></small></td>
		<td><small><?=$tot_bonus; ?></small></td>
		<!--<td><small><?=$wall_bal;?> RC</small></td>-->
		<td><small><?=$admin_tax_anount;?> RC</small></td>
		<td><small><?=$admin_tds_anount;?> RC</small></td>
		<td><small><?=$left_amnt;?> RC</small></td>
		<!--<td>
			<small><?=$start_date; ?> <br />To<br /> <?=$end_date; ?></small>
		</td>-->
		</tr>
	<?php	
		$pd++; 
	}	
?>	
</table> 
</form>


