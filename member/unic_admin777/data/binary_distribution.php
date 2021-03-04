<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/pair_point_income.php");
query_execute_sqli("update income_process set mode = 1 ");
	
pair_point_income($systems_date);
query_execute_sqli("update income_process set mode = 0 ");
?>