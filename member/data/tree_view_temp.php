<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/binary_layout/display_temp.php");
include("function/total_child_count_temp.php");


$login_id = $_SESSION['mlmproject_user_id'];
?>
<form name="myform" action="index.php?page=tree_view_temp" method="post">
<table class="table table-bordered">
	<tr>
		<th class="text-right" width="74%">User Name</th>
		<td class="text-right"><input type="text" name="username" class="form-control input-mobile" /></td>		
		<td class="text-right"><input type="submit" value="Search" name="Search" class="btn btn-default" /></td>
	</tr>
</table>
</form>
 <style>
 @media screen and (max-width: 764px) {
.input-mobile{width:auto;}
}
 </style>
<?php

if(isset($_POST['Search']))
{
	$username = $_REQUEST['username'];
	$sql = "select * from user_temp1 where username = '$username'";
	$qu = query_execute_sqli($sql);
	$num = mysqli_num_rows($qu);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($qu))
		{
			$sql = "SELECT `get_chield_by_parent_temp`($login_id) AS `get_chield_by_parent_temp`;";
			$query = query_execute_sqli($sql);
			while($row1 = mysqli_fetch_array($query)){
				$child = explode(",",$row1[0]);
			}
			if(in_array($row['id_user'],$child))
			{
				$id = $row['id_user'];
			}
			else
			{
				$id = $login_id;
				echo "<B class='text-danger'>$username isn't in your downline  !</B>"; 
			}
		}
	}	
	else
	{
		echo "<B class='text-danger'>In-Correct User Id !</B>"; 
		$id = $login_id; 
	}	

}
else
{
	$u_id = $_REQUEST[id];
	if($u_id == 0) $u_id = '';
	if($u_id == '') 
	{ 
		$id = $_SESSION['mlmproject_user_id'];
	}  
	else {$id = $u_id; }
}	

$c = 1;
$j = 1;
$pos[0] = $id;
$sql = "SELECT * FROM user_temp1 WHERE id_user = '$id' ";
$id_query = query_execute_sqli($sql);

while($id_row = mysqli_fetch_array($id_query))
{
	$type = 'B';
	$position[0] = $id_row['position'];;
	$date[0] = $id_row['date'];
	$img[0] = get_img($id,$type,$systems_date);
	$user_name[0] = $id_row['username'];
	$name[0] = $id_row['f_name']." ".$id_row['l_name']; 
	$all_child = give_total_children($id);
	$left_child[0] = $all_child[0];
	$right_child[0] = $all_child[1];
	$parent_u_name[0] = get_username($id_row['real_parent']);	
	$parent_full_name[0] = get_full_name($id_row['real_parent']);
}

for($i = 0; $i <7; $i++)
{
	if($pos[$i] == 0)
	{
		$pos[$j] = 0;
		$left_child[$j] = 0;
		$right_child[$j] = 0;
		$j++;
		$pos[$j] = 0;
		$left_child[$j] = 0;
		$right_child[$j] = 0;
		$j++;
	}
	else
	{
		$n_id = $pos[$i];
		for($ps = 0; $ps < 2; $ps++)
		{
			$query = query_execute_sqli("SELECT * FROM user_temp1 WHERE parent_id = '$n_id' and position = '$ps' ");
			while($row = mysqli_fetch_array($query))
			{
				$pos[$j] = $row['id_user'];
				//$mode[$j] = get_mode($pos[$j]);
				$position[$j] = $row['position'];
				$user_name[$j] = $row['username'];
				$parent_u_name[$j] = get_username($row['real_parent']);
				$parent_full_name[$j] = get_full_name($row['real_parent']);
				$name[$j] = $row['f_name']." ".$row['l_name']; 
				$type = "B";
				$date[$j] = $row['date']; 
				//$gender[$j] = $row['gender'];
				$all_child = give_total_children($pos[$j]);
				$left_child[$j] = $all_child[0];
				$right_child[$j] = $all_child[1];
				$img[$j] = get_img($pos[$j],$type,$systems_date);
				//print "<br> Id ".$pos[$j]." Left ".$left_child[$j]." Right ".$right_child[$j];
				
				$j++;
			}
			$num = mysqli_num_rows($query);
			if($num == 0)  
			{
				$pos[$j] = 0; 
				$left_child[$j] = 0;
				$right_child[$j] = 0;
				$j++; 
			} 
		}					 
	}
	
}

$c = count($new_reg);
for($i = 0; $i < $c; $i++) {
	print $new_reg[$i][0].$new_reg[$i][1]; print "<br>"; }
$page = "index.php?val=tree_view";

display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender,$_SESSION['mlmproject_user_username']);	

function get_img($id,$type,$date)	
{
	//$q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and date <= '$date' and end_date >= '$date' ");
	$q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' ");
	$num = mysqli_num_rows($q);
	if($type == 'B' and $num != 0) { $imges = "p"; }
	if($type == 'B' and $num == 0) { $imges = "f"; }
	if($type == 'C') { $imges = "b"; }
	return $imges;
}

function get_mode($id)
{
	$query = query_execute_sqli("select * from user_temp1 where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
	}
	if($type == 'A') { $mode = "Deactivate";  }
	if($type == 'B') { $mode = "Activate";  }
	if($type == 'B') { $mode = "Blocked";  }
	return $mode;
}

function get_username($parent)
{
	$query = query_execute_sqli("select * from user_temp1 where	id_user = $parent ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;	
	}
}


	
/*	$mode[$j] = '';
			$position[$j] = 0;
			$user_name[$j] = '';
			$parent_u_name[$j] = '';
			$parent_full_name[$j] = '';
			$type = '';
			$date[$j] = ''; 
			$gender[$j] = 
			$all_child = 0;
			$left_child[$j] = 0;
			$right_child[$j] = 0;
			$name[$j] = ''; 
			$img[$j] = '';*/
			
?>			