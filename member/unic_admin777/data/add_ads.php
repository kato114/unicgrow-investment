<?php
include('../../security_web_validation.php');
?>
<?php

if(isset($_POST['submit']))
{
	$ad_title=$_POST['ad_title'];
	$ad_image=$_POST['ad_image'];
	$ad_subject=$_POST['ad_subject'];
	$ad_date=date("d/m/Y");
	$ad_desc=$_POST['ad_desc'];

	$target_path = "E:/server1/canindia/business_2015/images/advertisement/";
	$target_path = $target_path . basename( $_FILES['ad_image']['name']); 

 	$file_name=$_FILES['ad_image']['name'];
 
	if(move_uploaded_file($_FILES['ad_image']['tmp_name'], $target_path)) 
	{
    	$sql = "INSERT INTO advertise (ad_title,ad_image,ad_subject,ad_date,ad_desc)
		VALUES ('$ad_title', '$file_name', '$ad_subject','$ad_date','$ad_desc')";
		query_execute_sqli($sql);

		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=advertisement\"";
		echo "</script>";
	} 
	else
	{
	    echo "There was an error uploading the file, please try again!";
    }
}
?>

<form name="frm1" action="" method="post" enctype="multipart/form-data">
<table width="50%" border="1">
	<tr><th class="text-center" colspan="2"><p align="center">Admin Panel for Advertisement</p></th></tr>
	<tr>
		<td><strong>Advertisement Title </strong></td>
		<td><input type="text" name="ad_title" id="ad_title" /></td>
	</tr>
	<tr>
		<td><strong>Advertisement Image </strong></td>
		<td><input type="file" name="ad_image" id="ad_image" /></td>
	</tr>
	<tr>
		<td><strong>Subject</strong></td>
		<td><input type="text" name="ad_subject" id="ad_subject" /></td>
	</tr>
	<tr>
		<td><strong>Description</strong></td>
		<td><textarea name="ad_desc" rows="2"></textarea></td>
	</tr>
	<tr>
		<td><strong><a href="index.php?page=advertisement">Back to list</a></strong></td>
		<td><input type="submit" name="submit" value="Submit" /> </td>
	</tr>
</table>
</form>
