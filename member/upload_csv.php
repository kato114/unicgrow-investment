<?php
ini_set("display_errors",'on');
session_start();

$db_host = "localhost";
$db_username = "mahendra";
$db_password = "mahendra123";
$db = "topride_business_test2";
$con = new mysqli($db_host,$db_username,$db_password,$db);
if (!$con){
  die("Connection error: " . mysqli_connect_error());
}
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
			while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
			{
				
				//It wiil insert a row to our subject table from our csv file`
				if($i > 1){
					$date = date("Y-m-d H:i:s",strtotime($emapData[9]));
					/*$sql = "INSERT into user_roi(user_id,`username`,`contact`,`email_id`,`sponser`,sponsername,sponser_emailid,binary_placement,side, 	joining_date,activation_date,status,package_name,state,city,address,kyc_status,pan_no,bank_name,branch_name, 	ifsc_code, 	account_no) 
					values ('".trim($emapData[1])."','".trim($emapData[2])."','".trim($emapData[3])."','".trim($emapData[4])."','".trim($emapData[5])."','".trim($emapData[6])."','".trim($emapData[7])."','".trim($emapData[8])."','".trim($emapData[9])."','".trim($emapData[10])."','".trim($emapData[11])."','".trim($emapData[12])."','".trim($emapData[13])."','".trim($emapData[14])."','".trim($emapData[15])."','".trim($emapData[16])."','".trim($emapData[17])."','".trim($emapData[18])."','".trim($emapData[19])."','".trim($emapData[20])."','".trim($emapData[21])."','".trim($emapData[22])."')";*/
					if($emapData[22] != "" and $emapData[22] > 0){
					$sql = "INSERT into user_roi(`username`,`contact`) values ('".trim($emapData[1])."','".trim(str_replace(",","",$emapData[22]))."')";//stop roi
					//$sql = "INSERT into user_roi1(`username`) values ('".trim($emapData[0])."')"; // block member
					//insert member
					/*$sql = "INSERT into user_paid_roi(`username`,`paid_date`,`no_of_pack`,`amount`) 
					values('".trim($emapData[1])."','$date','".trim($emapData[10])."','".trim($emapData[11])."')";//insert paid entry*/
					
					query_execute_sqli($sql);
					}
				}
				$i++;
			}
			fclose($file);
			//throws a message if data successfully imported to mysql database from CSV/excel file
			echo "<script type=\"text/javascript\">
			alert(\"CSV File has been successfully Imported.\");
			</script>";
			//close of connection
			mysqli_close($con); 
		}
	}
	else
	{
		echo "<script type=\"text/javascript\">
		alert(\"Invalid File:Please Upload CSV File only.\");
		</script>";
	}
}
function query_execute_sqli($sqli){
	global $con;
	global $vr;
	global $vri;
	$vr[$vri] = $srs = mysqli_query($con,$sqli);
	$vri++;
	return $srs;
}	 
?>
<form enctype="multipart/form-data" method="post" role="form">
<table width="55%" border="0">
	<tr>
		<td>File Upload</td>
		<td><input type="file" name="file" id="file" size="150"> Import Only CSV File Format.</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td></td>
		<td><button type="submit" class="normal-button" name="Import" value="Import">Upload</button></td>
	</tr>
</table>
</form>