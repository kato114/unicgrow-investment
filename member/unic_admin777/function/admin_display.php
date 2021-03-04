<?php
function display($pos,$page,$img,$username,$parent_username)
{
print "
<div id=\"content\" class=\"narrowcolumn\">
<div class=\"comment odd alt thread-odd thread-alt depth-1\" style=\"width:90%\">
<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=400>
<tr>
<th align=\"center\" align=\"center\" colspan=\"8\" height=\"40\" width=\"100%\">
<div class=\"comment odd alt thread-odd thread-alt depth-2\" style=\"width:150px; height:100px;\" >
<img src=\"../images/new/$img[0].png\" height=75><br>
<div id=simpleTooltip style=\"height: 20px\">
<a href=\"$page&id=$pos[0]\">
$username[0]
</a>
<br>
($parent_username[0])
</div>
</div>
</th>
</tr>

<tr>";
	
	th_display_level(2,1,$username,$pos,$parent_username,$img,$page);
	th_display_level(2,2,$username,$pos,$parent_username,$img,$page);
print "</tr><tr>";

	th_display_level(3,3,$username,$pos,$parent_username,$img,$page);
	th_display_level(3,4,$username,$pos,$parent_username,$img,$page);
	th_display_level(3,5,$username,$pos,$parent_username,$img,$page);
	th_display_level(3,6,$username,$pos,$parent_username,$img,$page);
	
print "</tr><tr>";

th_display(7,$username,$pos,$parent_username,$img,$page);
th_display(8,$username,$pos,$parent_username,$img,$page);
th_display(9,$username,$pos,$parent_username,$img,$page);
th_display(10,$username,$pos,$parent_username,$img,$page);
th_display(11,$username,$pos,$parent_username,$img,$page);
th_display(12,$username,$pos,$parent_username,$img,$page);
th_display(13,$username,$pos,$parent_username,$img,$page);
th_display(14,$username,$pos,$parent_username,$img,$page);

print"</tr>
	</table></div></div>";
}	


function th_display($val,$username,$pos,$parent_username,$img,$page)
{
if($username[$val]=='0' or $username[$val]=='')
	{
	print "<th align=\"center\" height=\"100\" width=\"100\" style=\"padding-right: 5px\">
	<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:60px; height:50px;\" >
	<img src=\"../images/new/$img[$val].png\" height=70>
	</div>
	</div>
	</th>";	
	}
else
	{
	print "<th align=\"center\" height=\"100\" width=\"100\" style=\"padding-right: 5px\">
	<div class=\"comment odd alt thread-odd thread-alt depth-3\" style=\"width:60px; height:50px;\" >
	<img src=\"../images/new/$img[$val].png\" height=20>
	<div id=simpleTooltip style=\"height: 18px\"><a href=\"$page&id=$pos[$val]\">
	$username[$val]</a><br>$parent_username[$val]
	</div>
	</div>
	</th>
	";
	}
}

function th_display_level($level,$val,$username,$pos,$parent_username,$img,$page)
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
	if($username[$val]=='0' or $username[$val]=='')
	{
	print "<th align=\"center\" colspan=\"$colspan\" height=\"40\" width=\"$td_width%\">
	<div class=\"comment odd alt thread-odd thread-alt depth-$depth\" style=\"width:$div_width; height:80px;\" >
	<img src=\"../images/new/$img[$val].png\" height=$img_height>
	</div>
	</div>
	</th>";	
	}
	else
	{
	
		print "<th align=\"center\" colspan=\"$colspan\" height=\"40\" width=\"$td_width%\">
		<div class=\"comment odd alt thread-odd thread-alt depth-$depth\" style=\"width:$div_width; height:80px;\" >
		<img src=\"../images/new/$img[$val].png\" height=$img_height>
		<div id=simpleTooltip style=\"height: 20px\"><a href=\"$page&id=$pos[$val]\">
		$username[$val]</a><br>($parent_username[$val])
		</div>
		</div>
		</th>";
	}
}


function get_img($type,$gender)	
{
	if($type == 'B' && $gender == male) { $imges = "a"; }
	if($type == 'B' && $gender == female) { $imges = "b"; }
	if($type != 'C' && $gender == male) { $imges = "c"; }
	if($type != 'C' && $gender == female) { $imges = "d"; }
	return $imges;
}		
	
function get_username($parent)
{
	if($parent != 0) {
		$query = query_execute_sqli("select * from users where	id_user = $parent ");
		while($row = mysqli_fetch_array($query))
		{
			$username = $row['username'];
			return $username;	
		}
	}
}

?> 		
