<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(isset($_POST['update'])){
	$table_id = $_POST['table_id'];
	$title = $_POST['title'];
	$video_link = $_POST['video_link'];
	
	$sql = "UPDATE `video` SET `title`='$title', `video_link`='$video_link', `date`=NOW() WHERE id='$table_id'";
	query_execute_sqli($sql);
	?> <script>alert("Link Successfully Edited!"); window.location = "index.php?page=<?=$val?>";</script> <?php
	$_SESSION['cat_gall_succ'] = "<B class='text-success'>Category Edit Successfully !!</B>";
	?> <script> window.location = "index.php?page=category_gallery";</script> <?php
}

if(isset($_POST['delete_video'])){
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `video` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `video` DROP `id`");
	query_execute_sqli("ALTER TABLE `video` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
			
	?> <script>alert("Link Successfully Deleted!"); window.location = "index.php?page=<?=$val?>";</script> <?php
}
elseif(isset($_POST['edit_video'])){
	$table_id=$_POST['table_id'];
	$sql = "SELECT * FROM video WHERE id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){  
		$id = $row['id'];
		$title = $row['title'];
		$video_link = $row['video_link'];
		$date = $row['date'];
	}
	?>
	<form name="message" action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="table_id" />
	<table class="table table-bordered">
		<thead><tr><th colspan="2">Edit Youtube Video Link</th></tr></thead>
		<tr>
			<th>Title</th>
			<td><input type="text" name="title" value="<?=$title?>" class="form-control" /></td>
		</tr>
		<tr>
			<th>Youtube Video Link</th>
			<td><input type="text" name="video_link" value="<?=$video_link?>" class="form-control" /></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="update" value="Update" class="btn btn-info" />
			</td>
		</tr>
	</table>
	</form> <?php
}
else{
	$sql = "SELECT * FROM video";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(id) num FROM gallery_category";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">Title</th>
				<th class="text-center">Video Link</th>
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
			$query = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($query)){  
				$id = $row['id'];
				$title = $row['title'];
				$video_link = $row['video_link'];
				$date = $row['date']; ?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$title?></td>
					<td><?=$video_link?></td>
					<td><?=$date?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer;" value="" name="edit_video" title="Edit This Video" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px; border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer;" name="delete_video" title="Delete This Video" onclick="javascript:return confirm(&quot; Are You Sure? You want to Delete This Link !! &quot;);" />
						</form>
					</td>
				</tr> <?php	
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no info Found !!</B>";}
}