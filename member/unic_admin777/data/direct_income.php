<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$newp = $_GET['p'];
$plimit = "25";

$SQL = "select *,SUM(amount) amt from income where type = 1 GROUP BY user_id ";
$q = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($q);

if($totalrows > 0)
{ ?>	
	<table align="center" hspace=0 cellspacing=0 cellpadding=0 border=0 width=95%>
		<thead>
		<tr>
			<th class="text-center">User ID</th>
			<th class="text-center">BY ID</th>
			<th class="text-center">Investment</th>
			<th class="text-center">Date</td>
		</tr>
		</th>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
		while($row = mysqli_fetch_array($query))
		{
			$user_id = get_user_name($row['user_id']);
			$income_by = get_user_name($row['incomed_id']);
			$amount = $row['amt'];
			$date = date('d/m/Y', strtotime($row['date']));
			?>
			<tr align="center">
				<td class="input-small"><small><?=$user_id?></small></td>
				<td><small><?=$income_by?> </small></td>
				<td><small><?=$amount?> &#36;</small></td>
				<td><small><?=$date?></small></td>
			</tr> <?php
		} ?>
		<tr><td colspan=5>&nbsp;</td></tr>
		<tr>
			<td colspan=5 height=30 class="text-center">
			<B>
				<?php
				if ($newp > 1)
				{ ?> <a href="<?="index.php?page=direct_income&p=".($newp-1);?>">&laquo;</a> <?php }
				for ($i = 1; $i <= $pnums; $i++) 
				{ 
					if ($i != $newp)
					{ ?> <a href="<?="index.php?page=direct_income&p=$i";?>"><?php print_r("$i");?></a><?php  }
					else
					{ print_r("$i"); }
				} 
				if ($newp < $pnums) 
				{ ?> <a href="<?="index.php?page=direct_income&p=".($newp+1);?>">&raquo;</a> <?php   } ?>
			</B>
			</td>
		</tr>
	</table>
<?php
} 
else{  echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>