<?php
include('../security_web_validation.php');
?>
<?php
session_start();
require_once("config.php");

$id_user = $_SESSION['mlmproject_user_id'];

if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	$query = query_execute_sqli("SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$receive_id  = $row['receive_id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
	}
		$qqq = query_execute_sqli("SELECT * FROM admin WHERE id_user = '0'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['username'];
			$name = ucfirst($name);
		}
?> 
		<table class="table table-bordered table-hover">
			<tr><td>Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$title?></td></tr>
			<tr><td>To &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$name?></td></tr>
			<tr><td>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$message_date?></td></tr>
			<tr><td>Message &nbsp;: &nbsp;<?=$message; ?></td></tr>
		</table>		
<?php
}
else
{
	$query = query_execute_sqli("SELECT * FROM message WHERE id_user = '$id_user' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ 				
		?>
		<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center">Title</th>
				<th class="text-center">Message</th>
				<th class="text-center">Name</th>
				<th class="text-center">Date</th>
			</tr>
		</thead>
		<?php	
		while($row = mysqli_fetch_array($query))
		{
			$receive_id  = $row['receive_id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = date('d/M/Y', strtotime($row['message_date']));
			$mode = $row['mode'];
			$id = $row['id'];
			
			$que = query_execute_sqli("SELECT * FROM admin WHERE id_user = '0'");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['username'];
			
			}
	?>
			<tr>
				<td class="text-center"><?= $title; ?></td>
				<td class="text-center">
					<form action="" method="post">
						<input type="hidden" name="table_id" value="<?= $id; ?>"  />
						<input type="submit" name="read" value="<?= $message; ?>" 
						style=" height:20px; width:150px; background:none; border:none; box-shadow:none; cursor:pointer;vertical-align:middle; " />
					</form>
				</td>
				<td class="text-center"><?= $name; ?></td>
				<td class="text-center"><?= $message_date; ?></td>
			</tr>
	<?php } 
		echo "</table>";
	}
	else { echo "<B class='text-danger'>There are no information to show !!</B>";}
}	
?>



