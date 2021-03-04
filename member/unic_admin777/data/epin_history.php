<?php
include('../../security_web_validation.php');
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$epin = $_POST['epin'];
	$sql = "SELECT * FROM e_pin t1 
	INNER JOIN epin_history t2 ON t1.id = t2.epin_id AND epin = '$epin' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<table class="table table-bordered">
			<?php
			while($row = mysqli_fetch_array($query))
			{
				$gen_id = $row['generate_id'];
				$pin_date = $row['date'];
				$pin_date = date('d/m/Y', strtotime($row['date']));
				$used_id = get_user_name($row['used_id']);
				$used_date = date('d/m/Y', strtotime($row['used_date']));
				$owner = $row['transfer_to'];
				$transfer_id = $row['user_id'];
				
				if($gen_id == 0){
					$generate_id = 'Admin';	
				}
				else{
					$generate_id = get_user_name($row['generate_id']);
				}
				
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
				
				if($used_id == '')
				{
					$used_id = "<span class='label label-warning'>Not Used</span>";
					$used_date = "<span class='label label-danger'>No Date</span>";
				}
				$date = $row['date'];
				$epin = $row['epin'];
				?>
				<tr><th>Generate By</th>	<th><?=$generate_id?></th></tr>
				<tr><th>Transfer To</th>	<th><?=$owner?></th></tr>
				<tr><th>Generate Date</th>	<th><?=$pin_date?></th></tr>
				<tr><th>Used By</th>		<th><?=$used_id?></th></tr>	
				<tr><th>Used Date</th>		<th><?=$used_date?></th></tr> <?php
			} ?>
		</table> <?php		
	}		
	else
	{
		$sql = "select * from e_pin where epin = '$epin' ";
		$query = query_execute_sqli($sql);
		?>
		<table class="table table-bordered">
			<?php
			while($row = mysqli_fetch_array($query))
			{
				$used_id = get_user_name($row['used_id']);
				$used_date = date('d/m/Y', strtotime($row['used_date']));
				$pin_date = date('d/m/Y', strtotime($row['date']));
				if($used_id == '')
				{
					$used_id = "<span class='label label-warning'>Not Used</span>";
					$used_date = "<span class='label label-danger'>No Date</span>";
				}
				$generate_id = "No Information";
				$transfer_id = get_user_name($row['user_id']);
				
				?>
				<tr><th>Generate By</th>	<th><?=$generate_id?></th></tr>
				<tr><th>Transfer To</th>	<th><?=$transfer_id?></th></tr>
				<tr><th>Generate Date</th>	<th><?=$pin_date?></th></tr>
				<tr><th>Used By</th>		<th><?=$used_id?></th></tr>	
				<tr><th>Used Date</th>		<th><?=$used_date?></th></tr> <?php
			} ?>
		</table>
		<?php
	}
}
else
{
?>
<form method="post" action="index.php?page=epin_history">
<table class="table table-bordered">
	<tr>
		<th>E-pin</th>
		<td><input type="text" name="epin" placeholder="Insert E-pin" class="form-control" /></td>
		<th colspan="2">
			<input type="submit" value="Submit" name="Submit" class="btn btn-primary" />		
		</th>
	</tr>
</table>
</form>	
<?php
}
?>