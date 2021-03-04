<?php
include('../../security_web_validation.php');
?>
<?php
//include("../config.php");
ini_set("display_errors","off");
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$cnt_d = date('M');
$cnt_yr = date('Y');

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

$start_date = $_REQUEST['s_date'];
$end_date = $_REQUEST['e_date'];
$mnth_end_day = date('t',strtotime($systems_date));

$selected_day = intval(date("d"));
$selected_month = date("m");
$selected_year = date("Y");
if($_POST['year'] == '' and $_POST['month'] == '' and $_POST['day'] =='' )
{
	$find_interval = ", MIN(`date`) AS date1, MAX(`date`) AS date2 ";
	$date_field = ",date1,date2";
}
/*$select_date = ",MIN(`date`) AS date1,MAX(`date`) AS date2 ";*/
$sql_income_join = "inner join income as incm on last_tab.user_id = incm.user_id group by incm.user_id";
$query_Search = "";

if(isset($_POST['Search']))
{	
	$query_Search = '';
	$y = $_POST['year'];
	$m = $_POST['month'];
	$last_frid = date("Y-m-d",strtotime($systems_date.'last friday'));
	
	$start_date = date($y.'-'.$m.'-01');
	$end_date = date($y.'-'.$m.'-t');
	$query_Search .= "where t1.mode = 0 and t1.date >= '$start_date' and t1.date <= '$end_date' ";
	$select_date = "";
	$sql_income_join = "";
}

$last_fri = date("Y-m-d",strtotime($systems_date.'last friday'));
// This code for Paging of next page
/*if(isset($_REQUEST['s_date'] ) and $_REQUEST['s_date'] !='' and isset($_REQUEST['e_date']) and $_REQUEST['e_date'] !='')
{	$query_Search = '';
	$query_Search .= "where t1.mode = 0 and t1.date >= '".$_REQUEST['s_date']."' and t1.date <= '".$_REQUEST['e_date']."' ";
	$select_date = "";
	$sql_income_join = "";
}*/	
if(isset($_POST['payout']))
{
	$total_array = $_POST['content'];
	$cnt = count($total_array );
	$date_array = $_REQUEST['pay_date'];
	for($i = 0; $i < $cnt; $i++)
	{
		$u_id = $total_array[$i];
		if($u_id > 0):
		$username = get_user_name($u_id);
		/*$cont = count($date_array);
		for($j = 0; $j <= $cont; $j++)
		{
			$pay_user = $date_array[$j]['pay_user'];
			if($pay_user == $u_id)
			{
				$start_day = $date_array[$j]['p_s_date']; 
				$end_day = $date_array[$j]['p_e_date'];
				break;
			}
		}*/
		
		$sql = "select last_tab.*, (direct_b + binary_b) as tot_b from ( 
				select user_id, coalesce(direct_b, 0) as direct_b ,
					coalesce(binary_b, 0) as binary_b
				from(
						SELECT t1.user_id,
						sum(case when t1.type=1 then t1.amount end) as direct_b ,
						sum(case when t1.type=2 then t1.amount end) as binary_b
						FROM `income` as t1
						where t1.user_id = '$u_id' and `date` <= '$last_fri'
						group by t1.user_id
				) as tab
			) as last_tab
			$sql_income_join
			having tot_b > $setting_min_withdrawal";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0)
		{	
			while($row = mysqli_fetch_array($query))
			{
				$direct_bonus = $row['direct_b'];
				$binary_bonus = $row['binary_b'];
				$tot_bonus = $row['tot_b'];
				
				$direct_b = $direct_bonus;
				$binary_b = $binary_bonus;
			}
			
			$wall_bal = wallet_balance($u_id);
			$st_date = $ed_date = '';
			//print $u_id;
				
			if($tot_bonus >= $setting_min_withdrawal)		
			{
				$left_amnt = 0;
				
				$request_amount = $tot_bonus;
				
				$admin_tds = $request_amount*($setting_withdrawal_tax/100);
				$admin_tax = $request_amount*($setting_admin_tax/100);
				$left_amnt = $request_amount-($admin_tax+$admin_tds);
				
				$date = date('Y-m-d');
			
				//print $wall_bal.'--'.$left_amnt;
				//print "<br>";
				/*if($left_amnt <= $wall_bal)
				{*/
				
				// print $u_id."-".$left_amnt."-".$wall_bal.'-'.$tot_bonus.'-'.$setting_min_withdrawal;
					
					$sqls = "insert into payment_information 
					(user_id , income , tax , tds , date , amount ,period_st_date , 
					period_end_date) values ('$u_id' , '$request_amount' , '$admin_tax' , 
					'$admin_tds' , '$date' , '$left_amnt' , '$start_day' , '$end_day') ";
					query_execute_sqli($sqls);
					
				
					//query_execute_sqli("update wallet set roi = roi-'$direct_bonus' where id = '$u_id' ");
					
					$sqw = "update income set mode = 1 where user_id = '$u_id' and type in(2,3)";
					query_execute_sqli($sqw);
				//}
			}
		}
		else
		{ print "<p>".$username." is not eligible for payment !!</p>"; }
		endif;
	}	
	print "<p>Payment has been Transfered From Wallet !<br>Payment is on Hold !!</p>"; 
}
else
{	if($query_Search == '')
	{
		$query_Search = "where t1.mode=0";
	}
	else
	{
		$query_Search .= "and t1.mode=0";
	}
	$cur_date_st = date('Y-m-01' );
	$cur_date_end = date('Y-m-t');
	$query_Search .= " and date <= '$last_fri' ";
	
 	$sqli = "select last_tab.*, (direct_b + binary_b) as tot_b from ( 
				select user_id$date_field, coalesce(direct_b, 0) as direct_b ,
				coalesce(binary_b, 0) as binary_b
				from(
						SELECT t1.user_id,
						sum(case when t1.type=1 then t1.amount end) as direct_b,
						sum(case when t1.type=2 then t1.amount end) as binary_b
						$find_interval
						FROM `income` as t1
						$query_Search
						group by t1.user_id
				) as tab
			) as last_tab
			$sql_income_join
			having tot_b > $setting_min_withdrawal";
	$_SESSION['sql_payment'] = $sqli;
	 
	$quer = query_execute_sqli($sqli);
	$totalrows = mysqli_num_rows($quer);
	if($totalrows != 0)
	{
		while($ro = mysqli_fetch_array($quer))
		{ 
			$totalamount += $ro['tot_b'];
			$direct_b += $ro['direct_b'];
			$binary_b += $ro['binary_b'];
			$u_id = $ro['user_id'];
			/*$real_id = real_child($u_id);
			if($real_id == 1)
			{
				$sh_tab = 1;
			}*/
			$sh_tab = 1;
		}
		if($sh_tab  ==1)
		{
			/*$roi_admin_tx = $direct_b*$admin_tax_roi/100;
			$binary_admin_tx = $binary_b*$admin_tax_binary/100;
			
			$admin_tds = $totalamount*($setting_withdrawal_tax/100);
			$admin_tax = $roi_admin_tx+$binary_admin_tx;
			$left_amnt = $totalamount-($admin_tax+$admin_tds);*/
			
			$admin_tds = $totalamount*($setting_withdrawal_tax/100);
			$admin_tax = $totalamount*($setting_admin_tax/100);
			$left_amnt = $totalamount-($admin_tax+$admin_tds);
				
			?>		
			<table align="center" cellspacing=0 cellpadding=0 width=100%> 
			<form name="myformtt" method="post">
			<tr>
				<th colspan=7 align="center">Select Month&nbsp;
					<select id="year" name="year" style="width:70px;">
						<option value="">YYYY</option>
				<?php
						$yr = date('Y');
						for($i = 2014; $i <= $yr; $i++) 
						{ ?>
							<option <?php if($year == $i) { ?> selected="selected" <?php } ?> 
							value="<?=$i; ?>"><?=$i; ?></option>
				<?php 	}  ?> 
					</select>
					<select id="month" name="month" style="width:52px;" onchange="get_date()">
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
				
					<!--Select Date&nbsp;
					<select name="day" style="width:152px;">
						<option value="">Select Date</option>
						<option value="1">1-<?=$mnth_end_day?></option>
					</select>-->
					
					<input type="submit" name="Search" value="Search" class="btn btn-info" />	
				</th>
			</tr>
			</form>
			</table>
			<table align="center" cellspacing=0 cellpadding=0 width=100%> 
				<tr><th >&nbsp;</th></tr>
				<tr>
					<th class="text-center">Total Amount</th>
					<th class="text-center">Admin Tax </th>
					<th class="text-center">TDS Tax </th>
					<th class="text-center">Net Amount</th>
				</tr>
				<tr>
					<th class="form-control"><?=$totalamount;?> RC</th>
					<th class="form-control"><?=$admin_tax; ?> RC</th>
					<th class="form-control"><?=$admin_tds; ?> RC</th>
					<th class="form-control"><?=$left_amnt;?> RC</th>
				</tr>
				<tr><th colspan="4">&nbsp;</th></tr>
				
			</table>
			
			<form name="myformtt" method="post">
			<table align="center" cellspacing=0 cellpadding=0 width=100%>
			<?php
			/*$day = date("D", strtotime($systems_date));
			if($day == 'Fri')
			{*/
			?>
			<tr>
				<th colspan=12  valign=top align=center >
					<p>
						For Payment Select and Click <img src="images/click_here.png" /> &nbsp;
						<input type="submit" name="payout" value="Payout" class="btn btn-info" />	
					</p>
				</th>
			</tr>
			<?
			//}
			?>
			<tr><th colspan=12>&nbsp;</th></tr>
			<tr align="right" height="50">
				<th class="text-center" colspan=12>
					<B style="font-size:20px;">
						To Create Excel File <img src="images/click_here.png" /> &nbsp; 
						<a href="index.php?page=payment_report_excel">Click Here</a>
					</B>
				</th>
			</tr>
			<tr align="center" style=" color:#000000;">
				<th height=30 class="text-center">
					 <INPUT type="checkbox" onchange="checkAll(this)" name="content[]" /> Check All  
				</th>
				<th height=30 class="text-center"><strong>SR NO.</th>
				<th class="text-center"><b>User Id</b></th>
				<th class="text-center"><b>Name</b></th>
				<th class="text-center"><b>Direct Income</b></th>
				<th class="text-center"><b>ROI Income</b></th>
				<th class="text-center"><b>Total Income</b></th>
				<!--<th class="text-center"><b>Wallet Amount</b></th>-->
				<th class="text-center"><b>Admin Tax</b></th>
				<th class="text-center"><b>TDS Tax</b></th>
				<th class="text-center"><b>Net Payble Amount</b></th>
				<th class="text-center"><b>Date</b></th>
			</tr>
		<?php		
			$pd = 1;
			$sql = "select last_tab.*, (direct_b + binary_b) as tot_b from ( 
					select user_id$date_field, coalesce(direct_b, 0) as direct_b ,
					coalesce(binary_b, 0) as binary_b
					from(
							SELECT t1.user_id,
							sum(case when t1.type=1 then t1.amount end) as direct_b,
							sum(case when t1.type=2 then t1.amount end) as binary_b
							$find_interval
							FROM `income` as t1
							$query_Search
							group by t1.user_id
					) as tab
				) as last_tab
				$sql_income_join
				having tot_b > $setting_min_withdrawal";
			$query = query_execute_sqli($sql);
			
			$_SESSION['sql_payment'] = $sql;
			
			while($row = mysqli_fetch_array($query))
			{	
				$left_amnt = 0;
				$u_id = $row['user_id'];
				$username = get_user_name($u_id);
				$name = get_full_name($u_id);
				$wall_bal = wallet_balance($u_id);
				
				$direct_bonus = $row['direct_b'];
				$binary_bonus = $row['binary_b'];
				//$real_id = real_child($u_id);
				
				$tot_bonus = $direct_bonus+$binary_bonus;
				$request_amount = $tot_bonus;
				if($row['date1'] != '' and $row['date2'] != '')
				{
					$start_date = $row['date1'];
					$end_date = $row['date2'];
					$date_blank = 1;
					$start_date = date('d-m-Y' , strtotime($start_date));
					$end_date = date('d-m-Y' , strtotime($end_date));
				}			
				
				/*if($tot_bonus == $request_amount) { $color = "color:#333;";} //When wallet balance equal
				else{ $color = "color:#000099;";}
				
				if($real_id == 1) {  $color = "color:#333;";} //When real child > 2 
				else{ $color = "color:#FF0000;"; }
				
				if($tot_bonus != $request_amount) //When wallet balance not equal
				{ $color = "color:#000099;";}
				
				if($tot_bonus != $request_amount and $real_id != 1) //When real child < 2 & wallet balance not equal
				{ $color = "color:#712f07;";}
				 
				$roi_admin_tax = $direct_bonus*$admin_tax_roi/100;
				$binary_admin_tax = $binary_bonus*$admin_tax_binary/100;
				
				$admin_tds_anount = $request_amount*($setting_withdrawal_tax/100);
				$admin_tax_anount = $roi_admin_tax+$binary_admin_tax;
				$left_amnt = $request_amount-($admin_tds_anount+$admin_tax_anount);*/
				
				$admin_tds_anount = $request_amount*($setting_withdrawal_tax/100);
				$admin_tax_anount = $request_amount*($setting_admin_tax/100);
				$left_amnt = $request_amount-($admin_tax_anount+$admin_tds_anount);
								
				$paid_date = $row['app_date'];
				$date = $row['date'];
				$payment_mode = $row['payment_mode'];
				$information = $row['information'];
				$mode = $row['mode'];
		
		?>
				<tr align="center">
				<td align="left" class="form-control" style="padding-left:5px;">
					<input type="checkbox" name="content[]" value="<?=$u_id; ?>" />
					<input type="hidden" name="pay_date[<?=$pd;?>][<?='pay_user';?>]" value="<?=$u_id; ?>" />
					<input type="hidden" name="pay_date[<?=$pd;?>][<?='p_s_date';?>]" value="<?=$start_date; ?>" />
					<input type="hidden" name="pay_date[<?=$pd;?>][<?='p_e_date';?>]" value="<?=$end_date; ?>" />	
				</td>
				<td><small><?=$pd; ?></small></td>
				<td><small><?=$username; ?></small></td>
				<td><small><?=$name; ?></small></td>
				<td><small><?=$direct_bonus; ?></small></td>
				<td><small><?=$binary_bonus; ?></small></td>
				<td><small><?=$tot_bonus; ?></small></td>
				<!--<td><small><?=$wall_bal;?> RC</small></td>-->
				<td><small><?=$admin_tax_anount;?> RC</small></td>
				<td><small><?=$admin_tds_anount;?> RC</small></td>
				<td><small><?=$left_amnt;?> RC</small></td>
				<td>
					<small><?=$start_date; ?> <br />To<br /> <?=$end_date; ?></small>
				</td>
				</tr>
		<?php	$pd++; 
		
			}	?>	
		</table> 
		</form>
<?php 
		}
		else{ print "<p>There is No Member for Payment!</p>"; }
	}
	else{ print "<p>There is No Member for Payment on $cnt_d $cnt_yr !</p>"; }
}	?>

