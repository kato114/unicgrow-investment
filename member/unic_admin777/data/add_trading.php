<?php
include('../../security_web_validation.php');
?>
<?php
if(isset($_POST['submit']))
{
$title=$_POST['title'];
$standard=$_POST['standard'];
$toptrade=$_POST['toptrade'];
if($title!="" and $standard!="" and $toptrade!="")
{
	$sql = "INSERT INTO trading(title,standard, toptrade, date)
	VALUES ('$title','$standard','$toptrade','$systems_date')";
	query_execute_sqli($sql);
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=add_trading&msg=suss\"";
	echo "</script>";
}else
{
echo $error="Some Field Is blank";
}
}


if($error=="" and $_REQUEST['msg']=='suss')
{
  echo "Successfully";
}

?>
<table class="table table-bordered">
	<form name="message" action="index.php?page=add_trading" method="post">
		<input type="hidden" name="id" value=""  />
		<input type="hidden" name="id_user" value=""  />
		<tr>
			<th>Statistics Title</th>
			<td><input type="text" style="width:370px;" name="title" /></td>
		</tr>
		<tr>
			<th>Standard</th>
			<td><input type="text" style="width:370px;" name="standard" /></td>
		</tr>
		<tr>
			<th>Top-Trade</th>
			<td><input type="text" style="width:370px;" name="toptrade" /></td>
		</tr>
		<tr>
			<td colspan="2" class="text-right" align="right">
				<input type="submit" name="submit" value="Submit" class="btn btn-info"/>
			</td>
		</tr>
	</form>
</table>
		

