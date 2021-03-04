<?php
//include("../config.php");
//include("setting.php");

function binary_payment_calculation($income_binary,$income_type)
{
	include("deducted_amount.php");
	for($i = 1; $i <= 15; $i++)
	{
		$previous_date =   date('Y-m-d', strtotime("-{$i} days"));
		$query = query_execute_sqli("select * from income where date = '$previous_date' and type = 2 ");
		$num = mysqli_num_rows($query);
		if($num == 0)
		{
			$income_date[] = $previous_date;
		}
	}
	$count = count($income_date);
	for($i = $count; $i >= 0; $i--)
	{
		$q = query_execute_sqli("select * from left_right where date = '$income_date[$i]' ");
		$number = mysqli_num_rows($q);
		if($number > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$incomed_id = 0;
				$user_id = $row['user_id'];
				$left_child = $row['left_child'];
				$right_child = $row['right_child'];
				$check = check_income_condition($user_id);
				if($check == 1)
				{
					$pair = min($left_child, $right_child);
					$amount = get_pair_amount($pair,$income_binary); 
					if($amount != 0)
					{
						$deducted_amount = deducted_amount($user_id,$amount);
						query_execute_sqli("insert into income (user_id , amount , date , type , incomed_id) values ('$user_id' , '$deducted_amount' , '$income_date[$i]' , '$income_type[2]' , '$incomed_id') ");
				
					
						insert_into_wallet($user_id,$deducted_amount,$income_type[2]);	
					}
					/*$binary_username = get_user_name($user_id);
					$binary_amount = $deducted_amount;
					$user_phone = get_user_phone($user_id);
					include("sms_message.php");
					send_sms($url_sms,$income_binary,$user_phone);  //send sms */
				}	
			}
		}
	}	

}

function get_pair_amount($pair,$income)
{
	for($i = 0; $i<5; $i++)
	{
		if($income[$i][0] == $pair)
		{
			$amount = $income[$i][1];
		}
	}
	if($income[4][0] < $pair)
	{
		$amount = $income[4][1];
	}
	return $amount;	
}

//binary_payment_calculation($binary_income,$income_type);