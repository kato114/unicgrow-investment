<?php
    $conn = mysqli_connect("localhost", "root", "");
    mysqli_select_db("phppot_examples");
    $sql = "SELECT imageId FROM output_images ORDER BY imageId DESC"; 
    $result = query_execute_sqli($sql);
?>
<HTML>
<HEAD>
<TITLE>List BLOB Images</TITLE>
<link href="imageStyles.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
<?php
	while($row = mysqli_fetch_array($result)) {
	?>
		<img src="imageView.php?image_id=<?php echo $row["imageId"]; ?>" /><br/>
	
<?php		
	}
    mysqli_close($conn);
?>
</BODY>
</HTML>