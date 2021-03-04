<?php
/*ini_set("display_errors",'on');
include("../config.php");
include("../function/functions.php");
 print_r(get_pair_point_income($id=33,$left_point=1850.00,$right_point=750.00,$date='2019-11-16'));*/
 
 
function pair_point_income($date)
{
	include("setting.php");
	$done = 0;
	
	$income_date_dist = date("Y-m-d", strtotime($date."-1 day") );
	$sys_date = $income_dates = $date; //date("Y-m-d");
	$week_pn = get_pre_nxt_date($income_date_dist , $binary_froward_day);
	$previous_date = $week_pn[0];
	$next_date = $week_pn[1];
	 $sql = "select * from pair_point where date/* = '$income_date_dist'*/ between '$previous_date' and '$next_date' and pair_mode=1 and least(`left_point`,`right_point`) > 0";
	$chk_query = query_execute_sqli($sql);
	$chk_num = mysqli_num_rows($chk_query);
	if($chk_num > 0)
	{	
		//$next_date = date('Y-m-d', strtotime("next $binary_pay_day", strtotime($income_date_dist)));
		//$next_date = date('Y-m-d', strtotime($next_date."-1 day"));
		//$previous_date = date('Y-m-d', strtotime("previous $binary_pay_day", strtotime($next_date)));
	  	 $sql = "select t1.* ,least(left_point,right_point)as point,t2.date from pair_point as t1
				inner join reg_fees_structure as t2 on t1.user_id = t2.user_id
				left join users t3 on t1.user_id = t3.id_user
				where t1.date /*= '$income_date_dist'*/ between '$previous_date' and '$next_date' and t1.pair_mode=1 
				and least(t1.`left_point`,t1.`right_point`) >= $min_matching_business and ((t3.l_lps > 1 and t3.r_lps >= 1) or (t3.l_lps >= 1 and t3.r_lps > 1))
				and t2.date <= '$income_date_dist' /*and t3.binary_qdate <= '$income_date_dist'*/ 
				group by t1.user_id 
				having point > 0";//between '$previous_date' and '$next_date' //and t2.plan not in('z')and t2.boost_id=0
				
		$q = query_execute_sqli($sql);
		$n = mysqli_num_rows($q);
		if($n > 0)
		{ 
			$k = 0;
			while($rrr = mysqli_fetch_array($q))
			{
				$id = $rrr['user_id'];
				$type = get_type_user($id);
				if($type == 'B')
				{ 
					$binary_check = check_user_for_binary_income($id);
					if($binary_check == 1)
					{
						$tot_l_child = 1;
						$tot_r_child = 1;
						if($tot_l_child >= 1 and $tot_r_child >= 1 )
						{
							$income_date = date("Y-m-d", strtotime($income_dates."-1 day") );
							$sql = "select t1.*,t2.l_lps,t2.r_lps,t2.package from pair_point t1
									left join users t2 on t1.user_id = t2.id_user
									where t1.user_id = '$id' and t1.date /*= '$income_date_dist'*/ between '$previous_date' and '$next_date'  and t1.pair_mode=1 /*and t2.binary_qdate <= '$income_date_dist'*/ and least(t1.`left_point`,t1.`right_point`) >= $min_matching_business group by t1.user_id  ";//between '$previous_date' and '$next_date'
							$query = query_execute_sqli($sql);
							$num = mysqli_num_rows($query);
							if($num != 0)
							{
								while($row = mysqli_fetch_array($query))
								{
									//$date = date("Y-m-d");
									$pair_id = $row['id'];
									$left_point = $row['left_point'];
									$right_point = $row['right_point'];
									$ul_lps = $row['l_lps'];
									$ur_lps = $row['r_lps'];
									$step_topup = $row['package'];
									$binary_qdate = $row['binary_qdate'];
									$max_pair = min($left_point,$right_point);
									
									if((($ul_lps > 1 and $ur_lps >= 1) or ($ul_lps >= 1 and $ur_lps > 1)) and $step_topup == 2 and $max_pair >= $min_matching_business){
										$incomes = get_pair_point_income($id,$left_point,$right_point,$income_date_dist,$previous_date,$next_date);
										$inc_per = $incomes[1];
										//query_execute_sqli("update pair_point set pair_mode=0,inc_per= $inc_per where id ='$pair_id' ");
										$income = $incomes[0]; 
										if($income > 0)
										{
											
											query_execute_sqli("update pair_point set pair_mode=0 where date /*= '$income_date_dist'*/ between '$previous_date' and '$next_date' and  least(`left_point`,`right_point`) > 0 and user_id='$id' and pair_mode=1 ");
											$done = 1;
											$sql = "update users set binary_date='$date' 
													where id_user='$id' and binary_date is NULL";
											query_execute_sqli($sql);
											
											$incomes = $income;
											
											$sql = "insert into income 
													(user_id , amount , tax , tds_tax , date , type,mode,token_rate ) 
													values ('$id' , '$incomes' , '$tax_amount1' , '$tax_amount2' ,
													 '$date' , '$income_type[3]',1,'$token_rate')";
											query_execute_sqli($sql);
											$m_wallet = $incomes;
											query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$id' ");
											insert_wallet_account($id , $id , $m_wallet , $date , $acount_type[2] ,$acount_type_desc[2], $mode=1 , get_user_allwallet($id,'amount'),$wallet_type[1],$remarks = "Binary Income");
											
											
											//set_level_binary_bonus($id,$incomes,$date);
											$k++;
											if($soft_chk == "DEL"):
											$to = get_user_email($user_id);
											$full_message = "Today Binary Receive successfully Thanks !! By https://www.unicgrow.com";
											$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $full_message);	
											$msg_roi = "Today Binary Receive successfully Thanks !! By https://www.unicgrow.com";
											$phone = get_user_phone($user_id);
											send_sms($phone,$msg_roi);
											endif;
										}
										
									}
								}	
							}
						}	
					}	
				}		
			}
		}
		if($done == 1){
			print "<font size=5 color=\"#004080\">Binary Income Successfully Distributed on $date! </font><br>";
			//query_execute_sqli("update pair_point set pair_mode=0 where date ='$income_date_dist' ");
			print "<font size=5 color=\"#004080\">Binary Income Successfully Distributed To $k Member! </font><br>";
		}
		else { 	
			print "<font size=5 color=\"#FF0000\">Alert - There Are No Binary Pair today ! </font><br>";
		}	
	}
	else { 
	print "<font size=5 color=\"#FF0000\">Alert - Binary Income Already Distributed on $date ! </font><br>"; 	}
}

//pair_point_income();

function get_pair_point_income($id,$left_point,$right_point,$date,$previous_date,$next_date)
{
	include("setting.php");
	$income = 0;
	
	$pc = 1;
	$pair_calc = $max_pair = min($left_point,$right_point);
	/*do
	{
		$pair_calc = $per_day_multiple_pair*$pc;
		$pc++;
	}
	while($pair_calc <= $max_pair);*/
	
	$total_pair = $pair_calc;//-$per_day_multiple_pair;
	
	$sql = "select sum(rfs.update_fees) update_fees,t2.capping
			from reg_fees_structure as rfs 
			left join users t2 on rfs.user_id = t2.id_user
			where rfs.user_id='$id' and rfs.mode in (1)
			order by rfs.invest_type desc limit 1";
	$result = query_execute_sqli($sql);
	$rows = mysqli_fetch_array($result);
	
	$tot_inv = $rows['update_fees'];
	$sql = "select * from plan_setting where amount <= $tot_inv  order by id desc limit 1";
	$query1 = query_execute_sqli($sql);	
	$plan_id = "";
	while($rr = mysqli_fetch_array($query1))
	{
		$plan_id = $rr['id'];
		
	}
	mysqli_free_result($query1);
	$chk_capping = $rows['capping'];
	mysqli_free_result($result);
	
	
	if($chk_capping == '')$chk_capping = NULL;
	$caping = $tot_inv;
	
	if($chk_capping != NULL)
		$caping = $chk_capping;
		
	$inc_per = $set_binary_percent[$plan_id-1];
	$income = $total_pair*($inc_per/100);
	
	$caping = $caping;
	if($caping < $income){
		$flush_business = ($income*100/$inc_per) - $caping*$set_binary_percent[$plan_id-1];//$income - $caping;
		$remain_business = ($income*100/$inc_per) - $flush_business;//$income - $flush_business;
		$rincome = $caping*$set_binary_percent[$plan_id-1];
		$income = $caping;
	}
	else{
		$flush_business = 0;
		$remain_business = $income*100/$inc_per;//$income;
		$rincome = $income*100/$inc_per;//$income;
	}
	
	$sql = "update pair_point set total_business='".($income*100/$inc_per)."',flush_business='$flush_business',
	remain_business='$remain_business' 
	where user_id='$id' and `date` /*= '$date'*/ between '$previous_date' and '$next_date' order by id desc limit 1";
	query_execute_sqli($sql);
	return array($income,$inc_per);
}

function check_user_for_binary_income($id)
{
	return 1;
	$chk_query = query_execute_sqli("select SUM(update_fees) from reg_fees_structure where user_id = '$id' and mode in (1,189)");
	$chk_num = mysqli_num_rows($chk_query);
	if($chk_num > 0)
	{	
		while($row = mysqli_fetch_array($chk_query))
		{
			$total_invest_ment = $row[0];
			if($total_invest_ment >= 10)
				return 1;
			else
				return 0;	
		}
	}
	else
		return 0;		
}

