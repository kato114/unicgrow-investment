<?php
include('../../security_web_validation.php');

include("../function/functions.php");

if(isset($_POST["Import"]))
{
	$filename=$_FILES["file"]["tmp_name"];
	$fileext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	if($fileext == 'csv')
	{
		if($_FILES["file"]["size"] > 0)
		{
			$file = fopen($filename, "r");
			$i = 0;
			$k = 1;
			?>
			<table class="table table-bordered table-hover">
				<thead>
					<th class="text-center">S.NO.</th>
					<th class="text-center">User ID</th>
					<th class="text-center">UTR NO.</th>
					<th class="text-center">DATE</th>
					<th class="text-center">Remarks</th>
				</thead>
				<?php
				unset($_SESSION['cr_import']);
				$_SESSION['cr_import'] = array();
				while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
				{
					if($i > 0){
						if($emapData[0] != ""):
						$user_id = get_new_user_id($emapData[0]);
						if($user_id > 0){
							$date = date("Y-m-d",strtotime($emapData[2]));
							$sql = "SELECT * FROM withdrawal_crown_wallet WHERE user_id = '$user_id' AND status = '0' 							and DATE(date) = '$date'";
							$query = query_execute_sqli($sql);
							$num = mysqli_num_rows($query);
							mysqli_free_result($query);
							if($num > 0){
								if($emapData[1] != "" and $emapData[3] != ""){
									if(!in_array($user_id,$_SESSION['cr_import']['user_id'])){
										if($num == 1){
											$_SESSION['cr_import']['user_id'][] = $user_id;
											$_SESSION['cr_import']['utr'][] = $emapData[1];
											$_SESSION['cr_import']['date'][] = $emapData[2];
											$_SESSION['cr_import']['remark'][] = $emapData[3];
											?>
											<tr class="text-center">
												<td><?=$k?></td>
												<td><?=$emapData[0]?></td>
												<td><?=$emapData[1]?></td>
												<td><?=$emapData[2]?></td>
												<td><?=$emapData[3]?></td>
											</tr>
											<?php
											$k++;
										}
										else{
											echo "<B class='text-danger'>Duplicate Data Found In Systems Of User Id : ".$emapData[0]."</B>";
										}
									}
									else{
										echo "<B class='text-danger'>Duplicate Data Found Of User Id : ".$emapData[0]."</B>";
							break;
									}
								}
								else{
									echo "<B class='text-danger'>UTR NO. OR Remarks Not Avialable For USER ID  ".$emapData[0]."!!</B>";
									break;
								}
							}
							else{
								echo "<B class='text-danger'>Withdrawal Not Found !</B>";
								//echo "<B class='text-danger'>Withdrawal Not Found Of USER ID  ".$emapData[0]."!!</B>";
								break;
							}
						}
						else{
							echo "<B class='text-danger'>User Id Incorrect : ".$emapData[0]."</B>";
							break;
						}
						endif;
					}
					$i++;
				}
				if($k != $i){
					print "<B class='text-danger'>Complete Data Not Imported !!</B>";
				}
			?>
			</table>
			<div class="col-md-12">
				<form action="" method="post">
					<input type="submit" name="back" value="Back" class="btn btn-danger" />
					<?php
					if($k == $i){ ?>
					<input type="submit" name="continue" value="Continue" class="btn btn-success" />
					<?php } ?>
				</form>
			</div>
			<?php
			fclose($file);
		}
	}
	else{ ?> <script>alert("Invalid File:Please Upload CSV File only.");</script> <?php }
}
elseif(isset($_POST['continue']) and $_POST['continue'] == 'Continue'){
	for($i = 0; $i < count($_SESSION['cr_import']['user_id']); $i++){
		$user_id = $_SESSION['cr_import']['user_id'][$i];
		$utr_no = $_SESSION['cr_import']['utr'][$i];
		$date = date('Y-m-d',strtotime($_SESSION['cr_import']['date'][$i]));
		$remark = $_SESSION['cr_import']['remark'][$i];
		$sql = "UPDATE withdrawal_crown_wallet SET status = 2 , transaction_no = '$utr_no', 
				sys_comment = '$remark' WHERE user_id = '$user_id' and DATE(date)='$date' ";//stop roi
		query_execute_sqli($sql);
		$sql = "insert into withdrawal_cr_import SET user_id = $user_id , transaction_no = '$utr_no', 
				comment = '$remark',date='$date',imp_date='$systems_date_time',impact_id=(select id from withdrawal_crown_wallet  WHERE user_id = '$user_id' and DATE(date)='$date' ) ";//stop roi
		query_execute_sqli($sql);
	}
	?> <script>alert("Upload CSV SUCCESSFULLY"); window.location = "index.php?page=cr_import";</script> <?php
}
else{
?>
<form enctype="multipart/form-data" method="post" role="form">
<table class="table table-bordered table-hover">
	<tr>
		<th>File Upload</th>
		<td><input type="file" name="file" id="file" size="150"></td>
		<td><button type="submit" class="btn btn-success" name="Import" value="Import">Upload</button></td>
	</tr>
	<tr>
		<th class="text-danger" colspan="3"> Import Only CSV File Format.</th>
	</tr>
</table>
</form>
<?php
}
?>