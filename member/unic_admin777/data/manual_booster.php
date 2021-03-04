<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/direct_income.php");
include("../function/all_child.php");

if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit' and !isset($_SESSION['make_investment']))
	{
		$_SESSION['make_investment'] = 1;
		$u_name = $_REQUEST[user_name];
		$q = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($q);
		
		if($num == 0){ echo "<B class='text-danger'>Please Enter right User Name!</B>"; }
		else
		{
			while($id_row = mysqli_fetch_array($q))
				$_SESSION['admin_master_boost_id'] = $id = $id_row['id_user'];
			$sql = "select * from reg_fees_structure where user_id='$id' and mode='1' and boost_id = 0 
					and plan not in('z')
					order by id asc limit 1";
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows(query_execute_sqli($sql));
			if($num > 0){
				while($row = mysqli_fetch_array($query)){
					$invest_id = $row['id'];
					$update_fees = $row['update_fees'];
					$pv = $row['request_crowd'];
					$profit = $row['profit'];
					$invest_type = $row['invest_type'];
					$invest_date = $row['date'];
					$start_date = $row['start_date'];
					$total_days = $row['total_days'];
					$total_count = $row['count'];
					$plan = $row['plan'];
					$by_wallet = $row['by_wallet'];
					$remarks = $row['remarks'];
					$pos = $row['position'];
					$roi_date = $row['roi_date'];
				}
				$childrens = give_all_children($id);
				$l_child = $childrens[0];
				$r_child = $childrens[1];
				$left_boost = 1;//set_1r1_condition($l_child,$id,$update_fees,$back_date=false);
				$right_boost = 1;//set_1r1_condition($r_child,$id,$update_fees,$back_date=false);
				if($left_boost > 0 and $right_boost > 0){
					$boost_income = $update_fees * $set_booter_percent[$invest_type-1]/100;
					if($boost_income > 0 and $id > 0 and get_type_user($id) == "B"){
						$systems_date_time = $systems_date." ".date("H:i:s");
						query_execute_sqli("update reg_fees_structure set mode=66 where id='$invest_id'");
						$profit = $profit + ($profit*$set_booter_percent[$invest_type-1]/100);
						$insert_sql = "INSERT INTO reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date , profit , total_days , invest_type , plan , time,`count`,by_wallet,remarks,boost_id,position,roi_date) 
									VALUES ('$id' , '100' , '$pv' , '$update_fees' , '$date' , '$start_date', '$profit' , '$total_days' , '$invest_type', '$plan' , '$systems_date_time','$total_count','1','$remarks','$invest_id',$pos,'$roi_date') ";
						query_execute_sqli($insert_sql);
						echo "<B class='text-success'>Member $u_name Have To Boost For Active Investment !!</B>";
					}
					else{ echo "<B class='text-danger'>Systems Error Occour !!</B>"; }
				}
				else{ echo "<B class='text-danger'>Member $u_name Have Not Eligible For Booster !</B>"; }
			}
			else{ echo "<B class='text-danger'>Member $u_name Have No Active Investment Yet !</B>"; }	
		}
	}
	else { echo "<B class='text-danger'>There are Some Conflict !</B>"; }
}
else
{ 
unset($_SESSION['make_investment']);
?>	
<script>
$(document).ready(function() {	
	$("#username_search").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var username_search = $(this).val();
		if(username_search.length < 3){$("#username_view").html('');return;}
		
		if(username_search.length >= 3){
		
			$("#username_view").html('Lodding...');
			$.post('../check_username.php', {'search_username':username_search},function(data)
			{
			  $("#username_view").html(data);
			});
		}
	});		
});	
</script>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th width="40%">Enter Member UserName <span id="username_view"></span></th>
		<td><input type="text" name="user_name" class="form-control" id="username_search"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
<?php } ?>

