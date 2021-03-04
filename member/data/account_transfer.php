<?php
include('../security_web_validation.php');
ini_set("display_errors","off");
include('condition.php');
include('function/setting.php');
include("function/send_mail.php");
include("data/api.php");
$login_id = $_SESSION['mlmproject_user_id'];


$toraid = get_tora_id($login_id);
if($toraid==0){
	$process = 0;
	if(isset($_POST['transfer_account'])){
		$apicall = new TransferShareAPI();
		$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$login_id' ");
		while($row = mysqli_fetch_array($query)){
			$username = $row['username'];
			$fname= $row['f_name'];
			$lname= $row['l_name'];
			$phone_no = $row['phone_no'];
			$email = $row['email'];
			$real_parent = $row['real_parent'];
			
		}
		mysqli_free_result($query);
		$share_wallet = get_user_allwallet($login_id,'share_holder');
		$req=array("userid"=>$login_id,
				   "username"=>$username,
				   "fname"=>$fname,
				   "lname"=>$lname,
				   "email"=>$email,
				   "amount"=>$share_wallet,
				   "phone_no"=>$phone_no,
				   "real_parent"=>$real_parent
		);
		$apicall->Setup_path($Tora_Share_Transfer_path);	
		$result_array=$apicall->RegisterAndShare('create_account',$req);
		$comeonid=$result_array['toraId'];
		if($result_array['error'] != 'ok'){
			print $result_array['error'];
		}
		elseif($comeonid!=0){
			$process = 1;
			$sql="update users set tora_ref_id= $comeonid where id_user=$login_id";
			query_execute_sqli($sql);
			$sql="update wallet set share_holder= 0 where id=$login_id";
			query_execute_sqli($sql);	
			insert_wallet_account($login_id , $login_id , $share_wallet , $systems_date_time , $acount_type[16] , $acount_type_desc[16], 2 , get_user_allwallet($login_id,'share_holder'),$wallet_type[3],$remarks = "share transfer to tora");	
			$tx_no = $result_array['tx_no'];
			create_trasaction($login_id,$share_wallet,$systems_date_time,$tx_no);
			echo "<B class='text-success'>Your Account Successfully Transfered To Tora Global !!</B>";
			
			if(strtoupper($soft_chk) == "LIVE"){
				//Tora Transfer message
				include("email_letter/account_transfer_tor.php");
				$to = get_user_email($login_id);
				//include("function/full_message.php");
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
				
				$msg_topup = "Your Account Successfully Transfered To Tora Global !!";
				$phone = get_user_phone($login_id);
				send_sms($phone,$msg_topup);
				//End email message
			}	
		}
		else{
		?><script type="text/javascript">window.location = "index.php?page=<?=$val?>";</script> <?php
		}
	}
	if($process == 0 and $toraid==0){
	?>
		<div class="panel panel-success">
			<div class="panel-heading">
				<div class="row">
					<div class="pull-left"><B>Transfer News :</B></div>
					<div class="col-lg-10 cls_news text-right">
						<marquee width="100%" scrollamount="3" onmouseover="this.stop();" onmouseout="this.start();">
						<form action="index.php?page=<?=$val?>" method="post">
							<input type="submit" name="transfer_account" value="Transfer Account & Redeem" class="btn btn-info" />
						</form>
						</marquee>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
}
elseif($_POST['redeem_account'] and $_POST['redeem_account'] == 'Redeem'){
	$share_wallet = get_user_allwallet($login_id,'share_holder');
	if($share_wallet > 0){
		$apicall = new TransferShareAPI();
		$apicall->Setup_path($Tora_Share_Transfer_path);
		$req=array("userid"=>$toraid,
				   "amount"=>$share_wallet
		);	
		$result_array = $apicall->RegisterAndShare('redeem_account',$req);
		if($result_array['error'] != 'ok'){
			print $result_array['error'];
		}
		elseif($result_array['redeem_confirmation'] > 0){
			$sql="update wallet set share_holder = 0 where id=$login_id";
			query_execute_sqli($sql);	
			insert_wallet_account($login_id , $login_id , $share_wallet , $systems_date_time , $acount_type[16] , $acount_type_desc[16], 2 , get_user_allwallet($login_id,'share_holder'),$wallet_type[3],$remarks = "share transfer to tora");	$tx_no = $result_array['tx_no'];
			create_trasaction($login_id,$share_wallet,$systems_date_time,$tx_no);
			echo "<B class='text-success'>Your Redeem Fund Successfully Transfered To Tora Global !!</B>";
			
			if(strtoupper($soft_chk) == "LIVE"){
				//Tora Transfer message
				include("email_letter/account_transfer_red.php");
				$to = get_user_email($login_id);
				//include("function/full_message.php");
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
				
				$msg_topup = "Your Redeem Fund Successfully Transfered To Tora Global !!";
				$phone = get_user_phone($login_id);
				send_sms($phone,$msg_topup);
				//End email message
			}		
		}
	}
	else{
		echo "<B class='text-danger'>In-Sufficent Redeem Fund !!</B>";
	}
}
else{ ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<div class="row">
			<div class="pull-left"><B>Transfer News :</B></div>
			<div class="col-lg-10 cls_news">
				<marquee width="100%" scrollamount="3">
					Your Account Have Been Transfered To Tora Global !
				</marquee>
			</div>
		</div>
	</div>
</div>
<?php
}
function create_trasaction($user_id,$amt,$systems_date_time,$tx_no){
	$sql = "INSERT INTO `transfer_trasaction` (`user_id`, `txno`, `amount`, `date`, `mode`) VALUES ('$user_id', '$tx_no', '$amt', '$systems_date_time', '1');";
	query_execute_sqli($sql);
}
$share_wallet = get_user_allwallet($login_id,'share_holder');
?>
<form action="index.php?page=<?=$val?>" method="post">
<table class="table table-bordered table-hover ">
	<thead><tr><th colspan="2"><B>Redeem Detail :</B></th></tr></thead>
	<tbody>
		<tr><th>Tora Share</th><td><?=$share_wallet?></td></tr>
	<?php
	if($share_wallet > 0 and $toraid > 0){ ?>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="redeem_account" value="Redeem" class="btn btn-primary">
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
</form>