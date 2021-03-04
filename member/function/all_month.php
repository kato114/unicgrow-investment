<?php

function all_month($start_year,$star_month)
{
while($start_month != 13)
	{
		$curr_year = date('Y');
		$curr_month = date('m');
		$curr_date = $curr_year."-".intval($curr_month); 
		$all_d = $start_year."-".$start_month;
		$all_date[] = $all_d; 
		if($all_d == $curr_date) { break; }
		$start_month= $start_month+01;
		if($start_month == 13) { $start_year++; $start_month = 01; }
	}
	return $all_date;
}

