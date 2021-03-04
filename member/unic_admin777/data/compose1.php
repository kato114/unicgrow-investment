<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
require_once("../config.php");
$id = $_SESSION['admin_id'];
if(isset($_POST['submit']))
{ 
	
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
	$message_date = date('y-m-d');
	$username = $_REQUEST['username'];

	if($title != '')
	{		
		if($username == 'All' or $username == 'all')
		{
			$quu = query_execute_sqli("select * from users");
			while($rrr = mysqli_fetch_array($quu))
			{
				$all_user[] = $rrr ['id_user'];	
			}
			
			$cnt = count($all_user);
			for($i = 0; $i < $cnt; $i++)
			{
			 	$all_user[$i];
			 	query_execute_sqli("INSERT INTO message (id_user,receive_id, title, message, message_date) VALUES ('0','$all_user[$i]' , '$title' , '$message', '$message_date') ");	
			}
			$_SESSION['success'] = "<font color=green size=2><strong>Message send successfully!</strong></font>";
				
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
		}
		else
		{
			$query = query_execute_sqli("select * from users where username = '$username' ");
			$num = mysqli_num_rows($query);
			if($num > 0)
			{
				while($row = mysqli_fetch_array($query))
					$receive_id = $row['id_user'];
					
				query_execute_sqli("INSERT INTO message (id_user,receive_id, title, message, message_date) VALUES ('0','$receive_id' , '$title' , '$message', '$message_date') ");	
				$_SESSION['success'] = "<font color=green size=2><strong>Message send successfully!</strong></font>";
					
				echo "<script type=\"text/javascript\">";
				echo "window.location = \"index.php?page=compose\"";
				echo "</script>";
			}
			else
			{
				$_SESSION['error'] = "<font color=red size=2><strong>Please Enter Correct Username!</strong></font>";
				echo "<script type=\"text/javascript\">";
				echo "window.location = \"index.php?page=compose\"";
				echo "</script>";
			}
		}
	}
	
	else
	{
		$_SESSION['error'] = "<font color=red size=2><strong>Please Enter Title!</strong></font>";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?val=compose&open=9\"";
		echo "</script>";
		
	}	
}
