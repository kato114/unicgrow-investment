<?php
include('config.php');
include('function/transferapifunction.php');
include('function/functions.php');
include("function/send_mail.php");
include("function/best_position.php");
//print $toraid=register_user(1,"test","tesfirst","testlast","tst@mail.com",5,$systems_date_time);
if($_POST['key']==528556189 and $_POST['version']==1){
	if(isset($_POST['cmd']) and $_POST[cmd]=='get_account_info'){
		$userid=$_POST['userid'];
		$sql = "SELECT * FROM users WHERE id_user = '$userid' ";
		$query = query_execute_sqli($sql);
		$num=mysqli_num_rows($query);
		if($num>0){
			while($row = mysqli_fetch_array($query)){
				$account_info['username'] = $row['username'];
				$account_info['name'] = $row['f_name']." ".$row['l_name'];
			}
			print json_encode(array('error'=>'ok',"account_info"=>$account_info));
		}else{
			print json_encode(array("toraId"=>0,'error'=>"<B class='text-danger'>Error : Member Not Found<B/>"));
		}
	}
	else if(isset($_POST['cmd']) and $_POST[cmd]=='checkKyc'){
		$userid=$_POST['comeonid'];
		$sql = "SELECT * FROM users WHERE id_user = '$userid' ";
		$query = query_execute_sqli($sql);
		$num=mysqli_num_rows($query);
		if($num>0){
			while($row = mysqli_fetch_array($query)){
				$account_info['username'] = $row['username'];
				$account_info['name'] = $row['f_name']." ".$row['l_name'];
			}
			print json_encode(array('error'=>'ok',"account_info"=>$account_info));
		}else{
			print json_encode(array("toraId"=>0,'error'=>"<B class='text-danger'>Error : Member Not Found<B/>"));
		}
		
	}
}
?>