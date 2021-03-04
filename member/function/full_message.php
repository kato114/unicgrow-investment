<?php
$full_message = NULL;
$m = explode(" ", $db_msg);
$cnt = count($m );
	for($i = 0; $i < $cnt; $i++)
	{
		$c = count($m[$i]);
		if($m[$i][0] == '#')
		{
			$f =split('#', $m[$i]);
			$full_message .=  $$f[1]." ";
		}
		else {
		$full_message .=$m[$i]." ";
		}
	}


/*$count = count($db_msg);
for($j = 0; $j < $count; $j++)
{
	$m = explode(" ", $db_msg[$j][$i]);
	$cnt = count($m );
	for($i = 0; $i < $cnt; $i++)
	{
		$c = count($m[$i]);
		if($m[$i][0] == '#')
		{
			$f =split('#', $m[$i]);
			$full_message[$j] .=  $$f[1]." ";
		}
		else {
		$full_message[$j] .=$m[$i]." ";
		}
	}
} */
?>
