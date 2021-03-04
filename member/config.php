<?php 
error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
//date_default_timezone_set('America/Los_Angeles');
//$url_sms = "http://www.smsappgw.com/WS/CMP/wservicecmp.pc.php";
$refferal_link = "http://www.unicgrow.com/";
//$url_sms = "http://www.smsappgw.com/WS/CMP/wservicecmp.pc.php";
$refferal_link = "https://www.unicgrow.com/member";
//$uri = "http://192.168.1.101/server2/geniusworld/";

// Blue Bull Coin
//$coinpayments_merchant="a62a7e26be123d9dfb5641238d7f723b";
//$coinpayments_secret="fx123456";
//$coinpayments_email="unicgrow@gmail.com";

$Public_Key="846cbbf9608861a24ee8f4a8d95961040101c03ca2066598fb177b1d6e83fe7d";
$Private_Key="005503b22C99809918e62750e8C630A9Cdaf545e3497fe124f3c1811ed89677E";
//$coinpayments_ipn_url="http://www.bluebullcoin.com/office/ipn.php";


$Public_WKey="81d7b8928b44f8a78240e22a035936aa3232d0a112def4032f5833388cee1d42e8f8";
$Private_WKey="e8869Fa7560d209A705430366a04cA05A20b013232f1Acb2a9f7EbDb88805017390c";
// setting for bitcoin
$Tora_Share_Transfer_path = 'http://192.168.0.101/server2/toraglobal/business/api.php';
//$Tora_Share_Transfer_path = 'http://www.unicgrow.com/member/api.php';
$Gift_Card_Redeem_path = 'http://www.thegiftcardcentral.com/member/api.php';

$dir_path =  __DIR__;
$kyc_docs_path = $dir_path."/images/mlm_kyc/";	
$payment_receipt_img_full_path = $dir_path."/images/payment_receipt/" ;  
$profile_img_full_path = $dir_path."/images/"; 
$path = $dir_path; 
$save_excel_file_path = $dir_path."/admin/userinfo/";
$user_profile_folder = $dir_path."/images/mlm_profile_image/";
$legal_docs_folder = $dir_path."/images/legal/";
$user_screenshot_folder = $dir_path."/images/screenshot/";
$user_support_folder = $dir_path."/images/mlm_support/";
$gallery_folder = $dir_path."/images/mlm_gallery/";
$epin_receipt_folder = $dir_path."/images/mlm_epin_receipt/";

$tora_login_path = "http://www.unicgrow.com/member/login_check.php";
// Page Limit For Pagging
$page_limit = 100;

// investment Warning Message

$admin_email_title = "";
$admin_email_msg = "";
$admin_email = "";

$gecurrency_payee_user_id = "rbkmoney";
$ipn_security_key = "1377477201";


$db_host = "localhost";
$db_username = "root";
$db_password = "";
$db = "unicgrow";
//$con=mysql_connect($db_host,$db_username,$db_password);
//mysql_select_db($db,$con);

$con = new mysqli($db_host,$db_username,$db_password,$db);
if (!$con){ 
  die("Connection error: " . mysqli_connect_error());
}
$ip_Add = $_SERVER['REMOTE_ADDR']."(".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].")";
	$datess = date('Y-m-d');
	$u_id_f_ip = $_SESSION['AXCHAIN_user_id'];
	query_execute_sqli("insert into ips_address (user_id , ip_add , date) values ('$u_id_f_ip' , '$ip_Add' , '$datess') ");
	
$q_c_dd = query_execute_sqli("select * from system_date where id = 1 ");
while($row_q_c_d = mysqli_fetch_array($q_c_dd))
{
	$current_d = $row_q_c_d['sys_date'];
}
/*********free up memory variable ****/
$vr = array();					   /**/
$vri = 0;						   /**/
/********free up memory variable******/
function query_execute_sqli($sqli){
	global $con;
	global $vr;
	global $vri;
	$vr[$vri] = $srs = mysqli_query($con,$sqli);
	$vri++;
	return $srs;
}
function get_mysqli_insert_id(){
	global $con;
	return mysqli_insert_id($con);
}
function query_affected_rows(){
	global $con;
	return mysqli_affected_rows($con);
}
function free_object_memory(){
	global $vr;
	global $con;
	for($i = 0; $i < count($vr); $i++){
		mysqli_free_result($vr[$i]);
	}
	unset($vr);
	mysqli_close($con);
}

$systems_date = date('Y-m-d');	
$systems_date_time = date("Y-m-d H:i:s");	


//$systems_date = $current_d; // date('Y-m-d', strtotime(" + 7 hours 44 minutes"));	
//$systems_date_time = date("Y-m-d H:i:s",strtotime($current_d." ".date("H:i:s")));	

function data_logs($from,$title,$message,$type_data){
	$date = date('Y-m-d');
	$time = time();
	$ip_Add = $_SERVER['REMOTE_ADDR']."(".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].")";
	query_execute_sqli("insert into logs (title , message , user_id , date , time , type , ip_add) values ('$title' , '$message' , '$from' , '$date' , '$time' , '$type_data' , '$ip_Add') ");
}
function data_logs_tool($from,$title,$message,$type_data){
	$date = date('Y-m-d');
	$time = date('H:i:s');
	$ip_Add = $_SERVER['REMOTE_ADDR']."(".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"].")";
	query_execute_sqli("insert into logs_tool (title , message , user_id , date , time , type , ip_add) values ('$title' , '$message' , '$from' , '$date' , '$time' , '$type_data' , '$ip_Add') ");
}


// sms function


function send_sms($mobile,$message)
{
//Change your configurations here.
//---------------------------------
$username="test";
$api_password="a6777tvyub8jrgghk";
$sender="test";
$domain="speed.bulksmspark.in";
$priority="1";// 1-Normal,2-Priority,3-Marketing
$method="POST";
//---------------------------------

	$username=urlencode($username);
	$password=urlencode($api_password);
	$sender=urlencode($sender);
	$message=urlencode($message);
	
	$parameters="username=$username&api_password=$api_password&sender=$sender&to=$mobile&message=$message&priority=$priority";

	if($method=="POST")
	{
		$opts = array(
		  'http'=>array(
			'method'=>"$method",
			'content' => "$parameters",
			'header'=>"Accept-language: en\r\n" .
					  "Cookie: foo=bar\r\n"
		  )
		);

		$context = stream_context_create($opts);

		$fp = fopen("http://$domain/pushsms.php", "r", false, $context);
	}
	else
	{
		$fp = fopen("http://$domain/pushsms.php?$parameters", "r");
	}

	$response = stream_get_contents($fp);
	fpassthru($fp);
	fclose($fp);


//	if($response=="")
//	echo "Process Failed, Please check domain, username and password.";
//	else
//	echo "$response";
	


}



//message 
//message 



$recipient_acc = 'unicgrow@gmail.com';
$item_name = 'Registration';
$currency = 'BTC';
$usd_value_current = 1;



//Activity On panel by user and by Admin
function activity_on_panel($member_id, $request_data, $panel_id){
	//$panel_id  1 For User and 2 for Admin and 3 for Sub Admin
	$url = $_SERVER['REQUEST_URI'];
	$post_data = http_build_query($request_data, '', '&');
	
	$sql = "SELECT * FROM panel_work_history WHERE post_data = '$post_data' AND member_id = '$member_id' AND panel_id = '$panel_id' ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0){
		if(count($request_data) > 0){
			$sql = "INSERT INTO `panel_work_history`(`member_id`, `panel_id`, `url`, `post_data`, `date_time`, `ip_add`) 
			VALUES ('$member_id', '$panel_id', '$url', '$post_data', NOW(), '".$_SERVER['REMOTE_ADDR']."')";
			query_execute_sqli($sql);
		}
	}
}


//mail setting
$from_email = "noreply@unicgrow.com";
$SmtpServer="unicgrow.com";
$SmtpPort="465"; //default
$SmtpUser="support@unicgrow.com";
$SmtpPass="Unic@123"; // Password of this email
?>
