<?php

function display_member($u_name,$page)
{
	$q = query_execute_sqli("select * from users where username = '$u_name' ");
	$num = mysqli_num_rows($q);
	if($num == 0)
	{
		echo "<h3>Please Enter right User Name!</h3>"; 
	}
	else
	{
		while($row = mysqli_fetch_array($q))
		{
			$i = 0;
			$id = $row['id_user'];
			$id_par = $row['parent_id'];
			$gender = $row['gender'];
				$type = $row['type'];
					if($type == 'B' && $gender == 'male') { $img[$i] = "a"; }
					if($type == 'B' && $gender == 'female') { $img[$i] = "b"; }
					if($type != 'C' && $gender == 'male') { $img[$i] = "c"; }
					if($type != 'C' && $gender == 'female') { $img[$i] = "d"; }
			$id_par_username = get_username($id_par);
			
		}
		
		$query = query_execute_sqli("SELECT * FROM users WHERE parent_id = '$id' ");
		$num = mysqli_num_rows($query);
		if($num == 0)
		{
			echo "User Name $u_name has no child!"; 
		}
		else 
		{
			$i =1;
			while($row = mysqli_fetch_array($query))
			{
	
				$parent = $row['real_parent'];
				$pr_username[$i] = get_username($parent);
				//$p_username[]	= $pr_username;
				$gender = $row['gender'];
				$type = $row['type'];
					if($type == 'B' && $gender == 'male') { $img[$i] = "a"; }
					if($type == 'B' && $gender == 'female') { $img[$i] = "b"; }
					if($type != 'C' && $gender == 'male') { $img[$i] = "c"; }
					if($type != 'C' && $gender == 'female') { $img[$i] = "d"; }
				$user_name[$i] = $row['username'];
				$i++;				
			}
			
			 
			print "
				<div id=\"content\" class=\"narrowcolumn\">
				<div class=\"comment odd alt thread-odd thread-alt depth-1\" style=\"width:90%\">
					<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=400>
							<tr>
							<th align=\"center\" align=\"center\" colspan=\"8\" height=\"40\" width=\"100%\">
				<div class=\"comment odd alt thread-odd thread-alt depth-2\" style=\"width:150px; height:100px;\" >
				<img src=\"images/new/$img[0].png\" height=75><br>
				<div id=simpleTooltip style=\"height: 20px\">
				$u_name<br />($id_par_username)
				</div>
				</div>
				</th>
				</tr>
				<tr>
				
				<tr>
				<th align=\"center\" colspan=\"4\" height=\"40\" width=\"50%\">
				<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:100px; height:80px\">
				<img src=\"images/new/$img[1].png\" height=50>
				<div id=simpleTooltip style=\"height: 20px\">
				$user_name[1]<br>($pr_username[1])
				</div>
				</th>
								<th align=\"center\" colspan=\"4\" height=\"40\" width=\"50%\">
				<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:100px; height:80px\">
				<img src=\"images/new/$img[2].png\" height=50>
				<div id=simpleTooltip style=\"height: 20px\">
				$user_name[2]<br>($pr_username[2])
				</div>
				</div>
				</th>
				</tr></table></div></div>";

				
		}
		
	}		
}

	
function get_username($parent)
{
	$query = query_execute_sqli("select * from users where	id_user = $parent ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;	
	}
}
	

function display($pos,$page,$img,$user_name,$parent_u_name)
{
print "
<div id=\"content\" class=\"narrowcolumn\">
<div class=\"comment odd alt thread-odd thread-alt depth-1\" style=\"width:90%\">
	<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=400>
			<tr>
			<th align=\"center\" align=\"center\" colspan=\"8\" height=\"40\" width=\"100%\">
<div class=\"comment odd alt thread-odd thread-alt depth-2\" style=\"width:150px; height:100px;\" >
<img src=\"images/new/$img[0].png\" height=75><br>
<div id=simpleTooltip style=\"height: 20px\">
<a href=\"$page&id=$pos[0]\">
$user_name[0]
</a>
<br>
($parent_u_name[0])
</div>
</div>
</th>
</tr>

<tr>";
	
	th_display_level(2,1,$user_name,$pos,$parent_u_name,$img,$page);
	th_display_level(2,2,$user_name,$pos,$parent_u_name,$img,$page);
print "</tr><tr>";

	th_display_level(3,3,$user_name,$pos,$parent_u_name,$img,$page);
	th_display_level(3,4,$user_name,$pos,$parent_u_name,$img,$page);
	th_display_level(3,5,$user_name,$pos,$parent_u_name,$img,$page);
	th_display_level(3,6,$user_name,$pos,$parent_u_name,$img,$page);
	
print"</tr>
	</table></div></div>";
}	


function th_display($val,$user_name,$pos,$parent_u_name,$img,$page)
{
if($user_name[$val]=='0' or $user_name[$val]=='')
	{
	print "<th align=\"center\" height=\"100\" width=\"100\" style=\"padding-right: 5px\">
	<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:60px; height:50px;\" >
	<img src=\"images/new/$img[$val].png\" height=60>
	</div>
	</div>
	</th>";	
	}
else
	{
	print "<th align=\"center\" height=\"100\" width=\"100\" style=\"padding-right: 5px\">
	<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:60px; height:50px;\" >
	<img src=\"images/new/$img[$val].png\" height=20>
	<div id=simpleTooltip style=\"height: 18px\"><a href=\"$page&id=$pos[$val]\">
	$user_name[$val]</a><br>$parent_u_name[$val]
	</div>
	</div>
	</th>
	";
	}
}

function th_display_level($level,$val,$user_name,$pos,$parent_u_name,$img,$page)
{
	if($level == 2)
	{
		$td_width = 50;
		$div_width = 100;
		$depth = 3;
		$img_height=50;
		$colspan=4;
	}
	if($level == 3)
	{
		$td_width = 25;
		$div_width = 80;
		$depth = 2;
		$img_height=40;
		$colspan=2;
	}
	if($user_name[$val]=='0' or $user_name[$val]=='')
	{
	print "<th align=\"center\" colspan=\"$colspan\" height=\"40\" width=\"$td_width%\">
	<div class=\"comment odd alt thread-odd thread-alt depth-$depth\" style=\"width:$div_width; height:80px;\" >
	<img src=\"images/new/e.png\" height=70>
	</div>
	</div>
	</th>";	
	}
	else
	{
		print "<th align=\"center\" colspan=\"$colspan\" height=\"40\" width=\"$td_width%\">
		<div class=\"comment odd alt thread-odd thread-alt depth-$depth\" style=\"width:$div_width; height:80px;\" >
		<img src=\"images/new/$img[$val].png\" height=$img_height>
		<div id=simpleTooltip style=\"height: 20px\"><a href=\"$page&id=$pos[$val]\">
		$user_name[$val]</a><br>($parent_u_name[$val])
		</div>
		</div>
		</th>";
	}
}