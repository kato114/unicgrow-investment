<?php
include('../../security_web_validation.php');

if(isset($_POST['Submit'])){ 
	$category = $_POST['category'];
	$date = date('Y-m-d H:i:s');
	
	$sql = "INSERT INTO gallery_category (category , date) VALUES ('$category' , '$date')";
	query_execute_sqli($sql);
	?> <script>alert("Category Add Successfully!"); window.location="index.php?page=<?=$val?>";</script> <?php
}	

else{ ?>
	<form action="" method="post">
	<table class="table table-bordered">
		<thead><tr><th colspan="3">Category</th></tr></thead>
		<tr>
			<th>Add Category</th>
			<td><input type="text" name="category" class="form-control" /></td>
			<td><input type="submit" name="Submit" class="btn btn-info" value="Create" /></td>
		</tr>
	</table>
	</form> <?php 
} ?>

