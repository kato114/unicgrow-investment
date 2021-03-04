<?php
include('../../security_web_validation.php');
?>
<?php
 $id=$_GET['id'];

$sql="select * from advertise where id='$id'";
$res=query_execute_sqli($sql);
$row=mysqli_fetch_array($res);

$image=$row['ad_image'];
$target_path = "E:/server1/canindia/business_2015/images/advertisement/";

$target_image=$target_path.$image;
unlink($target_image);

$sql_delete = "DELETE  FROM advertise WHERE id='$id'";
query_execute_sqli($sql_delete);
query_execute_sqli("ALTER TABLE `advertise` AUTO_INCREMENT =1");
echo "<script type=\"text/javascript\">";
echo "window.location = \"index.php?page=advertisement\"";
echo "</script>";

?>
