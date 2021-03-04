<?php
function tree_member($level,$id)
{	
	$field = $qu = $group_by = "";
	for($i = 1; $i < $level+1; $i++)
	{
		$j = $i+1;
		if($i==1){
			$field .= "t$j.username AS lev$i,t$j.id_user AS lev$i"."_id";
			$group_by .= "t$i.id_user";
		}
		else{
			$field .= ",t$j.username AS lev$i,t$j.id_user AS lev$i"."_id";
			$group_by .= ",t$i.id_user";
		}
			
		$qu .= "LEFT JOIN users AS t$j"." ON t$j."."real_parent = t$i.id_user ";
		 
	}
	$pp = $level+1;
	 $sql = "select ".$field." from users as t1 ".$qu." where t1.id_user='$id' group by ".$group_by.",t$pp.id_user";
	/*$sql = "select t2.username as lev1,t3.username as lev2,t4.username as lev3,t5.username as lev4
				from users as t1
				LEFT JOIN users AS t2 ON t2.real_parent = t1.id_user
				LEFT JOIN users AS t3 ON t3.real_parent = t2.id_user  
				LEFT JOIN users AS t4 ON t4.real_parent = t3.id_user
				LEFT JOIN users AS t5 ON t5.real_parent = t4.id_user
				where t1.id_user='$id'
				group by t1.id_user, t2.id_user, t3.id_user, t4.id_user, t5.id_user
				";*/
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) > 0)
	{	
		$level1_array = array();
		$level2_array = array();
		$level3_array = array();
		$level4_array = array();
		echo "<table width=\"750\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
		?>	<tr>
				<td rowspan="2"><img src="images/Icon_o.gif" /></td>
				<td colspan="4" align="center" style="font-weight:bold; color:#000070;font-size: 11pt;"><?=get_username($id);?>&nbsp;<img src="images/genealogy.gif" align="bottom" /> &nbsp; Downline</td>
			</tr>
			<tr><td align="left" colspan="4">Your Downline is listed below for up to <?=$level;?> levels. Click any member's name to view up to <?=$level;?> more levels below them. The purple dates below are when the member joined.</td></tr>
			<tr><td colspan="5" align="center">
			<div>
<form action=""  method="post" lang="en" runat="server">
<select name="level">
<?php
for($i = 1; $i < 5; $i++)
{
?>
	<option value="<?=$i;?>" <?php if($level == $i){print 'selected="selected"';} ?> ><?=$i;?> Level</option>

<?php
}
?>
</select>
<input type="submit" value="View" name="view" id="view" onClick="viewmember()" />
</form>
</div>
			</td></tr>	
		<?php
		while($row = mysqli_fetch_array($query))
		{
			$lev1 = $row['lev1'];
			$lev2 = $row['lev2'];
			$lev3 = $row['lev3'];
			$lev4 = $row['lev4'];
			if($lev1 == NULL)
			{
				echo "<tr><td>You Have No Down-Line</td></tr>";
			}
			else{
				if(!in_array($lev1,$level1_array))
				{
					if($lev1 != NULL){
						$level_id = $row['lev1_id'];
						echo "<tr><td width=6%>Level 1</td>
								<td colspan=4>
									<a href=# onclick=module(".$level_id.",1,$level) >".$lev1."</a>
								</td>
							</tr>";
						$level1_array[] = $row['lev1'];
					}	
				}
				
				
				if(!in_array($lev2,$level2_array))
				{
					if($lev2 != NULL)
					{				
						$level_id = $row['lev2_id'];
						echo "
								<tr>
									<td width=6%>Level 2</td>
									<td>&nbsp;</td>
									<td colspan=3><a href=# onclick=module(".$level_id.",2,$level) >".$lev2."</a></td>
								</tr>";
						$level2_array[] = $row['lev2'];
					}
				}	
				if(!in_array($lev3,$level3_array))
				{
					if($lev3 != NULL)
					{
						$level_id = $row['lev3_id'];
						echo "
								<tr>
									<td width=6%>Level 3</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan=2><a href=# onclick=module(".$level_id.",3,$level) >".$lev3."</a></td>
								</tr>";
						$level3_array[] = $row['lev3'];
					}
				}
				if(!in_array($lev4,$level4_array))
				{
					if($lev4 != NULL)
					{
						$level_id = $row['lev4_id'];
						echo "
								<tr>
									<td width=6%>Level 4</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a href=# onclick=module(".$level_id.",4,$level) >".$lev4."</a></td>
								</tr>";	
						$level4_array[] = $row['lev4'];
					}
				}
			}
		}
		echo "</table>";
	}
	else
	{
		echo "You Have No Down-Line";
	}
	
}

function ajax_tree_member($level,$step_lev_inc,$id)
{	
	
	$field = $qu = $group_by = "";
	for($i = 1; $i < $level+1; $i++)
	{
		$j = $i+1;
		if($i==1){
			$field .= "t$j.username AS lev$i,t$j.id_user AS lev$i"."_id";
			$group_by .= "t$i.id_user";
		}
		else{
			$field .= ",t$j.username AS lev$i,t$j.id_user AS lev$i"."_id";
			$group_by .= ",t$i.id_user";
		}
			
		$qu .= "LEFT JOIN users AS t$j"." ON t$j."."real_parent = t$i.id_user ";
		 
	}
	$pp = $level+1;
	 $sql = "select ".$field." from users as t1 ".$qu." where t1.id_user='$id' group by ".$group_by.",t$pp.id_user";
	/*$sql = "select t2.username as lev1,t3.username as lev2,t4.username as lev3,t5.username as lev4
				from users as t1
				LEFT JOIN users AS t2 ON t2.real_parent = t1.id_user
				LEFT JOIN users AS t3 ON t3.real_parent = t2.id_user  
				LEFT JOIN users AS t4 ON t4.real_parent = t3.id_user
				LEFT JOIN users AS t5 ON t5.real_parent = t4.id_user
				where t1.id_user='$id'
				group by t1.id_user, t2.id_user, t3.id_user, t4.id_user, t5.id_user
				";*/
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) > 0)
	{	
		$level1_array = array();
		$level2_array = array();
		$level3_array = array();
		$level4_array = array();
		echo "<table width=100%>";
		?>
			<tr>
				<td rowspan="2"><img src="images/Icon_o.gif" /></td>
				<td colspan="4" align="center" style="font-weight:bold; color:#000070;font-size: 11pt;"><?=get_username($id);?>&nbsp;<img src="images/genealogy.gif" align="bottom" /> &nbsp; Downline</td>
			</tr>
			<tr><td align="left" colspan="4">Your Downline is listed below for up to 4 levels. Click any member's name to view up to 4 more levels below them. The purple dates below are when the member joined.</td></tr>
			<tr><td colspan="5" align="center">
			<div>
<form action=""  method="post" lang="en" runat="server">
<select name="level">
<?php
for($i = 1; $i < 5; $i++)
{
?>
	<option value="<?=$i;?>" <?php if($level == $i){print 'selected="selected"';} ?> ><?=$i;?> Level</option>

<?php
}
?>
</select>
<input type="submit" value="View" name="view" id="view" onClick="viewmember()" />
</form>
</div>
			</td></tr>		
		<?php
		while($row = mysqli_fetch_array($query))
		{	
			$step_lev = '';
			$lev1 = $row['lev1'];
			$lev2 = $row['lev2'];
			$lev3 = $row['lev3'];
			$lev4 = $row['lev4'];
			if($lev1 == NULL)
			{
				echo "<tr><td>You Have No Down-Line</td></tr>";
			}
			else{	
				if(!in_array($lev1,$level1_array))
				{
					if($lev1 != NULL){
						$level_id = $row['lev1_id'];
						$step_lev = $step_lev_inc + 1;
						echo "<tr><td width=6%>Level ($step_lev)</td><td colspan=4><a href=# onclick=module(".$level_id.",1,$level) >".$lev1."</a></td></tr>";
						$level1_array[] = $row['lev1'];
					}	
				}
				
				
				if(!in_array($lev2,$level2_array))
				{
					if($lev2 != NULL)
					{				
						$level_id = $row['lev2_id'];
						$step_lev = $step_lev_inc + 2;
						echo "
								<tr>
									<td width=6%>Level ($step_lev)</td>
									<td>&nbsp;</td>
									<td colspan=3><a href=# onclick=module(".$level_id.",2,$level) >".$lev2."</a></td>
								</tr>";
						$level2_array[] = $row['lev2'];
					}
				}	
				if(!in_array($lev3,$level3_array))
				{
					if($lev3 != NULL)
					{
						$level_id = $row['lev3_id'];
						$step_lev = $step_lev_inc + 3;
						echo "
								<tr>
									<td width=6%>Level ($step_lev)</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td colspan=2><a href=# onclick=module(".$level_id.",3,$level) >".$lev3."</a></td>
								</tr>";
						$level3_array[] = $row['lev3'];
					}
				}
				if(!in_array($lev4,$level4_array))
				{
					if($lev4 != NULL)
					{
						$level_id = $row['lev4_id'];
						$step_lev = $step_lev_inc + 4;
						echo "
								<tr>
									<td width=6%>Level ($step_lev)</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td><a href=# onclick=module(".$level_id.",4,$level) >".$lev4."</a></td>
								</tr>";	
						$level4_array[] = $row['lev4'];
					}
				}
			}
		}
	
		echo "</table>";
	}
	
	
}
function get_username($id){
	$sql = query_execute_sqli("select * from users where id_user='$id'");
	while($row = mysqli_fetch_array($sql)){
		$user_info = $row['username']."&nbsp;(".$row['f_name']."&nbsp;".$row['l_name'].")";
	}
	return $user_info;
}
?>