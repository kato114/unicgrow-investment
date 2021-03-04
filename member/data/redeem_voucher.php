<?php
include('../security_web_validation.php');
session_start();
include("condition.php");
include("function/setting.php");
include("voucherapi.php");

$voucherapi = new VoucherRedeemAPI;

$login_id = $_SESSION['mlmproject_user_id'];

if(isset($_SESSION['succ_msg'])){
	echo $_SESSION['succ_msg'];
	unset($_SESSION['succ_msg']);
}

if(isset($_POST['Submit'])){ 
	if(isset($_POST["vcode"]) && $_POST["vcode"] != ""){
	
		if(isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]){
			$vouchercode = $_POST['vcode'];
			
			$cmd="redeem";
			$req = array(
			'vcode' => $vouchercode,
			'userid' => $login_id
	    	);
			$voucherapi->Setup_path($Gift_Card_Redeem_path);
		    $result=$voucherapi->Redeem($cmd,$req);
		 	$msg = $result['Success'];
			$response = $result['result'];
			if($result['error'] == 'ok'){
				if($response == 1){
					$price = $result['price'];
					
				query_execute_sqli("UPDATE wallet SET amount = amount + '$price' WHERE id = '$login_id' ");
				insert_wallet_account($login_id , $login_id , $price , $systems_date , $acount_type[35] , $acount_type_desc[35], 1 , get_user_allwallet($login_id,'amount'),$wallet_type[1],$remarks = "voucher redeem ");
				$swl="INSERT INTO `voucher_redeem` (`code`, `price`, `userid`, `date`) VALUES ('$vouchercode', '$price', '$login_id', '$systems_date_time')";
				query_execute_sqli($swl);	
					
				}
				$_SESSION['succ_msg'] = $msg;
				echo "<script>window.location = 'index.php?page=redeem_voucher'</script>";
			}
			else{
				print $result['error'];
			}
			
		}else{ echo "<B class='text-danger'>Please Enter correct Code !!</B>"; }
	}else{ echo "<B class='text-danger'>Please Enter Voucher Code !!</B>"; }
}
?>

<form name="create_catg" action="" method="post" enctype="multipart/form-data">
	<table class="table table-bordered table-hover">	
		<tr>
			<th>Voucher Code</th>
			<td><input type="text" name="vcode" class="form-control" /></td>
		</tr>
		<tr>
			<th>Security Code</th>
			<td>
				<div style="float:left;"><input type="password" name="captcha" class="form-control" /> </div>
				<div style="float:left; margin:0.5% 50% 0 0;">&nbsp;<img src="captcha.php" /></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="Submit" class="btn btn-info" value="Redeem" />
			</td>
		</tr>
	</table>
	</form>