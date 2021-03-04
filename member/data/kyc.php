<?php
include('../security_web_validation.php');
session_start();
include('condition.php');
include("function/setting.php");
$user_id = $_SESSION['mlmproject_user_id'];

$sql = "SELECT * FROM kyc WHERE user_id = '$user_id' AND mode = 1 ";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0)
{
	while($row = mysqli_fetch_array($query))
	{
		$bank_ac = $row['bank_ac'];
		$pan_no = $row['pan_no'];
		$name = $row['name'];
		$fat_name = $row['father_name'];
		$mobile = $row['mobile_no'];
		$gender = $row['gender'];
		$maried = $row['marital_status'];
		$dob = $row['dob'];
		
		$bank = $row['bank'];
		$branch = $row['branch'];
		$ifsc = $row['ifsc'];
		
		$aadhar = $row['aadhar_no'];
		$id_proof_type = $row['id_proof_type'];
		
		$c_address = $row['c_address'];
		$p_address = $row['permanent_address'];
		$address_type = $row['address_type'];
		
		$pan_card = $row['pan_card'];		
		$cancl_chq_passbook = $row['cancl_chq_passbook'];
		$id_proof = $row['id_proof'];
		$photo = $row['photo'];
		$addr_proof = $row['address_proof'];
		
		$img_pan = "<img src='images/mlm_kyc/$pan_card' style='vertical-align:middle' width='100' />";
		$img_id = "<img src='images/mlm_kyc/$id_proof' style='vertical-align:middle' width='100' />";
		$img_passbook="<img src='images/mlm_kyc/$cancl_chq_passbook' style='vertical-align:middle' width='100' />";
		$img_photo = "<img src='images/mlm_kyc/$photo' style='vertical-align:middle' width='100' />";
		$img_addr = "<img src='images/mlm_kyc/$addr_proof' style='vertical-align:middle' width='100' />";
		
	}
	
	 ?>
		
	<table class="table table-bordered table-hover">
		<tr><th colspan="4" style="background:#58636E; color:#FFFFFF;">Identity Details</th></tr>
		<tr>
			<th>Bank A/C No.</th>		<td><?=$bank_ac?></td>
			<th>PAN No.</th>			<td><?=$pan_no?></td>
		</tr>
		<tr>
			<th>Name</th>				<td><?=$name?></td>
			<th>Father Name</th>		<td><?=$fat_name?></td>
		</tr>
		<tr>
			<th>Mobile No.</th>			<td><?=$mobile?></td>
			<th>Gender</th>				<td><?=$gender?></td>
		</tr>
		<tr>
			<th>Marital Status</th>		<td><?=$maried?></td>
			<th>DOB</th>				<td><?=$dob?></td>
		</tr>
		<tr>
			<th>PAN Card</th>			<td><?=$img_pan?></td>
			<th>Bank Name</th>			<td><?=$bank?></td>
		</tr>
		<tr>
			<th>Branch Name</th>		<td><?=$branch?></td>
			<th>IFSC</th>				<td><?=$ifsc?></td>
		</tr>
		<tr>
			<th>Bank PassBook</th>		<td><?=$img_passbook?></td>
			<th>Aadhar No.</th>			<td><?=$aadhar?></td>
		</tr>
		
		<tr>
			<th>ID Proof</th>			<td><?=$img_id?></td>
			<th>Photo</th>				<td><?=$img_photo?></td>
		</tr>
		<tr><th>Identity Type</th>		<td colspan="3"><?=$id_proof_type?></td></tr>
		
		<tr><th colspan="4" style="background:#58636E; color:#FFFFFF;">Address Details</th></tr>
		
		<tr>
			<th>Correspondence Address</th>		<td><?=$c_address?></td>
			<th>Permanent Address</th>			<td><?=$p_address?></td>
		</tr>
		<tr>
			<th>Address Type</th>				<td><?=$address_type?></td>
			<th>Address Proof</th>				<td><?=$img_addr?></td>
		</tr>
	</table> <?php
}
else{ echo "<B class='text-danger'>There are no info to show !!</B>"; }  
//else{ echo "<B class='text-danger'>KYC not approved by Admin !!</B>"; }  
?>
