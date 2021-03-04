<?php
include('../security_web_validation.php');

if(isset($_POST['user_id'])){
	unset($_SESSION['user_id']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
}
$user_id = $_SESSION['user_id'];

$sql = "SELECT t2.* FROM users t1 
LEFT JOIN users t2 ON t1.real_parent = t2.id_user
WHERE t1.id_user = '$user_id'";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$username = $row['username'];
	$name = ucwords($row['f_name']." ".$row['f_name']);
	$phone_no = $row['phone_no'];
	$email = $row['email'];
	$fb_id = $row['fb_id'];
	$whatsapp = $row['whatsapp'];
	$skype_id = $row['skype_id'];
}

?>
<div class="col-md-12">
	<a class="btn btn-danger" href="index.php?page=binarytree"><i class="fa fa-reply"></i> Back</a>
</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Sponsor User ID : </B></div><div class="col-md-9"><B><?=$username?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Sponsor Name : </B></div><div class="col-md-9"><B><?=$name?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Sponsor Phone : </B></div><div class="col-md-9"><B><?=$phone_no?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Sponsor E-mail : </B></div><div class="col-md-9"><B><?=$email?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Whatsapp Number : </B></div><div class="col-md-9"><B><?=$whatsapp?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Facebook ID : </B></div><div class="col-md-9"><B><?=$fb_id?></B></div>
<div class="col-md-12">&nbsp;</div>
<div class="col-md-3"><B>Skype ID : </B></div><div class="col-md-9"><B><?=$skype_id?></B></div>
