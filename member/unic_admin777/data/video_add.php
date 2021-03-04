<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/setting.php");

if(isset($_POST['submit'])){
	$title = $_POST['title'];
	$video_link = $_POST['video_link'];
	
	$sql = "INSERT INTO `video` (`title`, `video_link`, `date`) VALUES ('$title ', '$video_link', NOW())";
	query_execute_sqli($sql);
	
	?> <script>alert("Video Link Successfully Added!"); window.location="index.php?page=<?=$val?>";</script> <?php
}
?>	

<form action="" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="2">Youtube Video Link</th></tr></thead>
	<tr>
		<th>Title</th>
		<td><input type="text" name="title" class="form-control" /></td>
	</tr>
	<tr>
		<th>Youtube Video Link</th>
		<td><input type="text" name="video_link" class="form-control" /></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Add Video Link" class="btn btn-info" />
		</td>
	</tr>
</table>
</form>