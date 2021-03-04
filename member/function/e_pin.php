<?php
//include("../../config.php");
function get_epin($num)
{
	for($i = 0; $i < $num; $i++)
	{
		$unique[$i] = mt_rand(1000000000, 9999999999);
	}
	return $unique;	
}
function generate_epin($id,$new_user,$product_id,$to,$num,$data_log,$log_type,$from,$SmtpServer,$SmtpPort,$SmtpUser,$SmtpPass,$epin_generate_message,$fees) //$to = email id , num = no of unique id
{
	//include("function/send_mail.php");
	$unique_epin = get_epin($num);
	$c = count($unique_epin);
	for($i = 0; $i < $c; $i++)
	{
		$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin[$i]' ");
		$num = mysqli_num_rows($query);
		if($num == 0)
		{
			$mode = 0;
			$date = date('Y-m-d');
			$t = time();
			query_execute_sqli("insert into e_pin (epin, user_id , product_id , mode , time , date , used_id) values ('$unique_epin[$i]' , '$id' ,'$product_id' , '$mode' , '$t' , '$date' , '$new_user')");
			
			$position = get_user_position($id);
			data_logs($id,$position,$data_log[8][0],$data_log[8][1],$log_type[7]);
			
			$epin_generate_username = get_user_name($id);
			$epin_amount = $fees;
			$payee_epin_username = get_user_name($new_user);
			$epin = $unique_epin[0];
			$title = "E pin mail";
			$db_msg = $epin_generate_message;
			include("full_message.php");
			//print $full_message;
			/*$cnt = count($to);
			for($i = 0; $i < $cnt; $i++)
			{*/
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
			$SMTPChat = $SMTPMail->SendMail();
			
			$req_username = epin_generate_username;
			$epin_phone = get_user_phone($id);
			include("sms_message.php");
			send_sms($url_sms,$epin_generate,$epin_phone);  //send sms of
			
		}
		else{
				$num = $c-$temp;
				get_epin($num);
			}
		$temp++;
	}
	return $unique_epin;	
}	