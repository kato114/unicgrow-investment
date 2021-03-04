<?php
include('../security_web_validation.php');
?>
<script>
$(function(){
	$("#checkAll").click(function () {
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
 });
</script>
<?php
session_start();
$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "15";

if(isset($_POST['submit']) and count($_POST['mm_chk']) > 0){
	$sql = "select * from kyc where user_id = '$login_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{	
		$img1 = $row['id_proof'];
		$img2 = $row['pan_card'];
		$pan_no = $row['pan_no'];
	}	
	for($i = 0; $i < count($_POST['mm_chk']); $i++){
		$mm_chk_id = $_POST['mm_chk'][$i];
		$sql = "INSERT INTO kyc 
				(user_id,bank_ac,pan_no,name,father_name,mobile_no,gender,marital_status,
				dob,bank,branch,ifsc,aadhar_no,id_proof_type,c_address,permanent_address,
				address_type,pan_card,cancl_chq_passbook,id_proof,photo,address_proof,date,mode,by_user_id)
			  	
				SELECT $mm_chk_id,bank_ac,pan_no,name,father_name,mobile_no,gender,marital_status,
				dob,bank,branch,ifsc,aadhar_no,id_proof_type,c_address,permanent_address,
				address_type,pan_card,cancl_chq_passbook,id_proof,photo,address_proof,NOW(),mode,$login_id 
			  	FROM kyc where user_id = $login_id";
		query_execute_sqli($sql);
		
	}
	?> 
	<script>
		alert('Kyc Detail Copied Successfully'); 
		window.location ="index.php?page=kyc_docs_copy";
	</script> <?php 
}
//$sql = "SELECT * FROM users WHERE real_parent = '$login_id' order by date desc ";

$sql = "SELECT t1.* from users t1
		left join kyc t2 ON t1.id_user = t2.user_id
		where t1.f_name in (SELECT f_name from users where id_user = $login_id) and t1.id_user not in($login_id)
		AND t2.user_id IS NULL";

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

$sql1 = "SELECT * FROM kyc WHERE user_id = '$login_id'";
$query1 = query_execute_sqli($sql1);
$num = mysqli_num_rows($query1);
if($num == 0){ echo "<B class='text-danger'>Kyc Detail Is Not Completed!</B>"; }
else{ 
	if($totalrows > 0){
		?>
		<form action="index.php?page=kyc_docs_copy" method="post">
		<table class="table table-bordered table-hover">
			<tr>
				<th colspan="1">Total Members</th>
				<th colspan="2"><?=round($totalrows,2);?></th>
				<th colspan="1"><input type="submit" name="submit" value="Merge" class="btn btn-info" /></th>
			</tr>
			<tr>
				<th class="text-center"><input type="checkbox" id="checkAll"><br />All</th>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Name</th>
			</tr>
		<?php 
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $plimit*($newp-1);
		while($row = mysqli_fetch_array($query))
		{
			$sr_no++;
			$username = $row['username'];
			$id_user = $row['id_user'];
			$name= ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$date = date('d/m/Y', strtotime($row['date']));
			$id_user = $row['id_user'];
			$chk_box = '<input type="checkbox" name="mm_chk[]" value="'.$id_user.'">';
			
			?>
			<tr class="text-center">
				<td><?=$chk_box?></td>
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
			</tr> <?php
			$sr_no++;
		} ?>
		</table>
		</form>
	<?php
		
	}
	else{ echo "<B class='text-danger'>There Have No Member For KYC Merge !</B>"; }
}
?>