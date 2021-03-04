<?php	

function message_alert($id)
{
	$messages = "<table width=600 border=0> <tr>
					<th class=\"message tip\" width=200>Title</th>
					<th class=\"message tip\" width=200>Message</th>
					<th class=\"message tip\" width=200>Message By</th></tr>";

	$query = query_execute_sqli("select * from message where message_to = '$id' ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$title = $row['title'];
			$message = $row['message'];
			$by = $row['message_by'];
			$messages .="<tr><td><small>$title</small></td><td><small>$message</small></td><td><small>$by</small></td></tr>";
		}
	}
	else
	{
		$messages .="<tr><td class=form_data colspan=3>There Is No Messages to show!</td></tr>";
	}	
	$messages .="</table>";
	return $messages;
}


function request_message($id,$title,$message,$message_to)
{
	query_execute_sqli("insert into message (title , message , message_by , message_to) values ('$title' , '$message' , '$id' , '$message_to' ) ");

}	
	
