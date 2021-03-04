<?php
include('../security_web_validation.php');
?>
<?php
$user_id = $_SESSION['mlmproject_user_id'];
$allowedfiletypes = array("jpg");
$uploadfolder = $payment_receipt_img_full_path;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;


if(isset($_POST['Submit']) and $_SESSION['receipt_upload'] == 1)
{
	$table_id = $_POST['table_id'];
		
	$unique_time = time();
	$unique_name =	"NP".$unique_time.$user_id;
	$uploadfilename = $_FILES['payment_receipt']['name'];
	
		if(!empty($_FILES['payment_receipt']['name']))
		{
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
			if (!in_array($fileext,$allowedfiletypes)) 
			{
				print "<div style=\"text-align:center; color:#FF0000;font-size:12pt;\">Upload Only JPG Formet</div><br />";
			}	
			else 
			{
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				if (copy($_FILES['payment_receipt']['tmp_name'], $fulluploadfilename))
				{ 
					query_execute_sqli("insert into upload_receipt (user_id , receipt , mode) values('$user_id', '$unique_name', 1)" );
				
					print "<div style=\"text-align:center; color:green;font-size:12pt;\">Receipt Upload Successfully</div><br />";
					
					$_SESSION['receipt_upload'] = 0;	
				}	
				else
				{
					echo "<script type=\"text/javascript\">";
					echo "window.location = \"index.php?page=receipt&pay_err=2\"";
					echo "</script>"; 
				}	
			}
		}
		else
		{
			print "<div style=\"text-align:center; color:#FF0000;font-size:12pt;\">Please Select Receipt</div><br />";	
		}	
}
else
{
$_SESSION['receipt_upload'] = 1;
?>

<center>
<table width="80%">
	<form action="" method="post" enctype="multipart/form-data">
	  <tr>
	  	<th>Upload Your Payment Receipt</th>
		<td width="50%">
			<input type="file" name="payment_receipt" size="2" style="background-color:#d8fbcc;" />
		</td>
		<td>
			<input type="submit" name="Submit" value="Upload" class="normal-button" style="display:inline;"/>
		</td>
	 </tr>
	</form>
</table>		  
</center>

<?php
}
?>