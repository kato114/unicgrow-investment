<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/binary_layout/display.php");
include("function/total_child_count.php");

?>
<h1 align="left">Search Member</h1>
<?php

$send_id = $_REQUEST['id'];
if(isset($_POST['submit']) or $send_id != '')
{
	if($send_id != '')
	{
		if($send_id == 0)
			$send_id = 1;
		$num = 1;	
	}
	else
	{	
		$u_name = $_REQUEST[user_name];
		$q = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($q);
	}	
	if($num == 0)
	{
		echo "<h3>Please Enter correct User Name!</h3>"; 
	}
	else
	{
		if($send_id != '')
		{
			$id = $send_id;
		}
		else
		{	
			while($row = mysqli_fetch_array($q))
			{
			   $id = $row['id_user'];
			}
		}	
		$c = 1;
		$j = 1;
		$pos[0] = $id;
		$id_query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
		while($id_row = mysqli_fetch_array($id_query))
		{
			$type = $id_row['type'];
			$position[0] = get_user_pos($id);
			$mode[0] = get_mode($id);
			$date[0] = $id_row['date'];
			$gender[0] = $id_row['gender'];
			$img[0] = get_img($pos[0],$type);
			$user_name[0] = $id_row['username'];
			$all_child = give_total_children($id);
			$left_child[0] = $all_child[0];
			$right_child[0] = $all_child[1];
			$name[0] = $id_row['f_name']." ".$id_row['l_name']; 
			$parent_u_name[0] = get_username($id_row['parent_id']);	
		}
		for($i = 0; $i <1; $i++)
		{
			if($pos[$i] == 0)
			{
				$pos[$j] = 0;
				$j++;
				$pos[$j] = 0;
				$j++;
			}
			else
			{
				$n_id = $pos[$i];
				$query = query_execute_sqli("SELECT * FROM users WHERE parent_id = '$n_id' ");
				while($row = mysqli_fetch_array($query))
				{
					$pos[$j] = $row['id_user'];
					$mode[$j] = get_mode($pos[$j]);
					$position[$j] = get_user_pos($pos[$j]);
					$user_name[$j] = $row['username'];
					$parent_u_name[$j] = get_username($row['parent_id']);	
					$type = $row['type'];
					$date[$j] = $row['date'];
					$gender[$j] = $row['gender'];
					$all_child = give_total_children($pos[$j]);
					$left_child[$j] = $all_child[0];
					$right_child[$j] = $all_child[1];
					$name[$j] = $row['f_name']." ".$row['l_name']; 
					$img[$j] = get_img($pos[$j],$type);
					$j++;	
				}
				$num = mysqli_num_rows($query);
				if($num == 1) { $pos[$j] = 0; $j++; }
				if($num == 0) { $pos[$j] = 0; $j++; 
								$pos[$j] = 0; $j++;
							  }
			}
			
		}
		
		$page = "index.php?val=tree_view&open=3";
		display_member($pos,$page,$img,$user_name,$parent_u_name,$name,$mode,$position,$date,$left_child,$right_child,$gender);
	}
}			
else{ ?>
<table align="center" border="0" width=450>
<form name="my_form" action="index.php?page=search-member" method="post">
  <tr>
    <td align="left" colspan="2" class="td_title"><strong style="font-size:16px">Member Information</strong></td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td class="td_title"><h3 style="width:250px;">Enter Member UserName</h3></td>
    <td><input type="text" name="user_name" size=3 class="input-medium"/></td>
  </tr>
  <tr>
    <td align="center" colspan="2"><p align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" class="normal-button" /></p></td>
    
  </tr>
  </form>
</table>
<?php  } 

function get_img($id,$type)	
{
	$date = date('Y-m-d');
	$q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and date <= '$date' and end_date >= '$date' ");
	$num = mysqli_num_rows($q);
	if($type == 'B' and $num != 0) { $imges = "p"; }
	if($type == 'B' and $num == 0) { $imges = "f"; }
	if($type == 'C') { $imges = "b"; }
	return $imges;
}

function get_mode($id)
{
	$query = query_execute_sqli("select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
	}
	if($type == 'A') { $mode = "Deactivate";  }
	if($type == 'B') { $mode = "Light";  }
	if($type == 'B') { $mode = "Activate";  }
	return $mode;
}

function get_username($parent)
{
	$query = query_execute_sqli("select * from users where	id_user = $parent ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;	
	}
}
	
?>	