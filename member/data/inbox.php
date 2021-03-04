<?php
include('../security_web_validation.php');
?>
<?php
session_start();
require_once("config.php");

$id = $_SESSION['mlmproject_user_id'];

if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	query_execute_sqli("update message set mode = 1 where id = '$table_id' ");
	$query = query_execute_sqli("SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$receive_id  = $row['receive_id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
		
	}
		$qqq = query_execute_sqli("SELECT * FROM admin WHERE id_user = '$id_user'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['username'];
			$name = ucfirst($name);
		}
?> 
		<table class="table table-bordered table-hover">
			<thead><tr><th colspan="2" class="align-left">Inbox</th></tr></thead>
			<tr><td>From &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$name; ?></td></tr>
			<tr><td>Title &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$title; ?></td></tr>
			<tr><td>Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;<?=$message_date; ?></td></tr>
			<tr><td>Message &nbsp;: &nbsp;<?=$message; ?></td></tr>
		</table>		
<?php
}
else
{
	$query = query_execute_sqli("SELECT * FROM message WHERE receive_id = '$id' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ 
		?>
		<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="span1 text-center">Title</th>
				<th class="span1 text-center">Message</th>
				<th class="span1 text-center">Name</th>
				<th class="span1 text-center">Date</th>
			</tr>
		</thead>
		<?php
		while($row = mysqli_fetch_array($query))
		{
			$id  = $row['id'];
			$receive_id  = $row['receive_id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = $row['message_date'];
			$mode = $row['mode'];
			
			$que = query_execute_sqli("SELECT * FROM admin");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['username'];
			}
?>
	<tr>
		<form action="" method="post">
			<input type="hidden" name="table_id" value="<?=$id; ?>"  />
			<td>
			<input type="submit" name="read" value="<?=$title; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
			<td>
			<input type="submit" name="read" value="<?=$message; ?>" style=" width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
			<td>
			<input type="submit" name="read" value="<?=$name; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td> 
			<td>
			<input type="submit" name="read" value="<?=$message_date; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; vertical-align:top; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
		</form>
	</tr>
<?php 		}
		print "</table>";

	}
	else { echo "<B class='text-danger'>There are no information to show !!</B>";}
?>

<?php  } ?>			
