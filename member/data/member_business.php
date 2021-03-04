<?php
include('../security_web_validation.php');
?>
<style>
th,td{ text-align:center;}
</style>
<?php

include("function/member_business_function.php");
if(isset($_POST['Submit']))
{
	$start_date = $_REQUEST['start_date'];
	$start_date = date("Y-m-d", strtotime($start_date));  // change date format
	$end_date = $_REQUEST['end_date'];
	$end_date = date("Y-m-d", strtotime($end_date));  // change date format
	
	$date1=date_create($start_date);
	$date2=date_create($end_date);
	$diff=date_diff($date1,$date2);
	$cnt_day = $diff->format("%a");
	
	// two month between total day
	$first_date = date('Y-m-d',strtotime($start_date."2 month"));
	$mt_date1 = $date1; 
	$mt_date2 = date_create($first_date);
	$mt_diff=date_diff($mt_date1,$mt_date2);
	$mt_cnt_day = $mt_diff->format("%a"); // two month between total day
	
	if($cnt_day<=$mt_cnt_day)
	{
	$id = $_SESSION['mlmproject_user_id'];
	$member_business_level = 0;
	
	$sql = "select t1.username,t2.user_id 
			from users as t1
			inner join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			where t2.user_id = '$id' 
			group by t1.id_user,t2.user_id 
			order by t2.user_id ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$i = 0;
		$error = 0;
		$username = get_user_name($id);
		$business = give_all_children($id,$start_date,$end_date);
		$left_business = $business[0];
		$right_business = $business[1];
		$left_cnt = count($left_business);
		$right_cnt = count($right_business);
		$total_left_business = ($left_business[0][3]);
		$total_right_business = ($right_business[0][3]);
		echo "<table class=\"table table-bordered table-hover\">
				 <tr>
					<th colspan=2 class=\"text-center\">Start Date</th>
					<th colspan=2 class=\"text-center\">$start_date</th>
					<th colspan=2 class=\"text-center\">End Date</th>
					<th colspan=2 class=\"text-center\">$end_date</th>
				</tr>
				<tr>
					<th colspan=\"4\" class=\"text-center\">Username</th>
					<th colspan=\"4\" class=\"text-center\">$username</th>
				</tr>
				<tr>
					<th colspan=\"2\" class=\"text-center\">Left Business</th>
					<th colspan=\"2\" class=\"text-center\">$total_left_business</th>
					<th colspan=\"2\" class=\"text-center\">Right Business</th>
					<th colspan=\"2\" class=\"text-center\">$total_right_business</th>
				 </tr>";
		 echo "<tr>";							
		while($row = mysqli_fetch_array($query))
		{	
			
			$id = $row['user_id'];
			
			if($id > 0 and $id != NULL)
			{
					
					
					if($left_cnt >0)
					{	
						echo "<td colspan=\"4\">
								<table class=\"table table-bordered table-hover\">
								<tr>
									<th class=\"text-center\">Username</th>
									<th class=\"text-center\">Business</th>												
									<th class=\"text-center\">Topup Date</th>
								 </tr>";
						$tot_business = 0;
						for($jj = 0; $jj<$left_cnt; $jj++)
						{
						$username = $left_business[$jj][0];
						$business = $left_business[$jj][1];
						$topup_date = $left_business[$jj][2];
						$li = $left_business[$jj][3];
						$error = 1;
							if($business != '' or $business != 0)
							{
								echo "<tr>
											<th class=\"text-center\">$username&nbsp;$li</th>
											<th class=\"text-center\">$business</th>
											<th class=\"text-center\">$topup_date</th>
										</tr>";
										$tot_business = $tot_business+$business;
							}
						}
						/*echo "
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td colspan=\"2\" class=\"text-center\">Total Left Business</td>
									<td colspan=\"2\" class=\"text-center\">$tot_business</td>
								<tr>";*/
						echo "</table></td>";
					}
					
					if($right_cnt >0)
					{	
						echo "<td colspan=\"4\" valign=\"top\">
								<table width=100% style=\"padding-left:0px;\">
								<tr>
									<th class=\"text-center\">Username</th>
									<th class=\"text-center\">Business</th>												
									<th class=\"text-center\">Topup Date</th>
								 </tr>";
						$tot_business = 0;
						for($jj = 0; $jj<$right_cnt; $jj++)
						{
						$username = $right_business[$jj][0];
						$business = $right_business[$jj][1];
						$topup_date = $right_business[$jj][2];
						$ri = $right_business[$jj][3];
						$error = 1;
							if($business != '' or $business != 0)
							{
								echo "<tr>
											<th class=\"text-center\">$username &nbsp;$ri</th>
											<th class=\"text-center\">$business</th>
											<th class=\"text-center\">$topup_date</th>
										</tr>";
										$tot_business = $tot_business+$business;
							}
						}
						/*echo "
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td colspan=\"1\" class=\"text-center\">Total Right Business</td>
									<td colspan=\"2\" class=\"text-center\">$tot_business</td>
								<tr>";*/
							echo "</table></td>";
					}
				}
			}
		}
		else
		{
			echo "There Are No Top Up";
		}
		echo "</tr></table>";
	
	}
	else
	{
		$_SESSION['error_date'] = "
			<font style=\"color:#FF0000\">Please Fill Two Month Date</font>
		";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=member_business\"";
		echo "</script>";
	}
	
}
else
{
echo $_SESSION['error_date'];
unset($_SESSION['error_date']);
?>
<form method="post" action="">
	<table class="table table-bordered table-hover">
	  <thead><tr><th colspan="5" class="align-left">Member Business</th></tr></thead>
	  <tr>
			<td>Start Date</td>
			<td><input type="text" name="start_date" placeholder="Start Date" class="flexy_datepicker_input" /></td>
			<td>End Date</td>
			<td><input type="text" name="end_date" placeholder="End Date" class="flexy_datepicker_input" /></td>
			<td><input type="submit" value="Submit" name="Submit" class="btn btn-primary" /></td>
		</tr>
	</table>
</form>	
<?php
}



?>