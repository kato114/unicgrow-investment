<?php
include('../../security_web_validation.php');
session_start();


$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $gallery_folder;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

if(isset($_POST['Submit'])){ 
	$catg_id = $_POST['category'];
	$title = $_POST['title'];
	$date = date('Y-m-d', strtotime($_POST['date']));
	$time = date('H:i:s');
	
	for ($i = 0; $i < count($_FILES['photo']); $i++){
		$unique_name =	"CN".time().$i;
		$uploadfilename = $_FILES['photo']['name'][$i];
	
		if(!empty($_FILES['photo']['name'][$i])){
			 if (($_FILES["file"]["size"][$i] < 100000)){ //Approx. 100kb files can be uploaded.
				$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
				if (!in_array($fileext,$allowedfiletypes)){ echo "<B style='color:#FF0000;'>Invalid Extension</B>"; }
				else{
					$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
					if (copy($_FILES['photo']['tmp_name'][$i], $fulluploadfilename)){ 
						$image = $unique_name.".".$fileext; 
					
						$sql = "INSERT INTO gallery (catg_id , title , image , date , time) 
						VALUES ('$catg_id' , '$title' , '$image' , CURDATE() , CURTIME())";
						query_execute_sqli($sql);
						?> <script>
							alert("Images Add Successfully!"); window.location="index.php?page=<?=$val?>";
						</script> <?php
					}
				}
			}
			else{ echo "<B class='text-danger'>Invalid file Size or Type!</B>"; }
		}
	}
}	

else
{ ?>
<form action="" method="post" enctype="multipart/form-data">
<table class="table table-bordered">
	<tr>
		<th>Category</th>
		<td>
			<select name="category" class="form-control">
				<option value="">Select Category</option>
				<?php
				$sqli = "SELECT * FROM gallery_category";
				$query = query_execute_sqli($sqli);
				while($row = mysqli_fetch_array($query)){ 
					$id = $row['id'];
					$category = $row['category'];
					?>
					<option value="<?=$id?>"><?=$category?></option>
					<?php
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th>Title</th>
		<td><input type="text" name="title" class="form-control" required /></td>
	</tr>
	<!--<tr>
		<th>Date</th>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" class="form-control" />
				</div>
			</div>
		</td>
	</tr>-->
	<tr>
		<th>Image</th>
		<td><input type="file" name="photo[0]" multiple /></td>
	</tr>
	<!--<tr>
		<th>Image 2</th>
		<td><input type="file" name="photo[1]" /></td>
	</tr>
	<tr>
		<th>Image 3</th>
		<td><input type="file" name="photo[2]" /></td>
	</tr>
	<tr>
		<th>Image 4</th>
		<td><input type="file" name="photo[3]" /></td>
	</tr>
	<tr>
		<th>Image 5</th>
		<td><input type="file" name="photo[4]" /></td>
	</tr>-->
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="Submit" class="btn btn-info" value="Submit" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

