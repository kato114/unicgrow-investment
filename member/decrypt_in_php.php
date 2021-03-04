<?php
include('config.php');
$date = date('Y-m-d');
$key = pack("H*", "0123456789abcdef0123456789abcdef");
$iv =  pack("H*", "abcdef9876543210abcdef9876543210");

$encrypted = base64_decode($_POST["encrypted"]);
$shown = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);

$d=rtrim($shown,"\0");
$vcode = preg_replace("/[^a-zA-Z0-9]/", "", $d);
$userid=trim($_POST["user"]);

$chek=check_voucher($userid,$vcode);
if($chek){
	$mode=$chek['mode'];
	$exdate=$chek['exdate'];
	$price=$chek['price'];
	if($mode==3){
		echo "Used voucher";
	}else if($mode==2 and $exdate >= $date){
		echo "price=".$price;
	}else if($mode==2 and $exdate <= $date){
		echo "voucher expired";
	}
}else{
	echo "invalid voucher code";
}




function check_voucher($userid,$vcode){	
$sql = "select t1.mode,t1.expiredate,t1.price from voucher_purchase t1 
where t1.code = '$vcode' and t1.userid='$userid' LIMIT 1";
$q = query_execute_sqli($sql);	
	while($row = mysqli_fetch_array($q)){
		$rows['mode']=$row['mode'];
		$rows['exdate']=$row['expiredate'];
		$rows['price']=$row['price'];
    }
return $rows;

}

?>