<?php
include('../../security_web_validation.php');
?>
<?php

include("condition.php");
include("../function/binary_layout/display.php");
include("../function/total_child_count.php");
include("../function/functions.php");

?>
<div class="col-md-4 col-md-offset-8">
<form method="post" action="index.php?page=tree_view">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="username" placeholder="Search By Username" class="form-control" /></td>
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	
</div> 
<style>
@media screen and (max-width: 764px) {
	.input-mobile{width:auto;}
}
</style>
<?php 

if(isset($_POST['Search'])){
	$username = $_REQUEST['username'];
	$qu = query_execute_sqli("select * from users where username = '$username'");
	$num = mysqli_num_rows($qu);
	if($num > 0){
		while($row = mysqli_fetch_array($qu)){
			//if(get_downline_network($login_id,$row['id_user'])){
				$id = $row['id_user'];
			/*}
			else{
				$id = $login_id;
				echo "<B style='color:#FF0000;'>$username isn't in your downline  !</B>"; 
			}*/
		}
	}	
	else{
		echo "<B style='color:#FF0000;'>In-Correct User Id !</B>"; 
		$id = 1; 
	}	

}
else{
	$u_id = $_REQUEST[id];
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
$sql = "SELECT t1.*,t2.username s_username,CONCAT(t2.f_name,' ',t2.l_name) name,t3.user_id 
		FROM users t1
		left join users t2 on t1.real_parent = t2.id_user
		left join reg_fees_structure t3 on t3.user_id = t1.id_user
 		WHERE t1.id_user = '$id'
		group by t3.user_id ";
$id_query = query_execute_sqli($sql);

while($id_row = mysqli_fetch_array($id_query)){
	$type = $id_row['type'];
	$position[0] = get_mem_pos($id_row['position']);
	$mode[0] = get_mode($type);
	$date[0] = $id_row['date'];
	$gender[0] = $id_row['gender'];
	$img[0] = get_img($id_row['user_id'],$type,$systems_date);
	$user_name[0] = $id_row['username'];
	$name[0] = $id_row['f_name']." ".$id_row['l_name'];
	$total_child[0] = give_total_children($id);
	$parent_u_name[0] = $id_row['s_username'];	
	$parent_full_name[0] = $id_row['name'];
}
for($i = 0; $i <7; $i++){
	if($pos[$i] == 0){
		$pos[$j] = 0;
		$left_child[$j] = 0;
		$right_child[$j] = 0;
		$j++;
		$pos[$j] = 0;
		$left_child[$j] = 0;
		$right_child[$j] = 0;
		$j++;
	}
	else{
		$n_id = $pos[$i];
		for($ps = 0; $ps < 2; $ps++){
			$sql = "SELECT t1.*,t2.username s_username,CONCAT(t2.f_name,' ',t2.l_name) name,t3.user_id 
					FROM users t1
					left join users t2 on t1.real_parent = t2.id_user
					left join reg_fees_structure t3 on t3.user_id = t1.id_user
					WHERE t1.parent_id = '$n_id' and t1.position = '$ps'
					group by t3.user_id ";
			$query = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($query)){
				$pos[$j] = $row['id_user'];
				$type = $row['type'];
				$mode[$j] = get_mode($type);
				$position[$j] = get_mem_pos($row['position']);
				$user_name[$j] = $row['username'];
				$parent_u_name[$j] = $row['s_username'];
				$parent_full_name[$j] = $row['name'];
				$name[$j] = $row['f_name']." ".$row['l_name']; 
				$date[$j] = $row['date']; 
				$gender[$j] = $row['gender'];
				$total_child[$j] = give_total_children($pos[$j]);
				$img[$j] = get_img($row['user_id'],$type,$systems_date);
				
				$j++;
			}
			$num = mysqli_num_rows($query);
			if($num == 0){
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

display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$gender,$_SESSION['mlmproject_user_username'],$total_child,true);	

function get_img($id,$type,$date){
	$num = 0;
	if($id != "")$num = 1;
	if($type == 'B' and $num != 0) { $imges = "p"; }
	if($type == 'B' and $num == 0) { $imges = "f"; }
	if($type == 'C') { $imges = "b"; }
	if($type == 'D') { $imges = "b"; }
	return $imges;
}
function get_mem_pos($position){
	if($position == 0) { $pos = "Left"; }
	else { $pos = "Right";  }
	return $pos;	
}
function get_mode($type){
	if($type == 'A') { $mode = "Deactivate";  }
	if($type == 'B') { $mode = "Activate";  }
	if($type == 'B') { $mode = "Blocked";  }
	return $mode;
}

function get_username($parent){
	$query = query_execute_sqli("select * from users where	id_user = $parent ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;	
	}
}
