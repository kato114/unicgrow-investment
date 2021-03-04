<?php
include('../../security_web_validation.php');

$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $kyc_docs_path;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

if(isset($_POST['submit']))
{
	$user_id = $_REQUEST['user_id'];
	$image = $_POST['image'];
	$uploadfilename = $_FILES['image']['name'];
	
	$cnt = count($uploadfilename);
	for($i = 0; $i < $cnt; $i++)
	{
		if(!empty($_FILES['image']['name']))
		{
			$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
			if (!in_array($fileext,$allowedfiletypes)) 
			{
				$inval = 1;break;
			}
		}
	}
	if($inval == 1)
	{
		echo "<B style='color:#FF0000;'>Invalid Extension!!</B>";
	}
	else
	{
		for($i = 0; $i < $cnt; $i++)
		{	
			$_FILES['image']['name'][$i] = $uploadfilename[$i];
			if(!empty($_FILES['image']['name']))
			{
				$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
				if (!in_array($fileext,$allowedfiletypes)) 
				{
					print "Invalid Extension";
				}
				else 
				{
					$unique_time = time();
					$unique_name =	"NP".$unique_time.$user_id.$i;
					
					$fulluploadfilename = '';
					$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
					$unique_name = $unique_name.".".$fileext;
					
					if (copy($_FILES['image']['tmp_name'][$i], $fulluploadfilename))
					{ 	
						$sep = "";
						
						switch($i)
						{
							case '0' : $field = 'pan_card'; break;
							case '1' : $field = 'id_proof'; break;
							//case '2' : $field = 'photo'; break;
						}
						
						if($i == 0 or ($cnt-2 == $i))
						$sep = ",";
						$img .= $field."='".$unique_name."'".$sep;
					}
				}
			}
			else
			{ print "Please Select Image !!"; }
		}
		//$sql = "UPDATE kyc SET $img , date = '$cur_date' WHERE user_id = '$user_id' ";
		$sql = "UPDATE kyc SET $img WHERE user_id = '$user_id' ";
		query_execute_sqli($sql);
		
		$_SESSION['IMG_UPLOAD'] = "<B style='color:#008000;'>Images Successfully Upload !!</B>";
		
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=kyc\"";
		echo "</script>"; 
	}
}	


$member_id = $_REQUEST['User_id'];

$SQL = "SELECT * FROM kyc WHERE user_id = '$member_id'";
$query = query_execute_sqli($SQL);
while($row = mysqli_fetch_array($query))
{
	$user_id = $row['user_id'];
	$id_proof = $row['id_proof'];
	$pan_card = $row['pan_card'];
	
	$img_pan = "<img src='../images/mlm_kyc/$pan_card' style='vertical-align:middle' width='100' />";
	$img_id = "<img src='../images/mlm_kyc/$id_proof' style='vertical-align:middle' width='100' />";
	
} ?>
<form name="add_kyc_docs" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="user_id" value="<?=$user_id?>">
<table width="60%" border="0">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>PAN Card</td>
		<td><input type="file" name="image[]" class="form-control" width="50" required /></td>
		<td> <?=$img_pan?></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Aadhar Card</td>
		<td><input type="file" name="image[]" class="form-control" width="50" required /></td>
		<td> <?=$img_id?></td>
	</tr>
	<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Update" class="btn btn-info" /></td></tr>
</table>
</form>