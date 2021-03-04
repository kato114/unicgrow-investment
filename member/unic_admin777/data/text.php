<?php
include('../../security_web_validation.php');
?>
<?php
session_start();

include("condition.php");
include("../function/setting.php");


if(isset($_POST['submit']))
{
	$promotion_text = mysqli_real_escape_string($con,$_REQUEST['promotion_text']);
	$date = date('Y-m-d');
	$sql = "INSERT INTO `text` (`promotion_text`, `date`) VALUES ('$promotion_text ', '$date')";
	query_execute_sqli($sql);
	
	
	//query_execute_sqli("update text set promotion_text = '$promotion_text', date = '$date' ");
	
	?> <script> 
		alert("Promotional Text Successfully Added!");
		window.location = "index.php?page=text";
	</script> <?php
}

$query = query_execute_sqli("select * from text ");
while($row = mysqli_fetch_array($query))
{
	$promotion_text = $row['promotion_text'];
}
?>	

<form name="request" action="index.php?page=text" method="post">
<table class="table table-bordered">
	<tr>
		<th>Promotional Text</th>
		<td><textarea name="promotion_text" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Add" class="btn btn-info" />
		</td>
	</tr>
	<tr><th colspan="2" class="text-danger">Example: #username , #f_name , #l_name , #email , #phone_no</th></tr>
</table>
</form>



