<?php
include('../security_web_validation.php');
?>
<?php
 $uid=$_SESSION['mlmproject_user_id'];
 $sql_ads="SELECT t1.*,t2.title,t2.image,t2.ad_url 
FROM add_clicks as t1
inner join advertisement as t2
on t1.add_id = t2.id
where t1.user_id = '$uid'";
$result_ads=query_execute_sqli($sql_ads);
$sql_num=mysqli_num_rows($result_ads);
if($sql_num > 0)
{
?>


<table class="table table-hover table-striped">
<thead>
<tr>
<th class="span1">Sr No.</th>
<th class="span1">Ad Title</th>
<th class="span1">Ad Image</th>
<th class="span1">Ad Url</th>
<th class="span1">Ad Desc</th>
<th class="span1">Clicked On</th>

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
<td  style="width:300px; height:200px;"><img width="300" height="200" src="images/advertisement/<?php echo $row_ads['image'];?>" /></td>
<td ><a  href="#"><?php echo $row_ads['ad_url'];?></a></td>
<td ><?php echo $row_ads['date'];?></td>
<td ><?php echo $row_ads['time'];?></td>

<td class="input-medium"><?php if($row_ads['mode']==0)
				{
				echo "Un Approved";
				}
				else
				{
				 echo "Approved";
				} ?></td>
</tr>
<?php $no++;}?>
</table>
<?php
}
else{
echo "<h2>There is no Information to show.</h2>";
}
?>