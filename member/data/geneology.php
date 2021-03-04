<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/binary_layout/display_new.php");
include("function/total_child_count.php");


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
		$id = $_SESSION['mlmproject_user_id']; 
	}	

}
else
{	
	$action = $_REQUEST['action'];
	if($action == '')
	{
	 	$u_id = $_REQUEST['id'];
		if($u_id == 0) $u_id = '';
		if($u_id == '') 
		{ 
			$id = $_SESSION['mlmproject_user_id']; 
			/*echo "<script type=\"text/javascript\">";	
			echo "window.location = \"register.php\"";
			echo "</script>";*/
			
		}  
		else {$id = $u_id; }
	}
	else{
	 switch($action)
	  {
	  	case 'top': $id = top();
					break;
		case 'up': 	 	
						$_SESSION['genelogy_id']=$_REQUEST['id'];
						$id = upline();
						if($id == 0){
							$id=1;
						}
						break;
		case 'left': 	$_SESSION['genelogy_id']=$_REQUEST['id']; 
						$id = bottom_left();
						break;
		case 'right':   $_SESSION['genelogy_id']=$_REQUEST['id']; 
						$id = bottom_right();
						break;
	  }
	}
	unset($_SESSION['genelogy_id']);
}	

$c = 1;
$j = 2;
$pos[1] = $id;
$id_query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");

while($id_row = mysqli_fetch_array($id_query))
		{
			$type = $id_row['type'];
			$position[1] = get_user_pos($id);
			$mode[1] = get_mode($id);
			$date[1] = $id_row['date'];
			$gender[1] = $id_row['gender'];
			$product_id = $id_row['product_id'];
			$img[1] = get_img($id,$type,$systems_date);
			$user_name[1] = $id_row['username'];
			$all_child = give_total_children($id);
			$left_child[1] = $all_child[0];
			$right_child[1] = $all_child[1];
			$ful_name[1] = $id_row['f_name']." ".$id_row['l_name']; 
			$parent_u_name[1] = get_username($id_row['real_parent']);	
			$parent_full_name[1] = get_full_name($id_row['real_parent']);
		}
for($i = 1; $i <8; $i++)
{
	if($pos[$i] == 0)
	{
		$pos[$j] = 0;
		$j++;
		$pos[$j] = 0;
		$j++;
	}
	else{
		$n_id = $pos[$i];
		for($p = 0; $p < 2; $p++)
		{
			$query = query_execute_sqli("SELECT * FROM users WHERE parent_id = '$n_id' and position = '$p' ");
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
				$ful_name[$j] = $row['f_name']." ".$row['l_name'];
				$product_id = $row['product_id'];
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
	$new_reg[$i][0].$new_reg[$i][1]; print "<br>"; }
$page = "index.php?page=geneology";
display($pos,$page,$img,$user_name,$ful_name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender);	


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
	$query = query_execute_sqli("select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
	}
	if($type == 'A') { $mode = "Deactivate";  }
	if($type == 'B') { $mode = "Activate";  }
	if($type == 'C') { $mode = "Blocked";  }
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
<hr color="#CCCCCC" size="1">
<table class="table table-borderless table-hover">
	<tr>
	<td class="text-center"><img border="0" style="width: 35px;" src="img/f.png"></td>
	<td class="text-center"><img border="0" style="width: 35px;" src="img/p.png"></td>
	<td class="text-center"><img border="0" style="width: 35px;" src="img/c.png"></td>
	<td class="text-center"><img border="0" style="width: 35px;" src="img/b.png"></td>
	</tr>
	<tr>
		<th class="text-center">Free Member</th>
		<th class="text-center">Paid Member</td>
		<th class="text-center">Blank Position </td>
		<th class="text-center">Block Member </td>
	</tr>
</table>