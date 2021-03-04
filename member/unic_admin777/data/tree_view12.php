<?php
include('../../security_web_validation.php');
?>
<?php

include("condition.php");
include("function/binary_layout/display.php");
include("../function/total_child_count.php");
include("../function/functions.php");

?>
<table width="100%" border="0" cellspacing="0"  cellpadding="0">
  <tr>
    <td>
		<form name="myform" action="index.php?page=tree_view" method="post">
			<h1>Binary Tree</h1>
	</td>		
		<td align="right" width="75%">	
				<h3 style="margin:0px; padding:0px;">User Name <input type="text" style="width:150px;" name="username" class="form-control" /></h3>
		</td>		
		<td align="right"> 
			<input type="submit" value="Search" name="Search" class="button" style="width:90px;" />
		</td>
			
		</form>	
  </tr>
</table><p></p>

 
<?php

if(isset($_POST['Search']))
{
	$username = $_REQUEST['username'];
	$qu = query_execute_sqli("select * from users where username = '$username' ");
	$num = mysqli_num_rows($qu);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($qu))
			$id = $row['id_user'];
	}	
	else
	{
		print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct User Id !</font>"; 
		$id = 1; 
	}	

}
else
{
	$u_id = $_REQUEST['id'];
	if($u_id == 0) $u_id = '';
	if($u_id == '') 
	{ 
		$id = 1; 
		
	}  
	else {$id = $u_id; }
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
			$img[0] = get_img($id,$type,$systems_date);
			$user_name[0] = $id_row['username'];
			$all_child = give_total_children($id);
			$left_child[0] = $all_child[0];
			$right_child[0] = $all_child[1];
			$name[0] = $id_row['f_name']." ".$id_row['l_name']; 
			$parent_u_name[0] = get_username($id_row['real_parent']);	
			$parent_full_name[0] = get_full_name($id_row['real_parent']);
		}
for($i = 0; $i <7; $i++)
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
		for($ps = 0; $ps < 2; $ps++)
		{
			$query = query_execute_sqli("SELECT * FROM users WHERE parent_id = '$n_id' and position = '$ps' ");
			while($row = mysqli_fetch_array($query))
			{
				$pos[$j] = $row['id_user'];
				$mode[$j] = get_mode($pos[$j]);
				$position[$j] = get_user_pos($pos[$j]);
				$user_name[$j] = $row['username'];
				$parent_u_name[$j] = get_username($row['real_parent']);
				$parent_full_name[$j] = get_full_name($row['real_parent']);
				$type = $row['type'];
				$date[$j] = $row['date']; 
				$gender[$j] = $row['gender'];
				$all_child = give_total_children($pos[$j]);
				$left_child[$j] = $all_child[0];
				$right_child[$j] = $all_child[1];
				$name[$j] = $row['f_name']." ".$row['l_name']; 
				$img[$j] = get_img($pos[$j],$type,$systems_date);
				$j++;	
			}
			$num = mysqli_num_rows($query);
			if($num == 0)  { $pos[$j] = 0; $j++; } 
		}
	}
	
}
$c = count($new_reg);
for($i = 0; $i < $c; $i++) {
	print $new_reg[$i][0].$new_reg[$i][1]; print "<br>"; }
$page = "index.php?val=tree_view&open=3";
display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender);	
function get_img($id,$type,$date)
{
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
	if($type == 'B') { $mode = "Activate";  }
	if($type == 'B') { $mode = "Blocked";  }
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
	