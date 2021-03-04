<?php
include('../../security_web_validation.php');
?>
<?php
$sql_ads="select * from advertisement where mode=1";
$result_ads=query_execute_sqli($sql_ads);

?>


<table width="80%" border="1">
<tr>
<th class="text-center">Sr No.</th>
<th class="text-center">Ad Title</th>
<th class="text-center">Ad Image</th>
<th class="text-center">Ad Url</th>
<th class="text-center">Ad Desc</th>
<th class="text-center">Ad Date</th>
<th class="text-center">User</th>
<th class="text-center">Active Mode</th>
<th class="text-center">Change Mode To</th>
</tr>
<?php
$no=1;
while($row_ads=mysqli_fetch_array($result_ads))
{
?>
<tr>
<td><?php echo $no;?></td>
<td><?php echo $row_ads['title'];?></td>
<td style=" width:300px; height:200px;"><img width="300" height="200" src="../images/advertisement/<?php echo $row_ads['image'];?>" /></td>
<td><a  href="#"><?php echo $row_ads['ad_url'];?></a></td>
<td><?php echo $row_ads['description'];?></td>
<td><?php echo $row_ads['date'];?></td>
<td><?php if($row_ads['user_id']==0)
				{
				echo "Admin";
				}
				else
				{
				 echo $_SESSION['mlmproject_user_name'];
				}
?></td>
<td><?php if($row_ads['mode']==0)
				{
				echo "Un Approved";
				}
				else
				{
				 echo "Approved";
				} ?></td>
				
	  <td>
	  <a 
	  href="index.php?page=change_mode_ads&add_id=<?php echo $row_ads['id'];?>&mode=<?php echo $row_ads['mode'];?>">
	  
	  <?php
	  if($row_ads['mode']==1)
	  {
	   echo "Un Approve";
	  }
	  else
	  {
	  echo "Approve";
	  }
	  
	  ?>
	  
	  </a>
	  </td>			
</tr>
<?php $no++;}?>
</table>