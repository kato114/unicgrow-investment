<?php
include('../../security_web_validation.php');

session_start();

$newp = $_GET['p'];
$plimit = 20;

if(isset($_POST['news_delete']))
{
	$news_id=$_POST['news_id'];
	query_execute_sqli("DELETE FROM `news` WHERE id = '$news_id'");
	print "Successfully";
}

if(isset($_REQUEST['news_no']))
{ ?>
	<p style='text-align:left;'><a href='index.php?page=pop_nlist&p=<?=$newp?>' class='btn btn-info'>Back</a></p>
	
	<?php
	$qa = query_execute_sqli("select * from news where id='".$_REQUEST['news_no']."'");
	if(mysqli_num_rows($qa) > 0)
	{ ?>
		<div class="row">
		<?php
		while($rowa=mysqli_fetch_array($qa)){
			?>
			<div class="col-md-12">Title : <?=$rowa['title']?></div>
			<div class="col-md-12">Date : <?=$rowa['date']?></div>
			<div class="col-md-12">Message : <?=$rowa['news']?></div> <?php
		} ?>
		</div> <?php
	}else{echo "Not Found";}
}
else
{
	$SQL = "SELECT * FROM news";
	$q = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($q);
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Date</th>
				<th class="text-center">Title</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<?php	
			$pnums = ceil($totalrows/$plimit);
			if($newp == ''){ $newp = '1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$q1 = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
			while($row = mysqli_fetch_array($q1))
			{ 
				$id = $row['id'];
				$date = date('d/m/Y', strtotime($row['date']));
				$title = $row['title']; ?>
				
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$date?></td>	
					<td><a href="index.php?page=pop_nlist&p=<?=$newp?>&news_no=<?=$id?>"><?=$title?></a></td>
					<td>
					<form action="index.php?page=pop_nlist&p=<?=$newp?>" method="post">
						<input type="hidden" name="news_id" value="<?=$id?>"  /> 
						<input type="submit" name="news_delete" value="Delete" class="btn btn-info" />
					</form>
				   </td>
				</tr>	
				<?php	
				$sr_no++;	
			} ?>
		</table> <?php
		pagging_admin_panel($newp,$pnums,$val); 
	}
	else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
}?>
