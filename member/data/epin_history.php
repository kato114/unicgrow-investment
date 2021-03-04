<?php
include('../security_web_validation.php');



if(isset($_POST['Submit']) or isset($_POST['epin_submit']))
{
	if(isset($_POST['epin_submit'])){
		print "<a href=\"index.php?page=my_pin_history\" class=\"btn btn-info\">Back</a>";
	}
	$epin = $_POST['epin'];
	
	$sql = "SELECT * FROM e_pin t1 INNER JOIN epin_history t2 ON t1.id = t2.epin_id AND epin = '$epin' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<table class="table table-bordered"> <?php
		while($row = mysqli_fetch_array($query))
		{
			$gen_id = $row['generate_id'];
			$pin_date = $row['date'];
			
			if($gen_id == 0){
				$generate_id = 'Admin';	
			}
			else{
				$generate_id = get_user_name($row['generate_id']);
			}
			
			$owner = $row['transfer_to'];
			$transfer_id = $row['user_id'];
			if($transfer_id == $owner)
			{
				$transfer_id = 'No Transfer';
				$owner = get_user_name($owner);
			}
			else
			{
				$transfer_id = get_user_name($transfer_id);
				$owner = get_user_name($owner);
			}
			$used_id = get_user_name($row['used_id']);
			$used_date = $row['used_date'];
			if($used_id == '')
			{
				$used_id = "<font color=red>Not Used</font>";
				$used_date = "<font color=red>No Date</font>";
			}
			$date = $row['date'];
			$epin = $row['epin'];
			?> 
			<tr><th>Generate By</th><th><?=$generate_id?></th></tr>
			<tr><th>Transfer To</th><th><?=$owner?></th></tr>
			<tr><th>Generate Date</th><th><?=$pin_date?></th></tr>
			<tr><th>Used By</th><th><?=$used_id?></th></tr>	
			<tr><th>Used Date</th><th><?=$used_date?></th></tr>	<?php
		} ?>
		</table> <?php	
	}		
	else
	{
		$sql = "select * from e_pin where epin = '$epin' ";
		$query = query_execute_sqli($sql);
		$nums = mysqli_num_rows($query);
		if($nums > 0)
		{ ?>
			<table class="table table-bordered">
			<?php
			while($row = mysqli_fetch_array($query))
			{
				$used_id = get_user_name($row['used_id']);
				$used_date = $row['used_date'];
				$pin_date = $row['date'];
				if($used_id == '')
				{
					$used_id = "<font color=red>Not Used</font>";
					$used_date = "<font color=red>No Date</font>";
				}
				$generate_id = "No Information";
				$transfer_id = get_user_name($row['user_id']);
				?>
				<tr><th>Generate By</th> 	<th><?=$generate_id?></th></tr>
				<tr><th>Transfer To</th>	<th><?=$transfer_id?></th></tr>
				<tr><th>Generate Date</th>	<th><?=$pin_date?></th></tr>
				<tr><th>Used By</th>		<th><?=$used_id?></th></tr>	
				<tr><th>Used Date</th>		<th><?=$used_date?></th></tr>	<?php
			} ?>
			</table> <?php
		}
		else{ echo "<B class='text-danger'>Please Enter Correct E-pin !</B>"; }	
	}
}
else
{
?>
<form method="post" action="index.php?page=epin_history">
<table class="table table-bordered">		
	<tr>
		<th>E-pin</th>
		<td><input type="text" name="epin" placeholder="Insert E-pin" class="form-control"></td>
		<td><input type="submit" value="Submit" name="Submit" class="btn btn-primary" /></td>
	</tr>
</table>
</form>	
<?php
}
?>