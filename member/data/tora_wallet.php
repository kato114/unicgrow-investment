<?php
include('../security_web_validation.php');
session_start();
require_once("config.php");
include("condition.php");
include("data/api.php");
$login_id = $_SESSION['mlmproject_user_id'];
$toraid = get_tora_id($login_id);
$torashare=0;

if($toraid>0){
	/*$apicall = new TransferShareAPI();
	$req=array("toraid"=>$toraid);
	
	$apicall->Setup_path($Tora_Share_Transfer_path);	
	
	$result_array=$apicall->RegisterAndShare('getShare',$req);
	if($result_array['error']== 'ok'){
		 $torashare=$result_array['amt'];
	}*/
}


$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$login_id' ");
while($row = mysqli_fetch_array($query)){
	$amount = $row['amount'];
	$tora_share = $row['share_holder'];
}
$arr = array('tora_ref_id' => $toraid);
$json_data = json_encode($arr);
?>
<div class="col-md-1 col-md-offset-10">
	<form method="post" action="<?=$tora_login_path?>" target="_blank">
		<input type="hidden" name="tora_ref_id" value='<?=$json_data?>' />
		<input type="hidden" name="type" value="lgn_frm_conpn" />
		<input type="submit" name="login" value="Login With Toraglobal" class="btn btn-danger btn-sm" />
	</form>
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">
	<div class="alert alert-success"><B>Tora Share : <?=$tora_share+$torashare;?></B></div>
</div>