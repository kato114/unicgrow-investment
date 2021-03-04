<?php
include('../../security_web_validation.php');
?>
<?php
include '../function/functions.php';
include '../function/setting.php';

if(isset($_POST['add']))
{
	print "<p></p><form method='post'><table width=50%>
				<tr>
					<th><p style='padding: 0 0 8px'>Enter Username</p></th>
					<th>
						<input type='text' name=add_franc  class='input-medium' required/>
					</th>
				</tr>	
				<tr>
					<th><p style='padding: 0 0 8px'>Enter Location</p></th>
					<th>
						<input type='text' name=loac_franc  class='input-medium' required/>
					</th>
				</tr>	
				<tr align=center>
					<th colspan=2><input type='submit' name=submit value='Submit' class='button3' /></th>					
				</tr>	
			</table>
			</form>	";
}

elseif(isset($_POST['submit']))
{
	$franc_id = $_POST['add_franc'];
	$location = $_POST['loac_franc'];
	$chk_in_franch = query_execute_sqli("select * from franchise as t1 inner join users as t2 on t1.franchise_id = t2.id_user and t2.username = '$franc_id' ");
	$num = mysqli_num_rows($chk_in_franch);
	
	if($num == 0)
	{
		$chk_user = get_new_user_id($franc_id);
		$time = date('h:i:s');
		
		if($chk_user > 0)
		{
			$sql = "insert into franchise (franchise_id, franchise_wallet, franchise_location, date, time)
						values('$chk_user', '0', '$location', '$systems_date' , '$time')";
			$query = query_execute_sqli($sql);
			print "<p></p><font color='green'>Add Successfully</font>";
		}
		else
		{
			print "<p></p><font color='dark'>Please Enter Correct Username</font>";
		}
	}
	else
	{
		print "<p></p><font color='dark'>This Username Already Have Franchise</font>";
	}		
}

elseif(isset($_POST['delete']))
{
	$id = $_POST['franch_id'];
	query_execute_sqli("delete from franchise where franchise_id = '$id' ");
	print "<font color='light'>Delete Successfully</font>";
}

elseif(isset($_POST['add_balance']))
{
	$id = $_POST['franch_id'];
?>	<form method="post" action="">
	<input type="hidden" name="franch_id" value="<?= $id;?>">
	<table width="40%">
		<tr>
			<th><p>Enter Amount</p></th>
			<th><p><input type="text" name="amount_add" value="" required></p></th>
			<th><p><input type="submit" name="update_balance" value="Update" class="button3"/></p></th>
		</tr>
	</table>
	</form>
<?php	
}
elseif(isset($_POST['update_balance']))
{
	$id = $_POST['franch_id'];
	$franch_amount = $_POST['amount_add'];
	query_execute_sqli("update franchise set franchise_wallet = franchise_wallet+'$franch_amount' where franchise_id = '$id' ");
	print "<font color='light'>Added Successfully</font>";
	
	$franc_wall = frachise_wallet_bal($id);
	insert_franch_wallet_account($id, $franch_amount, $systems_date, $fr_acount_type[1],$fr_acount_type_desc[1],1,$franc_wall);
	
	echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=franchise\"";
		echo "</script>";
}
else
{
	$sql = "select * from franchise as t1 inner join users as t2 on t1.franchise_id = t2.id_user";
	$query = query_execute_sqli($sql);
	$sr = 1;
?>

<table cellpadding="0" cellspacing="0" width="90%">
	<tr>
		<th class="text-center">SR</th>
		<th class="text-center">Franchise Name</th>
		<th class="text-center">Franchise Username</th>
		<th class="text-center">Phone</th>
		<th class="text-center">Email</th>	
		<th class="text-center">Location</th>	
		<th class="text-center">Franchise Wallet</th>	
		<th class="text-center">Status</th>								
	</tr>
<?php while($row = mysqli_fetch_array($query)){?>	
	<form method="post" action="index.php?page=franchise">
	<tr>
		<th class="input-small" style="width:auto;"><?= $sr; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['f_name'].' '.$row['l_name']; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['username']; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['phone_no']; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['email']; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['franchise_location']; ?></th>
		<th class="input-small" style="width:auto;"><?= $row['franchise_wallet']; ?></th>
		<th class="input-small" style="width:auto;">
			<input type="hidden" name="franch_id" value="<?= $row['franchise_id']; ?>" />
			<input type="submit" name="delete" value="Delete" class="button2" />
			<input type="submit" name="add_balance" value="Add Balance" class="button2" />
		</th>
	</tr></form>	
<?php $sr++; } ?>	
	<tr>
		<th colspan="8" align="right" height="150px">
			<form method="post">
				<input type="submit" name="add" value="Add Franchise" class="button3" />
			</form>	
		</th>
	</tr>
					
</table>
<?php 
}
?>