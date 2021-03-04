<?php
include('../../security_web_validation.php');
session_start();

$newp = $_GET['p'];
$plimit = "20";



if(isset($_SESSION['cat_edit_succ']))
{
	echo $_SESSION['cat_edit_succ'];
	unset($_SESSION['cat_edit_succ']);
}

if(isset($_POST['delete_quote']))
{
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `quote` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `quote` DROP `id`");
	query_execute_sqli("ALTER TABLE `quote` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
			
	echo "<B class='text-success'>Quote Delete Successfully !!</B>";
}

if(isset($_POST['update']))
{
	$table_id = $_POST['table_id'];
	$title = $_POST['title'];
	$quotes = $_POST['quotes'];
	$date = date('Y-m-d H:i:s');
	
	if($title != "" and $quotes != "")
	{
		$sql = "UPDATE `quote` SET `title` = '$title',`quotes` = '$quotes' , 
		`date` = '$date' WHERE id = '$table_id'";
		query_execute_sqli($sql);
		
		$_SESSION['cat_edit_succ'] = "<B class='text-success'>Quote Edit Successfully !!</B>";
		?> <script>window.location = "index.php?page=quote"; </script> <?php
	}
	else{ echo "<B class='text-danger'>Please fill all field !!</B>"; }
}
elseif(isset($_POST['edit_quote']))
{
	$table_id=$_POST['table_id'];
	$sql = "select * from quote where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{  
		$id = $row['id'];
		$title = $row['title'];
		$quotes = $row['quotes'];
	}
	?>
	<form name="message" action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="table_id" />
	<table class="table table-bordered">
		<tr>
			<th>Title</th>
			<td><input type="text" name="title" value="<?=$title;?>" class="form-control" /></td>
		</tr>
		<tr>
			<th>Quote</th>
			<td><textarea name="quotes" class="form-control"><?=$quotes;?></textarea></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="update" value="Update" class="btn btn-info"/>
			</td>
		</tr>
	</table>
	</form>
<?php
}
else
{ 
	$sqli = "select * from quote";
	$query = query_execute_sqli($sqli);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr no.</th>
				<th class="text-center">Title</th>
				<th class="text-center">Quotes</th>
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
			$query = query_execute_sqli("$sqli LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($query))
			{  
				$id = $row['id'];
				$title = $row['title'];
				$quotes = $row['quotes'];
				$date = date('d/m/Y' , strtotime($row['date']));
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$title;?></td>
					<td><?=$quotes;?></td>
					<td><?=$date;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit"  style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer" value="" name="edit_quote" title="Edit This Quote" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px;   border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer" name="delete_quote" title="Delete This Quote" />
						</form>
					</td>
				</tr> <?php	
				 $sr_no++;
			} ?>
		</table> <?php
		pagging_admin_panel($newp,$pnums,$val); 
	}
	else{ echo "<B class='text-danger'>There are no quotes Found !!</b>";}
} ?>

