<?php
include('../security_web_validation.php');
?>
<?PHP
session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");


/*$sqli = "SELECT t1.*,t2.file FROM my_ticket t1 
LEFT JOIN my_ticket_message t2 ON t1.user_id = t2.user_id 
where t1.user_id = '$login_id' GROUP BY t2.ticket_id";	*/
$sqli = "SELECT * FROM my_ticket WHERE user_id = '$login_id'";
$query = query_execute_sqli($sqli);
$num = mysqli_num_rows($query);
if($num > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th>Sr no.</th>
			<th>Subject</th>
			<th>Date</th>
			<th>Time</th>
			<!--<th>Image</th>-->
			<th class="text-center">More</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query))
		{  
			$id = $row['id'];
			$title = $row['title'];
			$comment = $row['message'];
			$mode = $row['mode'];
			$uniq_id = $row['unique_id'];
			$user_id = $row['user_id'];
			$date = $row['date'];
			$date1 = date('d/m/Y',strtotime($date));
			$time = date('H:i:s',strtotime($date));
			
			$file = get_ticket_image($user_id,$id);
			
			?>
			<tr>
				<td><?=$sr_no?></td>
				<td><?=$title?></td>
				<td><?=$date1?></td>
				<td><?=$time?></td>
				<!--<td>
					<a href="ticket_img.php?unique_id=<?=$uniq_id?>&file=<?=$file?>" target="_blank">Click Here</a>
				</td>-->
				<td class="text-center">
					<form action="index.php?page=my_ticket" method="POST">
						<input type="hidden" value="<?=$id?>" name="ticket_id" />
						<input type="hidden" value="<?=$mode?>" name="mode" />
						<input type="hidden" value="<?=$catg_id?>" name="catg_id" />
						<input type="hidden" value="<?=$uniq_id?>" name="unique_id" />
						<input type="Submit" value="More" name="more" class="btn btn-info" />
					</form>
				</td>
			</tr> <?php	
			$sr_no++;
		} ?>
	</table> <?php
}
else{ echo "<B class='text-danger'>There are no information Found !!</b>";}


function get_ticket_image($user_id,$ticket_id)
{
	$sql = "SELECT file FROM my_ticket_message WHERE user_id = '$user_id' AND ticket_id = '$ticket_id'";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$file = $row[0];
	return $file;
}

?>