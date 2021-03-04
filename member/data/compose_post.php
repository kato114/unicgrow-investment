<?php
include('../security_web_validation.php');
?>
<?php
session_start();
require_once("config.php");
$id = $_SESSION['mlmproject_user_id'];

if(isset($_POST['submit']))
{ 
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
	$username = $_REQUEST['username'];
	$message_date = date('y-m-d');
	$time = date('H:i:s');
	
	if($title != '')
	{		
		if($username == 'Admin' or $username == 'admin')
		{
				/*$quu = query_execute_sqli("select * from admin");
				while($rrr = mysqli_fetch_array($quu))
				{
					$admin = $rrr ['admin'];	
				}*/
			query_execute_sqli("INSERT INTO message (id_user,receive_id, title, message, message_date, mode , time) 
			VALUES ('$id','0' , '$title' , '$message', '$message_date' , '0' , '$time') ");
			
			$_SESSION['success'] = "<font color=green size=2><strong>Message send successfully!</strong></font>";
				
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
		}
		/*else
		{
		$query = query_execute_sqli("select * from users where username = '$username' ");
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($query))
				$receive_id = $row['id_user'];
				
			query_execute_sqli("INSERT INTO message (id_user,receive_id, title, message, message_date) VALUES ('$id','$receive_id' , '$title' , '$message', '$message_date') ");	
			print "Message send successfully!";
		}
		}*/
		else
		{
			$_SESSION['error'] = "<font color=red size=2><strong>Please Enter Correct Admin Username!</strong></font>";
			echo "<script type=\"text/javascript\">";
			echo "window.location = \"index.php?page=compose\"";
			echo "</script>";
			
		}
	}	
	else
	{
		$_SESSION['error'] = "<font color=red size=2><strong>Please Enter Title!</strong></font>";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=compose\"";
		echo "</script>";
		
	}	
}
