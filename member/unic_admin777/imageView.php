<?php
	include("../config.php");
    if(isset($_GET['image_id'])) {
        $sql = "SELECT imageType,image FROM product WHERE id=" . $_GET['image_id'];
		$result = query_execute_sqli("$sql");
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType"]);
        echo $row["image"];
	}
?>