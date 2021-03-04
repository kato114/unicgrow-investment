<?php
include('../security_web_validation.php');
?>
<?php
$user_id = $_SESSION['mlmproject_user_id'];

$sql = "select * from my_meetings where user_id = '$user_id' ";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num != 0)
{
?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="10">Meeting Status</th></tr></thead>
		<tr>
			<th class="text-center">Meeting Name</th> 
			<th class="text-center">Purpose</th>
			<th class="text-center">Message</th>
			<th class="text-center">Venue</th>
			<th class="text-center">Organizer Name</th>
			<th class="text-center">State</th>
			<th class="text-center">City</th>
			<th class="text-center">Contact No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Status</th>
		</tr>
<?php
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$purpose = $row['purpose'];
		$message = $row['message'];
		$venue = $row['venue'];
		$orgz_name = $row['organizer_name'];
		$state = $row['state'];
		$city = $row['city'];
		$contact_no = $row['contact_no'];
		$date = $row['date'];
		$mode = $row['mode'];
		
		if($mode == 0){ $mode = "<span style=\"color:#FF9900;\">Pending</span>";}
		elseif($mode == 1){ $mode = "<span style=\"color:green;\">Approved</span>";}
		elseif($mode == 2){ $mode = "<span style=\"color:red;\">Cancel</span>";}
		
		print "
			<tr>
				<td class=\"text-center\">$title</td>
				<td class=\"text-center\">$purpose</td>
				<td class=\"text-center\">$message</td>
				<td class=\"text-center\">$venue</td>
				<td class=\"text-center\">$orgz_name</td>
				<td class=\"text-center\">$state</td>
				<td class=\"text-center\">$city</td>
				<td class=\"text-center\">$contact_no</td>
				<td class=\"text-center\">$date</td>
				<td class=\"text-center\"><strong>$mode</strong></td>
			</tr>";
		$j = 1;
	}
	
	print "</table>";
}		
else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</B>";  }

?>
