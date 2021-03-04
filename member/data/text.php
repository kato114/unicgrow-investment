<?php
include('../security_web_validation.php');
?>
<?php
session_start();
$login_id = $_SESSION['mlmproject_user_id'];
$sql = query_execute_sqli("SELECT * FROM users where id_user = '$login_id'");
while($ro = mysqli_fetch_array($sql))
{
	$username = $ro['username'];
	$f_name = $ro['f_name'];
	$l_name = $ro['l_name'];
	$phone_no = $ro['phone_no'];
	$email = $ro['email'];
}
$query = query_execute_sqli("select * from text ");
$num = mysqli_num_rows($query);
if($num > 0)
{
	while($row = mysqli_fetch_array($query))
	{
		$promotion_text = $row['promotion_text'];
		$db_msg = $promotion_text;
		include("function/full_message.php");
		//$full_message = str_replace("#","$",$full_message);
	?>
	<div class="col-md-12">
		<div class="panel-body">
			<textarea id="txtarea" onClick="this.focus();this.select()" style="height:200px;" class="form-control">
			<?=$full_message;?> Join via Referral Link <?=$refferal_link."/register.php?ref=".$_SESSION['mlmproject_user_name']?>
			</textarea>
		</div>
	</div>
	<?php
	}
}
else{
echo "<B style='color:#FF0000; font-size:16px;'>There are no information to show !!</B>"; }
?>				