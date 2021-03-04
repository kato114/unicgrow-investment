<?php


/*$query = query_execute_sqli("select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$point = $row['point'];
	$level = $row['parent_limit'];
	$real_par_point = $row['real_parent_point'];
	$month_point = $row['month_point'];
	$point_value = $row['point_value'];
	
	//$user_fees
	$fees = $row['registration_fees'];
	
	//minimum transfer value for request
	$min_transfer = $row['min_transfer'];
}*/

// Starting taer and month of the company



$binary = 1;
$real_par_income = 20;
$virtual_par_income = 10;
$network_income = 5;
$virtual_parent_condition = 1;

// registration types
$type[0] = "A";
$type[1] = "B";
$type[2] = "C";
$type[3] = "D";

//product id for registration

$product_id[1] = "reg";

//income

$income[1] = 20;  //survey income
$income[2] = 30;  //direct member income

//income type
$income_type[1] = 1; // survey income type
$income_type[2] = 2;  // irect member income type
$income_type[3] = 3;  //  binary income

// binary income

$binary_income[0][0] = 1;
$binary_income[0][1] = 10;
$binary_income[1][0] = 2;
$binary_income[1][1] = 20;
$binary_income[2][0] = 3;
$binary_income[2][1] = 30;
$binary_income[3][0] = 5;
$binary_income[3][1] = 50;
$binary_income[4][0] = 10;
$binary_income[4][1] = 100;
$binary_income[5][0] = 20;
$binary_income[5][1] = 200;