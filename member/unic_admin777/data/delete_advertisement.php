<?php
include('../../security_web_validation.php');
?>
<?php
        $add_id=$_REQUEST['add_id'];
		$uid=$_SESSION['mlmproject_user_id'];
		$sql_image="select image from advertisement where id='$add_id'";
		$result_image=query_execute_sqli($sql_image);
		$row_image=mysqli_fetch_array($result_image);
		$image=$row_image['image'];
		
		$sql_delete="delete from advertisement where id='$add_id'";
		@unlink("../../images/advertisement/$image");
		
		query_execute_sqli($sql_delete);
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=view_advertisement\"";
		echo "</script>";
		
		
		
		?>