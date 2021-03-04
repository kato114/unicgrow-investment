<?php
/*include("../../config.php");
include("setting.php");*/
/*function survey_income($id,$income_type,$amount,$survey)
{
	$type = get_type($id);
	$incomed_id = 0;
	if($survey == 1 && $type == 'B')
	{
		$date = date('Y-m-d');
		
		$query = query_execute_sqli("insert into income (user_id , amount , date , type , incomed_id) values ('$id' , '$amount' , '$date' , '$income_type' , '$incomed_id') ");	
	
	}
}	

/*$id = 2;

survey_income($id,$income_type[1],$income[1],1);
direct_member_income($id,$income_type[2],$income[2]);*/

function direct_member_income($user_id,$income_type,$direct_income)
{
	$parent_id = get_parent($user_id);
	$type = get_type($parent_id);
	if($type != 'A')
	{
		$date = date('Y-m-d');
		include("deducted_amount.php");
		$deducted_amount = deducted_amount($user_id,$direct_income);
		$query = query_execute_sqli("insert into income (user_id , amount , date , type , incomed_id) values ('$parent_id' , '$deducted_amount' , '$date' , '$income_type' , '$user_id') ");	
		insert_into_wallet($parent_id,$deducted_amount,$income_type);
	}
}

function get_parent($user_id) //gettinf real parent
{
	$query = query_execute_sqli("select * from users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$parent = $row['real_parent'];
	}
	return $parent;
}

function get_type($user_id)  //getting type
{
	$query = query_execute_sqli("select * from users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
	}
	return $type;
}

function get_idate($user_id)  //getting type
{
	$query = query_execute_sqli("select * from users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['date'];
	}
	return $date;
}