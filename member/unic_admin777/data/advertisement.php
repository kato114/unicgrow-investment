<?php
include('../../security_web_validation.php');
?>
<p align="right"><strong><a href="index.php?page=add_ads">Add New</a></strong></p>
<?php 
$sql = "SELECT * FROM advertise";
$res = query_execute_sqli($sql);
$num = mysqli_num_rows($res);
if($num > 0)
{
	//$row=$mysqli_fetch_array($result);
	?>
		<table width="100%" border="1" cellpadding="0" cellspacing="0">
			<tr>
				<th class="text-center">ID</th>
				<th class="text-center">Advertisement Title</th>
				<th class="text-center">Advertisement</th>
				<th class="text-center">Advertisement Subject</th>
				<th class="text-center">Advertisement Date</th>
				<th class="text-center">Description</th>
				<th class="text-center">Action</th>
			</tr>
		<?php
			$sr_no = 1;
			while($row=mysqli_fetch_array($res))
			{ ?>
				<tr>
					<td><?=$sr_no;?></td>
					<td><?=$row['ad_title'];?></td>
					<td height="100px" width="250px">
						<img src="../images/advertisement/<?=$row['ad_image'];?>" height="100px" width="250px" />
					</td>
					<td><?=$row['ad_subject'];?></td>
					<td><?=$row['ad_date'];?></td>
					<td><?=$row['ad_desc'];?></td>
					<td align="center">
						<strong>
							<a href="index.php?page=update_ads&id=<?=$row['id']; ?>">Edit</a>&nbsp;&nbsp;||&nbsp;&nbsp;
							<a href="index.php?page=delete_ads&id=<?=$row['id']; ?>">Delete</a>
						</strong>
					</td>
				</tr>
	<?php 	$sr_no++;
			}?>
	</table>
<?php 
}
else{ echo "There are no information to show!!";}
?>