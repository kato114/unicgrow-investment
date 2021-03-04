<?php
include('../security_web_validation.php');
?>
<?PHP
session_start();
include("condition.php");
include("function/setting.php");

include("function/send_mail.php");

$login_id = $_SESSION['mlmproject_user_id'];

$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $user_support_folder;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

if(isset($_SESSION['tickt_succ']))
{
	echo $_SESSION['tickt_succ'];
	unset($_SESSION['tickt_succ']);
}

if(isset($_SESSION['com_ad_succ']))
{
	echo $_SESSION['com_ad_succ'];
	unset($_SESSION['com_ad_succ']);
}


if(isset($_POST['Submit']))
{ 
	$catg_id = $_REQUEST['catg_id'];
	$title = $_REQUEST['my_ticket'];
	$message = $_REQUEST['comment'];
	
	$date = date('Y-m-d H:i:s');
	$unique_id = substr(md5(rand(0, 1000000)), 0, 10);
	$ip_add =  $_SERVER['REMOTE_ADDR'];
	if(isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]){
		if(!empty($_FILES['file']['name'])){
			$unique_time = time();
			$unique_name =	"NP".$unique_time;
			$uploadfilename = $_FILES['file']['name'];
		
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
			
			if(!in_array($fileext,$allowedfiletypes)){
				echo "<B class='text-danger'>Invalid Extension</B>";
			}
			else{
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				if(copy($_FILES['file']['tmp_name'], $fulluploadfilename)){ 
					$unique_name = $unique_name.".".$fileext;
				}
			}
		}
		
		$sql = "Insert into my_ticket (user_id , catg_id , title , date , unique_id) 
		values('$login_id' , '$catg_id' , '$title' , '$date' , '$unique_id') ";
		query_execute_sqli($sql);
		
		$ticket_id = get_mysqli_insert_id();
		
		$sqls = "INSERT INTO my_ticket_message (user_id , ticket_id , message , message_by , 
		ip_address , date , unique_id , file) VALUES('$login_id' , '$ticket_id' , '$message' , 'user' , 
		'$ip_add' , '$date','$unique_id' , '$unique_name')";
		query_execute_sqli($sqls);
		
		
		$query = query_execute_sqli("select email from my_ticket_categry where id = '$catg_id'");
		$row = mysqli_fetch_array($query);
		
		/*if(strtoupper($soft_chk) == "LIVE"){
			//support message
			include("email_letter/support_msg.php");
			$to = $email;
			//include("function/full_message.php");
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,
			$title, $db_msg);	
			
			$to_user = get_user_email($login_id);
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to_user, $title, $db_msg);
			//End email message
		}*/
		
		$_SESSION['tickt_succ'] = "<B class='text-success'>Support Ticket Generate Successfully !!</B>";
		
		if($_POST['tickt_from'] == 'wel_main'){
		?> <script>window.location = "index.php?page=support_ticket";</script> <?php 
		}
		?> <script>window.location = "index.php?page=support";</script> <?php 
	}
	else{ echo "<B class='text-danger'>Please Enter correct Code !!</B>"; }
} ?>
	<form name="create_catg" action="" method="post" enctype="multipart/form-data">
	<table class="table table-bordered table-hover">
		<tr>
			<th>Category</th>
			<td>
				<select name="catg_id" class="form-control">
					<option value="">Category</option>
					<?php
					$sql = "select * from my_ticket_categry";
					$query = query_execute_sqli($sql);
					while($row = mysqli_fetch_array($query))
					{  
						$id = $row['id'];
						$category = $row['category']; ?>
						<option value="<?=$id;?>"><?=$category;?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Subject</th>
			<td><input type="text" name="my_ticket" class="form-control" /></td>
		</tr>
		<tr>
			<th>Message</th>
			<td><textarea name="comment" cols="20" rows="3" class="form-control"></textarea></td>
		</tr>
		<!--<tr>
			<th>Image</th>
			<td><input class="form-control" type="file" name="file" /></td>
		</tr>-->
		<tr>
			<th>Security Code</th>
			<td>
				<div style="float:left;"><input type="password" name="captcha" class="form-control" /> </div>
				<div style="float:left; margin:0.5% 50% 0 0;">&nbsp;<img src="captcha.php" /></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="Submit" class="btn btn-info" value="Create" />
			</td>
		</tr>
	</table>
	</form>
