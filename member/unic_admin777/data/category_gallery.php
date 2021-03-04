<?php
include('../../security_web_validation.php');
session_start();

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['delete_catg'])){
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `gallery_category` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `gallery_category` DROP `id`");
	query_execute_sqli("ALTER TABLE `gallery_category` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (id)");
	
	?> <script>alert("Category Delete Successfully!"); window.location="index.php?page=<?=$val?>";</script> <?php		
}

if(isset($_POST['update'])){
	$table_id = $_POST['table_id'];
	$category = $_POST['category'];
	$date = date('Y-m-d H:i:s');
	
	if($category != ""){
		$sql = "UPDATE `gallery_category` SET `category` = '$category' , `date` = '$date' WHERE id = '$table_id'";
		query_execute_sqli($sql);
		?> <script>alert("Category Edit Successfully!"); window.location="index.php?page=<?=$val?>";</script> <?php
	}
	else{ echo "<B class='text-danger'>Please fill Category field !!</B>"; }
}
elseif(isset($_POST['edit_catg'])){
	$table_id=$_POST['table_id'];
	$sql = "select * from gallery_category where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){  
		$id = $row['id'];
		$category = $row['category'];
	}
	?>
	<form name="message" action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="table_id" />
	<table class="table table-bordered">
		<tr>
			<th>Category</th>
			<td><input type="text" name="category" value="<?=$category;?>" class="form-control" /></td>
			<td><input type="submit" name="update" value="Update" class="btn btn-info"/></td>
		</tr>
	</table>
	</form> <?php
}
else{ 
	$sql = "SELECT * FROM gallery_category";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(id) num FROM gallery_category";
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
				<th class="text-center">Category</th>
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
			while($row = mysqli_fetch_array($query))
			{  
				$id = $row['id'];
				$category = $row['category'];
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$category;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer;" value="" name="edit_catg" title="Edit This Category" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;   border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer;"  name="delete_catg" title="Delete This Category" />
						</form>
					</td>
				</tr> <?php	
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no Category Found !!</b>";}
} ?>

