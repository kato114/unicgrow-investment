<?php
include('../security_web_validation.php');
?>
<?php
$uid=$_SESSION['mlmproject_user_id'];
$sql_ads="select * from advertisement";
$result_ads=query_execute_sqli($sql_ads);
$sql_num=mysqli_num_rows($result_ads);
if($sql_num > 0)
{
?>

<div><a href="index.php?page=add_advertisement" class="btn btn-primary">Add Advertisement</a></div>
<table class="table table-hover table-striped">
<thead>
<tr>
<th class="span1">Sr No.</th>
<th class="span1">Ad Title</th>
<th class="span1">Ad Image</th>
<th class="span1">Ad Url</th>
<th class="span1">Ad Desc</th>
<th class="span1">Ad Date</th>

<th class="span1">Mode</th>
</tr>
</thead>
<?php
$no=1;
while($row_ads=mysqli_fetch_array($result_ads))
{
?>
<tr>
<td ><?php echo $no;?></td>
<td ><?php echo $row_ads['title'];?></td>
<td  style="width:300px; height:200px;"><a  href="index.php?page=insert_add_click&add_id=<?php echo $row_ads['id'];?>"><img width="300" height="200" src="images/advertisement/<?php echo $row_ads['image'];?>" /></a></td>
<td ><a  href="#"><?php echo $row_ads['ad_url'];?></a></td>
<td ><?php echo $row_ads['description'];?></td>
<td ><?php echo $row_ads['date'];?></td>

<td class="input-medium"><?php if($row_ads['mode']==0)
				{
				echo "Un Approved";
				}
				else
				{
				 echo "Approved";
				} ?></td>
</tr>
<?php $no++;}

?>
</table>
<?php
}
else{

echo "<h2>There is no Information to show.</h2>";

}
?>