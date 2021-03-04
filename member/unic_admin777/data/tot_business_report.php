<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<?php
$admin_loginID = $_SESSION['intrade_admin_id'];

if(isset($_POST['update'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
	$password = $_POST['password'];
	
	$sqlk = "SELECT * FROM admin WHERE id_user = '$admin_loginID' AND password = '$password'";
	$query = query_execute_sqli($sqlk);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$sql = "UPDATE withdrawal_crown_wallet SET status = 2 , transaction_no = '$utr_no', sys_comment = '$remarks' 
		WHERE id = '$table_id' AND user_id = '$user_id'";
		query_execute_sqli($sql);
		?> 
		<script>alert("Edit successfully !"); window.location = "index.php?page=<?=$val?>";</script> <?php
	}
	else{ ?>
		<script>
			alert("Please enter your correct password !"); window.location = "index.php?page=<?=$val?>";
		</script> <?php
	}
}

elseif(isset($_POST['approve'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
		
	$sql = "UPDATE withdrawal_crown_wallet SET status = 2 , transaction_no = '$utr_no', sys_comment = '$remarks' 
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	?> 
	<script>alert("Approved successfully !"); window.location = "index.php?page=<?=$val?>";</script> <?php
}

elseif(isset($_POST['edit'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
	?>
		<form method="post" action="index.php?page=<?=$val?>">
			<input type="hidden" name="table_id" value="<?=$table_id?>" />
			<input type="hidden" name="user_id" value="<?=$user_id?>" />
			<table class="table table-bordered">
				<tr>
					<th>UTR No.</th>
					<td><input type="text" name="utr_no" value="<?=$utr_no?>" class="form-control" /></td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td><textarea name="remarks" class="form-control"><?=$remarks?></textarea></td>
				</tr>
				<tr>
					<th>Password</th>
					<td><input type="password" name="password" class="form-control" /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="update" value="Update" class="btn btn-primary" />
					</td>
				</tr>
			</table>
		</form>
	<?php
}
else{
	
	if(isset($_POST['user_id'])){
		unset($_SESSION['user_id'],$_SESSION['withdraw_amt']);
	}
	if(!isset($_SESSION['user_id'])){
		$_SESSION['user_id'] = $_POST['user_id'];
		$_SESSION['withdraw_amt'] = $_POST['withdraw_amt'];
		
	}
	$user_id = $_SESSION['user_id'];
	$withdraw_amt = $_SESSION['withdraw_amt'];
	
	
	$sql = "SELECT * FROM withdrawal_crown_wallet WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Date</th>
				<th class="text-center" width="20%">UTR No.</th>
				<th class="text-center">Remarks</th>
				<th class="text-center">Status</th>
			</tr>
			</thead>
			<?php
			$sr_no = 1;
			$que = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($que))
			{ 	
				$id = $row['id'];
				$user_id = $row['user_id'];
				$amount = $row['request_crowd'];
				$mode = $row['status'];
				$remarks = $row['sys_comment'];
				$utr_no = $row['transaction_no'];
				$date = date('d/m/Y', strtotime($row['date']));
				
				
				switch($mode){
					case 0 : 
						$status = "<span class='label label-warning'>Pending</span><br /><br />
						<input type='submit' name='approve' value='Approve' class='btn btn-success btn-sm' onclick='javascript:return confirm(&quot; Are You Sure? You want to Approve !! &quot;);' />";	
					break;
					case 1 : $status = "<span class='label label-success'>Processing</span>";	break;
					case 2 : $status = "<span class='label label-primary'>Confirm</span><br /><br />
						<input type='submit' name='edit' value='Edit' class='btn btn-info btn-sm' onclick='javascript:return confirm(&quot; Are You Sure? You want to Edit !! &quot;);' />";	break;
					case 3 : $status = "<span class='label label-danger'>Cancel</span>";	break;
				}
				
				if($utr_no == ''){
					$utr_no = "<input type='text' name='utr_no' class='form-control' required />";
				}
				
				if($remarks == ''){
					$remarks = "<textarea name='remarks' class='form-control'></textarea>";
				}
				?>
				<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="table_id" value="<?=$id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<input type="hidden" name="utr_no" value="<?=$utr_no?>" />
				<input type="hidden" name="remarks" value="<?=$remarks?>" />
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$amount;?> &#36;</td>
					<td><?=$date?></td>
					<td><?=$utr_no?></td>
					<td><?=$remarks?></td>
					<td><?=$status?></td>
				</tr>
				</form> <?php
				$sr_no++;
			} ?>
		</table> <?php
	}
	else{ echo "<B class='text-danger'>No info found!</B>";  }
}
?>
