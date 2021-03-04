<?php
include('../security_web_validation.php');
?>
<?php
//ini_set("display_errors",'on');
include("condition.php");
include("function/setting.php");
include("function/e_pin.php");

include("function/send_mail.php");

$id = $new_user_id = $_SESSION['mlmproject_user_id'];
?>
<script type='text/javascript' src='js/new_jquery.js'></script>
<script type="text/javascript">

    $(document).ready(function(){
		$(".box").hide();
		$(".cash ").show();
        /*$('input[type="radio"]').click(function(){
            if($(this).attr("value")=="topup-epin"){

                $(".box").hide();

                $(".cash").show();

            }

            if($(this).attr("value")=="reg-epin"){

                $(".box").hide();

                $(".epin").show();

            }
			
			if($(this).attr("value")=="first_plan"){

                $(".box").hide();

                $(".next").show();
            }
        });*/

    });

</script>
<style type="text/css">

    .box{ display: none; }

    .cash{ background: none; }

    .epin{ background: none; }
	
	.button3{ display:inline;}

</style>
<?php

if(isset($_POST['submit']))
{
	if($_SESSION['generate_pin_for_user'] == 1)
	{
		$field_name = $_REQUEST['field_name'];
		$epin_type = $_REQUEST['epin_type'];
		$epin_number = $_REQUEST['epin_number'];
		$user_pin = $_REQUEST['user_pin'];
		/*$amount_pin = $_REQUEST['amount_pin'];*/
		$p_value = $_POST['plan_value'];
		$gen_plan = $_REQUEST['gen_plan'];
		$sql = "select * from users where id_user='$id' and password='$user_pin'";
		$user_pin_num = mysqli_num_rows(query_execute_sqli($sql));
		if($user_pin_num > 0){
			if($epin_type == 0){
			$amount_remain = 0;
			$amount_pin = 1;
			}	
			else	
				$amount_remain = 0;//$amount_pin%1000;
			
			if($amount_remain == 0)
			{
			
				if($epin_number == 0)
				{
					print "<div style=\"color:#FF0000;font-size:14pt\" align=center>Please Input Valid Number</div>";
				}
				else
				{
					$qu = query_execute_sqli("select * from plan_setting where id = '$epin_type' ");
					while($rrr = mysqli_fetch_array($qu))
					{ 
						$amount = $rrr['amount'];
					}
					
					$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$id' ");
					while($row = mysqli_fetch_array($query))
						$wallet_amount = $row['amount'];
					
					if($epin_type == 0 and $_SESSION['reg_pin_no'] == $field_name)
					{
						if($gen_plan == 'plan-5000'){
							$amount = 5000;
						}	
						else{	
							$amount = $fee_of_registration;
						}	
							
						$total_epin_amount = $amount*$epin_number;
						$tax_epin = ($total_epin_amount*$pin_gen_tax)/100;
						$avail_epin_amount = $tax_epin+$total_epin_amount;
					}	
					elseif($epin_type > 0 and $_SESSION['topup_pin_no'] == $field_name)
					{	
						$total_epin_amount = $amount*$epin_number;
						$tax_epin = ($total_epin_amount*$pin_gen_tax)/100;
						$avail_epin_amount = $tax_epin+$total_epin_amount;
					}	
						
					if($wallet_amount >= $avail_epin_amount)
					{
						if($epin_type == 0 and $_SESSION['reg_pin_no'] == $field_name)
						{	
							$amount_mode = 1;
							$mode = 2;
							$p_type = "Registration";
						}
						elseif($epin_type > 0 and $_SESSION['topup_pin_no'] == $field_name)
						{
							$mode = 1;
							$p_type = "Top-up";
							$amount_mode = 1;
						}	
						
						if($amount_mode == 1)
						{
							if($gen_plan == 'plan-5000')
							{
								$epin_type = 1;
								$p_value = 'B';
								$mode = 1;
							}
							
							for($ii = 0; $ii < $epin_number; $ii++)
							{
								do
								{
									$unique_epin = substr(md5(rand(0, 1000000)), 0, 10);
									$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin' ");
									$num = mysqli_num_rows($query);
								}while($num > 0);
								
								$date = $systems_date;
								$t = date('h:i:s');
								query_execute_sqli("insert into e_pin (epin , epin_type , user_id , amount , mode , time , date, plan) values ('$unique_epin' , '$epin_type' , '$new_user_id' ,'$amount' , '$mode' , '$t' , '$date', '$p_value')");
								
								$qus = "select * from e_pin where epin = '$unique_epin' ";
								$query_epin = query_execute_sqli($qus);
								while($rok = mysqli_fetch_array($query_epin))
								{
									$epin_new_id = $rok['id'];
								}
								query_execute_sqli("insert into epin_history (epin_id, generate_id , user_id , transfer_to, date) values ('$epin_new_id' , '$new_user_id' , '$new_user_id' , '$new_user_id' , '$date')");
								
							}	
								query_execute_sqli("update wallet set amount = amount-'$avail_epin_amount' where id = '$new_user_id' ");
								query_execute_sqli("insert into account (user_id , dr , date , account,type,wall_type, 	wallet_balance) values ('$new_user_id' , '$avail_epin_amount' , '$systems_date_time' , 'E-pin Generate','5',1,".get_user_allwallet($new_user_id,'amount').")");
								if($soft_chk == "LIVE"){
									$message = "$epin_number E-pin of $p_type Generate Successfully. www.canindia.co.in";
									$phone = get_user_phone($id);
									send_sms($phone,$message);
								}
								$date = date('Y-m-d');
								$username_log = get_user_name($new_user_id);
								$epin_log = $amount;
								include("function/logs_messages.php");
								data_logs($id,$data_log[9][0],$data_log[9][1],$log_type[9]);
								$_SESSION['done_epin'] = 1;
								$_SESSION['epin_success'] = "<B style=\"color:#018D0B; font-size:12pt;\">E-Pin Generate Successfully !</B>";
								
								$_SESSION['generate_pin_for_user'] = 0;	
								echo "<script type=\"text/javascript\">";
								echo "window.location = \"index.php?page=generate_epin\"";
								echo "</script>";	
						}
						else
						{
							print "<B style=\"color:#FF0000; font-size:12pt;\">Please Select Correct Plan</B>";
						}			
					}
					else 
					{ 
						print "<B style=\"color:#FF0000; font-size:12pt;\">Error : You have no Sufficient Balance in your Wallet !</B>"; 
					}
						
				}
			}
			else
			{
				print "<B style=\"color:#FF0000; font-size:12pt;\">Please Enter multiple Of Thousand</B>";
			}
		}
		else{
			 echo "<B class='text-danger'>Please Enter Correct Trasaction Password!</B>"; 
		}	
	}
}
else
{ 
  $_SESSION['generate_pin_for_user'] = 1;
  $day = date("D", strtotime($systems_date));
  
	$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$id' ");
	while($row = mysqli_fetch_array($query)){
		 $wallet_amount = $row['amount'];
		 $company_amount = $row['roi'];
	}	

	if($_SESSION['done_epin'] == 1 and $_SESSION['generate_pin_for_user'] == 1)
	{
		print $_SESSION['epin_success'];
		$_SESSION['generate_pin_for_user'] = 0;
	}	
?>

<table class="table table-bordered table-hover">
	<thead><tr><th colspan="2" class="align-left">Generate E-pin</th></tr></thead>
	<!--<tr>
		<td colspan="2">
			<input type="radio" id="inv_mode" name="investmentmode"  value="reg-epin" checked="checked"  />  
			Registration E-pin  
			<input type="radio" id="inv_mode" name="investmentmode"  value="topup-epin" /> Top-up E-pin
			
			<input type="radio" id="inv_mode" name="investmentmode"  value="first_plan" /> 
			5000 E-pin
		</td>
	</tr>-->
	<tr>
		<td>Wallet Balance </td>
		<td width="50%"><?=$wallet_amount;?> &#36; </td>
  	</tr>
	<tr>
		<td class="epin box" colspan="2">
		  <form method="post" action="">
			<table class="table table-borderless">
				<tr>
					<td width="390">No of E-pin</td>
					<td><input type="text" name="epin_number" required /></td>
				</tr>
				<tr>
					<td>E-pin Type </td>
					<td>
						<select name="epin_type" style="width:210px;">
							<option value="0">Registration Epin</option>		
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">
						<?php $_SESSION['reg_pin_no'] = substr(md5(rand(0, 1000000)), 0, 5);?>
						<input type="hidden" name="gen_plan" value="0" />
						<input type="hidden" name="field_name" value="<?=$_SESSION['reg_pin_no'];?>"/>
						<input type="submit" name="submit" value="Generate" class="btn btn-primary" />
					</td>
				</tr>
			</table>
		  </form>
		</td>
		<td class="cash box" colspan="2">
		 <?php 
		 	if(/*$day == 'Sun' or $day == 'Sat'*/1)
			{?>
		  <form method="post" action="">	
			<table class="table table-borderless">
				<tr>
					<td>E-pin Type </td>
					<td>
						<select name="epin_type" style="width:210px;" class="form-control">
							<?php
								$qu = query_execute_sqli("select * from plan_setting ");
								while($rrr = mysqli_fetch_array($qu))
								{ 
								$plan_name = $rrr['plan_name'];
								$plan_id = $rrr['id'];
								$amount = $rrr['amount'];
								?>
								
								<option value="<?php print $plan_id; ?>"><?php print $plan_name.' ('.$amount.')'; ?></option>
								<?php	}	
								?>		
						</select>
					</td>
				</tr>
				<tr>
					<td width="390">No of E-pin</td>
					<td><input type="text" name="epin_number" class="form-control" required /></td>
				</tr>
				<tr>      
				<td>Trasaction Password</td>  
					<td><input type="password" name="user_pin" class="form-control" /></td>    
				</tr>
				 <!-- <tr>
					<td>Plan</td>
					<td><select name="plan_value" style="width:210px;" required>
							<option value="">Select Plan</option>
							<option value="A">Plan A</option>
							<option value="B">Plan B</option>
						</select>
					</td>
				  </tr> -->
				 
				<tr>
					<td colspan="2" class="text-center">
						<?php $_SESSION['topup_pin_no'] = substr(md5(rand(0, 1000000)), 0, 5);?>
						<input type="hidden" name="field_name" value="<?=$_SESSION['topup_pin_no'];?>"/>
						<input type="submit" name="submit" value="Generate" class="btn btn-primary" />
					</td>
				</tr>
			</table>
		  </form>	
	  <?php }
			else
			{
				echo "<B style=\"color:#FF0000; font-size:12pt;\">Top-up E-Pin Not Generate Today !</B>"; 
			}
		?>
		</td>
		<td class="next box" colspan="2">
		  <form method="post" action="">
			<table class="table table-borderless">
				<tr>
					<td width="390">No of E-pin</td>
					<td><input type="text" name="epin_number" required /></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">
						<input type="hidden" name="gen_plan" value="plan-5000" />
						<input type="hidden" name="field_name" value="<?=$_SESSION['reg_pin_no'];?>"/>
						<input type="submit" name="submit" value="Generate" class="btn btn-primary" />
					</td>
				</tr>
			</table>
		  </form>
		</td>
	</tr>
</table>
 
<?php 
	$_SESSION['done_epin'] = 2;
	
} ?>
<style>
table{
	font: 15px arial;
}

</style>