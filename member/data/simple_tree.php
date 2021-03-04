<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/binary_layout/simple_display.php");
//include("function/total_child_count.php");


$login_id = $_SESSION['mlmproject_user_id'];
?>

<script>$(document).ready(function() {	
	$("#sponsor_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 5){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 5){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('check_username.php', {'sponsor_username':sponsor_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>
<form name="myform" action="index.php?page=simple_tree" method="post">
	<div align="right">
		<strong>User Name</strong>
		<input type="text" name="username"  id="sponsor_username" />
		<input type="submit" value="Search" name="Search" class="btn btn-primary" />
	</div>
	<div style="width:280px; float:right; text-align:left;">
		<span id="user-result"></span>
	</div>
</form>
 
<?php

if(isset($_POST['Search']))
{
	$username = $_REQUEST['username'];
	$qu = query_execute_sqli("select * from users where username = '$username' and id_user > '$login_id' ");
	$num = mysqli_num_rows($qu);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($qu))
		{
			if(get_downline_network($login_id,$row['id_user']))
			{
				$id = $row['id_user'];
			}
			else
			{
				$id = $login_id;
				print "<p align=center><font color=\"#FF0000\" size=\"+1\">$username isn't in your downline  !</font></p>"; 
			}
		}
	}	
	else
	{
		print "<p align=center><font color=\"#FF0000\" size=\"+1\">In-Correct User Id or User Id Not In Your Downline !</font></p>"; 
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
			//$all_child = give_total_children($id);
			$left_child[0] = $all_child[0];
			$right_child[0] = $all_child[1];
			$ful_name[0] = $id_row['f_name']." ".$id_row['l_name']; 
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
				//$all_child = give_total_children($pos[$j]);
				$left_child[$j] = $all_child[0];
				$right_child[$j] = $all_child[1];
				$ful_name[$j] = $row['f_name']." ".$row['l_name']; 
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