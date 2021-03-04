<?php
session_start();
ini_set('display_errors','on');
include('config.php');

$username = mysqli_real_escape_string($con,$_REQUEST['username']);
$password = mysqli_real_escape_string($con,$_REQUEST['password']);
//$password = set_get_password($password,1);

$sql = "SELECT * FROM users WHERE username='".$username."' && password='".$password."' ";
//$sql = "select t1.*,t2.type as manager_type from users t1 left join user_manager as t2 on t1.id_user = t2.manager_id where t1.username='$username' && t1.password='$password' ";

$sql1 = query_execute_sqli($sql);
$f_p = $_POST['forgetPassword'];
$count = mysqli_num_rows($sql1);
if($count > 0)
{	
	while($row = mysqli_fetch_array($sql1))
	{
		$_SESSION['user_manager_type'] = $row['manager_type'];
		
		$ip_id = $_SESSION['mlmproject_user_id'] = $row['id_user'];
		$cnt = 1;
		/*$dw_li[0] = $ip_id;
		for($i = 0; $i < $cnt; $i++){
			$id = $dw_li[$i];
			$sql = "select id_user from users where parent_id='$id'";
			$qu = query_execute_sqli($sql);
			$numq = mysqli_num_rows($qu);
			if($numq > 0){
				while($rt = mysqli_fetch_array($qu))
				{
					$dw_li[$cnt] = $rt['id_user'];
					$cnt++;
				}
			}
		}
		unset($dw_li[0]);
		$dw_li = array_values($dw_li);
		$_SESSION['mlmproject_user_network'] = $dw_li;*/
		$_SESSION['mlmproject_user_type'] = $row['type'];
		$_SESSION['mlmproject_user_username'] = $row['username'];
		$_SESSION['mlmproject_user_refrral'] = $row['refrral_link'];
		$_SESSION['mlmproject_user_freeze_mode'] = $row['freeze'];
		$_SESSION['mlmproject_user_protect_mode'] = $row['protected'];
		$_SESSION['mlmproject_user_mode'] = $row['mode'];
		$_SESSION['mlmproject_user_status'] = $row['status'];
		$_SESSION['mlmproject_user_step'] = $row['step'];
		$_SESSION['mlmproject_user_bitcoin'] = $row['ac_no'];
		$_SESSION['mlmproject_user_lastaccess'] = $row['binary_date'];
		$_SESSION['mlmproject_user_bitcoin_system'] = $row['branch'];
		$_SESSION['mlmproject_user_full_name'] = ucwords($row['f_name']." ".$row['l_name']);
	}
	$ip_Add = $_SERVER['REMOTE_ADDR'];//."(".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].")";
	$datess = date('Y-m-d');
	query_execute_sqli("insert into ips_address (user_id , ip_add , date) values ('$ip_id' , '$ip_Add' , '$datess') ");
	
	query_execute_sqli("UPDATE `users` SET `login_ip` = '$ip_Add' WHERE `id_user` = '$ip_id';");
	
	query_execute_sqli("UPDATE `users` SET `binary_date` = '$systems_date_time' WHERE `id_user` = '$ip_id';");
	
	$_SESSION['mlmproject_user_name']=$_POST['username'];
	$_SESSION['mlmproject_user_position']=$_POST['position'];
	$_SESSION['mlmproject_user_email']=$_POST['email'];
	
	$client_ip_add = $_SERVER['REMOTE_ADDR'];
	$q = query_execute_sqli("select * from block_ip_address where block_ip_address = '$client_ip_add' ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		$_SESSION['royalforexgroup_client_ip_blocked'] = 1; 
	}
	else { $_SESSION['royalforexgroup_client_ip_blocked'] = 0;  }
	
	$_SESSION['mlmproject_user_login']=1;
	if($_SESSION['mlmproject_user_step'] == 0 and $_SESSION['mlmproject_user_id'] > 1){ ?>
		<script type="text/javascript">window.location = "index.php";</script> <?PHP
	} ?>
	<script type="text/javascript">window.location = "index.php";</script> <?PHP
}
else
{
	$_SESSION['pass_incorr'] = "<b class='text-danger'>UserName or Password is Incorrect !</b>"; ?>
	<script type="text/javascript">window.location = "login.php?err=1";</script> <?PHP
}
mysqli_close($con);
?>