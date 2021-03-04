<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
if(isset($_POST['news_delete']))
{
$news_id=$_POST['news_id'];
query_execute_sqli("DELETE FROM `trading` WHERE id='$news_id'");
print "Successfully";
}
$newp = $_GET['p'];
if(isset($_REQUEST['news_no']))
{
	$qa = query_execute_sqli("select * from trading where id='".$_REQUEST['news_no']."'");
	echo "<a href='index.php?page=trading_list&p=$newp' class='btn btn-danger'><i class=\"fa fa-reply\"></i>Back</a><p>&nbsp;</p>";
	if(mysqli_num_rows($qa)!=0){$rowa=mysqli_fetch_array($qa);
	?>
		<div style="height:30px; text-align:left; padding-left:10px;">Date : <?=$rowa['date']; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Standard : <?=$rowa['standard']; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Top-Trade : <?=$rowa['toptrade']; ?></div>
		<div style="height:30px; text-align:left; padding-left:10px;">Statistics Title : <?=$rowa['title']; ?></div>
<?php
}else{echo "Not Found";}
}
else{
$plimit = "15";
$q = query_execute_sqli("select * from trading ");
$totalrows = mysqli_num_rows($q);
if($totalrows != 0)
{
?>
	<table width="100%" border="1" cellpadding="0" cellspacing="0">  
		<thead>
		<tr bgcolor="#CCCCCC">
			<th style="width:100px;" class="td_title">Date</th>
			<th class="td_title">Statistics Title</th>
		    <th style="width:100px;"  class="td_title">Standard</th>
			<th style="width:100px;"  class="td_title">Top-Trade</th>
			<th style="width:100px;"  class="td_title">Action</th>
		</tr>
		</thead>
	<?php	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$q1 = query_execute_sqli("select * from trading LIMIT $start,$plimit ");		
	
	while($id_row = mysqli_fetch_array($q1))
	{
		?>
		<tr>
			<th class="td_title"><?=$id_row['date']?></th>	
			
			<td class="td_title">
				<a href="index.php?page=trading_list&p=<?=$newp?>&news_no=<?=$id_row['id']?>">
					<?=$id_row['title']?>
				</a>
			</td>
			<td class="td_title">
				<a href="index.php?page=trading_list&p=<?=$newp?>&news_no=<?=$id_row['id']?>">
					<?=$id_row['standard']?>
				</a>
			</td>
			<td class="td_title">
				<a href="index.php?page=trading_list&p=<?=$newp?>&news_no=<?=$id_row['id']?>">
					<?=$id_row['toptrade']?>
				</a>
			</td>
			<td class="td_title">
			<form action="index.php?page=trading_list&p=<?=$newp?>" method="post">
			    <input type="hidden" name="news_id" value="<?=$id_row['id']?>"  /> 
				<input style="background-color:transparent; color:red; text-decoration:underline; border:0;" type="submit" name="news_delete" value="Delete" />
 			</form>
		   </td>
		</tr>	
		<?php		
	}
print "<tr><td colspan=5>&nbsp;</td></tr>
	   <tr><td colspan=5 height=30px width=400 class=\"box-grey\">&nbsp;&nbsp;<strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=trading_list&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=trading_list&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=trading_list&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr>";
}
else 
{ 
   print "<tr><td colspan=\"5\" width=200 class=\"td_title\">There is no Investment to show !</td></tr>";
  // unset($_SESSION['serch']); 
}	
}		 
?>  
</table>
