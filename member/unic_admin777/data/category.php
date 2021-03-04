<?php
include('../../security_web_validation.php');

session_start();

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(isset($_SESSION['cat_edit_succ']))
{
	echo $_SESSION['cat_edit_succ'];
	unset($_SESSION['cat_edit_succ']);
}

if(isset($_POST['delete_catg']))
{
	$table_id=$_POST['table_id'];
	query_execute_sqli("DELETE FROM `my_ticket_categry` WHERE id = '$table_id'");
	query_execute_sqli("ALTER TABLE `my_ticket_categry` DROP `id`");
	query_execute_sqli("ALTER TABLE `my_ticket_categry` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY KEY (id)");
			
	echo "<B class='text-success'>Category Delete Successfully !!</B>";
}

if(isset($_POST['update']))
{
	$cat_id = $_POST['cat_id'];
	$category = $_POST['category'];
	$email = $_POST['email'];
	$date = date('Y-m-d H:i:s');
	
	if($category != "" and $email != "")
	{
		$sql = "UPDATE `my_ticket_categry` SET `category` = '$category',`email` = '$email' , 
		`date` = '$date' WHERE id = '$cat_id'";
		query_execute_sqli($sql);
		
		$_SESSION['cat_edit_succ'] = "<B class='text-success'>Category Edit Successfully !!</B>";
		?> <script> window.location = "index.php?page=category";</script> <?php
	}
	else{ echo "<B class='text-danger'>Please fill all field !!</B>"; }
}
elseif(isset($_POST['edit_catg']))
{
	$table_id=$_POST['table_id'];
	$sql = "select * from my_ticket_categry where id = '$table_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{  
		$id = $row['id'];
		$category = $row['category'];
		$email = $row['email'];
	}
	?>
	<form name="message" action="" method="post">
	<input type="hidden" value="<?=$id;?>" name="cat_id" />
	<table class="table table-bordered">
		<tr>
			<th>Category</th>
			<td><input type="text" name="category" value="<?=$category?>" class="form-control" /></td>
		</tr>
		<tr>
			<th>E-mail</th>
			<td><input type="text" name="email" value="<?=$email?>" class="form-control" /></td>
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
	$sql = "SELECT * FROM my_ticket_categry";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(id) num FROM my_ticket_categry";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	if($totalrows > 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. no.</th>
				<th class="text-center">Category</th>
				<th class="text-center">E-mail</th>
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
				$email = $row['email'];
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$category;?></td>
					<td><?=$email;?></td>
					<td>
						<form action="" method="POST">
							<input type="hidden" value="<?=$id?>" name="table_id" />
							<input type="Submit" value="" style="width:18px; height:18px; border:0;background-color:transparent; background-image:url(images/edit.png); cursor:pointer" name="edit_catg" title="Edit This Category" />
							<input type="Submit" value="" style="width:18px; height:18px; margin-left:5px; border:0;background-color:transparent; background-image:url(images/delete.png); cursor:pointer" name="delete_catg" title="Delete This Category" />
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