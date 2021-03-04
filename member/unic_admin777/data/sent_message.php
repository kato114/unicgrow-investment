<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
require_once("../config.php");
//include("function/functions.php");

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
		$qqq = query_execute_sqli("SELECT * FROM users WHERE id_user = '$receive_id'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['f_name']." ".$rrrr['l_name'];
		}
?> 
		<div style="height:30px; text-align:left">Title : <?php print $title; ?></div>
		<div style="height:30px; text-align:left">To : <?php print $name; ?></div>
		<div style="height:30px; text-align:left">Date : <?php print $message_date; ?></div>
		<div style="height:auto; text-align:left; margin-top:20px;">Message : <?php print $message; ?></div>
<?php
}
else
{
	$id = $_SESSION['admin_id'];
	$query = query_execute_sqli("SELECT * FROM message WHERE id_user = '0' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		
	<table cellpadding="0" cellspacing="0" border="0" width="920" bgcolor="#eaeff2">				
<?php
		while($row = mysqli_fetch_array($query))
		{
			$receive_id  = $row['receive_id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = $row['message_date'];
			$mode = $row['mode'];
			$id = $row['id'];
			
			$que = query_execute_sqli("SELECT * FROM users WHERE id_user = '$receive_id'");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['f_name']." ".$rrr['l_name'];
			}
?>
			<tr height="30">
				<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<?php print $title; ?>
				</td>
				<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;"><?php print $name; ?></td>
				<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400; padding-top:0px;">
					<form action="" method="post">
						<input type="hidden" name="table_id" value="<?php print $id; ?>"  />
						<input type="submit" name="read" value="<?php print $message; ?>" style="width:150px; 
						height:20px; background:none; border:none; box-shadow:none; cursor:pointer; " />
					</form>
				</td>
				<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
					<?php print $message_date; ?>
				</td>
			</tr>
<?php 	}
		print "</table>"; 
	}	
	 else { print "<B style=\"color:#ff0000;\"><br />There are no information to show</B>";}
}?>
