<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/message.php");
include("function/setting.php");


?>
 <h1 align="left">User Request</h1>
<?php
if(isset($_POST['submit']))
{ 
	$id = $_SESSION['mlmproject_user_id'];
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
	$message_to = $_REQUEST['to_id'];
	$num = user_exist($message_to);
	if($num != 0)
	{
		$msg_to = get_new_user_id($message_to);
		request_message($id,$title,$message,$msg_to);
		$position = get_user_position($id);
		data_logs($id,$position,$data_log[7][7],$data_log[6][1],$log_type[6]);
		print "Message send successfully!";
	}
	else { print "Please Enter Correct username !"; }	
}
else
{				
?>

<table border="0">
<form name="message" action="index.php?page=user-requests" method="post">
  <tr>
    <td class="form_label"><strong>Title</strong></td>
    <td><textarea name="title" style="height:20px; width:400px">
		</textarea>
	</td>
  </tr>
  <tr>
    <td class="form_label" valign=top><strong>Message</strong></td>
    <td class="form_data"><textarea name="message"  style="height:175px; width:400px">
		</textarea>
	</td>
  </tr>
  <tr>
    <td class="form_label"><strong>Message To</strong></td>
    <td class="form_data"><input type="text" name="to_id" class="input-medium" style="width:400px"></td>
  </tr>
  <tr>
    <td align="right" colspan="2"><input type="submit" value="Send" name="submit" class="normal-button" /></td>
  </tr>
</table>

<?php  }  ?>

