<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

?>

<table width="80%">
	<tr class="text-center">
		<th>Sr no</th>
		<th>Username</th>
		<th>Receipt</th>
	</tr>
<?php
$sr = 1;
$query = query_execute_sqli("select * from upload_receipt");
$num = mysqli_num_rows($query);
if($num != 0)
{
	while($row = mysqli_fetch_array($query))
	{
	  $username = get_user_name($row['user_id']);
	  $receipt = $row['receipt']; 
	  echo "<tr class=\"input-medium\">
			<th>$sr</th>
			<th>$username</th>
			<th>
				<a href=\"../payment_receipt/$receipt.jpg\" target=\"blank\"><img src=\"../payment_receipt/$receipt.jpg\" height=100px></a>
			</th>
		   </tr>";
		$sr++;
	}
}
else
{
	print "There Are No Receipt";
}	
?>
</table>