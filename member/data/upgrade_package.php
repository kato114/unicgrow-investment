<?php
include('../security_web_validation.php');
die("Please contact to customer care.");
?>
<?php
ini_set("display_errors","off");
include("condition.php");
include("function/setting.php");
include("function/direct_income.php");
include("function/send_mail.php");
include("function/pair_point_calc.php");
include("function/all_child.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
$username = $_SESSION['mlmproject_user_username'];
$inv_epin = $_POST['invest_epin'];
$company_wallet = $main_wallet = get_user_allwallet($id,'amount');//companyw

?>
<style>
.li-style{}
@media (max-width:767px)
{
.li-style{}
}

</style>
<script>
$(document).ready(function() { 
	$("#secw_id").hide();
	$(".buy-now-comm").hide();
	<?php
	if(isset($_POST['epin_submit'])){ ?>
		$("#genpadi").hide();
		$("#genepin").show(); <?php
	}
	else{ ?>
		$("#genpadi").show();
		$("#genepin").hide(); <?php
	} ?>
	$("#genpadi").show();
	$("#genepin").hide();
	$('.top_from').change(function() {
		var top_from = $(this).val();
		if(top_from == 1)	{
			$("#genpadi").show();
			$("#genepin").hide();
		}
		if(top_from == 2){
			$("#genepin").show();
			$("#genpadi").hide();
		}
	});
	$('.top_to').change(function() {
		var top_from = $(this).val();
		if(top_from == 0 || top_from == 1 )	{
			$(".buy-now-self").show();
			$(".buy-now-comm").hide();
		}
		if(top_from == 10){
			$(".buy-now-comm").show();
			$(".buy-now-self").hide();
			
		}
	});
});
function plan_display(id,ug=false){
		var list = [<?php for($i = 0; $i < count($plan_name); $i++){
			echo "'",$plan_name[$i],"',";
		} ?>];
		var nn = list[id-1];
		$("#investment_plan").val(id);
		$("#investment_select").html(nn);
		$(".plan_show").hide();
		$("#secw_id").show();
		
}

</script>

<?php
if(isset($_POST['submit']))
{
	
	$investment_plan = $_REQUEST['investment_plan'];
	$investment = $_REQUEST['investment_amt'] = $set_amount[$investment_plan-1];
	$remarks = $_REQUEST['remarks'];
	$request_user_id = $login_id;
	$pass_num = 0;
	$user_pin = $_REQUEST['user_pin'];
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' ";
	$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; } 
	{
		if($request_user_id > 0)
		{
			if(1)//$pass_num > 0
			{
				
				$result[0] = $request_user_id;
				$rcw_id = 1;
				$_POST['curr_type'] = 2;
				if(in_array($request_user_id,$result)){
					$currency_name = "&#36;";
					$set_amount1 = min($set_amount);
					$deduct_amt = $investment;
					if($_POST['top-from'] == 1){
						if(in_array($investment,$set_amount)){
							$pre_amount = 0 ;
							$sql = "select * from reg_fees_structure where user_id='$login_id' and mode=1 order by id desc limit 1";
							$query = query_execute_sqli($sql);
							$cnt = mysqli_num_rows($query);
							if($cnt > 0){
								while($row = mysqli_fetch_array($query))
								{
									$pre_amount = $row['update_fees'];
								}
							}
							mysqli_free_result($query);
							 $deduct_amt = $deduct_amt - $pre_amount;
							
							if($company_wallet >= $deduct_amt){
								if(!isset($_SESSION['session_user_investmentacw'])){
									$_SESSION['session_user_investmentacw'] = 0;
									
									$sql = "update wallet set amount = amount - '$deduct_amt' where id='$login_id'";
									query_execute_sqli($sql);
									//$sql = "select * from plan_setting where amount <= '$deduct_amt' order by id desc limit 1";
									$sql = "select * from plan_setting where id = '$investment_plan' order by id desc limit 1";
									$query1 = query_execute_sqli($sql);	
									$plan_id = "";
									while($rr = mysqli_fetch_array($query1))
									{
										$plan_id = $rr['id'];
										$days = $rr['days'];
										$pv = $rr['pv'];
										$profit = $rr['daily_profit']; 
										$invest_amount = $investment;
										$update_fees = $rr['amount'];
									}
									mysqli_free_result($query1);
									$plan_select_type = $plan_id;
									$p_value = 64+$plan_id;
									$p_value = chr($p_value);
									$time = $systems_time;
									$sql = "select * from reg_fees_structure 
											where user_id='$request_user_id' order by id asc limit 1";
									$sq = query_execute_sqli($sql);
									while($rt = mysqli_fetch_array($sq)){
										$start_date = $rt['start_date'];
										$pre_count = $rt['count'];
									}
									mysqli_free_result($sq);
									$sql = "select * from reg_fees_structure where user_id='$request_user_id'";
									$sq = query_execute_sqli($sql);
									$nsq = mysqli_num_rows($sq);
									if($nsq > 0){
										while($rt = mysqli_fetch_array($sq)){
											$pos = $rt['position'];
										}
									}
									else{
										$pos = direct_member_position(real_parent($request_user_id),$request_user_id);
									}
									mysqli_free_result($sq);
									query_execute_sqli("update reg_fees_structure set mode=99 where user_id='$request_user_id' and mode=1 order by id desc limit 1");
									$insert_sql = "INSERT INTO reg_fees_structure (user_id , rcw_id,request_crowd, 
									update_fees , date ,start_date , profit , total_days , invest_type , plan , 
									time,`count`,by_wallet,remarks,position,level) 
									VALUES ('$request_user_id' , '$rcw_id' , '$pv' , '$update_fees' , '$systems_date'
									 , '$start_date', '$profit' , '$days' , '$plan_id', '$p_value' , 
									 '$systems_date_time','$pre_count','1','$remarks',$pos,1) ";
									query_execute_sqli($insert_sql);
									$insert_id = get_mysqli_insert_id();
									$sql = "update users set package='$pv' where id_user='$request_user_id'";
									query_execute_sqli($sql);
									$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
									`date_time`) VALUES ('$login_id','$insert_id','Debit Fund For TOPUP FROM Company Wallet','0','$deduct_amt',(SELECT amount FROM wallet where id='$login_id'), '$systems_date_time')";
									query_execute_sqli($sqk);
									insert_wallet_account($login_id , $request_user_id , $deduct_amt , $systems_date_time , $acount_type[24] ,$acount_type_desc[24], $mode=2 ,get_user_allwallet($login_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For TOPUP From Deposit Wallet");
									
									pair_point_calculation($request_user_id,$systems_date,false); // binary
									get_booster_income(real_parent($request_user_id),$systems_date);
									get_booster_income($request_user_id,$systems_date);
									echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$investment $currency_name TOPUP Has Been Completed !</B>";
									
									if(strtoupper($soft_chk) == "LIVE"){
										//Fund Transfer message
										include("email_letter/upgrade_msg.php");
										$to = get_user_email($request_user_id);
										//include("function/full_message.php");
										$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $db_msg);	
										
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
								}
								else{
									echo "<script type=\"text/javascript\">";
									echo "window.location = \"index.php?page=activation_company_wallet\"";
									echo "</script>";
								}
							}
							else{
								echo "<B class='text-danger'>Error : In-Sufficient Wallet Fund!!</B>";
							}
						}
						else{
							echo "<B class='text-danger'>Error : Please Enter Correct Amount Of Plan !!</B>"; 
						}	
					}
					if($_POST['top-from'] == 2){
						 
					}
				}
				else{ echo "<B class='text-danger'>Error : Requested Member Have Not In Your Network List!!</B>"; }	
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Trasaction Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}
		
}
	
else
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);
	$sql = "select * from reg_fees_structure where user_id='$login_id'";
	$tpd_num = mysqli_num_rows(query_execute_sqli($sql));
	$sql = "select * from plan_setting";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{ 
		$plan_id = $row['id'];
		$plan_name = $row['plan_name'];
		$amount = $row['amount'];
		$roi = $row['daily_profit'];
		$ref_per = $row['refferal_percent'];
		$bin_per = $row['binary_percent']; 
		$daily_upto = $row['days'];
		switch ($plan_id) {
			case 1: $btn = "primary"; break;
			case 2: $btn = "warning"; break;
			case 3: $btn = "default"; break;
			case 4: $btn = "danger"; break;
			case 5: $btn = "success"; break;
			case 6: $btn = "info"; break;
			case 7: $btn = "primary"; break;
		}
		?>
		<div class="col-md-4">
			<div class="panel panel-success plan_show">
				<!--<div class="panel-heading text-center">
					<div><i class="fa fa-road"></i><B><?=$plan_name?></B></div>
				</div>-->
				<div class="panel-body">
				<img src="images/plan/<?=$plan_id?>.jpg" width="100%" />
					<!--<ul class="list-unstyled text-left" style="font-weight:bold;">
						<li>
							<i class="fa fa-check"></i>  
							<span  class="text-danger">&#36;<?=$amount?></span>
						</li>
						<li>
							<i class="fa fa-check"></i> Binary 
							<span  class="text-danger"><?=$bin_per?>%</span>
						</li>
						<li>
							<i class="fa fa-check"></i> Monthly Return
							<span  class="text-danger"><?=$roi?> USD</span>
						</li>
						<li>
							<i class="fa fa-check"></i> Max ROI 
							<span  class="text-danger"><?=$daily_upto?> Month</span>
						</li>
					</ul>-->
				</div>
				<?php
				$pack_id = my_package($login_id)[3];
				if($pack_id == 0){
					$slect = "<button class='btn btn-$btn' name='plan_display' onclick=window.location.href='index.php?page=activation_company_wallet';>Make Investment</button>";
				}
				else{
					$slect = "<button class='btn btn-$btn' name='plan_display' onclick=plan_display($plan_id,'upgrade');>Upgrade Now</button>";
					if($pack_id >= $plan_id){
						$slect = "<button class='btn btn-$btn' name='plan_display' onclick='window.location.href=#'>BOOKED</button>";
					}
				}
				?>
				<div class="panel-heading text-center buy-now-self">
					<?=$slect?>
				</div>
			</div>
			
		</div>
		<?php
		$k++;
	} 
	mysqli_free_result($query);
	?>
		<form name="invest" id="acw" method="post" action="">
		<table class="table table-bordered table-hover" id="secw_id">
			<tr>     
				<th width="30%"> E-Wallet Balance</th>   
				<th><?=$company_wallet?> &#36;</th> 
			</tr>
			<tr>     
				<th width="30%">Top-Up From</th>   
				<th>
					<input type="radio" name="top-from" class="top_from" value="1" checked="checked" /> E-Wallet&nbsp;</th> 
			</tr>
			<tr id="genpadi">      
				<th>Select Package</th>  
				<td>
					<input type="hidden" name="investment_plan" id="investment_plan"  value=""/><span id="investment_select">N/A</span>
				</td>    
			</tr>
			<tr id="genepin">       
				<th>Epin</th>    
				<td> 
					<input type="text"  name="epin_chk" value="<?=$_POST['invest_epin'].$_POST['epin_chk']?>" class="form-control" /> 
				</td>      
			</tr>  
			<tr>       
				<th>Remarks</th>  
				<td><textarea type="text" name="remarks" class="form-control"></textarea></td>    
			</tr>     
			<!--<tr>      
				<th>Trasaction Password</th>  
				<td><input type="password" name="user_pin" class="form-control" /></td>    
			</tr> -->    
			<tr>     
				<td class="text-center" colspan="2">    
					<input type="submit" name="back" value="Back" class="btn btn-info" />    
					<input type="submit" name="submit" value="CONFIRM TOPUP" class="btn btn-info" />    
				</td>     
			</tr>     
		</table>
	</form>
	<form id="acw1"></form>
	<?php
	
}
?>



