<?php
include('../security_web_validation.php');
?>
<?php

if(isset($_POST['submit']))
{
	$ad_title=$_POST['ad_title'];
	$ad_image=$_POST['ad_image'];
	$ad_url=$_POST['ad_url'];
	$ad_date=date('Y-m-d');
	$ad_desc=$_POST['ad_desc'];
    $user_id=$_SESSION['mlmproject_user_id'];
	$target_path = "E:/server1/alexaatraffic/business/images/advertisement/";
	$target_path = $target_path . basename( $_FILES['ad_image']['name']); 

 	$file_name=$_FILES['ad_image']['name'];
 
	if(move_uploaded_file($_FILES['ad_image']['tmp_name'], $target_path)) 
	{
    	$sql = "INSERT INTO advertisement (title,image,ad_url,description,date,user_id,mode)
		VALUES ('$ad_title', '$file_name','$ad_url','$ad_desc','$ad_date','$user_id','0')";
		query_execute_sqli($sql);
        echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=view_advertisement\"";
		echo "</script>";
	} 
	else
	{
	    echo "There was an error uploading the file, please try again!";
    }
}
?>

<form name="frm1" action="" method="post" enctype="multipart/form-data">
<table class="table table-bordered table-hover">
<thead>
	<tr><th  class="span1" colspan="2"><p align="center">Admin Panel for Advertisement</p></th></tr></thead>
	<tr>
		<td ><strong>Advertisement Title </strong></td>
		<td ><input type="text" name="ad_title" id="ad_title" /></td>
	</tr>
	<tr>
		<td ><strong>Advertisement Image </strong></td>
		<td ><input type="file" name="ad_image" id="ad_image"  /></td>
	</tr>
	<tr>
		<td ><strong>Advertisement Url</strong></td>
		<td ><textarea name="ad_url" id="ad_url" ></textarea></td>
	</tr>
	<tr>
		<td ><strong>Description</strong></td>
		<td ><textarea name="ad_desc" rows="2"></textarea></td>
	</tr>
	<tr>
		<td ><strong><a href="index.php?page=all_advertisement">View list</a></strong></td>
		<td ><input type="submit" name="submit" value="Submit" class="btn btn-primary" /> </td>
	</tr>
</table>
</form>
