<?php
include('../../security_web_validation.php');
?>
<?php
$id=$_GET['id'];
$sql_data="select * from advertise where id='$id'";
$result=query_execute_sqli($sql_data);
$row=mysqli_fetch_array($result);

$tbl_ad_title=$row['ad_title'];
$tbl_ad_image=$row['ad_image'];
$tbl_ad_subject=$row['ad_subject'];
$tbl_ad_desc=$row['ad_desc'];
 
if(isset($_POST['submit']))
{
	$ad_title=$_POST['ad_title'];
	$ad_image=$_POST['ad_image'];
	$ad_subject=$_POST['ad_subject'];
	$ad_date=date("d/m/Y");
	$ad_desc=$_POST['ad_desc'];

	$target_path = "E:/server1/canindia/business_2015/images/advertisement/";
	$target_image=$target_path.$tbl_ad_image;
	unlink($target_image);
	
	$target_path = $target_path . basename( $_FILES['ad_image']['name']); 
	
	$file_name=$_FILES['ad_image']['name'];
	move_uploaded_file($_FILES['ad_image']['tmp_name'], $target_path);
 
   	$sql = "UPDATE  advertise SET ad_title='$ad_title', ad_image='$file_name', 
	ad_subject='$ad_subject', 	ad_date='$ad_date', ad_desc='$ad_desc' WHERE id='$id'";	
	query_execute_sqli($sql);

	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=advertisement\"";
	echo "</script>";
}
?>

<form name="frm1" action="" method="post" enctype="multipart/form-data">
<table width="50%" border="1">
	<tr><th class="text-center" colspan="2"><p align="center">Admin Panel for Advertisement</p></th></tr>
	<tr>
		<td><strong>Advertisement Title </strong></td>
		<td><input type="text" name="ad_title" id="ad_title" value="<?=$tbl_ad_title;?>" /></td>
	</tr>
	<tr>
		<td><strong>Advertisement Image </strong></td>
		<td><input type="file" name="ad_image" id="ad_image"  /></td>
	</tr>
	<tr>
		<td><strong>Subject</strong></td>
		<td><input type="text" name="ad_subject" id="ad_subject" value="<?=$tbl_ad_subject;?>" /></td>
	</tr>
	
	<tr>
		<td><strong>Description</strong></td>
		<td><textarea name="ad_desc" rows="2" value="<?=$tbl_ad_desc;?>"></textarea></td>
	</tr>
	<tr>
		<td><strong><a href="index.php?page=advertisement">Back to list</a></strong></td>
		<td><input type="submit" name="submit" value="Update" /> </td>
	</tr>
</table>
</form>
