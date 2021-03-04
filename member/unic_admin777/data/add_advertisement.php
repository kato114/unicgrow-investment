<?php
include('../../security_web_validation.php');
?>
<?php

if(isset($_REQUEST['submit']))
{
	$ad_title=$_REQUEST['ad_title'];
	$ad_image=$_REQUEST['ad_image'];
	$ad_url=$_REQUEST['ad_url'];
	$mode=$_REQUEST['mode'];
	$ad_date=date('Y-m-d');
	$ad_desc=$_REQUEST['ad_desc'];

	$target_path = "E:/server1/alexaatraffic/business/images/advertisement/";
	$target_path = $target_path . basename( $_FILES['ad_image']['name']); 

 	$file_name=$_FILES['ad_image']['name'];
 
	if(move_uploaded_file($_FILES['ad_image']['tmp_name'], $target_path)) 
	{
    	$sql = "INSERT INTO advertisement (title,image,ad_url,description,date,user_id,mode)
		VALUES ('$ad_title', '$file_name','$ad_url','$ad_desc','$ad_date','0','$mode')";
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
		<td><strong>Advertisement Url</strong></td>
		<td><textarea name="ad_url" id="ad_url" style="width:180px; height:60px;"></textarea></td>
	</tr>
	<tr>
		<td><strong>Mode</strong></td>
		<td><select name="mode" id="mode">
		<option value="1">Approved</option>
		<option value="0">Un Approved</option>
		</select></td>
	</tr>
	<tr>
		<td><strong>Description</strong></td>
		<td><textarea name="ad_desc" style="width:230px; height:90px;"></textarea></td>
	</tr>
	<tr>
		<td><strong><a href="index.php?page=view_advertisement">View list</a></strong></td>
		<td><input type="submit" name="submit" value="Submit" /> </td>
	</tr>
</table>
</form>
