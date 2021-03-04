<?php
function give_all_children($id,$start_date,$end_date)  //give all children
{
	// Left business
	
	$sql = "select t1.id_user
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 0 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$left = $row['id_user'];
			$children[0] = get_all_child($left,$start_date,$end_date,"left");
		}
	}
	
	// Right business
	$sql = "select t1.id_user
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 1 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[1] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$right = $row['id_user'];
			$children[1] = get_all_child($right,$start_date,$end_date,"right");
		}
	}
	return $children;
}


function get_all_child($id,$start_date,$end_date,$side)  // get all child in id network
{
 	/*$child[0][0] = get_user_name($id);
	$child[0][1] = $amount;
	$child[0][2] = $topup_date;*/
	$total_business = 0;
	$sql = "select t1.username,t1.id_user,t2.user_id,t1.position,
			t2.update_fees as business,t2.date as topup_date
			from users as t1
			inner join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date'
			where t1.id_user = '$id'";
	$quer = query_execute_sqli($sql);
	$num = mysqli_num_rows($quer);
	$block = 0;
	if($num > 0){
		while($rr = mysqli_fetch_array($quer))
		{
			//$child[$block][0] = get_user_name($rr['id_user']);
			//$child[$block][1] = $rr['business'];
			//$child[$block][2] = $rr['topup_date'];
			//$child[$block][3] = $rr['id_user'];
			$total_business = $total_business + $rr['business'];
			$block++;
		}
	}
	else
	{
		//$child[$block][0] = get_user_name($id);
		//$child[$block][1] = 0;
		//$child[$block][2] = '';
		//$child[$block][3] = $id;
		$total_business = $total_business + 0;
		$block = 1;
	}
	$temp_child[0] = $id;
	$count = count($temp_child);
	for($i = 0; $i <$count; $i++)
	{
		$idds = $temp_child[$i];
		
			
		$sql = "select t1.id_user,t1.username,t2.user_id, 
				sum(t2.update_fees) as business,t2.date as topup_date,t1.position
				from users as t1 
				left join reg_fees_structure as t2 
				on t1.id_user = t2.user_id 
				AND t2.date >= '$start_date' 
				AND t2.date <= '$end_date' 
				where t1.parent_id = '$idds' 
				group by t2.user_id,t1.id_user ";
	
			$result = query_execute_sqli($sql);
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{ 						
					//$child[$block][0] = $row['username'];
					//$child[$block][1] = $row['business'];
					//$child[$block][2] = $row['topup_date'];
					//$child[$block][3] = $row['position'].$side.$id;  //testing
					$temp_child[$block] = $row['id_user'];
					$total_business = $total_business + $row['business'];
				//echo	$temp_child[$block]."&nbsp;&nbsp;".$child[$block][1];
				//	echo "<br>";
					$block++;
				}
				
				$count = count($temp_child);
			}
	}
	$child[0][0] = $total_business;
	//echo $count;
	return $child;
}


/*function give_all_children($id,$start_date,$end_date)  //give all children
{
	// Left business
	
	$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 0 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = 0;
	}
	else
	{	$i = 0;
		while($row = mysqli_fetch_array($query))
		{
			$left = $row['id_user'];
			$amount = $row['totl_business'];
			$children[0] = get_all_child($left,$start_date,$end_date,$amount,$id,"left");
		}
	}
	
	// Right business
	$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 1 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[1] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$right = $row['id_user'];
			$amount = $row['totl_business'];
			$children[1] = get_all_child($right,$start_date,$end_date,$amount,$id,"right");
		}
	}
	return $children;
}


function get_all_child($id,$start_date,$end_date,$amount,$par,$side)  // get all child in id network
{
 	$child[0] = $id;
	$block = 1;
	$count = count($child);
	for($i = 0; $i <$count; $i++)
	{
		$idds = $child[$i];
	
	$sql = "select t1.id_user,t1.type,t2.user_id,t1.position,
				sum(t2.update_fees) as totl_business
				from users as t1
				left join reg_fees_structure as t2
				on t1.id_user = t2.user_id
				AND t2.date >= '$start_date'
				AND t2.date <= '$end_date' 
				where t1.parent_id = '$idds' 
				group by t2.user_id,t1.id_user";
	
			$result = query_execute_sqli($sql);
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{ 						
						$child[$block] = $row['id_user'];
						$amount = $amount + $row['totl_business'];
						/*echo $row['totl_business'].
						"par = $par&nbsp;$side&nbsp;am&nbsp;&nbsp;id$child[$block]&nbsp;&nbsp;posi = "
						.$row['position']."<br />";
						$block++;
					
				}
				$count = count($child);
				
			}
	}
	
	return $amount;
}*/

?>