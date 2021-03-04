<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");

if(isset($_POST['submit']))
{
	$ip_address = $_REQUEST[ip_address];
	if($ip_address != '')
	{
		$q = query_execute_sqli("select * from block_ip_address where block_ip_address = '$ip_address' ");
		$num = mysqli_num_rows($q);
		if($num > 0){ echo "<B class='text-danger'>This Ip Address Already Blocked !</B>"; }
		else{
			$date = date('Y-m-d');
			query_execute_sqli("insert into block_ip_address (block_ip_address , date) values ('$ip_address' , '$date') ");
			
			echo "<B class='text-success'>Ip Address Successfully Saved !</B>"; 
		}
	}
	else { echo "<B class='text-danger'>Please Enter IP Address!</B>"; }	
}

else{ ?> 
<form name="my_form" action="index.php?page=block_ip_add" method="post">
<table class="table table-bordered">
	<tr>
		<th>Enter Ip Address</th>
		<td><input type="text" name="ip_address" class="form-control"/></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  }  ?>

