<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/message.php");
include("function/setting.php");

include("function/send_mail.php");

$user_id = $_SESSION['mlmproject_user_id'];
?>

<!--<h1 align="left">Request to Admin</h1>-->
<?php
if(isset($_POST['submit']))
{ 
	$title = $_REQUEST['title'];
	$message = $_REQUEST['message'];
		$q = query_execute_sqli("select * from admin ");
		while($r = mysqli_fetch_array($q))
		{
			$message_to = $r['email'];
		}
		
		query_execute_sqli("insert into callback_request (user_id , title , message , date ) values ('$user_id' , '$title' , '$message' , '$systems_date') ");
		
		$full_message = "Message :".$message."<br><br> From : ".$_SESSION['mlmproject_user_name'];
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $message_to, $title, $full_message);
		$SMTPChat = $SMTPMail->SendMail();
		
		$title = 'Message';
		$message = 'Message E-mailed To Admin';
		data_logs($user_id,$title,$message,0);
		print "Message send successfully!";
}
else
{				
?>

<table class="display" align=center hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
<form name="message" action="index.php?page=callback_request" method="post">
  <tr>
    <td class="form_label"><strong>Title</strong></td>
    <td><input type="text" name="title" style="height:20px; width:400px"  />
	</td>
  </tr>
  <tr>
    <td class="form_label" valign=top><strong>Message</strong></td>
    <td><textarea name="message"  style="height:175px; width:400px"></textarea></td>
  </tr>
  <tr>
    <td align="right" colspan="2"><input type="submit" value="Send" name="submit" class="normal-button"/></td>
  </tr>
</table>

<?php  }  ?>

