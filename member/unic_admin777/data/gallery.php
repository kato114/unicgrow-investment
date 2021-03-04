<?php
include('../../security_web_validation.php');
session_start();

$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $gallery_folder;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['delete_image'])){
	$table_id = $_POST['table_id'];
	$image = $_POST['image'];
	query_execute_sqli("DELETE FROM `gallery` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `gallery` DROP `id`");
	query_execute_sqli("ALTER TABLE `gallery` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
	unlink($gallery_folder.$image);
			
	?> <script>alert("Image Delete Successfully!"); window.location="index.php?page=<?=$val?>";</script> <?php
}

if(isset($_POST['update'])){
	$table_id = $_POST['table_id'];
	$title = $_POST['title'];
	$images = $_POST['image'];
	$date = $_POST['date'];
	$time = date('H:i:s');

	$unique_name =	"CN".time();
	$uploadfilename = $_FILES['photo']['name'];
	
	if($title != "" and $date != ''){
		if(!empty($_FILES['photo']['name'])){
			unlink($gallery_folder.$images);
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
			if (!in_array($fileext,$allowedfiletypes)){ echo "<B class='text-danger'>Invalid Extension</B>"; }
			else{
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				if (copy($_FILES['photo']['tmp_name'], $fulluploadfilename))
				{ $image = $unique_name.".".$fileext; }
			}
		}
		else{
			$image = $images;
		}
		
		$sql = "UPDATE `gallery` SET `title`='$title',`image`='$image' ,`date`='$date' ,`time`='$time' 
		WHERE id = '$table_id'";
		query_execute_sqli($sql);
		?> <script>alert("Image Edit Successfully!"); window.location="index.php?page=<?=$val?>";</script> <?php
	}
	else{ echo "<B class='text-danger'>Please fill all field !!</B>"; }
}
elseif(isset($_POST['edit_image'])){
	$table_id=$_POST['table_id'];
	$sql = "select * from gallery where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){  
		$id = $row['id'];
		$title = $row['title'];
		$image = $row['image'];
		$date = $row['date'];
	}
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="hidden" value="<?=$id;?>" name="table_id" />
		<input type="hidden" value="<?=$image;?>" name="image" />
		<table class="table table-bordered">
			<tr>
				<th>Title</th>
				<td><input type="text" name="title" value="<?=$title?>" class="form-control" /></td>
			</tr>
			<tr>
				<th>Date</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" name="date" placeholder="Search By Date" class="form-control" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>Image</th>
				<td><input type="file" name="photo" /> <img src="../images/mlm_gallery/<?=$image?>" width="50" /> </td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">
					<input type="submit" name="update" class="btn btn-info" value="Update" />
				</td>
			</tr>
		</table>
	</form> <?php
}
else{ 
	$sql = "SELECT * FROM gallery";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">Title</th>
				<th class="text-center">Image</th>
				<th class="text-center">Date</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			if ($newp==''){ $newp='1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($query)){  
				$id = $row['id'];
				$title = $row['title'];
				$image = $row['image'];
				$date = date('d/m/Y' , strtotime($row['date']));
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$title;?></td>
					<td><img src="../images/mlm_gallery/<?=$image?>" width="50" /></td>
					<td><?=$date;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="hidden" value="<?=$image;?>" name="image" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer" value="" name="edit_image" title="Edit This Image" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;   border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer" name="delete_image" title="Delete This Image" />
						</form>
					</td>
				</tr> <?php	
				 $sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no info to show!!</B>";}
} ?>

