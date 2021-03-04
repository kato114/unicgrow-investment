<?php
include('../../security_web_validation.php');
?>
<?PHP
session_start();
if(isset($_POST['delete_catg']))
{
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `text` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `text` DROP `id`");
	query_execute_sqli("ALTER TABLE `text` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
	
	?> <script>alert('Delete Successfully !'); window.location="index.php?page=text_list";</script> <?php		
}

if(isset($_POST['update']))
{
	$cat_id = $_POST['cat_id'];
	$promotion_text = mysqli_real_escape_string($con,$_REQUEST['promotion_text']);
	$date = date('Y-m-d');
	
	if($promotion_text != "")
	{
		$sql = "UPDATE `text` SET `promotion_text` = '$promotion_text', `date` = '$date' WHERE id = '$cat_id'";
		query_execute_sqli($sql);
		
		?> <script>alert('Text Edit Successfully !'); window.location="index.php?page=text_list";</script> <?php		
	}
	else{ ?> <script>alert('Please fill all field !'); window.location="index.php?page=text_list";</script><?php }
}
elseif(isset($_POST['edit_catg']))
{
	$table_id=$_POST['table_id'];
	$sql = "select * from text where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{  
		$id = $row['id'];
		$promotion_text = $row['promotion_text'];
	}
	?>
	<form name="request" action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="cat_id" />
	<table class="table table-bordered">
		<tr>
			<th>Promotional Text</th>
			<td><textarea name="promotion_text" class="form-control"><?=$promotion_text;?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="update" value="Update" class="btn btn-info" />
			</td>
		</tr>
		<tr><th colspan="2" class="text-danger">Example: #username , #f_name , #l_name , #email , #phone_no</th></tr>
	</table>
	</form>
	
<?php
}
else
{ 
	$sqli = "select * from text";
	$query = query_execute_sqli($sqli);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{ ?>
		<table class="table table-bordered">
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">Promotion Text</th>
				<th class="text-center">Action</th>
			</tr>
			<?php
			$sr_no = 1;
			while($row = mysqli_fetch_array($query))
			{  
				$id = $row['id'];
				$promotion_text = $row['promotion_text'];
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$promotion_text;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer" value="" name="edit_catg" title="Edit This Category" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;   border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer"  name="delete_catg" title="Delete This Category" />
						</form>
					</td>
				</tr> <?php	
				 $sr_no++;
			} ?>
		</table> <?php
	}
	else{ echo "<B class='text-danger'>There are no Text Found !!</b>";}
} ?>

