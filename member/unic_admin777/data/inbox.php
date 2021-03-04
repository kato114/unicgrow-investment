<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
require_once("../config.php");
//include("function/functions.php");
$id = $_SESSION['admin_id'];

if(isset($_POST['read']))
{
	$table_id = $_POST['table_id'];
	query_execute_sqli("update message set mode = 1 where id = '$table_id' ");
	$query = query_execute_sqli("SELECT * FROM message WHERE id = '$table_id'");
	while($row = mysqli_fetch_array($query))
	{
		$id  = $row['id'];
		$title = $row['title'];
		$message = $row['message'];
		$message_date = $row['message_date'];
		$mode = $row['mode'];
		$receive_id = $row['id_user'];
		
	}
	if($receive_id == 0)
	{
		$name = "Admin";
	}
	else
	{	
		$qqq = query_execute_sqli("SELECT * FROM users WHERE id_user = '$receive_id'");
		while($rrrr = mysqli_fetch_array($qqq))
		{
			$name = $rrrr['f_name']." ".$rrrr['l_name'];
		
		}
	}	
?> 
		<div style="height:30px; text-align:left; padding-left:10px;">From : <?php print $name; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Title : <?php print $title; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Date : <?php print $message_date; ?></div>
		<div style="height:auto; text-align:left; padding-left:10px; margin-top:20px;">
			Message : <?php print $message; ?>
		</div>
		
<?php
}
else
{
	
	$query = query_execute_sqli("SELECT * FROM message WHERE receive_id = '$id' order by id desc");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
	<table cellpadding="0" cellspacing="0" border="0" width="920" bgcolor="#eaeff2">
<?php					
		while($row = mysqli_fetch_array($query))
		{
			$id  = $row['id'];
			//$receive_id = get_receive_id($row['receive_id']);
			$title = $row['title'];
			$message = $row['message'];
			$message_date = $row['message_date'];
			$mode = $row['mode'];
			$receive_id = $row['id_user'];
			
			$que = query_execute_sqli("SELECT * FROM users WHERE id_user = '$receive_id'");
			while($rrr = mysqli_fetch_array($que))
			{
				$name = $rrr['f_name']." ".$rrr['l_name'];
			}
?>
	<tr height="30">
		<form action="" method="post">
			<input type="hidden" name="table_id" value="<?php print $id; ?>"  />
			<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
			<input type="submit" name="read" value="<?php print $title; ?>" style="width:120px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
				
			<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
			<input type="submit" name="read" value="<?php print $message; ?>" style=" padding-top:5px; width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
		
			<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
			<input type="submit" name="read" value="<?php print $name; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td> 
			
			<td width="300" style="border-bottom:dotted 1px #CCCCCC; font-weight:400;">
			<input type="submit" name="read" value="<?php print $message_date; ?>" style="width:150px; height:20px; background:none; border:none; box-shadow:none; cursor:pointer; text-align:left; <?php if($mode == 0) { ?> font-weight:bold; <?php } ?>" />
			</td>
		</form>
		</tr>
<?php }
	  print "</table>"; 
	}
	 else { print "<B style=\"color:#ff0000;\"><br />There are no information to show</B>";}
} ?>			
