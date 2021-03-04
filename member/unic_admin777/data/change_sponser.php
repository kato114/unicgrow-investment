<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");

if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Change Sponser' and $_POST['des_name'] != "")
	{
		$des_name = $_POST['des_name'];
		$sql = "select t1.*,t2.username sponser from users t1
				left join users t2 on t1.real_parent = t2.id_user
				where t1.username = '".$_POST['user_name']."' ";
		$q = query_execute_sqli($sql);
		while($id_row = mysqli_fetch_array($q)){
			$spon_id = $id_row['real_parent'];
			$member_id = $id_row['id_user'];
			$pre_spon_name = $id_row['sponser'];
		}
		$sql = "select t1.* from users t1
				where t1.username = '$des_name' ";
		$q = query_execute_sqli($sql);
		$num = mysqli_num_rows($q);
		$_POST['submit'] = 'Submit';
		if($num > 0){
			while($id_row = mysqli_fetch_array($q)){
				$id = $id_row['id_user'];
			}
			if($_SESSION['admin_make_chan_id'] > $id){
				if($_SESSION['admin_make_chan_id'] != $id){
					if($spon_id != $id){
						query_execute_sqli("update users set real_parent = '$id'");
						echo "<B class='text-success'>Your Request Of Change Sponser Has Been Completed Successfully!</B>";
						$log_username = $_POST['user_name'];
						$pre_spon_name_log = $pre_spon_name;
						$next_spon_name_log = $des_name;
						$date = $systems_date;
						include("../function/logs_tool_messages.php");
						data_logs_tool($member_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
					}
					else{
						echo "<B class='text-danger'>Sponser Exists !</B>";
					}
				}
				else{
					echo "<B class='text-danger'>Member Self Not Get Self As Sponser Name!</B>";
				}
			}
			else{
				echo "<B class='text-danger'>Sponser Name Must Be Of Your Upline!</B>";
			}
			
		}
		else{
			echo "<B class='text-danger'>Please Enter Right Sponser Name!</B>";
		}
	}
	if($_POST['submit'] == 'Submit')
	{
		$u_name = $_POST['user_name'];
		$sql = "select t1.*,t2.username sponser,t3.username binary_p  from users t1
				left join users t2 on t1.real_parent = t2.id_user
				left join users t3 on t1.parent_id = t3.id_user
				where t1.username = '$u_name' ";
		$q = query_execute_sqli($sql);
		$num = mysqli_num_rows($q);
		
		if($num == 0){ echo "<B class='text-danger'>Please Enter right User Name!</B>"; }
		else
		{
			while($id_row = mysqli_fetch_array($q)){
				$_SESSION['admin_make_chan_id'] = $id = $id_row['id_user'];
				$username = $id_row['username'];
				$sponser = $id_row['sponser'];
				$binary_p = $id_row['binary_p'];
				$id = $id_row['id_user'];
			}
			 ?>
			<form name="invest" method="post" action="">
			<input type="hidden" name="user_name" value="<?=$_POST['user_name']?>"/>
			<table class="table table-bordered">
				<thead>
				<tr>
					<th class="text-center">Username</th>
					<th class="text-center">Sponser</th>
					<th class="text-center">Binary Placement </th>
					<th class="text-center">Desired Sponser</th>
				</tr>
				</thead>
				<tr>
					<td class="text-center"><?=$username?></td>
					<td class="text-center"><?=$sponser?></td>
					<td class="text-center"><?=$binary_p?> </td>
					<td class="text-center"><input type="text" class="form-control" name="des_name" value="<?=$_POST['des_name']?>" /></td>
				</tr>
				<tr>
					<td colspan="5" class="text-center">
						<input type="submit" name="submit" value="Change Sponser" class="btn btn-info" />
					</td>
				</tr>
				<!--<tr>
					<td colspan="5">
						<a href="index.php?page=add_funds" class="btn btn-info">Add Funds</a>
					</td>
				</tr>-->
			</table>
			</form> <?php	
		}
	}
	
}
else
{ 
unset($_SESSION['make_investment']);
?>	
<form action="" method="post">
<table class="table table-bordered">
	<!--<tr><th colspan="2">Wallet Information</th></tr>-->
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
<?php } ?>

