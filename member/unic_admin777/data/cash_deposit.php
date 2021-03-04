<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");


if(isset($_POST['submit'])){

	$username = $_POST['username'];
	$name = $_POST['name'];
	$mobile = $_POST['mobile'];
	$amount = $_POST['amount'];
	$recvd_by = $_POST['recvd_by'];
	$remarks = $_POST['remarks'];
	
	$user_id = get_new_user_id($username);
	
	
	do{
		$utr_no = rand(11111,99999);
	}
	while(mysqli_num_rows(query_execute_sqli("SELECT utr_no FROM cash_deposit WHERE utr_no = '$utr_no'"))!=0);	
	
	
	$date_insert = date("Y-m-d H:i:s");
	if(!empty($_POST['date'])){
		$date_insert = date('Y-m-d', strtotime($_POST['date']))." ".date("H:i:s");
	}
	
	if(!empty($_POST['username'])){
		$sql = "INSERT INTO `cash_deposit`(`user_id`, `name`, `mobile`, `amount`, `received_by`, `remarks`, `date`, `utr_no`) 
		VALUES ('$user_id', '$name', '$mobile', '$amount', '$recvd_by', '$remarks', '$date_insert', '$utr_no')";
				
		query_execute_sqli($sql);
		
		
		$message = "Dear Member, Your UTR No. is ".$utr_no;
		send_sms($mobile,$message);
		?>
		<script>alert("Cash Deposit Successfully !"); window.location = "index.php?page=<?=$val?>";</script> 
		<?php
	}
	else{ ?> <script>alert("Please Enter username!"); window.location = "index.php?page=<?=$val?>";</script> <?php }
}
else
{
	$admin_name = $_SESSION['intrade_admin_name'];
?>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Date</th>
		<td>
			<div id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" value="<?=date('m/d/Y')?>" class="form-control" />
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th width="35%">User ID &nbsp;&nbsp;&nbsp;<span id="user-result"></span></th>
		<td><input type="text" name="username" id="search_username" class="form-control" required /></td>
	</tr>
	<tr>
		<th>Name</th>
		<td><input type="text" name="name" class="form-control" value="<?=$_POST['name']?>" required /></td>
	</tr>
	<tr>
		<th>Mobile No.</th>
		<td><input type="text" name="mobile" class="form-control" value="<?=$_POST['mobile']?>" required /></td>
	</tr>
	<tr>
		<th>Amount</th>
		<td><input type="text" name="amount" class="form-control" value="<?=$_POST['amount']?>" required /></td>
	</tr>
	<tr>
		<th>Received By</th>
		<td><input type="text" name="recvd_by" class="form-control" value="<?=$admin_name?>" required /></td>
	</tr>
	<tr>
		<th>Remarks</th>
		<td><textarea name="remarks" class="form-control" required><?=$_POST['remarks']?></textarea></td>
	</tr>
	
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-info" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

<script>
$(document).ready(function() {	
	$("#search_username").keyup(function (e) {
		//removes spaces from username
		
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 3){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 3){
			$("#user-result").html('Lodding...');
			$.post('../check_username.php', {'search_username':sponsor_username},function(data){
				$("#user-result").html(data);
			});
		}
	});	
});		
</script>
