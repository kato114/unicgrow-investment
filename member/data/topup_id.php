<?php
include('../security_web_validation.php');
?>
<?php
session_start();
ini_set("dispaly_errors" , "off");
include("condition.php");
include("function/setting.php");

include("function/direct_income.php");
include("function/check_income_condition.php");
include("function/pair_point_calc.php");
include("function/all_child.php");
?>

<script>$(document).ready(function() {	
	$("#sponsor_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 5){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 5){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('check_username.php', {'sponsor_username':sponsor_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>
<center>
<?php
$login_id = $_SESSION['mlmproject_user_id'];
$inv_epin = $_POST['invest_epin'];
$date = $systems_date;
$t = date('h:i:s');

if(isset($_POST['submit']))
{
	$plan_select_type = $_POST['plan_select_type'];
	$invest_by = $_REQUEST['investment'];
	$pin = $_REQUEST['pin'];
	$user_name = $_REQUEST['user_id'];
	$id = get_new_user_id($_REQUEST['user_id']);
	if($id != 0 and $id != $login_id)
	{
		$sql = "select * from reg_fees_structure where user_id='$id' and level=0";
		$numq = mysqli_num_rows(query_execute_sqli($sql));
		if($numq == 0){
			$user_down_exist =  get_downline($login_id,$id);
			if($user_down_exist ==1)
			{
				$query = query_execute_sqli("select * from e_pin where epin = '$invest_by' and epin_type > 0 and mode = 1 ");		
				$epin_exist = mysqli_num_rows($query);
				if($epin_exist > 0)
				{
					while($row = mysqli_fetch_array($query))
					{
						$epin_tbl_id = $row['id'];
						$investment = $row['amount'];
						$epin_type = $row['epin_type']; 
					}	
					
					$q = query_execute_sqli("select * from users where id_user = '$login_id' and user_pin = '$pin' ");
					$num_count = mysqli_num_rows($q);
					if($num_count > 0)
					{
						$_SESSION['session_user_investment'] = 1;
			
			?>			<form method="post" action="">
							<input type="hidden" name="investment" value="<?=$invest_by;?>" />
							<input type="hidden" name="username" value="<?=$user_name;?>" />
							<input type="submit" name="confirm" value="Confirm" class="btn btn-primary" />
						</form>
						
			<?php	}
					else 
					{  echo "<font color=\"#FF0000\">Error : Please Enter Correct Password!!</font>"; }		
				}
				else
				{  echo "<font color=\"#FF0000\">Please Enter Correct Envestment Pin</font>";  }
			}
			else
			{  echo "<font color=\"#FF0000\">Username Dosn't Exist in Downline!!</font>";  }
		}
		else
		{  echo "<font color=\"#FF0000\">Member Have Al-ready Activate !!</font>";  }
	}
	else
	{  echo "<font color=\"#FF0000\">Please Enter Correct Username</font>";  }	
}

elseif(isset($_POST['confirm']) and $_SESSION['session_user_investment'] == 1)
{
	if($_POST['confirm'] == 'Confirm')
	{
		$invest_by = $_POST['investment'];
		$id = get_new_user_id($_POST['username']);
		if($id != 0)
		{
			$query = query_execute_sqli("select * from e_pin where epin = '$invest_by' and epin_type > 0 and mode = 1 ");	
			$epin_exist = mysqli_num_rows($query);
			if($epin_exist > 0)
			{
				while($row = mysqli_fetch_array($query))
				{
					$epin_tbl_id = $row['id'];
					$investment = $row['amount'];
					$epin_type = $row['epin_type']; 
				}
			
				$investmentmode = 'epin';//$_REQUEST['investmentmode'];
				
				$qr = query_execute_sqli("select * from plan_setting where id = '$epin_type' ");
				while($rr = mysqli_fetch_array($qr))
				{
					$plan_id = $rr['id'];
					$days = $rr['days'];
					$pv = $rr['pv'];
					$profit = $rr['daily_profit']; 
					$invest_amount = $investment;
				}
				$p_value = 64+$plan_id;
				$p_value = chr($p_value);
				$time = $systems_time;
				$start_date = date('Y-m-d', strtotime($systems_date . ' + 1 month'));
				$end_date = date('Y-m-d', strtotime($systems_date . " + $days month"));
				$time = $systems_time;//date("Y-m-d H:i:s");
				$sql = "select * from reg_fees_structure where user_id='$id'";
				$sq = query_execute_sqli($sql);
				$nsq = mysqli_num_rows($sq);
				if($nsq > 0){
					while($rt = query_execute_sqli($sq)){
						$pos = $rt['position'];
					}
				}
				else{
					$pos = direct_member_position(real_parent($id),$id);
				}					
				query_execute_sqli("insert into reg_fees_structure (user_id ,rcw_id,request_crowd,  update_fees , date ,start_date ,  profit , total_days , invest_type , plan , time,`count`,by_wallet,remarks,position) 
				values ('$id' , '$login_id','$pv', '$investment' , '$systems_date' , '$start_date', '$profit' , '$days' , '$plan_id', '$p_value' , '$systems_date_time','0','1','$remarks',$pos)");
				$insert_id = get_mysqli_insert_id();
				query_execute_sqli("update e_pin set used_id = '$id' , used_time = '$t' , used_date = '$date' , mode = 0 where id = '$epin_tbl_id' ");
					
				
									
				$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
				`date_time`) VALUES ('$id','$insert_id','Epin Top-Up By Epin $invest_by','0','$investment',(SELECT amount FROM wallet where id='$id'), '$systems_date_time')";
				query_execute_sqli($sqk);
				
				pair_point_calculation($id,$systems_date,false); // binary
				get_booster_income(real_parent($id),$systems_date);
				get_booster_income($id,$systems_date);
				echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$investment $currency_name TOPUP Has Been Completed !</B>";
				if(strtoupper($soft_chk) == "LIVE"){
					//Fund Transfer message
					$request_user_id = $id;
					include("email_letter/activation_company_wallet_msg.php");
					$to = get_user_email($request_user_id);
					include("function/full_message.php");
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,
					$title, $full_message);	
					
					$msg_topup = "Your ID is Topup successfully !! By https://www.unicgrow.com";
					$phone = get_user_phone($request_user_id);
					send_sms($phone,$msg_topup);
					
					
					$to_user = get_user_email($login_id);
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to_user, $title, $full_message);
					
					$req_id = get_user_name($request_user_id);
					$phone = get_user_phone($login_id);
					$msg_topup="$req_id is Topup successfully by you !! By https://www.unicgrow.com";
					send_sms($phone,$msg_topup);
					//End email message
				}
				
			}else { echo "<font color=\"#FF0000\">Error : Enter Corrcet E-pin for Investment !</font>"; }
		}else{echo "<font color=\"#FF0000\">Please Enter Correct Username</font>";}	
	}
}	
else
{
?>
	<form name="invest" method="post" action="">
	<table class="table table-bordered table-hover">
		<input type="hidden" name="plan_select_type" value="1" />
		<thead><tr><th colspan="2" class="align-left">Topup ID</th></tr></thead>
		<tr>
			<td>Username</th>
			<td>  
				<input type="text" name="user_id" value=""  id="sponsor_username" />
				<span id="user-result"></span>
			</td>
		</tr>
		<tr>
			<td>Investment E-pin</th>
			<td><input type="text" name="investment" value="<?=$inv_epin;?>"  /></td>
		</tr>
		<tr>
			<td>Trasaction Password</th>
			<td><input type="text" name="pin" /></td>
		</tr>
		<tr>
			<td colspan="2" class="span1 text-center">
				<input type="submit" name="submit" value="TOPUP" class="btn btn-primary" />
			</td>
		</tr>
	  </table>
	  </form>
<?php
}
function get_date_after_given_days($date,$days)     
{
	$i = 1;
	$given_date = $date;
	do
	{
		$temp_day = date('D', strtotime($given_date . ' +1 days'));
		if($temp_day == 'Sat' or $temp_day == 'Sun')
			$given_date = date('Y-m-d', strtotime($given_date . ' +1 days'));
		else
		{
			$given_date = date('Y-m-d', strtotime($given_date . ' +1 days'));
			$i++;
		}	
		
		
	}
	while($i <= $days);
	return $given_date;
}	

function get_downline($id,$inv_id)  // get all child in id network
{
	$result = query_execute_sqli("select get_chield_by_parent($id) as rr ");
	while($row = mysqli_fetch_array($result)){
		$child = $row[0];
	}
	if($child != "")$child = explode(",",$child);
	
	if(in_array($inv_id,$child))return 1;
	return 0;
}


?>